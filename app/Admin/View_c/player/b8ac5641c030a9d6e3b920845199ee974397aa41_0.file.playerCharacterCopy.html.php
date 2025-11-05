<?php
/* Smarty version 3.1.30, created on 2023-04-05 11:37:21
  from "/lnmp/www/app/Admin/View/player/playerCharacterCopy.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_642cecf1f3f996_20499817',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b8ac5641c030a9d6e3b920845199ee974397aa41' => 
    array (
      0 => '/lnmp/www/app/Admin/View/player/playerCharacterCopy.html',
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
function content_642cecf1f3f996_20499817 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<style type="text/css">
    .same{
        width: 90px;
        margin: auto;
        margin-top: 20px;
        font-weight: bold;    
    }
</style>
<div class="jin-content-title"><span>角色信息复制</span></div>

<table class="table table-striped text-center">
    <thead>
    <tr>
    	<th>帐号</th>
        <th>渠道</th>
        <th>平台</th>
        <th>角色ID</th>  
        <th>角色名</th>
        <th>等级</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody id="content"></tbody>
</table>

<div class="alert alert-info">
    <div id="group_server_2"></div>
</div>
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 type="text/javascript">
	gsSelect('#group', '#server', '');

    var devicetype = function (json) {
        if (json.devicetype == 8) {
            return 'ios(' + json.devicetype + ')';
        } else if (json.devicetype == 11) {
            return 'android(' + json.devicetype + ')';
        }
    }
    var btn = function (json) {
        var c = '<div class="btn-group btn-group-sm">' +
        '<a data-type="copy" class="btn btn-success">复制到以下渠道服务器下</a>' + 
        '</div>';
        
        return c;
    }
	var data = {};
	data.char_id = $.getUrlParam('char_id');
	var url = '/?p=Admin&c=Player&a=playerCharacter&jinIf=914';
	var arr = ['acc_name', 'group_name', devicetype, 'char_id', 'char_name', 'level', 'create_time', btn];
    var id = "#content";
    noPageContentList(url, data, id, arr);

	$('#content').on('click', 'a[data-type="copy"]', function () { 
        var gid = $('#group').val();
		var sid = $('#server').val();
        var char_id = $(this).parents('tr').find('td').eq(3).text();
        var sid_old = $.getUrlParam('sid');

		if (sid != '') {
			layer.open({
	            type: 1,
	            closeBtn: 2,
	            title: '角色信息复制',
	            area: ['400px', '170px'],
	            btn: ['确定', '取消'],
	            btnAlign: 'c',
	            content: '<div class="same">确定复制吗？</div>',
	            yes: function (res) {
	                $.ajax({
	                    type: "POST",
	                    url: '/?p=Admin&c=Player&a=playerCharacter&jinIf=915',
	                    data: {
                            gid: gid,
	                        sid: sid,
                            sid_old: sid_old,
	                        char_id: char_id
	                    },
	                    dataType: 'json',
	                    success: function (res) {
	                        if (res == 1) {
	                            layer.closeAll();
	                            layer.alert('复制成功', {icon: 1});
                                noPageContentList(url, data, id, arr);
	                        } else {
	                            layer.msg('复制失败', {icon: 2});
	                        }
	                    }
	                });
	            },
	            cancel: function () {
	            }
	        })
		} else {
			layer.alert('请选择服务器！');
		} 
    });
<?php echo '</script'; ?>
><?php }
}
