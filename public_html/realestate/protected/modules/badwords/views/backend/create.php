<?php
$this->adminTitle = tt('Add word to the blacklist', 'badwords');

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage blacklist', 'badwords'), array('admin')),
);

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>