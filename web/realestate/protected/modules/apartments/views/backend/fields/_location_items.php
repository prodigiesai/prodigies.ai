<?php
/* @var $model \Apartment */

$countries = Country::getCountriesArray(); ?>
<div class="form-group">
    <?php echo CHtml::activeLabelEx($model, 'loc_country'); ?>
    <?php
    echo Select2::activeDropDownList($model, 'loc_country', $countries, array(
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
    <?php echo CHtml::error($model, 'loc_country'); ?>
</div>

<?php
//при создании города узнаём id первой в дропдауне страны
$country_keys = array_keys($countries);

if ($model->loc_country && in_array($model->loc_country, $country_keys)) {
    $country = $model->loc_country;
} else {
    $country = isset($country_keys[0]) ? $country_keys[0] : 0;
}

$regions = Region::getRegionsArray($country);
$region_keys = array_keys($regions);

if ($model->loc_region && in_array($model->loc_region, $region_keys)) {
    $region = $model->loc_region;
} else {
    $region = isset($region_keys[0]) ? $region_keys[0] : 0;
}

$cities = City::getCitiesArray($region, 0, 2);

if ($model->loc_city) {
    $city = $model->loc_city;
} else {
    $city_keys = array_keys($cities);
    $city = isset($city_keys[0]) ? $city_keys[0] : 0;
}

$districts = District::getDistrictArray($city, 2);

if ($model->loc_district) {
    $districtId = $model->loc_district;
} else {
    $districtId = isset($districts[0]) ? $districts[0] : 0;
}

?>

<div class="form-group">
    <?php echo CHtml::activeLabelEx($model, 'loc_region'); ?>
    <?php
    echo Select2::activeDropDownList($model, 'loc_region', $regions, array(
            'id' => 'ap_region',
            'ajax' => array(
                'type' => 'GET',
                'url' => $this->createUrl('/location/main/getCities'),
                'data' => 'js:"region="+$("#ap_region").val()',
                'success' => 'function(result){
                    $("#ap_city").html(result);
                    $("#ap_city").select2().trigger("change");
                    $("#ap_city").change();
                }',
            ),
            'class' => 'span3 form-control'
        )
    );

    ?>
    <?php echo CHtml::error($model, 'loc_region'); ?>
</div>

<?php include_once '_location_items_cd_metro.php' ?>