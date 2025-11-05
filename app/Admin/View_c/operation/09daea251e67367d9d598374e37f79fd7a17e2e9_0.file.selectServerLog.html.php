<?php
/* Smarty version 3.1.30, created on 2024-08-29 15:46:16
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\operation\selectServerLog.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66d02748a741f3_44373431',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '09daea251e67367d9d598374e37f79fd7a17e2e9' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\operation\\selectServerLog.html',
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
function content_66d02748a741f3_44373431 (Smarty_Internal_Template $_smarty_tpl) {
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
    .container {
        width: 800px;
        position: relative;
        padding-bottom: 20px;
    }
    .content {
        overflow: hidden;
        line-height: 1;
        /* 最多展示几行就(n*line-height)em， */
        /*height: 10em;*/
        text-align: left;
        min-height: 1em;
        max-height: 10em;
        background: lightgray;
    }
    .more{
        position: absolute;
        bottom: 0;
        right: 0;
        background: #483f3f;
        color: #e7a3a3;
    }
    .more::after{
        content: '展开';
    }
    .check{
        display: none;
    }
    .check:checked ~ .content{
        height: auto;
    }
    .check:checked ~ .more::after{
        content: '收起';
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
            <th>计数</th>
            <th>时间</th>
            <th>MSG</th>
            <th style="">logMsg</th>

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
       return  '<div style="">'+
        '<div class="container">'+
            '<input class="check" type="checkbox" id="check">'+
                // '<label class="more" for="check"></label>'+
                '<div class="content">'
        +json.logMsg+
               ' </div>'
        '</div></div>'

    // return '<div style="display: none">'
    //         +json.logMsg+
    //         '</div>'
    };
    var btn = [
        "<div class='btn-group btn-group-sm'>" +
        "<button data-type='u' class='btn btn-primary'>查看</button>" +

        "</div>"
    ];
    var arr = ['ids','gi','si','pi','deviceModel', 'code','acc','fn', 'ver','cnt', 'createtime',btn,logMsg];
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
    $("#content").on('click', 'button[data-type="u"]', function () {
        var zhuangbei = $(this).parents('tr').find('td').eq(12).text();
        // var jineng = $(this).parents('tr').find('td').eq(20).text();
        layer.open({
            area: ['1360px', '800px'],
            btn: ['确定'],
            content: '<div class="tab-content">' +
                '<div class="tab-pane active" id="nav_content1">' +
                '<div class="input-group"><span class="input-group-addon">MSG详情</span><textarea id="content0"  rows="28"  class="form-control">' + zhuangbei + '</textarea></div><br>' +
                // '<div class="input-group"><span class="input-group-addon">技能信息</span><textarea id="content0"  rows="10"  class="form-control">' + jineng + '</textarea></div><br>' +
                '</div>'
        });

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
    $('.container').on('click', 'label[class="more"]', function () {  // 基础修改
        // removeBackgroud($(this))

                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '服务器配置修改',
                    area: [layerW, layerH],
                    btn: ['修改', '取消'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content: '',
                    yes: function (index) {
                        var chk_value =[];
                        $('input[name="check"]:checked').each(function(){
                            chk_value.push($(this).val());
                        });
                        var chk_value1 =[];
                        $('input[name="device_type"]:checked').each(function(){
                            chk_value1.push($(this).val());
                        });
                        $.ajax({
                            type: "POST",
                            url: location.href + '&jinIf=913',
                            data: {
                                server_id: $('#server_id').val(),
                                name: $('#name').val(),
                                game_dn: $('#game_dn').val(),
                                game_dn2: $('#game_dn2').val(),
                                white_ip: $('#white_ip').val(),
                                white_code: $('#white_code').val(),
                                white_acc: $('#white_acc').val(),
                                game_port: $('#game_port').val(),
                                game_port2: $('#game_port2').val(),
                                soap_add: $('#soap_add').val(),
                                soap_port: $('#soap_port').val(),
                                app_version: $('#app_version').val(),
                                res_version: $('#res_version').val(),
                                sort: $('#u_sort').val(),
                                world_id: $('#world_id').val(),
                                world_id_son: $('#world_id_son').val(),
                                platfrom_id: $('#platfrom_id').val(),
                                remain: $('#remain').val(),
                                device_type:chk_value1,
                                open_other_ip:chk_value
                            },
                            beforeSend: function () {
                                layer.load(2, {
                                    shade: [0.3, '#fff']//0.3透明度的白色背景
                                });
                            },
                            success: function () {
                                layer.closeAll('loading');
                                layer.close(index);
                                layer.alert('修改成功', {icon: 1}, function (index) {
                                    layer.close(index);
                                    noPageContentList(url, data, id, arr);
                                });
                            }
                        });
                    },
                    cancel: function () {
                    }
                });

    });

<?php echo '</script'; ?>
><?php }
}
