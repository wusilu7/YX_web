<?php
/* Smarty version 3.1.30, created on 2024-04-24 18:13:27
  from "D:\pro\WebSiteYiXing\app\Admin\View\mb\fullMailSend.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6628db47ede204_62797697',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '5ab354567838aaedf9bd5cb3a3c9d0c2e528f5ef' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\mb\\fullMailSend.html',
      1 => 1704262933,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_6628db47ede204_62797697 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.19.mailSend.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->

<style type="text/css">
    #editor{
        width: 1550px;
    }
    .w-e-text-container,
    .w-e-toolbar{
        width: 1400px;
        margin-left: 300px;
    }
    .alert {
        padding-bottom: 46px;
    }
    .alert-info{
        color: white;
    }
</style>

<div class="jin-content-title"><span>全服邮件发送</span></div>
<div class="alert alert-info">
    <div class="form-group" id="group_server_6"></div>
</div>
<div class="form-horizontal">
    <div class="form-group hide">
        <label for="template" class="col-sm-2 control-label">选择模板</label>
        <div class="col-sm-10">
            <select id="template">
                <option value=""></option>
            </select>
            <div class="btn-group">
                <button id='save_temp' class="btn btn-success">保存为模板</button>
                <button id='delete_temp' class="btn btn-danger">删除模版</button>
            </div>
        </div>
    </div>
    <div class="form-group hide">
        <label for="full_id" class="col-sm-2 control-label ">全服邮件ID</label>
        <div class="col-sm-10">
            <input id="full_id" maxlength="2" class="form-control"
                   placeholder="此栏填1-99的纯数字ID，因全服邮件最多可接收200封，相同ID的全服邮件会以新代旧替换，不填的话ID默认100-199循环"/>
        </div>
    </div>
    <div class="form-group">
        <label for="more_servers" class="col-sm-2 control-label ">发送服务器*</label>
        <div class="col-sm-10">
            <select id="s" class="selectpicker show-tick col-sm-3 col-xs-12" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>
        </div>
    </div>
    <?php if ($_smarty_tpl->tpl_vars['isMultilingual']->value == '[1]') {?>
    <div class="form-group">
        <ul class="nav nav-tabs col-sm-offset-2">
            <li  class="active"><a href="#nav_content1" data-toggle="tab">中文</a></li>
            <li><a href="#nav_content2" data-toggle="tab">繁体</a></li>
            <li><a href="#nav_content3" data-toggle="tab">英语</a></li>
            <li><a href="#nav_content4" data-toggle="tab">西班牙</a></li>
            <li><a href="#nav_content5" data-toggle="tab">阿拉伯语</a></li>
            <li><a href="#nav_content6" data-toggle="tab">俄语</a></li>
            <li><a href="#nav_content7" data-toggle="tab">泰文</a></li>
            <li><a href="#nav_content8" data-toggle="tab">巴西</a></li>
            <li><a href="#nav_content9" data-toggle="tab">印尼</a></li>
            <li><a href="#nav_content10" data-toggle="tab">日本</a></li>
            <li><a href="#nav_content11" data-toggle="tab">韩文</a></li>
        </ul>
    </div>
    <?php }?>
    <div class="tab-content">
        <div class="tab-pane active" id="nav_content1">
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label ">标题*</label>
                <div class="col-sm-10">
                    <input id="title1" maxlength="20" class="form-control" placeholder="必填，标题最多20个字"/>
                </div>
            </div>
            <div class="form-group">
                <label for="words1" class="col-sm-2 control-label ">内容*</label>
                <div class="col-sm-10">
                    <textarea id="words1" class="form-control" cols="30" rows="10" placeholder="内容最多300个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content2">
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label ">标题*</label>
                <div class="col-sm-10">
                    <input id="title2" maxlength="20" class="form-control" placeholder="必填，标题最多20个字"/>
                </div>
            </div>
            <div class="form-group">
                <label for="word2" class="col-sm-2 control-label ">内容*</label>
                <div class="col-sm-10">
                    <textarea id="words2" class="form-control" cols="30" rows="10" placeholder="内容最多300个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content3">
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label ">标题*</label>
                <div class="col-sm-10">
                    <input id="title3" maxlength="20" class="form-control" placeholder="必填，标题最多20个字"/>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-sm-2 control-label ">内容*</label>
                <div class="col-sm-10">
                    <textarea id="words3" class="form-control" cols="30" rows="10" placeholder="内容最多300个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content4">
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label ">标题*</label>
                <div class="col-sm-10">
                    <input id="title4" maxlength="20" class="form-control" placeholder="必填，标题最多20个字"/>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-sm-2 control-label ">内容*</label>
                <div class="col-sm-10">
                    <textarea id="words4" class="form-control" cols="30" rows="10" placeholder="内容最多300个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content5">
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label ">标题*</label>
                <div class="col-sm-10">
                    <input id="title5" maxlength="20" class="form-control" placeholder="必填，标题最多20个字"/>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-sm-2 control-label ">内容*</label>
                <div class="col-sm-10">
                    <textarea id="words5" class="form-control" cols="30" rows="10" placeholder="内容最多300个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content6">
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label ">标题*</label>
                <div class="col-sm-10">
                    <input id="title6" maxlength="20" class="form-control" placeholder="必填，标题最多20个字"/>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-sm-2 control-label ">内容*</label>
                <div class="col-sm-10">
                    <textarea id="words6" class="form-control" cols="30" rows="10" placeholder="内容最多300个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content7">
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label ">标题*</label>
                <div class="col-sm-10">
                    <input id="title7" maxlength="20" class="form-control" placeholder="必填，标题最多20个字"/>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-sm-2 control-label ">内容*</label>
                <div class="col-sm-10">
                    <textarea id="words7" class="form-control" cols="30" rows="10" placeholder="内容最多300个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content8">
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label ">标题*</label>
                <div class="col-sm-10">
                    <input id="title8" maxlength="20" class="form-control" placeholder="必填，标题最多20个字"/>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-sm-2 control-label ">内容*</label>
                <div class="col-sm-10">
                    <textarea id="words8" class="form-control" cols="30" rows="10" placeholder="内容最多300个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content9">
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label ">标题*</label>
                <div class="col-sm-10">
                    <input id="title9" maxlength="20" class="form-control" placeholder="必填，标题最多20个字"/>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-sm-2 control-label ">内容*</label>
                <div class="col-sm-10">
                    <textarea id="words9" class="form-control" cols="30" rows="10" placeholder="内容最多300个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content10">
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label ">标题*</label>
                <div class="col-sm-10">
                    <input id="title10" maxlength="20" class="form-control" placeholder="必填，标题最多20个字"/>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-sm-2 control-label ">内容*</label>
                <div class="col-sm-10">
                    <textarea id="words10" class="form-control" cols="30" rows="10" placeholder="内容最多300个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content11">
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label ">标题*</label>
                <div class="col-sm-10">
                    <input id="title11" maxlength="20" class="form-control" placeholder="必填，标题最多20个字"/>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-sm-2 control-label ">内容*</label>
                <div class="col-sm-10">
                    <textarea id="words11" class="form-control" cols="30" rows="10" placeholder="内容最多300个字符"></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="create_time" class="col-sm-2 control-label ">发送时间</label>
        <div class="col-sm-10">
            <input id="create_time" class="form-control" placeholder="不填代表审核通过后立即发送">
        </div>
    </div>
    <div class="form-group">
        <label for="valid_time" class="col-sm-2 control-label ">失效时间</label>
        <div class="col-sm-10">
            <input id="valid_time" class="form-control" placeholder="不填代表该邮件永远有效">
        </div>
    </div>
    <div class="form-group">
        <label for="cond_min_lv" class="col-sm-2 control-label ">最小等级</label>
        <div class="col-sm-10">
            <input id="cond_min_lv" maxlength="4" class="form-control" placeholder="请填写数字，不填代表没有最小等级限制"/>
        </div>
    </div>
    <div class="form-group">
        <label for="cond_max_lv" class="col-sm-2 control-label ">最大等级</label>
        <div class="col-sm-10">
            <input id="cond_max_lv" maxlength="4" class="form-control" placeholder="请填写数字，不填代表没有最大等级限制"/>
        </div>
    </div>
    <div class="form-group">
        <label for="cond_create_time" class="col-sm-2 control-label ">创角时间</label>
        <div class="col-sm-10">
            <input id="cond_create_time" maxlength="3" class="form-control" placeholder="该时间点之前创建的角色可以收到，不填代表没有限制"/>
        </div>
    </div>
    <div class="form-group">
        <label for="cond_world_id" class="col-sm-2 control-label ">服世界ID</label>
        <div class="col-sm-10">
            <input id="cond_world_id" maxlength="3" class="form-control" placeholder="不填，默认为-1"/>
        </div>
    </div>
    <!--money-->
    <div class="form-group">
        <label class="col-sm-2 control-label jin-mail-gray">货币</label>
        <div class="col-sm-10">
            <hr/>
        </div>
    </div>
    <!--2个货币槽位填充-->
    <div id="money"></div>
    <!--item-->
    <div class="form-group">
        <label class="col-sm-2 control-label jin-mail-gray">道具</label>
        <div class="col-sm-10">
            <hr/>
        </div>
    </div>
    <!--5个道具槽位填充-->
    <div id="item"></div>

    <div class="form-group">
        <label for="exp" class="col-sm-2 control-label ">经验</label>
        <div class="col-sm-10">
            <input id="exp" maxlength="20" class="form-control"/>
        </div>
    </div>
</div>
<!--发送-->
<div class="center">
    <button id="send" class="btn btn-primary">发送</button>
</div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ① 每封邮件最多可附带2种货币，5种道具，不填货币/道具栏即代表不带相应附件；
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    var data = {};
    gsSelect3('#g', '', '#s');
    //groupSelect();
    templateSelect();
//    $("#group").on('change', function () {
//        obj = {};
//        obj.dom = "#more_servers";
//        obj.data = {gi: $(this).val()};
//        obj.multiple = true;
//        obj.placeholder = '';
//        obj.url = isExist(obj.url, "?p=Admin&c=Operation&a=server&jinIf=944");
//        obj.id = isExist(obj.id, "server_id");
//        obj.text = isExist(obj.text, "name");
//        obj.wd = isExist(obj.wd, "world_id");
//        obj.wds = isExist(obj.wds, "world_id_son");
//        $("#more_servers").val('');
//        $('#servers>span>span>span>ul').html('');
//        i_jinSelect(obj);
//    });
//    //下拉框选项核心函数
//    function i_jinSelect(obj) {
//        var dom = isExist(obj.dom);
//        var width = isExist(obj.width, "300px");
//        var id = isExist(obj.id);
//        var text = isExist(obj.text);
//        var wd = isExist(obj.wd);
//        var wds = isExist(obj.wds);
//        var data = isExist(obj.data);
//        var val = isExist(obj.val, isExist($.cookie('s_' + dom), 0));//默认值
//        var multiple = isExist(obj.multiple, false);//默认单选
//        var placeholder = isExist(obj.placeholder, '请选择');//默认值
//        $.ajax({
//            type: "POST",
//            url: obj.url,
//            dataType: 'json',
//            data: data,
//            success: function (res) {
//                var arr = [];
//                var t;
//                for (var i = 0; i < res.length; i++) {
//                    if (res[i][wd] == res[i][wds]) {
//                        var ps = '(主服)';
//                        t = '';
//                    } else {
//                        if (res[i][wd] != -1 && res[i][wds] != 0) {
//                            var ps = '(子服)';
//                            t = '-------';
//                        } else {
//                            var ps = '';
//                            t = '';
//                        }
//                    }
//
//                    arr[i] = {//关联数组版配置
//                        id: res[i][id],
//                        text: t+res[i][text]+ps
//                    }
//                }
//                $(dom).select2({
//                    data: arr,
//                    placeholder: placeholder,
//                    theme: "classic",
//                    width: width,
//                    multiple: multiple
//                }).val(val).trigger('change');
//            }
//        });
//    }
    //2个货币槽位生成
    var c_money = function () {
        var c = '';
        for (var i = 1; i <= 6; i++) {
            c +=
                '<div class="form-group">' +
                '<label class="col-sm-2 control-label">货币类型-' + i + '</label>' +
                '<div class="col-sm-3">' +
                '<select id="money_type' + i + '">' +
                '<option value=""></option>' +
                '</select>' +
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label class="col-sm-2 control-label ">货币数量-' + i + '</label>' +
                '<div class="col-sm-10">' +
                '<input id="money_num' + i + '" class="form-control" placeholder="数量"/>' +
                '</div>' +
                '</div>';
        }
        return c;
    };
    $("#money").html(c_money);
    //给货币select框赋值
    var obj1 = {}, obj2 = {}, obj3 = {}, obj4 = {}, obj5 = {}, obj6 = {};
    obj1.dom = "#money_type1";
    obj2.dom = "#money_type2";
    obj3.dom = "#money_type3";
    obj4.dom = "#money_type4";
    obj5.dom = "#money_type5";
    obj6.dom = "#money_type6";
    moneySelect(obj1);
    moneySelect(obj2);
    moneySelect(obj3);
    moneySelect(obj4);
    moneySelect(obj5);
    moneySelect(obj6);
    //5个道具槽位生成
    var c_item = function () {
        var c = '';
        for (var i = 1; i <= 5; i++) {
            c +=
                '<div class="form-group">' +
                '<label class="col-sm-2 control-label ">道具id-' + i + '</label>' +
                '<div class="col-sm-10">' +
                '<select id="item_type' + i + '"  class="selectpicker"  data-live-search="true" data-actions-box="true" title="请选择道具-' + i + '-"></select>'+
                '</div>' +
                '</div>' +
                '<div class="form-group">' +
                '<label class="col-sm-2 control-label ">道具数量-' + i + '</label>' +
                '<div class="col-sm-10">' +
                '<input id="item_num' + i + '" class="form-control" placeholder="-' + i + '-" maxlength=3/>' +
                '</div>' +
                '</div>' +
                '<div class="form-group hide">' +
                '<label class="col-sm-2 control-label ">道具过期时间-' + i + '</label>' +
                '<div class="col-sm-10">' +
                '<input id="item_time' + i + '" class="form-control" placeholder="不填代表该邮件道具永不过期">' +
                '</div>' +
                '</div>';
        }
        return c;
    };
    $("#item").html(c_item);
    $(function () {
        getItems(["#item_type1","#item_type2","#item_type3","#item_type4","#item_type5"],'');
    });
    calendarOne('hour', '#item_time1');
    calendarOne('hour', '#item_time2');
    calendarOne('hour', '#item_time3');
    calendarOne('hour', '#item_time4');
    calendarOne('hour', '#item_time5');
    //发送功能
    calendarOne('hour', '#create_time');
    calendarOne('hour', '#valid_time');
    calendarOne('hour', '#cond_create_time');

    function conver(s) {
        return s < 10 ? '0' + s : s;
    }

    $("#send").on('click', function () {
        //money字符串拼接
        var money = '';
        for (var i = 1; i <= 6; i++) {
            var id_type = "#money_type" + i;
            var id_num = "#money_num" + i;
            var money_type = $(id_type).val();
            var money_num = $(id_num).val();
            if (money_type && money_num) {
                money += money_type + '#' + money_num + ';';
            }
        }
        //item字符串拼接
        var item = '';
        for (i = 1; i <= 5; i++) {
            id_type = "#item_type" + i;
            id_num = "#item_num" + i;
            id_time = "#item_time" + i;
            var item_type = $(id_type).val();
            var item_num = $(id_num).val();
            var item_time = $(id_time).val();
            if (item_type && item_num) {
                if (item_time === '') {
                    item_time = -1;
                } else {
                    item_time = Date.parse(item_time) / 1000 + 28800;
                }
                item += item_type + '#' + item_num + '#' + item_time + ';';
            }
        }
        var title1 = $("#title1").val();
        var title2 = $("#title2").val();
        var title3 = $("#title3").val();
        var title4 = $("#title4").val();
        var title5 = $("#title5").val();
        var title6 = $("#title6").val();
        var title7 = $("#title7").val();
        var title8 = $("#title8").val();
        var title9 = $("#title9").val();
        var title10 = $("#title10").val();
        var title11 = $("#title11").val();
        var content1 = $("#words1").val();
        var content2 = $("#words2").val();
        var content3 = $("#words3").val();
        var content4 = $("#words4").val();
        var content5 = $("#words5").val();
        var content6 = $("#words6").val();
        var content7 = $("#words7").val();
        var content8 = $("#words8").val();
        var content9 = $("#words9").val();
        var content10 = $("#words10").val();
        var content11 = $("#words11").val();
        var si = JSON.stringify($("#s").val());
        var check_type = $('input[name=check_type]:checked').val();
        var filter_type = $('input[name=filter_type]:checked').val();
        var exp = $("#exp").val();
        var create_time_obj = $("#create_time").val();
        var valid_time_obj = $("#valid_time").val();
        var min_lv_obj = $("#cond_min_lv").val();
        var max_lv_obj = $("#cond_max_lv").val();
        var cond_create_obj = $("#cond_create_time").val();
        var cond_world_obj = $("#cond_world_id").val();
        //空值判断
        var myDate = new Date();
        var year = myDate.getFullYear();
        var month = myDate.getMonth()+1;
        var date = myDate.getDate(); 
        var h = myDate.getHours();       
        var m = myDate.getMinutes();     
        var s = myDate.getSeconds(); 
        if (create_time_obj === '') {//发送时间
            var create_time = Date.parse(new Date()) / 1000;
            //create_time = year + '-' + conver(month) + "-" + conver(date) + " " + conver(h) + ':' + conver(m) + ":" + conver(s);
        } else {
            create_time = Date.parse(create_time_obj) / 1000 + 28800;
            //create_time = create_time_obj
        }
        if (valid_time_obj === '') {//失效时间
            var valid_time =  Date.parse(new Date())/1000+604800; //不填失效时间  默认7天
        } else {
            valid_time = Date.parse(valid_time_obj) / 1000 + 28800;
        }
        if (min_lv_obj === '') {//最小等级
            var cond_min_lv = -1;
        } else {
            cond_min_lv = min_lv_obj;
        }
        if (max_lv_obj === '') {//最大等级
            var cond_max_lv = -1;
        } else {
            cond_max_lv = max_lv_obj;
        }
        if (cond_create_obj === '') {//创角时间
            var cond_create_time = -1;
        } else {
            cond_create_time = Date.parse(cond_create_obj) / 1000 + 28800;
        }

        if (cond_world_obj === '') {  //  服世界ID
            var cond_world_id = -1;
        } else {
            cond_world_id = cond_world_obj;
        }
        //全服邮件附加信息
        var full_id = $("#full_id").val();
        var full_info =
            'create_time=' + create_time +
            '`valid_time=' + valid_time +
            '`cond_min_lv=' + cond_min_lv +
            '`cond_max_lv=' + cond_max_lv +
            '`cond_create_time=' + cond_create_time + '`cond_world_id=' + cond_world_id;
        var data = {
            group: $("#g").val()[0],
            si: si,
            check_type: check_type,
            filter_type: filter_type,
            full_id: full_id,
            full_info: full_info,
            start_time: year + '-' + conver(month) + "-" + conver(date) + " " + conver(h) + ':' + conver(m) + ":" + conver(s),
            title1: title1,
            title2: title2,
            title3: title3,
            title4: title4,
            title5: title5,
            title6: title6,
            title7: title7,
            title8: title8,
            title9: title9,
            title10: title10,
            title11: title11,
            content1: content1,
            content2: content2,
            content3: content3,
            content4: content4,
            content5: content5,
            content6: content6,
            content7: content7,
            content8: content8,
            content9: content9,
            content10: content10,
            content11: content11,
            money: money,
            item: item,
            exp:exp
        };
        if (check_type === 912) {
            if (
                (si.length !== 0) &&
                title1 &&
                content1
            ) {
                // 发送邮件
                send_mail(data,cond_min_lv,cond_max_lv);
            } else {
                layer.alert("请填写发送服务器、邮件标题和内容");
            }
        } else {
            if (title1 && content1) {
                // 发送邮件
                send_mail(data,cond_min_lv,cond_max_lv);
            } else {
                layer.alert("请填写邮件标题和内容");
            }
        }
    });

    function send_mail(data,cond_min_lv,cond_max_lv) {
        var reg = new RegExp("^[0-9]*$");//数字正则验证
        if (reg.test(data.full_id) || data.full_id === '') {
            if ((reg.test(cond_min_lv) || cond_min_lv === -1) && (reg.test(cond_max_lv) || cond_max_lv === -1)) {console.log(data);
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=911",
                    data: data,
                    dataType: 'json',
                    success: function () {
                        layer.alert("发送成功，请等待审核");
                    },
                    error: function () {
                    }
                });
            }
            else {
                layer.alert("最小/最大等级栏请填写大于0的正整数");
            }
        } else {
            layer.alert("全服邮件ID栏请填写纯数字");
        }
    }

    //以下为模板功能
    $("#save_temp").on('click', function () {
        var temp_title = $("#template").find("option:selected").text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '保存为模板',
            area: ['400px', '170px'],
            btn: ['保存', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">模板名称</span><input id="temp_title1" type="text" class="form-control" value="' +
            temp_title + '"></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=9112',
                    data: {
                        temp_title: $("#temp_title1").val(),//模板标题
                        title: $("#title").val(),
                        content: $("#content").val(),
                        create_time: $("#create_time").val(),
                        valid_time: $("#valid_time").val(),
                        cond_min_lv: $("#cond_min_lv").val(),
                        cond_max_lv: $("#cond_max_lv").val(),
                        cond_create_time: $("#cond_create_time").val()
                    },
                    success: function () {
                        layer.close(index);
                        layer.alert('保存成功', {icon: 1}, function (index1) {
                            layer.close(index1);
                            templateSelect();//刷新模板下拉框
                        });
                    }
                });
            },
            cancel: function () {
            }
        });
    });
    $("#delete_temp").on('click', function () {
        var obj = $("#template");
        var id = obj.val();
        var temp_title = obj.find("option:selected").text();
        layer.alert('确认删除“' + temp_title + '”模板？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=914",
                data: {
                    id: id
                },
                success: function () {
                    layer.alert('删除成功', {icon: 1}, function (index) {
                        layer.close(index);
                        obj.empty();
                        templateSelect();//刷新模板下拉框
                    });
                }
            });
        });
    });
    //模板切换
    $("#template").on('change', function () {
        var id = $("#template").val();
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=912",
            data: {
                id: id
            },
            dataType: 'json',
            success: function (json) {
                $("#title").val(json.title);
                $("#content").val(json.content);
                $("#create_time").val('');
                $("#valid_time").val('');
                $("#cond_min_lv").val(json.cond_min_lv);
                $("#cond_max_lv").val(json.cond_max_lv);
                $("#cond_create_time").val('');
            }
        });
    });
<?php echo '</script'; ?>
>
<?php }
}
