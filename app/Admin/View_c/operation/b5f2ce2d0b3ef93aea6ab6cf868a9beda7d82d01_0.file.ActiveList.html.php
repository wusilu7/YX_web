<?php
/* Smarty version 3.1.30, created on 2024-08-16 10:07:08
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\operation\ActiveList.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66beb44ce2edc1_43162356',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b5f2ce2d0b3ef93aea6ab6cf868a9beda7d82d01' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\operation\\ActiveList.html',
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
function content_66beb44ce2edc1_43162356 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<style>
    .filed-width{
        width: 70% !important;
    }
</style>
<div class="jin-content-title"><span>热更活动表Server</span></div>
<div class="alert alert-info">
     <div id="group_only" style="display: inline-block;"></div>
    <label for="tb_path" style="color: white;">表名：</label>
    <select  id="tb_path"></select>
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    <a id="jin_add" class="btn btn-info jin_add"><span class="glyphicon glyphicon-plus"></span></a>
    <form id="uploadForm" enctype="multipart/form-data" style="display: inline-block;">
        <input id="file" accept=".xls,.xlsx" type="file" name="file"/>
    </form>
    <button id="upload" class='btn btn-success'>导入数据</button>
    <a id="sync0" class="btn btn-info jin_add">同步欧美</a>
    <a id="sync2" class="btn btn-info jin_add">同步海外</a>
    <a id="sync1" class="btn btn-info jin_add">同步国内</a>
</div>
<button id='send_all0' class='btn btn-success'>批量应用</button>
<button id='delete_all0' class='btn btn-danger'>批量撤回</button>
<button id='send_all0_time' class='btn btn-success'>定时批量应用</button>
<button id='delete_all0_time' class='btn btn-danger'>定时批量撤回</button>
<button id='delete_all' class='btn btn-danger'>批量删除(热更后数据库)</button>
<button id='delete_all_before' class='btn btn-danger'>批量删除(本地数据库)</button>
<button id="jin_jindu" class="btn btn-primary">查看应用进度</button>
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
        '<a data-type="update" class="btn btn-primary">修改</a>&nbsp;' +
        '<a data-type="delete" class="btn btn-danger">删除</a>'
    ];
    var arr = [];
    var id = "#content";
    var data = {};
    var id_check = function (json) {
        return '<input type="checkbox" value="' + json[json['IDS']] + '" />'
    };
    // 普通查询
    $("#jin_search").on('click', function () {
        select();
    });

    function select() {
        $.ajax({
            type: "POST",
            url: "/?p=Admin&c=Operation&a=ActiveListStructure&jinIf=912&show=1",
            data: {
                gi:$("#group").val(),
                tb_path:$("#tb_path").val()
            },
            dataType: "json",
            success: function (json) {
                arr = [];
                arr.push(id_check);
                var c= '<th><input id="all_choose" type="checkbox"><label for="all_choose">全选</label></th>';
                for (var i=0;i<json.length;i++){
                    arr.push(json[i]['filed_name']);
                    c += '<th>'+json[i]['filed_name']+'<br>'+json[i]['filed_annotation']+'</th>';
                }
                arr.push(btn);
                c +='<th>操作</th>';
                $("#tb_th").html(c);
                common();
            }
        });
    }

    function common() {
        data.gi=$("#group").val();
        data.tb_path=$("#tb_path").val();
        noPageContentList(url, data, id, arr);
    }

    //增加
    $("#jin_add").on('click', function () {
        $.ajax({
            type: "POST",
            url: "/?p=Admin&c=Operation&a=ActiveListStructure&jinIf=912&show=1",
            data: {
                gi:$("#group").val(),
                tb_path:$("#tb_path").val()
            },
            dataType: "json",
            success: function (json) {
                var c='';
                var arr_filed = [];//字段名放入数组以便于下面yes遍历
                for (var i=0;i<json.length;i++){
                    arr_filed.push(json[i]['filed_name']);
                    c+='<div class="input-group"><span class="input-group-addon">'+json[i]['filed_name']+'</span>' +
                        '<input type="text" id="'+json[i]['filed_name']+'" data-data-tb_path="'+json[i]['tb_path']+
                        '" data-data-client_tb_id="'+json[i]['client_tb_id']+'" data-data-is_utf8="'+json[i]['is_utf8']+'" data-data-client_col_id="'+json[i]['client_col_id']+
                        '"  class="form-control" placeholder="'+ json[i]['filed_annotation']+'"></div>';
                }
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '添加活动表',
                    area: ['800px', '900px'],
                    btn: ['确认', '取消'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content:'<div class="jin-child">'+c+'</div>',
                    yes: function (index1) {
                        var arr_tb_body =[];
                        for (var i=0;i<arr_filed.length;i++){
                            var json_temporary={
                                'client_dbc_id':$("#"+arr_filed[i]).attr('data-data-client_tb_id'),
                                'client_row_idx':$("#"+arr_filed[0]).val(),
                                'client_col_idx':$("#"+arr_filed[i]).attr('data-data-client_col_id'),
                                'client_value':$("#"+arr_filed[i]).val(),
                                'server_dbc_name':$("#"+arr_filed[i]).attr('data-data-tb_path'),
                                'server_row_idx':$("#"+arr_filed[0]).attr('id'),
                                'server_cond_value':$("#"+arr_filed[0]).val(),
                                'server_col_idx':$("#"+arr_filed[i]).attr('id'),
                                'server_value':$("#"+arr_filed[i]).val(),
                                'is_utf8':$("#"+arr_filed[i]).attr('data-data-is_utf8')
                            };
                            arr_tb_body.push(json_temporary);
                        }
                        $.ajax({
                            type: "POST",
                            url: location.href + "&jinIf=911",
                            data: {
                                arr_tb_body:arr_tb_body,
                                gi:$("#group").val()
                            },
                            dataType: "json",
                            success: function () {
                                layer.close(index1);
                                layer.alert('成功', {icon: 1}, function (index) {
                                    layer.close(index);
                                });
                                select();
                            }
                        });
                    }
                });
            }
        });
    });

    $("#group").on('change',function () {
        getTbPath();
    });

    function getTbPath() {
        $.ajax({
            type: "post",
            url: "/?p=Admin&c=Operation&a=ActiveListStructure&jinIf=9121",
            dataType: "json",
            data:{
                'gi': $("#group").val()
            },
            success: function (res) {
                var arr = [];
                for (var i = 0; i < res.length; i++) {
                    arr[i] = {//索引版配置
                        id: res[i].tb_path,
                        text: res[i].tb_name
                    }
                }
                if(arr.length>0){
                    $("#tb_path").select2({
                        data: arr,
                        placeholder: '请选择',
                        theme: "classic",
                        width: "200px",
                        multiple: false
                    }).trigger('change');
                }else {
                    $("#tb_path").html('').val('');
                }
            }
        });
    }

    $('#content').on('click', 'a[data-type="delete"]', function() {
        var id = $(this).parents('tr').find('input').eq(0).val();
        layer.alert('确认删除吗？', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=914",
                data: {
                    id: id,
                    gi: $("#group").val(),
                    tb_path: $("#tb_path").val()
                },
                dataType: "json",
                success: function () {
                    layer.alert('删除成功', {icon: 1}, function (index) {
                        layer.close(index);
                        select();
                    });
                }
            });
        });
        return false;
    }).on('click', 'a[data-type="update"]', function() {
        var id = $(this).parents('tr').find('input').eq(0).val();
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=916",
            data: {
                id: id,
                gi: $("#group").val(),
                tb_path: $("#tb_path").val()
            },
            dataType: "json",
            success: function (json) {
                var c='';
                var arr_filed = [];//字段名放入数组以便于下面yes遍历
                for (var i=0;i<json.length;i++){
                    var is_send_s = "";
                    var is_disabled = "";
                    if(json[i]['is_send_s']==1){
                        is_send_s = "checked";
                    }
                    if(json[i]['server_row_idx']==json[i]['server_col_idx']){
                        is_disabled = "disabled";
                    }
                    arr_filed.push(json[i]['server_col_idx'].trim());
                    c+='<div class="input-group"><span class="input-group-addon">'+json[i]['server_col_idx']+'</span>' +
                        '<input type="text" '+is_disabled+'  id="'+json[i]['server_col_idx'].trim()+'" value="'+json[i]['server_value'].replace(/\"/g,"&#34;")+'" ' +
                        'data-data-server_cond_value="'+json[i]['server_cond_value']+'"  class="form-control filed-width">' +
                        '<input '+is_disabled+' style="margin-left: 10px; width: 20px; height: 20px;" '+is_send_s+' type="checkbox" id="'+json[i]['server_col_idx']+'_is_send_s" value="1">服务器' +
                        '</div>';
                }
                console.log(arr_filed)
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '修改活动表',
                    area: ['900px', '600px'],
                    btn: ['确认', '取消'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content:'<div class="jin-child">' +
                    c+
                    '</div>',
                    yes: function (index1) {
                        var arr_tb_body =[];
                        for (var i=0;i<arr_filed.length;i++){
                            var json_temporary={
                                'server_col_idx':arr_filed[i],
                                'server_value':$("#"+arr_filed[i]).val(),
                                'is_send_s':$("#"+arr_filed[i]+"_is_send_s").is(':checked') ? $("#"+arr_filed[i]+"_is_send_s").val() : 0,
                                'server_cond_value':$("#"+arr_filed[i]).attr('data-data-server_cond_value')
                            };
                            arr_tb_body.push(json_temporary);
                        }
                        $.ajax({
                            type: "POST",
                            url: location.href + "&jinIf=917",
                            data: {
                                arr_tb_body:arr_tb_body,
                                gi: $("#group").val(),
                                tb_path: $("#tb_path").val()
                            },
                            beforeSend: function () {
                                layer.load(2, {
                                    shade: [0.3, '#fff']//0.3透明度的白色背景
                                });
                            },
                            dataType: "json",
                            success: function () {
                                layer.closeAll('loading');
                                layer.close(index1);
                                layer.alert('成功', {icon: 1}, function (index) {
                                    layer.close(index);
                                    select();
                                });

                            }
                        });
                    }
                });
            }
        });
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
            var url111 = location.href + "&jinIf=9131";
        }else{
            var url111 = location.href + "&jinIf=9133";
        }
        var IDs =getChoose();
        if (IDs == '') {
            layer.msg('请选择活动!');
            return false;
        }
        if ($('#s').val() == '') {
            layer.msg('请选择服务器!');
            return false;
        }
        layer.alert('确认批量应用吗？', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: url111,
                data: {
                    id: IDs,
                    gi: $("#group").val(),
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
        });
    }
    //批量定时应用
    function sendTbBodyAllTime(s_type,is_add) {
        var IDs =getChoose();
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
                    url: location.href + "&jinIf=9132",
                    data: {
                        id: IDs,
                        gi: $("#group").val(),
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

    $("#delete_all").on('click',function(){
        var IDs =getChoose();
        if (IDs == '') {
            layer.msg('请选择活动!');
            return false;
        }
        if ($('#s').val() == '') {
            layer.msg('请选择服务器!');
            return false;
        }
        layer.alert('确认操作吗？', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9141",
                data: {
                    id: IDs,
                    tb_path: $("#tb_path").val(),
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
                    layer.alert('成功', {icon: 1}, function (index) {
                        layer.close(index);
                    });
                }
            });
        });
    });

    $("#delete_all_before").on('click',function(){
        var IDs =getChoose();
        if (IDs == '') {
            layer.msg('请选择活动!');
            return false;
        }
        layer.alert('确认操作吗？', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9142",
                data: {
                    id: IDs,
                    tb_path: $("#tb_path").val(),
                    gi: $("#group").val()
                },
                dataType: "json",
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
                    layer.alert('成功', {icon: 1}, function (index) {
                        layer.close(index);
                    });
                }
            });
        });
    });

    // 获取选中的服务器
    function getChoose() {
        var idss = '';
        $('#content input[type="checkbox"]:checked').each(function(index, el) {
            if (index == 0) {
                idss = $(el).val();
            } else {
                idss += ',' + $(el).val();
            }
        });
        return idss;
    }

    $("#upload").click(function () {
        layer.alert('请确保要上传的文件已生成表结构!', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function (index2) {
            layer.close(index2);
            var formData = new FormData($('#uploadForm')[0]);
            var gi = $("#group").val();
            var tb_path = $("#tb_path").val();
            $.ajax({
                type: 'post',
                url: location.href + '&jinIf=915&gi='+gi+'&tb_path='+tb_path,
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
    $("#sync0").on('click', function () {  // 点击后本地数据库
        sync(0);
    });
    $("#sync1").on('click', function () {  // 点击后本地数据库
        sync(1);
    });
    $("#sync2").on('click', function () {  // 点击后本地数据库
        sync(2);
    });
    function sync(s_type) {
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
                obj11.url = location.href + "&jinIf=919&s_type="+s_type;
                groups(obj11);
            },
            yes: function (index1) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=921",
                    data: {
                        gi: $("#group").val(),
                        tb_path: $("#tb_path").val(),
                        gig: $("#g_other").val(),
                        s_type:s_type
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
                url: location.href + "&jinIf=9134",
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

<?php echo '</script'; ?>
><?php }
}
