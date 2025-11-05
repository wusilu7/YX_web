<?php
/* Smarty version 3.1.30, created on 2024-10-18 15:39:40
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\mb\mailQuery.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_671210bc9d0fa6_77742967',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'd438e4fb7ee27d386579eb501035fcc3b7daf23c' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\mb\\mailQuery.html',
      1 => 1723704876,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_671210bc9d0fa6_77742967 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->

<div class="jin-content-title"><span>普通邮件查询</span></div>
<div class="alert alert-info">
    <div id="group_server"></div>
</div>
<input type="text" id="role_value" class="form-control" placeholder="角色名或ID查询" style="width: 10%; display: inline-block;">
<a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
<!--<button id='s_all' class='btn btn-primary'>显示所有(不指定服务器)</button>-->
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>编号</th>
            <th>服务器</th>
            <th>收件人</th>
            <th>邮件标题</th>
            <th>邮件内容</th>
            <th>货币</th>
            <th>道具</th>
            <th>经验</th>
            <th>创建时间</th>
            <th>创建人</th>
            <th>审核时间</th>
            <th>审核人</th>
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
    gsSelect('#group', '#server', '#platform', jsonQuery);
    var url = "/?p=Admin&c=Mb&a=mailQuery&jinIf=912";
    var receiver = function (json) {
        return '【' + json.receiver_type + '】' + json.receiver;
    };
    var arr = ['mail_id','si', receiver, 'title', 'content', 'money', 'item','exp', 'ct', 'cu', 'at', 'au'];
    var id = ["#content", "#page"];
    var data = {
    };
    function jsonQuery() {
        data.page=1;
        data.si=$("#server").val();
        data.role_value = $("#role_value").val();
        tableList(url, data, id, arr)
    }
    $("#jin_search").on('click', function () {
        jsonQuery();
    });

    $('#s_all').on('click',function () {
        data.page=1;
        data.si='';
        tableList(url, data, id, arr)
    });
<?php echo '</script'; ?>
>
<?php }
}
