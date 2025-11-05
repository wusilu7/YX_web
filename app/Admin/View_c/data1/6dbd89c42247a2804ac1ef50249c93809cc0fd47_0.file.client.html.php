<?php
/* Smarty version 3.1.30, created on 2024-02-06 00:58:06
  from "/lnmp/www/app/Admin/View/data1/client.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65c1139e952e68_86724472',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6dbd89c42247a2804ac1ef50249c93809cc0fd47' => 
    array (
      0 => '/lnmp/www/app/Admin/View/data1/client.html',
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
function content_65c1139e952e68_86724472 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<style type="text/css">
    .alert-info{
        color: white;
    }
    .form-group{
        margin-bottom: 35px;
    }
</style>


<div class="jin-content-title"><span>Client</span></div>
<div class="alert alert-info">
    <div class="form-group" id="group_server_9"></div>
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
    </div><br>
    <div class="jin-search-div">
        <input size="16" type="text" id="client_id" class="form-control jin-datetime"
               placeholder="client_id">
        <input size="16" type="text" id="socket_id" class="form-control jin-datetime"
               placeholder="socket_id">
        <input size="16" type="text" id="account" class="form-control jin-datetime"
               placeholder="account">
        <input size="16" type="text" id="msg" class="form-control jin-datetime"
               placeholder="msg">
        <input size="16" type="text" id="arg0" class="form-control jin-datetime"
               placeholder="arg0">
        <input size="16" type="text" id="arg1" class="form-control jin-datetime"
               placeholder="arg1">
        <input size="16" type="text" id="arg2" class="form-control jin-datetime"
               placeholder="arg2">
        <input size="16" type="text" id="ip" class="form-control jin-datetime"
               placeholder="ip">
    </div><br>
    <div class="jin-search-div">
        <input size="16" type="text" id="char_guid" class="form-control jin-datetime"
               placeholder="char_guid">
        <input size="16" type="text" id="char_name" class="form-control jin-datetime"
               placeholder="char_name">
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
        <a id="jin_excel" class="btn btn-danger">保存到Excel</a>
    </div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead id="title">
        <tr>
            <th>log_id</th>
            <th>log_time</th>
            <th>client_ptr</th>
            <th>ptr_idx</th>
            <th>client_id</th>
            <th>socket_id</th>
            <th>account</th>
            <th>char_guid</th>
            <th>char_name</th>
            <th>ip</th>
            <th>port</th>
            <th>msg</th>
            <th>arg0</th>
            <th>arg1</th>
            <th>arg2</th>
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
 type="text/javascript">
    //gsSelect('#group', '#server', '', '');
    gsSelect3('#g', '', '#s');
    calendar('minute', '#time_start', '#time_end');
    var url = location.href + "&jinIf=912";
    var arr = ['log_id', 'log_time', 'client_ptr',  'ptr_idx', 'client_id', 'socket_id', 'account', 'char_guid', 'char_name', 'ip', 'port', 'msg', 'arg0', 'arg1', 'arg2'];
    var id = ["#content", "#page"];
    var data = {};
    function getDaily() {
        data.page       = 1;
        data.time_start = $("#time_start").val();
        data.time_end   = $("#time_end").val();
        data.client_id  = $("#client_id").val();
        data.socket_id  = $('#socket_id').val();
        data.account    = $('#account').val();
        data.char_guid  = $('#char_guid').val();
        data.char_name  = $('#char_name').val();
        data.msg  = $('#msg').val();
        data.arg0 = $('#arg0').val();
        data.arg1  = $('#arg1').val();
        data.arg2  = $('#arg2').val();
        data.ip  = $('#ip').val();
        data.si    = $('#s').val()[0];
        data.siArr    = $('#s').val();
        tableList(url, data, id, arr);
    }

    $("#jin_search").click(function () {
        getDaily();
    });

    $("#jin_excel").on('click', function () {
        data.page = 'excel';
        data.time_start = $("#time_start").val();
        data.time_end   = $("#time_end").val();
        data.client_id  = $("#client_id").val();
        data.socket_id  = $('#socket_id').val();
        data.account    = $('#account').val();
        data.char_guid  = $('#char_guid').val();
        data.char_name  = $('#char_name').val();
        data.msg  = $('#msg').val();
        data.arg0 = $('#arg0').val();
        data.arg1  = $('#arg1').val();
        data.arg2  = $('#arg2').val();
        data.si    = $('#server').val();
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
