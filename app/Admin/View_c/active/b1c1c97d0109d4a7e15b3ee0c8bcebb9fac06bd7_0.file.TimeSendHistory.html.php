<?php
/* Smarty version 3.1.30, created on 2024-01-31 17:12:27
  from "/lnmp/www/app/Admin/View/active/TimeSendHistory.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65ba0efba0f482_81100533',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b1c1c97d0109d4a7e15b3ee0c8bcebb9fac06bd7' => 
    array (
      0 => '/lnmp/www/app/Admin/View/active/TimeSendHistory.html',
      1 => 1678771397,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_65ba0efba0f482_81100533 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<div class="jin-content-title"><span>定时应用记录</span></div>
<div class="alert alert-info">
     <div id="group_only" style="display: inline-block;" class="hide"></div>
    <select  id="status" style="padding: 5px;">
        <option value="0">未完成</option>
        <option value="1">已完成</option>
    </select>
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>编号</th>
            <th>定时时间</th>
            <th>应用服务器</th>
            <th>活动名</th>
            <th>活动标识</th>
            <th>活动ID</th>
            <th>类型</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>

<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
    groupSelect({dom: "#group"});
    var url = location.href + "&jinIf=912";
    var btn = [
        '<a data-type="delete" class="btn btn-danger">删除</a>'
    ];
    var arr = ['timing_id','time','sis','tb_path','sign','ids','is_add',btn];
    var id = "#content";
    var data = {};
    // 普通查询
    $("#jin_search").on('click', function () {
        common();
    });
    function common() {
        data.status = $("#status").val();
        data.gi=$("#group").val();
        noPageContentList(url, data, id, arr);
    }
    $('#content').on('click', 'a[data-type="delete"]', function() {
        var id = $(this).parents('tr').find('td').eq(0).text();
        layer.alert('确认删除？', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=914",
                data: {
                    id: id
                },
                dataType: "json",
                success: function (json) {
                    layer.alert('删除成功', {icon: 1}, function (index) {
                        layer.close(index);
                    });
                    common();
                }
            });
        });

    })
<?php echo '</script'; ?>
><?php }
}
