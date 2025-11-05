<?php
/* Smarty version 3.1.30, created on 2024-08-15 15:01:28
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\signin\signin.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66bda7c8886ab9_31242926',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '79c0a458cc9b3952f6d936450322b1f8d0546f84' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\signin\\signin.html',
      1 => 1723704877,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_66bda7c8886ab9_31242926 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- |↑↑↑|上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <!-- |↓↓↓|IE 兼容模式 -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- |↓↓↓|国产浏览器高速模式 -->
    <meta name="renderer" content="webkit">
    <meta name="description" content="XOA">
    <meta name="keywords" content="XOA">
    <link rel="stylesheet" type="text/css" href="<?php echo CSS;?>
jin/0.signin.css">
    <title>登录 - XOA </title>
</head>
<body>
<div id="box"></div>
<div class="cent-box">
    <div class="cent-box-header">
        <h1 class="main-title1 hide">XOA</h1>
    </div>
    <div class="cont-main clearfix" >
        <div class="group">
            <div class="group-ipt">
                <input type="text" name="user_id" id="user_id" placeholder="帐号" value="<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
" required>
            </div>
            <div class="group-ipt">
                <input type="password" name="password" id="password" placeholder="密码" required>
            </div>
        </div>
        <div class="button">
            <button type="button" id="button">登&nbsp;录</button>
        </div>
        <div class="remember clearfix">
            <label class="forgot-password">
                <a href="#" id="forget_pwd">忘记密码？</a>
            </label>
        </div>
    </div>
</div>
<div class="footer">
    <p>Copyright &copy; 2017 XuanQu Net. All rights reserved.</p>
</div>
</body>
</html>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
signin/particles.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
signin/background.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
jquery-3.2.1.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
layer/layer.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
    $("#button").click(function () {
        var data = {};
        data.user_id = $('#user_id').val();
        data.password = $('#password').val();

        $.ajax({
            type: "post",
            async: true,
            url: '/?p=Admin&c=Signin&a=signinSure',
            data: data,
            dataType: "json",
            success: function (json) {
                if (json == 1) {
                    location.href = '?p=Admin&c=Index&a=index';
                }
                if (json == 2) {
                    layer.msg('密码错误', {
                        offset: 't',
                        anim: 6
                    });
                }
                if (json == 3) {
                    layer.msg('帐号不存在', {
                        offset: 't',
                        anim: 6
                    });
                }
                if (json == 4) {
                    layer.msg('帐号禁止登录', {
                        offset: 't',
                        anim: 6
                    });
                }
            }
        });
    });
    $("#forget_pwd").click(function () {
        layer.tips('请联系后台管理员', '#forget_pwd', {
          tips: [2, '#78BA32']
        });
    });  
    
<?php echo '</script'; ?>
><?php }
}
