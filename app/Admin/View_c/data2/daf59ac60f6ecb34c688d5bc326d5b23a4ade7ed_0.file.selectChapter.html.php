<?php
/* Smarty version 3.1.30, created on 2024-04-24 18:13:31
  from "D:\pro\WebSiteYiXing\app\Admin\View\data2\selectChapter.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6628db4bd75345_18190040',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'daf59ac60f6ecb34c688d5bc326d5b23a4ade7ed' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\data2\\selectChapter.html',
      1 => 1713952306,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_6628db4bd75345_18190040 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>章节通关率</span></div>
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
        <select id="opt_type" style="padding: 8px;">
            <option value="0">冒险</option>
            <option value="1">训练</option>
            <option value="2">挑战</option>
            <option value="5">宠物乐园</option>
            <option value="6">时空裂隙</option>
        </select>
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
        <!--<input size="16" type="checkbox" id="ischeck1" value="1">-->
        <!--<label for="ischeck1" style="margin-left: 0px;">新角色</label>-->
        <!--<input size="16" type="checkbox" id="ischeck2" value="1">-->
        <!--<label for="ischeck2" style="margin-left: 0px;">付费</label>-->
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th style="display: none">周目</th>
            <th>章节</th>
            <th>参加次数</th>
            <th>成功次数</th>
            <th>成功率(次数)</th>
            <th>参加人数</th>
            <th>成功人数</th>
            <th>成功率(人数)</th>
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
    var url = location.href + "&jinIf=912";
    var btn = function (json) {
        return ['<button data-type="bw" class="btn btn-sm btn-primary">查询小章节通关率</button>'];
    };
    var arr = ['param0','param1','num1','num2','lv1','people1','people2','lv2',btn];
    var id = '#content';
    var data = {};
    $(function () {
        gsSelect('#group', '#server', '#platform');
        calendar('minute', '#time_start', '#time_end');
    });
    function getCharge() {
        data.time_start = $('#time_start').val();//查询开始时间;
        data.time_end   = $('#time_end').val();//查询结束时间
        data.si         = $('#server').val();
        data.pi         = $('#platform').val();
        data.group         = $('#group').val();
        data.opt_type         = $('#opt_type').val();
        data.ischeck1      = $('#ischeck1').is(':checked') ? $('#ischeck1').val() : '';
        data.ischeck2      = $('#ischeck2').is(':checked') ? $('#ischeck2').val() : '';
        noPageContentList12(url, data, id, arr);
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        getCharge();
    });
    $('#content').on('click', 'button[data-type="bw"]', function () {
        var param0=$(this).parents('tr').find('td').eq(0).text();
        var param1=$(this).parents('tr').find('td').eq(1).text();
        var opt_type         = $('#opt_type').val();
        if(param1=='1000'){
            param1=0;
        }
        $.ajax({
            type: "post",
            url: location.href + "&jinIf=9121",
            data: {
                param0:param0,
                param1:param1,
                si:$('#server').val(),
                opt_type:opt_type
            },
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (json) {
                layer.closeAll('loading');
                var c='<table class="table table-bordered table-hover text-center jin-server-table">' +
                    '<tr><th>小章节</th><th>参加次数</th><th>成功次数</th><th>成功率(次数)</th><th>参加人数</th><th>成功人数</th><th>成功率(人数)</th></tr>';
                for (var i=0;i<json.length;i++){
                    c+='<tr>' +
                        '<td>'+json[i]['param3']+'</td>' +
                        '<td>'+json[i]['num1']+'</td>' +
                        '<td>'+json[i]['num2']+'</td>' +
                        '<td>'+json[i]['lv1']+'</td>' +
                        '<td>'+json[i]['people1']+'</td>' +
                        '<td>'+json[i]['people2']+'</td>' +
                        '<td>'+json[i]['lv2']+'</td>' +
                        '</tr>';
                }
                c+='</table>';
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '',
                    area: ['800px', '800px'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content:'<div class="jin-child">' +
                    c+
                    '</div>'
                });
            }
        });
    });
<?php echo '</script'; ?>
>
<?php }
}
