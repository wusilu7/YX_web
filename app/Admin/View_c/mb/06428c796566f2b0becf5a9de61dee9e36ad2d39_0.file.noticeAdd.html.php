<?php
/* Smarty version 3.1.30, created on 2023-04-21 09:28:17
  from "/lnmp/www/app/Admin/View/mb/noticeAdd.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6441e6b1e61f71_72067408',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '06428c796566f2b0becf5a9de61dee9e36ad2d39' => 
    array (
      0 => '/lnmp/www/app/Admin/View/mb/noticeAdd.html',
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
function content_6441e6b1e61f71_72067408 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.09.notice.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>公告添加</span></div>
<hr/>

<div class="col-sm-8 col-sm-offset-2 form-horizontal" id="jin-add-mother">
    <br/>
    <br/>
    <div class="form-group">
        <label class="col-sm-2 control-label">选择渠道</label>
        <div id="groups" class="col-sm-10">
            <!--多选列表-->
        </div>
    </div>
    <div class="form-group">
        <label for="time_start" class="col-sm-2 control-label">开始时间</label>
        <div class="col-sm-10">
            <input id="time_start" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="time_end" class="col-sm-2 control-label">失效时间</label>
        <div class="col-sm-10">
            <input id="time_end" class="form-control"/>
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
                <label for="t1" class="col-sm-2 control-label ">公告标题</label>
                <div class="col-sm-10">
                    <input id="t1" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label for="c1" class="col-sm-2 control-label">公告内容</label>
                <div class="col-sm-10">
                    <textarea id="c1" class="form-control" cols="30" rows="12" placeholder=""></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content2">
            <div class="form-group">
                <label for="t2" class="col-sm-2 control-label ">公告标题</label>
                <div class="col-sm-10">
                    <input id="t2" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label for="c2" class="col-sm-2 control-label">公告内容</label>
                <div class="col-sm-10">
                    <textarea id="c2" class="form-control" cols="30" rows="12" placeholder=""></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content3">
            <div class="form-group">
                <label for="t3" class="col-sm-2 control-label ">公告标题</label>
                <div class="col-sm-10">
                    <input id="t3" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label for="c3" class="col-sm-2 control-label">公告内容</label>
                <div class="col-sm-10">
                    <textarea id="c3" class="form-control" cols="30" rows="12" placeholder=""></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content4">
            <div class="form-group">
                <label for="t4" class="col-sm-2 control-label ">公告标题</label>
                <div class="col-sm-10">
                    <input id="t4" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label for="c4" class="col-sm-2 control-label">公告内容</label>
                <div class="col-sm-10">
                    <textarea id="c4" class="form-control" cols="30" rows="12" placeholder=""></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content5">
            <div class="form-group">
                <label for="t5" class="col-sm-2 control-label ">公告标题</label>
                <div class="col-sm-10">
                    <input id="t5" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label for="c5" class="col-sm-2 control-label">公告内容</label>
                <div class="col-sm-10">
                    <textarea id="c5" class="form-control" cols="30" rows="12" placeholder=""></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content6">
            <div class="form-group">
                <label for="t6" class="col-sm-2 control-label ">公告标题</label>
                <div class="col-sm-10">
                    <input id="t6" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label for="c6" class="col-sm-2 control-label">公告内容</label>
                <div class="col-sm-10">
                    <textarea id="c6" class="form-control" cols="30" rows="12" placeholder=""></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content7">
            <div class="form-group">
                <label for="t7" class="col-sm-2 control-label ">公告标题</label>
                <div class="col-sm-10">
                    <input id="t7" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label for="c7" class="col-sm-2 control-label">公告内容</label>
                <div class="col-sm-10">
                    <textarea id="c7" class="form-control" cols="30" rows="12" placeholder=""></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content8">
            <div class="form-group">
                <label for="t8" class="col-sm-2 control-label ">公告标题</label>
                <div class="col-sm-10">
                    <input id="t8" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label for="c8" class="col-sm-2 control-label">公告内容</label>
                <div class="col-sm-10">
                    <textarea id="c8" class="form-control" cols="30" rows="12" placeholder=""></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content9">
            <div class="form-group">
                <label for="t9" class="col-sm-2 control-label ">公告标题</label>
                <div class="col-sm-10">
                    <input id="t9" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label for="c9" class="col-sm-2 control-label">公告内容</label>
                <div class="col-sm-10">
                    <textarea id="c9" class="form-control" cols="30" rows="12" placeholder=""></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content10">
            <div class="form-group">
                <label for="t10" class="col-sm-2 control-label ">公告标题</label>
                <div class="col-sm-10">
                    <input id="t10" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label for="c10" class="col-sm-2 control-label">公告内容</label>
                <div class="col-sm-10">
                    <textarea id="c10" class="form-control" cols="30" rows="12" placeholder=""></textarea>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="nav_content11">
            <div class="form-group">
                <label for="t11" class="col-sm-2 control-label ">公告标题</label>
                <div class="col-sm-10">
                    <input id="t11" class="form-control"/>
                </div>
            </div>
            <div class="form-group">
                <label for="c11" class="col-sm-2 control-label">公告内容</label>
                <div class="col-sm-10">
                    <textarea id="c11" class="form-control" cols="30" rows="12" placeholder=""></textarea>
                </div>
            </div>
        </div>
    </div>
    <!--发送-->
    <div class="center">
        <button id="send" class="btn btn-primary jin-btn-short">增添</button>
    </div>
</div>

<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    $(function () {
        getAllGroup('groups');
    });
    function getAllGroup(type) {
        var c = '<div>' +
            '<input type="checkbox" id="check_all_' + type + '" class="regular-checkbox big-checkbox"/><label for="check_all_' + type + '"></label>' +
            '<label class="jin-all-text" for="check_all_' + type + '">勾选所有渠道</label>' +
            '<label id="role_num_' + type + '"></label></div>';
        $.ajax({
            type: "post",
            url: "?p=Admin&c=Rbac&a=selectRole&child=per_set&ri=127&jinIf=9125",//功能权限
            dataType: "json",
            success: function (json) {
                for (var i = 0; i < json.length; i++) {//取数据填表
                    c +=
                        '<div class="jin-checkbox-inline" style="display: inline-block;width: 250px;">' +
                        '<input type="checkbox" name="' + type + '" id="' + type + '_' + json[i].id + '" value="' + json[i].id + '" class="regular-checkbox"/>' +
                        '<label for="' + type + '_' + json[i].id + '"></label><label class="jin-checkbox-text" for="' + type + '_' + json[i].id + '">' + json[i].name + '</label>' +
                        '</div>';
                }
                $("#"+type).html(c);
                //全选函数
                checkedAll(type);
            }
        });
    }
    calendar('hour', '#time_start', '#time_end');


    //公告增添
    $("#send").on('click', function () {
        var t = $('#t').val();
        var c = $('#c').val();
        if (t !== '' && c !== '') {
            var data = {
                groups: checkedValue('groups').join(","),
                time_start: $("#time_start").val(),
                time_end: $("#time_end").val(),
                title1: $('#t1').val(),
                title2: $('#t2').val(),
                title3: $('#t3').val(),
                title4: $('#t4').val(),
                title5: $('#t5').val(),
                title6: $('#t6').val(),
                title7: $('#t7').val(),
                title8: $('#t8').val(),
                title9: $('#t9').val(),
                title10: $('#t10').val(),
                title11: $('#t11').val(),
                content1: $('#c1').val(),
                content2: $('#c2').val(),
                content3: $('#c3').val(),
                content4: $('#c4').val(),
                content5: $('#c5').val(),
                content6: $('#c6').val(),
                content7: $('#c7').val(),
                content8: $('#c8').val(),
                content9: $('#c9').val(),
                content10: $('#c10').val(),
                content11: $('#c11').val()
            };
            $.ajax({
                type: "POST",
                url: location.href + '&jinIf=911',
                data: data,
                dataType: 'json',
                success: function () {
                    layer.alert("增添成功", {icon: 1}, function (index) {
                        layer.close(index);
                        jsonQuery();
                    });
                },
                error: function () {
                    layer.alert("发送失败", {icon: 2});
                }
            });
        } else {
            layer.msg('请填写必要数据');
            return 0;
        }
    });

<?php echo '</script'; ?>
><?php }
}
