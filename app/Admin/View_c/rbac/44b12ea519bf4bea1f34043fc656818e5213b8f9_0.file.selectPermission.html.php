<?php
/* Smarty version 3.1.30, created on 2023-06-07 16:26:36
  from "/lnmp/www/app/Admin/View/rbac/selectPermission.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_64803f3c1df4b8_01413849',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '44b12ea519bf4bea1f34043fc656818e5213b8f9' => 
    array (
      0 => '/lnmp/www/app/Admin/View/rbac/selectPermission.html',
      1 => 1678771403,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_64803f3c1df4b8_01413849 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>权限列表</span></div>
<!--栅格搭框架-->
<div class="col-sm-10  col-md-10  col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
    <div class="table-responsive">
        <table class="table table-striped text-center">
            <thead>
            <tr>
                <th>权限编号</th>
                <th>父节点名称</th>
                <th>权限名称</th>
                <th>控制器名称</th>
                <th>方法名称</th>
                <th>是否启用</th>
            </tr>
            </thead>
            <tbody>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['sp']->value, 'p');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['p']->value) {
?>
            <tr>
                <td><?php echo $_smarty_tpl->tpl_vars['p']->value['per_id'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['p']->value['parent_name'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['p']->value['per_name'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['p']->value['controller_name'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['p']->value['action_name'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['p']->value['enable'];?>
</td>
            </tr>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

            </tbody>
        </table>
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
}
}
