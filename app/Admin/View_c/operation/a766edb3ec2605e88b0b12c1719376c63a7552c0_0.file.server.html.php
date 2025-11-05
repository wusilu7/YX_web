<?php
/* Smarty version 3.1.30, created on 2023-03-14 13:34:20
  from "/lnmp/www/app/Admin/View/operation/server.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6410075c0c9893_08115769',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a766edb3ec2605e88b0b12c1719376c63a7552c0' => 
    array (
      0 => '/lnmp/www/app/Admin/View/operation/server.html',
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
function content_6410075c0c9893_08115769 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.06.server.css" rel="stylesheet">

<style type="text/css">
    .server_red{color: red;}
    .server_green{color: green;}
    #open_other_ip{
        width: 50px;
        zoom: 140%;
    }
</style>
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>服务器配置</span></div>
<div class="jin-server-select">
    <?php if ($_smarty_tpl->tpl_vars['Mobel']->value == 'Mobel') {?>
    <div class="form-group" id="group_server_6_mobel"></div>
    <?php } else { ?>
    <div class="form-group" id="group_server_6"></div>
    <?php }?>
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    <button data-type="insert" class="btn btn-primary left">新增服务器</button>
    <input type="checkbox" id="sort"/>
    <button data-type="preserve" class="jin-hidden btn btn-sm btn-danger">保存排序</button>
    <input size="16" type="checkbox" id="ischeck" value="1">
    <label for="ischeck">显示服配置信息</label>
</div>
<div class="jin-server-select">
    <div>
        <label for="server_name">筛选：</label>
        <input id="server_name" type="text" class="form-control jin-search-input" placeholder="服务器名字(模糊匹配)">
    </div>
    <br>
    <button data-type="all_maintenance" class="btn btn-info">批量维护</button>
    <button data-type="all_cancel" class="btn btn-warning">批量取消维护</button>
    <button data-type="all_change" class="btn btn-primary">批量修改</button>
    <button data-type="all_change_wid" class="btn btn-primary">批量修改world_id</button>
    <button data-type="all_change_netState" class="btn btn-primary">批量修改网络状态</button>
    <button data-type="all_change_isNew" class="btn btn-primary">批量修改新服标记</button>
    <button data-type="all_change_isOnline" class="btn btn-info">批量汇总</button>
    <button data-type="all_change_isShow" class="btn btn-info">批量显示</button>
    <!--<button data-type="all_change_isNotice" class="btn btn-info">批量显示公告</button>-->
    <!--<button data-type="all_change_appVersion" class="btn btn-info">批量修改客户端服务器版本号</button>-->
    <button data-type="all_change_funcmask" class="btn btn-primary">批量修改游戏掩码</button>
    <button data-type="all_open" class="btn btn-info">批量开服脚本</button>
    <button data-type="all_close" class="btn btn-warning">批量关服脚本</button>
    <button data-type="all_check" class="btn btn-primary">检测配置</button>
    <button data-type="all_excel_dau" class="btn btn-warning">导出dau数据(合服)</button>
</div>
<div class="table-responsive">
    <table class="table table-bordered table-striped text-center jin-server-table">
        <thead>
        <tr>
            <th class="jin-server-column1">
                <input id="all_choose" type="checkbox">
                <label for="all_choose">全选</label>
            </th>
            <th class="jin-server-column1">序号</th>
            <th class="jin-server-column2">服务器ID</th>
            <th>服务器名称</th>
            <th style="width: 100px;">渠道名称</th>
            <th>world_<br>id</th>
            <th>world_<br>id_<br>son</th>
            <th>platfrom_<br>id</th>
            <th>world_<br>time</th>
            <th>file_path</th>
            <th>server_<br>group_<br>id</th>
            <th>IP地址<br>SOAP地址</th>
            <th>端口</th>
            <th>ip白名单</th>
            <th>app</th>
            <th>资源</th>
            <th>状态</th>
            <th>汇总</th>
            <th>显示</th>
            <th>显示公告</th>
            <th>服配置<br>信息</th>
            <th class="jin-server-column5">操作</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
    <div class="jin-explain  clearfix">
        <b>说明</b>：
        <div>
            ①点击渠道下拉框切换渠道，<b>新增服务器</b>默认归属在当前所选渠道下。
        </div>
        <div>
            ②点击<b>排序按钮</b>开启排序，排序完成记得保存。
        </div>
        <div>
            ③线上表示该服务器计入汇总功能，线下表示本地服务器或者不计入汇总功能。
        </div>
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 type="text/javascript">
    var isMultilingualClass='';
    if(eval('<?php echo $_smarty_tpl->tpl_vars['isMultilingual']->value;?>
')==0){
        isMultilingualClass='hide'
    }
    var url = location.href + '&jinIf=912';
    var checkbox = function (json) {
        return '<input type="checkbox" data-data-info1="'+json.info+'" ' +
            'data-data-info2="'+json.info2+'" ' +
            'data-data-info3="'+json.info3+'" ' +
            'data-data-info4="'+json.info4+'" ' +
            'data-data-info5="'+json.info5+'" ' +
            'data-data-info6="'+json.info6+'" ' +
            'data-data-info7="'+json.info7+'" ' +
            'data-data-info8="'+json.info8+'" ' +
            'data-data-info9="'+json.info9+'" ' +
            'data-data-info10="'+json.info10+'" ' +
            'data-data-info11="'+json.info11+'"  value="' + json.server_id + '" />';
    }
    var btn = [
        '<div class="btn-group btn-group-sm">' +
        '<a data-type="update" class="btn btn-default">基本配置</a><a data-type="advance" class="btn btn-primary">高级配置</a><br>' +
        '<a data-type="maintenance" class="btn btn-info">点击维护</a><a data-type="cancel" class="btn btn-warning">取消维护</a><br>' +
        '<a data-type="copy" class="btn btn-success">复制</a><a data-type="delete" class="btn btn-danger">删除</a>' +
        '</div>'
    ];
    var arr = [checkbox,'sort', 'server_id',  'name', 'group_name','world_id', 'world_id_son', 'platfrom_id', 'world_time','file_path','server_group_id','game_dn', 'game_port', 'white_ip', 'app_version', 'res_version', 'state', 'online', 'is_show','is_show_notice','sql1', btn];
    var id = "#content";
    var data = {};
    //选渠道下拉框（定制版，可选择所有）
    gsSelect3('#g');
    
    // 渠道类型定制版
    function groupSelectAll(obj) {
        obj = isExist(obj, {});
        obj.dom = isExist(obj.dom, "#group");
        obj.width = isExist(obj.width, "230px");
        obj.url = isExist(obj.url, "?p=Admin&c=Operation&a=group&jinIf=9421");
        obj.id = isExist(obj.id, "group_id");
        obj.text = isExist(obj.text, "group_name");
        jinSelect(obj);
    }
    $('#ischeck').on('click', function () {
        if($('#ischeck').is(':checked')){
            arr[9] = 'file_path1';
            arr[20] = 'sql';
        }else{
            arr[9] = 'file_path';
            arr[20] = 'sql1';
        }
        noPageContentList(url, data, id, arr,true);
    });
    if ($.cookie('cookie_g')) {
        data.group_id = eval('[' + $.cookie('cookie_g') + ']');
        noPageContentList(url, data, id, arr,true);
    }
    $("#jin_search").on('click', function () {
        data.group_id = $("#g").val();
        data.server_name = $("#server_name").val();
        $.cookie('cookie_g', data.group_id, {expires: 30});
        noPageContentList(url, data, id, arr,true);
    });

    //新增服务器
    $('button[data-type="insert"]').on('click', function () {
        var docW = window.screen.width;
        if(docW < 768){
            layerW = '80%';
            layerH = '80%';
        }else{
            layerW = '450px';
            layerH = '700px;';
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '新增服务器',
            area: [layerW,layerH],
            btn: ['新增', '取消'],
            btnAlign: 'c',
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">服务器名称</span><input id="name" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">IP地址</span><input id="game_dn" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">端口</span><input id="game_port" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">soap地址</span><input id="soap_add" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">soap端口</span><input id="soap_port" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">Account 地址</span><input id="a_add" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">Account 端口</span><input id="a_port" type="text" class="form-control" value="3306"></div>' +
            '<div class="input-group"><span class="input-group-addon">Account 用户</span><input id="a_user" type="text" class="form-control" value="mjgame"></div>' +
            '<div class="input-group"><span class="input-group-addon">Account 密码</span><input id="a_pw" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">Account 前缀</span><input id="a_prefix" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">Game 地址</span><input id="g_add" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">Game 端口</span><input id="g_port" type="text" class="form-control" value="3306"></div>' +
            '<div class="input-group"><span class="input-group-addon">Game 用户</span><input id="g_user" type="text" class="form-control" value="mjgame"></div>' +
            '<div class="input-group"><span class="input-group-addon">Game 密码</span><input id="g_pw" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">Game 前缀</span><input id="g_prefix" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">Log 地址</span><input id="l_add" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">Log 端口</span><input id="l_port" type="text" class="form-control" value="3306"></div>' +
            '<div class="input-group"><span class="input-group-addon">Log 用户</span><input id="l_user" type="text" class="form-control" value="mjgame"></div>' +
            '<div class="input-group"><span class="input-group-addon">Log 密码</span><input id="l_pw" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">Log 前缀</span><input id="l_prefix" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">新增数量</span><input id="s_num" type="text" class="form-control" value="1"></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=911',
                    data: {
                        group_id: $('#g').val(),
                        name: $('#name').val(),
                        game_dn: $('#game_dn').val(),
                        game_port: $('#game_port').val(),
                        soap_add: $('#soap_add').val(),
                        soap_port: $('#soap_port').val(),
                        a_add: $('#a_add').val(),
                        a_port: $('#a_port').val(),
                        a_user: $('#a_user').val(),
                        a_pw: $('#a_pw').val(),
                        a_prefix: $('#g_prefix').val(),
                        g_add: $('#g_add').val(),
                        g_port: $('#g_port').val(),
                        g_user: $('#g_user').val(),
                        g_pw: $('#g_pw').val(),
                        g_prefix: $('#g_prefix').val(),
                        l_add: $('#l_add').val(),
                        l_port: $('#l_port').val(),
                        l_user: $('#l_user').val(),
                        l_pw: $('#l_pw').val(),
                        l_prefix: $('#l_prefix').val(),
                        s_num:$('#s_num').val()
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (res) {
                        layer.closeAll('loading');
                        if (res) {
                            layer.close(index);
                            layer.alert('添加成功', {icon: 1}, function (index) {
                                layer.close(index);
                                noPageContentList(url, data, id, arr);
                            });  
                        }
                    }
                });
            },
            cancel: function () {
            }
        })
    });

    // 全选
    $('#all_choose').click(function() {
        var check_on = $(this).is(':checked');
        if (check_on) {
            $('#content').find('input[type="checkbox"]').attr('checked', true);
            $('#content').find('tr').attr('style', 'background: #aba5618c');
        } else {
            $('#content').find('input[type="checkbox"]').attr('checked', false);
            $('#content').find('tr').removeAttr('style', 'background: #aba5618c');
        }
    });

    // 获取选中的服务器
    function getChoose() {
        var server_id = '';
        var name = '';
        var first_server = '';

        $('#content input[type="checkbox"]:checked').each(function(index, el) {
            if (index == 0) {
                server_id = $(el).val();
                name = $(el).parent('td').siblings('td').eq(2).text();
                first_server = $(el).val();
            } else {
                server_id += ',' + $(el).val();
                name += ',' + $(el).parent('td').siblings('td').eq(2).text();
            }
        });

        return {
            'server_id': server_id,
            'name': name,
            'first_server':first_server
        };
    }

    // 点击批量维护
    $('button[data-type="all_maintenance"]').click(function() {
        var arr = getChoose();
        // console.log(arr);
        // return false;
        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            maintenance(arr.server_id, arr.name,['','','','','','','','','','','']);
        }
    });

    // 批量维护
    function maintenance(server_id, name,info) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '服务器维护设置',
            area: ['400px', '500px'],
            btn: ['确认维护', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon"><input name="infocheck" type="radio" value="1">时间维护</span><input type="text" id="info_time" class="form-control jin-datetime-long"></div>' +
            '<div class="input-group '+isMultilingualClass+'">' +
            '<ul class="nav nav-tabs">' +
            '<li  class="active"><a href="#nav_content11" data-toggle="tab">中文</a></li>' +
            '<li><a href="#nav_content22" data-toggle="tab">繁体</a></li>' +
            '<li><a href="#nav_content33" data-toggle="tab">英语</a></li>' +
            '<li><a href="#nav_content44" data-toggle="tab">西班牙</a></li>' +
            '<li><a href="#nav_content55" data-toggle="tab">阿拉伯语</a></li>' +
            '<li><a href="#nav_content66" data-toggle="tab">俄语</a></li>' +
            '<li><a href="#nav_content77" data-toggle="tab">泰文</a></li>' +
            '<li><a href="#nav_content88" data-toggle="tab">巴西</a></li>' +
            '<li><a href="#nav_content99" data-toggle="tab">印尼</a></li>' +
            '<li><a href="#nav_content1010" data-toggle="tab">日本</a></li>' +
            '<li><a href="#nav_content1111" data-toggle="tab">韩文</a></li>' +
            '</ul>' +
            '</div>' +
            '<div class="input-group"><span class="input-group-addon"><input checked name="infocheck" type="radio" value="2">公告维护</span>' +
            '<div class="tab-content" style="padding-top: 0px;">' +
            '<div class="tab-pane active" id="nav_content11"><textarea id="info1" rows="9"  class="form-control">'+info[0]+'</textarea></div>' +
            '<div class="tab-pane" id="nav_content22"><textarea id="info2" rows="9"  class="form-control">'+info[1]+'</textarea></div>' +
            '<div class="tab-pane" id="nav_content33"><textarea id="info3" rows="9"  class="form-control">'+info[2]+'</textarea></div>' +
            '<div class="tab-pane" id="nav_content44"><textarea id="info4" rows="9"  class="form-control">'+info[3]+'</textarea></div>' +
            '<div class="tab-pane" id="nav_content55"><textarea id="info5" rows="9"  class="form-control">'+info[4]+'</textarea></div>' +
            '<div class="tab-pane" id="nav_content66"><textarea id="info6" rows="9"  class="form-control">'+info[5]+'</textarea></div>' +
            '<div class="tab-pane" id="nav_content77"><textarea id="info7" rows="9"  class="form-control">'+info[6]+'</textarea></div>' +
            '<div class="tab-pane" id="nav_content88"><textarea id="info8" rows="9"  class="form-control">'+info[7]+'</textarea></div>' +
            '<div class="tab-pane" id="nav_content99"><textarea id="info9" rows="9"  class="form-control">'+info[8]+'</textarea></div>' +
            '<div class="tab-pane" id="nav_content1010"><textarea id="info10" rows="9"  class="form-control">'+info[9]+'</textarea></div>' +
            '<div class="tab-pane" id="nav_content1111"><textarea id="info11" rows="9"  class="form-control">'+info[10]+'</textarea></div>' +
            '</div>' +
            '</div>' +
            '</div>',
            yes: function (index) {
                if(!$('input[name="infocheck"]:checked').val()){
                    layer.msg('选择维护类型!');
                    return false;
                }
                layer.alert('确认维护[' + name + ']服务器？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + "&jinIf=9131",
                        data: {
                            group_name: $('#select2-group-container').text(),
                            server_name: name,
                            server_id: server_id,
                            info_time : $("#info_time").val(),
                            info1: $('#info1').val(),
                            info2: $('#info2').val(),
                            info3: $('#info3').val(),
                            info4: $('#info4').val(),
                            info5: $('#info5').val(),
                            info6: $('#info6').val(),
                            info7: $('#info7').val(),
                            info8: $('#info8').val(),
                            info9: $('#info9').val(),
                            info10: $('#info10').val(),
                            info11: $('#info11').val(),
                            info_type:$('input[name="infocheck"]:checked').val()
                        },
                        beforeSend: function () {
                            layer.load(2, {
                                shade: [0.3, '#fff']//0.3透明度的白色背景
                            });
                        },
                        success: function (json) {
                            layer.closeAll('loading');
                            var arr = $.parseJSON(json);
                            if (arr.status == 1) {
                                layer.close(index);
                                layer.alert(arr.msg, {icon: 1}, function (index) {
                                    layer.close(index);
                                    history.go(0);
                                });
                            } else {
                                layer.alert(arr.msg, {icon: 2});
                            }
                        }
                    });
                });
            },
            cancel: function () {
            }
        });
        $(document).ready(calendarOne('hour', "#info_time"));
    }


    // 点击批量维护
    $('button[data-type="all_open"]').click(function() {
        var arr = getChoose();
        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            all_open(arr.server_id, arr.name,'开启',1);
        }
    });

    // 点击批量维护
    $('button[data-type="all_close"]').click(function() {
        var arr = getChoose();
        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            all_open(arr.server_id, arr.name,'关闭',0);
        }
    });

    // 批量维护
    function all_open(server_id, name,info,status) {
        layer.alert('确认'+info+'[' + name + ']服务器？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9145",
                data: {
                    server_id: server_id,
                    status:status
                },
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                dataType: "json",
                success: function (json) {
                    layer.closeAll('loading');
                    var cc='';
                    for (var i=0;i<json.length;i++){
                        cc+='<span style="color: red;">'+json[i].si+'服务器:</span>'+json[i].msg+'<br>'
                    }
                    layer.alert(cc, {icon: 6,area:["600px","400px"]}, function (index) {
                        layer.close(index);
                    });
                }
            });
        });
    }

    // 点击批量取消维护
    $('button[data-type="all_cancel"]').click(function() {
        var arr = getChoose();
        // console.log(arr);
        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            cancel(arr.server_id, arr.name);
        }
    });

    // 批量取消维护
    function cancel(server_id, name) {
        layer.alert('确认取消[' + name + ']服务器的维护状态？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9132",
                data: {
                    group_name: $('#select2-group-container').text(),
                    server_name: name,
                    server_id: server_id
                },
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
                    var arr = $.parseJSON(json);
                    if (arr.status == 1) {
                        layer.alert(arr.msg, {icon: 1}, function (index) {
                            layer.close(index);
                            history.go(0);
                        });
                    } else {
                        layer.alert(arr.msg, {icon: 2});
                    }
                }
            });
        });
    }

    // 批量修改
    $('button[data-type="all_change"]').click(function() {
        var arr = getChoose();
        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            change(arr.server_id, arr.name);
        }
    });

    // 打开批量修改界面
    function change(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '批量修改',
            area: ['400px', '500px'],
            btn: ['修改', '取消'],
            btnAlign: 'c',
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">ip白名单</span><input id="white_ip" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">app版本号</span><input id="app_version" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">资源版本号</span><input id="res_version" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">设备白名单</span><textarea placeholder="多个用|分割"  id="white_code" class="form-control" cols="30" rows="4"></textarea></div>' +
            '<div class="input-group"><span class="input-group-addon">账号白名单</span><textarea placeholder="多个用|分割" id="white_acc" class="form-control" cols="30" rows="4"></textarea></div>' +
            '</div>',
            yes: function (yes) {
                layer.alert('确认修改[' + name + ']服务器？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + '&jinIf=915',
                        data: {
                            group_name: $('#select2-group-container').text(),
                            server_name: name,
                            server_id: server_id,
                            white_ip: $('#white_ip').val(),
                            app_version: $('#app_version').val(),
                            res_version: $('#res_version').val(),
                            white_code: $('#white_code').val(),
                            white_acc: $('#white_acc').val(),
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            layer.load(2, {
                                shade: [0.3, '#fff']//0.3透明度的白色背景
                            });
                        },
                        success: function (res) {
                            layer.closeAll('loading');
                            if (res.status == 1) {
                                layer.close(yes);
                                layer.alert('修改成功', {icon: 1}, function (success) {
                                    layer.close(success);
                                    history.go(0);
                                });
                            } else {
                                layer.msg(res.msg, {icon: 2});
                            }
                        }
                    });
                });
            },
            cancel: function () {
            }
        })
    }

    // 批量修改world_id
    $('button[data-type="all_change_wid"]').click(function(name) {
        var arr = getChoose();
        
        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            updatewid(arr.server_id, arr.name);
        }
    });

    function updatewid(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + 'world_id修改',
            area: ['400px', '240px'],
            btn: ['确认修改', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">world_id</span><input id="world_id" type="text" class="form-control"></div>' + 
            '<div class="input-group"><span class="input-group-addon">platfrom_id</span><input id="platfrom_id" type="text" class="form-control"></div>' +        
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=916",
                    data: {
                        world_id:$('#world_id').val(),
                        platfrom_id:$('#platfrom_id').val(),
                        server_name: name,
                        server_id: server_id,
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        var arr = $.parseJSON(json);
                        if (arr.status == 1) {
                            layer.close(index);
                            layer.alert('修改成功', {icon: 0}, function (success) {
                                layer.close(success);
                                history.go(0);
                            });
                        } else {
                            layer.alert(arr.msg, {icon: 1});
                        }
                    }
                }); 
            },
            cancel: function () {
            }
        });
    }

    // 批量修改网络状态
    $('button[data-type="all_change_netState"]').click(function(name) {
        var arr = getChoose();

        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            updatenetState(arr.server_id, arr.name);
        }
    });

    function updatenetState(server_id, name) {
        var c='';
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=941",
            dataType: 'json',
            success: function (res) {
                c+='<div class="col-sm-12">' +
                    '<select id="state" class="col-sm-6 col-md-offset-3">';
                for (var i = 0; i < res.length; i++) {
                    c+='<option value="'+i+'">'+res[i]+'</option>';
                }
                c+='</select>'+
                    '</div>';
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '[' + name + ']' + '网络状态修改',
                    area: ['400px', '200px'],
                    btn: ['确认修改', '取消'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content: c,
                    yes: function (index) {
                        $.ajax({
                            type: "POST",
                            url: location.href + "&jinIf=917",
                            data: {
                                state: $('#state').val(),
                                server_name: name,
                                server_id: server_id,
                            },
                            beforeSend: function () {
                                layer.load(2, {
                                    shade: [0.3, '#fff']//0.3透明度的白色背景
                                });
                            },
                            success: function (json) {
                                layer.closeAll('loading');
                                var arr = $.parseJSON(json);
                                if (arr.status == 1) {
                                    layer.close(index);
                                    layer.alert('修改成功', {icon: 0}, function (success) {
                                        layer.close(success);
                                        history.go(0);
                                    });
                                } else {
                                    layer.alert(arr.msg, {icon: 1});
                                }
                            }
                        });
                    },
                    cancel: function () {
                    }
                });
            }
        });


    }

    // 批量修改新服标记
    $('button[data-type="all_change_isNew"]').click(function(name) {
        var arr = getChoose();

        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            updateisNew(arr.server_id, arr.name);
        }
    });

    function updateisNew(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '新服标记修改',
            area: ['400px', '240px'],
            btn: ['确认修改', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">新服标记</span><input id="tab" type="text" class="form-control"></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=918",
                    data: {
                        tab:$('#tab').val(),
                        server_name: name,
                        server_id: server_id,
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        var arr = $.parseJSON(json);
                        if (arr.status == 1) {
                            layer.close(index);
                            layer.alert('修改成功', {icon: 0}, function (success) {
                                layer.close(success);
                                history.go(0);
                            });
                        } else {
                            layer.alert(arr.msg, {icon: 1});
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    }

    // 批量汇总
    $('button[data-type="all_change_isOnline"]').click(function(name) {
        var arr = getChoose();

        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            updateisOnline(arr.server_id, arr.name);
        }
    });

    function updateisOnline(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '是否汇总修改',
            area: ['300px', '150px'],
            btn: ['确认修改', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<select id="Online" class="col-sm-4 col-md-offset-4">'+
            '<option value="1">汇总</option> '+
            '<option value="0">不汇总</option> '+
            '</select>',
            yes: function (index) {
                var url11 ='';
                if($('#Online').val()==1){
                    url11 =location.href + "&jinIf=9137";
                }else{
                    url11 =location.href + "&jinIf=9138";
                }
                $.ajax({
                    type: "POST",
                    url: url11,
                    data: {
                        server_id: server_id,
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        var arr = $.parseJSON(json);
                        if (arr.status == 1) {
                            layer.close(index);
                            layer.alert('修改成功', {icon: 0}, function (success) {
                                layer.close(success);
                                history.go(0);
                            });
                        } else {
                            layer.alert(arr.msg, {icon: 1});
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    }

    // 批量显示
    $('button[data-type="all_change_isShow"]').click(function(name) {
        var arr = getChoose();

        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            updateisShow(arr.server_id, arr.name);
        }
    });

    function updateisShow(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '是否显示修改',
            area: ['300px', '150px'],
            btn: ['确认修改', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<select id="Show" class="col-sm-4 col-md-offset-4">'+
            '<option value="1">显示</option> '+
            '<option value="0">不显示</option> '+
            '</select>',
            yes: function (index) {
                var url11 ='';
                if($('#Show').val()==1){
                    url11 =location.href + "&jinIf=9133";
                }else{
                    url11 =location.href + "&jinIf=9134";
                }
                $.ajax({
                    type: "POST",
                    url: url11,
                    data: {
                        server_id: server_id,
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        var arr = $.parseJSON(json);
                        if (arr.status == 1) {
                            layer.close(index);
                            layer.alert('修改成功', {icon: 0}, function (success) {
                                layer.close(success);
                                history.go(0);
                            });
                        } else {
                            layer.alert(arr.msg, {icon: 1});
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    }

    // 批量显示公告
    $('button[data-type="all_change_isNotice"]').click(function(name) {
        var arr = getChoose();

        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            updateisNotice(arr.server_id, arr.name);
        }
    });

    function updateisNotice(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '是否显示公告',
            area: ['300px', '150px'],
            btn: ['确认修改', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<select id="Show" class="col-sm-4 col-md-offset-4">'+
            '<option value="1">显示</option> '+
            '<option value="0">不显示</option> '+
            '</select>',
            yes: function (index) {
                var url11 ='';
                if($('#Show').val()==1){
                    url11 =location.href + "&jinIf=9142";
                }else{
                    url11 =location.href + "&jinIf=9143";
                }
                $.ajax({
                    type: "POST",
                    url: url11,
                    data: {
                        server_id: server_id,
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        var arr = $.parseJSON(json);
                        if (arr.status == 1) {
                            layer.close(index);
                            layer.alert('修改成功', {icon: 0}, function (success) {
                                layer.close(success);
                                history.go(0);
                            });
                        } else {
                            layer.alert(arr.msg, {icon: 1});
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    }

    // 批量修改客户端服务器版本号
    $('button[data-type="all_change_appVersion"]').click(function(name) {
        var arr = getChoose();

        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            updateappVersion(arr.server_id, arr.name);
        }
    });

    function updateappVersion(server_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '批量修改客户端服务器版本号修改',
            area: ['400px', '240px'],
            btn: ['确认修改', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">版本号</span><input id="version" type="text" class="form-control"></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=919",
                    data: {
                        version:$('#version').val(),
                        server_id: server_id
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        if (json == 1) {
                            layer.close(index);
                            layer.alert('修改成功', {icon: 1}, function (success) {
                                layer.close(success);
                                history.go(0);
                            });
                        } else {
                            layer.alert('修改失败', {icon: 2}, function (index) {
                                layer.close(index);
                            })
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    }

    // 批量修改主播
    $('button[data-type="all_change_funcmask"]').click(function(name) {
        var arr = getChoose();

        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            updateFuncmask(arr.server_id, arr.name,arr.first_server);
        }
    });

    function updateFuncmask(server_id, name,first_server) {
        var c='';
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=942&si="+first_server,
            dataType: "json",
            async:false,
            success: function (json) {
                for(var i=0;i<json.length;i++){
                    c+='<input type="checkbox" id="funcmask_'+i+'" name="funcmask" value="'+json[i].value+'"  style="zoom:150%;"  />'+
                        '<label for="funcmask_'+i+'" style=" vertical-align:middle;">'+json[i].name+'</label>'
                }
                c +='<input id="funcmask" class="form-control" placeholder=""/>';
            }
        });

        layer.open({
            type: 1,
            closeBtn: 2,
            title: '[' + name + ']' + '批量修改游戏掩码',
            area: ['800px', '240px'],
            btn: ['确认修改', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">游戏功能掩码开关</span>'+c+'</div>' +
            '</div>',
            success:function (index) {
                $(":checkbox").click(function () {
                    var c2 = eval(checkedValue("funcmask").join("+"));
                    $('#funcmask').val(c2);
                });
                //输入框联动勾选
                $('#funcmask').on('input',function () {
                    $("[name='funcmask']").prop("checked", false);
                    var  arr=[];
                    var  str=parseInt($(this).val()).toString(2);
                    for (var k = 1; k <= str.length; k++){
                        arr.push(str.substr(-k,1))
                    }
                    for (var k = 0; k < arr.length; k++) {
                        if(arr[k]==1){
                            $('#funcmask_' + k).prop("checked", true);
                        }
                    }
                });
            },
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=920",
                    data: {
                        funcmask:$('#funcmask').val(),
                        server_id: server_id
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        var arr = $.parseJSON(json);
                        if (arr.status == 1) {
                            layer.close(index);
                            layer.alert('修改成功', {icon: 0}, function (success) {
                                layer.close(success);
                                history.go(0);
                            });
                        } else {
                            layer.alert(arr.msg, {icon: 1});
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    }

    $('#content').on('click', 'a[data-type="update"]', function () {  // 基础修改
        removeBackgroud($(this))
        var server_id = $(this).parents('tr').find('td').eq(2).text();
        var name = '';
        var game_dn = '';
        var game_port = '';
        var app_version = '';
        var res_version = '';
        var u_sort = '';
        var remain = '';
        var white_ip = '';
        var white_code = '';
        var white_acc = '';
        var world_id = '';
        var platfrom_id = '';

        var game_dn2 = '';
        var game_port2 = '';
        var game_dn3 = '';
        var game_port3 = '';
        var game_dn4 = '';
        var game_port4 = '';
        $.ajax({
            url: location.href + "&jinIf=9121",
            type: 'POST',
            data: {
                server_id: server_id
            },
            dataType: "json",
            success: function (res) {
                name = res.name;
                game_dn = res.game_dn;
                game_port = res.game_port;
                soap_add = res.soap_add;
                soap_port = res.soap_port;
                app_version = res.app_version;
                res_version = res.res_version;
                u_sort = res.sort;
                remain = res.remain;
                white_ip = res.white_ip;
                white_code = res.white_code;
                white_acc = res.white_acc;
                world_id = res.world_id;
                world_id_son = res.world_id_son;
                platfrom_id = res.platfrom_id;
                app_server_version = res.app_server_version;
                check1 = '';
                if (res.open_other_ip.includes(1) == true) {
                    check1 = 'checked';
                }
                check8 = '';
                if (res.device_type.includes(8) == true) {
                    check8 = 'checked';
                }
                check11 = '';
                if (res.device_type.includes(11) == true) {
                    check11 = 'checked';
                }
                check0 = '';
                if (res.device_type.includes(0) == true) {
                    check0 = 'checked';
                }
                check7 = '';
                if (res.device_type.includes(7) == true) {
                    check7 = 'checked';
                }

                var docW = window.screen.width;
                layerW = '500px';
                layerH = '700px;';
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '服务器配置修改',
                    area: [layerW, layerH],
                    btn: ['修改', '取消'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content: '<div class="jin-child">' +
                    '<div class="input-group"><span class="input-group-addon">服务器ID</span><input id="server_id" type="text" class="form-control" value="' +
                    server_id + '" readonly></div>' +
                    '<div class="input-group"><span class="input-group-addon">服务器名称</span><input id="name" type="text" class="form-control" value="' +
                    name + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">IP白名单</span><input id="white_ip" type="text" placeholder="多个用|分割" class="form-control" value="' +
                    white_ip + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">设备白名单</span><textarea placeholder="多个用|分割"  id="white_code" class="form-control" cols="30" rows="4">'+white_code+'</textarea></div>' +
                    '<div class="input-group"><span class="input-group-addon">账号白名单</span><textarea placeholder="多个用|分割" id="white_acc" class="form-control" cols="30" rows="4">'+white_acc+'</textarea></div>' +
                    '<div class="input-group"><span class="input-group-addon">设备类型</span>' +
                    '<input name="device_type" type="checkbox" '+check8+' value="8" style="width:20px;height:20px;">IOS(8)<input name="device_type" '+check11+' type="checkbox" value="11" style="width:20px;height:20px;">安卓(11)<br>' +
                    '<input name="device_type" type="checkbox" '+check7+' value="7" style="width:20px;height:20px;">windows(7)<input name="device_type" '+check0+' type="checkbox" value="0" style="width:20px;height:20px;">mac(0)' +
                    '</div>' +
                    '<div class="input-group"><span class="input-group-addon"><input name="check" type="checkbox" value="1" '+check1+'> IP地址</span><input id="game_dn" type="text" class="form-control" value="' +
                    game_dn + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">端口</span><input id="game_port" type="text" class="form-control" value="' +
                    game_port + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">SOAP 地址</span><input id="soap_add" type="text" class="form-control" value="' +
                    soap_add + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">SOAP 端口</span><input id="soap_port" type="text" class="form-control" value="' +
                    soap_port + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">app版本号</span><input id="app_version" placeholder="多个用|分割" type="text" class="form-control" value="' +
                    app_version + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">资源版本号</span><input id="res_version" placeholder="多个用|分割" type="text" class="form-control" value="' +
                    res_version + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">客户端服务器版本号</span><input id="app_server_version" disabled type="text" class="form-control" value="' +
                    app_server_version + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">排序</span><input id="u_sort" type="text" class="form-control" value="' +
                    u_sort + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">主服id</span><input id="world_id" type="text" class="form-control" value="' +
                    world_id + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">子服id</span><input id="world_id_son" type="text" class="form-control" value="' +
                    world_id_son + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">平台id</span><input id="platfrom_id" type="text" class="form-control" value="' +
                    platfrom_id + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">备注</span><input id="remain" type="text" class="form-control" value="' +
                    remain + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">创建人</span><input disabled  type="text" class="form-control" value="' +
                    res.create_user + '"></div>' +
                    '<div class="input-group"><span class="input-group-addon">创建时间</span><input disabled type="text" class="form-control" value="' +
                    res.create_time + '"></div>' +
                    '</div>',
                    yes: function (index) {
                        var chk_value =[]; 
                        $('input[name="check"]:checked').each(function(){ 
                            chk_value.push($(this).val()); 
                        });
                        var chk_value1 =[];
                        $('input[name="device_type"]:checked').each(function(){
                            chk_value1.push($(this).val());
                        });
                        $.ajax({
                            type: "POST",
                            url: location.href + '&jinIf=913',
                            data: {
                                server_id: $('#server_id').val(),
                                name: $('#name').val(),
                                game_dn: $('#game_dn').val(),
                                white_ip: $('#white_ip').val(),
                                white_code: $('#white_code').val(),
                                white_acc: $('#white_acc').val(),
                                game_port: $('#game_port').val(),
                                soap_add: $('#soap_add').val(),
                                soap_port: $('#soap_port').val(),
                                app_version: $('#app_version').val(),
                                res_version: $('#res_version').val(),
                                sort: $('#u_sort').val(),
                                world_id: $('#world_id').val(),
                                world_id_son: $('#world_id_son').val(),
                                platfrom_id: $('#platfrom_id').val(),
                                remain: $('#remain').val(),
                                device_type:chk_value1,
                                open_other_ip:chk_value
                            },
                            beforeSend: function () {
                                layer.load(2, {
                                    shade: [0.3, '#fff']//0.3透明度的白色背景
                                });
                            },
                            success: function () {
                                layer.closeAll('loading');
                                layer.close(index);
                                layer.alert('修改成功', {icon: 1}, function (index) {
                                    layer.close(index);
                                    noPageContentList(url, data, id, arr);
                                });
                            }
                        });
                    },
                    cancel: function () {
                    }
                });
            }
        });
    }).on('click', 'span[data-type="no"]', function () {  // 点击后在游戏服务器列表中显示服务器
        removeBackgroud($(this))
        var server_id = $(this).parents('tr').find('td').eq(2).text();
        var name = $(this).parents('tr').find('td').eq(3).text();
        layer.alert('确认显示[' + name + ']服务器？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9133",
                data: {
                    server_id: server_id
                },
                dataType: 'json',
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
                    if (json.status == 1) {
                        layer.alert('已在游戏服务器列表中显示', {icon: 1}, function (index) {
                            layer.close(index);
                            noPageContentList(url, data, id, arr);
                        });
                    } else {
                        layer.alert(json.msg, {icon: 2}, function (index) {
                            layer.close(index);
                        })
                    }
                }
            });
        });
    }).on('click', 'span[data-type="yes"]', function () {  // 点击后在游戏服务器列表中隐藏服务器
        removeBackgroud($(this))
        var server_id = $(this).parents('tr').find('td').eq(2).text();
        var name = $(this).parents('tr').find('td').eq(3).text();
        layer.alert('确认隐藏[' + name + ']服务器？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9134",
                data: {
                    server_id: server_id
                },
                dataType: 'json',
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
                    if (json.status == 1) {
                        layer.alert('已在游戏服务器列表中隐藏', {icon: 1}, function (index) {
                            layer.close(index);
                            noPageContentList(url, data, id, arr);
                        });
                    } else {
                        layer.alert(json.msg, {icon: 2}, function (index) {
                            layer.close(index);
                        })
                    }
                }
            });
        });
    }).on('click', 'span[data-type="off"]', function () {  // 点击后改为线上数据库
        removeBackgroud($(this))
        var server_id = $(this).parents('tr').find('td').eq(2).text();
        var name = $(this).parents('tr').find('td').eq(3).text();
        layer.alert('确认把[' + name + ']服务器改为线上数据库？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9137",
                data: {
                    server_id: server_id
                },
                dataType: 'json',
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
                    if (json.status == 1) {
                        layer.alert('已改为线上数据库', {icon: 1}, function (index) {
                            layer.close(index);
                            noPageContentList(url, data, id, arr);
                        });
                    } else {
                        layer.alert(json.msg, {icon: 2}, function (index) {
                            layer.close(index);
                        })
                    }
                }
            });
        });
    }).on('click', 'span[data-type="on"]', function () {  // 点击后本地数据库
        removeBackgroud($(this))
        var server_id = $(this).parents('tr').find('td').eq(2).text();
        var name = $(this).parents('tr').find('td').eq(3).text();
        layer.alert('确认把[' + name + ']服务器改为本地数据库？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9138",
                data: {
                    server_id: server_id
                },
                dataType: 'json',
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
                    if (json.status == 1) {
                        layer.alert('已改为本地数据库', {icon: 1}, function (index) {
                            layer.close(index);
                            noPageContentList(url, data, id, arr);
                        });
                    } else {
                        layer.alert(json.msg, {icon: 2}, function (index) {
                            layer.close(index);
                        })
                    }
                }
            });
        });
    }).on('click', 'span[data-type="off_notice"]', function () {  // 点击后本地数据库
        removeBackgroud($(this));
        var server_id = $(this).parents('tr').find('td').eq(2).text();
        var name = $(this).parents('tr').find('td').eq(3).text();
        layer.alert('确认把[' + name + ']服务器显示公告？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9142",
                data: {
                    server_id: server_id
                },
                dataType: 'json',
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
                    if (json.status == 1) {
                        layer.alert('已显示公告', {icon: 1}, function (index) {
                            layer.close(index);
                            noPageContentList(url, data, id, arr);
                        });
                    } else {
                        layer.alert(json.msg, {icon: 2}, function (index) {
                            layer.close(index);
                        })
                    }
                }
            });
        });
    }).on('click', 'span[data-type="on_notice"]', function () {  // 点击后本地数据库
        removeBackgroud($(this));
        var server_id = $(this).parents('tr').find('td').eq(2).text();
        var name = $(this).parents('tr').find('td').eq(3).text();
        layer.alert('确认把[' + name + ']服务器不显示公告？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9143",
                data: {
                    server_id: server_id
                },
                dataType: 'json',
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
                    if (json.status == 1) {
                        layer.alert('已不显示公告', {icon: 1}, function (index) {
                            layer.close(index);
                            noPageContentList(url, data, id, arr);
                        });
                    } else {
                        layer.alert(json.msg, {icon: 2}, function (index) {
                            layer.close(index);
                        })
                    }
                }
            });
        });
    }).on('click', 'a[data-type="maintenance"]', function () {  // 点击维护
        removeBackgroud($(this))
        var server_id = $(this).parents('tr').find('td').eq(2).text();
        var name = $(this).parents('tr').find('td').eq(3).text();
        var s_info1 = $(this).parents('tr').find('td').eq(0).find('input').attr("data-data-info1");
        var s_info2 = $(this).parents('tr').find('td').eq(0).find('input').attr("data-data-info2");
        var s_info3 = $(this).parents('tr').find('td').eq(0).find('input').attr("data-data-info3");
        var s_info4 = $(this).parents('tr').find('td').eq(0).find('input').attr("data-data-info4");
        var s_info5 = $(this).parents('tr').find('td').eq(0).find('input').attr("data-data-info5");
        var s_info6 = $(this).parents('tr').find('td').eq(0).find('input').attr("data-data-info6");
        var s_info7 = $(this).parents('tr').find('td').eq(0).find('input').attr("data-data-info7");
        var s_info8 = $(this).parents('tr').find('td').eq(0).find('input').attr("data-data-info8");
        var s_info9 = $(this).parents('tr').find('td').eq(0).find('input').attr("data-data-info9");
        var s_info10 = $(this).parents('tr').find('td').eq(0).find('input').attr("data-data-info10");
        var s_info11 = $(this).parents('tr').find('td').eq(0).find('input').attr("data-data-info11");
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=9121",
            data: {
                server_id: server_id
            },
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (json) {
                layer.closeAll('loading');
                $('#info').html(json['info']);
                info = json['info'];
            }
        });
        maintenance(server_id, name,[s_info1,s_info2,s_info3,s_info4,s_info5,s_info6,s_info7,s_info8,s_info9,s_info10,s_info11]);
    }).on('click', 'a[data-type="cancel"]', function () {  // 取消维护
        removeBackgroud($(this))
        var server_id = $(this).parents('tr').find('td').eq(2).text();
        var name = $(this).parents('tr').find('td').eq(3).text();
        cancel(server_id, name);
    }).on('click', 'a[data-type="delete"]', function () {  // 删除服务器
        removeBackgroud($(this))
        var server_id = $(this).parents('tr').find('td').eq(2).text();
        var name = $(this).parents('tr').find('td').eq(3).text();
        var group_name = $(this).parents('tr').find('td').eq(4).text();
        layer.alert('确认删除[' + name + ']服务器？', {icon: 0, btn: ['确定', '取消'], shadeClose: true}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=914",
                data: {
                    group_name: group_name,
                    server_name: name,
                    server_id: server_id
                },
                dataType: "json",
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
                    console.log(json);
                    if (json.status == 2) {
                        layer.alert(json.msg);
                    } else {
                        layer.alert('删除成功', {icon: 1}, function (index) {
                            layer.close(index);
                            noPageContentList(url, data, id, arr);
                        });
                    }
                }
            });
        });
    }).on('click', 'a[data-type="advance"]', function () {
        removeBackgroud($(this))
        var server_id = $(this).parents('tr').find('td').eq(2).text();
        location.href += '&si=' + server_id;
    }).on('click', 'a[data-type="copy"]', function () {
        removeBackgroud($(this))
        var server_id = $(this).parents('tr').find('td').eq(2).text();
        location.href +=  '&type=copy' + '&si=' + server_id;
    }).on('click', 'tr', function() {
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
        $.cookie('cookie_gs', s_id, {expires: 7});
    })

    // 禁止点击tr默认选中
    function removeBackgroud(obj) {
        var tr = $(obj).parents('tr');
        var cb = tr.find('td:first>input');
        if (! cb.is(':checked')) {
            cb.attr('checked', true);
            tr.attr('style', 'background: #aba5618c');
        } else {
            cb.attr('checked', false);
            tr.removeAttr('style', 'background: #aba5618c');
        }
    }

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
                noPageContentList(url, data, id, arr);
            }
        }
    });

    $('button[data-type="preserve"]').on('click', function () {//保存排序
        var id_list = '';
        $('#content').find('tr').each(function () {
            id_list += $(this).children().eq(2).text() + ',';
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
                        noPageContentList(url, data, id, arr);
                    });
                }
            });
        });
    })
    $('button[data-type="all_check"]').on('click', function () {
        var arr = getChoose();
        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            all_check(arr.server_id);
        }
    });

    function all_check(server_id) {
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=9146",
            data: {
                server_id: server_id
            },
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            dataType: "json",
            success: function (json) {
                layer.closeAll('loading');
                console.log(json);
                var c='<table class="table table-bordered table-striped text-center jin-server-table"><thead><th>服务器名称</th><th>Account</th><th>Game</th><th>Log</th><th>跨服日志</th><th>跨服Game</th></thead><tbody>';
                for (var i in json){
                    c+='<tr><td>'+i+'</td><td>'+json[i]['account']+'</td><td>'+json[i]['game']+'</td><td>'+json[i]['log']+'</td><td>'+json[i]['cross']+'</td><td>'+json[i]['cross_game']+'</td></tr>';
                }
                c+='</tbody></table>';
                layer.open({
                    type: 1,
                    closeBtn: 2,
                    title: '检测',
                    area: ['1000px', '800px'],
                    btnAlign: 'c',
                    shadeClose: true, //点击遮罩关闭
                    content: '<div class="jin-child">' +c+
                    '</div>',
                    yes: function (index) {

                    }
                });
            }
        });
    }

    // 批量修改新服标记
    $('button[data-type="all_excel_dau"]').click(function(name) {
        var arr = getChoose();
        if (arr.server_id == '') {
            layer.alert('请选择服务器！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            all_excel_dau(arr.server_id,arr.name);
        }
    });

    function all_excel_dau(server_id,name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: 'dau数据导出',
            area: ['400px', '240px'],
            btn: ['确认修改', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">天数</span><input id="days" type="text" class="form-control"></div>' +
            '</div>',
            yes: function (index1) {
                if ($('#days').val() == '') {
                    layer.alert('请输入天数!', {icon: 2}, function (index) {
                        layer.close(index);
                        return false;
                    });
                }
                $.ajax({
                    type: "post",
                    url: location.href + "&jinIf=9147",
                    data: {
                        days:$('#days').val(),
                        server_id: server_id,
                        name: name
                    },
                    dataType: "json",
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']
                        });
                    },
                    success: function (output) {
                        layer.closeAll('loading');
                        layer.close(index1);
                        location.href = output;
                    },
                    error: function () {
                        layer.closeAll('loading');
                        layer.msg('文件下载失败，请缩小筛选条件后再次下载');
                    }
                });
            },
            cancel: function () {
            }
        });
    }

    $('span[dir=ltr]').css("width", '220px');
<?php echo '</script'; ?>
>
<?php }
}
