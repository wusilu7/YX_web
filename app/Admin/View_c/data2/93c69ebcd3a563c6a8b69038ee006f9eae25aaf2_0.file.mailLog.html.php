<?php
/* Smarty version 3.1.30, created on 2024-04-24 17:51:54
  from "D:\pro\WebSiteYiXing\app\Admin\View\data2\mailLog.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6628d63ae04f85_47250219',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '93c69ebcd3a563c6a8b69038ee006f9eae25aaf2' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\data2\\mailLog.html',
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
function content_6628d63ae04f85_47250219 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>邮件日志</span></div>
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
        <input id="char_guid" type="text" class="form-control jin-search-input" placeholder="角色id">
        <input id="char_name" type="text" class="form-control jin-search-input" placeholder="角色名">
        <input id="mail_id" type="text" class="form-control jin-search-input" placeholder="邮件ID">
        opt类型：<select  id="opt">
            <option value="999">全部</option>
            <option value="0">创建</option>
            <option value="1">删除</option>
            <option value="2">领取</option>
        </select>
        <input size="16" type="checkbox" id="ischeck1" value="1">
        <label for="ischeck1" style="margin-left: 0px;">查询合服前</label>
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    </div>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>邮件ID</th>
            <th>物品版本</th>
            <th>录入时间</th>
            <th>角色ID</th>
            <th>物品1</th>
            <th>物品2</th>
            <th>物品3</th>
            <th>物品4</th>
            <th>物品5</th>
            <th>货币1</th>
            <th>货币2</th>
            <th>经验</th>
            <th>opt</th>
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
    var arr = ['mail_id','version', 'log_time', 'receiver_guid', 'item_id1', 'item_id2', 'item_id3', 'item_id4', 'item_id5', 'currency_type1', 'currency_type2','exp','opt'];
    var id = ["#content", "#page"];
    var data = {page: 1};
    $(function () {
        gsSelect('#group', '#server', '#platform');
        calendar('minute', '#time_start', '#time_end');
    });
    function getCharge() {
        var time_start  = $('#time_start').val();
        var time_end    = $('#time_end').val();

        data.page       = 1;
        data.time_start = time_start;//查询开始时间;
        data.time_end   = time_end;//查询结束时间

        data.char_guid  = $('#char_guid').val();
        data.char_name  = $('#char_name').val();
        data.mail_id  = $('#mail_id').val();
        data.opt  = $('#opt').val();
        data.si         = $('#server').val();
        data.before      = $('#ischeck1').is(':checked') ? $('#ischeck1').val() : '';
        tableList(url, data, id, arr);
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        data.check_type = 912;
        getCharge();
    });
<?php echo '</script'; ?>
>
<?php }
}
