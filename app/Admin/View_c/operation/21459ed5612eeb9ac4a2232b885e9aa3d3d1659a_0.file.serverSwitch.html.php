<?php
/* Smarty version 3.1.30, created on 2024-08-21 20:11:14
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\operation\serverSwitch.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66c5d9627687e0_88603350',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '21459ed5612eeb9ac4a2232b885e9aa3d3d1659a' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\operation\\serverSwitch.html',
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
function content_66c5d9627687e0_88603350 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<link href="<?php echo CSS;?>
jin/3.05.serverswitch.css" rel="stylesheet">
<div class="jin-content-title"><span>服务器开关</span></div>

<div class="jin-server-select">
    <?php if ($_smarty_tpl->tpl_vars['Mobel']->value == 'Mobel') {?>
    <div class="form-group" id="group_server_6_mobel"></div>
    <?php } else { ?>
    <div class="form-group" id="group_server_6"></div>
    <?php }?>
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    <button data-type="all_open" class="btn btn-success">批量开服</button>
    <button data-type="all_shut" class="btn btn-danger">批量关服</button>
    <button data-type="all_activityTime" class="btn btn-success">批量活动时间</button>
    <button data-type="all_allow_ip" class="btn btn-success">批量更新允许的ip列表</button>
    <button data-type="all_hefu" class="btn btn-primary">批量修改开服+合服时间(本地)</button>
    <label style="cursor: pointer;" class="hide"><input name="filter_type" type="radio" value="101" checked/>检测执行</label>
    <label style="cursor: pointer;" class="hide"><input name="filter_type" type="radio" value="102" />强制执行</label>
</div>
<div class="table-responsive">
    <div>
        <label for="server_name">筛选：</label>
        <input id="server_name" type="text" class="form-control jin-search-input" placeholder="服务器名字(模糊匹配)">
    </div>
    <br>
    <table class="table table-bordered table-hover text-center jin-server-table">
        <thead>
        <tr>
            <th class="jin-server-column1">
                <input id="all_choose" type="checkbox">
                <label for="all_choose">全选</label>
            </th>
            <th class="jin-server-column1">序号</th>
            <th>服务器名称</th>
            <th class="jin-server-column3">ID</th>
            <th>渠道名称</th>
            <th>游戏地址端口</th>
            <th>状态说明</th>
            <th>开服时间</th>
            <th>合服时间</th>
            <th class="jin-server-column5">开关</th>
            <th class="jin-server-column6">规则</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
    <div class="jin-explain  clearfix">
        <b>说明</b>：
        <div>
            ①点击渠道下拉框切换渠道。
        </div>
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
    var url = location.href + '&jinIf=912';
    var btn1 = [
        '<div class="btn-group btn-group-sm">' +
        '<button data-type="open" class=" btn btn-success">开服</button>' +
        '<button data-type="shut" class="btn btn-danger">关服</button>' +
        '<button data-type="setting" class="btn btn-set">设置活动时间</button>' +
        '</div>'
    ];
    var btn2 = [
        '<button data-type="bw" class="btn btn-sm btn-primary">黑白名单</button>'
    ];
    var id = "#content";
    var data = {};
    //选服下拉框
    $(document).ready(gsSelect3('#g'));  //页面加载完成后，调用groupSelect()函数，这个函数在jin-select.js中有封装
    function common() {
        var c = '';
        data.server_name = $("#server_name").val();
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (json) {
                layer.closeAll('loading');
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
                            '<td>' + json[i]['open_time'] + '</td>' +
                            '<td>' + json[i]['mergetime'] + '</td>' +
                            '<td>' + btn1 + '</td>' +
                            '<td>' + btn2 + '</td>' +
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
    }
    $("#jin_search").on('click', function () {    //根据渠道读取相应渠道的服务器信息
        data.group_id = $("#g").val();
        $.cookie('cookie_g', data.group_id, {expires: 30});
        common();
    });
    if ($.cookie('cookie_g')) {
        data.group_id = eval('[' + $.cookie('cookie_g') + ']');
        common();
    }

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
        console.log($('#select2-group-container').text());
        open(arr.server_id, arr.name);
    });

    function open(server_id, name) {
        layer.confirm('请选择一种<b>开服</b>方式', {
            icon: 0, btnAlign: 'c', btn: ['立即开服', '定时开服'] //按钮
        }, function () {
            layer.alert('确认立即开启服务器[' + name + ']？', {icon: 0, btn: ['确定', '取消']}, function () {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=938",
                    data: {
                        group_name: $('#select2-group-container').text(),
                        si: server_id,
                        server_name: name,
                        filter_type : $('input[name=filter_type]:checked').val()
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function () {
                        layer.closeAll('loading');
                        layer.alert('开服成功', {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    },
                    error: function () {
                        layer.closeAll('loading').msg('开服失败');
                    }
                });
            });
        }, function () {
            layer.open({
                type: 1,
                closeBtn: 2,
                title: '选择一个开服时间',
                area: ['220px', '150px'],
                btn: ['确定', '取消'],
                btnAlign: 'c',
                shadeClose: false, //点击遮罩关闭
                content: '<div class="jin-child center">' +
                '<input type="text" id="time" class="form-control jin-datetime-long">' +
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
                                si: server_id,
                                opentime: time
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
        });
    }

    // 点击批量关服
    $('button[data-type="all_shut"]').click(function() {
        var arr = getChoose();
        // console.log(arr);
        shut(arr.server_id, arr.name);
    });

    function shut(server_id, name) {
        layer.confirm('请选择一种<b>关服</b>方式', {
            icon: 0, btnAlign: 'c', btn: ['立即关服', '定时关服'] //按钮
        }, function () {
            layer.alert('确认立即关闭服务器[' + name + ']？', {icon: 0, btn: ['确定', '取消']}, function () {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=939",
                    data: {
                        group_name: $('#select2-group-container').text(),
                        si: server_id,
                        server_name: name,
                        filter_type : $('input[name=filter_type]:checked').val()
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function () {
                        layer.closeAll('loading');
                        layer.alert('关服成功', {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    },
                    error: function () {
                        layer.closeAll('loading').msg('关服失败');
                    }
                });
            });
        }, function () {
            layer.open({
                type: 1,
                closeBtn: 2,
                title: '选择一个关服时间',
                area: ['220px', '150px'],
                btn: ['确定', '取消'],
                btnAlign: 'c',
                shadeClose: false, //点击遮罩关闭
                content: '<div class="jin-child center">' +
                '<input type="text" id="time" class="form-control jin-datetime-long">' +
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
                            url: location.href + "&jinIf=939",
                            data: {
                                si: server_id,
                                closetime: time
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
        });
    }

    // 点击批量关服
    $('button[data-type="all_activityTime"]').click(function() {
        if (getChoose().server_id == undefined) {
            return false;
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '修改活动时间',
            area: ['400px', '400px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: false, //点击遮罩关闭
            content: '<div class="jin-child center">' +
            '<div class="input-group"><span class="input-group-addon">令牌老服新服时间切割</span><input type="text" id="time" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">宠物老服新服时间切割</span><input type="text" id="time_baby" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">悬赏任务重置</span><input type="text" id="QuestMoneyReset" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">湖中女神切割时间</span><input type="text" id="AccMoney5" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">充值双倍珍珠重置</span><input type="text" id="FirstChargeReset" class="form-control"></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=940",
                    data: {
                        si: getChoose().server_id,
                        PassportCharge: $("#time").val(),
                        BabyTalentSplit: $("#time_baby").val(),
                        QuestMoneyReset: $("#QuestMoneyReset").val(),
                        AccMoney5:$("#AccMoney5").val(),
                        FirstChargeReset:$("#FirstChargeReset").val()
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function () {
                        layer.closeAll('loading');
                        layer.close(index);
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    }
                });
            }
        });
        $(document).ready(calendarOne('hour', "#time"),calendarOne('hour', "#time_baby"),calendarOne('hour', "#QuestMoneyReset"),calendarOne('hour', "#AccMoney5"),calendarOne('hour', "#FirstChargeReset"));
    });



    // 点击批量关服
    $('button[data-type="all_hefu"]').click(function() {
        if (getChoose().server_id == undefined) {
            return false;
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '修改时间',
            area: ['400px', '300px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: false, //点击遮罩关闭
            content: '<div class="jin-child center">' +
            '<div class="input-group"><span class="input-group-addon">开服时间</span><input type="text" id="kaifu" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">合服时间</span><input type="text" id="hefu" class="form-control"></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=942",
                    data: {
                        si: getChoose().server_id,
                        kaifu: $("#kaifu").val(),
                        hefu: $("#hefu").val()
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function () {
                        layer.closeAll('loading');
                        layer.close(index);
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                            common();
                        });
                    }
                });
            }
        });
        $(document).ready(calendarOne('hour', "#kaifu"),calendarOne('hour', "#hefu"));
    });

    // 点击批量关服
    $('button[data-type="all_allow_ip"]').click(function() {
        if (getChoose().server_id == undefined) {
            return false;
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '修改活动时间',
            area: ['500px', '200px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: false, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">IP</span><input type="text" id="allow_ip" class="form-control"></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=941",
                    data: {
                        si: getChoose().server_id,
                        allow_ip: $("#allow_ip").val()
                    },
                    dataType: "json",
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        layer.close(index);
                        if(json.status==1){
                            layer.alert('成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }else{
                            layer.alert('部分服务器失败'+json.msg, {icon: 0}, function (index) {
                                layer.close(index);
                            });
                        }
                    }
                });
            }
        });
    });

    $('#content').on('click', 'button[data-type="open"]', function () {  //开服
        removeBackgroud($(this))
        var server_id = $(this).parents('tr').find('td').eq(3).text();
        var name = $(this).parents('tr').find('td').eq(2).text();
        open(server_id, name);
    }).on('click', 'button[data-type="shut"]', function () {   //关服
        removeBackgroud($(this))
        var server_id = $(this).parents('tr').find('td').eq(3).text();
        var name = $(this).parents('tr').find('td').eq(2).text();
        shut(server_id, name);
    }).on('click', 'button[data-type="bw"]', function () {
        removeBackgroud($(this))
        var server_id = $(this).parents('tr').find('td').eq(3).text();
        location.href += '&si=' + server_id + '&list=sbw';
    }).on('click', 'button[data-type="setting"]', function () {
        removeBackgroud($(this))
        var server_id = $(this).parents('tr').find('td').eq(3).text();
        location.href += '&si=' + server_id;
    }).on('click', 'tr', function() {
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
    // 禁止点击tr默认选中
    function removeBackgroud(obj) {
        var tr = $(obj).parents('tr');
        var cb = tr.find('td:first>input');
        if (! cb.is(':checked')) {
            cb.attr('checked', true);
            tr.attr('style', 'background: #aba5618c');
        } else {
            cb.attr('checked', false);
            tr.removeAttr('style', 'background: #aba5618c');
        }
    }
<?php echo '</script'; ?>
>
<?php }
}
