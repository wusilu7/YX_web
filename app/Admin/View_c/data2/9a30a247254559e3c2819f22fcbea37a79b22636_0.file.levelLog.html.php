<?php
/* Smarty version 3.1.30, created on 2023-04-14 19:51:29
  from "/lnmp/www/app/Admin/View/data2/levelLog.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_64393e418db0e2_41433518',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9a30a247254559e3c2819f22fcbea37a79b22636' => 
    array (
      0 => '/lnmp/www/app/Admin/View/data2/levelLog.html',
      1 => 1678771398,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_64393e418db0e2_41433518 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<link href="<?php echo CSS;?>
jin/3.03.selectdistribution.css" rel="stylesheet">
<div class="jin-content-title"><span>玩家升级日志</span></div>
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
        <label for="player_name">筛选：</label>
        <input id="player_name" type="text" class="form-control jin-search-input" placeholder="角色名">
        <input id="player_num" type="text" class="form-control jin-search-input" placeholder="帐号">
        <input id="player_id" type="text" class="form-control jin-search-input" placeholder="角色ID">
        <input size="16" type="checkbox" id="ischeck1" value="1">
        <label for="ischeck1" style="margin-left: 0px;">查询合服前</label>
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
        <!--<a id="server_summary" class="btn btn-success">服务器汇总</a>-->
        <!--<a id="group_summary" class="btn btn-success">渠道汇总</a>-->
    </div>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>帐号</th>
            <th>角色ID</th>
            <th>角色名</th>
            <th>等级</th>
            <th>升级时间</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ① 查询条件可以按需自由组合，不填代表查询所有；
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
>
    gsSelect('#group', '#server', '#platform');
    calendar('minute', '#time_start', '#time_end');
    var url = location.href + "&jinIf=912";
    var arr = ['account', 'char_guid', 'char_name', 'player_level', 'log_time'];
    var id = ["#content", "#page"];
    var data = {page: 1};
    function getLog() {
        var time_start = $('#time_start').val();
        var time_end   = $('#time_end').val();
        // 检测汇总时间是否过长
        checkSummaryTime(data.check_type, time_start, time_end, 30, 15);

        data.time_start  = time_start;//查询开始时间;
        data.time_end    = time_end;//查询结束时间
        data.group       = $('#group').val();
        data.pi          = $('#platform').val();
        data.si          = $('#server').val();
        data.player_name = $("#player_name").val();
        data.player_id   = $("#player_id").val();
        data.player_num  = $("#player_num").val();
        data.before      = $('#ischeck1').is(':checked') ? $('#ischeck1').val() : '';
        tableList(url, data, id, arr);
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        data.check_type = 912;
        getLog();
    });
    // 服务器汇总
    $("#server_summary").on('click', function () {
        data.check_type = 998;
        getLog();
    });
    // 渠道汇总
    $("#group_summary").on('click', function () {
        data.check_type = 999;
        giCollect(getLog);
    });
<?php echo '</script'; ?>
>
<?php }
}
