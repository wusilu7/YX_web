<?php
/* Smarty version 3.1.30, created on 2024-01-19 10:22:16
  from "/lnmp/www/app/Admin/View/data2/selectDistribution.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65a9dcd8810761_12223319',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f4cd2e8d561ab16632565a98994fed2e2ac4aa2d' => 
    array (
      0 => '/lnmp/www/app/Admin/View/data2/selectDistribution.html',
      1 => 1705630931,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_65a9dcd8810761_12223319 (Smarty_Internal_Template $_smarty_tpl) {
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
<div class="jin-content-title"><span>等级分布</span></div>
<div class="alert alert-info">
    <div id="group_server"></div>
</div>
<hr/>
<div class="jin-search-div">
    <label for="time">日期：</label>
    <input size="16" type="text" id="time_start" class="form-control jin-datetime"placeholder="选择日期">
    <input size="16" type="text" id="time_end" class="form-control jin-datetime"placeholder="选择日期">
    <label for="role" class="hide">职业：</label>
    <select name="role" id="role" class="form-control jin-datetime hide">
        <option value="">请选择</option>
        <option value="0,0">亡灵.刺客</option>
        <option value="0,1">亡灵.游侠</option>
        <option value="1,0">维京.狼战士</option>
        <option value="1,1">维京.唤龙者</option>
        <option value="2,0">人类.法师</option>
        <option value="2,1">人类.法剑</option>
    </select>
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    <a id="server_summary" class="btn btn-success">服务器汇总</a>
</div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ① 等级分布图表示日期选择框（不填默认今天）注册的角色在当天/第3天/第7天的等级分布，默认当天；
    </div>
    <div>
        ②
        例如日期填写“2017-10-10”，点击搜索按钮后，后台将会绘制“2017-10-10”注册的角色在“2017-10-10”当天的等级分布图，再点击“3天分布”按钮，后台将会绘制“2017-10-10”注册的角色在第3天“2017-10-12”的等级分布图；
    </div>
</div>
<hr/>
<div id="jin-charts-distribution"></div>

<div class="btn-group center" id="div_btn">
    <button id='btn1' class="btn btn-info">当天分布</button>
    <button id='btn2' class="btn btn-info">3天分布</button>
    <button id='btn3' class="btn btn-info">7天分布</button>
</div>
<div id='total' class="center"></div>

<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>等级</th>
            <th>人数</th>
            <th>占比</th>
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
    var level = [];
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
                total = json[json.length - 1];
                for (var i = 0; i <= json.length - 2; i++) {
                    level.push(json[i].level + '级');
                    num.push(json[i].num);
                    if (total === '0') {
                        var scale = 0;
                    } else {
                        if (json[i].num == '') {
                            scale = '0%';
                        } else {
                            scale = (json[i].num / total * 100).toFixed(2) + '%';
                        }
                    }
                    if (json[i].level > 0) {
                        s += '<tr>' +
                            '<td>' + json[i].level + '级' + '</td>' +
                            '<td>' + json[i].num + '</td>' +
                            '<td>' + scale + '</td>' +
                            '</tr>';
                    }
                }
                $("#content").html(s);
                s = '总人数：' + total + '人';
                $("#total").html(s);
                myChart.setOption(option);
                level.splice(0, level.length);//清空数组
                num.splice(0, num.length);
            },
            error: function (msg) {
                $("#content").html('');
                $("#total").html('');
                myChart.clear();
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
    $("#jin_excel").on('click', function () {
        data1.page='excel';
        data1.role  = $('#role').val();
        data1.group = $('#group').val();
        data1.pi    = $('#platform').val();
        data1.si    = $('#server').val();
        data1.time_start  = $("#time_start").val();
        data1.time_end  = $("#time_end").val();
        $.ajax({
            type: "post",
            url: location.href + '&jinIf=912',
            data: data1,
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
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
echarts.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    var myChart = echarts.init(document.getElementById('jin-charts-distribution'));
    var option = {
        tooltip: {
            show: true,
            trigger: 'axis',
            axisPointer: {
                type: 'shadow',
                label: {
                    backgroundColor: '#6a7985'
                }
            }
        },
        color: ['#009966'],
        legend: {
            data: ['人数']
        },
        toolbox: {
            feature: {
                saveAsImage: {
                    name: 'xoa',
                    title: '保存'
                }
            }
        },
        xAxis: [
            {
                boundaryGap: false,
                name: '等级',
                type: 'category',
                data: level
            }
        ],
        yAxis: [
            {
                boundaryGap: false,
                name: '人数',
                type: 'value',
                data: num
            }
        ],
        series: [
            {
                name: "人数",
                type: "bar",
                data: num,
                roam: true,
                markPoint: {
                    data: [
                        {type: 'max', name: '最大值'},
                        {type: 'min', name: '最小值'}
                    ]
                },
                markLine: {
                    data: [
                        {type: 'average', name: '平均值'}
                    ]
                }
            }
        ]
    };
<?php echo '</script'; ?>
>
<?php }
}
