<div class="form">

    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => $this->modelName . '-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'well form-disable-button-after-submit'),
    ));

    ?>

    <p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

    <?php echo $form->errorSummary($model); ?>

    <?php
    $this->widget('application.modules.lang.components.langFieldWidget', array(
        'model' => $model,
        'field' => 'name',
        'type' => 'string'
    ));

    $list = ApartmentObjType::getList();
    if (!$model->isNewRecord && isset($list[$model->id])) {
        unset($list[$model->id]);
    }

    $list = CMap::mergeArray(array(0 => ''), $list);

    ?>

    <?php echo $form->checkboxControlGroup($model, 'with_obj'); ?>

    <?php echo $form->checkboxControlGroup($model, 'show_in_search'); ?>

    <?php echo $form->checkboxControlGroup($model, 'show_in_grid'); ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'parent_id'); ?>
        <?php echo $form->dropDownList($model, 'parent_id', $list, array('class' => 'width240')); ?>
        <?php echo $form->error($model, 'parent_id'); ?>
    </div>

    <div id="lang_with_obj" style="display: none;">
        <?php
        echo $form->errorSummary($settings->models);

        foreach ($settings->models as $key => $tm) {
            //deb($tm->message);
            $this->widget('application.modules.lang.components.langFieldWidget', array(
                'model' => $tm,
                'field' => 'translation',
                'type' => 'text',
                'fieldPrefix' => '[' . $key . ']',
                'labelSet' => $settings->getLabel($tm->message),
            ));
        }

        echo '<hr>';

        ?>
    </div>
    <?php
    if (!$model->isNewRecord && $model->icon_file):

        ?>
        <div class="form-group padding-bottom10 padding-top10">
            <div class="padding-bottom10"><?php echo tt('current_icon'); ?></div>
            <div><?php echo CHtml::image(Yii::app()->getBaseUrl() . '/' . $model->iconsMapPath . '/' . $model->icon_file); ?></div>
            <div><?php echo CHtml::link(tc('Delete'), $this->createUrl('deleteIcon', array('id' => $model->id))); ?></div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'icon_file'); ?>
        <div class="padding-bottom10">
            <span class="label label-info">
                <?php echo Yii::t('module_apartmentObjType', 'Supported file: {supportExt}.', array('{supportExt}' => $model->supportExt)) . ''; ?>
            </span>
        </div>
        <?php echo $form->fileField($model, 'icon_file'); ?>
        <?php echo $form->error($model, 'icon_file'); ?>
    </div>

    <?php if (issetModule('yandexRealty')): ?>
        <div class="clear"></div>
        <fieldset>
            <legend>Yandex.Realty / Яндекс.Недвижимость</legend>
            <div></div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'ya_type'); ?>
                <?php echo $form->dropDownList($model, 'ya_type', CMap::mergeArray(array('' => ''), YandexRealty::getYaTypesArray()), array('class' => 'width240')); ?>
                <?php echo $form->error($model, 'ya_type'); ?>
            </div>
            <div class="form-group">
                <?php echo $form->labelEx($model, 'ya_subtype'); ?>
                <?php echo $form->textField($model, 'ya_subtype', array('class' => 'width300', 'placeholder' => '')); ?>
                <?php echo $form->error($model, 'ya_subtype'); ?>
                <div class="padding-bottom10">
                    <a href="https://yandex.ru/support/realty/requirements/requirements-sale-housing.html"
                       target="_blank">https://yandex.ru/support/realty/requirements/requirements-sale-housing.html</a><br/>
                    <a href="https://yandex.ru/support/realty/requirements/requirements-commercial.html"
                       target="_blank">https://yandex.ru/support/realty/requirements/requirements-commercial.html</a><br/>
                    <a href="https://yandex.ru/support/realty/requirements/requirements-sale-new.html" target="_blank">https://yandex.ru/support/realty/requirements/requirements-sale-new.html</a>
                </div>
            </div>
        </fieldset>
    <?php endif; ?>

    <div class="clear"></div>
    <div class="form-group buttons">
        <?php
        echo AdminLteHelper::getSubmitButton($model->isNewRecord ? tc('Add') : tc('Save'));

        ?>
    </div>

    <?php $this->endWidget(); ?>

    <?php
    if (issetModule('seo') && !$model->isNewRecord) {
        $this->widget('application.modules.seo.components.SeoWidget', array(
            'model' => $model,
            'showBodyTextField' => true,
        ));
    }

    ?>
</div><!-- form -->

<script>
    $(function () {
        checkWithObj();

        $('#ApartmentObjType_with_obj').on('change', checkWithObj);
    });

    function checkWithObj() {
        var with_obj = $('#ApartmentObjType_with_obj').is(':checked');

        if (with_obj) {
            $('#lang_with_obj').show();
        } else {
            $('#lang_with_obj').hide();
        }
    }
</script>