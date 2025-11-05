<?php
/* Smarty version 3.1.30, created on 2024-08-15 14:58:22
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\index\index.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66bda70e863af1_18470184',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b6d6167d32d5b7b600c464834f35bdfc00b79c2b' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\index\\index.html',
      1 => 1723704876,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_66bda70e863af1_18470184 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.66.index.css" rel="stylesheet">
<style type="text/css">
    .wrong_red{
        color: red;
        margin-left:30px 
    }
    .wrong_green{
        color: green;
        margin-left:30px
    }
</style>

<!--更新说明-->
<div class="jin-change-div col-sm-6 col-sm-offset-3">
    <div class="panel panel-default">
        <div class="panel-heading">最新更新</div>
        <div id="content" class="panel-body"></div>
    </div>
    <?php if ($_smarty_tpl->tpl_vars['role_id']->value == 1) {?>
    <div class="panel panel-default">
        <div class="panel-heading">错误日志</div>
        <div id="content_wrong_daily" class="panel-body"></div>
        <div id="content_wrong_device" class="panel-body"></div>
        <div id="content_wrong_acc" class="panel-body"></div>
        <div id="content_wrong_char" class="panel-body"></div>
    </div>
    <?php }?>
</div>

<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
>
    $(document).ready(getNewChange());
    //最新更新
    function getNewChange() {
        var c = '';
        $.ajax({
            type: "post",
            url: "?p=Admin&c=Rbac&a=changelog&jinIf=912",
            dataType: "json",
            success: function (json) {
                c +=
                    '<div class="change-title">版本号：' + json[0]['version'] + '</div>' +
                    '<div class="change-date"> 更新日期：' + json[0]['date'] + '</div>';
                c += '<div class="change-text">';
                for (var j = 0; j < json[0]['content'].length; j++) {
                    c += '<div>' + (j + 1) + '. ' + json[0]['content'][j] + '</div>';
                }
                c += '</div>';
                c += '<a href="?p=Admin&c=Rbac&a=changelog" class="center btn btn-default">查看详情</a>';
                $("#content").html(c);
            },
            error: function () {
                layer.msg('数据获取失败，请勿频繁刷新');
            }
        });
    }
<?php echo '</script'; ?>
><?php }
}
