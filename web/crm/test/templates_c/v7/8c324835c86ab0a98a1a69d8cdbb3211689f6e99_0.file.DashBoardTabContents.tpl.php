<?php
/* Smarty version 4.5.4, created on 2025-01-11 21:19:19
  from '/home2/vivhjgte/public_html/crm/layouts/v7/modules/Vtiger/dashboards/DashBoardTabContents.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.5.4',
  'unifunc' => 'content_6782e0578db276_46242262',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8c324835c86ab0a98a1a69d8cdbb3211689f6e99' => 
    array (
      0 => '/home2/vivhjgte/public_html/crm/layouts/v7/modules/Vtiger/dashboards/DashBoardTabContents.tpl',
      1 => 1727647512,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6782e0578db276_46242262 (Smarty_Internal_Template $_smarty_tpl) {
?>
<div class='dashBoardTabContainer'><?php $_smarty_tpl->_subTemplateRender(call_user_func_array($_smarty_tpl->registered_plugins[ 'modifier' ][ 'vtemplate_path' ][ 0 ], array( "dashboards/DashBoardHeader.tpl",$_smarty_tpl->tpl_vars['MODULE_NAME']->value )), $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array('DASHBOARDHEADER_TITLE'=>vtranslate($_smarty_tpl->tpl_vars['MODULE']->value,$_smarty_tpl->tpl_vars['MODULE']->value)), 0, true);
?><br><div class="dashboardBanner"></div><div class="dashBoardTabContents clearfix"><div class="gridster_<?php echo $_smarty_tpl->tpl_vars['TABID']->value;?>
"><?php $_smarty_tpl->_assignInScope('ROWCOUNT', 0);
$_smarty_tpl->_assignInScope('COLCOUNT', 0);?><ul><?php $_smarty_tpl->_assignInScope('COLUMNS', 2);
$_smarty_tpl->_assignInScope('ROW', 1);
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['WIDGETS']->value, 'WIDGET', false, NULL, 'count', array (
  'index' => true,
));
$_smarty_tpl->tpl_vars['WIDGET']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['WIDGET']->value) {
$_smarty_tpl->tpl_vars['WIDGET']->do_else = false;
$_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index']++;
$_smarty_tpl->_assignInScope('WIDGETDOMID', $_smarty_tpl->tpl_vars['WIDGET']->value->get('linkid'));
if ($_smarty_tpl->tpl_vars['WIDGET']->value->getName() == 'MiniList') {
$_smarty_tpl->_assignInScope('WIDGETDOMID', ($_smarty_tpl->tpl_vars['WIDGET']->value->get('linkid')).('-').($_smarty_tpl->tpl_vars['WIDGET']->value->get('widgetid')));
} elseif ($_smarty_tpl->tpl_vars['WIDGET']->value->getName() == 'Notebook') {
$_smarty_tpl->_assignInScope('WIDGETDOMID', ($_smarty_tpl->tpl_vars['WIDGET']->value->get('linkid')).('-').($_smarty_tpl->tpl_vars['WIDGET']->value->get('widgetid')));
}
if ($_smarty_tpl->tpl_vars['WIDGETDOMID']->value) {?><li id="<?php echo $_smarty_tpl->tpl_vars['WIDGETDOMID']->value;?>
" <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index'] : null)%$_smarty_tpl->tpl_vars['COLUMNS']->value == 0 && (isset($_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index'] : null) != 0) {?> <?php $_smarty_tpl->_assignInScope('ROWCOUNT', $_smarty_tpl->tpl_vars['ROW']->value+1);?> data-row="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getPositionRow($_smarty_tpl->tpl_vars['ROWCOUNT']->value);?>
" <?php } else { ?> data-row="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getPositionRow($_smarty_tpl->tpl_vars['ROW']->value);?>
" <?php }
$_smarty_tpl->_assignInScope('COLCOUNT', ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index'] : null)%$_smarty_tpl->tpl_vars['COLUMNS']->value)+1);?> data-col="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getPositionCol($_smarty_tpl->tpl_vars['COLCOUNT']->value);?>
" data-sizex="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getSizeX();?>
" data-sizey="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getSizeY();?>
" <?php if ($_smarty_tpl->tpl_vars['WIDGET']->value->get('position') == '') {?> data-position="false"<?php }?>class="dashboardWidget dashboardWidget_<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index'] : null);?>
" data-url="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getUrl();?>
" data-mode="open" data-name="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getName();?>
"></li><?php } else {
$_smarty_tpl->_assignInScope('CHARTWIDGETDOMID', $_smarty_tpl->tpl_vars['WIDGET']->value->get('reportid'));
$_smarty_tpl->_assignInScope('WIDGETID', $_smarty_tpl->tpl_vars['WIDGET']->value->get('id'));?><li id="<?php echo $_smarty_tpl->tpl_vars['CHARTWIDGETDOMID']->value;?>
-<?php echo $_smarty_tpl->tpl_vars['WIDGETID']->value;?>
" <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index'] : null)%$_smarty_tpl->tpl_vars['COLUMNS']->value == 0 && (isset($_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index'] : null) != 0) {?> <?php $_smarty_tpl->_assignInScope('ROWCOUNT', $_smarty_tpl->tpl_vars['ROW']->value+1);?> data-row="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getPositionRow($_smarty_tpl->tpl_vars['ROWCOUNT']->value);?>
" <?php } else { ?> data-row="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getPositionRow($_smarty_tpl->tpl_vars['ROW']->value);?>
" <?php }
$_smarty_tpl->_assignInScope('COLCOUNT', ((isset($_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index'] : null)%$_smarty_tpl->tpl_vars['COLUMNS']->value)+1);?> data-col="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getPositionCol($_smarty_tpl->tpl_vars['COLCOUNT']->value);?>
" data-sizex="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getSizeX();?>
" data-sizey="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getSizeY();?>
" <?php if ($_smarty_tpl->tpl_vars['WIDGET']->value->get('position') == '') {?> data-position="false"<?php }?>class="dashboardWidget dashboardWidget_<?php echo (isset($_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_foreach_count']->value['index'] : null);?>
" data-url="<?php echo $_smarty_tpl->tpl_vars['WIDGET']->value->getUrl();?>
" data-mode="open" data-name="ChartReportWidget"></li><?php }
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?></ul><input type="hidden" id=row value="<?php echo $_smarty_tpl->tpl_vars['ROWCOUNT']->value;?>
" /><input type="hidden" id=col value="<?php echo $_smarty_tpl->tpl_vars['COLCOUNT']->value;?>
" /><input type="hidden" id="userDateFormat" value="<?php echo $_smarty_tpl->tpl_vars['CURRENT_USER']->value->get('date_format');?>
" /></div></div></div><?php }
}
