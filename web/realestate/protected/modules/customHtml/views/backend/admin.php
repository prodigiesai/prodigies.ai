<?php
$this->breadcrumbs = array(
    tt('Manage custom html'),
);

$this->adminTitle = tt('Manage custom html');

$this->menu = array(
    AdminLteHelper::getAddMenuLink(tt("Add custom html"), array('create')),
);

$this->widget('CustomGridView', array(
    'allowNoMoreTables' => true,
    'id' => 'custom-html-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'afterAjaxUpdate' => 'function(){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); attachStickyTableHeader();}',
    'columns' => array(
        array(
            'header' => tc('Status'),
            'type' => 'raw',
            'value' => 'Yii::app()->controller->returnStatusHtml($data, "custom-html-grid", 1)',
            'htmlOptions' => array(
                'class' => 'width50',
                'data-title' => tc('Actions'),
            ),
        ),
        array(
            'name' => 'name',
            'header' => tc('Name'),
            'htmlOptions' => array(
                'data-title' => tc('Name'),
            ),
        ),
        array(
            'header' => tt('Code'),
            'value' => '$data->getCode()',
            'htmlOptions' => array(
                'data-title' => tt('Code'),
            ),
        ),
        array(
            'class' => 'bootstrap.widgets.BsButtonColumn',
            'template' => '{update} {delete}',
            'htmlOptions' => array('class' => 'infopages_buttons_column button_column_actions'),
        ),
    ),
));

?>