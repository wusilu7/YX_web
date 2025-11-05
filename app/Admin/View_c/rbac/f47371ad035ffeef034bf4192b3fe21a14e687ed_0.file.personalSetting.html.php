<?php
/* Smarty version 3.1.30, created on 2023-06-07 15:54:18
  from "/lnmp/www/app/Admin/View/rbac/personalSetting.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_648037aa314be6_49998228',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f47371ad035ffeef034bf4192b3fe21a14e687ed' => 
    array (
      0 => '/lnmp/www/app/Admin/View/rbac/personalSetting.html',
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
function content_648037aa314be6_49998228 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.16.personalsetting.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->

	<div class="col-sm-4  col-md-4  col-lg-4 col-sm-offset-4 col-md-offset-4 col-lg-offset-4">
	    <div class="jin-content-title"><span>个人设置</span></div>
        <div class="input-group jin-input">
            <span class="input-group-addon">帐号</span>
            <input type="text" class="form-control" disabled="disabled" value="<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
" id="user_id" required>
        </div>
        <div class="input-group  jin-input">
            <span class="input-group-addon">姓名</span>
            <input type="text" class="form-control" disabled="disabled" value="<?php echo $_smarty_tpl->tpl_vars['name']->value;?>
" required>
        </div>
        <div class="input-group  jin-input">
            <span class="input-group-addon">密码</span>
            <input type="text" class="form-control" id="password" placeholder="如不修改密码请勿填写此项">
        </div>
        <button class="btn btn-success jin-btn" type="button" id="update">修&nbsp;改</button>
        <a class="btn btn-primary jin-btn"  href="?p=Admin&c=index&a=index">返&nbsp;回</a>
	</div>
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 type="text/javascript">
    var data = {};
    $("#update").on('click', function () {
        data.password = $('#password').val();
        if (!data.password) {
            layer.alert('密码不得为空');
        } else {
            $.ajax({
                type: "post",
                url: location.href + '&jinIf=912',
                data: data,
                dataType: "json",
                success: function (json) {
                    if (json == true) {
                        layer.alert('修改成功');  
                    } else {
                        layer.alert('修改失败'); 
                    }
                }
            });
        }
    });
<?php echo '</script'; ?>
><?php }
}
