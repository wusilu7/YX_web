<?php
/* Smarty version 3.1.30, created on 2024-05-08 11:25:55
  from "D:\pro\WebSiteYiXing\app\Admin\View\data2\countGameData.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_663af0c3046528_29970563',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '084c5ef208c401f3c3c24c1a0f198eb91e685df6' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\data2\\countGameData.html',
      1 => 1715138753,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_663af0c3046528_29970563 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.03.selectdistribution.css" rel="stylesheet">
<style>
    .btn_on {
        color: #fff;
        background-color: #3abc26;
        border-color: #1b6d85;
    }
</style>
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>玩家游戏进度汇总</span></div>
<div class="alert alert-info">
    <div id="group_server"></div>
</div>
<hr/>
<div class="jin-search-div">
    <label for="">日期：</label>
    <input size="16" type="text" id="time_start" class="form-control jin-datetime" placeholder="选择日期">
    <input size="16" type="text" id="time_end" class="form-control jin-datetime" placeholder="选择日期">
    <input type="text" id="acc_name" class="form-control jin-datetime" placeholder="账号名">
    <input type="text" id="char_id" class="form-control jin-datetime" placeholder="角色id">
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    <a id="jin_excel3" style="height: 32px;margin-top: -5px" class="btn btn-danger">保存到Excel</a>
</div>
<hr/>
<!--<div id="jin-charts-distribution"></div>-->

<div id='total' class="center"></div>

<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>排序编号</th>
            <th>channel</th>
            <th>value</th>
            <th>次数</th>
            <th>达成率</th>
            <th>流失率</th>
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
    calendar('minute', '#time_start', '#time_end');
    var level = [];
    var operation = [];
    var sum = [];
    var num = [];
    var data1 = {
        type: 1
    };
    $('#div_btn button').click(function() {
        $(this).addClass('btn_on').siblings().removeClass('btn_on');
    });

    function getDistribution() {
        data1.page='';
        data1.role  = $('#role').val();
        data1.group = $('#group').val();
        data1.pi    = $('#platform').val();
        data1.si    = $('#server').val();
        data1.time_start  = $("#time_start").val();
        data1.time_end  = $("#time_end").val();
        data1.acc_name  = $("#acc_name").val();
        data1.char_id  = $("#char_id").val();
        data1.excel  = '';
        $.ajax({
            type: "post",
            async: true,
            url: location.href + "&jinIf=912",
            data: data1,
            dataType: "json",
            beforeSend: function () {
                layer.load();
            },
            success: function (json) {

               layer.closeAll('loading');
                var s = '';
                total = json[json.length - 1];
                var operation = [];
                var sort_k = [];
                var value = [];
                const sum = [];
                for (var i = 0; i <= json.length - 1; i++) {

                    operation.push(json[i].operation );
                    value.push(json[i].value );
                    sum.push(json[i].sum);
                    sort_k.push(json[i].sort_k);
                    if (json[i].sum >= 0) {
                        s += '<tr>' +
                            '<td>' + json[i].sort_k  + '</td>' +
                            '<td>' + json[i].operation  + '</td>' +
                            '<td>' + json[i].value  + '</td>' +
                            '<td>' + json[i].sum + '</td>' +
                            '<td>' + json[i].lv + '</td>' +
                            '<td>' + json[i].lose + '</td>' +
                            '</tr>';
                    }
                }
                $("#content").html(s);
                s = '';
                $("#total").html(s);
                level.splice(0, level.length);//清空数组
                num.splice(0, num.length);
            },
            error: function (msg) {
                $("#content").html('');
                $("#total").html('');
                layer.closeAll('loading');
                layer.msg('数据获取失败，请勿频繁刷新');
            }
        });
    }
    $("#jin_search").click(function () {
        if ($('#server').val() === null) {
            layer.msg('请选择服务器');
            return false;
        }
        data1.check_type = 912;  // 普通查询
        getDistribution();
    });
    // 导出Excel
    $("#jin_excel3").on('click', function () {
        data1.page='';
        data1.role  = $('#role').val();
        data1.group = $('#group').val();
        data1.pi    = $('#platform').val();
        data1.si    = $('#server').val();
        data1.time_start  = $("#time_start").val();
        data1.time_end  = $("#time_end").val();
        data1.acc_name  = $("#acc_name").val();
        data1.char_id  = $("#char_id").val();
        data1.excel  = 'excel';
        $.ajax({
            type: "post",
            url: location.href + '&jinIf=912',
            data: data1,
            dataType: "json",
            beforeSend: function () {
                layer.load();
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
    // 服务器汇总
    $("#server_summary").click(function () {
        data1.check_type = 998;  // 服务器汇总
        getDistribution();
    });
    // 渠道汇总
    $("#group_summary").click(function () {
        data1.check_type = 999;  // 渠道汇总
        giCollect(getDistribution,1);
    });
    $("#btn1").click(function () {
        data1.type = 1;
        getDistribution();
    });
    $("#btn2").click(function () {
        data1.type = 3;
        getDistribution();
    });
    $("#btn3").click(function () {
        data1.type = 7;
        getDistribution();
    });
<?php echo '</script'; ?>
><?php }
}
