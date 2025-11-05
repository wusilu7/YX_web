<?php
/* Smarty version 3.1.30, created on 2024-01-31 16:35:32
  from "/lnmp/www/app/Admin/View/operation/serverCMail.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65ba065496ce71_26339938',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a17c19d57ba415cb19904e6b6bbb4b28bf4af36e' => 
    array (
      0 => '/lnmp/www/app/Admin/View/operation/serverCMail.html',
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
function content_65ba065496ce71_26339938 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>配置邮件记录</span></div>
<!--查询div-->
<hr/>
<div class="jin-search-div">
    <div>
        <label for="time_start">日期：</label>
        <input size="16" type="text" id="time_start" class="form-control jin-datetime-long"
               placeholder="开始日期">
        -
        <input size="16" type="text" id="time_end" class="form-control jin-datetime-long"
               placeholder="结束日期">
    </div>
    <div>
        <label for="Mtitle">筛选：</label>
        <input id="Mtitle" type="text" class="form-control jin-search-input" placeholder="标题">
        <input id="Mcontent" type="text" class="form-control jin-search-input" placeholder="正文">
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
        <button id="jin_search1" class="btn  btn-success">所有exeption</button>
        <button id="jin_search2" class="btn  btn-success">所有fail</button>
        <button data-type="all_open" class="btn btn-danger">批量删除</button>
    </div>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th class="jin-server-column1">
                <input id="all_choose" type="checkbox">
                <label for="all_choose">全选</label>
            </th>
            <th>邮件标题</th>
            <th>邮件正文</th>
            <th>时间</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>
<div class="jin-explain">
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    var contents = function (json) {
        return '<div style="font-weight: bold;">'+json.content+'</div>';
    };
    var id_check = function (json) {
        return '<input type="checkbox" value="' + json.id + '" />'
    };
    var btn = function (json) {
        if(json.is_show==0){
            return '<a data-type="biaoji" data-data-id="'+json.id+'" class="btn btn-info">标记</a><br><br>' +
                '<a data-type="delete" data-data-id="'+json.id+'" class="btn btn-danger">删除</a>';
        }else{
            return '<a class="btn btn-info">已处理</a><br><br>' +
                '<a data-type="delete" data-data-id="'+json.id+'" class="btn btn-danger">删除</a><br>';
        }
    };
    calendar('day', '#time_start', '#time_end');
    var url = location.href + "&jinIf=912";
    var arr = [id_check,'title',contents,'log_time',btn];
    var id = ["#content", "#page"];
    var data = {};
    $("#jin_search").on('click', function () {
        data.page = 1;
        data.Mtitle = $('#Mtitle').val();
        data.Mcontent = $('#Mcontent').val();
        data.time_start = $('#time_start').val();//查询开始时间;
        data.time_end = $('#time_end').val();//查询结束时间
        tableList(url, data, id, arr);
    });
    $("#jin_search1").on('click', function () {
        arr = [id_check,'title',contents,'log_time',btn];
        data.page = 1;
        data.Mtitle = $('#Mtitle').val();
        data.Mcontent = 'exeption';
        data.time_start = $('#time_start').val();//查询开始时间;
        data.time_end = $('#time_end').val();//查询结束时间
        tableList(url, data, id, arr);
    });
    $("#jin_search2").on('click', function () {
        arr = [id_check,'title',contents,'log_time',btn];
        data.page = 1;
        data.Mtitle = $('#Mtitle').val();
        data.Mcontent = 'fail';
        data.time_start = $('#time_start').val();//查询开始时间;
        data.time_end = $('#time_end').val();//查询结束时间
        tableList(url, data, id, arr);
    });
    $('#content').on('click', 'a[data-type="biaoji"]', function() {
        var ids = $(this).attr("data-data-id");
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=913",
            data: {
                id: ids
            },
            dataType: "json",
            success: function (json) {
                tableList(url, data, id, arr);
            }
        });
    }).on('click', 'a[data-type="delete"]', function() {
        var ids = $(this).attr("data-data-id");
        layer.alert('确认删除吗？', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function (index) {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=914",
                data: {
                    id: ids
                },
                dataType: "json",
                success: function (json) {
                    layer.close(index);
                    tableList(url, data, id, arr);
                }
            });
        });

    }).on('click', 'tr', function() {
        var cb = $(this).find('td:first>input');
        if (! cb.is(':checked')) {
            cb.attr('checked', true);
            $(this).attr('style', 'background: #aba5618c');
        } else {
            cb.attr('checked', false);
            $(this).removeAttr('style', 'background: #aba5618c');
        }
    });

    // 全选
    $('#all_choose').click(function() {
        var check_on = $(this).is(':checked');
        if (check_on) {
            $('#content').find('input[type="checkbox"]').attr('checked', true);
        } else {
            $('#content').find('input[type="checkbox"]').attr('checked', false);
        }
    });

    // 获取选中的服务器
    function getChoose() {
        var server_id = '';
        $('#content input[type="checkbox"]:checked').each(function(index, el) {
            if (index == 0) {
                server_id = $(el).val();
            } else {
                server_id += ',' + $(el).val();
            }
        });

        if (server_id == '') {
            layer.alert('请选择数据！', {icon: 2});
            return false;
        }

        return {
            'server_id': server_id
        };
    }

    // 点击批量开服
    $('button[data-type="all_open"]').click(function() {
        var arr1 = getChoose().server_id;
        if(arr1==undefined){
           return false;
        }
        layer.alert('确认删除吗？', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function (index) {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=914",
                data: {
                    id: arr1
                },
                dataType: "json",
                success: function (json) {
                    layer.close(index);
                    tableList(url, data, id, arr);
                }
            });
        });

    });


<?php echo '</script'; ?>
><?php }
}
