<?php
/* Smarty version 3.1.30, created on 2024-02-29 19:41:55
  from "/lnmp/www/app/Admin/View/player/history.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65e06d832ed320_76476413',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'f9eeaec49494ac462a5a173ad9eb131ddafafcc8' => 
    array (
      0 => '/lnmp/www/app/Admin/View/player/history.html',
      1 => 1709205013,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_65e06d832ed320_76476413 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>历史反馈</span></div>
<div class="alert alert-info">
    <div id="group_server_2"></div>
</div>
<!--查询区-->
<hr/>
<input id="feedback" maxlength="20" type="text" class="form-control jin-search-input" placeholder="问题">
<a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
<a href="javascript:history.go(-1)">返回反馈列表</a>
<hr/>
<!--数据区-->
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>反馈ID</th>
            <th>角色ID</th>
            <th>问题</th>
            <th>回复</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ① 查询框留空表示查询当前服全部角色信息；
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 type="text/javascript">
    gsSelect('#group', '#server', '', getHistory);
    var url = location.href + "&jinIf=912";
    var feedback = function (json) {
        var fb_content = '提问时间：' + json.feedback_time + '<br />问题：' + json.content;
        return fb_content;
    }
    var reply = function (json) {
        var r_content = '回复时间：' + json.reply_time + '<br />回复：' + json.reply_content;
        return r_content;
    }
    var arr = ['id', 'char_id', feedback, reply];
    var id = ["#content", "#page"];
    var data = {};
    function getHistory() {
        data.page = 1;
        data.si = $("#server").val();
        data.char_id = <?php echo GET('char_id');?>
;
        tableList(url, data, id, arr)
    }
    $("#jin_search").on('click', function () {
        data.feedback = $("#feedback").val();
        getHistory()
    });
<?php echo '</script'; ?>
>

<?php }
}
