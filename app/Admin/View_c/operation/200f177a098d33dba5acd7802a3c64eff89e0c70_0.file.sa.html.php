<?php
/* Smarty version 3.1.30, created on 2024-08-16 15:51:26
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\operation\sa.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66bf04feb73ba8_26084848',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '200f177a098d33dba5acd7802a3c64eff89e0c70' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\operation\\sa.html',
      1 => 1723704876,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_66bf04feb73ba8_26084848 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<link href="<?php echo CSS;?>
jin/3.07.sa.css" rel="stylesheet">
<div class="jin-content-title"><span>服务器高级配置</span></div>
<hr />
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ① 非开发人员只能修改 <b>app版本号、资源版本号、网络状态、新服标记、维护说明、游戏功能掩码开关、排序、备注</b> 等内容；
    </div>
</div>
<hr />
<div class="form-horizontal col-sm-8 col-sm-offset-2">
    <div class="form-group">
        <label for="server_id" class="col-sm-2 control-label">服务器ID</label>
        <div class="col-sm-10">
            <input id="server_id" class="form-control" readonly/>
        </div>
    </div>
    <div class="form-group">
        <label for="name" class="col-sm-2 control-label">服务器名称</label>
        <div class="col-sm-10">
            <input id="name" class="form-control" readonly/>
        </div>
    </div>
    <div class="form-group">
        <label for="game_dn" class="col-sm-2 control-label">IP地址</label>
        <div class="col-sm-10">
            <input id="game_dn" class="form-control" readonly/>
        </div>
    </div>
    <div class="form-group">
        <label for="game_port" class="col-sm-2 control-label">端口</label>
        <div class="col-sm-10">
            <input id="game_port" class="form-control" readonly/>
        </div>
    </div>
    <div class="form-group">
        <label for="white_ip" class="col-sm-2 control-label">ip白名单</label>
        <div class="col-sm-10">
            <input id="white_ip" class="form-control" readonly/>
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
    <div class="form-group">
        <label for="before_add" class="col-sm-2 control-label">合服前log 地址</label>
        <div class="col-sm-10">
            <input id="before_add" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="before_port" class="col-sm-2 control-label">合服前log 端口</label>
        <div class="col-sm-10">
            <input id="before_port" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="before_user" class="col-sm-2 control-label">合服前log 用户</label>
        <div class="col-sm-10">
            <input id="before_user" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="before_pw" class="col-sm-2 control-label">合服前log 密码</label>
        <div class="col-sm-10">
            <input id="before_pw" type="password" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="before_prefix" class="col-sm-2 control-label">合服前log 前缀</label>
        <div class="col-sm-10">
            <input id="before_prefix" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="funcmask" class="col-sm-2 control-label" >游戏功能掩码开关</label>
        <div class="col-sm-10 " style="font-size: 20px; vertical-align:middle;">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['sa']->value, 'm', false, 'k');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['k']->value => $_smarty_tpl->tpl_vars['m']->value) {
?>
            <input type="checkbox" id="funcmask_<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" name="funcmask" value="<?php echo $_smarty_tpl->tpl_vars['m']->value['value'];?>
"  style="zoom:150%;"  />
            <label for="funcmask_<?php echo $_smarty_tpl->tpl_vars['k']->value;?>
" style=" vertical-align:middle;"><?php echo $_smarty_tpl->tpl_vars['m']->value['name'];?>
</label>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

            <input id="funcmask" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="sort" class="col-sm-2 control-label">排序</label>
        <div class="col-sm-10">
            <input id="sort" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="candidate" class="col-sm-2 control-label">选人参数</label>
        <div class="col-sm-10">
            <input id="candidate" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="gameparam" class="col-sm-2 control-label">游戏参数</label>
        <div class="col-sm-10">
            <input id="gameparam" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="payparam" class="col-sm-2 control-label">支付参数</label>
        <div class="col-sm-10">
            <input id="payparam" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="form-group">
        <label for="remain" class="col-sm-2 control-label">备注</label>
        <div class="col-sm-10">
            <input id="remain" class="form-control" placeholder=""/>
        </div>
    </div>
    <div class="btn-group center jin-sa-btn">
        <button data-type="update" class="btn  btn-success">修改</button>
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
            success: function (json) {
                $('#server_id').val(json.server_id);
                $('#name').val(json.name);
                $('#game_dn').val(json.game_dn);
                $('#game_port').val(json.game_port);
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
                $('#before_add').val(json.before_add);
                $('#before_port').val(json.before_port);
                $('#before_user').val(json.before_user);
                $('#before_pw').val(json.before_pw);
                $('#before_prefix').val(json.before_prefix);
                $('#funcmask').val(json.funcmask);
                //勾选
                for (var k = 0; k < json.funcmask2.length; k++) {
                    if(json.funcmask2[k]==1){
                        $('#funcmask_' + k).prop("checked", true);
                    }
                }
                //勾选联动输入框
                $(":checkbox").click(function () {
                    var c2 = eval(checkedValue("funcmask").join("+"));
                    if(c2==undefined){
                        c2=0;
                    }
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
                $('#sort').val(json.sort);
                $('#candidate').val(json.candidate);
                $('#gameparam').val(json.gameparam);
                $('#payparam').val(json.payparam);
                $('#remain').val(json.remain);
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

    $('button[data-type="update"]').on('click', function () {//修改服务器
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=913",
            data: {
                state: $('#state').val(),
                app_version: $('#app_version').val(),
                res_version: $('#res_version').val(),
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
                before_add: $('#before_add').val(),
                before_port: $('#before_port').val(),
                before_user: $('#before_user').val(),
                before_pw: $('#before_pw').val(),
                before_prefix: $('#before_prefix').val(),
                funcmask: $('#funcmask').val(),
                sort: $('#sort').val(),
                candidate: $('#candidate').val(),
                gameparam: $('#gameparam').val(),
                payparam: $('#payparam').val(),
                remain: $('#remain').val(),
                server_id: $('#server_id').val()
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
