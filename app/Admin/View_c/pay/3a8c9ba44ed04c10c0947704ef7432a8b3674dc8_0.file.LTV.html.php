<?php
/* Smarty version 3.1.30, created on 2024-01-18 17:26:22
  from "/lnmp/www/app/Admin/View/pay/LTV.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65a8eebe4b5715_60023759',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '3a8c9ba44ed04c10c0947704ef7432a8b3674dc8' => 
    array (
      0 => '/lnmp/www/app/Admin/View/pay/LTV.html',
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
function content_65a8eebe4b5715_60023759 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.01.selectretention.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>LTV(按单个服统计)</span></div>
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
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    <!--<a id="group_summary" class="btn btn-success">渠道汇总</a>-->
    <input size="16" type="checkbox" id="ischeck" value="1">
    <label  for="ischeck" style="margin-left: 0px;">查询今日实时更新</label>
</div>
<div class="jin-explain">
</div>
<hr/>
<div class="table-responsive jin-table-12px">
    <table class="table table-striped text-center jin-retention-table">
        <thead>
        <tr>
            <th class="jin-retention-column1">日期</th>
            <th>新增设备数</th>
            <th>当天</th>
            <th>第2天</th>
            <th>第3天</th>
            <th>第4天</th>
            <th>第5天</th>
            <th>第6天</th>
            <th>第7天</th>
            <th>第8天</th>
            <th>第10天</th>
            <th>15天</th>
            <th>30天</th>
            <th>45天</th>
            <th>60天</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ① LTV计算公式:LTV = N天付费总额(该天新增设备数产生的付费)/该天新增设备数；
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 type="text/javascript">
    gsSelect('#group', '#server', '#platform');
    var numin0 = function (json) {
        if (json['numin0'] != '') {
            return json['numin0'] + '(' + json['r0'] + ')';
        } else {
            return '0(0)';
        }
    }
    var numin1 = function (json) {
        if (json['numin1'] != '') {
            return json['numin1'] + '(' + json['r1'] + ')';
        } else {
            return '0(0)';
        }
    }
    var numin2 = function (json) {
        if (json['numin2'] != '') {
            return json['numin2'] + '(' + json['r2'] + ')';
        } else {
            return '0(0)';
        }
    }
    var numin3 = function (json) {
        if (json['numin3'] != '') {
            return json['numin3'] + '(' + json['r3'] + ')';
        } else {
            return '0(0)';
        }
    }
    var numin4 = function (json) {
        if (json['numin4'] != '') {
            return json['numin4'] + '(' + json['r4'] + ')';
        } else {
            return '0(0)';
        }
    }
    var numin5 = function (json) {
        if (json['numin5'] != '') {
            return json['numin5'] + '(' + json['r5'] + ')';
        } else {
            return '0(0)';
        }
    }
    var numin6 = function (json) {
        if (json['numin6'] != '') {
            return json['numin6'] + '(' + json['r6'] + ')';
        } else {
            return '0(0)';
        }
    }
    var numin7 = function (json) {
        if (json['numin7'] != '') {
            return json['numin7'] + '(' + json['r7'] + ')';
        } else {
            return '0(0)';
        }
    }
    var numin9 = function (json) {
        if (json['numin9'] != '') {
            return json['numin9'] + '(' + json['r9'] + ')';
        } else {
            return '0(0)';
        }
    }
    var numin15 = function (json) {
        if (json['numin14'] != '') {
            return json['numin14'] + '(' + json['r14'] + ')';
        } else {
            return '0(0)';
        }
    }
    var numin30 = function (json) {
        if (json['numin29'] != '') {
            return json['numin29'] + '(' + json['r29'] + ')';
        } else {
            return '0(0)';
        }
    }
    var numin45 = function (json) {
        if (json['numin44'] != '') {
            return json['numin44'] + '(' + json['r44'] + ')';
        } else {
            return '0(0)';
        }
    }
    var numin60 = function (json) {
        if (json['numin59'] != '') {
            return json['numin59'] + '(' + json['r59'] + ')';
        } else {
            return '0(0)';
        }
    }
    calendar('month', '#time_start', '#time_end');
    var url = location.href + "&jinIf=912";
    var arr = ['date', 'numup',numin0, numin1, numin2, numin3, numin4, numin5, numin6,numin7,numin9, numin15, numin30,numin45,numin60];
    var id = ["#content", "#page"];
    var data = {};
    function getRetention() {
        data.page       = 1;
        data.group      = $('#group').val();
        data.pi         = $('#platform').val();
        data.si         = $('#server').val();
        data.time_start = $('#time_start').val();//查询开始时间
        data.time_end   = $('#time_end').val();//查询结束时间
        data.ischeck       = $('#ischeck').is(':checked') ? $('#ischeck').val() : 0;
        tableList(url, data, id, arr);
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        data.check_type = 912;
        getRetention();
    });
    // 服务器汇总
    $("#server_summary").on('click', function () {
        data.check_type = 998;
        getRetention();
    });
    // 渠道汇总
    $("#group_summary").on('click', function () {
        data.check_type = 999;
        giCollect(getRetention);
    });
    $("#jin_excel").on('click', function () {
        data.page = 'excel';  // 生成excel表
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
>
<?php }
}
