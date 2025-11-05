<?php
/* Smarty version 3.1.30, created on 2024-01-20 13:43:47
  from "C:\Users\Administrator\Desktop\pro\WebSiteYiXing\app\Admin\View\data2\selectTimePower.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65ab5d933fcc85_47006153',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '462e6c16e4b36f5745a5b115de7013fa1c6189db' => 
    array (
      0 => 'C:\\Users\\Administrator\\Desktop\\pro\\WebSiteYiXing\\app\\Admin\\View\\data2\\selectTimePower.html',
      1 => 1705720458,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_65ab5d933fcc85_47006153 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>通关时间排行</span></div>
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
    </div>
    <div>
        <label for="param1">筛选：</label>
        <input id="param1" type="text" class="form-control jin-search-input hide" placeholder="大章节">
        <input id="char_guid" type="text" class="form-control jin-search-input" placeholder="角色ID">
        <select id="opt_type" style="padding: 8px;">
            <option value="0">冒险</option>
            <option value="1">训练</option>
            <option value="2">挑战</option>
            <option value="5">宠物乐园</option>
            <option value="6">时空裂隙</option>
        </select>
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    </div>
</div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        <!--①都不输入:查询该所有章节通关时间最快的一个；<br>-->
        <!--①输入大章节:查询该章节通关时间最快的前30名；<br>-->
        ①输入角色ID:查询该角色通关每个大章节的最快时间；<br>
        <!--①输入大章节和角色ID:查询该角色通关该大章节最快时间的前30次；-->
    </div>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>排名</th>
            <th>周目</th>
            <th>大章节</th>
            <th>通关时间</th>
            <th>角色ID</th>
            <th>记录时间</th>
            <th>角色名</th>
            <th>等级</th>
            <th>总充值</th>
            <th>操作</th>
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
    var btn = [
        "<div class='btn-group btn-group-sm'>" +
        "<button data-type='a' class='btn btn-success'>查看本局详情</button>" +
        "</div>"
    ];
    var param0=function (json) {
        return json['param0']-0+1
    }
    var arr = ['power_id',param0,'param1', 'param5', 'char_guid','log_time','char_name','level','allfee',btn];
    var id = '#content';
    var data = {};

    $(function () {
        gsSelect('#group', '#server', '#platform');
        calendar('minute', '#time_start', '#time_end');
    });
    function getCharge() {
        data.time_start = $('#time_start').val();//查询开始时间;
        data.time_end   = $('#time_end').val();//查询结束时间
        data.param1  = $('#param1').val();
        data.char_guid  = $('#char_guid').val();
        data.si         = $('#server').val();
        data.pi         = $('#platform').val();
        data.opt_type         = $('#opt_type').val();
        noPageContentList(url, data, id, arr);
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        getCharge();
    });
    $('#content').on('click', 'button[data-type="a"]', function () {
        var param0=$(this).parents('tr').find('td').eq(1).text();
        var param1=$(this).parents('tr').find('td').eq(2).text();
        var char_guid=$(this).parents('tr').find('td').eq(4).text();
        var log_time=$(this).parents('tr').find('td').eq(5).text();
        $.ajax({
            type: "post",
            url: location.href + "&jinIf=9121",
            data: {
                param0:param0,
                param1:param1,
                char_guid:char_guid,
                log_time:log_time,
                si:$('#server').val()
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
                    '<tr><th>周目</th><th>大章节</th><th>小章节</th><th>通关时间</th><th>记录时间</th></tr>';
                for (var i=0;i<json.length;i++){
                    c+='<tr>' +
                        '<td>'+(json[i]['param0']-0+1)+'</td>' +
                        '<td>'+json[i]['param1']+'</td>' +
                        '<td>'+json[i]['param2']+'</td>' +
                        '<td>'+json[i]['param4']+'</td>' +
                        '<td>'+json[i]['log_time']+'</td>' +
                        '</tr>';
                }
                c+='</table>';
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '',
                    area: ['600px', '800px'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content:'<div class="jin-child">' +
                    c+
                    '</div>'
                });
            }
        });
    }).on('click', 'button[data-type="showchar"]', function () {
        var char_guid=$(this).parents('tr').find('td').eq(4).text();
        $.ajax({
            type: "post",
            url: location.href + "&jinIf=916",
            data: {
                char_guid:char_guid,
                si:$('#server').val()
            },
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (json) {
                layer.closeAll('loading');
                layer.alert(json);
            }
        });
    });
<?php echo '</script'; ?>
>
<?php }
}
