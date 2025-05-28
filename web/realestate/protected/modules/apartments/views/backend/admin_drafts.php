<div class="flash-notice">
    <?php echo tt('Drafts are automatically deleted once a day');?>
</div>

<?php
$this->breadcrumbs = array(
    tt('Drafts'),
);

$this->adminTitle = tt('Drafts');

$columns = array(
    array(
        'class' => 'CCheckBoxColumn',
        'id' => 'itemsSelected',
        'selectableRows' => '2',
        'htmlOptions' => array(
            'class' => 'center',
            'data-title' => tc('Actions'),
        ),
    ),
    array(
        'name' => 'id',
        'htmlOptions' => array(
            'class' => 'apartments_id_column',
            'data-title' => tt('ID', 'apartments'),
        ),
        'sortable' => true,
    ),
);

$columns[] = array(
    'value' => 'GridHelper::getSummary($data)',
    'type' => 'raw',
);

$columns[] = array(
    'class' => 'bootstrap.widgets.BsButtonColumn',
    'template' => '{listings} {view} {update} {delete}',
    'deleteConfirmation' => tc('Are you sure you want to delete this item?'),
    'htmlOptions' => array('style' => 'width: 200px; min-width: 200px;', 'class' => 'button_column_actions'),
    'headerHtmlOptions' => array('style' => 'width: 200px; min-width: 200px;'),
    'buttons' => array(
        'delete' => array(
            'visible' => '(!param("notDeleteListings", 0) || (param("notDeleteListings", 0) && !$data->deleted))'
        ),
        'listings' => array(
            'label' => '',
            'url' => 'Yii::app()->createUrl("/apartments/backend/main/admin", array("Apartment[ownerEmail]" => $data->user->email))',
            'options' => array('class' => 'glyphicon glyphicon-th-list', 'title' => tt('member_listings', 'apartments')),
            'visible' => '(Yii::app()->user->checkAccess("apartments_admin") && isset($data->user) && $data->user) ? true : false',
        ),
        'view' => array(
            'url' => '$data->getUrl()',
            'options' => array('target' => '_blank'),
        ),
    ),
);

$this->widget('CustomGridView', array(
    'allowNoMoreTables' => true,
    'id' => 'apartments-drafts-grid',
    'dataProvider' => $model->searchAll(),
    //'filter'=>$model,
    'afterAjaxUpdate' => 'function(){$("a[rel=\'tooltip\']").tooltip(); $("div.tooltip-arrow").remove(); $("div.tooltip-inner").remove(); attachStickyTableHeader(); $("a.fancy").fancybox({"ajax":{"data":"isFancy=true"},"titlePosition":"inside"});}',
    'rowCssClassExpression' => '"items[]_{$data->id}"',
    'rowHtmlOptionsExpression' => 'array("data-bid"=>"items[]_{$data->id}")',
    'columns' => $columns
));

$this->renderPartial('//site/admin-select-items', array(
    'url' => '/apartments/backend/main/itemsSelected',
    'id' => 'apartments-drafts-grid',
    'model' => $model,
    'options' => ['delete' => Yii::t('common', 'Delete')],
));
