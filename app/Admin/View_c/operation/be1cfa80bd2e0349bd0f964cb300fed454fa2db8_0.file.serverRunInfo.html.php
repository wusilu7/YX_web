<?php
/* Smarty version 3.1.30, created on 2024-04-24 18:13:11
  from "D:\pro\WebSiteYiXing\app\Admin\View\operation\serverRunInfo.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6628db37bdb431_56002182',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'be1cfa80bd2e0349bd0f964cb300fed454fa2db8' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\operation\\serverRunInfo.html',
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
function content_6628db37bdb431_56002182 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.02.selectonline.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span></span></div>

<hr/>
<div class="jin-search-div">
    <label class="select_label control-label">主机名:</label>
    <select id="hostname" class="selectpicker" multiple data-live-search="true" data-actions-box="true">

    </select>
    <label for="time">日期：</label>
    <input size="16" type="text" id="time" class="form-control jin-datetime"
           placeholder="选择日期">
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
</div>
<div id="jin-charts-online">

</div>



<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<!--AJAX调用在线人数的json接口-->
<?php echo '<script'; ?>
 src="<?php echo JS;?>
echarts.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
highcharts.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
    $('.selectpicker').selectpicker({
        'selectedText': 'cat',
        'noneSelectedText': '请选择',
        'deselectAllText': '全不选',
        'selectAllText': '全选'
    });
    $(".bs-actionsbox .btn-group").append('<button type="button" id="sure" class="btn btn-default">确认</button>');
    function hostname() {
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=9122",
            dataType: 'json',
            success: function (res) {
                var c = '';
                for (var i = 0; i < res.length; i++) {
                    c +='<optgroup label="'+ res[i][0] +'">'
                    for (var j = 1; j < res[i].length; j++){
                        c +='<option value="'+res[i][j]+'">'+res[i][j]+'</option>';
                    }
                    c += '</optgroup>';
                }
                $('#hostname').html(c);
                $('.selectpicker').selectpicker('refresh');
                for (var j=0;j<15;j++){
                    $('#jin-charts-online').append('<div class="jin-charts-online" id="jin-charts-online'+j+'"></div>')
                }
            }
        });
    }
    hostname();
    calendarOne('month', '#time');
    var data = {};
    function getOnline() {
        data.time  = $('#time').val();//查询开始时间
        data.hostname  = $('#hostname').val();
        $.ajax({
            type: "post",
            async: true,
            url: location.href + "&jinIf=912",
            data: data,
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (res) {
                layer.closeAll('loading');
                for (var i=0;i<res.length;i++){
                    var memory2 = eval('[' + res[i][1].memory2 + ']');
                    var disk = eval('[' + res[i][1].disk + ']');
                    var cpu = eval('[' + res[i][1].cpu + ']');
                    var uptime = eval('[' + res[i][1].uptime + ']');
                    var chart = Highcharts.chart('jin-charts-online'+i, {
                        title: {
                            text: '<span style="font-size: 30px;">'+res[i][0]+'</span>'
                        },
                        subtitle: {
                            text: ''
                        },
                        yAxis: {
                            title: {
                                text: '百分比'
                            }
                        },
                        xAxis: {
                            categories: res[i][1].day
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'middle'
                        },
                        tooltip: {
                            shared: true
                        },
                        series: [{
                            color: 'red',
                            name: '内存',
                            data: memory2
                        }, {
                            name: '磁盘',
                            data: disk
                        }, {
                            name: 'CPU',
                            data: cpu
                        }, {
                            name: 'Uptime',
                            data: uptime
                        }]
                    });
                }
            },
            error: function () {
                layer.closeAll('loading');
                layer.msg('暂时无法获取数据');
            }
        });
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        $('.jin-charts-online').each(function () {
            $(this).html('');
        });
        data.check_type = 912;
        getOnline();
    });

<?php echo '</script'; ?>
>
<?php }
}
