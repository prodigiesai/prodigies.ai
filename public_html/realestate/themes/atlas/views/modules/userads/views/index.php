<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {'packages': ['corechart']});
</script>

<div class="title highlight-left-right">
    <div>
        <h1><?php echo tc('My listings'); ?></h1>
    </div>
</div>
<div class="clear"></div><br/>

<?php
Yii::app()->getModule('userads');

$this->pageTitle .= ' - ' . tc('My listings');

if (!isset($this->breadcrumbs) || empty($this->breadcrumbs)) {
    $this->breadcrumbs = array(
        Yii::t('common', 'Control panel') => array('/usercpanel/main/index'),
        tt('Manage apartments', 'apartments')
    );
}
?>

<div class="flashes" style="display: none;"></div>
<div class="buttons-listing">
    <div class="additional-add-listing">
        <?php echo CHtml::link(tc('Add ad', 'apartments'), Yii::app()->createUrl('/userads/main/create'), array('class' => 'button-blue')); ?>
    </div>
    <div class="additional-filter-listing">
        <?php echo CHtml::link(tc('Filter'), 'javascript: void(0);', array('class' => 'button-blue', 'id' => 'search-listview-button')); ?>
    </div>
    <div class="additional-add-listing">
        <?php echo CHtml::link(tt('Drafts', 'apartments'), Yii::app()->createUrl('/userads/main/drafts'), array('class' => 'button-blue')); ?>
    </div>
    <div class="clear"></div>
</div>


<div class="search-my-listings-form" style="display:none">
    <?php
    $this->renderPartial('//modules/userads/views/_search', array(
        'model' => $model,
    ));
    ?>
</div>
<div class="clear"></div>

<?php
$this->widget('NoBootstrapListView', array(
    'afterAjaxUpdate' => 'function(){ if(typeof initBootstrapConfirm === "function"){initBootstrapConfirm();} }',
    'dataProvider' => $model->search(),
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

<?php
Yii::app()->clientScript->registerScript('search', "
	$('#search-listview-button').click(function(){
		$('.search-my-listings-form').toggle();
		
		if ($('.search-my-listings-form').css('display') == 'none') {
			$('#search-listview-button').html('" . tc('Filter') . "');
		}
		else {
			$('#search-listview-button').html('" . tc('Hide filter') . "');
		}
		return false;
	});

	$('.search-my-listings-form form').submit(function(){
		$.fn.yiiListView.update('my-listing-blocks', {
			data: $(this).serialize()
		});
		return false;
	});
", CClientScript::POS_END);
