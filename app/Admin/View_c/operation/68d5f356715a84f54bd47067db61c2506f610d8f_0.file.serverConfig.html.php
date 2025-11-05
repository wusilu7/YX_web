<?php
/* Smarty version 3.1.30, created on 2023-03-14 14:14:09
  from "/lnmp/www/app/Admin/View/operation/serverConfig.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_641010b120c9c6_60483762',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '68d5f356715a84f54bd47067db61c2506f610d8f' => 
    array (
      0 => '/lnmp/www/app/Admin/View/operation/serverConfig.html',
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
function content_641010b120c9c6_60483762 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.06.server.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>服务器配置</span></div>
<div class="jin-server-select">
    <lable>模板：</lable>
    <select  id="type" style="padding: 0 8px;"></select>
    <a data-type="insertConfig" class="btn btn-success ">新增配置选项</a>
    <form id="uploadForm" enctype="multipart/form-data" style="display: inline-block;">
        <input id="file" type="file" name="file"/>
    </form>
    <button id="upload" class='btn btn-primary'>生成数据</button>
    <!--<input type="checkbox" id="sort"/>-->
    <!--<button data-type="preserve" class="jin-hidden btn btn-sm btn-danger">保存排序</button>-->
    <!--<button data-type="insertCname" class="btn btn-primary right">新增配置名</button>-->
</div>
<div class="table-responsive">
    <table class="table table-bordered text-center">
        <thead>
        <tr>
            <th>编号</th>
            <th>配置名</th>
            <th>数字参数</th>
            <th>字符串参数</th>
            <th>备注</th>
            <th>注释</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div class="clearfix">

</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 type="text/javascript">
    function common() {
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=912",
            data:{
                type:$("#type").val()
            },
            dataType: 'json',
            success: function (json) {
                var c='';
                for (var i=0;i<json.length;i++){
                    if(json[i]['sign']==1){
                        json[i]['sign']='<div class="btn-group btn-group-sm"><a data-type="no_sign" class="btn btn-danger">取消标记</a></div> '
                        sty = 'color: red;'
                    }else{
                        json[i]['sign']='<div class="btn-group btn-group-sm"><a data-type="yes_sign" class="btn btn-success">标记</a></div> '
                        sty = '';
                    }
                    c+='<tr style="'+sty+'">' +
                        '<td>'+json[i]['id']+'</td>' +
                        '<td>'+json[i]['name']+'</td>' +
                        '<td>'+json[i]['value']+'</td>' +
                        '<td><div style="width: 200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">'+json[i]['strvalue']+'</div></td>' +
                        '<td>'+json[i]['comment']+'</td>' +
                        '<td>'+json[i]['annotation']+'</td>' +
                        '<td>' +
                            '<div class="btn-group btn-group-sm"><a data-type="updateConfig" class="btn btn-primary">修改</a></div>' +
                            '<div class="btn-group btn-group-sm"><a data-type="deleteConfig" class="btn btn-danger">删除</a></div>'+
                            json[i]['sign']+
                        '</td>' +
                        '</tr>';
                }
                $("#content").html(c);

            }
        });
    }
    $("#type").on('change', function () {
        common();
    });
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
                $("#type").html(c);
                common();
            }
        });
    }

    //新增
    $("a[data-type='insertConfig']").on('click', function () {
        var a='';
        if($("#type").val()=='ImportTool'){
            a='disabled'
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '新增',
            area: ['500px', '350px'],
            btn: ['新增', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">配置名</span><input id="configname" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">数字参数</span><input id="numValue" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">字符串参数</span><input id="strValue" type="text" class="form-control" '+a+'></div>' +
            '<div class="input-group"><span class="input-group-addon">备注</span><input id="comment" type="text" class="form-control" '+a+'></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=914',
                    data: {
                        type:$('#type').val(),
                        configname:$('#configname').val(),
                        numValue:$('#numValue').val(),
                        strValue:$('#strValue').val(),
                        comment:$('#comment').val()
                    },
                    success: function (res) {
                        layer.close(index);
                        layer.alert('添加成功！', {icon: 1}, function (index) {
                            layer.close(index);
                            common();
                        });
                    }
                });
            },
            cancel: function () {
            }
        })
    });

    $('#content').on('click', 'a[data-type="deleteConfig"]', function () {
        var id = $(this).parents('tr').find('td').eq(0).text();
        layer.alert('确认删除？', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=915",
                data: {
                    id: id
                },
                dataType: "json",
                success: function (json) {
                    layer.alert('删除成功', {icon: 1}, function (index) {
                        layer.close(index);
                        common();
                    });
                }
            });
        });
    }).on('click', 'a[data-type="updateConfig"]', function () {
        var a='';
        if($("#type").val()=='ImportTool'){
            a='disabled'
        }
        var id = $(this).parents('tr').find('td').eq(0).text();
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=917",
            data: {
                id: id
            },
            dataType: "json",
            success: function (json) {
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '服务器配置修改',
                    area: ['400px', '400px'],
                    btn: ['修改', '取消'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content: '<div class="jin-child">' +
                    '<div class="input-group"><span class="input-group-addon">配置名</span><input id="configname" type="text" class="form-control" value="'+json.name+'"></div>' +
                    '<div class="input-group"><span class="input-group-addon">数字参数</span><input id="numValue" type="text" class="form-control" value="'+json.value+'"></div>' +
                    '<div class="input-group"><span class="input-group-addon">字符串参数</span><input id="strValue" type="text" class="form-control" value="'+json.strvalue+'" '+a+'></div>' +
                    '<div class="input-group"><span class="input-group-addon">备注</span><input id="comment" type="text" class="form-control" value="'+json.comment+'" '+a+'></div>' +
                    '</div>',
                    yes: function (index) {
                        $.ajax({
                            type: "POST",
                            url: location.href + '&jinIf=916',
                            data: {
                                id:id,
                                configname:$('#configname').val(),
                                numValue:$('#numValue').val(),
                                strValue:$('#strValue').val(),
                                comment:$('#comment').val(),
                            },
                            success: function () {
                                layer.close(index);
                                layer.alert('修改成功', {icon: 1}, function (index) {
                                    layer.close(index);
                                    common();
                                });
                            }
                        });
                    },
                    cancel: function () {
                    }
                })
            }
        });
    }).on('click', 'span[data-type="no"]', function () {  // 点击后在游戏服务器列表中显示服务器
        var id = $(this).parents('tr').find('td').eq(0).text();
        layer.alert('确认取消注释？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=918",
                data: {
                    id: id,
                    annotation:1
                },
                dataType: 'json',
                success: function () {
                    layer.alert('修改成功', {icon: 1}, function (index) {
                        layer.close(index);
                        common();
                    });
                }
            });
        });
    }).on('click', 'span[data-type="yes"]', function () {  // 点击后在游戏服务器列表中隐藏服务器
        var id = $(this).parents('tr').find('td').eq(0).text();
        layer.alert('确认注释？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=918",
                data: {
                    id: id,
                    annotation:0
                },
                dataType: 'json',
                success: function () {
                    layer.alert('修改成功', {icon: 1}, function (index) {
                        layer.close(index);
                        common();
                    });
                }
            });
        });
    }).on('click', 'a[data-type="yes_sign"]', function () {  // 点击后在游戏服务器列表中隐藏服务器
        var id = $(this).parents('tr').find('td').eq(0).text();
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=919",
            data: {
                id: id,
                sign:1
            },
            dataType: 'json',
            success: function () {
                common();
            }
        });
    }).on('click', 'a[data-type="no_sign"]', function () {  // 点击后在游戏服务器列表中隐藏服务器
        var id = $(this).parents('tr').find('td').eq(0).text();
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=919",
            data: {
                id: id,
                sign:0
            },
            dataType: 'json',
            success: function () {
                common();
            }
        });
    })

    //服务器排序
    $('#sort').bootstrapSwitch({
        onText: "排序开启",
        offText: "排序关闭",
        onColor: "success",
        offColor: "default",
        size: "small",
        onSwitchChange: function (event, state) {
            var list = document.getElementById('content');
            var sort = Sortable.create(list, {animation: 160});
            if (state === true) {
                $('button[data-type="preserve"]').removeClass('jin-hidden');
            } else {
                $('button[data-type="preserve"]').addClass('jin-hidden');
                sort.destroy(list);
                common();
            }
        }
    });

    $('button[data-type="preserve"]').on('click', function () {//保存排序
        var id_list = '';
        $('#content').find('tr').each(function () {
            id_list += $(this).children().eq(0).text() + ',';
        });
        layer.alert('确定保存这个顺序', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9136",
                data: {id_list: id_list},
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function () {
                    layer.closeAll('loading');
                    layer.alert('顺序保存成功', {icon: 1}, function (index) {
                        layer.close(index);
                        $('#sort').bootstrapSwitch('toggleState');
                        common();
                    });
                }
            });
        });
    })

    $("#upload").click(function () {
        var formData = new FormData($('#uploadForm')[0]);
        layer.alert('确认上传吗？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: 'post',
                url: location.href + '&jinIf=942',
                data: formData,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success:function (json) {
                    layer.closeAll('loading');
                    if(json.status==1){
                        layer.alert(json.msg, {icon: 1}, function (index1) {
                            layer.close(index1);
                            $("#file").val('');
                        });
                    }else{
                        layer.alert(json.msg, {icon: 0}, function (index1) {
                            layer.close(index1);
                        });
                    }

                }
            })
        });
    });


<?php echo '</script'; ?>
>
<?php }
}
