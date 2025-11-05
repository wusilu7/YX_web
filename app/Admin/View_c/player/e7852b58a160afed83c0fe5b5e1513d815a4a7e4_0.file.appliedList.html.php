<?php
/* Smarty version 3.1.30, created on 2024-08-31 14:23:05
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\player\appliedList.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66d2b6c90bc756_55561117',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e7852b58a160afed83c0fe5b5e1513d815a4a7e4' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\player\\appliedList.html',
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
function content_66d2b6c90bc756_55561117 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<input id="role" class="form-control jin-search-input" placeholder="角色名或角色ID">
<a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
      		<th>编号</th>
            <th>服务器</th>
            <th>申请人</th>
            <th>充值对象</th>
            <th>订单号</th>
            <th>充值金额</th>
            <th>审核人</th>
            <th>充值时间</th>
            <th>状态</th>
            <th>类型</th>
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
    var url = location.href + "&jinIf=912";
    var id = ["#content", "#page"];
    var data = {};

    var btn = [
        "已审核"
    ];
    var arr = ['id', 'si', 'apply_name', 'charge_role', 'order', 'charge_money','apply_name1',  'charge_time', btn,'type'];
    $(function () {
        jsonAudit();
    })

    $("#jin_search").on('click', function () {
        jsonAudit();
    });


    //刷新数据
    function jsonAudit() {
        data.page = 1;
        data.role = $("#role").val();
	    tableList(url, data, id, arr);
    }
<?php echo '</script'; ?>
>
<?php }
}
