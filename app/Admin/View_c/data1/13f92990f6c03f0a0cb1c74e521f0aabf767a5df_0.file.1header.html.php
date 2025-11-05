<?php
/* Smarty version 3.1.30, created on 2024-02-27 17:29:51
  from "/lnmp/www/app/Admin/View/common/1header.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65ddab8f39cab9_87671517',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '13f92990f6c03f0a0cb1c74e521f0aabf767a5df' => 
    array (
      0 => '/lnmp/www/app/Admin/View/common/1header.html',
      1 => 1709020481,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_65ddab8f39cab9_87671517 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />-->
    <!-- |↑↑↑|上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <!-- |↓↓↓|IE 兼容模式 -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- |↓↓↓|国产浏览器高速模式 -->
    <meta name="renderer" content="webkit">
    <!-- Bootstrap -->
    <link href="<?php echo CSS;?>
layui.css" rel="stylesheet">
    <link href="<?php echo CSS;?>
bootstrap.css" rel="stylesheet">
    <link href="<?php echo CSS;?>
bootstrap-datetimepicker.css" rel="stylesheet">
    <link href="<?php echo CSS;?>
bootstrap-switch.min.css" rel="stylesheet">
    <link href="<?php echo CSS;?>
select2.min.css" rel="stylesheet">
    <link href="<?php echo CSS;?>
checkbox.css" rel="stylesheet">
    <link href="<?php echo CSS;?>
jin/1.header.css<?php echo HTML_VERSION;?>
" rel="stylesheet">
    <link href="<?php echo CSS;?>
jin/2.sidebar.css<?php echo HTML_VERSION;?>
" rel="stylesheet">
    <link href="<?php echo CSS;?>
jin/3.00.content.css<?php echo HTML_VERSION;?>
" rel="stylesheet">
    <link href="<?php echo CSS;?>
jin/4.footer.css<?php echo HTML_VERSION;?>
" rel="stylesheet">
    <link href="<?php echo CSS;?>
bootstrap-select.min.css" rel="stylesheet">
    <style type="text/css">
        a:hover,a:focus{
            text-decoration : none
        }
        @media all and (max-device-width: 1000px){
            .shouji{
                width:89%;
                margin-left:11%;
            }
        }
        @media all and (max-device-width: 1000px){
            .select_group_div .bs-searchbox{
                display: none !important;
            }
            .select_server_div .bs-searchbox{
                display: none !important;
            }
        }
        .select2-search__field{
            display: none !important;
        }
    </style>
    <title><?php if ($_smarty_tpl->tpl_vars['breadcrumb']->value['parent'] == '') {?>首页<?php } else {
echo $_smarty_tpl->tpl_vars['breadcrumb']->value['son'];
}?></title>
    <?php echo '<script'; ?>
>
        window.onload=function () {
            startTime()
        }
        //时钟
        function startTime() {
            var today = new Date();
            var y = today.getFullYear();
            var month = today.getMonth() + 1;
            var d = today.getDate();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            $("#time1").html(y + "-" + month + "-" + d + " " + h + ":" + m + ":" + s);
            t = setTimeout(function () {
                startTime()
            }, 1000);
        }
        function checkTime(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
        }
    <?php echo '</script'; ?>
>
</head>

<body>
<!--顶部导航条-->
<div class="navbar navbar-inverse navbar-fixed-top" style="background-color:#23262E;border-bottom-color:#393D49;box-shadow:0 0 0px 0px #ccc;">
    <!-- 左LOGO -->
    <div class="navbar-header nav-title ">
<!--        <a class="navbar-brand jin-logo" style="margin-left: 0px;font-size: 36px;font-family: STHeiti;color: rgba(78,84,101,0)" href="?p=Admin&c=Index&a=index">魔魂网络</a>-->
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <span id="time1" style="color:#9d9d9d;font-size: 25px; margin-right: 10px; float: left;margin-top: 7px;margin-left: 10px"></span>
        <span style="color: #9d9d9d;font-size: 25px; float: left;margin-top: 7px; "><span style="font-size: 25px;">|</span>&nbspIP:<?php echo $_smarty_tpl->tpl_vars['ip']->value;?>
</span>
    </div>
    <!-- 右个人信息 -->
    <div class="collapse navbar-collapse navbar-right is-collapse">
        <ul class="nav navbar-nav navbar-right">
            <li><a style="background-color:#009688;color:white">你好 , <?php echo $_SESSION['name'];?>
</a></li>
            <!--<li><a href="?p=Admin&c=Rbac&a=personalSetting">个人设置</a></li>-->
            <li><a href="?p=Admin&c=Signin&a=signout" style="margin-right:20px">安全退出</a></li>
        </ul>
    </div>
</div>

<!--START菜单栏和内容区-->
<div class="container-fluid">
    <!--菜单栏S-->
    <div class="col-sm-1">
        <ul class="layui-nav layui-nav-tree layui-nav-side" style="margin-top:50px; width: 150px;" lay-shrink="all">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['menu']->value, 'm', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['m']->value) {
?>
            <li class="layui-nav-item <?php if ($_smarty_tpl->tpl_vars['m']->value['parent']['controller'] == $_smarty_tpl->tpl_vars['get_c']->value) {?>layui-nav-itemed<?php }?>">
                <a href="javascript:;"><?php echo $_smarty_tpl->tpl_vars['m']->value['parent']['per_name'];?>
</a>
                <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['m']->value, 'c', false, 'key');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['c']->value) {
?>
                <?php if ($_smarty_tpl->tpl_vars['key']->value !== 'parent' && $_smarty_tpl->tpl_vars['c']->value['p'] !== '') {?>
                <dl class="layui-nav-child" style="margin-bottom:0px">
                    <dd id ="<?php echo $_smarty_tpl->tpl_vars['c']->value['p'];
echo $_smarty_tpl->tpl_vars['c']->value['c'];
echo $_smarty_tpl->tpl_vars['c']->value['a'];?>
">
                        <a href='?p=<?php echo $_smarty_tpl->tpl_vars['c']->value['p'];?>
&c=<?php echo $_smarty_tpl->tpl_vars['c']->value['c'];?>
&a=<?php echo $_smarty_tpl->tpl_vars['c']->value['a'];?>
' target="_self"><?php echo $_smarty_tpl->tpl_vars['c']->value['per_name'];?>
</a>
                    </dd>
                </dl>
                <?php }?>
                <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

            </li>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

        </ul>
    </div>


    <!--菜单栏E-->

    <!--START主体内容区-->
    <div class="col-sm-11" style="margin-left: 140px;">
        <!--面包屑导航S-->
        <ol class="breadcrumb">
            <li><a href="?p=Admin&c=Index&a=index">首页</a></li>
            <?php if ($_smarty_tpl->tpl_vars['breadcrumb']->value['parent'] != '') {?>
            <li class="active"><?php echo $_smarty_tpl->tpl_vars['breadcrumb']->value['parent'];?>
</li>
            <li class="active"><?php echo $_smarty_tpl->tpl_vars['breadcrumb']->value['son'];?>
</li>
            <?php }?>
        </ol>
        <!--面包屑导航E-->
        <div class="clearfix">
            <!--接下面--><?php }
}
