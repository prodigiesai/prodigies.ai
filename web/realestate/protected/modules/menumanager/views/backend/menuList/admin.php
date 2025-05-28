<?php
$this->menu = array(
    AdminLteHelper::getAddMenuLink(tt('Add menu'), array('create')),
);

$this->adminTitle = tt('Manage menu');

$this->widget('CustomGridView', array(
    'allowNoMoreTables' => true,
    'id' => 'city-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'function(id, data){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); reInstallSortable(id, data); attachStickyTableHeader();}',
    'rowCssClassExpression' => '"items[]_{$data->id}"',
    'rowHtmlOptionsExpression' => 'array("data-bid"=>"items[]_{$data->id}")',
    'columns' => array(
//        array(
//            'class' => 'CCheckBoxColumn',
//            'id' => 'itemsSelected',
//            'selectableRows' => '2',
//            'htmlOptions' => array(
//                'class' => 'center',
//                'data-title' => tc('Actions'),
//            ),
//        ),

        //'id',
        array(
            'class' => 'editable.EditableColumn',
            'header' => tc('Name'),
            'name' => 'name_' . Yii::app()->language,
            'value' => '$data->getStrByLang("name")',
            'editable' => array(
                'url' => Yii::app()->controller->createUrl('/menumanager/backend/menuList/ajaxEditColumn', array('model' => 'MenuList', 'field' => 'name_' . Yii::app()->language)),
                'placement' => 'right',
                'emptytext' => '',
                'savenochange' => 'true',
                'title' => tc('Name'),
                'options' => array(
                    'ajaxOptions' => array('dataType' => 'json')
                ),
                'success' => 'js: function(response, newValue) {
					if (response.msg == "ok") {
						message("' . tc("Success") . '");
					}
					else if (response.msg == "save_error") {
						var newValField = "' . tc("Error. Repeat attempt later") . '";

						return newValField;
					}
					else if (response.msg == "no_value") {
						var newValField = "' . tt("Enter the required value", 'configuration') . '";

						return newValField;
					}
				}',
            ),
            'sortable' => false,
            'htmlOptions' => array(
                'data-title' => tc('Name'),
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'template' => '{update} {delete}',
            'deleteConfirmation' => tc('Are you sure you want to delete this item?'),
            'htmlOptions' => array('class' => 'infopages_buttons_column button_column_actions', 'style' => 'width:160px;'),
            'buttons' => array(
                'delete' => array(
                    'visible' => '$data->allowDelete()',
                ),
                'update' => array(
                    'url' => 'Yii::app()->createUrl("/menumanager/backend/main/admin", array("id"=>$data->id))',
                ),
            ),
        ),
    ),
));

//$this->renderPartial('//site/admin-select-items', array(
//    'url' => '/location/backend/city/itemsSelected',
//    'id' => 'city-grid',
//    'model' => $model,
//    'options' => array(
//        'activate' => Yii::t('common', 'Activate'),
//        'deactivate' => Yii::t('common', 'Deactivate'),
//        'delete' => Yii::t('common', 'Delete')
//    ),
//));

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

		function updateGrid() {
			$.fn.yiiGridView.update('city-grid');
		}
";

$cs->registerScript('sortable-project', $str_js);
