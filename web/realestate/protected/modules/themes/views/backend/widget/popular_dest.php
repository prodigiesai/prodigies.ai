<?php
$this->pageTitle = Yii::app()->name . ' - ' . tt('Widget "Popular destinations"');

$this->menu = array(
    //array('label' => tt('Manage themes'), 'url' => array('admin')),
    AdminLteHelper::getBackMenuLink(tt('Manage themes'), array('/themes/backend/main/admin')),
    AdminLteHelper::getBackMenuLink(tt('Edit theme') . ' "' . ucfirst($model->title) . '"', array('/themes/backend/main/update', 'id' => $model->id)),
);

$this->adminTitle = tt('Widget "Popular destinations"');

?>

<div class="col-md-12">

    <div class="panel panel-default">
        <div class="panel-heading"><?= tt('Widget "Popular destinations"') ?></div>

        <div class="panel-body">
            <?php require '_popular_dest_form.php' ?>
        </div>
    </div>

</div>

<div class="clearfix"></div>

