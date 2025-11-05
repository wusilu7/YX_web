<?php
/* Smarty version 3.1.30, created on 2023-04-05 11:26:09
  from "/lnmp/www/app/Admin/View/data2/selectMoney.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_642cea519e6816_86769163',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '95beb3c59ed001234b81d8970e6f0465eb050582' => 
    array (
      0 => '/lnmp/www/app/Admin/View/data2/selectMoney.html',
      1 => 1678771399,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_642cea519e6816_86769163 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.04.selectmoney.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>货币日志</span></div>
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
        <label for="money_type">货币类型：</label>
        <div  style="width: 15%; display: inline-block;">
            <select id="money_type" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>
        </div>
        <label for="add_num">货币总产出：</label>
        <span id="add_num"></span>
        <label for="subtract_num">货币总消耗：</label>
        <span id="subtract_num"></span>
        <input size="16" type="checkbox" id="ischeck1" value="1">
        <label for="ischeck1" style="margin-left: 0px;">查询合服前</label>
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
        <a id="jin_excel" class="btn btn-danger">保存到Excel</a>
    </div>
    <div>
        <label>筛选：</label>
        <input id="char" type="text" class="form-control jin-search-input" placeholder="账号名">
        <input id="char_id" type="text" class="form-control jin-search-input" placeholder="角色ID">
        <input id="char_name" type="text" class="form-control jin-search-input" placeholder="角色名">
        <input id="trans_id" type="text" class="form-control jin-search-input" placeholder="trans_id">
        <!--<input id="currency_opt" type="text" class="form-control jin-search-input" placeholder="行为">-->
        <select id="currency_opt"  class="selectpicker"  data-live-search="true" data-actions-box="true" title="货币行为"></select>
        <!--<input id="opt" type="text" class="form-control jin-search-input" placeholder="类型">-->
        <select  id="opt" class="form-control jin-search-input">
            <option value="">货币类型</option>
            <option value="0">增加</option>
            <option value="1">减少</option>
        </select>
        <input id="real_amount" type="text" class="form-control jin-search-input" placeholder="变动数量">
        <!--<a id="server_summary" class="btn btn-success">服务器汇总</a>-->
        <!--<a id="group_summary" class="btn btn-success">渠道汇总</a>-->
        <!--<a id="jin_excel" class="btn btn-danger">保存到Excel</a>-->
    </div>
</div>
<div class="jin-explain">
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>日期</th>
            <th>帐号名</th>
            <th>角色ID</th>
            <th>角色名</th>
            <th>trans_id</th>
            <th>行为</th>
            <th>类型</th>
            <th>总数量</th>
            <th>变动数量</th>
            <th>剩余数量</th>
            <th>货币类型</th>
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
    var all = function (json) {
        return '<a class="btn btn-info" data-info="' + JSON.stringify(json).replace(/\"/g, "").replace(/,/g, "<br/>") + '"><span class="glyphicon glyphicon-info-sign"></span></a>';
    };
    var trans_id = function (json) {
        return '<button data-type="trans_id" class="btn btn-sm btn-primary">'+json['trans_id']+'</button>'
    };

    var arr = ['log_time', 'account', 'char_guid', 'char_name', trans_id, 'currency_opt', 'opt','total_amount', 'real_amount', 'balance','currency_type', all];
    var id = ["#content", "#page", "#add_num", "#subtract_num"];
    var data = {page: 1};
    gsSelect('#group', '#server');
    calendar('minute', '#time_start', '#time_end');
    moneySelects();
    $(function () {
        getCurrencyOpt("#currency_opt");
    });

    //设置一个函数来专门调用tableList方法
    function getQuest() {
        var time_start = $('#time_start').val();
        var time_end   = $('#time_end').val();
        // 检测汇总时间是否过长
        checkSummaryTime(data.check_type, time_start, time_end, 15, 7);
        data.page=1;

        data.time_start = time_start;//查询开始时间;
        data.time_end   = time_end;//查询结束时间
        data.char       = $("#char").val();
        data.char_id    = $("#char_id").val();
        data.char_name  = $("#char_name").val();
        data.trans_id   = $("#trans_id").val();
        data.money_type = $("#money_type").val();
        data.currency_opt = $("#currency_opt").val();
        data.opt = $("#opt").val();
        data.real_amount = $("#real_amount").val();
        data.group      = $('#group').val();
        data.pi         = $('#platform').val();
        data.si         = $('#server').val();
        data.before      = $('#ischeck1').is(':checked') ? $('#ischeck1').val() : '';

        tableList(url, data, id, arr, 3); //这个是自己封装的方法在jin-tableList.js文件中
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        data.check_type = 912;
        getQuest();
    });

    $("#jin_excel").on('click', function () {
        data.page = 'excel';
        data.time_start = $('#time_start').val();//查询开始时间;
        data.time_end = $('#time_end').val();//查询结束时间
        data.char = $("#char").val();
        data.char_id    = $("#char_id").val();
        data.char_name  = $("#char_name").val();
        data.trans_id = $("#trans_id").val();
        data.money_type = $("#money_type").val();
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
        layer.alert($(this).data('info'),{area: ['320px', '600px']});
    }).on('click', 'button[data-type="trans_id"]', function () {
        var trans_id = $(this).html();
        $.ajax({
            type: "post",
            url: location.href + '&jinIf=916',
            data: {
                trans_id:trans_id,
                si:$('#server').val()
            },
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']
                });
            },
            success: function (output) {
                layer.closeAll('loading');
                layer.alert(JSON.stringify(output).replace(/\"/g, "").replace(/,/g, "<br/>"),{area: ['320px', '600px']})
            }
        });
    });
<?php echo '</script'; ?>
>
<?php }
}
