<?php
$this->pageTitle = Yii::app()->name . ' - ' . tt('Edit theme');

$this->menu = array(
    //array('label' => tt('Manage themes'), 'url' => array('admin')),
    AdminLteHelper::getBackMenuLink(tt('Manage themes'), array('admin')),
);
$this->adminTitle = tt('Edit theme') . ' "' . ucfirst($model->title) . '"';

?>

<div class="form">

    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => 'Slider-form',
        'enableClientValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'well form-disable-button-after-submit'),
    ));

    ?>

    <?php //echo $form->errorSummary($model); ?>

    <?php
    $formFile = (dirname(__FILE__)) . '/_form_' . $model->title . '.php';


    //deb($model); die;

    if (file_exists($formFile)) {
        include $formFile;
    }

    ?>

    <div class="form-group buttons">
        <?php
        echo AdminLteHelper::getSubmitButton($model->isNewRecord ? tc('Add') : tc('Save'));

        ?>
    </div>
    <?php $this->endWidget(); ?>

</div><!-- form -->



