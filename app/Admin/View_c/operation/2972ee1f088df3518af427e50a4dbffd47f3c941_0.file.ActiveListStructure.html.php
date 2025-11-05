<?php
/* Smarty version 3.1.30, created on 2024-08-14 17:40:53
  from "D:\pro\WebSiteYiXing\app\Admin\View\operation\ActiveListStructure.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66bc7ba51f8bd5_93496426',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2972ee1f088df3518af427e50a4dbffd47f3c941' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\operation\\ActiveListStructure.html',
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
function content_66bc7ba51f8bd5_93496426 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<style>

</style>
<div class="jin-content-title"><span>热更活动表结构</span></div>
<div class="alert alert-info">
     <div id="group_only" style="display: inline-block;"></div>
    <a id="jin_add1" class="btn btn-info jin_add">导入表结构</a>
    <a id="sync0" class="btn btn-info jin_add">同步欧美</a>
    <a id="sync2" class="btn btn-info jin_add">同步海外</a>
    <a id="sync1" class="btn btn-info jin_add">同步国内</a>
</div>
<div style="float: right;" class="hide">
    <form id="uploadForm1"  enctype="multipart/form-data" style="display: inline-block;">
        <input type="file" name="file" id='file1'  accept=".xls,.xlsx" >
    </form>
    <a id="jin_add2" class="btn btn-info jin_add" style="display: inline-block;">导入DynamicData</a>
</div>
<hr>
<label for="tb_path">表名：</label>
<select  id="tb_path"></select>
<a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
<button id="all_updateS1" class="btn btn-info">批量开启允许发送</button>
<button id="all_updateS2" class="btn btn-warning">批量关闭允许发送</button>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th><input id="all_choose" type="checkbox"><label for="all_choose">全选</label></th>
            <th>编号</th>
            <th>表名</th>
            <th>表路径</th>
            <th>客户端映射表ID</th>
            <th>字段名</th>
            <th>字段名注释</th>
            <th>客户端映射列ID</th>
            <th>允许发送</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>

<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
    groupSelect({dom: "#group"});
    var url = location.href + "&jinIf=912";
    var btn = [
        '<a data-type="updatecommon" class="btn btn-primary">修改通用</a>&nbsp;' +
        '<a data-type="update" class="btn btn-primary">修改</a>&nbsp;' +
        '<a data-type="delete" class="btn btn-danger">删除</a>'
    ];
    var id_check = function (json) {
        if(json['is_allow']==1){
            return '<input type="checkbox" value="' + json['id'] + '" />'
        }else{
            return ''
        }

    };
    var is_send_s = function (json) {
        if(json['is_allow']==1){
            if(json['is_send_s']==1){
                return'<span data-type="y3" class="glyphicon glyphicon-ok" style="color: rgb(10,191,0);font-size: 20px;"></span>';
            }else{
                return'<span data-type="n3" class="glyphicon glyphicon-remove" style="color: rgb(255,60,63);font-size: 20px;"></span>';
            }
        }else{
            return ''
        }

    };
    var btn = function (json) {
        if(json['is_allow']==1){
            return '<a data-type="updatecommon" class="btn btn-primary">修改通用</a>&nbsp;' +
                '<a data-type="update" class="btn btn-primary">修改</a>&nbsp;' +
                '<a data-type="delete" class="btn btn-danger">删除</a>';
        }else{
            return ''
        }

    };
    var arr = [id_check,'id','tb_name', 'tb_path',  'client_tb_id','filed_name','filed_annotation','client_col_id',is_send_s,btn];
    var id = "#content";
    var data = {};
    // 普通查询
    $("#jin_search").on('click', function () {
        common();
    });
    function common() {
        data.gi=$("#group").val();
        data.tb_path=$("#tb_path").val();
        noPageContentList(url, data, id, arr);
    }

    $("#jin_add1").on('click', function () {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '添加活动表',
            area: ['500px', '300px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">表名(客户端)</span><input type="text" id="tb_name"  class="form-control filed-width"></div>' +
            '<div class="input-group"><span class="input-group-addon">表地址(服务器)</span><input type="text" id="tb_path1"  class="form-control filed-width"></div>' +
            '<div class="input-group"><span class="input-group-addon">表文件</span><form id="uploadForm"  enctype="multipart/form-data"><input id="file" accept=".xls,.xlsx" type="file" name="file"/></form></div>' +
            '</div>',
            yes: function (index2) {
                var formData = new FormData($('#uploadForm')[0]);
                var gi = $("#group").val();
                var tb_path = $("#tb_path1").val();
                var tb_name = $("#tb_name").val();
                $.ajax({
                    type: 'post',
                    url: location.href + '&jinIf=9111&gi='+gi+'&tb_path='+tb_path+'&tb_name='+tb_name,
                    data: formData,
                    cache: false,
                    dataType: 'json',
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    processData: false,
                    contentType: false,
                    success:function (json) {
                        layer.closeAll('loading');
                        if(json.status==1){
                            layer.alert(json.msg, {icon: 1}, function (index1) {
                                layer.close(index2);
                                layer.close(index1);
                                $("#file").val('');
                            });
                        }else{
                            layer.alert(json.msg, {icon: 0}, function (index1) {
                                layer.close(index2);
                                layer.close(index1);
                            });
                        }

                    }
                })
            }
        });
    });

    $("#jin_add2").on('click', function () {
        var formData = new FormData($('#uploadForm1')[0]);
        $.ajax({
            type: 'post',
            url: location.href + '&jinIf=9112',
            data: formData,
            cache: false,
            dataType: 'json',
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            processData: false,
            contentType: false,
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

    $("#group").on('change',function () {
        getTbPath();
    });
    function getTbPath() {
        $.ajax({
            type: "post",
            url: location.href + "&jinIf=9121",
            dataType: "json",
            data:{
                'gi': $("#group").val()
            },
            success: function (res) {
                $("#tb_path").html('').val('');
                var arr = [];
                for (var i = 0; i < res.length; i++) {
                    arr[i] = {//索引版配置
                        id: res[i].tb_path,
                        text: res[i].tb_name
                    }
                }
                if(arr.length>0){
                    $("#tb_path").select2({
                        data: arr,
                        placeholder: '请选择',
                        theme: "classic",
                        width: "200px",
                        multiple: false
                    }).trigger('change');
                }else {
                    $("#tb_path").html('').val('');
                }
                common();
            }
        });
    }



    $('#content').on('click', 'span[data-type="y3"]', function () {
        var id = $(this).parents('tr').find('td').eq(1).text();
        var filed_name = $(this).parents('tr').find('td').eq(5).text();
        updateTbHeadS(id,0,filed_name);
        return false;
    }).on('click', 'span[data-type="n3"]', function () {
        var id = $(this).parents('tr').find('td').eq(1).text();
        var filed_name = $(this).parents('tr').find('td').eq(5).text();
        updateTbHeadS(id,1,filed_name);
        return false;
    }).on('click', 'a[data-type="updatecommon"]', function () {
        var tb_name = $(this).parents('tr').find('td').eq(2).text();
        var tb_path1 = $(this).parents('tr').find('td').eq(3).text();
        var client_tb_id = $(this).parents('tr').find('td').eq(4).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '添加活动表',
            area: ['600px', '300px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">表名</span><input type="text" value="'+tb_name+'" id="tb_name"  class="form-control filed-width"></div>' +
            '<div class="input-group"><span class="input-group-addon">表地址</span><input type="text" value="'+tb_path1+'" id="tb_path1"  class="form-control filed-width"></div>' +
            '<div class="input-group"><span class="input-group-addon">客户端映射ID</span><input type="text" value="'+client_tb_id+'" id="client_tb_id"  class="form-control filed-width"></div>' +
            '</div>',
            yes: function (index1) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=915",
                    data: {
                        gi:$("#group").val(),
                        tb_path:$("#tb_path").val(),
                        tb_name:$("#tb_name").val(),
                        client_tb_id:$("#client_tb_id").val(),
                        tb_path1:$("#tb_path1").val()
                    },
                    dataType: "json",
                    success: function () {
                        layer.close(index1);
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                            getTbPath();
                        });
                    }
                });
            }
        });
    }).on('click', 'a[data-type="update"]', function () {  // 点击后本地数据库
        var id = $(this).parents('tr').find('td').eq(1).text();
        var filed_name = $(this).parents('tr').find('td').eq(5).text();
        var filed_annotation = $(this).parents('tr').find('td').eq(6).text();
        var client_col_id = $(this).parents('tr').find('td').eq(7).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '添加活动表',
            area: ['600px', '300px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">字段名</span><input type="text" value="'+filed_name+'" id="filed_name"  class="form-control filed-width"></div>' +
            '<div class="input-group"><span class="input-group-addon">字段注释</span><input type="text" value="'+filed_annotation+'" id="filed_annotation"  class="form-control filed-width"></div>' +
            '<div class="input-group"><span class="input-group-addon">客户端映射列ID</span><input type="text" value="'+client_col_id+'" id="client_col_id"  class="form-control filed-width"></div>' +
            '</div>',
            yes: function (index1) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=916",
                    data: {
                        gi:$("#group").val(),
                        tb_path:$("#tb_path").val(),
                        filed_name:filed_name,
                        filed_name1:$("#filed_name").val(),
                        filed_annotation:$("#filed_annotation").val(),
                        client_col_id:$("#client_col_id").val(),
                        id:id
                    },
                    dataType: "json",
                    success: function () {
                        layer.close(index1);
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                            common();
                        });
                    }
                });
            }
        });
    }).on('click', 'a[data-type="delete"]', function () {  // 点击后本地数据库
        var id = $(this).parents('tr').find('td').eq(1).text();
        var filed_name = $(this).parents('tr').find('td').eq(5).text();
        layer.alert('确认删除吗？请谨慎使用', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=914",
                data: {
                    id: id,
                    gi: $("#group").val(),
                    filed_name:filed_name,
                    tb_path: $("#tb_path").val()
                },
                dataType: "json",
                success: function (json) {
                    layer.alert('成功', {icon: 1}, function (index) {
                        layer.close(index);
                        common();
                    });
                }
            });
        });
    }).on('click', 'tr', function() {
        if($(this).index()==0){
            return false;
        }
        var cb = $(this).find('td:first>input');
        if (! cb.is(':checked')) {
            cb.attr('checked', true);
            $(this).attr('style', 'background: #aba5618c');
        } else {
            cb.attr('checked', false);
            $(this).removeAttr('style', 'background: #aba5618c');
        }
    });

    // 全选
    $('#all_choose').click(function() {
        var check_on = $(this).is(':checked');
        if (check_on) {
            $('#content').find('input[type="checkbox"]').attr('checked', true);
            $('#content').find('tr:not(:first-child)').attr('style', 'background: #aba5618c');
        } else {
            $('#content').find('input[type="checkbox"]').attr('checked', false);
            $('#content').find('tr:not(:first-child)').removeAttr('style', 'background: #aba5618c');
        }
    });
    // 获取选中的
    function getChoose() {
        var idss = '';
        var filed_name = "";
        $('#content input[type="checkbox"]:checked').each(function(index, el) {
            if (index == 0) {
                idss = $(el).val();
                filed_name = "'"+$(el).parent('td').siblings('td').eq(4).text()+"'";
            } else {
                idss += ',' + $(el).val();
                filed_name += ",'" + $(el).parent('td').siblings('td').eq(4).text()+"'";
            }
        });
        return {
            'idss':idss,
            'filed_name':filed_name
        };
    }
    $("#all_updateS1").on('click',function () {
        var getchoose = getChoose();
        var id = getchoose.idss;
        var filed_name = getchoose.filed_name;
        updateAllTbHeadS(id,1,filed_name)
    });
    $("#all_updateS2").on('click',function () {
        var getchoose = getChoose();
        var id = getchoose.idss;
        var filed_name = getchoose.filed_name;
        updateAllTbHeadS(id,0,filed_name)
    });

    $("#sync0").on('click', function () {  // 点击后本地数据库
        sync(0);
    });
    $("#sync1").on('click', function () {  // 点击后本地数据库
        sync(1);
    });
    $("#sync2").on('click', function () {  // 点击后本地数据库
        sync(2);
    });
    function sync(s_type) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '同步数据',
            area: ['500px', '600px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">目标渠道</span>' +
            '<div class="select_group_div" style="width: 60%;">'+
            '<select id="g_other" class="selectpicker show-tick " multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
            '</div>'+
            '</div>' +
            '</div>',
            success: function (index) {
                obj11 = {id: '#g_other'};
                obj11.url = location.href + "&jinIf=919&s_type="+s_type;
                groups(obj11);
            },
            yes: function (index1) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=917",
                    data: {
                        gi: $("#group").val(),
                        tb_path: $("#tb_path").val(),
                        gig: $("#g_other").val(),
                        s_type:s_type
                    },
                    dataType: "json",
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        if(json.status==1){
                            layer.alert('成功', {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        }else{
                            layer.alert(json.msg, {icon: 2}, function (index) {
                                layer.close(index);
                            });
                        }
                    }
                });
            }
        });
    }

    function updateTbHeadS(id,s_type,filed_name) {
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=9132",
            data: {
                id: id,
                s_type:s_type,
                gi:$("#group").val(),
                tb_path:$("#tb_path").val(),
                filed_name:filed_name
            },
            dataType: 'json',
            success: function () {
                common();
            }
        });
    }

    function updateAllTbHeadS(id,s_type,filed_name) {
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=9133",
            data: {
                id: id,
                s_type:s_type,
                gi:$("#group").val(),
                tb_path:$("#tb_path").val(),
                filed_name:filed_name
            },
            dataType: 'json',
            success: function () {
                common();
            }
        });
    }

<?php echo '</script'; ?>
><?php }
}
