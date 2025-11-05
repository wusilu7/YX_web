<?php
/* Smarty version 3.1.30, created on 2024-01-31 20:17:30
  from "/lnmp/www/app/Admin/View/player/banAccount.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65ba3a5a5ccd16_41905393',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'dc14fbfd6235c41a94760ef30628c505bec78425' => 
    array (
      0 => '/lnmp/www/app/Admin/View/player/banAccount.html',
      1 => 1678771402,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_65ba3a5a5ccd16_41905393 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>封禁玩家帐号</span></div>
<!--日期查询div-->
<div class="alert alert-info">
    <div id="group_server"></div>
</div>
<!--查询div-->
<hr/>
<div class="jin-search-div">
    <div>
        <input id="account" type="text" class="form-control jin-search-input" placeholder="帐号">
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
        <a id="ban_search" class="btn btn-info"><span class="glyphicon glyphicon-search">查看已被封禁账号</span></a>
    </div>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>帐号</th>
            <th>封号</th>
            <th>封号开始时间</th>
            <th>封号结束时间</th>
            <th>封号原因</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ①……
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 type="text/javascript">
    gsSelect('#group', '#server');
    var url = location.href + "&jinIf=912";
    var btn = ['<a><span class="glyphicon glyphicon-remove"></span></a>'];
    var arr = ['acc_name', btn, 'block_begin', 'block_time', 'block_reason'];
    var id = ["#content", "#page"];
    var data = {};

    $("#jin_search").on('click', function () {
        data.page = 1;
        data.ban = '';
        data.account = $("#account").val();
        tableList(url, data, id, arr)
    });

    $("#ban_search").on('click', function () {
        data.page = 1;
        data.account = $("#account").val();
        data.ban = 'yes';
        tableList(url, data, id, arr)
    });

    $('#content').on('click', 'span', function () {
        var account = $(this).on('click').parents('tr').find('td').eq(0).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '封禁帐号操作',
            area: ['350px', '230px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child center jin-block">' +
            '<div>' +
            '玩家帐号：' +
            account +
            '</div>' +
            '<div>' +
            '封禁时长：' +
            '<input id="time" type="text" placeholder="单位为天，不填表示解封">' +
            '</div>' +
            '<div>' +
            '封禁原因：' +
            '<select id="type">' +
            '<?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['b1']->value, 'v', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['v']->value) {
?>' +
            '<option value=<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
><?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</option>' +
            '<?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>
' +
            '</select>' +
            '</div>' +
            '</div>',
            yes: function (index) {
                //当点击‘确定’按钮的时候，获取弹出层返回的值
                var time = $('#time').val();
                var reason = $('#type').val();
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=921",
                    data: {
                        si: $("#server").val(),
                        account: account,
                        time: time,
                        reason: reason
                    },
                    dataType: 'json',
                    success: function (json) {
                        layer.close(index);
                        layer.alert("操作成功", function (index) {
                            layer.close(index);
                            data.page = 1;
                            data.account = $("#account").val();
                            contentList(url, data, id, arr)
                        });
                    }
                });
            },
            cancel: function () {
                //右上角关闭
            }
        });
    });
<?php echo '</script'; ?>
><?php }
}
