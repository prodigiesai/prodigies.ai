<?php
$this->pageTitle = Yii::app()->name . ' - ' . tt('Widget "Best listings"');

$this->menu = array(
    //array('label' => tt('Manage themes'), 'url' => array('admin')),
    AdminLteHelper::getBackMenuLink(tt('Manage themes'), array('/themes/backend/main/admin')),
    AdminLteHelper::getBackMenuLink(tt('Edit theme') . ' "' . ucfirst($model->title) . '"', array('/themes/backend/main/update', 'id' => $model->id)),
);

$this->adminTitle = tt('Widget "Best listings"');

?>

<div class="row2">

    <div class="panel panel-default">
        <div class="panel-heading"><?= tt('Widget "Best listings"') ?></div>

        <div class="panel-body">
            <?php
            /** @var CustomForm $form */
            $form = $this->beginWidget('CustomForm', array(
                'id' => 'form-designer-filter',
                'htmlOptions' => array('class' => 'form-disable-button-after-submit'),
            ));
            ?>
            <div class="form-group">
                <?php
                $this->widget('application.modules.lang.components.langFieldWidget', array(
                    'model' => $titleModel,
                    'field' => 'translation',
                    'type' => 'string',
                    'labelSet' => tc('Title')
                ));

                ?>
            </div>
            <?php

            echo $form->checkBoxListControlGroup($dataModel, 'hotObjTypeIds', ApartmentObjType::getList());

            echo '<br>';
            echo $form->label($dataModel, 'hotLimit');
            echo $form->textField($dataModel, 'hotLimit');
            echo '<div class="clear"></div>';
            echo '<br>';

            echo AdminLteHelper::getSubmitButton(tc('Save'));
            $this->endWidget();
            ?>
        </div>
    </div>

</div>

<div class="clearfix"></div>

