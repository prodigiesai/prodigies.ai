<div class="title highlight-left-right">
    <div>
        <h1><?php echo tc('My drafts'); ?></h1>
    </div>
</div>
<div class="clear"></div><br/>

<?php
Yii::app()->getModule('userads');

$this->pageTitle .= ' - ' . tc('My drafts');

if (!isset($this->breadcrumbs) || empty($this->breadcrumbs)) {
    $this->breadcrumbs = array(
        Yii::t('common', 'Control panel') => array('/usercpanel/main/index'),
        tt('Manage apartments', 'apartments')
    );
}
?>

<div class="flashes" style="display: none;"></div>
<div class="buttons-listing">&nbsp;</div>

<?php
$this->widget('NoBootstrapListView', array(
    'afterAjaxUpdate' => 'function(){ if(typeof initBootstrapConfirm === "function"){initBootstrapConfirm();} }',
    'dataProvider' => $model->searchAll(),
    'itemView' => '//modules/userads/views/_view_listing',
    'viewData' => array(),
    'itemsTagName' => 'ol',
    'itemsCssClass' => 'my-listing-blocks',
    'id' => 'my-listing-blocks',
    'sortableAttributes' => array(),
));

Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl . '/common/css/bootstrap.min.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/common/js/bootstrap.min.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/common/js/bootstrap-confirmation.min.js', CClientScript::POS_BEGIN);
?>

<style>
    #fancybox-content {
        width: auto !important;
    }
</style>

<script>
    function initBootstrapConfirm() {
        $('[data-toggle=confirmation]').confirmation({
            rootSelector: '[data-toggle=confirmation]'
        });
    }

    $(document).ready(function () {
        initBootstrapConfirm();
    });
</script>
