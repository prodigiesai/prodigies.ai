<?php
/* @var $model Apartment */

if($this instanceof \application\modules\apartments\widgets\SearchFormWidget){
    $types = $this->getTypeList();
} else {
    $types = HApartment::getTypesArray(false);
}
?>

<div class="form-group">
    <div class=""><?php echo tc('Type') ?>:</div>
    <?php
    echo CHtml::dropDownList('Apartment[type]', $model->type, $types, array(
        'empty' => '',
        'id' => 'type_f',
        'class' => 'form-control'
    ));
    ?>
</div>