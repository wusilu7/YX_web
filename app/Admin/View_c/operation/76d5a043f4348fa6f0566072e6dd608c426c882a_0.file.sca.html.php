<?php
/* Smarty version 3.1.30, created on 2023-03-21 20:58:24
  from "/lnmp/www/app/Admin/View/operation/sca.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6419a9f0df4e25_96104947',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '76d5a043f4348fa6f0566072e6dd608c426c882a' => 
    array (
      0 => '/lnmp/www/app/Admin/View/operation/sca.html',
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
function content_6419a9f0df4e25_96104947 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<link href="<?php echo CSS;?>
jin/3.07.sa.css" rel="stylesheet">
<div class="jin-content-title"><span>新增服务器高级配置</span></div>
<div class="form-horizontal col-sm-8 col-sm-offset-2">
    <div class="form-group">
        <label for="group_id" class="col-sm-2 control-label">渠道ID</label>
        <div class="col-sm-3">
            <select id="group_id" class="form-control">
                <option></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">服务器名称</label>
        <div class="col-sm-10">
            <input id="name" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="game_dn" class="col-sm-2 control-label">IP地址</label>
        <div class="col-sm-10">
            <input id="game_dn" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="game_port" class="col-sm-2 control-label">端口</label>
        <div class="col-sm-10">
            <input id="game_port" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="game_dn2" class="col-sm-2 control-label">IP地址2</label>
        <div class="col-sm-10">
            <input id="game_dn2" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="game_port2" class="col-sm-2 control-label">端口2</label>
        <div class="col-sm-10">
            <input id="game_port2" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="game_dn3" class="col-sm-2 control-label">IP地址3</label>
        <div class="col-sm-10">
            <input id="game_dn3" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="game_port3" class="col-sm-2 control-label">端口3</label>
        <div class="col-sm-10">
            <input id="game_port3" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="game_dn4" class="col-sm-2 control-label">IP地址4</label>
        <div class="col-sm-10">
            <input id="game_dn4" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="game_port4" class="col-sm-2 control-label">端口4</label>
        <div class="col-sm-10">
            <input id="game_port4" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="white_ip" class="col-sm-2 control-label">ip白名单</label>
        <div class="col-sm-10">
            <input id="white_ip" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="app_version" class="col-sm-2 control-label">app版本号</label>
        <div class="col-sm-10">
            <input id="app_version" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label for="res_version" class="col-sm-2 control-label">资源版本号</label>
        <div class="col-sm-10">
            <input id="res_version" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label for="state" class="col-sm-2 control-label">网络状态</label>
        <div class="col-sm-3">
            <select id="state">
                <option></option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="tab" class="col-sm-2 control-label">新服标记</label>
        <div class="col-sm-10">
            <input id="tab" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="info" class="col-sm-2 control-label">维护说明</label>
        <div class="col-sm-10">
            <input id="info" class="form-control" placeholder=""/>
        </div>
    </div>
    <!--account数据库-->
    <div class="form-group">
        <label for="a_add" class="col-sm-2 control-label">Account 地址</label>
        <div class="col-sm-10">
            <input id="a_add" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="a_port" class="col-sm-2 control-label">Account 端口</label>
        <div class="col-sm-10">
            <input id="a_port" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="a_user" class="col-sm-2 control-label">Account 用户</label>
        <div class="col-sm-10">
            <input id="a_user" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="a_pw" class="col-sm-2 control-label">Account 密码</label>
        <div class="col-sm-10">
            <input id="a_pw" type="password" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="a_prefix" class="col-sm-2 control-label">Account 前缀</label>
        <div class="col-sm-10">
            <input id="a_prefix" class="form-control" placeholder=""/>
        </div>
    </div>
    <!--game数据库-->
    <div class="form-group">
        <label for="g_add" class="col-sm-2 control-label">Game 地址</label>
        <div class="col-sm-10">
            <input id="g_add" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="g_port" class="col-sm-2 control-label">Game 端口</label>
        <div class="col-sm-10">
            <input id="g_port" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="g_user" class="col-sm-2 control-label">Game 用户</label>
        <div class="col-sm-10">
            <input id="g_user" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="g_pw" class="col-sm-2 control-label">Game 密码</label>
        <div class="col-sm-10">
            <input id="g_pw" type="password" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="g_prefix" class="col-sm-2 control-label">Game 前缀</label>
        <div class="col-sm-10">
            <input id="g_prefix" class="form-control" placeholder=""/>
        </div>
    </div>
    <!--log数据库-->
    <div class="form-group">
        <label for="l_add" class="col-sm-2 control-label">Log 地址</label>
        <div class="col-sm-10">
            <input id="l_add" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="l_port" class="col-sm-2 control-label">Log 端口</label>
        <div class="col-sm-10">
            <input id="l_port" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="l_user" class="col-sm-2 control-label">Log 用户</label>
        <div class="col-sm-10">
            <input id="l_user" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="l_pw" class="col-sm-2 control-label">Log 密码</label>
        <div class="col-sm-10">
            <input id="l_pw" type="password" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="l_prefix" class="col-sm-2 control-label">Log 前缀</label>
        <div class="col-sm-10">
            <input id="l_prefix" class="form-control" placeholder=""/>
        </div>
    </div>

    <!--log数据库-->
    <div class="form-group">
        <label for="c_add" class="col-sm-2 control-label">跨服Log 地址</label>
        <div class="col-sm-10">
            <input id="c_add" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="c_port" class="col-sm-2 control-label">跨服Log 端口</label>
        <div class="col-sm-10">
            <input id="c_port" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="c_user" class="col-sm-2 control-label">跨服Log 用户</label>
        <div class="col-sm-10">
            <input id="c_user" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="c_pw" class="col-sm-2 control-label">跨服Log 密码</label>
        <div class="col-sm-10">
            <input id="c_pw" type="password" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="c_prefix" class="col-sm-2 control-label">跨服Log 前缀</label>
        <div class="col-sm-10">
            <input id="c_prefix" class="form-control" placeholder=""/>
        </div>
    </div>

    <div class="form-group">
        <label for="soap_add" class="col-sm-2 control-label">SOAP 地址</label>
        <div class="col-sm-10">
            <input id="soap_add" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="soap_port" class="col-sm-2 control-label">SOAP 端口</label>
        <div class="col-sm-10">
            <input id="soap_port" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="funcmask" class="col-sm-2 control-label">游戏功能掩码开关</label>
        <div class="col-sm-10">
            <input id="funcmask" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="sort" class="col-sm-2 control-label">排序</label>
        <div class="col-sm-10">
            <input id="sort" class="form-control" value="0" />
        </div>
    </div>
    <div class="form-group">
        <label for="world_id" class="col-sm-2 control-label">world_id</label>
        <div class="col-sm-10">
            <input id="world_id" class="form-control" value="0" />
        </div>
    </div>
    <div class="form-group">
        <label for="world_id_son" class="col-sm-2 control-label">world_id_son</label>
        <div class="col-sm-10">
            <input id="world_id_son" class="form-control" value="0" />
        </div>
    </div>
    <div class="form-group">
        <label for="platfrom_id" class="col-sm-2 control-label">platfrom_id</label>
        <div class="col-sm-10">
            <input id="platfrom_id" class="form-control" value="0" />
        </div>
    </div>
    <div class="form-group">
        <label for="world_time" class="col-sm-2 control-label">world_time</label>
        <div class="col-sm-10">
            <input id="world_time" class="form-control" value="0" />
        </div>
    </div>
    <div class="form-group">
        <label for="file_path" class="col-sm-2 control-label">file_path</label>
        <div class="col-sm-10">
            <input id="file_path" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label for="server_group_id" class="col-sm-2 control-label">server_group_id</label>
        <div class="col-sm-10">
            <input id="server_group_id" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label for="remain" class="col-sm-2 control-label">备注</label>
        <div class="col-sm-10">
            <input id="remain" class="form-control" placeholder=""/>
        </div>
    </div>
    <input type="hidden" id="open_other_ip" value="">
    <div class="btn-group center jin-sa-btn">
        <button data-type="add_server" class="btn  btn-success">新增</button>
        <button data-type="return" class="btn  btn-primary">返回</button>
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
 type="text/javascript">
    $(document).ready(getSa());
    function getSa() {
        $.ajax({
            type: "post",
            url: location.href + "&jinIf=912",
            dataType: "json",
            success: function (json) {console.log(json);
                $('#name').val(json.name);
                $('#game_dn').val(json.game_dn);
                $('#game_port').val(json.game_port);
                $('#game_dn2').val(json.game_dn2);
                $('#game_port2').val(json.game_port2);
                $('#game_dn3').val(json.game_dn3);
                $('#game_port3').val(json.game_port3);
                $('#game_dn4').val(json.game_dn4);
                $('#game_port4').val(json.game_port4);
                $('#white_ip').val(json.white_ip);
                $('#app_version').val(json.app_version);
                $('#res_version').val(json.res_version);
                $('#tab').val(json.tab);
                $('#info').val(json.info);
                $('#a_add').val(json.a_add);
                $('#a_port').val(json.a_port);
                $('#a_user').val(json.a_user);
                $('#a_pw').val(json.a_pw);
                $('#a_prefix').val(json.a_prefix);
                $('#g_add').val(json.g_add);
                $('#g_port').val(json.g_port);
                $('#g_user').val(json.g_user);
                $('#g_pw').val(json.g_pw);
                $('#g_prefix').val(json.g_prefix);
                $('#l_add').val(json.l_add);
                $('#l_port').val(json.l_port);
                $('#l_user').val(json.l_user);
                $('#l_pw').val(json.l_pw);
                $('#l_prefix').val(json.l_prefix);
                $('#c_add').val(json.c_add);
                $('#c_port').val(json.c_port);
                $('#c_user').val(json.c_user);
                $('#c_pw').val(json.c_pw);
                $('#c_prefix').val(json.c_prefix);
                $('#soap_add').val(json.soap_add);
                $('#soap_port').val(json.soap_port);
                $('#funcmask').val(json.funcmask);
                $('#remain').val(json.remain);
                $('#world_id').val(json.world_id);
                $('#world_id_son').val(json.world_id_son);
                $('#platfrom_id').val(json.platfrom_id);
                $('#world_time').val(json.world_time);
                $('#open_other_ip').val(json.open_other_ip);
                $('#file_path').val(json.file_path);
                $('#server_group_id').val(json.server_group_id);
                //下拉框
                $.ajax({
                    type: "POST",
                    url: "?p=Admin&c=Operation&a=group&jinIf=943",
                    dataType: 'json',
                    success: function (res) {
                        var c = '';
                        var li = '';
                        for (var i = 0; i < res.length; i++) {
                            c +='<optgroup style="color: red;" label="'+ res[i][0] +'">'
                            li += '<li class="dropdown-header " data-optgroup="'+ i +'"><span class="text">'+ res[i][0] +'</span></li>';
                            var x
                            for (var j = 1; j < res[i].length; j++) {
                                if (i == 0) {
                                    x = -1;
                                    for (var j = 1; j < res[i].length; j++) {
                                        var aa = j  - 1;
                                        c += '<option style="color: #0C0C0C" value="'+ res[i][j].group_id +'">'+ res[i][j].group_name +'</option>';
                                        li += '<li data-original-index="'+ aa +'">' +
                                            '<a tabindex="0" data-tokens="null">' +
                                            '<span class="text text_content">'+ res[i][j].group_name +'</span><span class="glyphicon glyphicon-ok check-mark"></span>'+
                                            '</a>'+
                                            '</li>';
                                    }
                                } else {
                                    x += res[i-1].length;
                                    for (var j = 1; j < res[i].length; j++) {
                                        var aa = j + x -i;
                                        c += '<option style="color: #0C0C0C" value="'+ res[i][j].group_id +'">'+ res[i][j].group_name +'</option>';
                                        li += '<li data-original-index="'+ aa +'">' +
                                            '<a tabindex="0" data-tokens="null">' +
                                            '<span class="text text_content">'+ res[i][j].group_name +'</span><span class="glyphicon glyphicon-ok check-mark"></span>'+
                                            '</a>'+
                                            '</li>';
                                    }
                                }
                            }
                            li += '<li class="divider" data-optgroup="2div"></li>';
                            c += '</optgroup>';
                        }
                        $("#group_id").html(c);
                    }
                });
                //下拉框
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=941",
                    dataType: 'json',
                    success: function (res) {
                        var arr = [];
                        for (var i = 0; i < res.length; i++) {
                            arr[i] = {
                                id: i,
                                text: res[i]
                            }
                        }
                        $("#state").select2({
                            data: arr,
                            placeholder: '请选择',
                            theme: "classic",
                            width: "150px"
                        }).val(json.state).trigger('change');
                    }
                });
            }
        });
    }

    $('button[data-type="add_server"]').on('click', function () {//增加服务器
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=914",
            data: {
                group_id: $('#group_id').val(),
                name: $('#name').val(),
                game_dn: $('#game_dn').val(),
                game_port: $('#game_port').val(),
                game_dn2: $('#game_dn2').val(),
                game_port2: $('#game_port2').val(),
                game_dn3: $('#game_dn3').val(),
                game_port3: $('#game_port3').val(),
                game_dn4: $('#game_dn4').val(),
                game_port4: $('#game_port4').val(),
                white_ip: $('#white_ip').val(),
                app_version: $('#app_version').val(),
                res_version: $('#res_version').val(),
                state: $('#state').val(),
                tab: $('#tab').val(),
                info: $('#info').val(),
                a_add: $('#a_add').val(),
                a_port: $('#a_port').val(),
                a_user: $('#a_user').val(),
                a_pw: $('#a_pw').val(),
                a_prefix: $('#a_prefix').val(),
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
                c_add: $('#c_add').val(),
                c_port: $('#c_port').val(),
                c_user: $('#c_user').val(),
                c_pw: $('#c_pw').val(),
                c_prefix: $('#c_prefix').val(),
                soap_add: $('#soap_add').val(),
                soap_port: $('#soap_port').val(),
                funcmask: $('#funcmask').val(),
                sort: $('#sort').val(),
                remain: $('#remain').val(),
                world_id:$('#world_id').val(),
                world_id_son:$('#world_id_son').val(),
                platfrom_id:$('#platfrom_id').val(),
                world_time:$('#world_time').val(),
                open_other_ip:$('#open_other_ip').val(),
                file_path:$('#file_path').val(),
                server_group_id:$('#server_group_id').val()
            },
            dataType: "json",
            success: function (json) {
                if (json.status == 1) {
                    layer.alert(json.msg, {icon: 1}, function (index) {
                        layer.close(index);
                        location.href = '?p=Admin&c=Operation&a=server';
                    });
                } else {
                    layer.alert(json.msg, {icon: 2}, function (index) {
                        layer.close(index);
                    });
                }
            }
        });
    });

    $('button[data-type="return"]').on('click', function () {
        location.href = '?p=Admin&c=Operation&a=server';
    });
<?php echo '</script'; ?>
>
<?php }
}
