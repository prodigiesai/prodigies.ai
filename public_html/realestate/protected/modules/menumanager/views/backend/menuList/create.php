<?php
$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage menu'), array('admin')),
);
$this->adminTitle = tt('Add menu');

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>