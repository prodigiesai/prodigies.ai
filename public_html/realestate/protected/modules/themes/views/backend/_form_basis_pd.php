<?php
$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css');

$cs->registerScriptFile(Yii::app()->request->baseUrl . '/common/js/jquery.ui.touch-punch.min.js');

?>

<?php $citiesString = $model->getCitiesString() ?>

    <ul id="sortable1" class="connectedSortable well"
        id="sortable1" <?php echo !$citiesString ? 'style="display: none;"' : '' ?>>
        <?= $citiesString ?>
    </ul>

<?php if (issetModule('location')) { ?>

    <?php
    $countries = Country::getCountriesArray();

    //при создании города узнаём id первой в дропдауне страны
    $country_keys = array_keys($countries);

    if ($dataModel->loc_country && in_array($dataModel->loc_country, $country_keys)) {
        $country = $dataModel->loc_country;
    } else {
        $country = isset($country_keys[0]) ? $country_keys[0] : 0;
    }

    $regions = Region::getRegionsArray($country);

    $region_keys = array_keys($regions);
    if ($dataModel->loc_region && in_array($dataModel->loc_region, $region_keys)) {
        $region = $dataModel->loc_region;
    } else {
        $region = isset($region_keys[0]) ? $region_keys[0] : 0;
    }

    $cities = City::getCitiesArray($region, 0, 2);

    if ($dataModel->loc_city) {
        $city = $dataModel->loc_city;
    } else {
        $city_keys = array_keys($cities);
        $city = isset($city_keys[0]) ? $city_keys[0] : 0;
    }

    ?>

    <div class="form-group">
        <?php echo CHtml::activeLabelEx($dataModel, 'loc_country'); ?>
        <?php
        echo Select2::activeDropDownList($dataModel, 'loc_country', $countries, array(
                'id' => 'ap_country',
                'ajax' => array(
                    'type' => 'GET', //request type
                    'url' => $this->createUrl('/location/main/getRegions'), //url to call.
                    'data' => 'js:"country="+$("#ap_country").val()',
                    'success' => 'function(result){
						$("#ap_region").html(result);
						$("#ap_region").change();
						$("#ap_region").select2().trigger("change");
					}'
                ),
                'class' => 'span3 form-control'
            )
        );

        ?>
        <?php echo CHtml::error($dataModel, 'loc_country'); ?>
    </div>

    <div class="form-group">
        <?php echo CHtml::activeLabelEx($dataModel, 'loc_region'); ?>
        <?php
        echo Select2::activeDropDownList($dataModel, 'loc_region', $regions, array('id' => 'ap_region',
                'ajax' => array(
                    'type' => 'GET', //request type
                    'url' => $this->createUrl('/location/main/getCities'), //url to call.
                    'data' => 'js:"region="+$("#ap_region").val()',
                    'success' => 'function(result){
						$("#ap_city").html(result);$("#ap_city").select2().trigger("change");' . ((issetModule('metroStations')) ? '$("#ap_city").change()' : '') .
                        '}'
                ),
                'class' => 'span3 form-control'
            )
        );

        ?>
        <?php echo CHtml::error($dataModel, 'loc_region'); ?>
    </div>

    <div class="form-group" id="locationCity">
        <?php echo CHtml::activeLabelEx($dataModel, 'loc_city'); ?>
        <?php echo CHtml::activeDropDownList($dataModel, 'loc_city', $cities, array('id' => 'ap_city', 'class' => 'span3 form-control')); ?>
        <?php echo CHtml::link(tt('Add city'), 'javascript:;', array('class' => 'btn btn-primary', 'id' => 'pd_add_city')) ?>
        <?php echo CHtml::error($dataModel, 'loc_city'); ?>
    </div>

<?php } else { ?>
    <?php
    $cities = ApartmentCity::getCityArray(false, 2);

    if ($dataModel->city_id) {
        $city = $dataModel->city_id;
    } else {
        $city_keys = array_keys($cities);
        $city = isset($city_keys[0]) ? $city_keys[0] : 0;
    }

    ?>
    <div class="form-group" id="locationCity">
        <?php echo CHtml::activeLabelEx($dataModel, 'city_id'); ?>
        <?php echo CHtml::activeDropDownList($dataModel, 'city_id', $cities, array('class' => 'span3 form-control')); ?>
        <?php echo CHtml::link(tt('Add city'), 'javascript:;', array('class' => 'btn btn-primary', 'id' => 'pd_add_city')) ?>
        <?php echo CHtml::error($dataModel, 'city_id'); ?>
    </div>

<?php } ?>

    <br/>

<?php
$successMsg = tc('Success');
$addCityUrl = Yii::app()->createUrl('/themes/backend/main/ajaxPdAddCity');
$saveSortUrl = Yii::app()->createUrl('/themes/backend/main/ajaxPdSaveSort');

$js = <<< JS

function addCity() {
    var data = {
        city_id: $('#ap_city').val()
    };
    
    $.ajax({
        url: '{$addCityUrl}',
        data: data,
        dataType: 'json',
        success: function(data) {
           if(data.status == 'ok'){
               showCities(data.cities);
               message(data.msg);
           } else {
               error(data.msg);
           }
        }
    });  
}

function pdDelCity(url) {
    $.ajax({
        url: url,
        dataType: 'json',
        success: function(data) {
           if(data.status == 'ok'){
               showCities(data.cities);
               message(data.msg);
           } else {
               error(data.msg);
           }
        }
    }); 
    return false;
}

function showCities(cities) {
    if(cities){
        $('#sortable1').html(cities).show();
    } else {
        $('#sortable1').hide();
    }
    pdSort();
}

function pdSort(){
    $("#sortable1").sortable({
        connectWith: ".connectedSortable",
        placeholder: "ui-state-highlight",
        items: "li:not(.ui-state-disabled)",
        update: function( event, ui ) {
           if (this === ui.item.parent()[0]) {
               var sort = $('#sortable1').sortable('toArray', { attribute: 'key' });
        
               if(tmpSort != sort){
                   saveSort(sort);
                   tmpSort = sort;
               }
           }
       }
    });
}

$(function() {
    $("#ap_city").select2().trigger("change");
    $('#pd_add_city').on('click', addCity);
    pdSort();
});

var tmpSort = [];

function saveSort(sort) {
        var sort = $('#sortable1').sortable('toArray', {attribute: 'key'});

        if (tmpSort == sort) {
            message('{$successMsg}');
            return false;
        }

        $.ajax({
            url: '{$saveSortUrl}',
            dataType: 'json',
            type: 'get',
            data: {
                sort: sort,
            },
            success: function (data) {
                if (data.status == 'ok') {
                    message(data.msg);
                    tmpSort = sort;
                } else {
                    error(data.msg);
                }
            }
        });
    }
JS;


Yii::app()->clientScript->registerScript('themes-js', $js, CClientScript::POS_END)

?>