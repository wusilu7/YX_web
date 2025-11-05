<?php
/* Smarty version 3.1.30, created on 2024-04-07 11:31:13
  from "D:\pro\WebSiteYiXing\app\Admin\View\data2\selectCPOrder.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6612138157e5a2_98962742',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b489a0ce9e1285adc9169bd474ab8aec36d75f95' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\data2\\selectCPOrder.html',
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
function content_6612138157e5a2_98962742 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>cp订单查询</span></div>
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
        <label for="acc">筛选：</label>
        <input id="orderid" type="text" class="form-control jin-search-input" placeholder="orderid">
        <input id="acc" type="text" class="form-control jin-search-input" placeholder="账号ID">
        <input id="char" type="text" class="form-control jin-search-input" placeholder="角色ID">
        <input id="pack" type="text" class="form-control jin-search-input" placeholder="pack">
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
        <a id="jin_delete" class="btn btn-danger">删除未支付的(2天前)</a>
    </div>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr id="thead">
            <th>cp订单号</th>
            <th>金额</th>
            <th>账号ID</th>
            <th>角色ID</th>
            <th>code</th>
            <th>app</th>
            <th>res</th>
            <th>状态</th>
            <th>时间</th>
            <th>pack</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>

<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
>
    var url = location.href + "&jinIf=912";
    var arr = ['cp_orderid', 'fee', 'acc', 'char_id','code', 'app', 'res','status','create_time','pack'];
    var id = ["#content", "#page"];
    var data = {};
    $(function () {
        gsSelect('#group', '#server', '#platform');
        calendar('month', '#time_start', '#time_end');
    });
    function getCharge() {
        var time_start = $('#time_start').val();
        var time_end   = $('#time_end').val();
        data.page       = 1;
        data.time_start = time_start;//查询开始时间;
        data.time_end   = time_end;//查询结束时间
        data.acc        = $("#acc").val();
        data.orderid    = $("#orderid").val();
        data.pack       = $("#pack").val();
        data.char       = $('#char').val();
        data.group      = $('#group').val();
        data.si         = $('#server').val();
        data.pi         = $('#platform').val();
        tableList(url, data, id, arr);
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        getCharge();
    });
    $("#jin_delete").on('click', function () {
        layer.alert('确认删除？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function (index) {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=914",
                data:{
                    si:$('#server').val()
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
                    getCharge();
                }
            });
        });
    });


<?php echo '</script'; ?>
>
<?php }
}
