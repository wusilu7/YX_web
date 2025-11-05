<?php
/* Smarty version 3.1.30, created on 2024-04-23 12:48:09
  from "/lnmp/www/app/Admin/View/test/showCharge.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66273d89a8a7b6_98005333',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1ecccc8e98bad158663cff5cb399a073211e3f4e' => 
    array (
      0 => '/lnmp/www/app/Admin/View/test/showCharge.html',
      1 => 1678771403,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_66273d89a8a7b6_98005333 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>查单</span></div>
<div class="alert alert-info">
    <div id="group_server_2"> </div>
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
        <label>筛选：</label>
        <input id="orderstr_id" type="text" class="form-control jin-search-input" placeholder="平台订单号">
        <input id="char_guid" type="text" class="form-control jin-search-input" placeholder="角色id">
        <input id="char_name" type="text" class="form-control jin-search-input" placeholder="角色名">
        <input id="order_id" type="text" class="form-control jin-search-input" placeholder="游戏订单号">
        <input id="tran_id" type="text" class="form-control jin-search-input" placeholder="事物id">
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    </div>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>日期</th>
            <th>平台订单号</th>
            <th>角色id</th>
            <th>角色名</th>
            <th>账号</th>
            <th>ip</th>
            <th>游戏订单号</th>
            <th>事物id</th>
            <th>设备类型</th>
            <th>主步骤</th>
            <th>子步骤</th>
            <th>充值id</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ① 当日数据实时更新；
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 type="text/javascript">
    gsSelect('#group', '#server', '');
    calendar('month', '#time_start', '#time_end');
    var url = location.href + "&jinIf=912";
    var arr = ['log_time', 'orderstr_id', 'char_guid','char_name', 'account', 'ip', 'order_id', 'tran_id', 'base_device_type', 'step', 'substep','charge_id'];
    var id = ["#content", "#page"];
    var data = {};
    function getCharge() {
        data.page        = 1;
        data.si          = $("#server").val();
        data.orderstr_id = $("#orderstr_id").val();
        data.char_guid   = $("#char_guid").val();
        data.tran_id    = $("#tran_id").val();
        data.char_name   = $("#char_name").val();
        data.order_id    = $("#order_id").val();
        data.time_start  = $('#time_start').val();//查询开始时间
        data.time_end    = $('#time_end').val();//查询结束时间
        data.cross      = $('#ischeck').is(':checked') ? $('#ischeck').val() : '';
        tableList(url, data, id, arr);
    }
    // 普通查询
    $("#jin_search").click(function () {
        getCharge();
    });
<?php echo '</script'; ?>
>
<?php }
}
