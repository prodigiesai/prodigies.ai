<?php
$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage blacklist', 'badwords'), array('admin')),
    AdminLteHelper::getAddMenuLink(tt('Add word to the blacklist', 'badwords'), array('create')),
    AdminLteHelper::getDeleteMenuLink(tt('Delete a word', 'badwords'), '#', array(
        'linkOptions' => array(
            'submit' => array('delete', 'id' => $model->id),
            'confirm' => tc('Are you sure you want to delete this item?'),
            'csrf' => true,
        ),
    )),
);

$this->adminTitle = tt('Edit a word', 'badwords');

?>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>