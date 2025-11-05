<?php
/* Smarty version 3.1.30, created on 2023-08-07 10:18:12
  from "/lnmp/www/app/Admin/View/data2/selectUnusualAcc.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_64d05464e02f21_26579741',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '560d4c3cecdf1c79c704199a62af96e3c00c9282' => 
    array (
      0 => '/lnmp/www/app/Admin/View/data2/selectUnusualAcc.html',
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
function content_64d05464e02f21_26579741 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>异常账号</span></div>
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
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>角色ID</th>
            <th>opt</th>
            <th>param0</th>
            <th>param1</th>
            <th>param2</th>
            <th>param3</th>
            <th>param4</th>
            <th>param5</th>
            <th>param6</th>
            <th>记录时间</th>
            <th>操作</th>
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
    var btn = [
        "<div class='btn-group btn-group-sm'>" +
        "<button data-type='a' class='btn btn-success'>封号</button>" +
        "</div>"
    ];
    var btns = function (json) {
      if(json['color']==1){
            return "<button data-type='d' class='btn btn-danger'>解除封号</button>";
      }else{
           return btn;
      }
    };
    var all = function (json) {
        return '<a class="btn btn-info" data-info="' + JSON.stringify(json).replace(/\"/g, "").replace(/,/g, "<br/>") + '"><span class="glyphicon glyphicon-info-sign"></span></a>';
    };
    var arr = ['char_guid','opt_type','param0','param1','param2','param3','param4','param5','param6','log_time',btns,all];
    var id = ["#content", "#page"];
    var data = {page: 1};
    $(function () {
        gsSelect('#group', '#server', '#platform');
        calendar('minute', '#time_start', '#time_end');
    });
    function getCharge() {
        data.time_start = $('#time_start').val();//查询开始时间;
        data.time_end   = $('#time_end').val();//查询结束时间
        data.si         = $('#server').val();
        data.pi         = $('#platform').val();
        tableList(url, data, id, arr);
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        getCharge();
    });
    $('#content').on('click', 'button[data-type="a"]', function () {
        var char_guid=$(this).parents('tr').find('td').eq(0).text();
        $.ajax({
            type: "post",
            url: location.href + "&jinIf=913",
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
                getCharge();
            }
        });
    }).on('click', 'button[data-type="d"]', function () {
        var char_guid=$(this).parents('tr').find('td').eq(0).text();
        $.ajax({
            type: "post",
            url: location.href + "&jinIf=914",
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
                getCharge();
            }
        });
    });
    $('#content').on('click', 'a[class="btn btn-info"]', function () {
        layer.alert($(this).data('info'),{area: ['320px', '600px']});
    });
<?php echo '</script'; ?>
>
<?php }
}
