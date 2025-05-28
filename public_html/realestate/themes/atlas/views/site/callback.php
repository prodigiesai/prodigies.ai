<?php
/** @var CallForm $model */
/** @var bool $isAjax */
?>

<?php $this->pageTitle=Yii::app()->name . ' - '.tc('Request a call back'); ?>

<h1><?php echo tc('Request a call back');?></h1>

<div class="form">
	<?php $form=$this->beginWidget('CustomActiveForm', array(
		'id'=>'callback-form',
		'action' => Yii::app()->controller->createUrl('/site/callback'),
		'enableClientValidation'=>false,
        'htmlOptions' => array('class' => 'form-disable-button-after-submit'),
	)); ?>
		<p>
			<?php echo tc('Fill out the form below and our specialists will contact you');?>
		</p>
	
		<!--<p class="note"><?php // echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>-->

        <div class="row">
            <?php echo $form->labelEx($model,'name'); ?>
            <?php echo $form->textField($model, 'name'); ?>
            <?php echo $form->error($model,'name'); ?>
        </div>

		<div class="row">
			<?php echo $form->labelEx($model,'phone'); ?>
            <?php echo $form->textField($model, 'phone'); ?>
			<?php echo $form->error($model,'phone'); ?>
		</div>

        <div class="row">
            <?php echo $form->labelEx($model, 'verifyCode'); ?>
            <?php $display = (param('useReCaptcha', 0)) ? 'none;' : 'block;' ?>
            <?php echo $form->textField($model, 'verifyCode', array('autocomplete' => 'off', 'style' => "display: {$display}")); ?>
            <br/>
            <?php $this->widget('CustomCaptchaFactory',
                array(
                    'captchaAction' => '/site/captcha',
                    'buttonOptions' => array('class' => 'get-new-ver-code'),
                    'clickableImage' => true,
                    'imageOptions' => array('id' => 'recover_captcha'),
                    'model' => $model,
                    'attribute' => 'verifyCode',
                )
            ); ?>
            <?php echo $form->error($model, 'verifyCode'); ?>
            <br/>
        </div>
		
        <div class="form-group buttons">
            <div class="block-afree-to-user-afreement">
                <?php echo Yii::t('common', 'By clicking "{buttonName}", you agree to our <a href="{licenceUrl}" target="_blank">User agreement</a>', array('{buttonName}' => Yii::t('common', 'Send'), '{licenceUrl}' => InfoPages::getUrlById(InfoPages::LICENCE_PAGE_ID))); ?>
            </div>
            <?php
            echo CHtml::submitButton(Yii::t('common', 'Send'), array('class' => 'btn btn-primary button-blue submit-button', 'id' => 'callback-btn'));
            ?>
        </div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
