<?php
/* Smarty version 3.1.30, created on 2024-07-26 14:45:07
  from "D:\pro\WebSiteYiXing\app\Admin\View\common\2footer.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66a345f3c5db03_68751332',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b14ea98e97ff2c5b8d8f7a4156630198a6ea1554' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\common\\2footer.html',
      1 => 1721703750,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_66a345f3c5db03_68751332 (Smarty_Internal_Template $_smarty_tpl) {
?>
<!--接上面-->
</div>

<!--START版本信息-->
<!--<div class="modal-footer jin-footer">-->
    <!--<span class="pull-right jin-footer-copyright">Copyright &copy; 2017 XuanQu Net. All rights reserved.</span>-->
    <!--<span class="pull-left label label-default jin-footer-version">XOA正式版 <?php echo VERSION;?>
</span>-->
<!--</div>-->
<!--END版本信息-->
</div>
<!--END主体内容区-->
</div>
<!--END菜单栏和内容区-->
</body>
</html>
<!--尾部END-->

<!-- |↓↓↓|以下两个为Bootstrap所需的JS文件 -->
<!-- 引入 bootstrap-table 样式 -->
<link href="<?php echo JS;?>
bootstrap-table-v1.22.5/bootstrap-table.min.css" rel="stylesheet">
<link href="<?php echo JS;?>
bootstrap-table-v1.22.5/bootstrap-table-custom.css" rel="stylesheet">
<?php echo '<script'; ?>
 src="<?php echo JS;?>
jquery-3.2.1.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
bootstrap.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
bootstrap-datetimepicker.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
bootstrap-datetimepicker.zh-CN.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
bootstrap-switch.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
select2.full.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
layer/layer.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
layui/layui.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
Sortable.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
jquery.cookie.js"><?php echo '</script'; ?>
>
<!-- 引入 bootstrap-table -->
<?php echo '<script'; ?>
 src="<?php echo JS;?>
bootstrap-table-v1.22.5/bootstrap-table.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
bootstrap-table-v1.22.5/bootstrap-table-zh-CN.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
jin.js<?php echo HTML_VERSION;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
jin-tableList.js<?php echo HTML_VERSION;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo JS;?>
jin-select.js<?php echo HTML_VERSION;?>
"><?php echo '</script'; ?>
>
<!-- 引入 bootstrap-table-custom -->
<?php echo '<script'; ?>
 src="<?php echo JS;?>
bootstrap-table-v1.22.5/bootstrap-table-custom.js"><?php echo '</script'; ?>
>
<!-- 自定义组件 -->
<?php echo '<script'; ?>
 src="<?php echo JS;?>
custom-module.js"><?php echo '</script'; ?>
>
<!-- 渠道多选插件 -->
<?php echo '<script'; ?>
 src="<?php echo JS;?>
bootstrap-select.min.js"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
 type="text/javascript">
	$('#g').selectpicker({});
    $('#s').selectpicker({});
    $('#s1').selectpicker({});
    $('#g1').selectpicker({});
    $('#g2').selectpicker({});

    $('.bs-select-all').text('全选');
    $('.bs-deselect-all').text('取消全选');
    $(".bs-actionsbox .btn-group").append('<button type="button" id="sure" class="btn btn-default">确认</button>');

    var model = "<?php echo $_smarty_tpl->tpl_vars['Mobel']->value;?>
";
    if (model == 'Mobel') {
        $("#group_server_5 input").focus(function(){
            document.activeElement.blur();
        });
        $("#group_server_5_mobel input").focus(function(){
            document.activeElement.blur();
        });
        $("#group_server_6 input").focus(function(){
            document.activeElement.blur();
        });
        $("#group_server_6_mobel input").focus(function(){
            document.activeElement.blur();
        });
    }

    //获取url参数的函数
    $.getUrlParam = function (name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return unescape(r[2]); return null;
    }

    var a = $.getUrlParam('a');
    var p = $.getUrlParam('p');
    var c = $.getUrlParam('c');
    var t = $.getUrlParam('type');

    //下拉框加入cookie缓存
    if (a != 'handForInterfaceData' && t != 'same') {
        $("button[data-id=g]").click(function() {
            if ($.cookie('cookie_g')) {
                //setTimeout(function(){
                    var cookie_g2 = eval('[' + $.cookie('cookie_g') + ']');
                    $('#g').selectpicker('val', cookie_g2);
                    $('#g').selectpicker('refresh');

                    obj33 = {id: '#s'};
                    obj33.url = "?p=Admin&c=Operation&a=server&jinIf=943";
                    obj33.gi = cookie_g2;
                    servers(obj33);
                //}, 800);
            }
        });
        $("button[data-id=s]").click(function() {
            if ($.cookie('cookie_s')) {
                var cookie_s2 = eval('[' + $.cookie('cookie_s') + ']');

                $('#s').selectpicker('val', cookie_s2);    
                $('#s').selectpicker('refresh');
            }
        }); 
    }

    $('#'+p+c+a).css({'background-color':'#009688'});
    
    layui.use('element', function(){
        var element = layui.element; //导航的hover效果、二级菜单等功能，需要依赖element模块
          
        //监听导航点击
        element.on('nav(demo)', function(elem){
            //console.log(elem)
            layer.msg(elem.text());
        });
    });
<?php echo '</script'; ?>
><?php }
}
