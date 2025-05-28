<?php
$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage menu'), array('admin')),
    AdminLteHelper::getAddMenuLink(tt('Add menu'), array('create')),
);

$this->adminTitle = tt('Edit menu') . ': <i>' . $model->getStrByLang('name') . '</i>';

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>