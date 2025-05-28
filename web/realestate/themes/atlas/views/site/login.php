<?php
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
    <div class="clear"></div><br/>

<?php if (demo()): ?>
    <div class="row buttons demo-auth-buttons" style="padding: 10px 0 20px 0;">
        <p>
            <a href="#" class="button-blue" onclick="demoLogin(); return false;"><?php echo tc('Log in as user'); ?></a>&nbsp;
            <?php //echo tc('or'); ?>
            <a href="#" class="button-blue"
               onclick="adminLogin(); return false;"><?php echo tc('log in as administrator'); ?></a>&nbsp;
            <?php if (issetModule('rbac')): ?>
                <?php //echo tc('or'); ?>
                <a href="#" class="button-blue"
                   onclick="moderatorLogin(); return false;"><?php echo tc('log in as moderator'); ?></a>&nbsp;
            <?php endif; ?>
        </p>
    </div>
<?php endif; ?>

    <p><?php echo Yii::t('common', 'Already used our services? Please fill out the following form with your login credentials'); ?>
        :</p>

    <div class="form">
        <?php $form = $this->beginWidget('CustomActiveForm', array(
            'id' => 'login-form',
            'enableClientValidation' => false,
            'htmlOptions' => array('class' => 'form-disable-button-after-submit'),
            /*'clientOptions'=>array(
                'validateOnSubmit'=>true,
            ),*/
        )); ?>

        <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

        <div class="row">
            <?php echo $form->labelEx($model, 'username'); ?>
            <?php echo $form->textField($model, 'username', array('class' => 'input-login-password-with-eye')); ?>
            <?php echo $form->error($model, 'username'); ?>
        </div>

        <div class="row row-password-with-eye">
            <?php echo $form->labelEx($model, 'password'); ?>
            <?php echo $form->passwordField($model, 'password', array('class' => 'input-password-with-eye', 'id' => 'login_password')); ?>
            <?php echo $form->error($model, 'password'); ?>
        </div>

        <?php if ($model->scenario == 'withCaptcha'): ?>
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
                        'imageOptions' => array('id' => 'login_captcha'),
                        'model' => $model,
                        'attribute' => 'verifyCode',
                    )
                ); ?>
                <?php echo $form->error($model, 'verifyCode'); ?>
                <br/>
            </div>
        <?php endif; ?>

        <div class="row rememberMe">
            <?php echo $form->checkBox($model, 'rememberMe'); ?>
            <?php echo $form->label($model, 'rememberMe'); ?>
            <?php echo $form->error($model, 'rememberMe'); ?>
        </div>

        <div class="row buttons">
            <?php echo CHtml::submitButton(Yii::t('common', 'Login'), array('class' => 'button-blue submit-button')); ?>
        </div>

        <div class="row">
            <?php if (param('useUserRegistration')): ?><?php echo CHtml::link(tt("Join now"), 'register'); ?> | <?php endif; ?> <?php echo CHtml::link(tt("Forgot password?"), 'recover'); ?>
        </div>

        <?php $this->endWidget(); ?>
    </div><!-- form -->

<?php if (issetModule('socialauth')) : ?>
    <?php $this->widget('ext.eauth.EAuthWidget', array('action' => 'site/login', 'title' => tt('Login with', 'socialauth'))); ?>
<?php endif; ?>

<?php

if (demo()) {
    Yii::app()->clientScript->registerScript('login-js', '
		function demoLogin(){
			login("demore@monoray.net", "demo");
		}

		function adminLogin(){
			login("adminre@monoray.net", "admin");
		}

		function moderatorLogin(){
			login("moderatorre@monoray.net", "moderator");
		}

		function login(username, password){
			$("input[name=\'LoginForm[username]\']").val(username);
			$("input[name=\'LoginForm[password]\']").val(password);
			$("#login-form").submit();
		}
	', CClientScript::POS_END);

    if (Yii::app()->request->getParam('inadminpanel')) {
        Yii::app()->clientScript->registerScript('in-admin-panel-auto-login-js', '
            adminLogin();
        ', CClientScript::POS_READY);
    }
}

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/js/hideShowPassword/css/example.wink.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/hideShowPassword/hideShowPassword.min.js', CClientScript::POS_END);
Yii::app()->clientScript->registerScript('initizlize-hide-show-password', '
	$("#login_password").hidePassword(true);
', CClientScript::POS_READY);