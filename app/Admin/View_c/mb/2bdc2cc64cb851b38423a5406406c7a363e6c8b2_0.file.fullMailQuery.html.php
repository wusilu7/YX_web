<?php
/* Smarty version 3.1.30, created on 2023-03-22 13:47:41
  from "/lnmp/www/app/Admin/View/mb/fullMailQuery.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_641a967d77aff8_09297544',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2bdc2cc64cb851b38423a5406406c7a363e6c8b2' => 
    array (
      0 => '/lnmp/www/app/Admin/View/mb/fullMailQuery.html',
      1 => 1678771400,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_641a967d77aff8_09297544 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>全服邮件查询</span></div>
<div class="alert alert-info">
    <div id="group_only"></div>
</div>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>编号</th>
            <th>发送服务器</th>
            <th>全服邮件ID</th>
            <th>邮件标题</th>
            <th>邮件内容</th>
            <th>货币</th>
            <th>道具</th>
            <th>经验</th>
            <th>额外信息</th>
            <th>创建时间</th>
            <th>创建人</th>
            <th>审核时间</th>
            <th>审核人</th>
            <th>撤回</th>
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
    groupSelect();
    $("#group").on('change', function () {
        jsonQuery();
    });
    function jsonQuery() {
        var url = location.href + "&jinIf=912";
        var btn = [
            "<button data-type='c' class='btn btn-sm btn-danger'>撤回</button>"
        ];
        var arr = ['mail_id', 'si', 'full_id', 'title', 'content', 'money', 'item','exp', 'full_info', 'ct', 'cu', 'at', 'au', btn];
        var id = ["#content", "#page"];
        var data = {
            page: 1,
            gi: $("#group").val()
        };
        $(document).ready(tableList(url, data, id, arr));
    }
    //全服邮件撤回
    $('#content').on('click', 'button[data-type="c"]', function () {
        var mail_id = $(this).parents('tr').find('td').eq(0).text();
        layer.alert('确定撤回 <b>' + mail_id + '号邮件</b>？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=931",
                data: {
                    mail_id: mail_id
                },
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function () {
                    layer.closeAll('loading');
                    layer.alert('<b>' + mail_id + '号邮件</b> 已撤回', {icon: 1}, function (index) {
                        layer.close(index);
                        jsonQuery();
                    });
                }
            });
        });
    })
<?php echo '</script'; ?>
><?php }
}
