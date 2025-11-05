<?php
/* Smarty version 3.1.30, created on 2023-03-14 13:32:15
  from "/lnmp/www/app/Admin/View/rbac/selectUser.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_641006df53df34_80638255',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0242ad07671b7fe2036541adeac9a089e6d9ca75' => 
    array (
      0 => '/lnmp/www/app/Admin/View/rbac/selectUser.html',
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
function content_641006df53df34_80638255 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->

<div class="jin-content-title"><span>用户管理</span></div>
<!--栅格搭框架-->
<div class="col-sm-10  col-md-10  col-lg-10 col-sm-offset-1 col-md-offset-1 col-lg-offset-1">
    <div class="table-responsive">
        <table class="table table-striped text-center">
            <thead>
            <tr>
                <th>编号</th>
                <th>帐号</th>
                <th>姓名</th>
                <th>角色</th>
                <th>创建时间</th>
                <th>最近登录时间</th>
                <th>最近登录IP</th>
                <th>禁登</th>
                <th>修改</th>
            </tr>
            </thead>
            <tbody>
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['su']->value, 'u');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['u']->value) {
?>
            <tr>
                <td><?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['u']->value['user_id'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['u']->value['name'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['u']->value['role_name'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['u']->value['create_time'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['u']->value['last_login_time'];?>
</td>
                <td><?php echo $_smarty_tpl->tpl_vars['u']->value['last_login_ip'];?>
</td>
                <td>
                    <?php if ($_smarty_tpl->tpl_vars['u']->value['is_valid'] == 1) {?>
                    <button class="btn btn-success stopUser" data-data-name="<?php echo $_smarty_tpl->tpl_vars['u']->value['name'];?>
" data-data-id="<?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
">有效帐号</button>
                    <?php } else { ?>
                    <button class="btn btn-danger recoveryUser" data-data-name="<?php echo $_smarty_tpl->tpl_vars['u']->value['name'];?>
" data-data-id="<?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
">无效帐号</button>
                    <?php }?>

                </td>
                <td><a href="?p=Admin&c=Rbac&a=selectUser&jinIf=112&id=<?php echo $_smarty_tpl->tpl_vars['u']->value['id'];?>
"><span
                        class="glyphicon glyphicon-edit"></span></a>
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
    <a class="btn btn-primary pull-right" href="?p=Admin&c=Rbac&a=selectUser&jinIf=111">添&nbsp;加&nbsp;用&nbsp;户</a>
</div>
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    $(document).ready(function () {
        //btn组
        $('.stopUser').on('click', function () {//
            var id = $(this).attr("data-data-id");
            layer.alert('是否禁止【'+$(this).attr("data-data-name")+'】登录', {icon: 0, btn: ['确定', '取消']}, function () {
                $.ajax({
                    type: "GET",
                    url: location.href + "&jinIf=114",
                    data: {
                        id: id
                    },
                    success: function (res) {
                        layer.alert('禁止登录成功', {icon: 1}, function (index) {
                            layer.close(index);
                            window.location.reload();

                        });

                    }
                });
            });
        })


        $('.recoveryUser').on('click', function () {//
            var id = $(this).attr("data-data-id");
            layer.alert('是否恢复【'+$(this).attr("data-data-name")+'】登录', {icon: 0, btn: ['确定', '取消']}, function () {
                $.ajax({
                    type: "GET",
                    url: location.href + "&jinIf=115",
                    data: {
                        id: id
                    },
                    success: function (res) {
                        layer.alert('恢复登录成功', {icon: 1}, function (index) {
                            layer.close(index);
                            window.location.reload();
                        });

                    }
                });
            });
        })
    });
<?php echo '</script'; ?>
>
<!--|↑↑↑↑↑↑|-->
<?php }
}
