<?php
/* Smarty version 3.1.30, created on 2023-04-21 09:25:39
  from "/lnmp/www/app/Admin/View/mb/notice.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6441e6136faeb2_66845578',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '637708c17dd2fe24938bf0ce460ecf30dd72e959' => 
    array (
      0 => '/lnmp/www/app/Admin/View/mb/notice.html',
      1 => 1678771400,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_6441e6136faeb2_66845578 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.09.notice.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>公告管理</span></div>
<div class="alert">
    <div class="form-group" id="group_server_6"></div>
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    <button data-type="insert" class="btn btn-primary left">新增公告</button>
    <input type="checkbox" id="sort"/>
    <button data-type="preserve" class="jin-hidden btn btn-sm btn-danger">保存排序</button>
    <!--<button data-type="all_update" class="btn btn-success">批量修改</button>-->
    <!--<button data-type="all_delete" class="btn btn-danger">批量删除</button>-->
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <thead class="jin-notice-head">
        <tr>
            <th class="jin-server-column1">
                <input id="all_choose" type="checkbox">
                <label for="all_choose">全选</label>
            </th>
            <th class="jin-server-column1">编号</th>
            <th>渠道</th>
            <th>标题</th>
            <th>开始时间</th>
            <th>失效时间</th>
            <th class="jin-notice-column6">内容</th>
            <th>创建时间</th>
            <th>创建人</th>
            <th>状态</th>
            <th class="jin-notice-column9">操作</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ①请留意公告的有效时间，及时删除过期公告。
    </div>
</div>

<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    var isMultilingualClass='';
    if(eval('<?php echo $_smarty_tpl->tpl_vars['isMultilingual']->value;?>
')==0){
        isMultilingualClass='hide'
    }
    var data = {};
    calendar('hour', '#time_start', '#time_end');
    gsSelect3('#g','#p');
    typeSelect();
    //公告类型
    function typeSelect(obj) {
        obj = isExist(obj, {});
        obj.dom = isExist(obj.dom, "#type");
        obj.url = isExist(obj.url, location.href + "&jinIf=941");
        jinSelect(obj);
    }
    $("#jin_search").on('click', function () {
        jsonQuery();
    });
    // 全选
    $('#all_choose').click(function() {
        var check_on = $(this).is(':checked');
        if (check_on) {
            $('#content').find('input[type="checkbox"]').attr('checked', true);
        } else {
            $('#content').find('input[type="checkbox"]').attr('checked', false);
        }
    });
    // 获取选中的服务器
    function getChoose() {
        var notice_id = '';
        var first = [];
        $('#content input[type="checkbox"]:checked').each(function(index, el) {
            if (index == 0) {
                notice_id = $(el).val();
                first.push($(el).parents('tr').find('td').eq(4).text());
                first.push($(el).parents('tr').find('td').eq(5).text());
                first.push($(el).parents('tr').find('td').eq(3).text());
                first.push($(el).parents('tr').find('td').eq(6).text());
            } else {
                notice_id += ',' + $(el).val();
            }
        });

        if (notice_id == '') {
            layer.alert('请选择公告！', {icon: 2});
            return false;
        }

        return {
            'first': first,
            'notice_id': notice_id
        };
    }

    // 点击批量修改
    $('button[data-type="all_update"]').click(function() {
        var arr = getChoose();
        all_update(arr.first,arr.notice_id);
    });
    // 点击批量删除
    $('button[data-type="all_delete"]').click(function() {
        var arr = getChoose();
        if(arr.notice_id){
            all_del(arr.notice_id);
        }
    });

    function all_update(first,notice_ids) {
        var notice_id = notice_ids;
        var time_start = first[0];
        var time_end = first[1];
        var title = first[2];
        var content = first[3];
        var docW = window.screen.width;
        if(docW < 768){
            layerW = '80%';
            layerH = '80%';
        }else{
            layerW = '650px';
            layerH = '610px;';
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '公告修改',
            area: [layerW, layerH],
            btn: ['修改', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">开始时间</span><input id="time_start1" type="text" class="form-control" value="' +
            time_start + '"></div>' +
            '<div class="input-group"><span class="input-group-addon">结束时间</span><input id="time_end1" type="text" class="form-control" value="' +
            time_end + '"></div>' +
            '<div class="input-group"><span class="input-group-addon">标题</span><input id="title1" type="text" class="form-control" value="' +
            title + '"></div>' +
            '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content1"  rows="16"  class="form-control">' + content + '</textarea></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=916',
                    data: {
                        notice_id: notice_id,
                        time_start: $('#time_start1').val(),
                        time_end: $('#time_end1').val(),
                        title: $('#title1').val(),
                        content: $('#content1').val()
                    },
                    success: function () {
                        layer.close(index);
                        layer.alert('修改成功', {icon: 1}, function (index) {
                            layer.close(index);
                            jsonQuery();
                        });
                    }
                });
            },
            cancel: function () {
            }
        });
        calendar('hour', '#time_start1', '#time_end1');
    }

    function all_del(notice_ids) {
        var notice_id = notice_ids;
        layer.alert('确认删除[' + notice_ids + '号公告]？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=917",
                data: {
                    notice_id: notice_id
                },
                success: function () {
                    layer.alert('删除成功', {icon: 1}, function (index) {
                        layer.close(index);
                        jsonQuery();
                    });
                }
            });
        });
    }

    //刷新数据
    function jsonQuery() {
        if ($("#g").val() == '') {
            layer.msg('请选择渠道!');
            return false;
        }
        var url = location + "&jinIf=912";
        var data = {
            gi: $("#g").val()
        };
        $.ajax({
            type: "post",
            url: url,
            data: data,
            dataType: "json",
            success: function (json) {
                var c = '';
                var btn = [
                    "<div class='btn-group btn-group-sm'>" +
                    "<button data-type='u' class='btn btn-primary'>修改</button>" +
                    "<button data-type='d' class='btn btn-danger'>删除</button>" +
                    "</div>"
                ];
                for (var i = 0; i < json.length; i++) {
                    var state = '';
                    var now = new Date().getTime();
                    var time_start = Date.parse(new Date(json[i].time_start.replace(/-/g, '/')));
                    var time_end = Date.parse(new Date(json[i].time_end.replace(/-/g, '/')));
                    if (now > time_start && now < time_end) {
                        state = '生效中';
                        c += '<tr class="jin-notice-enabled">';
                    } else {
                        c += '<tr class="jin-notice-disabled">';
                        state = '未生效';
                    }
                    c +=
                        '<td><input type="checkbox" value="' + json[i].notice_id + '" /></td>' +
                        '<td>' + json[i].notice_id + '</td>' +
                        '<td>' + json[i].gi + '</td>' +
                        '<td data-id="' + json[i].notice_id + '">' + json[i].title1 + '</td>' +
                        '<td>' + json[i].time_start + '</td>' +
                        '<td>' + json[i].time_end + '</td>' +
                        '<td>' + json[i].content1 + '</td>' +
                        '<td>' + json[i].create_time + '</td>' +
                        '<td>' + json[i].cu + '</td>' +
                        '<td>' + state + '</td>' +
                        '<td>' + btn + '</td>' +
                        '</tr>';
                }
                $("#content").html(c);
            },
            error: function () {
                layer.msg('数据获取失败，请勿频繁刷新');
            }
        });
    }
    //公告修改删除
    $('#content').on('click', 'button[data-type="d"]', function () {
        var notice_id = $(this).parents('tr').find('td').eq(3).data('id');
        layer.alert('确认删除[' + notice_id + '号公告]？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=914",
                data: {
                    notice_id: notice_id
                },
                success: function () {
                    layer.alert('删除成功', {icon: 1}, function (index) {
                        layer.close(index);
                        jsonQuery();
                    });
                }
            });
        });
    }).on('click', 'button[data-type="u"]', function () {
        var notice_id = $(this).parents('tr').find('td').eq(3).data('id');
        var time_start = $(this).parents('tr').find('td').eq(4).text();
        var time_end = $(this).parents('tr').find('td').eq(5).text();
        $.ajax({
            type: "POST",
            url: location.href + '&jinIf=9121',
            data: {
                notice_id: notice_id
            },
            dataType: "json",
            success: function (res) {
                var docW = window.screen.width;
                if(docW < 768){
                    layerW = '80%';
                    layerH = '80%';
                }else{
                    layerW = '1000px';
                    layerH = '650px;';
                }
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '公告修改',
                    area: [layerW, layerH],
                    btn: ['修改', '取消'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content: '<div class="jin-child">' +
                    '<div class="input-group"><span class="input-group-addon">开始时间</span><input id="time_start1" type="text" class="form-control" value="' + time_start + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">结束时间</span><input id="time_end1" type="text" class="form-control" value="' + time_end + '"></div>' +
                    '<div class="input-group '+isMultilingualClass+'">' +
                    '<ul class="nav nav-tabs">' +
                    '<li  class="active"><a href="#nav_content1" data-toggle="tab">中文</a></li>' +
                    '<li><a href="#nav_content2" data-toggle="tab">繁体</a></li>' +
                    '<li><a href="#nav_content3" data-toggle="tab">英语</a></li>' +
                    '<li><a href="#nav_content4" data-toggle="tab">西班牙</a></li>' +
                    '<li><a href="#nav_content5" data-toggle="tab">阿拉伯语</a></li>' +
                    '<li><a href="#nav_content6" data-toggle="tab">俄语</a></li>' +
                    '<li><a href="#nav_content7" data-toggle="tab">泰文</a></li>' +
                    '<li><a href="#nav_content8" data-toggle="tab">巴西</a></li>' +
                    '<li><a href="#nav_content9" data-toggle="tab">印尼</a></li>' +
                    '<li><a href="#nav_content10" data-toggle="tab">日本</a></li>' +
                    '<li><a href="#nav_content11" data-toggle="tab">韩文</a></li>' +
                    '</ul>' +
                    '</div>' +
                    '<div class="tab-content">' +
                    '<div class="tab-pane active" id="nav_content1">' +
                    '<div class="input-group"><span class="input-group-addon">标题</span><input id="title1" type="text" class="form-control" value="' + res.title1 + '"></div><br>' +
                    '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content1"  rows="12"  class="form-control">' + res.content1 + '</textarea></div>' +
                    '</div>' +
                    '<div class="tab-pane" id="nav_content2">' +
                    '<div class="input-group"><span class="input-group-addon">标题</span><input id="title2" type="text" class="form-control" value="' + res.title2 + '"></div><br>' +
                    '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content2"  rows="12"  class="form-control">' + res.content2 + '</textarea></div>' +
                    '</div>' +
                    '<div class="tab-pane" id="nav_content3">' +
                    '<div class="input-group"><span class="input-group-addon">标题</span><input id="title3" type="text" class="form-control" value="' + res.title3 + '"></div><br>' +
                    '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content3"  rows="12"  class="form-control">' + res.content3 + '</textarea></div>' +
                    '</div>' +
                    '<div class="tab-pane" id="nav_content4">' +
                    '<div class="input-group"><span class="input-group-addon">标题</span><input id="title4" type="text" class="form-control" value="' + res.title4 + '"></div><br>' +
                    '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content4"  rows="12"  class="form-control">' + res.content4 + '</textarea></div>' +
                    '</div>' +
                    '<div class="tab-pane" id="nav_content5">' +
                    '<div class="input-group"><span class="input-group-addon">标题</span><input id="title5" type="text" class="form-control" value="' + res.title5 + '"></div><br>' +
                    '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content5"  rows="12"  class="form-control">' + res.content5 + '</textarea></div>' +
                    '</div>' +
                    '<div class="tab-pane" id="nav_content6">' +
                    '<div class="input-group"><span class="input-group-addon">标题</span><input id="title6" type="text" class="form-control" value="' + res.title6 + '"></div><br>' +
                    '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content6"  rows="12"  class="form-control">' + res.content6 + '</textarea></div>' +
                    '</div>' +
                    '<div class="tab-pane" id="nav_content7">' +
                    '<div class="input-group"><span class="input-group-addon">标题</span><input id="title7" type="text" class="form-control" value="' + res.title7 + '"></div><br>' +
                    '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content7"  rows="12"  class="form-control">' + res.content7 + '</textarea></div>' +
                    '</div>' +
                    '<div class="tab-pane" id="nav_content8">' +
                    '<div class="input-group"><span class="input-group-addon">标题</span><input id="title8" type="text" class="form-control" value="' + res.title8 + '"></div><br>' +
                    '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content8"  rows="12"  class="form-control">' + res.content8 + '</textarea></div>' +
                    '</div>' +
                    '<div class="tab-pane" id="nav_content9">' +
                    '<div class="input-group"><span class="input-group-addon">标题</span><input id="title9" type="text" class="form-control" value="' + res.title9 + '"></div><br>' +
                    '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content9"  rows="12"  class="form-control">' + res.content9 + '</textarea></div>' +
                    '</div>' +
                    '<div class="tab-pane" id="nav_content10">' +
                    '<div class="input-group"><span class="input-group-addon">标题</span><input id="title10" type="text" class="form-control" value="' + res.title10 + '"></div><br>' +
                    '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content10"  rows="12"  class="form-control">' + res.content10 + '</textarea></div>' +
                    '</div>' +
                    '<div class="tab-pane" id="nav_content11">' +
                    '<div class="input-group"><span class="input-group-addon">标题</span><input id="title11" type="text" class="form-control" value="' + res.title11 + '"></div><br>' +
                    '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content11"  rows="12"  class="form-control">' + res.content11 + '</textarea></div>' +
                    '</div>' +
                    '</div>' +
                    '</div>',
                    yes: function (index) {
                        $.ajax({
                            type: "POST",
                            url: location.href + '&jinIf=913',
                            data: {
                                notice_id: notice_id,
                                time_start: $('#time_start1').val(),
                                time_end: $('#time_end1').val(),
                                title1: $('#title1').val(),
                                title2: $('#title2').val(),
                                title3: $('#title3').val(),
                                title4: $('#title4').val(),
                                title5: $('#title5').val(),
                                title6: $('#title6').val(),
                                title7: $('#title7').val(),
                                title8: $('#title8').val(),
                                title9: $('#title9').val(),
                                title10: $('#title10').val(),
                                title11: $('#title11').val(),
                                content1: $('#content1').val(),
                                content2: $('#content2').val(),
                                content3: $('#content3').val(),
                                content4: $('#content4').val(),
                                content5: $('#content5').val(),
                                content6: $('#content6').val(),
                                content7: $('#content7').val(),
                                content8: $('#content8').val(),
                                content9: $('#content9').val(),
                                content10: $('#content10').val(),
                                content11: $('#content11').val()
                            },
                            success: function () {
                                layer.close(index);
                                layer.alert('修改成功', {icon: 1}, function (index) {
                                    layer.close(index);
                                    jsonQuery();
                                });
                            }
                        });
                    },
                    cancel: function () {
                    }
                });
                calendar('hour', '#time_start1', '#time_end1');
            }
        });

    }).on('click', 'tr', function() {
        var cb = $(this).find('td:first>input');
        if (! cb.is(':checked')) {
            cb.attr('checked', true);
            $(this).attr('style', 'background: #aba5618c');
        } else {
            cb.attr('checked', false);
            $(this).removeAttr('style', 'background: #aba5618c');
        }
    });
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
                jsonQuery();
            }
        }
    });

    $('button[data-type="preserve"]').on('click', function () {//保存排序
        var id_list = '';
        $('#content').find('tr').each(function () {
            id_list += $(this).children().eq(0).find('input').val() + ',';
        });
        layer.alert('确定保存这个顺序', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9136",
                data: {
                    gi: $("#group").val(),

                    id_list: id_list
                },
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
                        //location.reload()
                        jsonQuery();
                    });
                }
            });
        });
    })


    $('button[data-type="insert"]').on('click', function () {//保存排序
        location.href += "&jinIf=915";
    })

<?php echo '</script'; ?>
><?php }
}
