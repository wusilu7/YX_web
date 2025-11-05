<?php
/* Smarty version 3.1.30, created on 2024-04-01 18:38:13
  from "D:\pro\WebSiteYiXing\app\Admin\View\data2\moneyConsume.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_660a8e95158212_00526661',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '96ed1567663379224d03098f6cf95c75c70b34e4' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\data2\\moneyConsume.html',
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
function content_660a8e95158212_00526661 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.17.moneyConsume.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>货币消耗统计</span></div>
<div class="alert alert-info">
    <div id="group_server"></div>
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

    <label for="money_type">货币类型：</label>
    <select id="money_type">
        <option value=""></option>
    </select>
    <input size="16" type="checkbox" id="ischeck1" value="1">
    <label for="ischeck1" style="margin-left: 0px;">查询合服前</label>
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    <a id="server_summary" class="btn btn-success">服务器汇总</a>
    <!--<a id="group_summary" class="btn btn-success">渠道汇总</a>-->
    <label for="subtract_num">总消费总额：</label>
    <span id="subtract_num"></span>
</div>
<!--<div class="jin-explain">-->
    <!--<b>说明</b>：-->
    <!--<div>-->
        <!--①由于数据量比较大，服务器汇总限制为最大查询15天，渠道汇总限制为最大查询7天；-->
    <!--</div>-->
<!--</div>-->
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>消费类型</th>
            <th>消费人数</th>
            <th>消费次数</th>
            <th>消费总额</th>
            <th>占比</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    moneySelect();
    gsSelect('#group', '#server', '#platform');
    calendar('minute', '#time_start', '#time_end');
    var data = {page: 1};
    function getConsume() {
        var time_start = $('#time_start').val();
        var time_end   = $('#time_end').val();
        $check = checkSummaryTime(time_start, time_end, 30, 15);
        if ($check === false) {
            return false;
        }

        data.time_start = time_start;//查询开始时间;
        data.time_end   = time_end;//查询结束时间
        data.money_type = $("#money_type").val();
        data.group      = $('#group').val();
        data.pi         = $('#platform').val();
        data.si         = $('#server').val();
        data.before      = $('#ischeck1').is(':checked') ? $('#ischeck1').val() : '';
        $.ajax({
            type: "POST",
            url: location + "&jinIf=912",
            data: data,
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (json) {
                layer.closeAll('loading');
                var c = '';
                for (var i = 0; i < json.length - 1; i++) {
                    var per = (json[i]['consume_total'] / json[json.length - 1] * 100).toFixed(2);
                    if (isNaN(per)) {//如果是非数字，避免出现NaN的情况
                        per = 0;
                    }
                    c +=
                        '<tr>' +
                        '<td>' + json[i]['type'] + '</td>' +
                        '<td>' + json[i]['char_num'] + '</td>' +
                        '<td>' + json[i]['consume_num'] + '</td>' +
                        '<td>' + json[i]['consume_total'] + '</td>' +
                        '<td>' + per + '%' + '</td>' +
                        '</tr>';
                }
                $('#content').html(c);
                $('#subtract_num').html(json[json.length - 1]);
            },
            error: function () {
                layer.closeAll('loading');
                layer.msg('数据获取失败，请勿频繁刷新');
            }
        });
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        data.check_type = 912;
        getConsume();
    });
    // 服务器汇总
    $("#server_summary").on('click', function () {
        data.check_type = 998;
        getConsume();
    });
    // 渠道汇总
    $("#group_summary").on('click', function () {
        data.check_type = 999;
        giCollect(getConsume);
    });
<?php echo '</script'; ?>
>
<?php }
}
