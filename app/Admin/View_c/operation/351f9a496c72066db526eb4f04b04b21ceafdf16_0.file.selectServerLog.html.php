<?php
/* Smarty version 3.1.30, created on 2024-01-06 10:31:41
  from "C:\Users\Administrator\Desktop\pro\WebSiteYiXing\app\Admin\View\operation\selectServerLog.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6598bb8d805f65_32532057',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '351f9a496c72066db526eb4f04b04b21ceafdf16' => 
    array (
      0 => 'C:\\Users\\Administrator\\Desktop\\pro\\WebSiteYiXing\\app\\Admin\\View\\operation\\selectServerLog.html',
      1 => 1704262932,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_6598bb8d805f65_32532057 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->

<style type="text/css">
    .alert-info{
        color: white;
    }
    .form-group{
        margin-bottom: 35px;
    }
    .col-sm-1 {
        width: 90px;
        padding-top: 8px;
    }
</style>
<div class="jin-content-title"><span>Server日志</span></div>
<div class="alert alert-info">
    <div class="form-group" id="group_server_5"></div>
</div>
<!--查询div-->
<hr/>
<div class="jin-search-div">
    <input id="code" type="text" class="form-control jin-search-input" placeholder="code">
    <input id="acc" type="text" class="form-control jin-search-input" placeholder="acc">
    <input id="fn" type="text" class="form-control jin-search-input" placeholder="fn">
    <input id="ver" type="text" class="form-control jin-search-input" placeholder="ver">
    <input id="logMsg" type="text" class="form-control jin-search-input" placeholder="logMsg">
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    <button id="del_log" class="btn btn-danger">删除特定时间前日志</button>
    <button id="del_log1" class="btn btn-danger">删除特定关键词日志</button>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>id</th>
            <th>渠道</th>
            <th>服务器</th>
            <th>平台</th>
            <th>型号</th>
            <th>code</th>
            <th>acc</th>
            <th>fn</th>
            <th>ver</th>
            <th>logMsg</th>
            <th>时间</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>

<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
    gsSelect3('#g', '#p', '#s');
    var url = location.href + "&jinIf=912";
    var logMsg = function (json) {
        return '<div style="text-align:left;">'+json.logMsg+'</div>'
    };
    var arr = ['ids','gi','si','pi','deviceModel', 'code','acc','fn', 'ver', logMsg, 'createtime'];
    var id = ["#content", "#page"];
    var data = {};
    function getDaily() {
        data.page       = 1;
        data.pi         = $('#p').val();
        data.si         = $("#s").val();
        data.gi         = $("#g").val();
        data.code         = $('#code').val();
        data.acc         = $('#acc').val();
        data.fn         = $("#fn").val();
        data.ver         = $("#ver").val();
        data.logMsg         = $("#logMsg").val();
        tableList(url, data, id, arr);
    }

    // 普通查询
    $("#jin_search").click(function () {
        data.check_type = 912;  // 普通查询
        getDaily();
    });

    $("#del_log").click(function () {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '选择一个时间',
            area: ['400px', '300px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: false, //点击遮罩关闭
            content: '<div class="jin-child"><div class="input-group"><span class="input-group-addon">时间</span>' +
            '<input type="text" id="deltime" class="form-control jin-datetime-long"></div>' +
            '</div>',
            yes: function (index) {
                var deltime = $("#deltime").val();
                layer.close(index);
                layer.alert('确认删除？', {
                    icon: 0,
                    btn: ['确定', '取消']
                }, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=914",
                        data: {
                            is_type:0,
                            deltime: deltime,
                            gi:$("#g").val()
                        },
                        beforeSend: function () {
                            layer.load(2, {
                                shade: [0.3, '#fff']//0.3透明度的白色背景
                            });
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.alert('删除成功', {icon: 1}, function (index) {
                                layer.close(index);
                                getDaily();
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
        $(document).ready(calendarOne('hour', "#deltime"));
    });


    $("#del_log1").click(function () {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '选择一个时间',
            area: ['400px', '300px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: false, //点击遮罩关闭
            content: '<div class="jin-child"><div class="input-group"><span class="input-group-addon">关键词</span>' +
            '<input type="text" id="deltime" class="form-control"></div>' +
            '</div>',
            yes: function (index) {
                var deltime = $("#deltime").val();
                layer.close(index);
                layer.alert('确认删除？', {
                    icon: 0,
                    btn: ['确定', '取消']
                }, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=914",
                        data: {
                            is_type:1,
                            deltime: deltime,
                            gi:$("#g").val()
                        },
                        beforeSend: function () {
                            layer.load(2, {
                                shade: [0.3, '#fff']//0.3透明度的白色背景
                            });
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.alert('删除成功', {icon: 1}, function (index) {
                                layer.close(index);
                                getDaily();
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
    });


<?php echo '</script'; ?>
><?php }
}
