<?php
$this->pageTitle .= ' - ' . tt('Change your password');
$this->breadcrumbs = array(
    tc('Control panel') => Yii::app()->createUrl('/usercpanel'),
    tt('Change your password'),
);
?>

<div class="title highlight-left-right">
    <div>
        <h1><?php echo tt('Change your password'); ?></h1>
    </div>
</div>
<div class="clear"></div><br/>

<div class="form">
    <?php
    $model->scenario = 'changePass';
    $model->password = '';
    $model->password_repeat = '';

    $form = $this->beginWidget('CustomActiveForm', array(
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-disable-button-after-submit'),
    ));

    ?>
    <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

    <?php
    if ($model->hasErrors('password')) {
        echo $form->errorSummary($model);
    }
    ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'password'); ?>
        <?php echo $form->passwordField($model, 'password', array('size' => 20, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'password'); ?>
    </div>

    <div class="row">
        <?php echo $form->labelEx($model, 'password_repeat'); ?>
        <?php echo $form->passwordField($model, 'password_repeat', array('size' => 20, 'maxlength' => 128)); ?>
        <?php echo $form->error($model, 'password_repeat'); ?>
    </div>

    <div class="row buttons">
        <?php echo CHtml::submitButton(tt('Change'), array('class' => 'button-blue submit-button')); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- search-form -->