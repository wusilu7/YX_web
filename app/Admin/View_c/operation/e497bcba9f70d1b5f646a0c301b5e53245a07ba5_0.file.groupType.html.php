<?php
/* Smarty version 3.1.30, created on 2023-04-20 13:20:16
  from "/lnmp/www/app/Admin/View/operation/groupType.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6440cb90f17714_19125153',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e497bcba9f70d1b5f646a0c301b5e53245a07ba5' => 
    array (
      0 => '/lnmp/www/app/Admin/View/operation/groupType.html',
      1 => 1678771401,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_6440cb90f17714_19125153 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<style type="text/css">
	.group{
        display: -webkit-box;
        width: 500px;
        margin: auto;
    }
    .group_type{
        width: 200px;
        height: 300px;
    }
    .group_name{
        width: 300px;
        height: 1200px;
    }
    .group100{
        width: 100%
    }
    .input-group{
        margin-left: 10px
    }
    .has-success .input-group-addon{
        background-color: #f0ad4e;
        border-color: #f0ad4e;
    }
    .glyphicon-remove{
        color: red;
        cursor:pointer;
    }
</style>

<div class="jin-content-title"><span>渠道分类配置</span></div>

<div class="group100">
    <div class="group">
        <div class="group_type">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['group_type']->value, 'v');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['v']->value) {
?>
                <div>
                    <label>
                        <input type="radio" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
" name="radio" onclick="show_type_name(<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
)"> 
                        <span ><?php echo $_smarty_tpl->tpl_vars['v']->value['type_name'];?>
</span>&nbsp;
                    </label>
                    <span class="glyphicon glyphicon-remove" onclick="del_type_name(<?php echo $_smarty_tpl->tpl_vars['v']->value['id'];?>
, '<?php echo $_smarty_tpl->tpl_vars['v']->value['type_name'];?>
')"></span>
                </div>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

        </div>
        <div class="group_name">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['group_name']->value, 'v');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['v']->value) {
?>
                <div>
                    <label>
                        <input type="checkbox" value="<?php echo $_smarty_tpl->tpl_vars['v']->value['group_id'];?>
" name="checkbox" id="type_name_<?php echo $_smarty_tpl->tpl_vars['v']->value['group_id'];?>
"> <?php echo $_smarty_tpl->tpl_vars['v']->value['group_name'];?>

                    </label>
                </div>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

        </div>
        <div>
            <a id="jin_search" class="btn btn-warning">执行</a>
            <div class="form-group has-success has-feedback">
                <div class="input-group">
                    <span class="input-group-addon"><a href="javascript:void(0)" style="color:white" id="addtype">添加分类</a></span>
                    <input type="text" class="form-control" id="new_type" aria-describedby="inputGroupSuccess3Status" style="border-color: #f0ad4e;">
                </div>
            </div>
        </div>
    </div>
</div>

<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
	var data = {};

    $("#jin_search").click(function () {
        data.type_id = $("input[name='radio']:checked").val();

        var arr = new Array();
        $(".group_name :checkbox:checked").each(function(i){
            arr[i] = $(this).val();
        });
        var vals = arr.join(",");
        data.group_id = vals
            
        $.ajax({
            type: "post",
            url: location.href + '&jinIf=912',
            data: data,
            dataType: "json",
            success: function (json) {
            	layer.closeAll();
                if (json == 1) {
                	layer.msg('归类成功');
                } else {
                	layer.msg('归类失败');
                }
            }
        });
    });

    $("#addtype").click(function () {
        data.new_type = $('#new_type').val();
        $.ajax({
            type: "post",
            url: location.href + '&jinIf=914',
            data: data,
            dataType: "json",
            success: function (json) {
                if (json) {
                    window.location.href = location.href;
                }
            }
        });
    });

    var show_type_name = function (id) {
        data.id = id;

        $.ajax({
            type: "post",
            url: location.href + '&jinIf=913',
            data: data,
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']
                });
            },
            success: function (json) {
                layer.closeAll();

                $('input[name="checkbox"]').each(function(){
                    $(this).prop("checked", false);
                });
                for (var i = 0; i < json.length; i++) {
                    $('#type_name_'+json[i].group_id).prop('checked', 'checked');
                }
            }
        });
    }

    var del_type_name = function (del_id, del_name) {
        data.del_id = del_id;

        layer.confirm('确定要删除 "'+del_name+'" 这个分类吗？', {
            btn: ['确定', '手滑了'] //按钮
        }, function(){
            $.ajax({
                type: "post",
                url: location.href + '&jinIf=915',
                data: data,
                dataType: "json",
                success: function (json) {
                    window.location.href = location.href;
                }
            });
        }, function(){
          
        });
    }
<?php echo '</script'; ?>
><?php }
}
