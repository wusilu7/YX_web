<?php
/* Smarty version 3.1.30, created on 2023-12-08 19:33:19
  from "/lnmp/www/app/Admin/View/mb/userAgreement.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6572feff5e26a7_72339821',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '94fd0fbe440b7b919a3c68e43cf1a78b0da3eb3e' => 
    array (
      0 => '/lnmp/www/app/Admin/View/mb/userAgreement.html',
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
function content_6572feff5e26a7_72339821 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<link href="<?php echo CSS;?>
jin/3.05.serverswitch.css" rel="stylesheet">
<div class="jin-content-title"><span>网络协议</span></div>

<div class="jin-server-select">
    <div class="form-group" id="group_server_6"></div>
    <select id="type" style="padding: 6px 5px;">
        <option value="1">用户协议</option>
        <option value="2">隐私协议</option>
        <option value="3">适龄提示</option>
    </select>
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    <a id="jin_add" class="btn btn-primary">新增</a>
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-center jin-server-table">
            <thead>
            <tr>
                <th class="jin-server-column1">
                    <input id="all_choose" type="checkbox">
                    <label for="all_choose">全选</label>
                </th>
                <th>渠道id</th>
                <th>内容</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody id="content"></tbody>
        </table>
    </div>
    <!--|↑↑↑↑↑↑|-->
    <?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

    <?php echo '<script'; ?>
 type="text/javascript">
        $(document).ready(gsSelect3('#g'));
        var data = {};
        $("#jin_search").on('click', function () {
            get_content();
        });
        $("#jin_add").on('click', function () {
            if ($('#g').val() == '') {
                layer.msg('请选择渠道!');
                return false;
            }
            layer.open({
                type: 1,
                closeBtn: 2,
                title: '新增',
                area: ['1000px', '600px'],
                btn: ['确认', '取消'],
                btnAlign: 'c',
                shadeClose: true, //点击遮罩关闭
                content:'<div class="jin-child">' +
                '<div class="select_group_div">'+
                '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content1"  rows="20"  class="form-control"></textarea></div>' +
                '</div>' +
                '</div>',
                yes: function (index1) {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=911",
                        data: {
                            gi:$("#g").val(),
                            type:$("#type").val(),
                            content:$("#content1").val()
                        },
                        dataType:'json',
                        beforeSend: function () {
                            layer.load(2, {
                                shade: [0.3, '#fff']//0.3透明度的白色背景
                            });
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.close(index1);
                            layer.alert('成功', {icon: 1}, function (index) {
                                layer.close(index);
                                get_content();
                            });
                        }
                    });
                }
            });
        });
        function get_content() {
            $.cookie('cookie_g', $("#g").val(), {expires: 30});
            var c = '';
            $.ajax({
                type: "post",
                url: location.href + '&jinIf=912',
                data: {
                    group_id:$("#g").val(),
                    type:$("#type").val()
                },
                dataType: "json",
                success: function (json) {
                    if(json.length>=1){
                        for (var i = 0; i < json.length; i++) {
                            c +=
                                '<tr>' +
                                '<td><input type="checkbox" value="' + json[i]['id'] + '" /></td>' +
                                '<td>' + json[i]['gi'] + '</td>' +
                                '<td>' + json[i]['content'] + '</td>' +
                                '<td><a data-type="update" class="btn btn-primary">修改</a><br>' +
                                '<a data-type="delete" class="btn btn-danger">删除</a></td>' +
                                '</tr>';
                            $("#content").html(c);
                        }
                    }else {
                        $("#content").html('');
                    }
                }
            });
        }
        $('#content').on('click', 'a[data-type="update"]', function() {
            var id = $(this).parents('tr').find('td').eq(0).find('input').val();
            var content = $(this).parents('tr').find('td').eq(2).text();
            layer.open({
                type: 1,
                closeBtn: 2,
                title: '修改',
                area: ['1000px', '600px'],
                btn: ['确认', '取消'],
                btnAlign: 'c',
                shadeClose: true, //点击遮罩关闭
                content:'<div class="jin-child">' +
                '<div class="select_group_div">'+
                '<div class="input-group"><span class="input-group-addon">内容</span><textarea id="content1"  rows="20"  class="form-control">'+content+'</textarea></div>' +
                '</div>' +
                '</div>',
                yes: function (index1) {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=913",
                        data: {
                            id: id,
                            content:$("#content1").val()
                        },
                        dataType:'json',
                        beforeSend: function () {
                            layer.load(2, {
                                shade: [0.3, '#fff']//0.3透明度的白色背景
                            });
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.close(index1);
                            layer.alert('成功', {icon: 1}, function (index) {
                                layer.close(index);
                                get_content();
                            });
                        }
                    });
                }
            });
        }).on('click', 'a[data-type="delete"]', function() {
            var id = $(this).parents('tr').find('td').eq(0).find('input').val();
            layer.alert('确认删除？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=914",
                    data: {
                        id: id
                    },
                    dataType:'json',
                    success: function () {
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                            get_content();
                        });
                    }
                });
            });
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
            var server_id = '';
            var name = '';
            $('#content input[type="checkbox"]:checked').each(function(index, el) {
                if (index == 0) {
                    server_id = $(el).val();
                    name = $(el).parent('td').next().next().text();
                } else {
                    server_id += ',' + $(el).val();
                    name += ',' + $(el).parent('td').next().next().text();
                }
            });

            if (server_id == '') {
                layer.alert('请选择服务器！', {icon: 2});
                return false;
            }

            return {
                'server_id': server_id,
                'name': name
            };
        }



        $('#content').on('click', 'tr', function() {
            var cb = $(this).find('td:first>input');
            if (! cb.is(':checked')) {
                cb.attr('checked', true);
                $(this).attr('style', 'background: #aba5618c');
            } else {
                cb.attr('checked', false);
                $(this).removeAttr('style', 'background: #aba5618c');
            }
            s_id='';
            $('#content input[type="checkbox"]:checked').each(function(index, el) {
                if (index == 0) {
                    s_id = $(el).val();
                } else {
                    s_id += ',' + $(el).val();
                }
            });
            $.cookie('cookie_gss', s_id, {expires: 7});
        });


    <?php echo '</script'; ?>
>
<?php }
}
