<?php
/* Smarty version 3.1.30, created on 2024-01-20 14:49:16
  from "/lnmp/www/app/Admin/View/player/playerCharacter.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65ab6cecdc94b1_86144494',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f48be823eb62856568f42dc007cba8fab408dcd4' => 
    array (
      0 => '/lnmp/www/app/Admin/View/player/playerCharacter.html',
      1 => 1705733345,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_65ab6cecdc94b1_86144494 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<style type="text/css">
    .isvalid{
        width: 180px;
        margin: auto;
        margin-top: 20px;
        font-weight: bold;
    }
    .table > tbody > tr > td{
        border-top: 0px solid #ddd;
    }
</style>
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>玩家角色信息</span></div>
<div class="alert alert-info">
    <div id="group_server"></div>
</div>
<!--查询区-->
<hr/>
<label for="time_start">创角日期：</label>
<input size="16" type="text" id="time_start" class="form-control jin-datetime"
       placeholder="开始日期">
-
<input size="16" type="text" id="time_end" class="form-control jin-datetime"
       placeholder="结束日期">
<label for="time_start">排序规则：</label>
<select id="sort_type" style="padding: 8px;">
    <option value="0">创建时间</option>
    <option value="1">等级</option>
    <option value="2">下线时间</option>
</select>
<input size="16" type="checkbox" id="ischeck1" value="1">
<label for="ischeck1" style="margin-left: 0px;">查所有服(不知道角色在哪个区服)</label>

<input size="16" type="checkbox" id="ischeck2" value="1">
<label for="ischeck2" style="margin-left: 0px;">处罚中</label>
<hr>
<input id="char" type="text" class="form-control jin-search-input" placeholder="账号名">
<input id="issuing_account" type="text" class="form-control jin-search-input hide" placeholder="发行ID">
<input id="char_id" type="text" class="form-control jin-search-input" placeholder="角色ID">
<input id="char_name" type="text" class="form-control jin-search-input" placeholder="角色名(模糊匹配)">
<input id="last_ip" type="text" class="form-control jin-search-input" placeholder="IP">
<input  id="player_name" maxlength="20" type="text" class="form-control jin-search-input" placeholder="角色名">
<input size="16" type="checkbox" id="ischeck" value="1">
<label for="gold" style="margin-left: 0px;">显示全部累充</label>

<!-- <input id="accurate" type="checkbox">精确匹配 -->
<a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
<a id="jin_excel" class="btn btn-danger">保存到Excel</a>
<hr/>
<!--数据区-->
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>帐号</th>
            <th>渠道</th>
            <th>发行ID</th>
            <th>角色ID</th>
            <th>角色累积充值</th>
            <th>角色名</th>
            <th>等级</th>
            <th>创建时间</th>
            <th>最近下线时间</th>
            <th>最后登录IP</th>
            <th>是否为有效角色</th>
            <th>是否处罚</th>
            <th>改名</th>
            <th>复制角色操作</th>
            <th>踢下线/解锁角色</th>
            <th>复制数据</th>
            <th>是否在线</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ① 查询框留空表示查询全部角色信息；
    </div>
    <div>
        ② 勾选精确匹配选项后只有输入完全匹配的帐号/角色ID/角色名才会查询到对应信息；
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 type="text/javascript">
    gsSelect('#group', '#server', '#platform');
    calendar('hour', '#time_start', '#time_end');
    var online_time = function (json) {
        var res = (json.online_time / 60).toFixed(2);
        return res;
    };
    var group = function (json) {
        return '<span data-data-id="'+json.server_id+'">'+json.group_name + '(' + json.paltform + ')('+json.server_id+')'+'</span>';
    };
    var devicetype = function (json) {
        if (json.devicetype == 8) {
            return 'ios(' + json.devicetype + ')';
        } else if (json.devicetype == 11) {
            return 'android(' + json.devicetype + ')';
        }
    };
    var isvalid = function (json) {
        if (json.isvalid == 1) {
            var c = '<div class="btn-group btn-group-sm">' +
                '<a data-type="update" class="btn btn-success">有效角色</a>' +
                '</div>';
        } else {
            var c = '<div class="btn-group btn-group-sm">' +
                '<a data-type="update" class="btn btn-danger">无效角色</a>' +
                '</div>';
        }

        return c;
    };
    var block_time = function (json) {
        if (json.block_time == 0) {
            var c = '<div class="btn-group btn-group-sm">' +
                '<a data-type="update_block_time" class="btn btn-success">未处罚</a>' +
                '</div>';
        } else {
            var c = '<div class="btn-group btn-group-sm">' +
                '<a title="处罚开始时间:'+json.block_begin+'" data-type="update_block_time" class="btn btn-danger">处罚中</a>' +
                '</div>';
        }

        return c;
    };
    var is_rename = function (json) {
        if (json.is_rename == 0) {
            var c = '<div class="btn-group btn-group-sm">' +
                '<a data-type="update_is_rename" class="btn btn-danger">禁止改名</a>' +
                '</div>';
        } else {
            var c = '<div class="btn-group btn-group-sm">' +
                '<a data-type="update_is_rename" class="btn btn-success">允许改名</a>' +
                '</div>';
        }

        return c;
    };
    var copy = function (json) {
        var c = '<div class="btn-group btn-group-sm">' +
            '<a data-type="copy" char_id="'+json.char_id+'" class="btn btn-success">复制</a>' +
            '</div>';

        return c;
    };
    var copydata = function (json) {
        var c = '<div class="btn-group btn-group-sm">' +
            '<a data-type="copydata" char_id="'+json.char_id+'" class="btn btn-success" style="margin-bottom: 2px;">复制数据</a><br>' +
            '<a data-type="copydata1" char_id="'+json.char_id+'" class="btn btn-success" style="margin-bottom: 2px;">复制扩展数据</a><br>' +
            // '<a data-type="copydata2" char_id="'+json.char_id+'" class="btn btn-success">导入内网</a>' +
            '<a data-type="copydata3" char_id="'+json.char_id+'" class="btn btn-success">转移账号</a>' +
            '</div>';

        return c;
    };
    var kick = function () {
        var c = '<div class="btn-group btn-group-sm">' +
            '<a data-type="kick"  class="btn btn-danger" style="margin-bottom: 2px;">踢出下线</a><br>' +
            // '<a data-type="deblock"  class="btn btn-success" style="margin-bottom: 2px;">解锁角色</a><br>' +
            '<a data-type="select_cheating"  class="btn btn-success" style="margin-bottom: 2px;">查询外挂</a><br>' +
            // '<a data-type="select_cheating1"  class="btn btn-success">查询外挂(IOS)</a><br>' +
            '</div>';
        return c;
    };
    var deblock = function () {
        var c = '<div class="btn-group btn-group-sm">' +
            '<a data-type="deblock"  class="btn btn-success">解锁角色</a>' +
            '</div>';

        return c;
    };
    var is_online = function () {
        var c = '<div class="btn-group btn-group-sm">' +
            '<a data-type="is_online"  class="btn btn-success" style="margin-bottom: 2px;">???</a><br>' +
            '<a data-type="set_saiji"  class="btn btn-primary" style="display: none">设置赛季</a>' +
            '</div>';
        return c;
    };
    var change_acc = function () {
        var c = '<div class="btn-group btn-group-sm">' +
            '<a data-type="change_acc"  class="btn btn-success" style="margin-bottom: 2px;">更改账号</a><br>' +
            '<a data-type="get_pw"  class="btn btn-primary" style="margin-bottom: 2px;">拷贝密码</a><br>' +
            '<a data-type="delete_power"  class="btn btn-success" style="margin-bottom: 2px;display: none">周目回档</a><br>' +
            '<a data-type="delete_fashion"  class="btn btn-primary" style="margin-bottom: 2px;">移除时装</a><br>' +
            '<a data-type="set_baby"  class="btn btn-primary" style="margin-bottom: 2px;">宠物设置</a>' +
            '<a data-type="set_power"  class="btn btn-success" style="margin-bottom: 2px;">设置排行榜</a><br>' +
            '<a data-type="sub_money"  class="btn btn-success" style="margin-bottom: 2px;">扣除货币</a><br>' +
            '</div>';
        return c;
    };


    var url = location.href + "&jinIf=912";
    var url2 = location.href + "&jinIf=912";
    var arr = ['acc_name', group, 'issuing_account', 'char_id', 'fee', 'char_name', 'level', 'create_time', 'logout_time','lastIP' , isvalid,block_time,is_rename, copy,kick, copydata, is_online, change_acc];
    var id = ["#content", "#page"];
    var data = {page: 1};
    $("#jin_search").on('click', function () {
        data.page = 1;
        data.sort_type = $('#sort_type').val();
        data.time_start = $('#time_start').val();//查询开始时间;
        data.time_end   = $('#time_end').val();//查询结束时间
        data.player_name= $("#player_name").val();
        data.char       = $("#char").val();
        data.issuing_account = $("#issuing_account").val();
        data.char_id    = $("#char_id").val();
        data.char_name  = $("#char_name").val();
        data.last_ip    = $("#last_ip").val();
        data.ischeck       = $('#ischeck').is(':checked') ? $('#ischeck').val() : '';
        data.ischeck1       = $('#ischeck1').is(':checked') ? $('#ischeck1').val() : '';
        data.ischeck2       = $('#ischeck2').is(':checked') ? $('#ischeck2').val() : '';
        data.si  = $("#server").val();
        data.pi  = $("#platform").val();
        data.gi  = $("#group").val();
        tableList(url, data, id, arr)
    });

    // 导出Excel
    $("#jin_excel").on('click', function () {
        data.page = 'excel';
        $.ajax({
            type: "post",
            url: location.href + '&jinIf=951',
            data: data,
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']
                });
            },
            success: function (output) {
                layer.closeAll('loading');
                location.href = output;
            },
            error: function () {
                layer.closeAll('loading');
                layer.msg('文件下载失败，请缩小筛选条件后再次下载');
            }
        });
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

    $('#content').on('click', 'a[data-type="update"]', function () {
        removeBackgroud($(this))
        var isvalid = $(this).parents('tr').find('td').eq(10).text();
        var char_id = $(this).parents('tr').find('td').eq(3).text();

        if (isvalid == '无效角色') {
            isvalid = '1';
        } else {
            isvalid = '0';
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '角色是否有效修改',
            area: ['400px', '170px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="isvalid">确定修改此角色的状态吗？</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=913',
                    data: {
                        isvalid: isvalid,
                        char_id: char_id
                    },
                    success: function (json) {
                        if (json == 1) {
                            layer.close(index);
                            layer.alert('修改成功', {icon: 1}, function (index) {
                                layer.close(index);
                                tableList(url2, data, id, arr)
                            });
                        } else {
                            layer.alert('修改失败');
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    }).on('click', 'a[data-type="update_block_time"]', function () {
        removeBackgroud($(this))
        var block_time = $(this).parents('tr').find('td').eq(11).text();
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '角色处罚是否修改',
            area: ['400px', '170px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="isvalid">确定修改此角色的处罚状态吗？<span style="color: red;">一年时间无产出</span></div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=9131',
                    data: {
                        si:$("#server").val(),
                        block_time: block_time,
                        char_id: char_id
                    },
                    dataType: "json",
                    success: function (json) {
                        if (json['result'] == 1) {
                            layer.close(index);
                            layer.alert('修改成功', {icon: 1}, function (index) {
                                layer.close(index);
                                tableList(url2, data, id, arr)
                            });
                        } else {
                            layer.alert('修改失败');
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    }).on('click', 'a[data-type="update_is_rename"]', function () {
        removeBackgroud($(this))
        var is_rename = $(this).parents('tr').find('td').eq(12).text();
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '是否允许改名',
            area: ['400px', '170px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="isvalid">确定修改此角色的改名状态吗？</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=9132',
                    data: {
                        si:$("#server").val(),
                        is_rename: is_rename,
                        char_id: char_id
                    },
                    dataType: "json",
                    success: function (json) {
                        if (json == 1) {
                            layer.close(index);
                            layer.alert('修改成功', {icon: 1}, function (index) {
                                layer.close(index);
                                tableList(url2, data, id, arr)
                            });
                        } else {
                            layer.alert('修改失败');
                        }
                    }
                });
            }
        });
    }).on('click', 'a[data-type="copy"]', function () {
        removeBackgroud($(this))
        var char_id = $(this).attr('char_id');
        var sid = $("#server").val();
        location.href += '&type=playerCharacterCopy' + '&char_id=' + char_id + '&sid=' + sid;
    }).on('click', 'a[data-type="copydata"]', function () {
        removeBackgroud($(this))
        var char_id = $(this).attr('char_id');
        var sid = $("#server").val();
        location.href = "?p=I&c=Player&a=getRoleData&si="+sid+"&char_id="+char_id;
    }).on('click', 'a[data-type="copydata1"]', function () {
        removeBackgroud($(this))
        var char_id = $(this).attr('char_id');
        var sid = $("#server").val();
        location.href = "?p=I&c=Player&a=getRoleData1&si="+sid+"&char_id="+char_id;
    }).on('click', 'a[data-type="copydata2"]', function () {
        removeBackgroud($(this));
        var char_id = $(this).attr('char_id');
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '角色导入内网',
            area: ['200px', '170px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="isvalid">确定操作吗？</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=924',
                    data: {
                        char_id:char_id,
                        si:$("#server").val()
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']
                        });
                    },
                    dataType: "json",
                    success: function (json) {
                        layer.closeAll('loading');
                    }
                });
            }
        });
    }).on('click', 'a[data-type="copydata3"]', function () {
        removeBackgroud($(this));
        var char_id = $(this).attr('char_id');
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '转移账号',
            area: ['400px', '400px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
                '<div class="input-group"><span class="input-group-addon">渠道</span><select class="input-group-addon" style="width: 268px;"  id="g_other"></select></div>' +
                '<div class="input-group"><span class="input-group-addon">服务器</span><select class="input-group-addon" style="width: 268px;" id="s_other"></select></div>' +
                '<div class="input-group"><span class="input-group-addon">账号ID</span><input id="acc_id" type="text" class="form-control" ></div>' +
                '<div class="input-group"><span class="input-group-addon">角色ID</span><input id="char_guid" type="text" class="form-control"></div>' +
                '<div class="input-group"><span class="input-group-addon">是否覆盖</span>' +
                '<select class="input-group-addon" style="width: 268px;" id="is_cover">' +
                '<option value=0>否</option>' +
                '<option value=1>是</option>' +
                '</select></div>' +
                '</div>',
            success: function (index) {
                $.ajax({
                    type: "post",
                    url: location.href + "&jinIf=9211",
                    dataType: "json",
                    success: function (res) {
                        var c='';
                        for (var i = 0; i < res.length; i++) {
                            c+='<option value="'+res[i]['group_id']+'">'+res[i]['group_name']+'</option>';
                        }
                        $("#g_other").html(c)
                    }
                });
                $("#g_other").on('change', function () { //渠道改变的时候
                    $.ajax({
                        type: "post",
                        url: location.href + "&jinIf=9212",
                        dataType: "json",
                        data: {
                            gi:$("#g_other").val()
                        },
                        success: function (res) {
                            var c='';
                            for (var i = 0; i < res.length; i++) {
                                c+='<option value="'+res[i]['server_id']+'">'+res[i]['name']+'</option>';
                            }
                            $("#s_other").html(c)
                        }
                    });
                });
            },
            yes: function (index1) {
                if ($('#s_other').val() == null || $('#acc_id').val() == '' || $('#char_guid').val() == '' ) {
                    layer.msg('缺少必要信息!');
                    return false;
                }
                $.ajax({
                    type: "post",
                    url: location.href + "&jinIf=9213",
                    dataType: "json",
                    data: {
                        si:$("#server").val(),
                        char_id:char_id,
                        gi:$("#g_other").val(),
                        s_other:$("#s_other").val(),
                        acc_id:$("#acc_id").val(),
                        char_guid:$("#char_guid").val(),
                        is_cover:$("#is_cover").val()
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (res) {
                        layer.closeAll('loading');
                        if(res){
                            layer.close(index1);
                            layer.alert('转移成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }else{
                            layer.alert('转移失败！或者选择覆盖转移');
                        }
                    }
                });
            }
        });
    }).on('click', 'a[data-type="kick"]', function () {
        removeBackgroud($(this))
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '角色踢下线',
            area: ['200px', '170px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="isvalid">确定该角色踢下线吗？</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=916',
                    data: {
                        char_id:char_id,
                        opttype:0,
                        si:$("#server").val()
                    },
                    dataType: "json",
                    success: function (json) {
                        if (json.result==1) {
                            layer.close(index);
                            layer.alert('修改成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        } else {
                            if(json.status==2){
                                layer.alert('无权限操作！ 请联系管理员');
                            }else{
                                layer.alert('修改失败！');
                            }
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    }).on('click', 'a[data-type="deblock"]', function () {
        removeBackgroud($(this))
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '解除角色',
            area: ['200px', '170px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="isvalid">确定解除该角色吗？</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=916',
                    data: {
                        char_id: char_id,
                        opttype:1,
                        si : $("#server").val()
                    },
                    dataType: "json",
                    success: function (json) {
                        if (json.result==1) {
                            layer.close(index);
                            layer.alert('修改成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        } else {
                            if(json.status==2){
                                layer.alert('无权限操作！ 请联系管理员');
                            }else{
                                layer.alert('修改失败！');
                            }
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    }).on('click', 'a[data-type="is_online"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        $this = $(this);
        $.ajax({
            type: "POST",
            url: location.href + '&jinIf=917',
            data: {
                char_id: char_id,
                si : $("#server").val()
            },
            dataType: "json",
            success: function (json) {
                if (json.result==1) {
                    if(json.error==1){
                        $this.html('在线');
                        $this.addClass("btn-success");
                        $this.removeClass("btn-danger");
                    }else{
                        $this.html('不在线');
                        $this.addClass("btn-danger");
                        $this.removeClass("btn-success");
                    }
                }
            }
        });
    }).on('click', 'a[data-type="delete_tx"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        $this = $(this);
        $.ajax({
            type: "POST",
            url: location.href + '&jinIf=927',
            data: {
                char_id: char_id,
                si : $("#server").val()
            },
            dataType: "json",
            success: function (json) {
                if (json.result==1) {
                    layer.alert('成功', {icon: 1}, function (index) {
                        layer.close(index);
                    });
                }else{
                    layer.alert('修改失败');
                }
            }
        });
    }).on('click', 'a[data-type="change_acc"]', function () {
        var oldAccount = $(this).parents('tr').find('td').eq(0).text();
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '账号ID修改',
            area: ['350px', '250px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
                '<div class="input-group"><span class="input-group-addon">旧账号ID</span><input id="oldAccount" disabled type="text" class="form-control" value="'+oldAccount+'"></div>' +
                '<div class="input-group"><span class="input-group-addon">新账号ID</span><input id="newAccount" type="text" class="form-control"></div>' +
                '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=918',
                    data: {
                        si: $("#server").val(),
                        oldAccount: oldAccount,
                        newAccount: $("#newAccount").val(),
                        char_id: char_id
                    },
                    success: function (json) {
                        layer.close(index);
                        if (json == 1) {
                            layer.alert('修改成功', {icon: 1}, function (index) {
                                layer.close(index);
                                tableList(url, data, id, arr)
                            });
                        }else if(json == 2){
                            layer.alert('无权限操作！ 请联系管理员');
                        } else{
                            layer.alert('修改失败');
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    }).on('click', 'a[data-type="set_saiji"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '设置赛季成就点',
            area: ['250px', '250px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
                '<div class="input-group"><span class="input-group-addon">类型</span><select id="score_type"><option value="0">赛季A</option><option value="1">赛季B</option></select></div>' +
                '<div class="input-group"><span class="input-group-addon">成就点数</span><input id="score_num" type="text" class="form-control"></div>' +
                '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=928',
                    data: {
                        si: $("#server").val(),
                        score_type: $("#score_type").val(),
                        score_num: $("#score_num").val(),
                        char_id: char_id
                    },
                    success: function (json) {
                        layer.close(index);
                        if (json == 1) {
                            layer.alert('修改成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        } else{
                            layer.alert('修改失败');
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    }).on('click', 'a[data-type="send_soap"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '应用',
            area: ['400px', '300px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
                '<div class="input-group"><span class="input-group-addon">arg0</span><input id="arg0" type="text" class="form-control"></div>' +
                '<div class="input-group"><span class="input-group-addon">arg1</span><input id="arg1" type="text" class="form-control"></div>' +
                '<div class="input-group"><span class="input-group-addon">arg2</span><input id="arg2" type="text" class="form-control"></div>' +
                '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=919',
                    data: {
                        si: $("#server").val(),
                        char_id: char_id,
                        arg0: $("#arg0").val(),
                        arg1: $("#arg1").val(),
                        arg2: $("#arg2").val()
                    },
                    success: function (json) {
                        layer.close(index);
                        if (json == 1) {
                            layer.alert('修改成功', {icon: 1}, function (index) {
                                layer.close(index);
                                tableList(url, data, id, arr)
                            });
                        }else if(json == 2){
                            layer.alert('无权限操作！ 请联系管理员');
                        } else{
                            layer.alert('修改失败');
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    }).on('click', 'a[data-type="get_pw"]', function () {
        var account = $(this).parents('tr').find('td').eq(0).text();
        $.ajax({
            type: "POST",
            url: location.href + '&jinIf=920',
            data: {
                account: account
            },
            success: function (json) {
                layer.alert(json, {icon: 1}, function (index) {
                    layer.close(index);
                });
            }
        });
    }).on('click', 'a[data-type="delete_power"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '周目回档',
            area: ['300px', '300px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
                '<div class="input-group"><span class="input-group-addon">类型</span><select id="station_id"><option value="0">冒险</option><option value="1">英雄</option><option value="4">赛季A</option><option value="5">赛季B</option><option value="8">保卫部落A</option><option value="9">保卫部落B</option></select></div>' +
                '<div class="input-group"><span class="input-group-addon">周目</span><input id="week_id" type="number" class="form-control" placeholder="周目一填写1,以此类推"></div>' +
                '<div class="input-group"><span class="input-group-addon">章节</span><input id="stage_id" type="number" class="form-control"></div>' +
                '</div>',
            yes: function (index) {
                if($("#week_id").val()>5&&$("#station_id").val()==0){
                    layer.alert("请输入正确周目", {icon: 0});
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=921',
                    data: {
                        si: $("#server").val(),
                        station_id:$("#station_id").val(),
                        week_id:$("#week_id").val()-1,
                        stage_id:$("#stage_id").val(),
                        char_id: char_id
                    },
                    success: function (json) {
                        layer.close(index);
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    }
                });
            },
            cancel: function () {
            }
        });
    }).on('click', 'a[data-type="sub_money"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '扣除货币',
            area: ['300px', '300px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
                '<div class="input-group"><span class="input-group-addon">货币类型</span><select id="currenty">' +
                '<option value="1">体力</option>' +
                '<option value="2">钻石</option>' +
                '<option value="3">金币</option>' +
                '<option value="4">小宝箱钥匙</option>' +
                '<option value="5">大宝箱钥匙</option>' +
                '<option value="6">石材</option>' +
                '<option value="7">木材</option>' +
                '<option value="8">锻造石</option>' +
                '<option value="9">天赋石</option>' +
                '<option value="10">兽粮</option>' +
                '<option value="11">星币</option>' +
                '<option value="12">命运钥匙</option>' +
                '<option value="13">星币(掉落用)</option>' +
                '<option value="14">经验</option>' +
                '<option value="15">宠物抽奖钥匙</option>' +
                // '<option value="1">体力</option>' +
                // '<option value="2">珍珠</option>' +
                // '<option value="3">石币</option>' +
                // '<option value="4">祭司宝箱钥匙</option>' +
                // '<option value="5">神灵宝箱钥匙</option>' +
                // '<option value="6">石头</option>' +
                // '<option value="7">木头</option>' +
                // '<option value="8">锻造石</option>' +
                // '<option value="9">天赋石</option>' +
                // '<option value="10">兽粮</option>' +
                // '<option value="11">祭品</option>' +
                // '<option value="12">命运钥匙</option>' +
                // '<option value="16">装备精华</option>' +
                // '<option value="18">令牌经验</option>' +
                // '<option value="20">种子</option>' +
                // '<option value="21">树叶</option>' +
                // '<option value="22">鲜花</option>' +
                // '<option value="23">节日积分</option>' +
                // '<option value="24">小节日任务积分</option>' +
                // '<option value="25">能量块</option>' +
                // '<option value="26">宠物钥匙</option>' +
                // '<option value="27">先锋试炼积分</option>' +
                // '<option value="28">赛季勇气勋章</option>' +
                // '<option value="29">赛季酋长勋章</option>' +
                // '<option value="31">开服宝箱积分</option>' +
                // '<option value="32">收集的珍珠</option>' +
                // '<option value="33">钓鱼积分</option>' +
                // '<option value="34">钓鱼古币</option>' +
                // '<option value="35">宠物抽奖积分</option>' +
                // '<option value="36">赛季前夕积分点</option>' +
                // '<option value="37">节日活动2货币1</option>' +
                // '<option value="38">节日活动2货币2</option>' +
                // '<option value="39">节日活动2货币3</option>' +
                // '<option value="40">赛季前夕战绩点</option>' +
                // '<option value="41">兽魂</option>' +
                // '<option value="42">保卫部落战功</option>' +
                // '<option value="43">保卫部落精魂</option>' +
                // '<option value="44">保卫部落名望</option>' +
                // '<option value="45">保卫部落积分</option>' +
                '</select></div>' +
                '<div class="input-group"><span class="input-group-addon">数量</span><input id="money" type="number" class="form-control"></div>' +
                '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=926',
                    data: {
                        si: $("#server").val(),
                        currenty:$("#currenty").val(),
                        money:$("#money").val(),
                        char_id: char_id
                    },
                    success: function (json) {
                        layer.close(index);
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    }
                });
            },
            cancel: function () {
            }
        });
    }).on('click', 'a[data-type="set_power"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '设置排行榜',
            area: ['300px', '350px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
                '<div class="input-group"><span class="input-group-addon">类型</span>' +
                '<select class="input-group-addon" style="width: 100px;" id="station_id">' +
                '<option value="0">冒险</option>' +
                '<option value="1">训练</option>' +
                '<option value="2">挑战</option>' +
                '<option value="5">宠物乐园</option>' +
                '<option value="6">时空裂隙</option>' +
                '</select>' +
                '</div>' +
                '<div class="input-group"><span class="input-group-addon">关卡</span><input id="sub_sort_data1"' +
                ' type="number" class="form-control"></div>' +
                // '<div class="input-group sort_data1"><span class="input-group-addon">周目</span>' +
                // '<select id="sort_data1" class="input-group-addon" style="width: 150px;" >' +
                // '<option value="0">1</option>' +
                // '<option value="1">2</option>' +
                // '<option value="2">3</option>' +
                // '<option value="3">4</option>' +
                // '<option value="4">5</option>' +
                // '</select>' +
                // '</div>' +
                // '<div class="input-group sort_data2"><span class="input-group-addon">周目</span>' +
                // '<select id="sort_data2" class="input-group-addon" style="width: 150px;" >' +
                // '<option value="0">精英</option>' +
                // '<option value="1">史诗</option>' +
                // '<option value="2">噩梦</option>' +
                // '<option value="3">痛苦</option>' +
                // '<option value="4">传说</option>' +
                // '<option value="5">灾难</option>' +
                // '<option value="6">折磨</option>' +
                // '<option value="7">恐惧</option>' +
                // '<option value="8">炼狱</option>' +
                // '<option value="9">毁灭</option>' +
                // '</select>' +
                // '</div>' +
                // '<div class="input-group sub_sort_data1"><span class="input-group-addon">关卡</span>' +
                // '<select id="sub_sort_data1" class="input-group-addon" style="width: 150px;" >' +
                // '<option value="1">1</option>' +
                // '<option value="2">2</option>' +
                // '<option value="3">3</option>' +
                // '<option value="4">4</option>' +
                // '<option value="5">5</option>' +
                // '<option value="6">6</option>' +
                // '<option value="7">7</option>' +
                // '<option value="8">8</option>' +
                // '<option value="9">9</option>' +
                // '<option value="10">10</option>' +
                // '<option value="11">11</option>' +
                // '<option value="12">12</option>' +
                // '<option value="13">13</option>' +
                // '<option value="14">14</option>' +
                // '</select>' +
                // '</div>' +
                // '<div class="input-group sub_sort_data2"><span class="input-group-addon">章节</span>' +
                // '<select id="sub_sort_data2" class="input-group-addon" style="width: 150px;" >' +
                // '<option value="1">1</option>' +
                // '<option value="2">2</option>' +
                // '<option value="3">3</option>' +
                // '<option value="4">4</option>' +
                // '</select>' +
                // '</div>' +
                '<div class="input-group"><span class="input-group-addon">时间(单位秒)</span><input id="extend_data"' +
                ' type="number" class="form-control"></div>' +
                '</div>',
            success:function () {
                $(".sub_sort_data2").hide();
                $(".sort_data2").hide();
                $("#station_id").on('change',function () {
                    if($(this).val()==2){
                        $(".sub_sort_data1").hide();
                        $(".sub_sort_data2").show();
                        $(".sort_data1").hide();
                        $(".sort_data2").show();
                    }else{
                        $(".sub_sort_data1").show();
                        $(".sub_sort_data2").hide();
                        $(".sort_data1").show();
                        $(".sort_data2").hide();
                    }
                });
            },
            yes: function (index) {
                if($("#station_id").val()==1){
                    sort_data  = $("#sort_data1").val();
                    sub_sort_data = $("#sub_sort_data1").val()
                }else{
                    sort_data  = $("#sort_data2").val();
                    sub_sort_data = $("#sub_sort_data2").val()
                }
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=925',
                    data: {
                        si: $("#server").val(),
                        station_id:$("#station_id").val(),
                        sort_data:sort_data,
                        sub_sort_data:sub_sort_data,
                        extend_data:$("#extend_data").val(),
                        char_id: char_id
                    },
                    success: function (json) {
                        layer.close(index);
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    }
                });
            },
            cancel: function () {
            }
        });
    }).on('click', 'a[data-type="delete_fashion"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '移除时装',
            area: ['300px', '200px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
                '<div class="input-group"><span class="input-group-addon">时装ID</span>' +
                '<select id="fashion_id" class="input-group-addon" style="width: 150px;" >' +
                '<option value="1">指挥官</option>' +
                '<option value="2">未来战警</option>' +
                '<option value="3">街头女王</option>' +
                '<option value="4">暴风机甲</option>' +
                // '<option value="1">原始人</option>' +
                // '<option value="2">海盗</option>' +
                // '<option value="3">悟空</option>' +
                // '<option value="4">忍者</option>' +
                // '<option value="5">波斯王子</option>' +
                // '<option value="6">暗夜女王</option>' +
                // '<option value="7">天国使者</option>' +
                // '<option value="8">圣诞娃娃</option>' +
                // '<option value="9">机械勇士</option>' +
                // '<option value="10">暴走萝莉</option>' +
                // '<option value="11">新春高级</option>' +
                // '<option value="12">新春低级</option>' +
                // '<option value="13">春芽</option>' +
                // '<option value="14">雷神之影</option>' +
                // '<option value="15">闪电人</option>' +
                // '<option value="16">沙漠艳后</option>' +
                // '<option value="17">法老守护</option>' +
                // '<option value="20">血族王子</option>' +
                '</select>' +
                '</div>' +
                '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=922',
                    data: {
                        si: $("#server").val(),
                        fashion_id:$("#fashion_id").val(),
                        char_id: char_id
                    },
                    success: function (json) {
                        layer.close(index);
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    }
                });
            }
        });
    }).on('click', 'a[data-type="set_baby"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '宠物设置',
            area: ['300px', '400px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
                '<div class="input-group"><span class="input-group-addon">baby_id</span><input id="baby_id" type="number" class="form-control"></div>' +
                '<div class="input-group"><span class="input-group-addon">opt_type</span><input id="opt_type" type="number" class="form-control"></div>' +
                '<div class="input-group"><span class="input-group-addon">param0</span><input id="param0" type="number" class="form-control"></div>' +
                '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=923',
                    data: {
                        si: $("#server").val(),
                        baby_id:$("#baby_id").val(),
                        opt_type:$("#opt_type").val(),
                        param0:$("#param0").val(),
                        char_id: char_id
                    },
                    success: function (json) {
                        layer.close(index);
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    }
                });
            }
        });
    }).on('click', 'a[data-type="select_cheating"]', function () {
        var char_guid = $(this).parents('tr').find('td').eq(3).text();
        var acc = $(this).parents('tr').find('td').eq(0).text();
        $.ajax({
            type: "post",
            url: location.href + "&jinIf=9191",
            data: {
                char:char_guid,
                acc:acc
            },
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (json) {
                layer.closeAll('loading');
                var c='<table class="table table-bordered table-hover text-center jin-server-table">' +
                    '<tr><th>设备名</th><th>设备类型</th><th>设备号</th><th>ip</th><th>包名</th><th>时间</th><th>检测结果</th><th>风险环境</th><th>风险等级</th><th>防御结果</th>';
                for(var i=0;i<json.length;i++){
                    c+='<tr>' +
                        '<td>'+json[i]['device_name']+'</td>' +
                        '<td>'+json[i]['device_type']+'</td>' +
                        '<td>'+json[i]['code']+'</td>' +
                        '<td>'+json[i]['ip']+'</td>' +
                        '<td>'+json[i]['pack']+'</td>' +
                        '<td>'+json[i]['time']+'</td>' +
                        '<td>'+json[i]['check_result']+'</td>' +
                        '<td>'+json[i]['risk']+'</td>' +
                        '<td>'+json[i]['risk_level']+'</td>' +
                        '<td>'+json[i]['defense_result']+'</td>' +
                        '</tr>';
                }
                c+='</table>';
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '',
                    area: ['1200px', '600px'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content:'<div class="jin-child">' +
                        c+
                        '</div>'
                });
            }
        });
    }).on('click', 'a[data-type="select_cheating1"]', function () {
        var char_guid = $(this).parents('tr').find('td').eq(3).text();
        var acc = $(this).parents('tr').find('td').eq(0).text();
        $.ajax({
            type: "post",
            url: location.href + "&jinIf=9192",
            data: {
                char:char_guid,
                acc:acc
            },
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (json) {
                layer.closeAll('loading');
                var c='<table class="table table-bordered table-hover text-center jin-server-table">' +
                    '<tr><th>设备id</th><th>ios版本</th><th>角色ID</th><th>账号</th><th>角色名</th><th>服务器</th><th>包名</th><th>ip</th><th>外挂风险</th><th>环境风险</th><th>其他风险</th><th>风险处理</th><th>时间</th>';
                for(var i=0;i<json.length;i++){
                    c+='<tr>' +
                        '<td>'+json[i]['code']+'</td>' +
                        '<td>'+json[i]['ios_v']+'</td>' +
                        '<td>'+json[i]['char_id']+'</td>' +
                        '<td>'+json[i]['acc']+'</td>' +
                        '<td>'+json[i]['char_name']+'</td>' +
                        '<td>'+json[i]['si']+'</td>' +
                        '<td>'+json[i]['pack']+'</td>' +
                        '<td>'+json[i]['ip']+'</td>' +
                        '<td>'+json[i]['plug_risk']+'</td>' +
                        '<td>'+json[i]['env_risk']+'</td>' +
                        '<td>'+json[i]['other_risk']+'</td>' +
                        '<td>'+json[i]['risk_result']+'</td>' +
                        '<td>'+json[i]['time']+'</td>' +
                        '</tr>';
                }
                c+='</table>';
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '',
                    area: ['1200px', '600px'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content:'<div class="jin-child">' +
                        c+
                        '</div>'
                });
            }
        });
    });
<?php echo '</script'; ?>
>
<?php }
}
