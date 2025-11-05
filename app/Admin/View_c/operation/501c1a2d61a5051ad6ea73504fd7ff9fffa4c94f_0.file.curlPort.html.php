<?php
/* Smarty version 3.1.30, created on 2024-09-19 14:25:47
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\operation\curlPort.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66ebc3ebe60ac6_50812970',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '501c1a2d61a5051ad6ea73504fd7ff9fffa4c94f' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\operation\\curlPort.html',
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
function content_66ebc3ebe60ac6_50812970 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.08.group.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>测试请求端口</span></div>

<div style="width: 300px; display: inline-block; float: left;">
    <input id="domain" type="text" class="form-control jin-search-input" placeholder="域名"><hr>
    <input id="ip" type="text" class="form-control jin-search-input" placeholder="ip"><hr>
    <textarea id="port" cols="30" rows="20" placeholder="端口"></textarea><br>
    <a id="jin_search" class="btn btn-success">请求</a>
</div>
<div id="showinfo" style="width: 900px; display: inline-block; float: left;">

</div>
<div id="page"></div>

<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>

    $("#jin_search").on('click', function () {
        $.ajax({
            type: "POST",
            url: location.href + '&jinIf=912',
            data: {
                domain:$("#domain").val(),
                ip:$("#ip").val(),
                port:$("#port").val().split('\n')
            },
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            dataType: "json",
            success: function (json) {
                console.log(json);
                layer.closeAll('loading');
                var c='';
                for (var i=0;i<json.length;i++){
                    c+='<span style="color: red">'+json[i]['port']+'</span>'+'-------'+json[i]['info']+'<br>'
                }
                $("#showinfo").html(c);
            }
        });
    });




<?php echo '</script'; ?>
><?php }
}
