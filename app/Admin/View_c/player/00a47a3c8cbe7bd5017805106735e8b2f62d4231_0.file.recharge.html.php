<?php
/* Smarty version 3.1.30, created on 2023-04-23 11:27:02
  from "/lnmp/www/app/Admin/View/player/recharge.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6444a586e2caa3_42898679',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '00a47a3c8cbe7bd5017805106735e8b2f62d4231' => 
    array (
      0 => '/lnmp/www/app/Admin/View/player/recharge.html',
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
function content_6444a586e2caa3_42898679 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<style type="text/css">
	.center{
		display:inline-block
	}
	.chargemoney{
		margin-left: 50px;
    	font-size: 17px;
	}
</style>
<div class="alert alert-info">
    <div id="group_server"></div>
</div>

<div class="form-horizontal col-sm-8 col-sm-offset-2">
    <div class="form-group">
        <select id="role_type">
            <option value="1">角色名</option>
            <option value="2">角色ID</option>
        </select>
        <input type="text" class="form-control" id="charge_role" required style="width:20%;display:inline-block">
        <span class="chargemoney">充值档位</span>
        <select id="charge_money">
	        <option value=""></option>
	    </select>
        <a id="send" class="btn btn-success center">充值</a>
	</div>
</div>

<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
    gsSelect('#group', '#server', '#platform');
    $('#group').on('change', function () { //渠道改变的时候
        if(eval('<?php echo $_smarty_tpl->tpl_vars['wbGroup']->value;?>
').indexOf($("#group").val())>=0){
            $("#charge_money").html('');
            chargeMoneySelect1();
        }else {
            $("#charge_money").html('');
            chargeMoneySelect();
        }
    });

    var data = {};

    $("#send").on('click', function () {
        data.charge_money = $("#charge_money").val();
        data.role_type = $("#role_type").val();
        data.charge_role = $("#charge_role").val();
        data.group = $("#group").val();
        data.pi = $("#platform").val();
        data.si = $("#server").val();

        if (data.charge_money && data.charge_role) {
	        $.ajax({
	            type: "POST",
	            url: location + "&jinIf=912",
	            data: data,
	            dataType: "json",
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
	            success: function (json) {
                    layer.closeAll('loading');
	            	if (json == 1) {
	            		layer.alert('充值成功, 请等待审核');
	            	} else if (json == 2) {
	            		layer.alert('充值失败, 该服没有此游戏角色');
	            	} else {
	            		layer.alert('充值失败！');
	            	}
	            },
	            error: function () {
	                layer.closeAll('loading');
	                layer.msg('充值失败！');
	            }
	        });
	    } else {
	    	layer.alert('请完整填写角色名/角色ID , 充值档位!');
	    }
    });
<?php echo '</script'; ?>
><?php }
}
