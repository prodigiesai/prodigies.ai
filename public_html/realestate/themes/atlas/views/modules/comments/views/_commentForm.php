<div class="form min-fancy-width <?php echo (isset($isFancy) && $isFancy) ? 'white-popup-block' : ''; ?>">
    <?php $form = $this->beginWidget('CustomActiveForm', array(
        'action' => Yii::app()->controller->createUrl('/comments/main/writeComment'),
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'form-disable-button-after-submit'),
    )); ?>
    <div class="title highlight-left-right">
        <div>
            <h2><?php echo Yii::t('module_comments', 'Leave a Comment'); ?></h2>
        </div>
    </div>
    <div class="clear"></div>
    <br/>

    <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>
    <?php echo $form->errorSummary($model); ?>

    <?php if (Yii::app()->user->isGuest) { ?>
        <div class="row">
            <?php echo $form->labelEx($model, 'user_name'); ?>
            <?php echo $form->textField($model, 'user_name', array('class' => 'width200')); ?>
            <?php echo $form->error($model, 'user_name'); ?>
        </div>

        <div class="row">
            <?php echo $form->labelEx($model, 'user_email'); ?>
            <?php echo $form->textField($model, 'user_email', array('class' => 'width200')); ?>
            <?php echo $form->error($model, 'user_email'); ?>
        </div>
    <?php } ?>

    <div class="row">
        <?php echo $form->labelEx($model, 'body'); ?>
        <?php echo $form->textArea($model, 'body', array('rows' => 3, 'cols' => 50, 'class' => 'width500')); ?>
        <?php echo $form->error($model, 'body'); ?>
    </div>

    <?php if ($model->enableRating) { ?>
        <div class="clear"></div>
        <div class="comments-ratings-block">
            <div class="row">
                <?php echo $form->labelEx($model, 'rating', array('for' => false)); ?>
                <div class="user-rating-comment">
                    <?php $this->widget(
                        'CStarRating',
                        array(
                            'name' => 'CommentForm[rating]',
                            'value' => $model->rating,
                            'resetText' => tt('Remove the rate', 'comments'),
                            'minRating' => Comment::MIN_RATING,
                            'maxRating' => Comment::MAX_RATING,
                        )
                    ); ?>
                </div>
                <?php echo $form->error($model, 'rating'); ?>
            </div>
        </div>
        <div class="clear"></div>
    <?php } ?>

    <?php if (Yii::app()->user->isGuest || param('useCaptchaCommentsForRegistered', 1)) { ?>
        <br/>
        <?php echo $form->labelEx($model, 'verifyCode'); ?>
        <?php $display = (param('useReCaptcha', 0)) ? 'none;' : 'block;' ?>
        <?php echo $form->textField($model, 'verifyCode', array('autocomplete' => 'off', 'style' => "display: {$display}")); ?>
        <br/>
        <?php $this->widget('CustomCaptchaFactory',
            array(
                'captchaAction' => '/comments/main/captcha',
                'buttonOptions' => array('class' => 'get-new-ver-code'),
                'clickableImage' => true,
                'model' => $model,
                'attribute' => 'verifyCode',
            )
        ); ?>
        <?php echo $form->error($model, 'verifyCode'); ?>
        <br/>
    <?php } ?>

    <div class="row buttons">
        <?php
        echo $form->hiddenField($model, 'url');
        echo $form->hiddenField($model, 'rel');
        echo $form->hiddenField($model, 'modelName');
        echo $form->hiddenField($model, 'modelId');
        ?>

        <div class="block-afree-to-user-afreement">
            <?php echo Yii::t('common', 'By clicking "{buttonName}", you agree to our <a href="{licenceUrl}" target="_blank">User agreement</a>', array('{buttonName}' => tc('Add'), '{licenceUrl}' => InfoPages::getUrlById(InfoPages::LICENCE_PAGE_ID))); ?>
        </div>

        <?php
        echo CHtml::submitButton(Yii::t('common', 'Add'), array('class' => 'button-blue submit-button'));
        ?>
    </div>
    <?php $this->endWidget(); ?>
</div>