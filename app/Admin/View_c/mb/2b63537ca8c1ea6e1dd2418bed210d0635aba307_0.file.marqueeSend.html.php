<?php
/* Smarty version 3.1.30, created on 2023-06-07 16:58:57
  from "/lnmp/www/app/Admin/View/mb/marqueeSend.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_648046d1e4e247_22789270',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2b63537ca8c1ea6e1dd2418bed210d0635aba307' => 
    array (
      0 => '/lnmp/www/app/Admin/View/mb/marqueeSend.html',
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
function content_648046d1e4e247_22789270 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.12.marquee.css" rel="stylesheet">
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

<div class="jin-content-title"><span>跑马灯发送</span></div>
<div class="alert alert-info">
    <div class="form-group" id="group_server_6"></div>
</div>
<hr/>
<div class="form-horizontal">
    <div class="form-group">
        <label for="more_servers" class="col-sm-2 control-label ">发送服务器*</label>
        <div class="col-sm-10" id="servers">
            <select id="s" class="selectpicker show-tick col-sm-3 col-xs-12" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>
        </div>
    </div>
    <div class="form-group">
        <label for="time_start" class="col-sm-2 control-label">开始滚动时间</label>
        <div class="col-sm-10">
            <input id="time_start" class="form-control" placeholder="不填代表立即滚动"/>
        </div>
    </div>
    <div class="form-group">
        <label for="count" class="col-sm-2 control-label ">数量</label>
        <div class="col-sm-10">
            <input id="count" class="form-control" placeholder="数量"/>
        </div>
    </div>
    <div class="form-group">
        <label for="interval" class="col-sm-2 control-label ">间隔时间</label>
        <div class="col-sm-10">
            <input id="interval" class="form-control" placeholder="间隔时间，单位为秒"/>
        </div>
    </div>
    <div class="form-group">
        <label for="run_times" class="col-sm-2 control-label ">滚屏次数</label>
        <div class="col-sm-10">
            <input id="run_times" class="form-control" placeholder="滚屏次数，默认1次"/>
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
                <label for="words" class="col-sm-2 control-label">文字内容</label>
                <div class="col-sm-10">
                    <textarea id="words1" class="form-control" cols="30" rows="10" placeholder="内容最多500个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content2">
            <div class="form-group">
                <label for="words" class="col-sm-2 control-label">文字内容</label>
                <div class="col-sm-10">
                    <textarea id="words2" class="form-control" cols="30" rows="10" placeholder="内容最多500个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content3">
            <div class="form-group">
                <label for="words" class="col-sm-2 control-label">文字内容</label>
                <div class="col-sm-10">
                    <textarea id="words3" class="form-control" cols="30" rows="10" placeholder="内容最多500个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content4">
            <div class="form-group">
                <label for="words" class="col-sm-2 control-label">文字内容</label>
                <div class="col-sm-10">
                    <textarea id="words4" class="form-control" cols="30" rows="10" placeholder="内容最多500个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content5">
            <div class="form-group">
                <label for="words" class="col-sm-2 control-label">文字内容</label>
                <div class="col-sm-10">
                    <textarea id="words5" class="form-control" cols="30" rows="10" placeholder="内容最多500个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content6">
            <div class="form-group">
                <label for="words" class="col-sm-2 control-label">文字内容</label>
                <div class="col-sm-10">
                    <textarea id="words6" class="form-control" cols="30" rows="10" placeholder="内容最多500个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content7">
            <div class="form-group">
                <label for="words" class="col-sm-2 control-label">文字内容</label>
                <div class="col-sm-10">
                    <textarea id="words7" class="form-control" cols="30" rows="10" placeholder="内容最多500个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content8">
            <div class="form-group">
                <label for="words" class="col-sm-2 control-label">文字内容</label>
                <div class="col-sm-10">
                    <textarea id="words8" class="form-control" cols="30" rows="10" placeholder="内容最多500个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content9">
            <div class="form-group">
                <label for="words" class="col-sm-2 control-label">文字内容</label>
                <div class="col-sm-10">
                    <textarea id="words9" class="form-control" cols="30" rows="10" placeholder="内容最多500个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content10">
            <div class="form-group">
                <label for="words" class="col-sm-2 control-label">文字内容</label>
                <div class="col-sm-10">
                    <textarea id="words10" class="form-control" cols="30" rows="10" placeholder="内容最多500个字符"></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content11">
            <div class="form-group">
                <label for="words" class="col-sm-2 control-label">文字内容</label>
                <div class="col-sm-10">
                    <textarea id="words11" class="form-control" cols="30" rows="10" placeholder="内容最多500个字符"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
<!--发送-->
<div class="center">
    <button id="send" class="btn btn-primary jin-btn-short">发送</button>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    var data = {};
    gsSelect3('#g', '', '#s');
//    groupSelect();
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
//
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
//
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
    // gsSelect('#group', '#server');
    calendarOne('hour', '#time_start');

    function conver(s) {
        return s < 10 ? '0' + s : s;
    }

    $("#send").on('click', function () {
        var check_type = $('input[name=check_type]:checked').val();
        var filter_type = $('input[name=filter_type]:checked').val();
        var time_start_val = $("#time_start").val();
        var myDate = new Date();
        var year = myDate.getFullYear();
        var month = myDate.getMonth()+1;
        var date = myDate.getDate(); 
        var h = myDate.getHours();       
        var m = myDate.getMinutes();     
        var s = myDate.getSeconds(); 

//        if (time_start_val == null || time_start_val == undefined || time_start_val=="") {
//            time_start_val = year + '-' + conver(month) + "-" + conver(date) + " " + conver(h) + ':' + conver(m) + ":" + conver(s);
//        }

        var data = {
            gi:         $("#g").val(),
            si:         JSON.stringify($("#s").val()),
            group: $("#g").val()[0],
            check_type: check_type,
            filter_type:filter_type,
            time_start: time_start_val,
            count:      $("#count").val(),
            interval:   $("#interval").val(),
            run_times:  $("#run_times").val(),
            words1:      $("#words1").val(),
            words2:      $("#words2").val(),
            words3:      $("#words3").val(),
            words4:      $("#words4").val(),
            words5:      $("#words5").val(),
            words6:      $("#words6").val(),
            words7:      $("#words7").val(),
            words8:      $("#words8").val(),
            words9:      $("#words9").val(),
            words10:      $("#words10").val(),
            words11:      $("#words11").val()
        };
        $.ajax({
            type: "POST",
            url: location.href + '&jinIf=911',
            data: data,
            dataType: 'json',
            success: function (json) {
                if (json.status == 1) {
                    layer.alert("发送成功，请等待审核", {icon: 1});
                } else {
                    layer.alert(json.msg, {icon: 2});
                }
            },
            error: function () {
                layer.alert("发送失败", {icon: 2});
            }
        });
    });
<?php echo '</script'; ?>
>
<?php }
}
