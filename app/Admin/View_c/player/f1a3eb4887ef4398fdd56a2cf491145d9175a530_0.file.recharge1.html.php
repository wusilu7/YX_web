<?php
/* Smarty version 3.1.30, created on 2024-04-24 17:49:10
  from "D:\pro\WebSiteYiXing\app\Admin\View\player\recharge1.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6628d596127f96_44800256',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f1a3eb4887ef4398fdd56a2cf491145d9175a530' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\player\\recharge1.html',
      1 => 1704262933,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_6628d596127f96_44800256 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<style type="text/css">
    .center{
        display:inline-block
    }
    .chargemoney{
        margin-left: 50px;
        font-size: 17px;
    }
</style>
<div class="alert alert-info">
    <div id="group_server"></div>
</div>

<div class="form-horizontal col-sm-10 col-sm-offset-1">
    <div class="form-group">
        <select id="role_type">
            <option value="1">角色名</option>
            <option value="2">角色ID</option>
        </select>
        <input type="text" class="form-control" id="charge_role" required style="width:20%;display:inline-block">
        <span class="chargemoney">充值档位</span>
        <select id="charge_money">
            <option value=""></option>
        </select>
        <a id="send" class="btn btn-success center">充值</a>
        <button id="jin_excel" class='btn btn-success'>Excel模板下载</button>
        <form id="uploadForm" enctype="multipart/form-data" style="display: inline-block;">
            <input id="file" type="file" name="file"/>
        </form>
        <button id="upload" class='btn btn-success'>导入数据</button>
        <button id="acc_search" class="btn btn-primary">发放记录</button>
    </div>
</div>

<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
    gsSelect('#group', '#server', '#platform');
    $('#group').on('change', function () { //渠道改变的时候
        if(eval('<?php echo $_smarty_tpl->tpl_vars['wbGroup']->value;?>
').indexOf($("#group").val())>=0){
            $("#charge_money").html('');
            chargeMoneySelect1();
        }else {
            $("#charge_money").html('');
            chargeMoneySelect();
        }
    });

    var data = {};

    $("#send").on('click', function () {
        data.charge_money = $("#charge_money").val();
        data.role_type = $("#role_type").val();
        data.charge_role = $("#charge_role").val();
        data.group = $("#group").val();
        data.pi = $("#platform").val();
        data.si = $("#server").val();

        if (data.charge_money && data.charge_role) {
            $.ajax({
                type: "POST",
                url: location + "&jinIf=912",
                data: data,
                dataType: "json",

                success: function (json) {
                    if (json == 1) {
                        layer.alert('充值成功, 请等待审核');
                    } else if (json == 2) {
                        layer.alert('充值失败, 该服没有此游戏角色');
                    } else {
                        layer.alert('充值失败！');
                    }
                },
                error: function () {
                    layer.closeAll('loading');
                    layer.msg('充值失败！');
                }
            });
        } else {
            layer.alert('请完整填写角色名/角色ID , 充值档位!');
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

    $("#acc_search").on('click', function () {
        location.href += "&jinIf=919";
    })
<?php echo '</script'; ?>
><?php }
}
