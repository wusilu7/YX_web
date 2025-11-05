<?php
/* Smarty version 3.1.30, created on 2023-08-10 14:22:14
  from "/lnmp/www/app/Admin/View/operation/serverCselect.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_64d482166b92d3_23488975',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ca6c5d205602fc126752b5373838d9be67b10348' => 
    array (
      0 => '/lnmp/www/app/Admin/View/operation/serverCselect.html',
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
function content_64d482166b92d3_23488975 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.06.server.css" rel="stylesheet">
<link href="<?php echo JS;?>
layui-v2.5.5/css/layui.css" rel="stylesheet">
<style>
    .select{
        height: 30px;
        font-size: 16px;
        padding: 0px 12px;
    }
    .my-body {
        position: fixed;
        top: 10px;
        bottom: 0;
        left: 400px;
        margin-top: 110px;
        right: 0;
    }
</style>
<div id="id-tree-server" style="width: 200px;"></div>
<div class="my-body" style="overflow: scroll;" >
    <!--|↓↓↓↓↓↓|-->
    <div class="jin-server-select">&nbsp;
        <input id="id-prefix" type="text" style="width: 140px;" class="form-control jin-search-input" disabled/>
        类型：
        <select  id="type" class="select"></select>
        &nbsp;
        <button id="insertConfig" class="btn btn-primary">新增配置项</button>
        &nbsp;
        <button id="ExcelConfig" class="btn btn-primary">Excel导出配置项</button>
        &nbsp;
        <button id="deleteAll" class="btn btn-danger pull-right" style=" margin-left: 10px;">全部删除(组名)</button>
        <button id="deleteAll1" class="btn btn-danger pull-right">全部删除(节点)</button>
        &nbsp;
        <button id="copy" class="btn btn-success">复制</button>
        &nbsp;
        <button id="changeType" class="btn btn-success">修改分类</button>
        &nbsp;
        <button id="txt" class="btn btn-success">TXT下载</button>
        &nbsp;
        <button id="checkout" class="btn btn-success">校验</button>
        <input type="checkbox" id="sort"/>
        <button data-type="preserve" class="jin-hidden btn btn-sm btn-danger">保存排序</button>
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
</div>


<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 src="<?php echo JS;?>
layui-v2.5.5/layui.all.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
    var tree = layui.tree;
    var prefix_val = '';
    var tree_server = eval('<?php echo $_smarty_tpl->tpl_vars['tree_server']->value;?>
');
    tree.render({
        elem: '#id-tree-server'  //绑定元素
        ,onlyIconControl: true
        , data: tree_server
        ,click: function(obj){
            console.log(obj)
            prefix_val = obj.data.prefix;
            console.log("prefix_val", prefix_val);
            //父节点不重新加载表格
            if (obj.data.pid == 1){
                return
            }
            $("#id-prefix").val(prefix_val);
            prefixonload();
        }
    });


    var btn = [
        '<div class="btn-group btn-group-sm"><a data-type="deleteConfig" class="btn btn-danger">删除</a></div> '
    ];

    function common() {
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=916",
            data: {
                type: $("#type").val(),
                prefix:prefix_val

            },
            dataType: 'json',
            success: function (json) {
                var c='';
                for (var i=0;i<json.length;i++){
                    if(json[i]['sign']==1){
                        sty = 'background: #286090;'
                    }else{
                        sty = '';
                    }
                    c+='<tr style="'+sty+'">' +
                        '<td>'+json[i].id+'</td>' +
                        '<td>'+json[i].name+'</td>' +
                        '<td><input type="text" value="'+json[i].value+'" class="form-control" disabled></td>' +
                        '<td><input type="text" value="'+json[i].strvalue+'" class="form-control" disabled></td>' +
                        '<td><input type="text" value="'+json[i].comment+'" class="form-control" disabled></td>' +
                        '<td>'+json[i].is_annotation+'</td>' +
                        '<td>'+btn+'</td>' +
                        '</tr>'
                }
                $("#content").html(c);
            }
        });
    }

    $("#type").change(function () {
        common();
    });

    $("#insertConfig").click(function () {
        var a='';
        if($("#type").val()=='ImportTool'){
            a='disabled'
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '服务器配置修改',
            area: ['350px', '400px'],
            btn: ['修改', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">配置名</span><input id="configname" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">数字参数</span><input id="numValue" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">字符串参数</span><input id="strValue" type="text" class="form-control" '+a+'></div>' +
            '<div class="input-group"><span class="input-group-addon">备注</span><input id="comment" type="text" class="form-control" '+a+'></div>' +
            '<div class="input-group"><span class="input-group-addon">注释</span><input id="annotation" type="text" class="form-control"></div>' +
            '<div class=""><span style="color: red">注：</span>1注释,0不注释</div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=920',
                    data: {
                        type : $("#type").val(),
                        prefix : prefix_val,
                        configname:$('#configname').val(),
                        numValue:$('#numValue').val(),
                        strValue:$('#strValue').val(),
                        comment:$('#comment').val(),
                        annotation:$('#annotation').val()
                    },
                    success: function (json) {
                        if(json){
                            layer.close(index);
                            layer.alert('新增成功', {icon: 1}, function (index) {
                                layer.close(index);
                                common();
                            });
                        }
                    }
                });
            },
            cancel: function () {
            }
        })
    });

    $("#deleteAll").click(function () {
        layer.alert('确认删除该分类下所有？', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=921",
                data: {
                    type : $("#type").val(),
                    prefix :prefix_val
                },
                dataType: "json",
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
                    if(json){
                        layer.alert('删除成功', {icon: 1}, function (index) {
                            layer.close(index);
                            location.reload();
                        });
                    }
                }
            });
        });
    });

    $("#deleteAll1").click(function () {
        layer.alert('确认删除该节点下所有？', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9211",
                data: {
                    type : $("#type").val(),
                    prefix :prefix_val
                },
                dataType: "json",
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
                    if(json){
                        layer.alert('删除成功', {icon: 1}, function (index) {
                            layer.close(index);
                            location.reload();
                        });
                    }
                }
            });
        });
    });

    $("#txt").click(function () {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '配置前缀',
            area: ['250px', '160px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">编码格式</span>' +
            '<select name="" id="charset" style="height: 30px; width: 100px;">' +
            '<option value="ansi">ansi</option>' +
            '<option value="unicode">unicode</option>' +
            '<option value="utf8">UTF-8</option>' +
            '</select></div></div>',
            yes: function (index) {
                layer.close(index);
                var downarr=[];
                $("#type").find("option").each(function () {
                    downarr.push($(this).html());
                });
                download(downarr,prefix_val,$("#charset").val())
            },
            cancel: function () {
            }
        });
    });

    $("#checkout").click(function () {
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=929",
            data: {
                prefix:prefix_val
            },
            dataType: 'json',
            success: function (json) {
                if(json){
                    layer.alert('校验成功', {icon: 1}, function (index) {
                        layer.close(index);
                        return false;
                    });
                }else{
                    layer.alert('校验失败', {icon: 2}, function (index) {
                        layer.close(index);
                        return false;
                    });
                }

            }
        });
    });

    $("#copy").click(function () {
        var h='';
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=924",
            dataType: 'json',
            success: function (json) {
                for (var i=0;i<json.length;i++){
                    h += '<option value="'+json[i].id+'$$'+json[i].type_name+'">'+json[i].type_name+'</option>'
                }
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '复制到该分类下',
                    area: ['350px', '220px'],
                    btn: ['确认', '取消'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content: '<div class="jin-child">' +
                    '<div class="input-group"><span class="input-group-addon">分类</span><select id="newprefix" class="form-control">'+h+'</select></div>' +
                    '</div>',
                    yes: function (index) {
                        layer.close(index);
                        $.ajax({
                            type: "POST",
                            url: location.href + "&jinIf=923",
                            data: {
                                prefix : prefix_val,
                                newprefix : $("#newprefix").val()
                            },
                            dataType: "json",
                            success: function (json) {
                                if(json){
                                    layer.alert('复制成功', {icon: 1}, function (index) {
                                        layer.close(index);
                                        location.reload();
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

    $("#changeType").click(function () {
        var h= '';
        $.ajax({
            type: "POST",
            url: "?p=Admin&c=Operation&a=serverCset&jinIf=924",
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
                    '<div class="input-group"><span class="input-group-addon">分类</span>' +
                    '<select id="config_type" class="form-control">'+h+'</select></div>' +
                    '</div>',
                    yes: function (index) {
                        $.ajax({
                            type: "POST",
                            url: "?p=Admin&c=Operation&a=serverCset&jinIf=925",
                            data: {
                                prefix :prefix_val,
                                config_type : $("#config_type").val()
                            },
                            dataType: 'json',
                            success: function (json) {
                                if(json){
                                    layer.alert('修改成功', {icon: 1}, function (index) {
                                        layer.close(index);
                                        location.reload();
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

    function  download(downarr,prefix,charset) {
        if(downarr.length > 0) {
            $("body").append("<iframe style='display: none;' src=?p=I&c=Server&a=sConfig&type="+downarr.pop()+"&prefix="+prefix+"&charset="+charset+"></iframe>")
            setTimeout(download(downarr,prefix,charset), 1);
        }
    }

    function prefixonload() {
        var loading = layer.load();
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=922",
            data: {
                prefix : prefix_val
            },
            dataType: "json",
            success: function (json) {
                var c='';
                for (var i=0;i<json.length;i++){
                    c+='<option value="'+json[i]+'">'+json[i]+'</option>'
                }
                $("#type").html(c);
                common();
                layer.close(loading);
            },
            error: function () {
                layer.close(loading);
            }
        });
    }

    $('#content').on('blur', 'input', function () {
        $(this).attr('disabled',true);
        var id = $(this).parents('tr').find('td').eq(0).text();
        var name = $(this).parents('tr').find('td').eq(1).find('input').val();
        var numValue = $(this).parents('tr').find('td').eq(2).find('input').val();
        var strValue = $(this).parents('tr').find('td').eq(3).find('input').val();
        var comment = $(this).parents('tr').find('td').eq(4).find('input').val();
        $.ajax({
            type: "POST",
            url: location.href + '&jinIf=917',
            data: {
                id:id,
                name:name,
                numValue:numValue,
                strValue:strValue,
                comment:comment,
                type:$("#type").val(),
                prefix:prefix_val
            },
            success: function (json) {

            }
        });

    }).on('dblclick', 'input', function () {
        $(this).attr('disabled',false)
    }).on('click', 'a[data-type="deleteConfig"]', function () {
        var id = $(this).parents('tr').find('td').eq(0).text();
        layer.alert('确认删除？', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=919",
                data: {
                    id:id
                },
                dataType: "json",
                success: function (json) {
                    if(json){
                        layer.alert('删除成功', {icon: 1}, function (index) {
                            layer.close(index);
                            common();
                        });
                    }
                }
            });
        });
    }).on('click', 'span[data-type="no"]', function () {  // 点击后在游戏服务器列表中显示服务器
        var id = $(this).parents('tr').find('td').eq(0).text();
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=927",
            data: {
                id: id,
                is_annotation:0
            },
            dataType: 'json',
            success: function () {
                common();
            }
        });

    }).on('click', 'span[data-type="yes"]', function () {  // 点击后在游戏服务器列表中隐藏服务器
        var id = $(this).parents('tr').find('td').eq(0).text();
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=927",
            data: {
                id: id,
                is_annotation:1
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
    $("#ExcelConfig").click(function () {
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=931",
            data: {
                prefix:$("#id-prefix").val()
            },
            dataType: 'json',
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']
                });
            },
            success: function (output) {
                layer.closeAll('loading');
                location.href = output;
            },
            error: function () {
                layer.closeAll('loading');
                layer.msg('文件下载失败，请缩小筛选条件后再次下载');
            }
        });
    })


<?php echo '</script'; ?>
>
<?php }
}
