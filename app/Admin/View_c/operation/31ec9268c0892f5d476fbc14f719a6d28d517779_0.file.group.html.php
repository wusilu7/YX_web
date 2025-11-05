<?php
/* Smarty version 3.1.30, created on 2024-08-19 14:50:32
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\operation\group.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66c2eb38276b04_61603914',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '31ec9268c0892f5d476fbc14f719a6d28d517779' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\operation\\group.html',
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
function content_66c2eb38276b04_61603914 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.08.group.css" rel="stylesheet">
<style>
    .jin-child > div {
        margin-bottom: 8px;
        width: 100%;
    }
</style>
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>渠道配置</span></div>
<button data-type="insert" class="btn btn-success">新增渠道</button>
<input size="16" type="checkbox" id="ischeck" value="1">
<label for="ischeck">显示服务器</label>
<button data-type="all_change" class="btn btn-primary">批量修改</button>
<div class="table-responsive">
    <table class="table table-striped table-bordered text-center jin-group-table">
        <thead>
        <tr>
            <th>渠道ID</th>
            <th>渠道名称</th>
            <th>强更资源</th>
            <th>日志</th>
            <th>资源地址</th>
            <th class="jin-group-column6">下载地址</th>
            <th class="jin-group-column7">渠道内服务器</th>
            <th>显示</th>
            <th>推荐服阈值</th>
            <th class="jin-group-column9">操作</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>

<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    var url = location.href + '&jinIf=912';
    var checkbox = function (json) {
        return '<input type="checkbox"  value="' + json.group_id + '" />'+json.group_id;
    };
    var down = [
        '<div class="jin-group-down">' +
        '<img data-type="ios" src="<?php echo IMG;?>
down_8.png"/>' +
        '<img data-type="android" src="<?php echo IMG;?>
down_11.png"/>' +
        '</div>'
    ];
    var btn = [
        '<div class="btn-group btn-group-sm">' +
        '<button data-type="update" class=" btn btn-primary">修改</button>' +
        '<button data-type="delete" class="btn btn-danger">删除</button>' +
        '</div>'
    ];
    var res = function (json) {
        return '<table class="jin-group-res"><tr><td>对外：</td><td class="jin-group-out">' + json.res + '</td></tr><tr><td>测试：</td><td>' + json.res_white + '</td></tr><tr><td>白名：</td><td>' + json.white + '</td></tr><tr><td>备用：</td><td>' + json.res_standby + '</td></tr></table>';
    };
    var arr = [checkbox, 'group_name', 'tab', 'level', res, down, 'server1', 'is_show','allow_num', btn];
    var id = ["#content", "#page"];
    var data = {page: 1};
    $(document).ready(tableList(url, data, id, arr));

    $('#ischeck').on('click', function () {
        if($('#ischeck').is(':checked')){
            arr[6] = 'server';
        }else{
            arr[6] = 'server1';
        }
        tableList(url, data, id, arr)
    });
    $('button[data-type="insert"]').on('click', function () {//新增渠道
        var docW = window.screen.width;
        if(docW < 768){
            layerW = '80%';
            layerH = '80%';
        }else{
            layerW = '560px';
            layerH = '360px;';
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '新增渠道',
            area: [layerW, layerH],
            btn: ['新增', '取消'],
            btnAlign: 'c',
            shadeClose: false, //点击遮罩
            content: '<div class="jin-child" style="margin-top: -3px;">' +
            '<div class="input-group"><span class="input-group-addon">渠道ID</span><input id="group_id" type="text" class="form-control" placeholder="请填写纯数字，且不要与已存在的ID重复"></div>' +
            '<div class="input-group"><span class="input-group-addon">渠道名称</span><input id="group_name" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">强更资源</span><input id="tab" type="text" class="form-control" value="False"></div>' +
            '<div class="input-group"><span class="input-group-addon">日志等级</span><input id="level" type="text" class="form-control" value="0"></div>' +
            '<div class="input-group"><span class="input-group-addon">资源地址</span><input id="res" type="text" class="form-control"></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=911',
                    data: {
                        group_id: $('#group_id').val(),
                        group_name: $('#group_name').val(),
                        tab: $('#tab').val(),
                        level: $('#level').val(),
                        res: $('#res').val()
                    },
                    success: function (res) {
                        layer.close(index);
                        if (res === '-1') {
                            layer.alert('与已存在的渠道ID重复', {icon: 2}, function (index) {
                                layer.close(index);
                            });
                        } else {
                            layer.alert('添加成功', {icon: 1}, function (index) {
                                layer.close(index);
                                contentList(url, data, id, arr);
                            });
                        }
                    }
                });
            },
            cancel: function () {
            }
        })
    });

    $('#content').on('click', 'button[data-type="update"]', function () {//修改渠道
        var group_id = $(this).parents('tr').find('td').eq(0).text();
        var group_name = $(this).parents('tr').find('td').eq(1).text();
        var tab = $(this).parents('tr').find('td').eq(2).text();
        var level = $(this).parents('tr').find('td').eq(3).text();
        var res = $(this).parents('tr').find('td').eq(4).find('tr').eq(0).find('td').eq(1).text();
        var res_white = $(this).parents('tr').find('td').eq(4).find('tr').eq(1).find('td').eq(1).text();
        var white = $(this).parents('tr').find('td').eq(4).find('tr').eq(2).find('td').eq(1).text();
        var res_standby = $(this).parents('tr').find('td').eq(4).find('tr').eq(3).find('td').eq(1).text();
        var allow_num = $(this).parents('td').prev().text();
        var ios = '';
        var android = '';
        var code_ios = '';
        var code_android = '';
        var code_ios_test = '';
        var code_android_test = '';
        var code_ios_other = '';
        var code_android_other = '';
        var code_ios2 = '';
        var code_android2 = '';
        var code_ios_test2 = '';
        var code_android_test2 = '';
        var code_ios_other2 = '';
        var code_android_other2 = '';
        var c1 = '';
        var c2 = '';
        var c11 = '';
        var c22 = '';
        var c111 = '';
        var c222 = '';
        var summarize_time = '';
        $.ajax({
            type: "POST",
            url: location.href + '&jinIf=946',
            data: {
            },
            async: false,
            success: function (res) {
                res = eval(res);
                for (var i=0;i<res.length;i++){
                    c2+='<input type="checkbox" style="width: 15px;height: 15px;" id="funcmask_android_'+i+'" name="funcmask_android" value="'+res[i].value+'"   />'+
                        '<label for="funcmask_android_'+i+'" style="margin-right: 5px; vertical-align:middle;">'+res[i].name+'</label>';
                    c1+='<input type="checkbox" style="width: 15px;height: 15px; "id="funcmask_ios_'+i+'" name="funcmask_ios" value="'+res[i].value+'"   />'+
                        '<label for="funcmask_ios_'+i+'" style="margin-right: 5px; vertical-align:middle;">'+res[i].name+'</label>';
                    c22+='<input type="checkbox" style="width: 15px;height: 15px;" id="funcmask_android_test_'+i+'" name="funcmask_android_test" value="'+res[i].value+'"   />'+
                        '<label for="funcmask_android_test_'+i+'" style="margin-right: 5px; vertical-align:middle;">'+res[i].name+'</label>';
                    c11+='<input type="checkbox" style="width: 15px;height: 15px; "id="funcmask_ios_test_'+i+'" name="funcmask_ios_test" value="'+res[i].value+'"   />'+
                        '<label for="funcmask_ios_test_'+i+'" style="margin-right: 5px; vertical-align:middle;">'+res[i].name+'</label>';
                    c222+='<input type="checkbox" style="width: 15px;height: 15px;" id="funcmask_android_other_'+i+'" name="funcmask_android_other" value="'+res[i].value+'"   />'+
                        '<label for="funcmask_android_other_'+i+'" style="margin-right: 5px; vertical-align:middle;">'+res[i].name+'</label>';
                    c111+='<input type="checkbox" style="width: 15px;height: 15px; "id="funcmask_ios_other_'+i+'" name="funcmask_ios_other" value="'+res[i].value+'"   />'+
                        '<label for="funcmask_ios_other_'+i+'" style="margin-right: 5px; vertical-align:middle;">'+res[i].name+'</label>';
                }
            }
        });

        $.ajax({
            type: "POST",
            url: location.href + '&jinIf=9121',//苹果
            data: {
                group_id: group_id
            },
            async: false,
            success: function (res) {
                ios = JSON.parse(res)['down_ios'];
                res_more = JSON.parse(res)['res_more'];
                android = JSON.parse(res)['down_android'];
                android_more = JSON.parse(res)['down_android_more'];
                down_ios_new = JSON.parse(res)['down_ios_new'];
                down_android_new = JSON.parse(res)['down_android_new'];
                down_android_new_more = JSON.parse(res)['down_android_new_more'];
                android_white = JSON.parse(res)['down_android_white'];
                code_ios = JSON.parse(res)['code_ios'];
                code_ios2 = JSON.parse(res)['code_ios2'];
                code_android = JSON.parse(res)['code_android'];
                code_android2 = JSON.parse(res)['code_android2'];
                code_ios_test = JSON.parse(res)['code_ios_test'];
                code_ios_test2 = JSON.parse(res)['code_ios_test2'];
                code_android_test = JSON.parse(res)['code_android_test'];
                code_android_test2 = JSON.parse(res)['code_android_test2'];
                code_ios_other = JSON.parse(res)['code_ios_other'];
                code_ios_other2 = JSON.parse(res)['code_ios_other2'];
                code_android_other = JSON.parse(res)['code_android_other'];
                code_android_other2 = JSON.parse(res)['code_android_other2'];
                gameid = JSON.parse(res)['gameid'];
                thread = JSON.parse(res)['thread'];
                app_version = JSON.parse(res)['app_version'];
                res_version = JSON.parse(res)['res_version'];
                summarize_time = JSON.parse(res)['summarize_time'];
                login_time = JSON.parse(res)['login_time'];
                login_time_new = JSON.parse(res)['login_time_new'];
                login_time_ios = JSON.parse(res)['login_time_ios'];
                login_time_new_ios = JSON.parse(res)['login_time_new_ios'];
                loginparam = JSON.parse(res)['loginparam'];
                android_md5 = JSON.parse(res)['android_md5'];
                android_version = JSON.parse(res)['android_version'];
                ios_version = JSON.parse(res)['ios_version'];
                android_imprint = JSON.parse(res)['android_imprint'];
                ios_imprint = JSON.parse(res)['ios_imprint'];
                android_version_new = JSON.parse(res)['android_version_new'];
                ios_version_new = JSON.parse(res)['ios_version_new'];
                android_imprint_new = JSON.parse(res)['android_imprint_new'];
                ios_imprint_new = JSON.parse(res)['ios_imprint_new'];
                notice = JSON.parse(res)['notice'];
                package_id = JSON.parse(res)['package_id'];
                login_app_version = JSON.parse(res)['login_app_version'];
                login_res_version = JSON.parse(res)['login_res_version'];
                login_app_version1 = JSON.parse(res)['login_app_version1'];
                login_res_version1 = JSON.parse(res)['login_res_version1'];
                login_v_info = JSON.parse(res)['login_v_info'];
                level_white = JSON.parse(res)['level_white'];
                shield = JSON.parse(res)['shield'];
                inherit_group = JSON.parse(res)['inherit_group'];
                check_update =  JSON.parse(res)['check_update'];
                check_update1 =  JSON.parse(res)['check_update1'];
                pay_gift =  JSON.parse(res)['pay_gift'];
                precise_gift =  JSON.parse(res)['precise_gift'];
                white_acc =  JSON.parse(res)['white_acc'];
                if(shield==1){
                    shield1="checked";
                }else{
                    shield1="";
                }
            }
        });
        var docW = window.screen.width;
        if(docW < 768){
            layerW = '80%';
            layerH = '80%';
        }else{
            layerW = '700px';
            layerH = '880px;';
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: group_name+'修改',
            area: [layerW, layerH],
            btn: ['修改', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<ul class="nav nav-tabs"><li class="active"><a href="#gift_send" data-toggle="tab">基础设置</a></li> <li><a href="#gift_query" data-toggle="tab">高级设置</a></li><li><a href="#gift_other" data-toggle="tab">其他设置</a></li></ul>' +
            '<div class="tab-content"><div id="gift_send" class="jin-child tab-pane active">' +
            '<div class="input-group"><span class="input-group-addon">渠道名称</span><input id="group_name" value="' +
            group_name +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">登录限制app版本号</span><input id="login_app_version" value="' +
            login_app_version +
            '" type="text" class="form-control" placeholder="只填一个,高于此版本才能登录"></div>' +
            '<div class="input-group"><span class="input-group-addon">登录限制res版本号</span><input id="login_res_version" value="' +
            login_res_version +
            '" type="text" class="form-control" placeholder="只填一个,高于此版本才能登录"></div>' +
            '<div class="input-group"><span class="input-group-addon">登录限制app版本号(白名单)</span><input id="login_app_version1" value="' +
            login_app_version1 +
            '" type="text" class="form-control" placeholder="只填一个,高于此版本才能登录"></div>' +
            '<div class="input-group"><span class="input-group-addon">登录限制res版本号(白名单)</span><input id="login_res_version1" value="' +
            login_res_version1 +
            '" type="text" class="form-control" placeholder="只填一个,高于此版本才能登录"></div>' +
            '<div class="input-group"><span class="input-group-addon">登录限制信息提示</span><input id="login_v_info" value="' +
            login_v_info +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">强更资源</span><input id="tab" value="' +
            tab +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">日志等级</span><input id="level" value="' +
            level +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">日志等级(白名单)</span><input id="level_white" value="' +
            level_white +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">对外资源</span><input id="res" value="' +
            res +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">替换资源</span><textarea  id="res_more" cols="30" rows="2" class="form-control" placeholder="日期1==资源地址1\n日期2==资源地址2">'+res_more+'</textarea></div>' +
            '<div class="input-group"><span class="input-group-addon">备用资源</span><input id="res_standby" value="' +
            res_standby +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">测试资源</span><input id="res_white" value="' +
            res_white +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">白名单(IP)</span><input id="white" value="' +
            white +
            '" type="text" class="form-control" placeholder="多个白名单用英文分号(;)隔开"></div>' +
            '<div class="input-group"><span class="input-group-addon">白名单(账号)</span><input id="white_acc" value="' +
            white_acc +
            '" type="text" class="form-control" placeholder="多个白名单用英文分号(;)隔开"></div>' +
            '<div class="input-group"><span class="input-group-addon">提审app版本号</span><input id="app_version" value="' +
            app_version +
            '" type="text" class="form-control" placeholder="多个版本号用英文分号(;)隔开"></div>' +
            '<div class="input-group"><span class="input-group-addon">提审res版本号</span><input id="res_version" value="' +
            res_version +
            '" type="text" class="form-control" placeholder="多个版本号用英文分号(;)隔开"></div>' +
            '<div class="input-group"><span class="input-group-addon">提审屏蔽信息</span><input type="checkbox" value=1 id="ischeck" '+shield1+'  style="width:30px;height:30px;"></div>' +
            '<div class="input-group"><span class="input-group-addon">安卓地址(白)</span><input id="android_white" value="' +
            android_white +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">ios 验码</span>'+c1+'<input id="code_ios" value="' +
            code_ios +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">ios 验码<br>(测试)</span>'+c11+'<input id="code_ios_test" value="' +
            code_ios_test +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">ios 验码<br>(提审)</span>'+c111+'<input id="code_ios_other" value="' +
            code_ios_other +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">android 验码</span>'+c2+'<input id="code_android" value="' +
            code_android +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">android 验码<br>(测试)</span>'+c22+'<input id="code_android_test" value="' +
            code_android_test +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">android 验码<br>(提审)</span>'+c222+'<input id="code_android_other" value="' +
            code_android_other +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">gameid</span><input id="gameid" value="' +
            gameid +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">推荐服阈值</span><input id="allow_num" value="' +
            allow_num +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">汇总开始时间</span><input id="summarize_time" value="' +
            summarize_time +
            '" type="text" class="form-control" placeholder="格式：2018-01-01"></div>' +
            '<div class="input-group"><span class="input-group-addon">登录参数</span><input id="loginparam" value="' +
            loginparam +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">尾包线程数</span><input id="thread" value="' +
            thread +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">安卓MD5</span><input id="android_md5" value="' +
            android_md5 +
            '" type="text" class="form-control"></div>' +
            '</div>' +
            '<div id="gift_query" class="jin-child tab-pane">' +
            '<div style="border:2px solid red;"><div style="text-align:center;font-size:15px;">现版本</div><div class="input-group"><span class="input-group-addon">版本检测开始时间</span><input id="login_time" value="' +
            login_time +
            '" type="text" class="form-control jin-datetime"></div>' +
            '<div class="input-group"><span class="input-group-addon">安卓版本</span><input id="android_version" value="' +
            android_version +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">安卓地址</span><input id="android" value="' +
            android +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">分包地址</span><textarea  id="android_more" cols="30" rows="4" class="form-control" placeholder="包名1==地址1\n包名2==地址2">'+android_more+'</textarea></div>' +
            '<div class="input-group"><span class="input-group-addon">安卓版本说明</span><textarea  id="android_imprint" cols="30" rows="4" class="form-control">'+android_imprint+'</textarea></div>' +
            '<div class="input-group"><span class="input-group-addon">版本检测开始时间(IOS)</span><input id="login_time_ios" value="' +
            login_time_ios +
            '" type="text" class="form-control jin-datetime"></div>' +
            '<div class="input-group"><span class="input-group-addon">iOS版本</span><input id="ios_version" value="' +
            ios_version +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">IOS地址</span><input id="ios" value="' +
            ios +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">IOS版本说明</span><textarea  id="ios_imprint" cols="30" rows="4" class="form-control">'+ios_imprint+'</textarea></div></div>' +
            '<div class="input-group"><span class="input-group-addon">商店非强更检测</span><input id="check_update" value="' +
            check_update +
            '" type="text" class="form-control" placeholder="多个版本号用英文分号(;)隔开"></div>' +
            '<div class="input-group"><span class="input-group-addon">商店强更跳过提示</span><input id="check_update1" value="' +
            check_update1 +
            '" type="text" class="form-control" placeholder="多个版本号用英文分号(;)隔开"></div>' +
            '<div style="border:2px solid blue"><div style="text-align:center;font-size:15px;">新版本(ip白名单不限制)</div><div class="input-group"><span class="input-group-addon">版本检测开始时间</span><input id="login_time_new" value="' +
            login_time_new +
            '" type="text" class="form-control jin-datetime"></div>' +
            '<div class="input-group"><span class="input-group-addon">安卓版本</span><input id="android_version_new" value="' +
            android_version_new +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">安卓地址</span><input id="down_android_new" value="' +
            down_android_new +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">分包地址</span><textarea  id="down_android_new_more" cols="30" rows="4" class="form-control">'+down_android_new_more+'</textarea></div>' +
            '<div class="input-group"><span class="input-group-addon">安卓版本说明</span><textarea  id="android_imprint_new" cols="30" rows="4" class="form-control">'+android_imprint_new+'</textarea></div>' +
            '<div class="input-group"><span class="input-group-addon">版本检测开始时间(ios)</span><input id="login_time_new_ios" value="' +
            login_time_new_ios +
            '" type="text" class="form-control jin-datetime"></div>' +
            '<div class="input-group"><span class="input-group-addon">iOS版本</span><input id="ios_version_new" value="' +
            ios_version_new +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">IOS地址</span><input id="down_ios_new" value="' +
            down_ios_new +
            '" type="text" class="form-control"></div>' +
            '<div class="input-group"><span class="input-group-addon">IOS版本说明</span><textarea  id="ios_imprint_new" cols="30" rows="4" class="form-control">'+ios_imprint_new+'</textarea></div></div>' +
            '<div class="input-group hide"><span class="input-group-addon">公告</span><textarea  id="notice" cols="30" rows="10" class="form-control">'+notice+'</textarea></div>' +
            '<div class="input-group"><span class="input-group-addon">版权信息</span><textarea  id="package_id" cols="30" rows="10" class="form-control">'+package_id+'</textarea></div>' +
            '</div>' +
            '<div id="gift_other" class="jin-child tab-pane">' +
            '<div class="input-group"><span class="input-group-addon">继承渠道</span><input id="inherit_group" value="' +
            inherit_group + '" type="text" class="form-control" placeholder="从该渠道继承某些数据"></div>' +
            '<div class="input-group"><span class="input-group-addon">付费礼包</span><input id="pay_gift" value="' +
            pay_gift + '" type="text" class="form-control" placeholder="渠道编号-标识 填写示例:0-付费礼包"></div>' +
            '<div class="input-group"><span class="input-group-addon">精准礼包</span><input id="precise_gift" value="' +
            precise_gift + '" type="text" class="form-control" placeholder="渠道编号-标识 填写示例:0-精准礼包"></div></div></div>',
            success: function(){
                //勾选
                for (var k = 0; k < code_ios2.length; k++) {
                    if(code_ios2[k]==1){
                        $('#funcmask_ios_' + k).prop("checked", true);
                    }
                }
                for (var k = 0; k < code_ios_test2.length; k++) {
                    if(code_ios_test2[k]==1){
                        $('#funcmask_ios_test_' + k).prop("checked", true);
                    }
                }
                for (var k = 0; k < code_ios_other2.length; k++) {
                    if(code_ios_other2[k]==1){
                        $('#funcmask_ios_other_' + k).prop("checked", true);
                    }
                }
                //勾选
                for (var k = 0; k < code_android2.length; k++) {
                    if(code_android2[k]==1){
                        $('#funcmask_android_' + k).prop("checked", true);
                    }
                }
                //勾选
                for (var k = 0; k < code_android_test2.length; k++) {
                    if(code_android_test2[k]==1){
                        $('#funcmask_android_test_' + k).prop("checked", true);
                    }
                }
                //勾选
                for (var k = 0; k < code_android_other2.length; k++) {
                    if(code_android_other2[k]==1){
                        $('#funcmask_android_other_' + k).prop("checked", true);
                    }
                }
                //勾选联动输入框
                $(":checkbox").click(function () {
                    var c3 = eval(checkedValue("funcmask_ios").join("+"));
                    if(c3==undefined){
                        c3=0;
                    }
                    $('#code_ios').val(c3);

                    var c33 = eval(checkedValue("funcmask_ios_test").join("+"));
                    if(c33==undefined){
                        c33=0;
                    }
                    $('#code_ios_test').val(c33);

                    var c333 = eval(checkedValue("funcmask_ios_other").join("+"));
                    if(c333==undefined){
                        c333=0;
                    }
                    $('#code_ios_other').val(c333);

                    var c4 = eval(checkedValue("funcmask_android").join("+"));
                    if(c4==undefined){
                        c4=0;
                    }
                    $('#code_android').val(c4);

                    var c44 = eval(checkedValue("funcmask_android_test").join("+"));
                    if(c44==undefined){
                        c44=0;
                    }
                    $('#code_android_test').val(c44);

                    var c444 = eval(checkedValue("funcmask_android_other").join("+"));
                    if(c444==undefined){
                        c444=0;
                    }
                    $('#code_android_other').val(c444);

                });
                //输入框联动勾选
                $('#code_ios').on('input',function () {
                    $("[name='funcmask_ios']").prop("checked", false);
                    var  arr=[];
                    var  str=parseInt($(this).val()).toString(2);
                    for (var k = 1; k <= str.length; k++){
                        arr.push(str.substr(-k,1))
                    }
                    for (var k = 0; k < arr.length; k++) {
                        if(arr[k]==1){
                            $('#funcmask_ios_' + k).prop("checked", true);
                        }
                    }
                });
                $('#code_ios_test').on('input',function () {
                    $("[name='funcmask_ios_test']").prop("checked", false);
                    var  arr=[];
                    var  str=parseInt($(this).val()).toString(2);
                    for (var k = 1; k <= str.length; k++){
                        arr.push(str.substr(-k,1))
                    }
                    for (var k = 0; k < arr.length; k++) {
                        if(arr[k]==1){
                            $('#funcmask_ios_test_' + k).prop("checked", true);
                        }
                    }
                });
                $('#code_ios_other').on('input',function () {
                    $("[name='funcmask_ios_other']").prop("checked", false);
                    var  arr=[];
                    var  str=parseInt($(this).val()).toString(2);
                    for (var k = 1; k <= str.length; k++){
                        arr.push(str.substr(-k,1))
                    }
                    for (var k = 0; k < arr.length; k++) {
                        if(arr[k]==1){
                            $('#funcmask_ios_other_' + k).prop("checked", true);
                        }
                    }
                });
                //输入框联动勾选
                $('#code_android').on('input',function () {
                    $("[name='funcmask_android']").prop("checked", false);
                    var  arr=[];
                    var  str=parseInt($(this).val()).toString(2);
                    for (var k = 1; k <= str.length; k++){
                        arr.push(str.substr(-k,1))
                    }
                    for (var k = 0; k < arr.length; k++) {
                        if(arr[k]==1){
                            $('#funcmask_android_' + k).prop("checked", true);
                        }
                    }
                });
                //输入框联动勾选
                $('#code_android_test').on('input',function () {
                    $("[name='funcmask_android_test']").prop("checked", false);
                    var  arr=[];
                    var  str=parseInt($(this).val()).toString(2);
                    for (var k = 1; k <= str.length; k++){
                        arr.push(str.substr(-k,1))
                    }
                    for (var k = 0; k < arr.length; k++) {
                        if(arr[k]==1){
                            $('#funcmask_android_test_' + k).prop("checked", true);
                        }
                    }
                });

                //输入框联动勾选
                $('#code_android_other').on('input',function () {
                    $("[name='funcmask_android_other']").prop("checked", false);
                    var  arr=[];
                    var  str=parseInt($(this).val()).toString(2);
                    for (var k = 1; k <= str.length; k++){
                        arr.push(str.substr(-k,1))
                    }
                    for (var k = 0; k < arr.length; k++) {
                        if(arr[k]==1){
                            $('#funcmask_android_other_' + k).prop("checked", true);
                        }
                    }
                });
                $(document).ready(calendarOne('hour', '#login_time'));
                $(document).ready(calendarOne('hour', '#login_time_new'));
                $(document).ready(calendarOne('hour', '#login_time_ios'));
                $(document).ready(calendarOne('hour', '#login_time_new_ios'));
            },
            yes: function (index) {
                var shield2 = $('#ischeck').is(':checked') ? $('#ischeck').val() : 0;
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=913',
                    data: {
                        group_id: group_id,
                        group_name: $('#group_name').val(),
                        tab: $('#tab').val(),
                        level: $('#level').val(),
                        res: $('#res').val(),
                        res_more: $('#res_more').val(),
                        res_standby: $('#res_standby').val(),
                        res_white: $('#res_white').val(),
                        white: $('#white').val(),
                        white_acc: $('#white_acc').val(),
                        ios: $('#ios').val(),
                        android: $('#android').val(),
                        android_more: $('#android_more').val(),
                        down_ios_new: $('#down_ios_new').val(),
                        down_android_new: $('#down_android_new').val(),
                        down_android_new_more: $('#down_android_new_more').val(),
                        android_white: $('#android_white').val(),
                        code_ios: $('#code_ios').val(),
                        code_android: $('#code_android').val(),
                        code_ios_test: $('#code_ios_test').val(),
                        code_android_test: $('#code_android_test').val(),
                        code_ios_other: $('#code_ios_other').val(),
                        code_android_other: $('#code_android_other').val(),
                        gameid: $('#gameid').val(),
                        allow_num: $('#allow_num').val(),
                        summarize_time: $('#summarize_time').val(),
                        login_time: $('#login_time').val(),
                        login_time_new: $('#login_time_new').val(),
                        login_time_ios: $('#login_time_ios').val(),
                        login_time_new_ios: $('#login_time_new_ios').val(),
                        loginparam: $('#loginparam').val(),
                        android_md5: $('#android_md5').val(),
                        android_version: $('#android_version').val(),
                        ios_version: $('#ios_version').val(),
                        android_imprint: $('#android_imprint').val(),
                        ios_imprint: $('#ios_imprint').val(),
                        android_version_new: $('#android_version_new').val(),
                        ios_version_new: $('#ios_version_new').val(),
                        android_imprint_new: $('#android_imprint_new').val(),
                        ios_imprint_new: $('#ios_imprint_new').val(),
                        notice: $('#notice').val(),
                        thread: $('#thread').val(),
                        app_version: $('#app_version').val(),
                        res_version: $('#res_version').val(),
                        login_app_version: $('#login_app_version').val(),
                        login_res_version: $('#login_res_version').val(),
                        login_app_version1: $('#login_app_version1').val(),
                        login_res_version1: $('#login_res_version1').val(),
                        login_v_info: $('#login_v_info').val(),
                        level_white: $('#level_white').val(),
                        package_id: $('#package_id').val(),
                        inherit_group: $('#inherit_group').val(),
                        check_update:$('#check_update').val(),
                        check_update1:$('#check_update1').val(),
                        pay_gift:$('#pay_gift').val(),
                        precise_gift:$('#precise_gift').val(),
                        shield:shield2
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
                            contentList(url, data, id, arr);
                        });
                    }
                });
            },
            cancel: function () {
            }

        });
    }).on('click', 'span[data-type="no"]', function () {//点击后在游戏服务器列表中显示服务器
        var gi = $(this).parents('tr').find('td').eq(0).text();
        var name = $(this).parents('tr').find('td').eq(1).text();
        layer.alert('确认在后台显示[' + name + ']渠道？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9133",
                data: {
                    gi: gi
                },
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function () {
                    layer.closeAll('loading');
                    layer.alert('已在后台渠道列表中显示', {icon: 1}, function (index) {
                        layer.close(index);
                        contentList(url, data, id, arr);
                    });
                }
            });
        });
    }).on('click', 'span[data-type="yes"]', function () {//点击后在后台组列表中隐藏
        var gi = $(this).parents('tr').find('td').eq(0).text();
        var name = $(this).parents('tr').find('td').eq(1).text();
        layer.alert('确认在后台隐藏[' + name + ']渠道？', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=9134",
                data: {
                    gi: gi
                },
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function () {
                    layer.closeAll('loading');
                    layer.alert('已在后台渠道列表中隐藏', {icon: 1}, function (index) {
                        layer.close(index);
                        contentList(url, data, id, arr);
                    });
                }
            });
        });
    }).on('click', 'button[data-type="delete"]', function () {//删除组
        var group_id = $(this).parents('tr').find('td').eq(0).text();
        var group_name = $(this).parents('tr').find('td').eq(1).text();
        layer.alert('确认删除渠道 <b>' + group_name + '</b>？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
            layer.alert('<b>渠道删除后将不可恢复！</b>', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=914',
                    data: {
                        group_id: group_id,
                        group_name: group_name
                    },
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function () {
                        layer.closeAll('loading');
                        layer.alert('删除成功', {icon: 1}, function (index) {
                            layer.close(index);
                            contentList(url, data, id, arr);
                        });
                    }
                });
            });
        });
    }).on('mouseover', 'img[data-type="ios"]', function () {
        var group_id = $(this).parents('tr').find('td').eq(0).text();
        var url = '';
        $.ajax({//苹果
            type: "POST",
            url: location.href + '&jinIf=9121',
            data: {
                group_id: group_id
            },
            async: false,
            success: function (res) {
                url = JSON.parse(res)['ios'];
            }
        });
        layer.tips("<a href=" + url + ">" + url + "</a>", this, {
            tips: [4, '#333'],
            area: 'auto',
            maxWidth: 800
        });

    }).on('mouseover', 'img[data-type="android"]', function () {
        var group_id = $(this).parents('tr').find('td').eq(0).text();
        var url = '';
        $.ajax({//安卓
            type: "POST",
            url: location.href + '&jinIf=9121',
            data: {
                group_id: group_id
            },
            async: false,
            success: function (res) {
                url = JSON.parse(res)['android'];
                url_white = JSON.parse(res)['android_white'];
            }
        });
        layer.tips("外：<a href=" + url + ">" + url + "</a><br/>测：<a href=" + url + ">" + url_white + "</a>", this, {
            tips: [2, '#96b118'],
            area: 'auto',
            maxWidth: 800
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
    // 批量修改
    $('button[data-type="all_change"]').click(function() {
        var arr = getChoose();
        if (arr.group_id == '') {
            layer.alert('请选择目标！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            change(arr.group_id);
        }
    });

    // 打开批量修改界面
    function change(group_id, name) {
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '批量修改',
            area: ['500px', '300px'],
            btn: ['修改', '取消'],
            btnAlign: 'c',
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">白名单</span><input id="white_ip" type="text" class="form-control" placeholder="多个用英文分号(;)隔开"></div>' +
            '<div class="input-group"><span class="input-group-addon">提审app版本号</span><input id="app_version" type="text" class="form-control" placeholder="多个用英文分号(;)隔开"></div>' +
            '<div class="input-group"><span class="input-group-addon">提审res版本号</span><input id="res_version" type="text" class="form-control" placeholder="多个用英文分号(;)隔开"></div>' +
            '</div>',
            yes: function (yes) {
                layer.alert('确认修改？', {icon: 0, btn: ['确定', '取消']}, function () {
                    $.ajax({
                        type: "POST",
                        url: location.href + '&jinIf=915',
                        data: {
                            group_id: group_id,
                            white_ip: $('#white_ip').val(),
                            app_version: $('#app_version').val(),
                            res_version: $('#res_version').val(),
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            layer.load(2, {
                                shade: [0.3, '#fff']//0.3透明度的白色背景
                            });
                        },
                        success: function () {
                            layer.closeAll('loading');
                            layer.close(yes);
                            layer.alert('修改成功', {icon: 1}, function (success) {
                                layer.close(success);
                                window.history.go(0);
                            });
                        }
                    });
                });
            },
            cancel: function () {
            }
        })
    }

    // 获取选中的服务器
    function getChoose() {
        var group_id = '';
        $('#content input[type="checkbox"]:checked').each(function(index, el) {
            if (index == 0) {
                group_id = $(el).val();
            } else {
                group_id += ',' + $(el).val();
            }
        });
        return {
            'group_id': group_id
        };
    }

<?php echo '</script'; ?>
><?php }
}
