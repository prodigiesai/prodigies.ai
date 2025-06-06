<?php
/* @var $model Apartment */

if($this instanceof \application\modules\apartments\widgets\SearchFormWidget){
    $objTypes = $this->getObjectTypeList();
} else {
    $objTypes = CArray::merge(array(0 => ''), ApartmentObjType::getList());
}
?>


<div class="form-group">
    <div class=""><?php echo tc('Property type') ?>:</div>
    <?php echo CHtml::dropDownList('Apartment[obj_type_id]', $model->obj_type_id, $objTypes, array('id' => 'obj_type', 'class' => 'form-control')); ?>
</div>