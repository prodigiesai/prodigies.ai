<?php if (issetModule('metroStations')) { ?>

    <?php $metros = MetroStations::getMetrosArray($city, 0); ?>

    <div class="form-group" id="locationCity"<?php if (param('allowCustomCities') && $model->customCity) {
        echo ' style="display: none;"';
    } ?>>
        <?php echo CHtml::activeLabelEx($model, 'loc_city'); ?>
        <?php
        echo Select2::activeDropDownList($model, 'loc_city', $cities, array(
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
                       } else {
                           $("#ap_metro").html("");
                           $("#ap_metro").trigger("chosen:updated");
                           $("#metro-block-apartment").hide();
                       }
                       
                       $("#ap_district").html(result.districts);
                       $("#ap_district").change();
                   }'
                ),
                'class' => 'span3 form-control'
            )
        );

        ?>
        <?php echo CHtml::error($model, 'loc_city'); ?>
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
    <?php }  ?>

    <div class="form-group" id="locationDistrict">
        <?php echo CHtml::activeLabelEx($model, 'loc_district'); ?>
        <?php
        echo Select2::activeDropDownList($model, 'loc_district', $districts, array(
                'id' => 'ap_district',
                'ajax' => array(
                    'type' => 'GET',
                    'url' => $this->createUrl('/location/main/getNears'),
                    'data' => 'js:"district="+$("#ap_district").val()',
                    'success' => 'function(result){
                        $("#ap_near").html(result);
                        $("#ap_near").select2().trigger("change");
                    }',
                ),
                'class' => 'span3 form-control'
            )
        );
        ?>
        <?php echo CHtml::error($model, 'loc_district'); ?>
    </div>

    <div class="form-group" id="metro-block-apartment"
         style="display: <?php echo (!empty($metros)) ? 'block;' : 'none;'; ?>">
        <?php echo CHtml::activeLabelEx($model, 'metroStations'); ?>
        <div>
            <?php
            echo Chosen::multiSelect(get_class($model) . '[metroStations]', $model->metroStations, $metros, array('id' => 'ap_metro', 'class' => 'width500 form-control', 'data-placeholder' => tt('Select metro stations', 'metroStations'))
            );
            if ($callFrom != 'guestAdModule')
                echo "<script>$('#ap_metro').chosen();</script>";

            ?>
        </div>
        <?php echo CHtml::error($model, 'metroStations'); ?>
    </div>
<?php } else { ?>
    <div class="form-group" id="locationCity"<?php if (param('allowCustomCities') && $model->customCity) {
        echo ' style="display: none;"';
    } ?>>
        <?php echo CHtml::activeLabelEx($model, 'loc_city'); ?>
        <?php /*echo CHtml::activeDropDownList($model, 'loc_city', $cities, array('id' => 'ap_city', 'class' => 'span3 form-control'));*/ ?>
        <?php
        echo Select2::activeDropDownList($model, 'loc_city', $cities, array(
                'id' => 'ap_city',
                'ajax' => array(
                    'type' => 'GET',
                    'url' => $this->createUrl('/location/main/getDistricts'),
                    'data' => 'js:"city="+$("#ap_city").val()',
                    'success' => 'function(result){
                        $("#ap_district").html(result);
                        $("#ap_district").select2().trigger("change");
                    }',
                ),
                'class' => 'span3 form-control'
            )
        );
        ?>
        <?php echo CHtml::error($model, 'loc_city'); ?>

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

    <div class="form-group" id="locationDistrict">
        <?php echo CHtml::activeLabelEx($model, 'loc_district'); ?>
        <?php
        echo Select2::activeDropDownList($model, 'loc_district', $districts, array(
                'id' => 'ap_district',
                'ajax' => array(
                    'type' => 'GET',
                    'url' => $this->createUrl('/location/main/getNears'),
                    'data' => 'js:"district="+$("#ap_district").val()',
                    'success' => 'function(result){
                        $("#ap_near").html(result);
                        $("#ap_near").select2().trigger("change");
                    }',
                ),
                'class' => 'span3 form-control'
            )
        );
        ?>
        <?php echo CHtml::error($model, 'loc_district'); ?>
    </div>

<?php } ?>