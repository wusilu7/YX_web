<?php
/* Smarty version 3.1.30, created on 2024-04-24 18:12:47
  from "D:\pro\WebSiteYiXing\app\Admin\View\test\soapTest.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6628db1f2bee54_19244977',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'da84e6482499a97128c18cb90618da000d9f30a1' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\test\\soapTest.html',
      1 => 1704262933,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_6628db1f2bee54_19244977 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.93.soapTest.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>SOAP测试</span></div>
<div class="alert alert-info">
    <div id="group_server_2"></div>
</div>
<div class="form-horizontal col-sm-8 col-sm-offset-2">
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">URL</span>
            <input type="text" class="form-control" id="url" placeholder="SOAP地址" required>
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">OPT</span>
            <input type="text" class="form-control" id="opt" placeholder="枚举值" required>
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">arg1</span>
            <input type="text" class="form-control" id="arg1" placeholder="参数一" required>
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">arg2</span>
            <input type="text" class="form-control" id="arg2" placeholder="参数二" required>
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">arg3</span>
            <input type="text" class="form-control" id="arg3" placeholder="参数三" required>
        </div>
    </div>
    <div class="form-group">
        <div class="input-group">
            <span class="input-group-addon">arg4</span>
            <input type="text" class="form-control" id="arg4" placeholder="参数四" required>
        </div>
    </div>
    <div class="form-group">
        <a id="send" class="btn btn-danger center">发送</a>
    </div>
    <div class="form-group">
        <textarea class="form-control" id="result" rows="4" placeholder="返回结果显示区" readonly></textarea>
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    $(function () { //ready
        gsSelect('#group', '#server');
        var arr = ['url', 'opt', 'arg1', 'arg2', 'arg3', 'arg4'];
        for (var i = 0; i < arr.length; i++) {
            $('#' + arr[i]).val($.cookie('soap_' + arr[i]));
        }
    });
    $('#server').change(function() {
        soapTest();
    });

    function soapTest() {
        $.ajax({
            type: "POST",
            url : location.href + "&jinIf=912",
            data: {si: $('#server').val()},
            dataType: 'json',
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (res) {
                layer.closeAll('loading');
                $("#url").val(res.url);
            },
            error: function () {
                layer.closeAll('loading');
                layer.alert("请选择服务器");
            }
        });
    }
    $('#send').on('click', function () {
        var data = {
            url : $("#url").val(),
            opt : $("#opt").val(),
            arg1: $("#arg1").val(),
            arg2: $("#arg2").val(),
            arg3: $("#arg3").val(),
            arg4: $("#arg4").val()
        };
        $.ajax({
            type: "POST",
            url : location.href + "&jinIf=931",
            data: data,
            dataType: 'json',
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (res) {
                layer.closeAll('loading');
                $("#result").text(JSON.stringify(res, null, "\t"));
            },
            error: function () {
                layer.closeAll('loading');
                layer.alert("发送失败，请检查参数是否正确填写！");
            }
        });
    });
<?php echo '</script'; ?>
>
<?php }
}
