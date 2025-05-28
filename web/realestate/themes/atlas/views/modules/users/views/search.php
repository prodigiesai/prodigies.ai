<?php
$userTypes = User::getTypeList('withAll');
$typeName = isset($userTypes[$type]) ? $userTypes[$type] : '?';

$this->pageTitle .= ' - ' . tc('Users') . ': ' . $typeName;
$this->breadcrumbs = array(
    tc('Users'),
);
?>

<div class="title highlight-left-right">
    <div>
        <h1><?php echo tc('Users'); ?></h1>
    </div>
</div>
<div class="clear"></div><br/>

<?php
$links = array();
$links[] = array('label' => tc('All'), 'url' => Yii::app()->createUrl('/users/main/search', array('type' => 'all')), 'active' => $type == 'all');
$links[] = array('label' => tc('Private persons'), 'url' => Yii::app()->createUrl('/users/main/search', array('type' => User::TYPE_PRIVATE_PERSON)), 'active' => $type == User::TYPE_PRIVATE_PERSON);
$links[] = array('label' => tc('Agents'), 'url' => Yii::app()->createUrl('/users/main/search', array('type' => User::TYPE_AGENT)), 'active' => $type == User::TYPE_AGENT);
$links[] = array('label' => tc('Agency'), 'url' => Yii::app()->createUrl('/users/main/search', array('type' => User::TYPE_AGENCY)), 'active' => $type == User::TYPE_AGENCY);
?>

<div id="userfilter">
    <?php
    $this->widget('zii.widgets.CMenu', array(
        'items' => $links,
        'htmlOptions' => array(
            'class' => 'nav nav-pills'
        )
    ));
    ?>
</div>

<div class="users-list-view">
    <?php if ($dataProvider && isset($dataProvider->data) && $dataProvider->data): ?>
        <?php
        $this->widget('zii.widgets.CListView',
            array(
                'dataProvider' => $dataProvider,
                'itemView' => '_search_user_item', // представление для одной записи
                'ajaxUpdate' => false, // отключаем ajax поведение
                'emptyText' => false,
                'summaryText' => "{start}&mdash;{end} " . tc('of') . " {count}",
                'template' => '{summary} {sorter} {items} <div class="clear"></div><br /> {pager}',

                'sortableAttributes' => array('username', 'date_created'),
                'pager' =>
                    array(
                        'class' => 'itemPaginatorAtlas',
                        'header' => '',
                        'selectedPageCssClass' => 'current',
                        'htmlOptions' => array(
                            'class' => ''
                        )
                    ),
                'pagerCssClass' => 'pagination',
            )
        );

        Yii::app()->clientScript->registerScript('generate-phone-users-view-all', '
				function getPhoneNum(elem, id){
					$.get(\'' . Yii::app()->controller->createUrl('/apartments/main/generatephone') . '?from=userlist&id=\' + id + \'\', function(data) {
						$(elem).closest("span").html(data);
					});
				}
			', CClientScript::POS_END);
        ?>
    <?php else: ?>
        <div class="empty">
            <?php echo tc('No user'); ?>
        </div>
    <?php endif; ?>
</div>