<?php
/* Smarty version 3.1.30, created on 2023-05-24 09:53:14
  from "/lnmp/www/app/Admin/View/active/GiftTest.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_646d6e0aedf7d3_03115758',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '04b681047277042ad1cd5d0f3b550c1ad8adfe2b' => 
    array (
      0 => '/lnmp/www/app/Admin/View/active/GiftTest.html',
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
function content_646d6e0aedf7d3_03115758 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<style type="text/css">
	.center{
		display:inline-block
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
        <span class="chargemoney">充值类型</span>
        <select id="charge_type">
	        <option value="1">付费礼包</option>
            <option value="0">精准礼包</option>
            <option value="2">时装</option>
	    </select>
        <span class="chargemoney">充值档位</span>
        <select id="charge_money">
        </select>
        <span class="chargemoney">额外参数</span>
        <input type="text"  id="other_param" >
        <input size="16" type="checkbox" id="ischeck" value="1">
        <label for="ischeck" style="margin-left: 0px;">忽略校验次数</label>
        <a id="send" class="btn btn-success center">充值</a>
	</div>
</div>

<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
    gsSelect('#group', '#server', '#platform',selectMoneyType);
    var data = {};
    function selectMoneyType() {
        $.ajax({
            type: "POST",
            url: location + "&jinIf=913",
            data: {
                gi:$("#group").val(),
                si:$("#server").val(),
                charge_type:$("#charge_type").val()
            },
            dataType: "json",
            success: function (json) {

                var cc= '';
                for (var i=0;i<json.length;i++){
                    cc+='<option value="'+json[i].Price+'|'+json[i].ResetType+'|'+json[i].GiftID+'">'+json[i].GiftName+'('+json[i].Price+')</option>';
                }
//                if($("#charge_type").val()==0){
//                    var price = [15,30,50,100,175,30,40,50,120,210,210,210];
//                    var gift_name = [
//                        '1阶神话宝箱',
//                        '2阶神话宝箱',
//                        '3阶神话宝箱',
//                        '4阶神话宝箱',
//                        '5阶神话宝箱',
//                        '遗忘之心宝箱',
//                        '炽烈硫磺宝箱',
//                        '远古之魂宝箱',
//                        '魔暴龙（神话）',
//                        '雷神之拳（神话）',
//                        '亡魂（神话）',
//                        '先祖之矛'
//                    ];
//                    for (var i=50;i<62;i++){
//                        cc+='<option value="'+price[(i-50)]+'|0|'+i+'">'+(i+1)+'-'+gift_name[(i-50)]+'</option>';
//                    }
//                }
                $("#charge_money").html(cc)
            }
        });
    }
    $("#charge_type").on('change', function () {
        selectMoneyType();
    });

    $("#send").on('click', function () {
        data.charge_money = $("#charge_money").val();
        data.charge_type = $("#charge_type").val();
        data.role_type = $("#role_type").val();
        data.charge_role = $("#charge_role").val();
        data.group = $("#group").val();
        data.pi = $("#platform").val();
        data.si = $("#server").val();
        data.other_param = $("#other_param").val();
        data.ischeck       = $('#ischeck').is(':checked') ? $('#ischeck').val() : 0;


        $.ajax({
            type: "POST",
            url: location + "&jinIf=912",
            data: data,
            dataType: "json",
            success: function (json) {
                if (json == 1) {
                    layer.alert('充值成功');
                } else if (json == 2) {
                    layer.alert('充值失败, 该服没有此游戏角色');
                } else {
                    layer.alert('充值失败！信息:'+json);
                }
            },
            error: function () {
                layer.closeAll('loading');
                layer.msg('充值失败！');
            }
        });
    });
<?php echo '</script'; ?>
><?php }
}
