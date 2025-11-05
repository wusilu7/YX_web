<?php
/* Smarty version 3.1.30, created on 2023-04-28 22:03:36
  from "/lnmp/www/app/Admin/View/data1/selectOnline.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_644bd238a5a991_28006592',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'aa6f1b128b5e360e687ba00ccb7a0cd4b6a43565' => 
    array (
      0 => '/lnmp/www/app/Admin/View/data1/selectOnline.html',
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
function content_644bd238a5a991_28006592 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.02.selectonline.css" rel="stylesheet">
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
<div class="jin-content-title"><span>实时在线人数</span></div>
<div class="alert alert-info">
    <div class="form-group" id="group_server_5"></div>
</div>
<hr/>
<div class="jin-search-div">
    <label for="time_start">日期：</label>
    <input size="16" type="text" id="time_start" class="form-control jin-datetime"
           placeholder="开始日期">
    -
    <input size="16" type="text" id="time_end" class="form-control jin-datetime"
           placeholder="结束日期">
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
</div>
<hr/>
<div id="jin-charts-online"></div>

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
    gsSelect3('#g', '#p', '#s');
    calendarOne('month', '#time_start',true);
    calendarOne('month', '#time_end');
    var data = {};
    function getOnline() {
        switch(document.domain) {
            case 'croodsadmin-juzhang.xuanqu100.com':
                url_finall = "http://106.14.43.222/?p=I&c=Player&a=selectOnline";
                break;
            case 'croodsadmin-lehao.xuanqu100.com':
                url_finall = "http://139.224.229.193/?p=I&c=Player&a=selectOnline";
                break;
            case 'croodsadmin-lufeifan.xuanqu100.com':
                url_finall = "http://croodsadmin-lufeifan.xuanqu100.com/?p=I&c=Player&a=selectOnline";
                break;
            case 'croodsadmin-channel.xuanqu100.com':
                url_finall = "http://139.224.10.141/?p=I&c=Player&a=selectOnline";
                break;
            default:
                url_finall = location.href + "&jinIf=912";
        }
        data.si    = $('#s').val()[0];
        data.siArr    = $('#s').val();
        data.time_start = $('#time_start').val();//查询开始时间
        data.time_end   = $('#time_end').val();//查询结束时间
        $.ajax({
            type: "post",
            async: true,
            url: url_finall,
            data: data,
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (res) {
                layer.closeAll('loading');
                var series_middle = [];
                for (var i in res){
                    if(i!='day'){
                        series_middle.push({
                            name: i,
                            data: eval('[' + res[i] + ']')
                        });
                    }
                }
                Highcharts.chart('jin-charts-online', {
                    title: {
                        text: ''
                    },
                    subtitle: {
                        text: ''
                    },
                    yAxis: {
                        title: {
                            text: '人数'
                        }
                    },
                    xAxis: {
                        categories: res.day
                    },
                    legend: {
                        layout: 'vertical',
                        align: 'right',
                        verticalAlign: 'middle'
                    },
                    tooltip: {
                        shared: true
                    },
                    series: series_middle
                });
            },
            error: function () {
                layer.closeAll('loading');
                layer.msg('暂时无法获取数据');
            }
        });
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        data.check_type = 912;
        getOnline();
    });
<?php echo '</script'; ?>
>
<?php }
}
