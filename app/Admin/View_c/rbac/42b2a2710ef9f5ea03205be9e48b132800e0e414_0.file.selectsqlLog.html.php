<?php
/* Smarty version 3.1.30, created on 2024-04-23 01:09:32
  from "/lnmp/www/app/Admin/View/rbac/selectsqlLog.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_662699ccb80ef6_51953944',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '42b2a2710ef9f5ea03205be9e48b132800e0e414' => 
    array (
      0 => '/lnmp/www/app/Admin/View/rbac/selectsqlLog.html',
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
function content_662699ccb80ef6_51953944 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->

<div class="jin-content-title"><span>后台操作日志</span></div>
<div class="table-responsive jin-table-12px">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>日志编号</th>
            <th>操作时间</th>
            <th>操作方法</th>
            <th>错误描述</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>

<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
>
    var url = "/?p=Admin&c=Rbac&a=selectsqlLog&jinIf=911";
    var arr = ['log_id', 'time', 'action_name', 'errorinfo'];
    var id = ["#content", "#page"];
    var data = {page: 1};
    $(document).ready(tableList(url, data, id, arr));
<?php echo '</script'; ?>
><?php }
}
