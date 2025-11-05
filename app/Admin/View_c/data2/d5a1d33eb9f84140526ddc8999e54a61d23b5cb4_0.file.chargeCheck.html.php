<?php
/* Smarty version 3.1.30, created on 2024-01-25 13:18:30
  from "C:\Users\Administrator\Desktop\pro\WebSiteYiXing\app\Admin\View\data2\chargeCheck.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65b1ef2619dfc9_74857313',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd5a1d33eb9f84140526ddc8999e54a61d23b5cb4' => 
    array (
      0 => 'C:\\Users\\Administrator\\Desktop\\pro\\WebSiteYiXing\\app\\Admin\\View\\data2\\chargeCheck.html',
      1 => 1704262932,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_65b1ef2619dfc9_74857313 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>充值查询</span></div>
<div class="alert alert-info">
    <div id="group_server"></div>
</div>
<!--查询div-->
<hr/>
<div class="jin-search-div">
    <div>
        <label for="time_start">日期：</label>
        <input size="16" type="text" id="time_start" class="form-control jin-datetime"
               placeholder="开始日期">
        -
        <input size="16" type="text" id="time_end" class="form-control jin-datetime"
               placeholder="结束日期">
    </div>
    <div>
        <label for="order_id">筛选：</label>
        <input id="order_id" type="text" class="form-control jin-search-input" placeholder="订单号">
        <input id="cp_orderid" type="text" class="form-control jin-search-input" placeholder="cp_orderid">
        <input id="char" type="text" class="form-control jin-search-input" placeholder="角色ID">
        <label for="order_id">充值类型：</label>
        <select id="gift_type" style="padding: 8px;">
            <option value="1">全部</option>
            <option value="2">正常</option>
            <option value="3">特权卡</option>
            <option value="4">付费礼包</option>
            <option value="5">时装</option>
            <option value="6">精准礼包</option>
            <option value="7">部落游商</option>
            <option value="8">基金</option>
            <option value="9">恐龙试炼</option>
            <option value="10">超值回馈</option>
        </select>
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
         <a id="server_summary" class="btn btn-success">服务器汇总</a>
        <!-- <a id="group_summary" class="btn btn-success">渠道汇总</a> -->
        <a id="jin_excel" class="btn btn-danger">保存到Excel</a>
        <a id="all_fail" class="btn btn-primary">所有充值失败</a>
        <label for="add_num">订单数：</label>
        <span id="add_num"></span>
        <label for="subtract_num">总金额：</label>
        <span id="subtract_num"></span>
    </div>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr id="thead">
            <th>充值订单号</th>
            <th>金额</th>
            <th>角色ID</th>
            <th>角色名</th>
            <th>充值时等级</th>
            <th>区服</th>
            <th>充值时间</th>
            <th>充值状态</th>
            <th>CP订单号</th>
            <th>充值类型</th>
            <th>平台类型</th>
            <th>其他</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ①查询条件可以按需自由组合；
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
>
    var url = location.href + "&jinIf=912";
    var arr = ['order_id', 'fee', 'char', 'char_name','level', 'server', 'pay_time','result','cp_orderid','ss_type','devicetype','pay_param'];
    var id = ["#content", "#page", "#add_num", "#subtract_num"];
    var data = {};
    $(function () {
        gsSelect('#group', '#server', '#platform');
        calendar('month', '#time_start', '#time_end');
    });
    function getCharge() {
        if(eval('<?php echo $_smarty_tpl->tpl_vars['wbGroup']->value;?>
').indexOf($("#group").val())>=0){ //当前所选渠道是英文渠道，金额显示外币
            arr[1] = 'fee1';
            $("#thead").find('th').eq(1).html("金额");
        }else {
            arr[1] = 'fee';
            $("#thead").find('th').eq(1).html("金额");
        }
        var time_start = $('#time_start').val();
        var time_end   = $('#time_end').val();

        data.page       = 1;
        data.time_start = time_start;//查询开始时间;
        data.time_end   = time_end;//查询结束时间
        data.order_id   = $("#order_id").val();
        data.cp_orderid   = $("#cp_orderid").val();
        data.char       = $('#char').val();
        data.gift_type       = $('#gift_type').val();
        data.group      = $('#group').val();
        data.si         = $('#server').val();
        data.pi         = $('#platform').val();

        tableList(url, data, id, arr,3);
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        data.check_type = 912;
        data.show_allfail = 0;
        getCharge();
    });

    // 普通查询
    $("#all_fail").on('click', function () {
        data.check_type = 912;
        data.show_allfail = 1;
        getCharge();
    });
    // 补发操作
    function fixpay(e) {
        var text = $(e).text();  
        var payorderid=e.getAttribute("pay_orderid");
        var billtype=e.getAttribute("bill_type");
        if(text == '成功'){
            layer.msg('该订单已成功发货');
            return false;
        }
        // alert(e.getElementById("orderid").value);
        $.ajax({
            type: "post",
            url: location.href + '&jinIf=914',
            data: {order_id:payorderid,bill_type:billtype},
            dataType: "json",
            success: function (output) {
                if(output.status == 1){
                    layer.msg('补发成功');
                    $(e).text('成功');
                }else if(output.status == 0){
                    layer.msg('补发失败');

                }else if(output.status == 3){
                    layer.msg('没有找到该订单');
                }else{
                    layer.msg('无需补发');
                }
            },
            error: function () {
                layer.msg('系统繁忙');
            }
        }); 
    }

    // 服务器汇总
    $("#server_summary").on('click', function () {
        data.check_type = 998;
        getCharge();
    });
    // 渠道汇总
    $("#group_summary").on('click', function () {
        data.check_type = 999;
        getCharge();
    });

    // 导出Excel
    $("#jin_excel").on('click', function () {
        data.page       = 'excel';
        data.time_start = $('#time_start').val();//查询开始时间;
        data.time_end   = $('#time_end').val();//查询结束时间
        data.order_id   = $("#order_id").val();
        data.char       = $('#char').val();
        data.group      = $('#group').val();
        data.si         = $('#server').val();
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
<?php echo '</script'; ?>
>
<?php }
}
