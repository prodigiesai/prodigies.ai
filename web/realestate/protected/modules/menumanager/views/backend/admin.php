<link rel="stylesheet" type="text/css"
      href="<?php echo Yii::app()->baseUrl; ?>/common/css/jquery.jstree/apple/style.css"/>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/common/js/jquery.jstree.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/common/js/jquery.dialog-plugin.js', CClientScript::POS_BEGIN);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/common/js/jquery.blockUI.js', CClientScript::POS_BEGIN);

$this->breadcrumbs = array(
    tt('Manage menu items'),
);

$this->menu = array(
    AdminLteHelper::getBackMenuLink(tt('Manage menu'), array('/menumanager/backend/menuList/admin')),
);

$this->adminTitle = tt('Manage menu items');

?>

<div class="flash-notice"><?php echo tt('help_menumanager_backend_main_admin'); ?></div>

<?php $this->renderPartial('_menu_form', array('menu_list_id' => $menu_list_id)) ?>

<div id="pageList" style="height: 400px; overflow: auto"></div>