<?php
$cities = ApartmentCity::getCityArray(false, 2);

if ($model->city_id) {
    $city = $model->city_id;
} else {
    $city_keys = array_keys($cities);
    $city = isset($city_keys[0]) ? $city_keys[0] : 0;
}

?>
<?php if (issetModule('metroStations')) { ?>
    <?php $metros = MetroStations::getMetrosArray($city, 0); ?>
    <div class="form-group" id="locationCity"<?php if (param('allowCustomCities') && $model->customCity) {
        echo ' style="display: none;"';
    } ?>>
        <?php echo CHtml::activeLabelEx($model, 'city_id'); ?>
        <?php
        echo CHtml::activeDropDownList($model, 'city_id', $cities, array(
                'id' => 'ap_city',
                'ajax' => array(
                    'type' => 'GET',
                    'url' => $this->createUrl('/metroStations/main/getMetroStations'),
                    'data' => 'js:"city="+$("#ap_city").val()+"&type=0"',
                    'dataType' => 'json',
                    'success' => 'function(result){
                           if (result.dropdownMetro) {
                               $("#metro-block-apartment").show();
                               $("#ap_metro").html(result.dropdownMetro);
                               $("#ap_metro").trigger("chosen:updated");
                           }
                           else {
                               $("#ap_metro").html("");
                               $("#ap_metro").trigger("chosen:updated");
                               $("#metro-block-apartment").hide();
                           }
                       }'
                ),
                'class' => 'span3 form-control'
            )
        );

        ?>
        <?php echo CHtml::error($model, 'city_id'); ?>
        <?php
        if (param('allowCustomCities')) {
            echo CHtml::link(tt('Custom city', 'apartments'), '#', array('onclick' => "switchCity(); return false;"));
        }
        ?>
    </div>
    <?php if (param('allowCustomCities')): ?>
        <div class="form-group" id="customCity"<?php if (!$model->customCity) {
            echo ' style="display: none;"';
        } ?>>
            <?php echo $form->labelEx($model, 'customCity'); ?>
            <?php echo $form->textField($model, 'customCity', array('class' => 'span3 form-control')); ?>
            <?php echo $form->error($model, 'customCity'); ?>
            <?php echo CHtml::link(tt('City from list', 'apartments'), '#', array('onclick' => "switchCity(); return false;")); ?>
            <?php echo CHtml::hiddenField('isCustomCity', ($model->customCity) ? 1 : 0, array('id' => 'isCustomCity')); ?>
        </div>
    <?php endif; ?>
    <div class="form-group" id="metro-block-apartment"
         style="display: <?php echo (!empty($metros)) ? 'block;' : 'none;'; ?>">
        <?php echo CHtml::activeLabelEx($model, 'metroStations'); ?>
        <div>
            <?php
            echo Chosen::multiSelect(get_class($model) . '[metroStations]', $model->metroStations, $metros, array('id' => 'ap_metro', 'class' => 'width500 form-control', 'data-placeholder' => tt('Select metro stations', 'metroStations'))
            );
            if ($callFrom != 'guestAdModule') {
                echo "<script>$('#ap_metro').chosen();</script>";
            }
            ?>
        </div>
        <?php echo CHtml::error($model, 'metroStations'); ?>
    </div>
    <div class="clear"></div>
<?php } else { ?>
    <div class="form-group" id="locationCity"<?php if (param('allowCustomCities') && $model->customCity) {
        echo ' style="display: none;"';
    } ?>>
        <?php echo CHtml::activeLabelEx($model, 'city_id'); ?>
        <?php echo CHtml::activeDropDownList($model, 'city_id', $cities, array('class' => 'span3 form-control')); ?>
        <?php echo CHtml::error($model, 'city_id'); ?>
        <?php
        if (param('allowCustomCities')) {
            echo CHtml::link(tt('Custom city', 'apartments'), '#', array('onclick' => "switchCity(); return false;"));
        }
        ?>
    </div>
    <?php if (param('allowCustomCities')) { ?>
        <div class="form-group" id="customCity"<?php if (!$model->customCity) {
            echo ' style="display: none;"';
        } ?>>
            <?php echo $form->labelEx($model, 'customCity'); ?>
            <?php echo $form->textField($model, 'customCity', array('class' => 'span3 form-control')); ?>
            <?php echo $form->error($model, 'customCity'); ?>
            <?php echo CHtml::link(tt('City from list', 'apartments'), '#', array('onclick' => "switchCity(); return false;")); ?>
            <?php echo CHtml::hiddenField('isCustomCity', ($model->customCity) ? 1 : 0, array('id' => 'isCustomCity')); ?>
        </div>
    <?php } ?>
<?php } ?>
