<?php
$this->breadcrumbs = array(
    tt('Manage langs'),
);

$this->menu = array(
    AdminLteHelper::getAddMenuLink(tt('Create new lang'), array('create')),
);
$this->adminTitle = tt('Manage langs');

$this->widget('CustomGridView', array(
    'allowNoMoreTables' => true,
    'id' => 'langs-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'function(){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); reInstallSortable(); attachStickyTableHeader();}',
    'rowCssClassExpression' => '"items[]_{$data->id}"',
    'rowHtmlOptionsExpression' => 'array("data-bid"=>"items[]_{$data->id}")',
    'columns' => array(
        /* array(
          'class'=>'CCheckBoxColumn',
          'id'=>'itemsSelected',
          'selectableRows' => '2',
          'htmlOptions' => array(
          'class'=>'center',
          ),
          ), */
        array(
            'header' => tc('Status'),
            'type' => 'raw',
            'value' => 'Yii::app()->controller->returnStatusHtml($data, "langs-grid", 0,
				$data->active ? array(Lang::getDefaultLangId(), Lang::getAdminMailLangId()) : !$data->currency->active)',
            'headerHtmlOptions' => array(
                'class' => 'apartments_status_column',
                'data-title' => tc('Status'),
            ),
            'filter' => false,
            'sortable' => false,
        ),
        array(
            'header' => tc('Default'),
            'type' => 'raw',
            'value' => '$data->getIsDefaultHtml(0)',
            'filter' => false,
            'sortable' => false,
            'htmlOptions' => array(
                'class' => 'width100 center',
                'data-title' => tc('Default'),
            ),
        ),
        array(
            'name' => 'admin_mail',
            'type' => 'raw',
            'value' => '$data->getIsDefaultHtml(1)',
            'filter' => false,
            'sortable' => false,
            'htmlOptions' => array(
                'class' => 'width100 center',
                'data-title' => tt('Administrator e-mail'),
            ),
        ),
        array(
            'name' => 'flag_img',
            'type' => 'raw',
            'value' => 'CHtml::image($data->getFlagUrl())',
            'sortable' => false,
            'filter' => false,
            'htmlOptions' => array(
                'class' => 'width100 center',
                'data-title' => tt('Flag icon'),
            ),
        ),
        array(
            'header' => tc('Language'),
            'value' => '$data->name_' . Yii::app()->language,
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tc('Language'),
            ),
        ),
        array(
            'header' => tc('Currency'),
            'value' => 'isset($data->currency) ? $data->currency->char_code : ""',
            'sortable' => false,
            'filter' => false,
            'htmlOptions' => array(
                'data-title' => tc('Currency'),
            ),
        ),
        array(
            'name' => 'dateFormat',
            'sortable' => false,
            'filter' => false,
            'htmlOptions' => array(
                'data-title' => tt('dateFormat', 'configuration'),
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'template' => '{up} {down}<br /><br />{update} {delete}',
            'deleteConfirmation' => tc('Are you sure you want to delete this item?'),
            'htmlOptions' => array('class' => 'infopages_buttons_column button_column_actions'),
            'buttons' => array(
                'down' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/lang/backend/main/move", array("id"=>$data->id, "direction" => "down", "catid" => "0"))',
                    'options' => array('class' => 'infopages_arrow_image_down glyphicon glyphicon-menu-down', 'title' => tc('Move an item down')),
                    'visible' => '$data->sorter < "' . $maxSorter . '"',
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'langs-grid'); return false;}",
                ),
                'up' => array(
                    'label' => '',
                    'url' => 'Yii::app()->createUrl("/lang/backend/main/move", array("id"=>$data->id, "direction" => "up", "catid" => "0"))',
                    'options' => array('class' => 'infopages_arrow_image_up glyphicon glyphicon-menu-up', 'title' => tc('Move an item up')),
                    'visible' => '$data->sorter > "' . $minSorter . '"',
                    'click' => "js: function() { ajaxMoveRequest($(this).attr('href'), 'langs-grid'); return false;}",
                ),
                'delete' => array(
                    'visible' => '$data->name_iso != Lang::getDefaultLang() && $data->name_iso != Lang::getAdminMailLang()'
                )
            ),
        ),
    ),
));

/*
  $this->renderPartial('//site/admin-select-items', array(
  'url' => '/lang/backend/main/itemsSelected',
  'id' => 'langs-grid',
  'model' => $model,
  'options' => array(
  'activate' => Yii::t('common', 'Activate'),
  'deactivate' => Yii::t('common', 'Deactivate'),
  'delete' => Yii::t('common', 'Delete')
  ),
  )); */

Yii::app()->clientScript->registerScript('setDefLang', "
	function changeDefault(id, admin_mail){
		$.ajax({
			type: 'POST',
			url: '" . Yii::app()->request->baseUrl . "/lang/backend/main/setDefault',
			data: { 'id' : id, 'admin_mail' : admin_mail },
			success: function(msg){
				$('#currency-grid').yiiGridView.update('langs-grid');
		}
		});
		return;
	}", CClientScript::POS_END);

?>

<?php
$csrf_token_name = Yii::app()->request->csrfTokenName;
$csrf_token = Yii::app()->request->csrfToken;

$cs = Yii::app()->getClientScript();
$cs->registerCoreScript('jquery.ui');

$str_js = "
		var fixHelper = function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		};

		function reInstallSortable(id, data) {
			installSortable();
		}

		function updateGrid() {
			$.fn.yiiGridView.update('langs-grid');
		}

		function installSortable() {
			if ($(window).width() > 767) {
				$('#langs-grid table.items tbody').sortable({
					forcePlaceholderSize: true,
					forceHelperSize: true,
					items: 'tr',
					update : function () {
						serial = $('#langs-grid table.items tbody').sortable('serialize', {key: 'items[]', attribute: 'data-bid'}) + '&{$csrf_token_name}={$csrf_token}';
						$.ajax({
							'url': '" . $this->createUrl('/lang/backend/main/sortitems') . "',
							'type': 'post',
							'data': serial,
							'success': function(data){
								updateGrid();
							},
							'error': function(request, status, error){
								alert('We are unable to set the sort order at this time.  Please try again in a few minutes.');
							}
						});
					},
					helper: fixHelper
				}).disableSelection();
			}
		}

		installSortable();
";

$cs->registerScript('sortable-project', $str_js);
