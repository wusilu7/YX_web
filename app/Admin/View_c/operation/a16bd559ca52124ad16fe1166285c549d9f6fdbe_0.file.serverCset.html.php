<?php
/* Smarty version 3.1.30, created on 2024-04-24 18:13:05
  from "D:\pro\WebSiteYiXing\app\Admin\View\operation\serverCset.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6628db31081bb3_99309745',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a16bd559ca52124ad16fe1166285c549d9f6fdbe' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\operation\\serverCset.html',
      1 => 1704262932,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_6628db31081bb3_99309745 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.19.mailSend.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<style>
    .sinput{
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        margin-right:20px;
        margin-bottom:10px;
    }
</style>
<div class="jin-content-title"><span>服务器配置查询</span></div>

<hr/>
<div class="form-horizontal">
    <div class="form-group">
        <label for="template" class="col-sm-2 control-label">选择模板</label>
        <div class="col-sm-10">
            <select id="template">
            </select>
            <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
            <button style="margin:0 30px;" id="create" class="btn btn-primary">生成</button>
            <button id="created" class="btn btn-warning ">已生成配置查询</button>
            <button  id="insertctype" class="btn btn-warning ">新增分类</button>
            <button  id="deletectype" class="btn btn-danger ">删除分类</button>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-10 col-sm-offset-2">
            <input  maxlength="20" class="sinput col-sm-3" style="text-align: center" placeholder="数字参数" disabled/>
            <input  maxlength="20" class="sinput col-sm-3" style="text-align: center" placeholder="字符串参数" disabled/>
            <input  maxlength="20" class="sinput col-sm-3" style="text-align: center" placeholder="备注" disabled/>
            <input   style="text-align: center;width: 50px;" placeholder="注释" disabled/>
        </div>
    </div>
    <div id="tContent"></div>


</div>
<!--发送-->
<div class="center">

</div>

<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    template();
    function template() {
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=941",
            dataType: 'json',
            success: function (json) {
                var c='';
                for (var i=0;i<json.length;i++){
                    c+='<option value="'+json[i]+'">'+json[i]+'</option>'
                }
                $("#template").html(c);
            }
        });
    }
    //模板切换
    $("#jin_search").on('click', function () {
        var a='';
        if($("#template").val()=='ImportTool'){
            a='disabled'
        }
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=912",
            data: {
                typeid: $("#template").val()
            },
            dataType: 'json',
            success: function (json) {
                var c='';
                for (var i=0;i<json.length;i++){
                    c+=
                        '<div class="form-group Sconfig">'+
                        '<label  class="col-sm-2 control-label ">'+json[i].name+'</label>'+
                        '<div class="col-sm-10">'+
                        '<input  maxlength="20" value="'+json[i].value+'" class="sinput col-sm-3" placeholder=""/>'+
                        '<input  maxlength="20" value="'+json[i].strvalue+'"  class="sinput col-sm-3" placeholder="" '+a+'/>'+
                        '<input  maxlength="20" value="'+json[i].comment+'"  class="sinput col-sm-3" placeholder="" '+a+'/>'+
                        '<input  style="width: 20px;height: 20px;" type="checkbox"  value="1">'+
                        '</div> </div>';
                }
                $("#tContent").html(c);
            }
        });
    });


    $("#create").on('click', function () {
        var arr=[];
        $(".Sconfig").each(function(){
            var arr1=[];
            arr1.push($(this).find('label').html());
            arr1.push($(this).find('input').eq(0).val());
            arr1.push($(this).find('input').eq(1).val());
            arr1.push($(this).find('input').eq(2).val());
            if($(this).find('input').eq(3).is(':checked')){
                arr1.push(1);
            }else{
                arr1.push(0);
            }
            arr.push(arr1);
        });
        var h= '';
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=924",
            dataType: 'json',
            success: function (json) {
                for (var i=0;i<json.length;i++){
                    h += '<option value="'+json[i].id+'">'+json[i].type_name+'</option>'
                }
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '配置前缀',
                    area: ['350px', '250px'],
                    btn: ['确认', '取消'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content: '<div class="jin-child">' +
                    '<div class="input-group"><span class="input-group-addon">配置前缀名</span><input id="prefix" type="text" class="form-control"></div>' +
                    '<div class="input-group"><span class="input-group-addon">分类</span><select id="config_type" class="form-control">'+h+'</select></div>' +
                    '</div>',
                    yes: function (index) {
                        $.ajax({
                            type: "POST",
                            url: location.href + "&jinIf=913",
                            data: {
                                typeid: $("#template").val(),
                                typeprefix: $("#prefix").val(),
                                configdata: arr,
                                config_type: $("#config_type").val()
                            },
                            dataType: 'json',
                            beforeSend: function () {
                                layer.load(2, {
                                    shade: [0.3, '#fff']//0.3透明度的白色背景
                                });
                            },
                            success: function (json) {
                                layer.closeAll('loading');
                                if(json > 0){
                                    layer.close(index);
                                    layer.alert('生成成功', {icon: 1}, function (index) {
                                        layer.close(index);
                                    });
                                }else{
                                    layer.alert('已存在前缀', {icon: 0}, function (index) {
                                        layer.close(index);
                                    });
                                }
                            }
                        });
                    },
                    cancel: function () {
                    }
                });
            }
        });
    });

    $("#deletectype").click(function () {
        var h='';
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=924",
            dataType: 'json',
            success: function (json) {
                for (var i=0;i<json.length;i++){
                    h += '<option value="'+json[i].id+'">'+json[i].type_name+'</option>'
                }
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '配置前缀',
                    area: ['350px', '220px'],
                    btn: ['确认', '取消'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content: '<div class="jin-child">' +
                    '<div class="input-group"><span class="input-group-addon">分类</span><select id="config_type" class="form-control">'+h+'</select></div>' +
                    '</div>',
                    yes: function (index) {
                        $.ajax({
                            type: "POST",
                            url: location.href + "&jinIf=928",
                            data: {
                                config_type: $("#config_type").val()
                            },
                            dataType: 'json',
                            success: function (json) {
                                layer.close(index);
                                layer.alert('删除成功', {icon: 1}, function (index) {
                                    layer.close(index);
                                });

                            }
                        });
                    },
                    cancel: function () {
                    }
                });
            }
        });
    });

    $("#insertctype").click(function () {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '新增分类',
            area: ['350px', '220px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">分类名</span><input id="type_name" type="text" class="form-control"></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=926",
                    data: {
                        type_name: $("#type_name").val(),
                    },
                    dataType: 'json',
                    success: function (json) {
                        if(json ){
                            layer.close(index);
                            layer.alert('新增成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    });

    $("#created").on('click', function () {
        location.href += "&jinIf=914";
    })



<?php echo '</script'; ?>
>
<?php }
}
