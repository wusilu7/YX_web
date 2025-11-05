<?php
/* Smarty version 3.1.30, created on 2023-06-07 10:09:58
  from "/lnmp/www/app/Admin/View/rbac/uu.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_647fe6f6526112_74463563',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '43b19c4bccb798fe7e1b567e7fb04bff1df6febe' => 
    array (
      0 => '/lnmp/www/app/Admin/View/rbac/uu.html',
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
function content_647fe6f6526112_74463563 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<!--栅格搭框架-->
<div class="col-sm-4  col-md-4  col-lg-4 col-sm-offset-4 col-md-offset-4 col-lg-offset-4">
    <div class="jin-content-title"><span>修改用户</span></div>
    <form action="#" method="post">
        <div class="input-group jin-input">
            <span class="input-group-addon">帐号</span>
            <input type="text" class="form-control" disabled="disabled" value="<?php echo $_smarty_tpl->tpl_vars['suu']->value['user_id'];?>
" name="user_id"
                   required>
        </div>
        <div class="input-group  jin-input">
            <span class="input-group-addon">姓名</span>
            <input type="text" class="form-control" disabled="disabled" value="<?php echo $_smarty_tpl->tpl_vars['suu']->value['name'];?>
" name="name" required>
        </div>
        <div class="input-group  jin-input">
            <span class="input-group-addon">密码</span>
            <input type="text" class="form-control" name="password" placeholder="如不修改密码请勿填写此项">
        </div>

        <select name="role_id" class="form-control dropdown-toggle ">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['sur']->value, 'r');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['r']->value) {
?>
            <option value=<?php echo $_smarty_tpl->tpl_vars['r']->value['role_id'];?>
><?php echo $_smarty_tpl->tpl_vars['r']->value['role_name'];?>
</option>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

        </select>

        <button class="btn btn-success jin-btn" type="submit">修&nbsp;改</button>
        <a class="btn btn-danger jin-btn" href="?p=Admin&c=Rbac&a=selectUser&jinIf=113&id=<?php echo $_GET['id'];?>
">删&nbsp;除&nbsp;此&nbsp;用&nbsp;户</a>
        <a class="btn btn-primary jin-btn"  href="?p=Admin&c=Rbac&a=selectUser">返&nbsp;回</a>
    </form>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
>
    $("select option[value='<?php echo $_smarty_tpl->tpl_vars['suu']->value['role_id'];?>
']").attr("selected", true);//默认选中
<?php echo '</script'; ?>
><?php }
}
