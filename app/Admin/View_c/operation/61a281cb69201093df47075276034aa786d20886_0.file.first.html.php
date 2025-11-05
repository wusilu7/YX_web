<?php
/* Smarty version 3.1.30, created on 2023-03-14 14:24:27
  from "/lnmp/www/app/Admin/View/operation/first.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6410131b738394_24781269',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '61a281cb69201093df47075276034aa786d20886' => 
    array (
      0 => '/lnmp/www/app/Admin/View/operation/first.html',
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
function content_6410131b738394_24781269 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 language="javascript" type="text/javascript" src="<?php echo JS;?>
WdatePicker.js"><?php echo '</script'; ?>
>
<link href="<?php echo CSS;?>
jin/3.20.gc.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<link href="<?php echo CSS;?>
jin/3.07.sa.css" rel="stylesheet">
<div class="jin-content-title"><span>设置活动时间</span></div>
<div class="form-horizontal col-sm-8 col-sm-offset-2">
    <div class="form-group">
        <label for="server_id" class="col-sm-2 control-label">服务器ID</label>
        <div class="col-sm-10">
            <input id="server_id" class="form-control" readonly/>
        </div>
    </div>
    <div class="form-group">
        <label for="server_name" class="col-sm-2 control-label">服务器名称</label>
        <div class="col-sm-10">
            <input id="server_name" class="form-control" readonly/>
        </div>
    </div>
    <div class="form-group">
        <label for="open_time" class="col-sm-2 control-label">设置开服时间</label>
        <div class="col-sm-10">
            <input id="open_time" class=" form-control" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd 00:00:00'})">
        </div>
    </div>
    <div class="form-group hide">
        <label for="open_time" class="col-sm-2 control-label">不设置招财猫(开服)</label>
        <div class="col-sm-10">
            <input size="16" type="checkbox" id="ischeck" value="1"  style="width:20px;height:20px;">
        </div>
    </div>
    <div class="form-group">
        <label for="open_time" class="col-sm-2 control-label">设置合服时间</label>
        <div class="col-sm-10">
            <input id="mergetime" class=" form-control" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd HH:mm:ss'})">
        </div>
    </div>
    <div class="form-group hide">
        <label for="open_time" class="col-sm-2 control-label">不设置招财猫(合服)</label>
        <div class="col-sm-10">
            <input size="16" type="checkbox" id="ischeck1" value="1"  style="width:20px;height:20px;">
        </div>
    </div>
    <div class="btn-group center" style="margin-bottom: 15px;">
        <button data-type="update_1" class="btn btn-success" style="width: 110px;">修改(开服)</button>

        <button data-type="update_3" class="btn btn-success" style="width: 110px; margin-left: 10px;">修改(合服)</button>
    </div>
    <hr />
    <div class="form-group">
        <label for="first_charge" class="col-sm-2 control-label">令牌老服新服时间切割</label>
        <div class="col-sm-10">
            <input id="first_charge" class=" form-control" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd HH:mm:ss'})">
        </div>
    </div>
    <div class="form-group">
        <label for="acc_money" class="col-sm-2 control-label">宠物老服新服时间切割</label>
        <div class="col-sm-10">
            <input id="acc_money" class=" form-control" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd HH:mm:ss'})">
        </div>
    </div>
    <div class="form-group">
        <label for="daily_acc_money" class="col-sm-2 control-label">悬赏任务重置</label>
        <div class="col-sm-10">
            <input id="daily_acc_money" class=" form-control" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd HH:mm:ss'})">
        </div>
    </div>
    <div class="form-group">
        <label for="cont_daily_acc_money" class="col-sm-2 control-label">湖中女神切割时间</label>
        <div class="col-sm-10">
            <input id="cont_daily_acc_money" class=" form-control" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd HH:mm:ss'})">
        </div>
    </div>
    <div class="form-group">
        <label for="vip_gift" class="col-sm-2 control-label">充值双倍珍珠重置</label>
        <div class="col-sm-10">
            <input id="vip_gift" class=" form-control" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd HH:mm:ss'})">
        </div>
    </div>
    <div class="form-group hide">
        <label for="vip_gift" class="col-sm-2 control-label">重置每日充值</label>
        <div class="col-sm-10">
            <input id="daily_costAcc_money" class=" form-control" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd HH:mm:ss'})">
        </div>
    </div>
    <div class="form-group hide">
        <label for="vip_gift" class="col-sm-2 control-label">重置消费累冲</label>
        <div class="col-sm-10">
            <input id="cont_daily_cost_acc_money" class=" form-control" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd HH:mm:ss'})">
        </div>
    </div>
    <div class="form-group hide">
        <label for="vip_gift" class="col-sm-2 control-label">重置1V1排名</label>
        <div class="col-sm-10">
            <input id="reset_1V1_data" class=" form-control" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd HH:mm:ss'})">
        </div>
    </div>
    <div class="form-group hide">
        <label for="vip_gift" class="col-sm-2 control-label">重置首冲奖励</label>
        <div class="col-sm-10">
            <input id="reset_charge_flag" class=" form-control" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd HH:mm:ss'})">
        </div>
    </div>
    <div class="btn-group center jin-sa-btn">
        <button data-type="update_2" class="btn  btn-success">修改</button>
        <button data-type="return" class="btn  btn-primary">返回</button>
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
    $(document).ready(getSa());
    function getSa() {
        $('#server_id').val(<?php echo GET('si');?>
);
        $.ajax({
            type: "post",
            url: location.href + "&jinIf=912",
            dataType: "json",
            data: {'si': $('#server_id').val()},
            success: function (json) {
                $('#open_time').val(json.open_time);
                $('#mergetime').val(json.mergetime);
                $('#server_name').val(json.group_name+'('+json.group_id+')--'+json.name);
                $('#first_charge').val(json.first_charge);
                $('#acc_money').val(json.acc_money);
                $('#daily_acc_money').val(json.daily_acc_money);
                $('#cont_daily_acc_money').val(json.cont_daily_acc_money);
                $('#vip_gift').val(json.vip_gift);
                $('#daily_costAcc_money').val(json.daily_costAcc_money);
                $('#cont_daily_cost_acc_money').val(json.cont_daily_cost_acc_money);
                $('#reset_1V1_data').val(json.reset_1V1_data);
                $('#reset_charge_flag').val(json.reset_charge_flag);
            }
        });
    }

    $('button[data-type="update_1"]').on('click', function () { //修改开服时间
        var ischeck = $('#ischeck').is(':checked') ? $('#ischeck').val() : '';
        layer.alert('确认修改开服时间？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=913",
                data: {
                    si: $('#server_id').val(),
                    open_time: $('#open_time').val(),
                    ischeck:ischeck
                },
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                dataType: "json",
                success: function (json) {
                    layer.closeAll('loading');
                    if (json.status == 1) {
                        layer.alert(json.msg, {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    } else {
                        layer.alert(json.msg, {icon: 2}, function (index) {
                            layer.close(index);
                        });
                    }
                }
            });
        });
    });

    $('button[data-type="update_3"]').on('click', function () { //修改开服时间
        var ischeck1 = $('#ischeck1').is(':checked') ? $('#ischeck1').val() : '';
        layer.alert('确认修改合服时间？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=915",
                data: {
                    si: $('#server_id').val(),
                    mergetime: $('#mergetime').val(),
                    ischeck1:ischeck1
                },
                dataType: "json",
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
                    if (json.status == 1) {
                        layer.alert(json.msg, {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    } else {
                        layer.alert(json.msg, {icon: 2}, function (index) {
                            layer.close(index);
                        });
                    }
                }
            });
        });
    });

    $('button[data-type="update_2"]').on('click', function () { //修改活动时间
        var first_charge = $('#first_charge').val();
        var acc_money = $('#acc_money').val();
        var daily_acc_money = $('#daily_acc_money').val();
        var cont_daily_acc_money = $('#cont_daily_acc_money').val();
        var vip_gift = $('#vip_gift').val();
        var daily_costAcc_money = $('#daily_costAcc_money').val();
        var cont_daily_cost_acc_money = $('#cont_daily_cost_acc_money').val();
        var reset_1V1_data = $('#reset_1V1_data').val();
        var reset_charge_flag = $('#reset_charge_flag').val();
        layer.alert('确认修改活动时间？', {icon: 0, shadeClose: true, btn: ['确定', '取消'],area :['400px']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=914",
                data: {
                    si: $('#server_id').val(),
                    first_charge: $('#first_charge').val(),
                    acc_money: $('#acc_money').val(),
                    daily_acc_money: $('#daily_acc_money').val(),
                    cont_daily_acc_money: $('#cont_daily_acc_money').val(),
                    vip_gift: $('#vip_gift').val(),
                    daily_costAcc_money: $('#daily_costAcc_money').val(),
                    cont_daily_cost_acc_money: $('#cont_daily_cost_acc_money').val(),
                    reset_1V1_data: $('#reset_1V1_data').val(),
                    reset_charge_flag: $('#reset_charge_flag').val()
                },
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                dataType: "json",
                success: function (json) {
                    layer.closeAll('loading');
                    if (json.status == 1) {
                        layer.alert(json.msg, {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    } else {
                        layer.alert(json.msg, {icon: 2}, function (index) {
                            layer.close(index);
                        });
                    }
                }
            });
        });

    });

    $('button[data-type="return"]').on('click', function () {
        location.href = '?p=Admin&c=Operation&a=server';
    });
<?php echo '</script'; ?>
>
<?php }
}
