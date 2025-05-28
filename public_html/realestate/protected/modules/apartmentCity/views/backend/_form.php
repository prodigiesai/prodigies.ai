<div class="form">

    <?php
    $form = $this->beginWidget('CustomForm', array(
        'id' => $this->modelName . '-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'well form-disable-button-after-submit'),
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

    <div class="well">
        <?php
        if (isset($model->image) && $model->image) {
            $src = $model->image->getSmallThumbLink();
            if ($src) {
                echo CHtml::link(CHtml::image($src, $model->getStrByLang('name')), $model->image->fullHref(), array('class' => 'fancy'));

                if (issetModule('seo') && !$model->isNewRecord) {
                    $this->widget('application.modules.seo.components.SeoImageWidget', array(
                        'model' => $model->image,
                        'showLink' => true,
                        'showForm' => false,
                        'showJS' => false,
                    ));
                }

                echo '<div style="padding-top: 3px;">' . CHtml::button(tc('Delete'), array(
                        'onclick' => 'document.location.href="' . $this->createUrl('/images/backend/main/deleteImg', array(
                                'id' => $model->image->id,
                                'mid' => $model->id,
                                'rUrl' => Yii::app()->createUrl('/apartmentCity/backend/main/update', array('id' => $model->id)),
                            )) . '";'
                    )) . '</div>';
            }

            echo '
					<div class="clear"></div>
					<br />
				';
        }

        ?>
        <?php echo $form->fileFieldControlGroup($model, 'cityImage', array()); ?>
        <div class="padding-bottom10">
            <span class="label label-info">
                <?php echo Yii::t('module_apartments', 'Supported file: {supportExt}.', array('{supportExt}' => ObjectImage::getSupportExt())); ?>
            </span>
        </div>
    </div>
    <br/>

    <div class="form-group buttons">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit',
            'type' => 'primary',
            'icon' => 'ok white',
            'label' => $model->isNewRecord ? tc('Add') : tc('Save'),
            'htmlOptions' => array(
                'class' => 'submit-button',
            ),
        ));

        ?>

        <?php if ($model->isNewRecord): ?>
            <?php
            $this->widget('bootstrap.widgets.TbButton', array('buttonType' => 'submit',
                'type' => 'primary',
                'icon' => 'ok white',
                'htmlOptions' => array('name' => 'addMore', 'onclick' => '$("#addMore").val(1);', 'class' => 'submit-button'),
                'label' => tc('Add and continue'),
            ));

            ?>
        <?php endif; ?>
    </div>

    <?php $this->endWidget(); ?>

    <?php
    if (issetModule('seo') && !$model->isNewRecord) {
        $this->widget('application.modules.seo.components.SeoImageWidget', array(
            'model' => $model->image,
            'showLink' => false,
            'showForm' => true,
            'showJS' => true,
        ));

        $this->widget('application.modules.seo.components.SeoWidget', array(
            'model' => $model,
            'showBodyTextField' => true,
        ));
    }

    ?>
</div><!-- form -->