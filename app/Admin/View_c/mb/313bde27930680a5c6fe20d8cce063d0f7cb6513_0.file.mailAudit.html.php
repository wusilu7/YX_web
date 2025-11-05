<?php
/* Smarty version 3.1.30, created on 2023-03-21 20:39:19
  from "/lnmp/www/app/Admin/View/mb/mailAudit.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6419a577ade9d5_48837308',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '313bde27930680a5c6fe20d8cce063d0f7cb6513' => 
    array (
      0 => '/lnmp/www/app/Admin/View/mb/mailAudit.html',
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
function content_6419a577ade9d5_48837308 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.18.mailAudit.css" rel="stylesheet">
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
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>普通邮件审核</span></div>
<div class="alert alert-info">
    <div class="form-group" id="group_server_9"></div>
</div>
<div class="jin-explain">
    <div>
        ① 批量excel发送邮件步骤:先下载Excel模板,填写对应数据,点击导入数据,最后选择批量审核；
    </div>
    <hr>
</div>
<a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>

<button id="jin_excel" class='btn btn-success'>Excel模板下载</button>
<form id="uploadForm" enctype="multipart/form-data" style="display: inline-block;">
    <input id="file" type="file" name="file"/>
</form>
<button id="upload" class='btn btn-primary'>导入数据</button>
<button id='s_all1' class='btn btn-success'>批量审核</button>
<button id='del_all1' class='btn btn-danger'>批量删除</button>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th class="jin-server-column1">
                <input id="all_choose" type="checkbox">
                <label for="all_choose">全选</label>
            </th>
            <th>编号</th>
            <th>服务器</th>
            <th>收件人</th>
            <th>邮件标题</th>
            <th>邮件内容</th>
            <th>货币</th>
            <th>道具</th>
            <th>经验</th>
            <th>创建人</th>
            <th>创建时间</th>
            <th class="jin-mail-column10">操作</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
>
    var  data={};
    gsSelect3('#g', '', '#s');
    //$(document).ready(gsSelect('#group', '#server', '#platform', jsonAudit));
    //待审核邮件数据
    function jsonAudit() {
        var url = location.href + "&jinIf=912";
        var btn = [
            "<div class='btn-group btn-group-sm'>" +
            "<button data-type='u' class='btn btn-primary'>修改</button>" +
            "<button data-type='a' class='btn btn-success'>审核通过</button>" +
            "<button data-type='d' class='btn btn-danger'>删除</button>" +
            "</div>"
        ];
        var receiver = function (json) {
            return '<span data-data-receiver_type="'+json.receiver_type+'" data-data-receiver="'+json.receiver+'">【' + json.receiver_type + '】' + json.receiver+'</span>'
        };
        var mail_id_check = function (json) {
            return '<input type="checkbox" value="' + json['mail_id'] + '" />'
        }
        var money_c = function (json) {
            return '<span data-data-money="'+json['money']+'">'+json['money_as']+'</span>'
        };
        var item_c = function (json) {
            return '<span data-data-item="'+json['item']+'">'+json['item_as']+'</span>'
        };
        var arr = [mail_id_check,'mail_id','si', receiver, 'title', 'content', money_c, item_c,'exp', 'cu', 'create_time', btn];
        var id = "#content";
        var data = {
            si: $("#s").val()
        };
        noPageContentList(url, data, id, arr);
    }
    $("#jin_search").click(function () {
        jsonAudit();
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
        var mail_id = '';
        $('#content input[type="checkbox"]:checked').each(function(index, el) {
            if (index == 0) {
                mail_id = $(el).val();
            } else {
                mail_id += ',' + $(el).val();
            }
        });
        if (mail_id == '') {
            layer.alert('请选择！', {icon: 2});
            return false;
        }
        return mail_id;
    }

    // 点击批量开服
    $("#s_all1").click(function() {
        var mail_ids = getChoose();
        layer.alert('确认审核邮件'+mail_ids+'？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=915",
                data: {
                    mail_ids:mail_ids
                },
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                dataType: 'json',
                success: function (json) {
                    layer.closeAll('loading');
                    if(json){
                        layer.alert('审核成功', {icon: 1}, function (index) {
                            layer.close(index);
                            jsonAudit();
                        });
                    }else{
                        layer.alert('权限不足！请联系管理员！', {icon: 2}, function (index) {
                            layer.close(index);
                        });
                    }

                }
            });
        });

    });


    // 点击批量开服
    $("#del_all1").click(function() {
        var mail_ids = getChoose();
        layer.alert('确认删除邮件'+mail_ids+'？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9141",
                data: {
                    mail_ids:mail_ids
                },
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                dataType: 'json',
                success: function (json) {
                    layer.closeAll('loading');
                    layer.alert('成功', {icon: 1}, function (index) {
                        layer.close(index);
                        jsonAudit();
                    });
                }
            });
        });

    });

    //邮件审核
    $('#content').on('click', 'button[data-type="a"]', function () {
        var mail_id = $(this).parents('tr').find('td').eq(1).text();
        var money = $(this).parents('tr').find('td').eq(6).html();
        var item = $(this).parents('tr').find('td').eq(7).html();
        layer.alert('审核通过后 <b>' + mail_id + '号邮件</b> 将激活<br>' +
            '货币:<br>' +
            '<div style="padding-left: 20px;">'+money+'</div>' +
            '道具:<br>' +
            '<div style="padding-left: 20px;">'+item+'</div>'+'<br>', {icon: 0, btn: ['确定', '取消']}, function (tip) {
            layer.close(tip);
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9138",
                data: {
                    mail_id: mail_id
                },
                dataType: 'json',
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
                    if (json.status == 1) {
                        layer.alert('审核通过，<b>' + mail_id + '号邮件</b> 已激活', {icon: 1}, function (index) {
                            layer.close(index);
                            jsonAudit();
                        });
                    }else if(json.status == 2){
                        layer.alert(json.msg, {icon: 2}, function (index) {
                            layer.close(index);
                        });
                    }else {
                        layer.alert(json.msg, {icon: 2}, function (index) {
                            layer.close(index);
                            jsonAudit();
                        });
                    }
                }
            });
        });
        return false;
    }).on('click', 'button[data-type="d"]', function () {
        var mail_id = $(this).parents('tr').find('td').eq(1).text();
        layer.alert('确认删除[' + mail_id + '号邮件]？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=914",
                data: {
                    mail_id: mail_id
                },
                success: function () {
                    layer.alert('删除成功', {icon: 1}, function (index) {
                        layer.close(index);
                        jsonAudit();
                    });
                }
            });
        });
        return false;
    }).on('click', 'button[data-type="u"]', function () {
        var mail_id = $(this).parents('tr').find('td').eq(1).text();
        var receiver_type = $(this).parents('tr').find('td').eq(3).find('span').attr('data-data-receiver_type');
        var receiver = $(this).parents('tr').find('td').eq(3).find('span').attr('data-data-receiver');
        var title = $(this).parents('tr').find('td').eq(4).text();
        var content = $(this).parents('tr').find('td').eq(5).text();
        var money = $(this).parents('tr').find('td').eq(6).find('span').attr('data-data-money');
        var item = $(this).parents('tr').find('td').eq(7).find('span').attr('data-data-item');
        var exp = $(this).parents('tr').find('td').eq(8).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '邮件修改',
            area: ['500px', '600px'],
            btn: ['修改', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">标题</span><input id="title" type="text" class="form-control" value="' +
            title + '"></div>' +
            '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content1"  rows="6"  class="form-control">' +
            content + '</textarea></div>' +
            '<div class="input-group"><span class="input-group-addon">角色类型</span>' +
            '<select id="receiver_type" class="input-group-addon" style="width: 150px;" >' +
            '<option value="角色名">角色名</option>' +
            '<option value="角色ID">角色ID</option>' +
            '</select></div>' +
            '<div class="input-group"><span class="input-group-addon">角色</span><input id="receiver" type="text" class="form-control" value="' +
            receiver + '"></div>' +
            '<div class="input-group"><span class="input-group-addon">货币</span><textarea id="money"  rows="2"  class="form-control">' +
            money + '</textarea></div>' +
            '<div class="input-group"><span class="input-group-addon">道具</span><textarea id="item"  rows="2"  class="form-control">' +
            item + '</textarea></div>' +
            '<div class="input-group"><span class="input-group-addon">经验</span><textarea id="exp"  rows="1"  class="form-control">' +
            exp + '</textarea></div>' +
            '</div>',
            success:function (index) {
                $("#receiver_type option[value='"+receiver_type+"']").attr("selected","selected");
            },
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=913',
                    data: {
                        mail_id: mail_id,
                        receiver_type :$('#receiver_type').val(),
                        receiver :$('#receiver').val(),
                        title: $('#title').val(),
                        content: $('#content1').val(),
                        money: $('#money').val(),
                        item: $('#item').val(),
                        exp: $('#exp').val()
                    },
                    success: function () {
                        layer.close(index);
                        layer.alert('修改成功', {icon: 1}, function (index1) {
                            layer.close(index1);
                            jsonAudit();
                        });
                    }
                });
            },
            cancel: function () {
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
    $("#jin_excel").on('click', function () {
        $.ajax({
            type: "post",
            url: location.href + '&jinIf=951',
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
        layer.alert('确认上传吗？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: 'post',
                url: location.href + '&jinIf=916',
                data: formData,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                success:function (json) {
                    if(json.status==1){
                        layer.alert(json.msg, {icon: 1}, function (index1) {
                            layer.close(index1);
                            $("#file").val('');
                            jsonAudit();
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
<?php echo '</script'; ?>
>
<?php }
}
