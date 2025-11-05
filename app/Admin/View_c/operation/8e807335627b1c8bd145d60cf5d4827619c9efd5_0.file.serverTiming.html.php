<?php
/* Smarty version 3.1.30, created on 2023-08-12 12:55:04
  from "/lnmp/www/app/Admin/View/operation/serverTiming.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_64d710a8dcd795_97857776',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '8e807335627b1c8bd145d60cf5d4827619c9efd5' => 
    array (
      0 => '/lnmp/www/app/Admin/View/operation/serverTiming.html',
      1 => 1678771401,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_64d710a8dcd795_97857776 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<link href="<?php echo CSS;?>
jin/3.05.serverswitch.css" rel="stylesheet">
<div class="jin-content-title"><span>服务器定时设置</span></div>

<div class="jin-server-select">
    <?php if ($_smarty_tpl->tpl_vars['Mobel']->value == 'Mobel') {?>
    <div class="form-group" id="group_server_6_mobel"></div>
    <?php } else { ?>
    <div class="form-group" id="group_server_6"></div>
    <?php }?>
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    <hr>
    <button data-type="all_open" class="btn btn-success">定时开服</button>
    &nbsp;
    &nbsp;
    <button data-type="all_shut" class="btn btn-danger">定时关服</button>
    &nbsp;
    &nbsp;
    <label style="cursor: pointer;"><input name="filter_type" type="radio" value="101" checked/>检测执行</label>
    <label style="cursor: pointer;"><input name="filter_type" type="radio" value="102" />强制执行</label>
    <hr>
    <button data-type="all_maintenance" class="btn btn-success">定时维护</button>
    &nbsp;
    &nbsp;
    <button data-type="all_cancel" class="btn btn-danger">定时取消维护</button>
    &nbsp;
    &nbsp;
    <button data-type="all_show" class="btn btn-success">定时显示</button>
    &nbsp;
    &nbsp;
    <button data-type="all_hide" class="btn btn-danger">定时隐藏</button>
    &nbsp;
    &nbsp;
    <button data-type="all_Online" class="btn btn-success">定时开启汇总</button>
    &nbsp;
    &nbsp;
    <button data-type="all_NoOnline" class="btn btn-danger">定时关闭汇总</button>
    &nbsp;
    &nbsp;
    <button data-type="all_isNew" class="btn btn-success">定时新服标记</button>
    &nbsp;
    &nbsp;
    <!--<button data-type="all_Anchor" class="btn btn-success">定时应用主播</button>-->
    &nbsp;
    &nbsp;
    <button data-type="all_Opentime" class="btn btn-success">定时设置开服时间</button>
    &nbsp;
    &nbsp;
    <button data-type="all_ShowNotice" class="btn btn-success">定时设置显示公告</button>
    &nbsp;
    &nbsp;
    <button data-type="all_SetActiveTime" class="btn btn-success">定时设置活动时间</button>
    &nbsp;
    &nbsp;
    <!--<button data-type="all_groupNotice" class="btn btn-success">定时修改福利更新公告</button>-->
    <hr>
<div class="table-responsive">
    <table class="table table-bordered table-hover text-center jin-server-table">
        <thead>
        <tr>
            <th class="jin-server-column1">
                <input id="all_choose" type="checkbox">
                <label for="all_choose">全选</label>
            </th>
            <th class="jin-server-column1">序号</th>
            <th>服务器名称</th>
            <th class="jin-server-column3">服务器ID</th>
            <th>渠道名称</th>
            <th>游戏地址端口</th>
            <th>状态说明</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
    var url = location.href + '&jinIf=912';
    var id = "#content";
    var data = {};
    //选服下拉框
    $(document).ready(gsSelect3('#g'));  //页面加载完成后，调用groupSelect()函数，这个函数在jin-select.js中有封装
    $("#jin_search").on('click', function () {    //根据渠道读取相应渠道的服务器信息
        data.group_id = $("#g").val();
        $.cookie('cookie_g', data.group_id, {expires: 30});
        var c = '';
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: "json",
            success: function (json) {
                if(json.length>=1){
                    for (var i = 0; i < json.length; i++) {
                        c +=
                            '<tr>' +
                            '<td><input type="checkbox" value="' + json[i]['server_id'] + '" /></td>' +
                            '<td>' + json[i]['sort'] + '</td>' +
                            '<td>' + json[i]['name'] + '</td>' +
                            '<td>' + json[i]['server_id'] + '</td>' +
                            '<td>' + json[i]['group_name'] + '</td>' +
                            '<td>' + json[i]['game_dn'] + ':' + json[i]['game_port'] + '</td>' +
                            '<td>' + json[i]['soap_add'] + ':' + json[i]['soap_port'] + '</td>' +
                            '</tr>';
                    }
                    $(id).html(c);
                }else {
                    $(id).html('');
                }
                if ($.cookie('cookie_gss')) {
                    var gs = eval('[' + $.cookie('cookie_gss') + ']');
                    for (var i in gs) {
                        $('#content').find('input[type="checkbox"][value="'+gs[i]+'"]').attr('checked', true);
                        $('#content').find('input[type="checkbox"][value="'+gs[i]+'"]').parent().parent().attr('style', 'background: #aba5618c');
                    }
                }
            }
        });
    });

    // 全选
    $('#all_choose').click(function() {
        var check_on = $(this).is(':checked');
        if (check_on) {
            $('#content').find('input[type="checkbox"]').attr('checked', true);
        } else {
            $('#content').find('input[type="checkbox"]').attr('checked', false);
        }
    });

    // 获取选中的服务器
    function getChoose() {
        var server_id = '';
        var name = '';
        $('#content input[type="checkbox"]:checked').each(function(index, el) {
            if (index == 0) {
                server_id = $(el).val();
                name = $(el).parent('td').next().next().text();
            } else {
                server_id += ',' + $(el).val();
                name += ',' + $(el).parent('td').next().next().text();
            }
        });

        if (server_id == '') {
            layer.alert('请选择服务器！', {icon: 2});
            return false;
        }

        return {
            'server_id': server_id,
            'name': name
        };
    }

    // 点击批量开服
    $('button[data-type="all_open"]').click(function() {
        var arr = getChoose();
        if(arr){
            open(arr.server_id, arr.name);
        }

    });

    function open(server_id, name) {
            layer.open({
                type: 1,
                closeBtn: 2,
                title: '选择一个开服时间',
                area: ['400px', '200px'],
                btn: ['确定', '取消'],
                btnAlign: 'c',
                shadeClose: false, //点击遮罩关闭
                content: '<div class="jin-child"><div class="input-group"><span class="input-group-addon">时间</span>' +
                '<input type="text" id="time" class="form-control jin-datetime-long"></div>' +
                '</div>',
                yes: function (index) {
                    var time = $("#time").val();
                    layer.close(index);
                    layer.alert('确认在<u>' + time + '</u>开启服务器<br/>[' + name + ']？', {
                        icon: 0,
                        btn: ['确定', '取消']
                    }, function () {
                        $.ajax({
                            type: "POST",
                            url: location.href + "&jinIf=938",
                            data: {
                                gi:$("#g").val(),
                                si: server_id,
                                time: time,
                                fuc:'onServer',
                                filter_type : $('input[name=filter_type]:checked').val()
                            },
                            beforeSend: function () {
                                layer.load(2, {
                                    shade: [0.3, '#fff']//0.3透明度的白色背景
                                });
                            },
                            success: function () {
                                layer.closeAll('loading');
                                layer.alert('定时开服成功', {icon: 1}, function (index) {
                                    layer.close(index);
                                });
                            },
                            error: function () {
                                layer.closeAll('loading').msg('开服失败');
                            }
                        });
                    });
                },
                cancel: function () {
                }
            });
            $(document).ready(calendarOne('hour', "#time"));

    }

    // 点击批量关服
    $('button[data-type="all_shut"]').click(function() {
        var arr = getChoose();
        if(arr){
            shut(arr.server_id, arr.name);
        }
    });

    function shut(server_id, name) {
            layer.open({
                type: 1,
                closeBtn: 2,
                title: '选择一个关服时间',
                area: ['400px', '220px'],
                btn: ['确定', '取消'],
                btnAlign: 'c',
                shadeClose: false, //点击遮罩关闭
                content: '<div class="jin-child"><div class="input-group"><span class="input-group-addon">时间</span>' +
                '<input type="text" id="time" class="form-control jin-datetime-long"></div>' +
                '</div>',
                yes: function (index) {
                    var time = $("#time").val();
                    layer.close(index);
                    layer.alert('确认在<u>' + time + '</u>关闭服务器<br/>[' + name + ']？', {
                        icon: 0,
                        btn: ['确定', '取消']
                    }, function () {
                        $.ajax({
                            type: "POST",
                            url: location.href + "&jinIf=938",
                            data: {
                                gi:$("#g").val(),
                                si: server_id,
                                time: time,
                                fuc:'offServer',
                                filter_type : $('input[name=filter_type]:checked').val()
                            },
                            beforeSend: function () {
                                layer.load(2, {
                                    shade: [0.3, '#fff']//0.3透明度的白色背景
                                });
                            },
                            success: function () {
                                layer.closeAll('loading');
                                layer.alert('定时关服成功', {icon: 1}, function (index) {
                                    layer.close(index);
                                });
                            },
                            error: function () {
                                layer.closeAll('loading').msg('关服失败');
                            }
                        });
                    });
                },
                cancel: function () {

                }
            });
            $(document).ready(calendarOne('hour', "#time"));
    }


    // 点击批量维护
    $('button[data-type="all_maintenance"]').click(function() {
        var arr = getChoose();
        if(arr){
            maintenance(arr.server_id, arr.name);
        }
    });

    // 批量维护
    function maintenance(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '服务器维护设置',
            area: ['400px', '360px'],
            btn: ['确认维护', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child"><div class="input-group"><span class="input-group-addon">维护时间</span>' +
            '<input type="text" id="time" class="form-control jin-datetime-long"></div>' +
            '</div>'+ '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">维护说明</span><textarea id="info" rows="7"  class="form-control"></textarea></div>' +
            '</div>',
            yes: function (index) {
                var time = $("#time").val();
                var info = $("#info").val();
                layer.close(index);
                layer.alert('确认在'+[time]+'时间维护[' + name + ']服务器？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=938",
                        data: {
                            gi:$("#g").val(),
                            si: server_id,
                            time:time,
                            fuc:'maintenance',
                            info: info
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.alert('定时维护成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
        $(document).ready(calendarOne('hour', "#time"));
    }

    // 点击批量取消维护
    $('button[data-type="all_cancel"]').click(function() {
        var arr = getChoose();
        if(arr){
            cancel(arr.server_id, arr.name);
        }
    });

    // 批量取消维护
    function cancel(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '服务器取消维护设置',
            area: ['400px', '200px'],
            btn: ['确认取消维护', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child"><div class="input-group"><span class="input-group-addon">取消维护时间</span>' +
            '<input type="text" id="time" class="form-control jin-datetime-long"></div>' +
            '</div>',
            yes: function (index) {
                var time = $("#time").val();
                layer.close(index);
                layer.alert('确认在'+[time]+'时间取消维护[' + name + ']服务器？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=938",
                        data: {
                            gi:$("#g").val(),
                            si: server_id,
                            time:time,
                            fuc:'cancel',
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.alert('定时取消维护成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
        $(document).ready(calendarOne('hour', "#time"));

    }

    // 点击批量显示
    $('button[data-type="all_show"]').click(function() {
        var arr = getChoose();
        if(arr){
            show(arr.server_id, arr.name);
        }
    });

    // 批量显示
    function show(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '服务器显示设置',
            area: ['400px', '200px'],
            btn: ['确认显示', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child"><div class="input-group"><span class="input-group-addon">显示时间</span>' +
            '<input type="text" id="time" class="form-control jin-datetime-long"></div>' +
            '</div>',
            yes: function (index) {
                var time = $("#time").val();
                layer.close(index);
                layer.alert('确认在'+[time]+'时间显示[' + name + ']服务器？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=938",
                        data: {
                            gi:$("#g").val(),
                            si: server_id,
                            time:time,
                            fuc:'show'
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.alert('定时显示成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
        $(document).ready(calendarOne('hour', "#time"));

    }

    // 点击批量隐藏
    $('button[data-type="all_hide"]').click(function() {
        var arr = getChoose();
        if(arr){
            hide(arr.server_id, arr.name);
        }
    });

    // 批量隐藏
    function hide(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '服务器隐藏设置',
            area: ['400px', '200px'],
            btn: ['确认隐藏', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child"><div class="input-group"><span class="input-group-addon">隐藏时间</span>' +
            '<input type="text" id="time" class="form-control jin-datetime-long"></div>' +
            '</div>',
            yes: function (index) {
                var time = $("#time").val();
                layer.close(index);
                layer.alert('确认在'+[time]+'时间隐藏[' + name + ']服务器？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=938",
                        data: {
                            gi:$("#g").val(),
                            si: server_id,
                            time:time,
                            fuc:'hide'
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.alert('定时隐藏成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
        $(document).ready(calendarOne('hour', "#time"));

    }

    // 点击批量汇总
    $('button[data-type="all_Online"]').click(function() {
        var arr = getChoose();
        if(arr){
            Online(arr.server_id, arr.name);
        }
    });

    // 批量汇总
    function Online(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '服务器开启汇总设置',
            area: ['400px', '200px'],
            btn: ['确认开启汇总', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child"><div class="input-group"><span class="input-group-addon">开启汇总时间</span>' +
            '<input type="text" id="time" class="form-control jin-datetime-long"></div>' +
            '</div>',
            yes: function (index) {
                var time = $("#time").val();
                layer.close(index);
                layer.alert('确认在'+[time]+'时间开启[' + name + ']服务器的汇总？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=938",
                        data: {
                            gi:$("#g").val(),
                            si: server_id,
                            time:time,
                            fuc:'Online'
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.alert('定时开启汇总成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
        $(document).ready(calendarOne('hour', "#time"));

    }

    // 点击批量取消汇总
    $('button[data-type="all_NoOnline"]').click(function() {
        var arr = getChoose();
        if(arr){
            NoOnline(arr.server_id, arr.name);
        }
    });

    // 批量取消汇总
    function NoOnline(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '服务器关闭汇总设置',
            area: ['400px', '200px'],
            btn: ['确认关闭汇总', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child"><div class="input-group"><span class="input-group-addon">关闭汇总时间</span>' +
            '<input type="text" id="time" class="form-control jin-datetime-long"></div>' +
            '</div>',
            yes: function (index) {
                var time = $("#time").val();
                layer.close(index);
                layer.alert('确认在'+[time]+'时间关闭[' + name + ']服务器的汇总？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=938",
                        data: {
                            gi:$("#g").val(),
                            si: server_id,
                            time:time,
                            fuc:'NoOnline'
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.alert('定时关闭汇总成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
        $(document).ready(calendarOne('hour', "#time"));

    }


    $('button[data-type="all_isNew"]').click(function() {
        var arr = getChoose();
        if(arr){
            isNew(arr.server_id, arr.name);
        }
    });


    function isNew(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '服务器新服标记',
            area: ['400px', '200px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">时间</span><input type="text" id="time" class="form-control jin-datetime-long"></div>' +
            '<div class="input-group"><span class="input-group-addon">操作</span><select  id="isNew" class="form-control">' +
            '<option value="1">新服</option>' +
            '<option value="0">非新服</option>' +
            '</select></div>' +
            '</div>',
            yes: function (index) {
                var time = $("#time").val();
                var isNew= $("#isNew").val();
                layer.close(index);
                layer.alert('确认在'+[time]+'时间设置[' + name + ']服务器的新服标记？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=938",
                        data: {
                            gi:$("#g").val(),
                            si: server_id,
                            time:time,
                            fuc:'isNew',
                            isNew:isNew
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.alert('定时设置成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
        $(document).ready(calendarOne('hour', "#time"));

    }


    $('button[data-type="all_Anchor"]').click(function() {
        var arr = getChoose();
        if(arr){
            Anchor(arr.server_id, arr.name);
        }
    });


    function Anchor(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '服务器主播应用',
            area: ['400px', '300px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">时间</span><input type="text" id="time" class="form-control jin-datetime-long"></div>' +
            '<div class="input-group"><span class="input-group-addon">操作</span><select  id="Anchor" class="form-control">' +
            '<option value="1">有效</option>' +
            '<option value="0">无效</option>' +
            '</select></div>' +
            '<div class="input-group"><span class="input-group-addon">主播模板</span><select  id="AnchorTem" class="form-control">' +
            '<option value="0">主播模板1</option>' +
            '<option value="1">主播模板2</option>' +
            '<option value="2">主播模板3</option>' +
            '</select></div>' +
            '</div>',
            yes: function (index) {
                var time = $("#time").val();
                var Anchor = $("#Anchor").val();
                var AnchorTem = $("#AnchorTem").val();
                layer.close(index);
                layer.alert('确认在'+[time]+'时间应用[' + name + ']服务器主播？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=938",
                        data: {
                            gi:$("#g").val(),
                            si: server_id,
                            time:time,
                            fuc:'Anchor',
                            Anchor:Anchor,
                            AnchorTem:AnchorTem
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.alert('定时设置成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
        $(document).ready(calendarOne('hour', "#time"));

    }

    $('button[data-type="all_Opentime"]').click(function() {
        var arr = getChoose();
        if(arr){
            Opentime(arr.server_id, arr.name);
        }
    });


    function Opentime(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '服务器开服时间',
            area: ['400px', '300px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">定时时间</span><input type="text" id="time" class="form-control jin-datetime-long"></div>' +
            '<div class="input-group"><span class="input-group-addon">要设置的开服时间</span><input type="text" id="opentime" class="form-control jin-datetime-long"></div>' +
            '</div>',
            yes: function (index) {
                var time = $("#time").val();
                var opentime = $("#opentime").val();
                layer.close(index);
                layer.alert('确认在'+[time]+'时间应用[' + name + ']服务器开服时间？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=938",
                        data: {
                            gi:$("#g").val(),
                            si: server_id,
                            time:time,
                            fuc:'Opentime',
                            opentime:opentime

                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.alert('定时设置成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
        $(document).ready(calendarOne('hour', "#time"),calendarOne('hour', "#opentime"));

    }

    $('button[data-type="all_ShowNotice"]').click(function() {
        var arr = getChoose();
        if(arr){
            ShowNotice(arr.server_id, arr.name);
        }
    });


    function ShowNotice(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '服务器显示公告',
            area: ['400px', '200px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">时间</span><input type="text" id="time" class="form-control jin-datetime-long"></div>' +
            '<div class="input-group"><span class="input-group-addon">操作</span><select  id="isNotice" class="form-control">' +
            '<option value="1">显示</option>' +
            '<option value="0">不显示</option>' +
            '</select></div>' +
            '</div>',
            yes: function (index) {
                var time = $("#time").val();
                var isNotice= $("#isNotice").val();
                layer.close(index);
                layer.alert('确认在'+[time]+'时间设置[' + name + ']服务器的显示公告？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=938",
                        data: {
                            gi:$("#g").val(),
                            si: server_id,
                            time:time,
                            fuc:'isNotice',
                            isNotice:isNotice
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.alert('定时设置成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
        $(document).ready(calendarOne('hour', "#time"));

    }

    $('button[data-type="all_SetActiveTime"]').click(function() {
        var arr = getChoose();
        if(arr){
            ActiveTime(arr.server_id, arr.name);
        }
    });


    function ActiveTime(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '活动时间设置',
            area: ['400px', '500px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child"><div class="input-group"><span class="input-group-addon">时间</span>' +
            '<input type="text" id="time" class="form-control jin-datetime-long"></div>' +
            '</div>'+ '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">令牌老服新服时间切割</span><input type="text" id="time_token" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">宠物老服新服时间切割</span><input type="text" id="time_baby" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">悬赏任务重置</span><input type="text" id="QuestMoneyReset" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">湖中女神切割时间</span><input type="text" id="AccMoney5" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">充值双倍珍珠重置</span><input type="text" id="FirstChargeReset" class="form-control"></div>' +
            '</div>',
            yes: function (index) {
                var time = $("#time").val();
                var version = $("#version").val();
                var PassportCharge= $("#time_token").val();
                var BabyTalentSplit= $("#time_baby").val();
                var QuestMoneyReset= $("#QuestMoneyReset").val();
                var AccMoney5=$("#AccMoney5").val();
                var FirstChargeReset=$("#FirstChargeReset").val();
                layer.close(index);
                layer.alert('确认设置吗？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=938",
                        data: {
                            gi:$("#g").val(),
                            si: server_id,
                            time:time,
                            fuc:'setActiveTime',
                            PassportCharge: PassportCharge,
                            BabyTalentSplit: BabyTalentSplit,
                            QuestMoneyReset: QuestMoneyReset,
                            AccMoney5:AccMoney5,
                            FirstChargeReset:FirstChargeReset
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.alert('定时成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
        $(document).ready(calendarOne('hour', "#time"),calendarOne('hour', "#time_token"),calendarOne('hour', "#time_baby"),calendarOne('hour', "#QuestMoneyReset"),calendarOne('hour', "#AccMoney5"),calendarOne('hour', "#FirstChargeReset"));
    }

    $('button[data-type="all_groupNotice"]').click(function() {
        groupNotice();
    });

    function groupNotice() {
        if ($("#g").val() == '') {
            layer.alert('请选择渠道！', {icon: 2});
            return false;
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '定时修改福利更新公告',
            area: ['400px', '400px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child"><div class="input-group"><span class="input-group-addon">时间</span>' +
            '<input type="text" id="time" class="form-control jin-datetime-long"></div>' +
            '</div>'+ '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">公告内容</span><textarea id="groupNotice" rows="7"  class="form-control"></textarea></div>' +
            '</div>',
            yes: function (index) {
                var time = $("#time").val();
                var groupNotice = $("#groupNotice").val();
                layer.close(index);
                layer.alert('确认在'+[time]+'时间修改福利更新公告？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=938",
                        data: {
                            gi:$("#g").val(),
                            si: '',
                            time:time,
                            fuc:'groupNotice',
                            groupNotice: groupNotice
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.alert('定时成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
        $(document).ready(calendarOne('hour', "#time"));
    }

    $('#content').on('click', 'tr', function() {
        var cb = $(this).find('td:first>input');
        if (! cb.is(':checked')) {
            cb.attr('checked', true);
            $(this).attr('style', 'background: #aba5618c');
        } else {
            cb.attr('checked', false);
            $(this).removeAttr('style', 'background: #aba5618c');
        }
        s_id='';
        $('#content input[type="checkbox"]:checked').each(function(index, el) {
            if (index == 0) {
                s_id = $(el).val();
            } else {
                s_id += ',' + $(el).val();
            }
        });
        $.cookie('cookie_gss', s_id, {expires: 7});
    });


<?php echo '</script'; ?>
>
<?php }
}
