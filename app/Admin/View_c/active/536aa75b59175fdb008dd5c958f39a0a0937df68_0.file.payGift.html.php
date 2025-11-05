<?php
/* Smarty version 3.1.30, created on 2024-09-19 13:49:35
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\active\payGift.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66ebbb6f712a70_10295048',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '536aa75b59175fdb008dd5c958f39a0a0937df68' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\active\\payGift.html',
      1 => 1726724952,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_66ebbb6f712a70_10295048 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="jin-content-title"><span>付费礼包</span></div>
<div class="alert alert-info">
     <div id="group_only" style="display: inline-block;"></div>
    <label for="sign" style="font-size: 18px;color: #eee;margin-bottom: 0;">标识：</label>
    <select  id="sign"></select>
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    <button id="jin_add" class="btn btn-info">新增标识</button>
    <button id="jin_delete" class="btn btn-danger">删除标识</button>
    <button id="jin_update" class="btn btn-info">修改标识名字</button>
    <button id="jin_copy" class="btn btn-primary">整体复制</button>
    <button id="jin_copy_one" class="btn btn-primary">单条复制</button>
    <button id="jin_sync1" class="btn btn-info">同步国内</button>
    <form id="uploadForm" enctype="multipart/form-data" style="display: inline-block; width: 180px;">
        <input id="file" accept=".xls,.xlsx" type="file" name="file"/>
    </form>
    <button id="upload" class='btn btn-success'>导入数据</button>
    <button id="jin_excel" class="btn btn-danger">excel导出</button>
</div>
<button id='send_all0' class='btn btn-success'>批量应用</button>
<button id='send_all0_time' class='btn btn-success'>定时批量应用</button>
<button id='delete_all0' class='btn btn-danger'>批量撤回</button>
<button id='close_all' class='btn btn-danger'>批量关闭</button>
<button id='delete_all0_time' class='btn btn-danger'>定时批量撤回</button>
<button id="jin_jindu" class="btn btn-primary">查看应用进度</button>
<button id="all_update" class="btn btn-info">批量修改</button>
<button id="jin_copy_one1" class="btn btn-primary">同步其他渠道</button>
<hr>
<label class="select_label control-label">应用渠道:</label>
<div class="select_group_div">
    <select id="g" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>
</div>
<label class="select_label control-label">应用服务器:</label>
<div class="select_server_div">
    <select id="s" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr id="tb_th">
            <th><input id="all_choose" type="checkbox"><label for="all_choose">全选</label></th>
            <th>礼包名</th>
            <th>礼包描述</th>
            <th>付费类型</th>
            <th>Icon</th>
            <th>是否开放</th>
            <th>开启时间</th>
            <th>结束时间</th>
            <th>初始价格</th>
            <th>消耗货币</th>
            <th>消耗人民币</th>
            <th>限购次数</th>
            <th>重置类型</th>
            <th>礼包类型</th>
            <th>UpdateTime</th>
            <th>ProductID</th>
            <th>超值</th>
            <th>SKU</th>
            <th>奖励</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>

<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
    groupSelect({dom: "#group"});
    gsSelect3('#g','','#s');
    var url = location.href + "&jinIf=912";
    var btn = [
        '<a data-type="update" class="btn btn-primary">修改</a>'
    ];

    var id = "#content";
    var data = {};
    var id_check = function (json) {
        return '<input type="checkbox" value="' + json[json['IDS']] + '" />'+json[json['IDS']]
    };
    var Reward = function (json) {
        return '<div style="width: 200px;">' + json['Reward'] + '</div>';
    };
    var SKU = function (json) {
        return '<span>安卓:'+json['SKUAndroid']+'</span><br><span>IOS:'+json['SKUIOS']+'</span>'
    };
    var arr = [id_check, 'Name', 'Tip', 'PayType', 'Icon', 'IsOpen', 'OpenTime', 'EndTime', 'InitPrice', 'Cost', 'Price', 'LimitCount', 'ResetType', 'Type', 'UpdateTime', 'ProductID', 'SuperValue', SKU, Reward, btn];
    // 普通查询
    $("#jin_search").on('click', function () {
        common();
    });
    $("#jin_delete").on('click', function () {
        layer.alert('确认删除该标识吗？请谨慎使用', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=914",
                data: {
                    gi: $("#group").val(),
                    sign: $("#sign").val()
                },
                dataType: "json",
                success: function (json) {
                    layer.alert('成功', {icon: 1}, function (index) {
                        layer.close(index);
                        window.location.reload();
                    });
                }
            });
        });
    });
    $("#jin_add").on('click', function () {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '修改',
            area: ['500px', '250px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">活动标识</span><input id="gift_sign" type="text"   class="form-control"></div>' +
            '</div>',
            yes: function (index1) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=917",
                    data: {
                        gi: $("#group").val(),
                        gift_sign:$("#gift_sign").val()
                    },
                    dataType: "json",
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        if (json==0){
                            layer.msg('重复的标识!',{time:800});
                            return false;
                        }else{
                            layer.close(index1);
                            layer.alert('成功', {icon: 1}, function (index) {
                                layer.close(index);
                                getTbPath();
                            });
                        }
                    }
                });
            }
        });
    });
    $("#jin_sync1").on('click', function () {
        sync(1);
    });
    function sync(s_type) {
        var IDs =getChoose().idss;
        if (IDs == '') {
            layer.msg('请选择活动!');
            return false;
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '同步数据',
            area: ['500px', '600px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">目标渠道</span>' +
            '<div class="select_group_div" style="width: 60%;">'+
            '<select id="g_other" class="selectpicker show-tick " multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
            '</div>'+
            '</div>' +
            '</div>',
            success: function (index) {
                obj11 = {id: '#g_other'};
                obj11.url = location.href + "&jinIf=9211&s_type="+s_type;
                groups(obj11);
            },
            yes: function (index1) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=921",
                    data: {
                        gi: $("#group").val(),
                        sign: $("#sign").val(),
                        gig: $("#g_other").val(),
                        s_type:s_type,
                        ids:IDs
                    },
                    dataType: "json",
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        if(json.status==1){
                            layer.alert('成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }else{
                            layer.alert(json.msg, {icon: 2}, function (index) {
                                layer.close(index);
                            });
                        }
                    }
                });
            }
        });
    }


    function common() {
        data.gi=$("#group").val();
        data.sign = $("#sign").val();
        noPageContentList(url, data, id, arr);
    }
    $("#group").on('change',function () {
        getTbPath();
    });
    $("#sign").on('change',function () {
        common();
    });
    function getTbPath() {
        $.ajax({
            type: "post",
            url: location.href + "&jinIf=916",
            dataType: "json",
            data:{
                'gi': $("#group").val()
            },
            success: function (res) {
                var arr = [];
                for (var i = 0; i < res.length; i++) {
                    arr[i] = {//索引版配置
                        id: res[i].sign,
                        text: res[i].sign
                    }
                }
                if(arr.length>0){
                    $("#sign").html('').val('');
                    $("#sign").select2({
                        data: arr,
                        placeholder: '请选择',
                        theme: "classic",
                        width: "200px",
                        multiple: false
                    }).trigger('change');
                }else {
                    $("#sign").html('').val('');
                }
            }
        });
    }



    $('#content').on('click', 'a[data-type="update"]', function() {
        var id = $(this).parents('tr').find('input').eq(0).val();
        window.open(location.href + '&jinIf=919&gi='+$("#group").val()+'&sign='+$("#sign").val()+'&id=' + id);
        return false;
    }).on('click', 'tr', function() {
        var cb = $(this).find('td:first>input');
        if (! cb.is(':checked')) {
            cb.attr('checked', true);
            $(this).attr('style', 'background: #aba5618c');
        } else {
            cb.attr('checked', false);
            $(this).removeAttr('style', 'background: #aba5618c');
        }
    });

    //全选
    $('#tb_th').on('click', 'input[type="checkbox"]', function() {
        var check_on = $(this).is(':checked');
        if (check_on) {
            $('#content').find('input[type="checkbox"]').attr('checked', true);
            $('#content').find('tr').attr('style', 'background: #aba5618c');
        } else {
            $('#content').find('input[type="checkbox"]').attr('checked', false);
            $('#content').find('tr').removeAttr('style', 'background: #aba5618c');
        }
    });
    //批量应用
    function sendBodyAll(s_type,is_add,send_type) {
        if(send_type==0){
            var url111 = location.href + "&jinIf=915";
        }else{
            var url111 = location.href + "&jinIf=9153";
        }
        var IDs =getChoose().idss;
        var si_s = getChoose().si_s;
        if (IDs == '') {
            layer.msg('请选择活动!');
            return false;
        }
        if ($('#s').val() == '') {
            layer.msg('请选择服务器!');
            return false;
        }
        $.ajax({
            type: "POST",
            url: url111,
            data: {
                id: IDs,
                si_s:si_s,
                gi: $("#group").val(),
                sign: $("#sign").val(),
                tb_path: $("#tb_path").val(),
                si:$("#s").val(),
                s_type:s_type,
                is_add:is_add
            },
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (json) {
                if(send_type==0){
                    layer.closeAll('loading');
                    if(json.status==1){
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    }else{
                        layer.alert('部分服务器失败'+json.msg, {icon: 0}, function (index) {
                            layer.close(index);
                        });
                    }
                }else{
                    layer.closeAll('loading');
                    layer.alert('成功', {icon: 1}, function (index) {
                        layer.close(index);
                    });
                }
            }
        });
    }


    $("#close_all").on('click',function(){
        var IDs =getChoose().idss;
        if (IDs == '') {
            layer.msg('请选择活动!');
            return false;
        }
        if ($('#s').val() == '') {
            layer.msg('请选择服务器!');
            return false;
        }
        layer.alert('确认批量关闭吗？', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9152",
                data: {
                    id: IDs,
                    si:$("#s").val()
                },
                dataType: "json",
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
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
        });
    });

    $("#send_all0").on('click',function(){
        layer.confirm('请选择一种<b>应用</b>方式', {
            icon: 0, btnAlign: 'c', btn: ['立即应用(不了解就选这个)', '延时应用'] //按钮
        }, function () {
            sendBodyAll(0,1,0)
        }, function () {
            sendBodyAll(0,1,1)
        });
    });
    $("#delete_all0").on('click',function(){
        layer.confirm('请选择一种<b>应用</b>方式', {
            icon: 0, btnAlign: 'c', btn: ['立即应用(不了解就选这个)', '延时应用'] //按钮
        }, function () {
            sendBodyAll(0,0,0)
        }, function () {
            sendBodyAll(0,0,1)
        });
    });
    $("#send_all0_time").on('click',function(){
        sendTbBodyAllTime(0,1)
    });
    $("#delete_all0_time").on('click',function(){
        sendTbBodyAllTime(0,0)
    });
    function sendTbBodyAllTime(s_type,is_add) {
        var IDs =getChoose().idss;
        var si_s = getChoose().si_s;
        if (IDs == '') {
            layer.msg('请选择活动!');
            return false;
        }
        if ($('#s').val() == '') {
            layer.msg('请选择服务器!');
            return false;
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '定时应用',
            area: ['400px', '200px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">定时时间</span><input id="ttime" type="text" class="form-control"></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=9151",
                    data: {
                        id: IDs,
                        si_s:si_s,
                        gi: $("#group").val(),
                        sign: $("#sign").val(),
                        tb_path: $("#tb_path").val(),
                        si:$("#s").val(),
                        s_type:s_type,
                        is_add:is_add,
                        ttime: $('#ttime').val()
                    },
                    dataType: "json",
                    success: function (json) {
                        layer.close(index);
                        if(json>=0){
                            layer.alert('成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }else{
                            layer.alert('失败', {icon: 0}, function (index) {
                                layer.close(index);
                            });
                        }
                    }
                });
            }
        });
        $(document).ready(calendarOne('hour', "#ttime"));
    }



    // 获取选中的
    function getChoose() {
        var idss = '';
        var si_s = '';
        $('#content input[type="checkbox"]:checked').each(function(index, el) {
            var siss = $(this).parents('tr').find('span').attr('data-data-si');
            if (index == 0) {
                idss = $(el).val();
                if(siss!=undefined){
                    si_s = siss;
                }
            } else {
                idss += ',' + $(el).val();
                if(siss!=undefined){
                    si_s += ';' + siss;
                }
            }
        });
        return {
           'idss':idss,
            'si_s':si_s
        };
    }
    $("#jin_update").on('click', function () {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '修改标识码',
            area: ['500px', '250px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">新标识名字</span><input id="new_sign" type="text"   class="form-control"></div>' +
            '</div>',
            yes: function (index1) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=923",
                    data: {
                        gi: $("#group").val(),
                        sign: $("#sign").val(),
                        new_sign:$("#new_sign").val()
                    },
                    dataType: "json",
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        if (json==0){
                            layer.msg('重复的标识!',{time:800});
                            return false;
                        }else{
                            layer.close(index1);
                            layer.alert('成功', {icon: 1}, function (index) {
                                layer.close(index);
                                location.reload();
                            });
                        }
                    }
                });
            }
        });
    });
    $("#jin_copy").on('click', function () {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '复制活动到指定渠道',
            area: ['600px', '600px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
            '<div class="select_group_div">'+
            '<select id="gg" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select></div>' +
            '</div>',
            success:function () {
                var obj3 = {};
                obj3.id = '#gg';
                obj3.url = "?p=Admin&c=Operation&a=group&jinIf=943";
                groups(obj3);
                $('#gg').selectpicker({
                    actionsBox: 'true'
                });
                $('.bs-select-all').text('全选');
                $('.bs-deselect-all').text('取消全选');
                $(".bs-actionsbox .btn-group").html('<div class="btn-group btn-group-sm btn-block"><button type="button" class="actions-btn bs-select-all btn btn-default">全选</button><button type="button" class="actions-btn bs-deselect-all btn btn-default">取消全选</button><button type="button" id="sure" class="btn btn-default">确认</button></div>');
            },
            yes: function (index1) {
                if ($("#gg").val() == '') {
                    layer.alert('请选择目标渠道！', {icon: 2}, function (index) {
                        layer.close(index);
                    });
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=924",
                    data: {
                        gi: $("#group").val(),
                        sign: $("#sign").val(),
                        copyTogi:$("#gg").val()
                    },
                    dataType:'json',
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json1) {
                        layer.closeAll('loading');
                        layer.close(index1);
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    }
                });
            }
        });
    });

    $("#jin_copy_one").on('click', function () {
        var IDs =getChoose().idss;
        if (IDs == '') {
            layer.msg('请选择活动!');
            return false;
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '复制活动到指定渠道',
            area: ['600px', '600px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div id="aaaa" class="jin-child">' +
            '<div class="select_group_div">'+
            '<select id="gg" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select></div>' +
            '<label for="sign11" style="font-size: 18px;">标识：</label><select  id="sign11"></select>'+
            '</div>',
            success:function () {
                var obj3 = {};
                obj3.id = '#gg';
                obj3.url = "?p=Admin&c=Operation&a=group&jinIf=943";
                groups(obj3);
                $('#gg').selectpicker({
                    actionsBox: 'true'
                });
                $('.bs-select-all').text('全选');
                $('.bs-deselect-all').text('取消全选');
                $(".bs-actionsbox .btn-group").html('<div class="btn-group btn-group-sm btn-block"><button type="button" class="actions-btn bs-select-all btn btn-default">全选</button><button type="button" class="actions-btn bs-deselect-all btn btn-default">取消全选</button><button type="button" id="sure" class="btn btn-default">确认</button></div>');

                $("#gg").on('change', function () { //渠道改变的时候
                    if($("#gg").val().length==1){
                        $.ajax({
                            type: "post",
                            url: location.href + "&jinIf=916",
                            dataType: "json",
                            data:{
                                'gi': $('#gg').val()[0]
                            },
                            success: function (res) {
                                var arr = [];
                                for (var i = 0; i < res.length; i++) {
                                    arr[i] = {//索引版配置
                                        id: res[i].sign,
                                        text: res[i].sign
                                    }
                                }
                                if(arr.length>0){
                                    $("#sign11").html('').val('');
                                    $("#sign11").select2({
                                        data: arr,
                                        placeholder: '请选择',
                                        theme: "classic",
                                        width: "200px",
                                        multiple: false,
                                        dropdownParent:$("#aaaa")
                                    }).trigger('change');
                                }else {
                                    $("#sign11").html('').val('');
                                }
                            }
                        });
                    }
                });
            },
            yes: function (index1) {
                if ($("#gg").val() == '') {
                    layer.alert('请选择目标渠道！', {icon: 2}, function (index) {
                        layer.close(index);
                    });
                    return false;
                }
                if ($("#sign11").val() == null) {
                    layer.alert('请选择目标标识！', {icon: 2}, function (index) {
                        layer.close(index);
                    });
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=9241",
                    data: {
                        gi: $("#group").val(),
                        sign: $("#sign").val(),
                        copyTogi:$("#gg").val(),
                        copyTosign:$("#sign11").val(),
                        id: IDs
                    },
                    dataType:'json',
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json1) {
                        layer.closeAll('loading');
                        layer.close(index1);
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    }
                });
            }
        });
    });

    $("#jin_copy_one1").on('click', function () {
        var IDs =getChoose().idss;
        if (IDs == '') {
            layer.msg('请选择活动!');
            return false;
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '同步活动到指定渠道',
            area: ['600px', '600px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div id="aaaa" class="jin-child">' +
            '<div class="select_group_div">'+
            '<select id="gg" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select></div><br><br>' +
            '<label for="sign11" style="font-size: 18px;">标识：</label><select  id="sign11"></select><br><br>'+
            '<label for="server_col_idx" style="font-size: 18px;">列名：</label>' +
            '<select name="usertype" class="selectpicker show-tick" multiple data-live-search="false"  id="server_col_idx">' +
            '<option value="Name">礼包名</option>' +
            '<option value="Tip">礼包描述</option>' +
            '<option value="Icon">礼包Icon</option>' +
            '<option value="IsOpen">是否开放</option>' +
            '<option value="LimitCount">限购次数</option>' +
            '<option value="OpenTime">开启时间</option>' +
            '<option value="EndTime">结束时间</option>' +
            '<option value="WeekID">周目限制</option>' +
            '<option value="Multiple">倍数</option>' +
            '<option value="IsCountDown">是否显示倒计时</option>' +
            '<option value="ShowReward1">展示奖励</option>' +
            '<option value="Reward1">实际奖励</option>' +
            '<option value="RewardRandPool">随机奖励</option>' +
            '<option value="RewardRandNum">随机数量</option>' +
            '<option value="Condition">条件</option>' +
            '<option value="RewardType">奖励模式</option>' +
            '<option value="ShowRewardEx1">展示奖励(扩展)</option>' +
            '<option value="RewardEx1">实际奖励(扩展)</option>' +
            '<option value="OtherReward">其他奖励</option>' +
            '<option value="UpdateTime">UpdateTime</option>' +
            '<option value="Price">消耗人民币</option>' +
            '<option value="OldPrice">原始价格</option>' +
            '<option value="PriceiOS">IOS价格</option>' +
            '<option value="PriceAndroid">安卓价格</option>' +
            '<option value="Type">类型</option>' +
            '</select>'+
            '</div>',
            success:function () {
                var obj3 = {};
                obj3.id = '#gg';
                obj3.url = "?p=Admin&c=Operation&a=group&jinIf=943";
                groups(obj3);
                $('#gg').selectpicker({
                    actionsBox: 'true'
                });
                $('.bs-select-all').text('全选');
                $('.bs-deselect-all').text('取消全选');
                $(".bs-actionsbox .btn-group").html('<div class="btn-group btn-group-sm btn-block"><button type="button" class="actions-btn bs-select-all btn btn-default">全选</button><button type="button" class="actions-btn bs-deselect-all btn btn-default">取消全选</button><button type="button" id="sure" class="btn btn-default">确认</button></div>');

                $("#gg").on('change', function () { //渠道改变的时候
                    if($("#gg").val().length==1){
                        $.ajax({
                            type: "post",
                            url: location.href + "&jinIf=916",
                            dataType: "json",
                            data:{
                                'gi': $('#gg').val()[0]
                            },
                            success: function (res) {
                                var arr = [];
                                for (var i = 0; i < res.length; i++) {
                                    arr[i] = {//索引版配置
                                        id: res[i].sign,
                                        text: res[i].sign
                                    }
                                }
                                if(arr.length>0){
                                    $("#sign11").html('').val('');
                                    $("#sign11").select2({
                                        data: arr,
                                        placeholder: '请选择',
                                        theme: "classic",
                                        width: "200px",
                                        multiple: false,
                                        dropdownParent:$("#aaaa")
                                    }).trigger('change');
                                }else {
                                    $("#sign11").html('').val('');
                                }
                            }
                        });
                    }
                });
            },
            yes: function (index1) {
                if ($("#gg").val() == '') {
                    layer.alert('请选择目标渠道！', {icon: 2}, function (index) {
                        layer.close(index);
                    });
                    return false;
                }
                if ($("#sign11").val() == null) {
                    layer.alert('请选择目标标识！', {icon: 2}, function (index) {
                        layer.close(index);
                    });
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=9242",
                    data: {
                        gi: $("#group").val(),
                        sign: $("#sign").val(),
                        copyTogi:$("#gg").val(),
                        copyTosign:$("#sign11").val(),
                        server_col_idx:$("#server_col_idx").val(),
                        id: IDs
                    },
                    dataType:'json',
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json1) {
                        layer.closeAll('loading');
                        layer.close(index1);
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    }
                });
            }
        });
    });


    $("#jin_jindu").on('click', function () {
        var finall_status=0;
        var layer_aaa = layer.msg('初始化', {
            icon: 16,
            shade: 0.3,
            time: false
        });
        var sss =  setInterval(function () {
            if(finall_status==1){
                clearInterval(sss); //关闭定时器
                setTimeout(function () {
                    layer.close(layer_aaa);
                    layer.closeAll('loading');
                },1000); //关闭定时器后 3s关闭最后一个sss产生的遮罩
            }
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9154",
                data: {
                    id: 0
                },
                dataType: "json",
                success: function (json1) {
                    if(json1[0].length==json1[1].length && json1[1][0].length!=''){
                        finall_status=1;
                    }
                    var cc='';
                    for (var i=0;i<json1[2].length;i++){
                        if(json1[1].includes(json1[0][i])){
                            cc+='<span style="color: #00a917; font-size: 20px;">'+json1[2][i]+'</span><br>'
                        }else{
                            cc+=json1[2][i]+'<br>'
                        }
                    }
                    layer_aaa=layer.msg('进度:<br>'+cc, {
                        icon: 16,
                        shade: 0.3,
                        time: false
                    });
                }
            });
        },5000);
    });

    $('#content').on('click', 'a[class="btn btn-info"]', function () {
        layer.alert($(this).data('info'));
        return false;
    });
    // 导出Excel
    $("#jin_excel").on('click', function () {
        data.gi=$("#group").val();
        data.sign = $("#sign").val();
        if($("#sign").val()==null){
            layer.msg('缺少标识!');
            return false;
        }
        $.ajax({
            type: "post",
            url: location.href + '&jinIf=926',
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
    $("#upload").click(function () {
        var formData = new FormData($('#uploadForm')[0]);
        var gi = $("#group").val();
        var sign = $("#sign").val();
        //sign = '测试';
        if(sign==null){
            layer.msg('缺少标识!');
            return false;
        }
        layer.alert('确认操作?', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function (index2) {
            layer.close(index2);
            $.ajax({
                type: 'post',
                url: location.href + '&jinIf=927&gi='+gi+'&sign='+sign,
                data: formData,
                cache: false,
                dataType: 'json',
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                processData: false,
                contentType: false,
                success:function (json) {
                    layer.closeAll('loading');
                    if(json.status==1){
                        layer.alert(json.msg, {icon: 1}, function (index1) {
                            layer.close(index1);
                            $("#file").val('');
                        });
                    }else{
                        layer.alert(json.msg, {icon: 0}, function (index1) {
                            layer.close(index1);
                        });
                    }

                }
            })
        });
    });
    $("#all_update").on('click', function () {
        var IDs =getChoose().idss;
        if (IDs == '') {
            layer.msg('请选择活动!');
            return false;
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '批量修改',
            area: ['600px', '300px'],
            btn: ['修改', '取消'],
            btnAlign: 'c',
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">开启时间</span><input id="OpenTime" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">结束时间</span><input id="EndTime" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">UpdateTime</span><input id="UpdateTime" type="text" class="form-control"></div>' +
            '</div>',
            yes: function (index1) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=928',
                    data: {
                        id: IDs,
                        gi: $("#group").val(),
                        sign: $("#sign").val(),
                        OpenTime: $("#OpenTime").val(),
                        EndTime: $("#EndTime").val(),
                        UpdateTime: $("#UpdateTime").val()
                    },
                    dataType: 'json',
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json1) {
                        layer.closeAll('loading');
                        layer.close(index1);
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                            common();
                        });
                    }
                });
            }
        })
    });
<?php echo '</script'; ?>
><?php }
}
