<?php
/* Smarty version 3.1.30, created on 2023-04-21 18:58:08
  from "/lnmp/www/app/Admin/View/rbac/changelog.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_64426c4022aa04_12238356',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '49ca4c468eb6b209fae63d3c7d8bb6c5a522a6d2' => 
    array (
      0 => '/lnmp/www/app/Admin/View/rbac/changelog.html',
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
function content_64426c4022aa04_12238356 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.99.changelog.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>更新日志</span></div>
<div class="col-sm-8 col-sm-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">更新日志</div>
        <div id="content" class="panel-body">
        </div>
    </div>
</div>
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    $(document).ready(getChange());
    function getChange() {
        var c = '';
        $.ajax({
            type: "post",
            url: location.href + "&jinIf=912",
            dataType: "json",
            success: function (json) {
                for (var i = 0; i < json.length; i++) {   //取数据填表
                    c += '<h4>' + json[i]['version'] + '</h4>';
                    c += '<div class="text">';
                    for (var j = 0; j < json[i]['content'].length; j++) {
                        c += '<div>' + (j + 1) + '. ' + json[i]['content'][j] + '</div>';
                    }
                    c += '</div>';
                }
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
