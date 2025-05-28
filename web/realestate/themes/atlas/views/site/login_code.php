<?php
/**
 * @var LoginCodeForm $model
 */

$this->pageTitle = Yii::app()->name . ' - ' . Yii::t('common', 'Login');
$this->breadcrumbs = array(
    Yii::t('common', 'Login')
);
?>

    <div class="title highlight-left-right">
        <div>
            <h1><?php echo Yii::t('common', 'Login'); ?></h1>
        </div>
    </div>
    <div class="clear"></div><br />

    <p>
        <?php echo Yii::t('common', 'A code has been sent to your E-mail. Please enter it in the field below. The code is valid for {n} minutes.', LoginCodeForm::CODE_ACTIVE_MIN);?>
    </p>

<div class="form">
        <?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login-code-form',
            'enableClientValidation' => false,
            'htmlOptions' => array('class' => 'form-disable-button-after-submit'),
            'action' => array('site/logincode'),
            /* 'clientOptions'=>array(
              'validateOnSubmit'=>true,
              ), */
        ));
        ?>

    <div class="row">
            <?php echo $form->labelEx($model, 'code'); ?>
            <?php echo $form->numberField($model, 'code', array('class' => 'form-control input-login-password-with-eye')); ?>
            <?php echo $form->error($model, 'code'); ?>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton(Yii::t('common', 'Login'), array('class' => 'button-blue submit-button')); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div><!-- form -->
    <div class="clear"></div>
