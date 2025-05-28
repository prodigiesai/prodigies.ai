<?php
/** @var PopDataForm $dataModel */
/** @var Themes $model */
/** @var PopUnit $popUnit */

$cs = Yii::app()->clientScript;
$cs->registerCoreScript('jquery.ui');
$cs->registerCssFile(Yii::app()->clientScript->getCoreScriptUrl() . '/jui/css/base/jquery-ui.css');

$cs->registerScriptFile(Yii::app()->request->baseUrl . '/common/js/jquery.ui.touch-punch.min.js');

?>


<?php
$unit = null;
$itemsString = '';

$itemsString = $popUnit->getItemsString();

$form = $this->beginWidget('CustomForm', array(
    'id' => 'main-pd-form',
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

<div class="form-group">
    <?php
    echo CHtml::activeLabel($dataModel, 'popular_dest_type');
    echo CHtml::activeDropDownList($dataModel, 'popular_dest_type', PopUnit::getTypeList($popUnit), array('id' => 'popular_dest_type', 'class' => 'form-control'));
    ?>
</div>

<?php if ($this->defaultTheme == 'dolphin') { ?>
    <div class="form-group">
        <?php
        echo CHtml::activeLabel($dataModel, 'pd_upload');
        echo CHtml::activeDropDownList($dataModel, 'pd_upload', DolphinDataForm::getUploadList(), array('id' => 'pd_upload', 'class' => 'form-control'));
        ?>
    </div>
<?php } ?>

<?= AdminLteHelper::getSubmitButton(tc('Apply')) ?>

<?php $this->endWidget(); ?><!-- form -->

<ul id="sortable1" class="connectedSortable well"
    id="sortable1" <?php !$itemsString ? 'style="display: none;"' : '' ?>>
    <?= $itemsString ?>
</ul>

<div class="pd-form">
    <?php
    $popUnit->renderForm($dataModel);
    $popUnit->registerJs();
    ?>
</div>


<?php
$successMsg = tc('Success');
$refreshUrl = Yii::app()->createUrl('/themes/backend/widget/popularDest', array('id' => $model->id));

//$js = <<< JS
//
//$('#main-pd-form').on('change', '#popular_dest_type', function() {
//    $('#main-pd-form').submit(); return false;
//});
//
//JS;
//
//Yii::app()->clientScript->registerScript('themes-pd-init-js', $js, CClientScript::POS_END);
?>
