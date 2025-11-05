<?php
/* Smarty version 3.1.30, created on 2023-05-23 14:04:24
  from "/lnmp/www/app/Admin/View/data1/selectDaily.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_646c57686a7107_50486597',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e42d53b5ddb87460890537bb20fc0501ec45f235' => 
    array (
      0 => '/lnmp/www/app/Admin/View/data1/selectDaily.html',
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
function content_646c57686a7107_50486597 (Smarty_Internal_Template $_smarty_tpl) {
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
<div class="jin-content-title"><span>游戏日报(按单个服统计)</span></div>
<div class="alert alert-info">
    <div class="form-group" id="group_server_5"></div>
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
    <!-- <a id="server_summary" class="btn btn-success">服务器汇总</a> -->
    <!--<a id="group_summary" class="btn btn-success">渠道汇总</a>-->
    <!--<a id="jin_excel" class="btn btn-danger">保存到Excel</a>-->
    <input size="16" type="checkbox" id="ischeck" value="1">
    <label  for="ischeck" style="margin-left: 0px;">查询今日实时更新</label>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
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
    <div>
        ② 括号上的数字为总数，括号内的三个数字：<b>左</b>为老玩家数据，<b>中</b>为老玩家(今日首充)数据，<b>右</b>为新玩家数据；
    </div>
    <div>
        ③ <b>DAU</b>：Daily Active User 日活跃用户(设备统计)；<b>APA</b>：Active Payment Account 充值人数；
        <b>PUR</b>：Pay User Rate 付费比率，通过 <u>APA/DAU</u> 计算得出；<b>ARPU</b>：Average Revenue Per User 活跃用户平均付费值，通过
        <u>总充值金额/DAU</u> 计算得出；
        <b>ARPPU</b>：Average Revenue Per Paying User 付费用户平均付费值，通过 <u>总充值金额/APA</u> 计算得出；
    </div>
    <!--<div>-->
        <!--③<b>新APA</b>：首充的(以前注册的在今天首充也算)<br>-->
    <!--</div>-->
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
        data.si         = $("#s").val();
        data.gi         = $("#g").val();
        data.many       = 'yes';
        data.time_start = $('#time_start').val();//查询开始时间
        data.time_end   = $('#time_end').val();//查询结束时间
        data.ischeck       = $('#ischeck').is(':checked') ? $('#ischeck').val() : 0;
        $.cookie('cookie_g', data.group, {expires: 30});
        $.cookie('cookie_s', data.si, {expires: 30});
        tableList(url, data, id, arr);
    }

    // 普通查询
    $("#jin_search").click(function () {
        data.check_type = 912;  // 普通查询
        getDaily();
    });

    // 服务器汇总
    $("#server_summary").click(function () {
        data.check_type = 998;  // 服务器汇总
        getDaily();
    });

    // 渠道汇总
    $("#group_summary").click(function () {
        data.check_type = 999;  // 渠道汇总
        giCollect(getDaily);
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
<?php echo '</script'; ?>
><?php }
}
