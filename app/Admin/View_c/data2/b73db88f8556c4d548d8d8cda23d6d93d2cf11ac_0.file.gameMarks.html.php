<?php
/* Smarty version 3.1.30, created on 2024-05-08 14:10:29
  from "/lnmp/www/app/Admin/View/data2/gameMarks.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_663b1755028f47_87299709',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'b73db88f8556c4d548d8d8cda23d6d93d2cf11ac' => 
    array (
      0 => '/lnmp/www/app/Admin/View/data2/gameMarks.html',
      1 => 1715148494,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_663b1755028f47_87299709 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.06.server.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>游戏进度标记</span></div>
<div class="jin-server-select">
    <!--    <lable>模板：</lable>-->
    <!--    <select  id="temName" style="padding: 0 8px; margin-right: 20px;"></select>-->
    <!--    <a data-type="addTemplate" class="btn btn-primary">新增模板</a>-->
    <a data-type="insertGN" class="btn btn-success">新增标记</a>
    <!--    <a data-type="insertGN1" class="btn btn-success">新增配置选项(下拉选择)</a>-->
    <input type="checkbox" id="sort"/>
    <button data-type="preserve" class="jin-hidden btn btn-sm btn-danger">保存排序</button>
    <button id="export" class="btn btn-danger">excel导出</button>
    <button id="upload" class='btn btn-success'>导入数据</button>
    <form id="uploadForm" enctype="multipart/form-data" style="display: inline-block; width: 180px;">
        <input id="file" accept=".xls,.xlsx" type="file" name="file"/>
    </form>
    <!--    <a data-type="delTemplate" class="btn btn-danger pull-right">删除模板</a>-->
    <!--    <a data-type="deleteType" class="btn btn-danger pull-right">删除模板分类</a>-->
    <!--    <a data-type="insertType" class="btn btn-warning pull-right">新增模板分类</a>-->
</div>
<div class="table-responsive">
    <table class="table table-bordered text-center">
        <thead>
        <tr>
            <th style="display: none">id</th>
            <th>排序编号</th>
            <th>key</th>
            <th>value</th>
            <!--            <th>生效时间</th>-->
            <!--            <th>有效</th>-->
            <th>操作</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<hr>
<!--描述：-->
<!--<br>-->
<!--<div class="clearfix">-->
<!--    <textarea   id="describe" style="width: 100%; height: 100px;"></textarea>-->
<!--</div>-->
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 type="text/javascript">
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
                // noPageContentList(url, data, id, arr);
            }
        }
    });

    var btn = [
        '<div class="btn-group btn-group-sm"><a data-type="deleteConfig" class="btn btn-danger">删除</a></div>' +
        '<div class="btn-group btn-group-sm"><a data-type="updateConfig" class="btn btn-danger">修改</a></div>'
    ];
    $('button[data-type="preserve"]').on('click', function () {//保存排序
        var id_list = '';
        $('#content').find('tr').each(function () {
            id_list += $(this).children().eq(0).text() + ',';
        });
        console.log(id_list);
        layer.alert('确定保存这个顺序', {icon: 0, btn: ['确定', '取消'], offset: ['15%', '30%']}, function () {
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
                    layer.alert('顺序保存成功', {icon: 1, offset: ['15%', '30%']}, function (index) {
                        layer.close(index);
                        common();
                        $('#sort').bootstrapSwitch('toggleState');
                        noPageContentList(url, data, id, arr);
                    });
                }
            });
        });
    })

    function common() {
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=917",
            data: {
                tem: $("#temName").val(),
                is_valid: 0
            },
            dataType: 'json',
            success: function (json) {
                var c = '';
                for (var i = 0; i < json.length; i++) {
                    // if(json[i].is_valid_source==1){
                    //     sty = 'style="background: #aba5618c;"'
                    // }else {
                    //     sty = '';
                    // }
                    c += '<tr ' + ' >' +
                        '<td style="display: none">' + json[i].id + '</td>' +
                        '<td>' + json[i].sort_k + '</td>' +
                        '<td><span class="form-control" style="width: 500px;">' + json[i].key + '</span> </td>' +
                        // '<td><input style="width: 500px;" type="text" value="'+json[i].value+'" class="form-control"></td>' +
                        '<td><span class="form-control" style="width: 500px;">' + json[i].value + '</span> </td>' +
                        // '<td><input style="width: 200px;" type="text"  value="'+json[i].echotime+'" class="form-control "></td>' +
                        // '<td>'+json[i].is_valid_source1+'</td>' +
                        '<td>' + btn + '</td>' +
                        '</tr>'
                }
                $("#content").html(c);
                describe();
            }
        });
    }

    function describe() {
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=924",
            data: {
                tem: $("#temName").val(),
            },
            dataType: 'json',
            success: function (json) {
                var c = '';
                c = json.content;
                $("#describe").html(c);
            }
        });
    }

    $("#describe").on('blur', function () {
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=925",
            data: {
                des: $("#describe").val(),
                tem: $("#temName").val()
            },
            dataType: 'json',
            success: function (json) {

            }
        });
    });

    $("a[data-type='insertType']").on('click', function () {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '新增分类',
            area: ['400px', '180px'],
            offset: ['15%', '30%'],
            btn: ['新增', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
                '<div class="input-group"><span class="input-group-addon">类名</span><input id="typename" type="text" class="form-control"></div>' +
                '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=911',
                    data: {
                        type_name: $('#typename').val(),
                    },
                    success: function (res) {
                        layer.close(index);
                        layer.alert('添加成功！', {icon: 1, offset: ['15%', '30%']}, function (index) {
                            layer.close(index);
                        });
                    }
                });
            },
            cancel: function () {
            }
        })
    });

    $("a[data-type='addTemplate']").on('click', function () {
        var h = '';
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=913",
            dataType: 'json',
            success: function (json) {
                for (var i = 0; i < json.length; i++) {
                    h += '<option value="' + json[i].id + '">' + json[i].type_name + '</option>'
                }
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '新增模板',
                    area: ['400px', '220px'],
                    offset: ['15%', '30%'],
                    btn: ['确认', '取消'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content: '<div class="jin-child">' +
                        '<div class="input-group"><span class="input-group-addon">模板名</span><input id="tem_name" type="text" class="form-control"></div>' +
                        '<div class="input-group"><span class="input-group-addon">分类</span><select id="tem_type" class="form-control">' + h + '</select></div>' +
                        '</div>',
                    yes: function (index) {
                        $.ajax({
                            type: "POST",
                            url: location.href + "&jinIf=914",
                            data: {
                                tem_name: $("#tem_name").val(),
                                tem_type: $("#tem_type").val(),
                                is_time: 3
                            },
                            dataType: 'json',
                            success: function (json) {
                                if (json) {
                                    layer.close(index);
                                    layer.alert('添加成功！', {icon: 1, offset: ['15%', '30%']}, function (index) {
                                        location.reload();
                                    });
                                } else {
                                    layer.alert('重复模板名！', {icon: 2, offset: ['15%', '30%']}, function (index) {
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

    $("a[data-type='insertGN']").on('click', function () {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '新增选项',
            area: ['400px', '245px'],
            offset: ['15%', '30%'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
                '<div class="input-group"><span class="input-group-addon">key</span><input id="gn_key" type="text" class="form-control"></div>' +
                '<div class="input-group"><span class="input-group-addon">value</span><input id="gn_value" type="text" class="form-control"></div>' +
                '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=916",
                    data: {
                        temName: $("#temName").val(),
                        key: $("#gn_key").val(),
                        value: $("#gn_value").val(),
                    },
                    dataType: 'json',
                    success: function (json) {
                        if (json == 0) {
                            layer.alert('添加失败 请检查参数', {icon: 0, offset: ['15%', '30%']}, function (index) {
                                layer.close(index);
                                common();
                            });
                        }
                        if (json) {
                            layer.close(index);
                            layer.alert('添加成功！', {icon: 1, offset: ['15%', '30%']}, function (index) {
                                layer.close(index);
                                common();
                            });
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    });

    $("a[data-type='insertGN1']").on('click', function () {
        var h = '';
        $.ajax({
            type: "POST",
            url: "?p=Admin&c=Data2&a=gameMarks&jinIf=917",
            dataType: 'json',
            success: function (json) {
                for (var i = 0; i < json.length; i++) {
                    h += '<option value="' + json[i].host_name + '">' + json[i].host_name + '</option>'
                }
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '新增选项',
                    area: ['400px', '350px'],
                    offset: ['15%', '30%'],
                    btn: ['确认', '取消'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content: '<div class="jin-child">' +
                        '<div class="input-group"><span class="input-group-addon">key</span><select id="gn_key" class="form-control">' + h + '</select></div>' +
                        '<div class="input-group"><span class="input-group-addon">value</span><select id="gn_value" class="form-control"><option value="start">start</option><option value="stop">stop</option></select></div>' +
                        '</div>',
                    yes: function (index) {
                        $.ajax({
                            type: "POST",
                            url: location.href + "&jinIf=916",
                            data: {
                                temName: $("#temName").val(),
                                gn_key: $("#gn_key").val(),
                                gn_value: $("#gn_value").val(),
                            },
                            dataType: 'json',
                            success: function (json) {
                                if (json) {
                                    layer.close(index);
                                    layer.alert('添加成功！', {icon: 1, offset: ['15%', '30%']}, function (index) {
                                        layer.close(index);
                                        common();
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

    $("a[data-type='deleteType']").on('click', function () {
        var h = '';
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=913",
            dataType: 'json',
            success: function (json) {
                for (var i = 0; i < json.length; i++) {
                    h += '<option value="' + json[i].id + '">' + json[i].type_name + '</option>'
                }
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '删除模板分类',
                    area: ['400px', '200px'],
                    offset: ['15%', '30%'],
                    btn: ['确认', '取消'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content: '<div class="jin-child">' +
                        '<div class="input-group"><span class="input-group-addon">分类</span><select id="tem_type" class="form-control">' + h + '</select></div>' +
                        '</div>',
                    yes: function (index) {
                        $.ajax({
                            type: "POST",
                            url: location.href + "&jinIf=922",
                            data: {
                                tem_type: $("#tem_type").val(),
                            },
                            dataType: 'json',
                            success: function (json) {
                                if (json) {
                                    layer.close(index);
                                    layer.alert('删除成功！', {icon: 1, offset: ['15%', '30%']}, function (index) {
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

    $("a[data-type='delTemplate']").on('click', function () {
        var temName = $("#temName").val();
        layer.alert('确认删除 <span style="color: red">' + temName + '</span>？', {
            icon: 0,
            btn: ['确定', '取消'],
            offset: ['15%', '30%'],
            shadeClose: true
        }, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=923",
                data: {
                    temName: temName
                },
                dataType: "json",
                success: function (json) {
                    layer.closeAll('loading');
                    if (json) {
                        layer.alert('删除成功', {icon: 1, offset: ['25%', '35%']}, function (index) {
                            layer.close(index);
                            location.reload();
                        });
                    }
                }
            });
        });
    });

    $("#temName").change(function () {
        common();
    });
    $(document).ready(function () {
        // $("#jin_search").delay(1000).trigger("click");
        // console.log('页面加载完成！');
        common();
        // var tar = $("button[data-type='d']").parents('tr').find('td').eq(11).text();
        // console.log(tar);

    });

    function prefix(prefix) {
        obj = {id: prefix};
        obj.url = location.href + "&jinIf=915";
        $.ajax({
            type: "POST",
            url: obj.url,
            dataType: 'json',
            success: function (res) {
                var c = '';
                var li = '';
                for (var i = 0; i < res.length; i++) {
                    c += '<optgroup style="color: red;" label="' + res[i][0] + '">'
                    li += '<li class="dropdown-header " data-optgroup="' + i + '"><span class="text">' + res[i][0] + '</span></li>';
                    var x
                    for (var j = 1; j < res[i].length; j++) {
                        if (i == 0) {
                            x = -1;
                            for (var j = 1; j < res[i].length; j++) {
                                var aa = j - 1;
                                c += '<option style="color: #0C0C0C" value="' + res[i][j].gnTem_name + '">' + res[i][j].gnTem_name + '</option>';
                                li += '<li data-original-index="' + aa + '">' +
                                    '<a tabindex="0" data-tokens="null">' +
                                    '<span class="text text_content">' + res[i][j].gnTem_name + '</span><span class="glyphicon glyphicon-ok check-mark"></span>' +
                                    '</a>' +
                                    '</li>';
                            }
                        } else {
                            x += res[i - 1].length;
                            for (var j = 1; j < res[i].length; j++) {
                                var aa = j + x - i;
                                c += '<option style="color: #0C0C0C" value="' + res[i][j].gnTem_name + '">' + res[i][j].gnTem_name + '</option>';
                                li += '<li data-original-index="' + aa + '">' +
                                    '<a tabindex="0" data-tokens="null">' +
                                    '<span class="text text_content">' + res[i][j].gnTem_name + '</span><span class="glyphicon glyphicon-ok check-mark"></span>' +
                                    '</a>' +
                                    '</li>';
                            }
                        }
                    }
                    li += '<li class="divider" data-optgroup="2div"></li>';
                    c += '</optgroup>';
                }
                $(obj.id).html(c);
                $('.selectpicker').selectpicker('refresh');
                common();
            }
        });
    }

    prefix("#temName");
    $('#content').on('click', 'a[data-type="updateConfig"]', function () {
        var id = $(this).parents('tr').find('td').eq(0).text();
        $.ajax({
            type: "POST",
            url: "?p=Admin&c=Data2&a=gameMarks&jinIf=917",
            data: {
                id: id
            },
            dataType: 'json',

            success: function (json) {
                console.log(json[0]);
                // for (var i=0;i<json.length;i++){
                //     h += '<option value="'+json[i].host_name+'">'+json[i].host_name+'</option>'
                // }
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '新增选项',
                    area: ['400px', '350px'],
                    offset: ['15%', '30%'],
                    btn: ['确认', '取消'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content: '<div class="jin-child">' +
                        // '<div class="input-group"><span class="input-group-addon">key</span><select id="gn_key" class="form-control">'+json.key+'</select></div>' +
                        // '<div class="input-group"><span class="input-group-addon">value</span><select id="gn_value" class="form-control"><option value="start">json.value</option><option value="stop">stop</option></select></div>' +
                        '<div class="input-group"><span class="input-group-addon">排序编号</span><input id="sort_k" type="text" value="' + json[0].sort_k + '" class="form-control"></div>' +
                        '<div class="input-group"><span class="input-group-addon">key</span><input id="key" type="text" value="' + json[0].key + '" class="form-control"></div>' +
                        '<div class="input-group"><span class="input-group-addon">value</span><input id="value" type="text" value="' + json[0].value + '" class="form-control"></div>' +
                        '<input id="id" style="display: none" type="text" value="' + json[0].id + '">' +
                        // '<div class="input-group"><span class="input-group-addon">value</span><input id="gn_value" type="text" class="form-control"></div>' +
                        '</div>',
                    yes: function (index) {
                        $.ajax({
                            type: "POST",
                            url: location.href + "&jinIf=919",
                            data: {
                                id: $("#id").val(),
                                key: $("#key").val(),
                                value: $("#value").val(),
                                sort_k: $("#sort_k").val(),
                            },
                            dataType: 'json',
                            success: function (json) {
                                if (json) {
                                    layer.close(index);
                                    layer.alert('修改成功！', {icon: 1, offset: ['25%', '35%']}, function (index) {
                                        layer.close(index);
                                        common();
                                    });
                                } else {
                                    layer.alert('请修改参数！', {icon: 0, offset: ['25%', '35%']}, function (index) {
                                        layer.close(index);
                                        common();
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
    })
    $('#content').on('click', 'a[data-type="deleteConfig"]', function () {
        var id = $(this).parents('tr').find('td').eq(0).text();
        layer.alert('确认删除？', {
            icon: 0,
            btn: ['确定', '取消'],
            offset: ['15%', '30%'],
            shadeClose: true
        }, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=918",
                data: {
                    id: id
                },
                dataType: "json",
                success: function (json) {
                    layer.alert('删除成功', {icon: 1, offset: ['25%', '35%']}, function (index) {
                        layer.close(index);
                        common();
                    });
                }
            });
        });
    }).on('blur', 'input', function () {
        var id = $(this).parents('tr').find('td').eq(0).text();
        var gn_key = $(this).parents('tr').find('td').eq(1).find('input').val();
        var gn_value = $(this).parents('tr').find('td').eq(2).find('input').val();
        var gn_echotime = $(this).parents('tr').find('td').eq(3).find('input').val();
        $.ajax({
            type: "POST",
            url: location.href + '&jinIf=919',
            data: {
                id: id,
                gn_key: gn_key,
                gn_value: gn_value,
                gn_echotime: gn_echotime
            },
            success: function () {
            }
        });
    }).on('click', 'span[data-type="no"]', function () {  // 点击后在游戏服务器列表中显示服务器
        var id = $(this).parents('tr').find('td').eq(0).text();
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=921",
            data: {
                id: id,
                is_valid_source: 1
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
            url: location.href + "&jinIf=921",
            data: {
                id: id,
                is_valid_source: 0
            },
            dataType: 'json',
            success: function () {
                common();
            }
        });
    })

    // 导入标记
    $("#upload").click(function () {
        var formData = new FormData($('#uploadForm')[0]);
        layer.alert('确认操作?', {
            icon: 0,
            btn: ['确定', '取消'],
            shadeClose: true
        }, function (index) {
            layer.close(index);
            $.ajax({
                type: 'post',
                url: location.href + '&jinIf=920',
                data: formData,
                cache: false,
                dataType: 'json',
                beforeSend: function () {
                    layer.load();
                },
                processData: false,
                contentType: false,
                success: function (json) {
                    layer.closeAll('loading');
                    layer.msg(json.msg, {icon: json.status, time: 1000});
                    common();
                    $('#file').val('');
                }
            })
        });
    });

    // 导出标记
    $('#export').click(function () {
        $.ajax({
            type: "GET",
            url: location.href + "&jinIf=921",
            dataType: "json",
            beforeSend: function () {
                layer.load();
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
    });
<?php echo '</script'; ?>
>
<?php }
}
