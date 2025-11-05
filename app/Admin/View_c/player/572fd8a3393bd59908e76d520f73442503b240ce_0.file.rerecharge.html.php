<?php
/* Smarty version 3.1.30, created on 2024-08-08 16:56:33
  from "D:\pro\WebSiteYiXing\app\Admin\View\player\rerecharge.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66b488419053c4_91811409',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '572fd8a3393bd59908e76d520f73442503b240ce' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\player\\rerecharge.html',
      1 => 1678771402,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_66b488419053c4_91811409 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<div class="jin-search-div">
    <form id="uploadForm" enctype="multipart/form-data" style="display: inline-block;">
        <input id="file" type="file" name="file"/>
    </form>
    <button id="jin_excel" class='btn btn-success'>Excel模板下载</button>
    <hr>
    <button id="upload" class='btn btn-success'>导入数据</button>
    <button id="allAudit" class='btn btn-success'>一键补单(只针对Excel导入的)</button>
</div>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
      		<th>编号</th>
            <th>服务器</th>
            <th>申请人</th>
            <th>充值对象</th>
            <th>订单号</th>
            <th>充值金额</th>
            <th>审核人</th>
            <th>充值时间</th>
            <th>类型</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>

<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
    
    $(function () {
        jsonAudit();
    });

    //刷新数据
    function jsonAudit() {
        var url = location.href + "&jinIf=912";
	    var id = "#content";
	    var data = {};
	    var btn = [
	            "<div class='btn-group btn-group-sm'>" +
	            "<button data-type='u' class='btn btn-primary'>修改</button>" +
	            "<button data-type='a' class='btn btn-success'>审核通过</button>" +
	            "<button data-type='d' class='btn btn-danger'>删除</button>" +
	            "</div>"
	        ];
	    var arr = ['id', 'si', 'apply_name', 'charge_role', 'order', 'charge_money','apply_name1', 'charge_time','type', btn];

	    noPageContentList(url, data, id, arr);
    }

    //充值审核
    $('#content').on('click', 'button[data-type="a"]', function () {
        var id = $(this).parents('tr').find('td').eq(0).text();
        var si = $(this).parents('tr').find('td').eq(1).text();
        var charge_role = $(this).parents('tr').find('td').eq(3).text();
        var order = $(this).parents('tr').find('td').eq(4).text();
        var charge_money = $(this).parents('tr').find('td').eq(5).text();
	        $.ajax({
	            type: "POST",
	            url: location.href + "&jinIf=913",
	            data: {
	                id: id,
                    si:si,
                    charge_role:charge_role,
                    order:order,
                    charge_money:charge_money
	            },
	            dataType: 'json',
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']
                    });
                },
	            success: function (json) {
                    layer.closeAll('loading');
                    if (json == true) {
                        jsonAudit();
                    } else if (json == -1) {
                        layer.alert('soap发送失败');
                    } else {
                        layer.alert('审核失败');
                    }
	            },
	            error: function (msg) {
	                layer.closeAll('loading');
	                layer.msg('数据获取失败，请勿频繁刷新');
	            }
	        });
    }).on('click', 'button[data-type="d"]', function () {
        var id = $(this).parents('tr').find('td').eq(0).text();
        layer.alert('确认删除[' + id + '号充值]？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=914",
                data: {
                    id: id
                },
                success: function () {
                    layer.alert('删除成功', {icon: 1}, function (index) {
                        layer.close(index);
                        jsonAudit();
                    });
                }
            });
        });
    }).on('click', 'button[data-type="u"]', function () {
    	var id = $(this).parents('tr').find('td').eq(0).text();
        var content1 = $(this).parents('tr').find('td').eq(2).text();
        var content2 = $(this).parents('tr').find('td').eq(3).text();
        var content3 = $(this).parents('tr').find('td').eq(4).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '充值修改',
            area: ['500px', '400px'],
            btn: ['修改', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">角色ID</span><input id="content1" type="text" class="form-control" value="' +
            content1 + '"></div>' +
            '<div class="input-group"><span class="input-group-addon">角色名</span><input id="content2"  rows="10"  class="form-control" value="' +
            content2 + '"></div>' +
            '<div class="input-group"><span class="input-group-addon">充值金额</span><input id="content3"  rows="10"  class="form-control" value="' +
            content3 + '"></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=915',
                    data: {
                    	id:id,
                        content1: $('#content1').val(),
                        content2: $('#content2').val(),
                        content3: $('#content3').val()
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
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']
                        });
                    },
                    success:function (json) {
                        layer.closeAll('loading');
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

    $("#allAudit").click(function () {
        layer.alert('确认一键补单吗？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: 'post',
                url: location.href + '&jinIf=917',
                dataType: 'json',
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']
                    });
                },
                success:function (json) {
                    layer.closeAll('loading');
                    if(json==1){
                        layer.alert('一键补单成功', {icon: 1}, function (index1) {
                            layer.close(index1);
                            jsonAudit();
                        });
                    } else if(json==2){
                        layer.alert('无数据！', {icon: 2}, function (index1) {
                            layer.close(index1);
                        });
                    }else {
                        layer.alert('部分失败，请手动补单！！', {icon: 2}, function (index1) {
                            layer.close(index1);
                            jsonAudit();
                        });
                    }
                }
            })
        });
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
<?php echo '</script'; ?>
>
<?php }
}
