<div class="form">

    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => $this->modelName . '-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'well form-disable-button-after-submit'),
    ));
    echo CHtml::hiddenField('addMore', 0, array('id' => 'addMore'));

    ?>

    <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <?php
    $this->widget('application.modules.lang.components.langFieldWidget', array(
        'model' => $model,
        'field' => 'name',
        'type' => 'string'
    ));

    ?>
    <div class="clear"></div>

    <div class="form-group buttons">
        <?php
        echo AdminLteHelper::getSubmitButton($model->isNewRecord ? tc('Add') : tc('Save'));

        ?>

        <?php
        if ($model->isNewRecord) {
            echo AdminLteHelper::getSubmitButton(tc('Add and continue'), array(
                'onclick' => '$("#addMore").val(1)',
            ));
        }

        ?>
    </div>

    <?php $this->endWidget(); ?>
</div><!-- form -->