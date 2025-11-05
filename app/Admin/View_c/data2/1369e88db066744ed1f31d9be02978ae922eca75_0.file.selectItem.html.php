<?php
/* Smarty version 3.1.30, created on 2024-04-24 18:01:33
  from "D:\pro\WebSiteYiXing\app\Admin\View\data2\selectItem.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6628d87d6b7440_76931806',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '1369e88db066744ed1f31d9be02978ae922eca75' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\data2\\selectItem.html',
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
function content_6628d87d6b7440_76931806 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>道具日志</span></div>
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
        <input size="16" type="checkbox" id="ischeck1" value="1">
        <label for="ischeck1" style="margin-left: 0px;">查询合服前</label>
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
        <a id="jin_excel" class="btn btn-danger">保存到Excel</a>
    </div>
    <div>
        <label for="player_name">筛选：</label>
        <input id="player_name" type="text" class="form-control jin-search-input" placeholder="角色名">
        <input id="player_id" type="text" class="form-control jin-search-input" placeholder="角色ID">
        <select id="item_id"  class="selectpicker"  data-live-search="true" data-actions-box="true" title="请选择道具"></select>
        <!--<input id="item_id" type="text" class="form-control jin-search-input" placeholder="道具ID">-->
        <!--<input id="opt" type="text" class="form-control jin-search-input" placeholder="变动类型（填数字）">-->
        <select  id="opt" class="form-control jin-search-input">
            <option value="">变动类型</option>
            <option value="0">增加</option>
            <option value="1">减少</option>
            <option value="2">更新</option>
            <option value="3">消耗</option>
            <option value="4">交换</option>
            <option value="5">合并</option>
        </select>
        <!--<input id="source" type="text" class="form-control jin-search-input" placeholder="来源（填数字）">-->
        <select id="source"  class="selectpicker"  data-live-search="true" data-actions-box="true" title="来源行为"></select>
        <input id="item_guid" type="text" class="form-control jin-search-input" placeholder="guid">
        <input id="trans_id" type="text" class="form-control jin-search-input" placeholder="trans_id">
        <!--<input id="new_item_id" type="text" class="form-control jin-search-input" placeholder="新道具ID">-->
        <!--<input id="old_item_id" type="text" class="form-control jin-search-input" placeholder="老道具ID">-->
        <!-- <a id="server_summary" class="btn btn-success">服务器汇总</a> -->
        <!-- <a id="group_summary" class="btn btn-success">渠道汇总</a> -->
    </div>
</div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ①查询条件可以按需自由组合；
    </div>
    <div>
        ②变动类型：0-增加,1-减少,2-更新,3-消耗,4-交换,5-合并
    </div>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>发生日期</th>
            <th>角色ID</th>
            <th>角色名</th>
            <th>trans_id</th>
            <th>变动类型</th>
            <th>来源</th>
            <th>原道具</th>
            <th>原guid</th>
            <th>原道具数量</th>
            <th>新道具</th>
            <th>新guid</th>
            <th>新道具数量</th>
            <th>变动数量</th>
            <th>详情</th>
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
    var old_item = function (json) {
        return json['old_item_id'] + '(' + json['old_item_name'] + ')';
    };
    var new_item = function (json) {
        return json['new_item_id'] + '(' + json['new_item_name'] + ')';
    };
    var all = function (json) {
        return '<a class="btn btn-info" data-info="' + JSON.stringify(json).replace(/\"/g, "").replace(/,/g, "<br/>") + '"><span class="glyphicon glyphicon-info-sign"></span></a>';
    };
    var arr = ['log_time', 'char_guid', 'char_name', 'trans_id','opt', 'source', old_item, 'old_item_guid', 'old_item_num', new_item, 'new_item_guid', 'new_item_num','item_num_change', all];
    var id = ["#content", "#page"];
    var data = {page: 1};
    $(function () {
        gsSelect('#group', '#server', '#platform');
        calendar('minute', '#time_start', '#time_end');
    });
    getItems(["#item_id"],'');
    getItemSource("#source");
    function getItem() {
        data.page = 1;
        var time_start = $('#time_start').val();
        var time_end   = $('#time_end').val();
        // 检测汇总时间是否过长
        //checkSummaryTime(data.check_type, time_start, time_end, 30, 15);

        data.time_start  = time_start;//查询开始时间;
        data.time_end    = time_end;//查询结束时间
        data.player_name = $("#player_name").val();
        data.player_id = $("#player_id").val();
        data.opt         = $("#opt").val();
        data.source      = $("#source").val();
        data.item_id     = $("#item_id").val();
        data.new_item_id     = $("#new_item_id").val();
        data.old_item_id     = $("#old_item_id").val();
        data.item_guid   = $("#item_guid").val();
        data.trans_id    = $("#trans_id").val();
        data.group       = $('#group').val();
        data.pi          = $('#platform').val();
        data.si          = $('#server').val();
        data.before      = $('#ischeck1').is(':checked') ? $('#ischeck1').val() : '';

        tableList(url, data, id, arr);
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        data.check_type = 912;
        getItem();
    });
    // 服务器汇总
    $("#server_summary").on('click', function () {
        data.check_type = 998;
        getItem();
    });
    // 渠道汇总
    $("#group_summary").on('click', function () {
        data.check_type = 999;
        getItem();
    });
    $("#jin_excel").on('click', function () {
        data.page = 'excel';
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
    $('#content').on('click', 'a[class="btn btn-info"]', function () {
        layer.alert($(this).data('info'));
    });
<?php echo '</script'; ?>
>
<?php }
}
