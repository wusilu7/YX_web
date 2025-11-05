<?php
/* Smarty version 3.1.30, created on 2024-01-15 20:46:20
  from "/lnmp/www/app/Admin/View/pay/chargeMoneyRate.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65a5291ce57855_85375050',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5dcc44b06756b76a8bdbdcd2f68eea3ff21d754b' => 
    array (
      0 => '/lnmp/www/app/Admin/View/pay/chargeMoneyRate.html',
      1 => 1678771401,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_65a5291ce57855_85375050 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.03.selectdistribution.css" rel="stylesheet">
<style>
    .btn_on {
        color: #fff;
        background-color: #269abc;
        border-color: #1b6d85;
    }
</style>
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>充值金额占比</span></div>
<div class="alert alert-info">
    <div id="group_server"></div>
</div>
<hr/>
<div class="jin-search-div">
    <label for="time_start">日期：</label>
    <input size="16" type="text" id="time_start" class="form-control jin-datetime" placeholder="开始日期">
    -
    <input size="16" type="text" id="time_end" class="form-control jin-datetime" placeholder="结束日期">
    <label for="gift_type">充值类型：</label>
    <select id="gift_type" style="padding: 8px;">
        <option value="0">正常</option>
        <option value="1">付费礼包</option>
    </select>
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
</div>
<div id='total' class="center"></div>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>额度(元)</th>
            <th>订单数</th>
            <th>订单占比</th>
            <th>总金额(元)</th>
            <th>金额占比</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--AJAX调用等级比例分布的json接口-->
<?php echo '<script'; ?>
 type="text/javascript">
    gsSelect('#group', '#server', '#platform');
    calendar('month', '#time_start', '#time_end');
    var section = [];
    var amount = '';
    var total = [];
    var data1 = {};
    function getDistribution() {
        var time_start = $('#time_start').val();
        var time_end   = $('#time_end').val();

        data1.time_start  = time_start;//查询开始时间;
        data1.time_end    = time_end;//查询结束时间
        data1.pi    = $('#platform').val();
        data1.si    = $('#server').val();
        data1.gift_type    = $('#gift_type').val();
        data1.group = $('#group').val();
        $.ajax({
            type: "post",
            async: true,
            url: location.href + "&jinIf=912",
            data: data1,
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (json) {
               layer.closeAll('loading');
                var s = '';
                if (json == 0) {
                    amount = json;
                    num = json;
                } else {
                    amount = json[json.length - 2];
                    num = json[json.length - 1];
                }
                for (var i = 0; i <= json.length - 3; i++) {
                    section.push(json[i].fee);
                    total.push(json[i].total);
                    var scale = '0%';
                    if (amount == '0') {
                        scale = '0%';
                    } else {
                        if (json[i].total == 0) {
                            scale = '0%';
                        } else {
                            scale = (json[i].total / amount * 100).toFixed(2) + '%';
                        }
                    }
                    var scale1 = '0%';
                    if (num == '0') {
                        scale1 = '0%';
                    } else {
                        if (json[i].num == 0) {
                            scale1 = '0%';
                        } else {
                            scale1 = (json[i].num / num * 100).toFixed(2) + '%';
                        }
                    }
                    s += '<tr>' +
                        '<td>' + json[i].fee + '</td>' +
                        '<td>' + json[i].num + '</td>' +
                        '<td>' + scale1 + '</td>' +
                        '<td>' + json[i].total + '</td>' +
                        '<td>' + scale + '</td>' +
                        '</tr>';
                }
                $("#content").html(s);
                s = '总充值金额：' + amount + '元&nbsp;&nbsp;&nbsp;&nbsp;总订单数：'+num;
                $("#total").html(s);
            },
            error: function () {
                $("#content").html('');
                $("#total").html('');
                layer.closeAll('loading');
                layer.msg('数据获取失败，请勿频繁刷新');
            }
        });
    }
    // 普通查询
    $("#jin_search").click(function () {
        data1.check_type = 912;  // 普通查询
        if ($('#server').val() === null) {
            layer.msg('请选择服务器');
            return false;
        }
        getDistribution();
    });
<?php echo '</script'; ?>
>
<?php }
}
