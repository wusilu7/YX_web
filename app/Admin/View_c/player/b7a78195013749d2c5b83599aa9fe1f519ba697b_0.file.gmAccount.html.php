<?php
/* Smarty version 3.1.30, created on 2024-04-24 17:49:26
  from "D:\pro\WebSiteYiXing\app\Admin\View\player\gmAccount.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6628d5a658d308_26437455',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b7a78195013749d2c5b83599aa9fe1f519ba697b' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\player\\gmAccount.html',
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
function content_6628d5a658d308_26437455 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>GM帐号管理</span></div>
<div class="alert alert-info">
    <div id="group_server"></div>
</div>
<ul class="nav nav-tabs">
    <li class="active"><a href="#gm_set" data-toggle="tab">GM设置</a></li>
    <li><a href="#gm_query" data-toggle="tab">GM列表</a></li>
</ul>
<div class="tab-content">
    <div class="tab-pane active" id="gm_set">
        <div class="form-horizontal">
            <div class="form-group">
                <label for="acc_name" class="col-sm-4 control-label ">帐号</label>
                <div class="col-sm-5">
                    <input id="acc_name" class="form-control" placeholder="请输入玩家帐号"/>
                </div>
            </div>
            <div class="form-group">
                <label for="acc_search" class="col-sm-4 control-label "></label>
                <div class="col-sm-5">
                    <button id="acc_search" class="btn btn-primary">查找</button>
                </div>
            </div>
            <div id="content"></div>
        </div>
    </div>
    <div class="tab-pane" id="gm_query">
        <div class="table-responsive">
            <table class="table table-hover text-center">
                <thead>
                <tr>
                    <th>GM帐号</th>
                    <th>GM类型</th>
                </tr>
                </thead>
                <tbody id="content_q"></tbody>
            </table>
        </div>
    </div>
</div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ①在GM设置页面查找想要设置为GM的帐号，在权限选项中选择完毕后请点击<b>保存配置</b>。
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    $(document).ready(gsSelect('#group', '#server'));
    $("#acc_search").on('click', function () {
        if ($.trim($("#acc_name").val()) === '') {
            layer.alert("请输入玩家帐号！", {icon: 0});
        } else {
            gm();
        }
    });
    function gm() {
        $.ajax({
            type: "POST",
            url: location.href + '&jinIf=9121',
            data: {acc_name: $("#acc_name").val()},
            dataType: 'json',
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (json) {
                layer.closeAll('loading');
                var c = '';
                c +=
                    '<div class="form-group">' +
                    '<label class="col-sm-4 control-label ">帐号</label>' +
                    '<div class="col-sm-5">' +
                    '<div id="acc_name1" class="form-control">' + json.acc_name + '</div>' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="auth" class="col-sm-4 control-label ">权限</label>' +
                    '<div class="col-sm-5">' +
                    '<select id="auth">' +
                    '</select>' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="update" class="col-sm-4 control-label "></label>' +
                    '<div class="col-sm-5">' +
                    '<button id="update" class="btn btn-danger">保存设置</button>' +
                    '</div>' +
                    '</div>';
                obj = {dom: "#auth", url: location.href + "&jinIf=941", val: json.auth};
                jinSelect(obj);
                $('#content').html(c);
                $("#update").on('click', function () {
                    var data = {
                        acc_name: $("#acc_name1").text(),
                        auth: $("#auth").val()
                    };
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=913",
                        data: data,
                        dataType: 'json',
                        success: function () {
                            layer.alert("设置成功");
                        },
                        error: function () {
                            layer.alert("设置失败");
                        }
                    });
                });
            },
            error: function () {
                layer.closeAll('loading');
                layer.msg('数据获取失败，请勿频繁刷新');
            }
        });
    }
    //切换页面
    $('ul').on('click', 'a[href="#gm_query"]', function () {
        jsonQuery();
    });
    $("#server").on('change', function () {
        if ($("#gm_query").hasClass("active")) {
            jsonQuery();
        }
    });
    function jsonQuery() {
        var url = location.href + "&jinIf=9122";
        var arr = ['acc_name', 'auth'];
        var id = "#content_q";
        var data = {};
        $(document).ready(noPageContentList(url, data, id, arr));
    }
<?php echo '</script'; ?>
><?php }
}
