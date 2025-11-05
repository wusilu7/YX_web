<?php
/* Smarty version 3.1.30, created on 2023-05-25 15:55:31
  from "/lnmp/www/app/Admin/View/data1/selectDaily1.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_646f14737440c6_05278351',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '0f639344a2e8c3e0ac184de0ebdbe8da88a6360b' => 
    array (
      0 => '/lnmp/www/app/Admin/View/data1/selectDaily1.html',
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
function content_646f14737440c6_05278351 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->

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
<div class="jin-content-title"><span>游戏日报(按整个渠道统计)</span></div>
<div class="alert alert-info">
    <div class="form-group" id="group_server_7"></div>
</div>
<!--查询div-->
<hr/>
<div class="jin-search-div">
    <label for="time_start">日期：</label>
    <input size="16" type="text" id="time_start" class="form-control jin-datetime"
           placeholder="开始日期">
    -
    <input size="16" type="text" id="time_end" class="form-control jin-datetime"
           placeholder="结束日期">
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    <input size="16" type="checkbox" id="ischeck" value="1">
    <label  for="ischeck" style="margin-left: 0px;">查询今日实时更新</label>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr id="thead">
            <th>日期</th>
            <th>新增设备</th>
            <!--<th>启动设备</th>-->
            <th>新增角色</th>
            <th>DAU</th>
            <th>APA</th>
            <th>充值次数</th>
            <th>充值金额</th>
            <th>PUR</th>
            <th>ARPU</th>
            <th>ARPPU</th>
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
    gsSelect3('#g', '#p', '#s');
    calendar('month', '#time_start', '#time_end');
    var myDate = new Date();
    var weekDay = ["星期天", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六"];
    Y = myDate.getFullYear() + '-';
    M = (myDate.getMonth()+1 < 10 ? '0'+(myDate.getMonth()+1) : myDate.getMonth()+1) + '-';
    D = myDate.getDate() < 10 ? '0'+myDate.getDate():myDate.getDate();
    stime = Y+M+D;
    var c_rate = function (json) {
        if (json.date == stime) {
            rate =json.date+'<br>(实时更新)';
        } else {
            if(json.date!='汇总'){
                var dt = new Date(Date.parse(json.date))
                rate = json.date+weekDay[dt.getDay()];
            }else{
                rate = json.date;
            }
        }
        return rate;
    };
    var url = location.href + "&jinIf=912";
    var arr = [c_rate, 'device', 'character', 'dau', 'apa', 'times', 'amount', 'pur', 'arpu', 'arppu'];
    var id = ["#content", "#page"];
    var data = {};
    function getDaily() {
        data.page       = 1;
        data.group      = $("#g").val();
        data.pi         = $('#p').val();
        data.time_start = $('#time_start').val();//查询开始时间
        data.time_end   = $('#time_end').val();//查询结束时间
        data.ischeck       = $('#ischeck').is(':checked') ? $('#ischeck').val() : 0;
        $.cookie('cookie_g', data.group, {expires: 30});
        tableList(url, data, id, arr);
    }

    // 普通查询
    $("#jin_search").click(function () {
        data.check_type = 912;  // 普通查询
        getDaily();
    });
<?php echo '</script'; ?>
><?php }
}
