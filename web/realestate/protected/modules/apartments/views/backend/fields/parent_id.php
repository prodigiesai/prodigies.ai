<?php
if (Yii::app()->user->id && $model->objType && $model->objType->parent_id) {
    if (HApartment::checkIssetParentList($model->objType->parent_id)) {
        echo $form->labelEx($model, 'parent_id');

        $this->widget('CAutoComplete', array(
            'model' => $model,
            'attribute' => 'parent_id_autocomplete',
            'url' => array('/apartments/main/getParentObject', 'objTypeID' => $model->objType->parent_id),
            'multiple' => true,
            'htmlOptions' => [
                'class' => 'span5 form-control',
                'onblur' => 'checkFillParentId($(this));',
                'onkeyup' => 'checkFillParentId($(this));',
                'id' => 'Apartment_parent_id_autocomplete',
                'style' => 'display: table-cell;',
            ],
            'minChars' => 0,
            'matchCase' => false,
            'methodChain' => ".result(function(event,item){ oreSetForParent(item[1]) })",
        ));

        $displayClear = $model->parent_id ? '' : 'style="display: none;"';

        echo '<span id="ore-parent-clear" '.$displayClear.'>&nbsp;';
        echo CHtml::link(tc('Clear'), 'javascript:;', ['onclick' => 'oreParentIdClear()']);
        echo '</span>';

        ?>
        <div><span class="label label-info"><?php echo tc('enter initial letters'); ?></span></div>
        <?php
        echo $form->error($model, 'parent_id');

        echo CHtml::hiddenField(get_class($model) . '[parent_id]', $model->parent_id, array('id' => 'Apartment_parent_id'));
    }

    $urlUploadLocation = CJavaScript::encode(Yii::app()->createUrl('/apartments/main/loadLocationData'));
    $issetModuleLocation = issetModule('location') ? 1 : 0;
    $objTypeId = $model->obj_type_id;

    $script = <<< JS
    
    var issetModuleLocation = $issetModuleLocation;
    
    function checkFillParentId(elem) {
        if (elem.val().length < 1) {
            $("#Apartment_parent_id").val('');
            $("#Apartment_parent_id_autocomplete").val('');
        }
    }
    
    function oreParentIdClear() {
        $('#Apartment_parent_id').val('');
        $('#ore-parent-clear').hide();
        $('#Apartment_parent_id_autocomplete').val('');
        
        $('div.ore-lang-field-address').show();
        
        $.ajax({
            url: $urlUploadLocation,
            data: {
                id: 0,
                obj_type_id: $objTypeId
            },
            dataType: 'json',
            type: 'post',
            success: function(data) {
                if(data.status == 'ok'){
                    $('#ore-form-location').replaceWith(data.locationHtml);
                }
            }
        });
    }
    
    function oreSetForParent(id) {
        
        $('#Apartment_parent_id').val(id);
        
        $('#ore-form-location').html('');
        $('div.ore-lang-field-address').hide();
        $('#ore-parent-clear').show();
        
        $.ajax({
            url: $urlUploadLocation,
            data: {
                id: id,
                obj_type_id: $objTypeId
            },
            dataType: 'json',
            type: 'post',
            success: function(data) {
                if(data.status == 'ok'){
                    $('#ore-form-location').replaceWith(data.locationHtml);
                }
            }
        });
    }
JS;

    Yii::app()->clientScript->registerScript('ore-parent-js', $script, CClientScript::POS_END);

    echo '<br/>';

}
?>


