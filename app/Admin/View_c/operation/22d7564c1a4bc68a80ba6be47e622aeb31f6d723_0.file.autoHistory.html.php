<?php
/* Smarty version 3.1.30, created on 2024-03-07 09:57:12
  from "/lnmp/www/app/Admin/View/operation/autoHistory.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65e91ef84c78d6_54002489',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '22d7564c1a4bc68a80ba6be47e622aeb31f6d723' => 
    array (
      0 => '/lnmp/www/app/Admin/View/operation/autoHistory.html',
      1 => 1678771400,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_65e91ef84c78d6_54002489 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<link href="<?php echo CSS;?>
jin/3.05.serverswitch.css" rel="stylesheet">
<style>
    .bootstrap-select:not([class*=col-]):not([class*=form-control]):not(.input-group-btn) {
        width: 400px;
    }
</style>
<div class="jin-content-title"><span>自动开服记录</span></div>
<div class="form-group" id="group_server_6"></div>
<a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
<div class="jin-server-select">
    <select  id="status" style="padding: 10px;">
        <option value="3">待审核</option>
        <option value="0">未完成</option>
        <option value="1">已完成</option>
        <option value="2">已删除</option>
    </select>
    <hr>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-hover text-center jin-server-table">
        <thead>
        <tr>
            <th>编号</th>
            <th>开启的服务器</th>
            <th>基准服务器</th>
            <th>注册/付费设备数/时间</th>
            <th>检测时间1</th>
            <th>检测时间2</th>
            <th>通知邮箱</th>
            <th>操作/完成时间</th>
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
    gsSelect3('#g');
    function getAuto() {
        var c='';
        $.ajax({
            type: "POST",
            url: location.href + '&jinIf=915',
            data:{
                status:$("#status").val(),
                gi:$("#g").val()
            },
            dataType:'json',
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (json) {
                layer.closeAll('loading');
                if(json.length>=1){
                    for (var i = 0; i < json.length; i++) {
                        if(json[i]['status']==1){
                            var b=json[i]['update_time'];
                        }else{
                            var b='<a data-type="go_auto" class="btn btn-primary">立即执行</a><br><br>' +
                                '<a data-type="update_auto" class="btn btn-success">修改</a><br><br>'+
                                '<a data-type="del_auto" class="btn btn-danger">删除</a></td>';
                            if(json[i]['is_show']==0){
                                var b='<a data-type="reback_auto" data-data-sid="'+json[i]['si']+'" class="btn btn-success">还原</a><br><br>'
                            }
                            if(json[i]['status']==3){
                                var b='<a data-type="audit" class="btn btn-primary">审核</a><br><br>' +
                                    '<a data-type="update_auto" class="btn btn-success">修改</a><br><br>'
                            }
                        }
                        if(json[i]['code_type']==0){
                            code_typess='注册设备';
                        }else{
                            code_typess='付费设备';
                        }
                        if(json[i]['code_type']==2){
                            code_typess='时间';
                        }
                        c +=
                            '<tr>' +
                            '<td>' + json[i]['id'] + '</td>' +
                            '<td data-data-gi="'+json[i]['gi']+'" data-data-si="'+json[i]['si']+'">' + json[i]['sis'] + '</td>' +
                            '<td data-data-gi="'+json[i]['standard2']+'" data-data-si="'+json[i]['standard']+'">' + json[i]['standard1'] + '</td>' +
                            '<td data-data-type="'+json[i]['code_type']+'" data-data-num="'+json[i]['codenum']+'">' + json[i]['codenum'] + '('+code_typess+')</td>' +
                            '<td>' + json[i]['hour']  + '</td>' +
                            '<td>' + json[i]['hourr']  + '</td>' +
                            '<td>' + json[i]['e_mail']  + '</td>' +
                            '<td>' + b+ '</td>' +
                            '</tr>';
                        $("#content").html(c);
                    }
                }else {
                    $("#content").html('');
                }
            }
        });
    }
    var data = {};
    $("#jin_search").on('click', function () {
        if($("#g").val()==''){
            layer.alert('请选择渠道！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        }
        $.cookie('cookie_g', data.group_id, {expires: 30});
        getAuto();
    });
    $('#content').on('click', 'a[data-type="del_auto"]', function() {
        var id = $(this).parents('tr').find('td').eq(0).text();
        layer.alert('确认删除？', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=916",
                data: {
                    id: id
                },
                dataType: "json",
                success: function (json) {
                    layer.alert('删除成功', {icon: 1}, function (index) {
                        layer.close(index);
                        getAuto();
                    });
                }
            });
        });

    }).on('click', 'a[data-type="audit"]', function() {
        var id = $(this).parents('tr').find('td').eq(0).text();
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=9161",
            data: {
                id: id
            },
            dataType: "json",
            success: function (json) {
                getAuto();
            }
        });

    }).on('click', 'a[data-type="go_auto"]', function() {
        var id = $(this).parents('tr').find('td').eq(0).text();
        var info = $(this).parents('tr').find('td').eq(1).text();
        layer.alert('确认立即执行？', {icon: 1, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=917",
                data: {
                    id: id,
                    info: info
                },
                dataType: "json",
                success: function (json) {
                    console.log(json)
                    if(json){
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                            getAuto();
                        });
                    }else{
                        layer.alert('失败', {icon: 2}, function (index) {
                            layer.close(index);
                        });
                    }
                }
            });
        });

    }).on('click', 'a[data-type="update_auto"]', function() {
        var id = $(this).parents('tr').find('td').eq(0).text();
        var codenum = $(this).parents('tr').find('td').eq(3).attr('data-data-num');
        var odate = $(this).parents('tr').find('td').eq(3).attr('data-data-num');
        var e_mail = $(this).parents('tr').find('td').eq(6).text();
        var code_type = $(this).parents('tr').find('td').eq(3).attr('data-data-type');
        var ggg1 =$(this).parents('tr').find('td').eq(1).attr('data-data-gi');
        var ggg2 =$(this).parents('tr').find('td').eq(2).attr('data-data-gi');
        var sss1 =$(this).parents('tr').find('td').eq(1).attr('data-data-si');
        var sss2 =$(this).parents('tr').find('td').eq(2).attr('data-data-si');
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '自动开服设置',
            area: ['1000px', '700px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon" style="width: 100px;">开启服务器</span>' +
            '<div class="select_group_div" style="width: 45%;">'+
            '<select id="gg" class="selectpicker show-tick " multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
            '</div>'+
            '<div class="select_group_div" style="width: 45%;">'+
            '<select id="ss" class="selectpicker show-tick" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
            '</div>' +
            '</div>' +
            '<div class="input-group"><span class="input-group-addon">基准服务器</span>' +
            '<div class="select_group_div" style="width: 45%;">'+
            '<select id="g1" class="selectpicker show-tick" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
            '</div>'+
            '<div class="select_group_div" style="width: 45%;">'+
            '<select id="s1" class="selectpicker show-tick" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
            '</div></div>' +
            '<div class="input-group"><span class="input-group-addon">检测设备类型</span>' +
            '<select id="code_type" class="input-group-addon" style="width: 150px;" >' +
            '<option value="0">注册数</option>' +
            '<option value="1">付费数</option>' +
            '<option value="2">时间</option>' +
            '</select></div>' +
            '<div class="input-group codenum"><span class="input-group-addon">注册设备数</span><input type="text" id="codenum" value="'+codenum+'" class="form-control jin-datetime-long"></div>' +
            '<div class="input-group odate"><span class="input-group-addon">时间</span><input id="odate" type="text" value="'+odate+'" class="form-control jin-datetime-long"></div>' +
            '<div class="input-group"><span class="input-group-addon">通知邮箱</span><input type="text" id="e_mail" value="'+e_mail+'" class="form-control"></div>' +
            '</div>',
            success: function (index) {
                if(code_type==2){
                    $(".codenum").hide();
                    $(".odate").show();
                }else{
                    $(".codenum").show();
                    $(".odate").hide();
                }

                $("#code_type").on('change',function () {
                    $("#codenum").val('');
                    $("#odate").val('');
                    if($(this).val()==2){
                        $(".codenum").hide();
                        $(".odate").show();
                    }else{
                        $(".codenum").show();
                        $(".odate").hide();
                    }
                });
                $("#code_type option[value='"+code_type+"']").attr("selected","selected");
                obj1 = {id: '#gg'};
                obj1.url = "?p=Admin&c=Operation&a=group&jinIf=943";
                obj3 = {id: '#ss'};
                obj3.url = "?p=Admin&c=Operation&a=server&jinIf=943";
                groups(obj1);
                if(ggg1){
                    setTimeout(function () {
                        $('#gg').selectpicker('val', eval('[' +ggg1+ ']'));
                        $('#gg').selectpicker('refresh');
                    },1000);
                    obj3.gi = ggg1;
                    servers(obj3);
                    setTimeout(function () {
                        $('#ss').selectpicker('val', eval('[' +sss1+ ']'));
                        $('#ss').selectpicker('refresh');
                    },1000)
                }
                $('#gg').on('change', function () { //渠道改变的时候
                    obj3.gi = $('#gg').val();
                    servers(obj3);
                });
                obj11 = {id: '#g1'};
                obj11.url = "?p=Admin&c=Operation&a=group&jinIf=943";
                obj33 = {id: '#s1'};
                obj33.url = "?p=Admin&c=Operation&a=server&jinIf=943";
                groups(obj11);
                if(ggg2){
                    setTimeout(function () {
                        $('#g1').selectpicker('val', eval('[' +ggg2+ ']'));
                        $('#g1').selectpicker('refresh');
                    },1200);
                    obj33.gi = ggg2;
                    servers(obj33);
                    setTimeout(function () {
                        $('#s1').selectpicker('val', eval('[' +sss2+ ']'));
                        $('#s1').selectpicker('refresh');
                    },1200)
                }
                $('#g1').on('change', function () { //渠道改变的时候
                    obj33.gi = $('#g1').val();
                    servers(obj33);
                });
            },
            yes: function (index1) {
                if($("#gg").val()==''||$("#ss").val()==''||$("#s1").val()==''){
                    layer.msg('请正确填写内容!',{time:800});
                    return false;
                }
                if($("#code_type").val()==2&&$("#odate").val()==''){
                    layer.msg('请正确填写内容!',{time:800});
                    return false;
                }
                if($("#code_type").val()!=2&&$("#codenum").val()==''){
                    layer.msg('请正确填写内容!',{time:800});
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=921",
                    data: {
                        id: id,
                        s: $("#ss").val(),
                        s1: $("#s1").val(),
                        g: $("#gg").val(),
                        e_mail: $("#e_mail").val(),
                        code_type: $("#code_type").val(),
                        odate:$("#odate").val(),
                        codenum: $("#codenum").val()
                    },
                    dataType:'json',
                    success: function (json1) {
                        layer.alert('修改成功', {icon: 1}, function (index) {
                            layer.close(index1);
                            layer.close(index);
                            getAuto();
                        });
                    }
                });
            },
            cancel: function () {
            }
        });
        calendarOne('hour', '#odate');
    }).on('click', 'a[data-type="reback_auto"]', function() {
        var id = $(this).parents('tr').find('td').eq(0).text();
        var sid = $(this).attr("data-data-sid");
        layer.alert('确认还原吗？请确定该任务是否执行成功', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=922",
                data: {
                    id: id,
                    sid:sid
                },
                dataType: "json",
                success: function (json) {
                    if(json>0){
                        layer.alert('还原成功', {icon: 1}, function (index) {
                            layer.close(index);
                            getAuto();
                        });
                    }else{
                        layer.alert('还原失败! 该任务已有服务器开启', {icon: 2}, function (index) {
                            layer.close(index);
                        });
                    }

                }
            });
        });

    });
<?php echo '</script'; ?>
>
<?php }
}
