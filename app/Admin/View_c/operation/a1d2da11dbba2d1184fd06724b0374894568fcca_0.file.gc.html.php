<?php
/* Smarty version 3.1.30, created on 2024-09-03 14:14:17
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\operation\gc.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66d6a939bb0a90_36226338',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'a1d2da11dbba2d1184fd06724b0374894568fcca' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\operation\\gc.html',
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
function content_66d6a939bb0a90_36226338 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 language="javascript" type="text/javascript" src="<?php echo JS;?>
WdatePicker.js"><?php echo '</script'; ?>
>
<link href="<?php echo CSS;?>
jin/3.20.gc.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>礼包码配置</span></div>
<!--<div class="alert alert-info">-->
<!--<div id="group_server"></div>-->
<!--</div>-->
<!--导航区-->
<ul class="nav nav-tabs">
    <li class="active"><a href="#gc_create" data-toggle="tab">生成礼包码</a></li>
    <li><a href="#gc_down" data-toggle="tab">礼包码下载</a></li>
    <li><a href="#gc_query" data-toggle="tab">单一礼包码查询</a></li>
</ul>
<!-- 面板区 -->
<div class="tab-content">
    <!--生成礼包码-->
    <div class="tab-pane active" id="gc_create">
        <!--主输入区-->
        <div class="form-horizontal">
            <div class="form-group">
                <label for="g" class="col-sm-2 control-label">渠道</label>
                <div class="col-sm-9">
                    <select id="g" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>
                </div>
            </div>
            <div class="form-group">
                <label for="time_start" class="col-sm-2 control-label">生效时间</label>
                <div class="col-sm-10">
                    <input id="time_start" class=" form-control" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd HH:mm:ss'})">
                    <!-- <input id="time_start" type="datetime-local" class="form-control"/> -->
                </div>
            </div>
            <div class="form-group">
                <label for="time_end" class="col-sm-2 control-label">过期时间</label>
                <div class="col-sm-10">
                    <input id="time_end" class=" form-control" type="text" onClick="WdatePicker({el:this,dateFmt:'yyyy-MM-dd HH:mm:ss'})">
                    <!-- <input id="time_end" type="datetime-local" class="form-control"/> -->
                </div>
            </div>
            <div class="form-group">
                <label for="gift_id" class="col-sm-2 control-label">绑定礼包编号</label>
                <div class="col-sm-10">
                    <input id="gift_id" class="form-control" placeholder="输入礼包编号，把即将生成的礼包码和对应的礼包绑定"/>
                </div>
            </div>
            <div class="form-group">
                <label for="code_type" class="col-sm-2 control-label">礼包码类型</label>
                <div class="col-sm-3">
                    <select id="code_type">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div id="content_code">
            </div>
            <div class="form-group">
                <label for="gift_type" class="col-sm-2 control-label">分组编号</label>
                <div class="col-sm-10">
                    <input id="gift_type" class="form-control" placeholder="0不分组 , 大于0且相等分为一组,一个角色只能用该分组下面一个码组"/>
                </div>
            </div>
        </div>
        <!--发送-->
        <div class="center">
            <button id="send" class="btn btn-primary jin-btn-short">生成</button>
        </div>
    </div>
    <!--礼包码下载-->
    <div class="tab-pane" id="gc_down">
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center jin-board-table">
                <thead>
                <tr>
                    <th>码组号</th>
                    <th>礼包码类型</th>
                    <th>分组</th>
                    <th>渠道id</th>
                    <th>渠道</th>
                    <th>生效时间</th>
                    <th>过期时间</th>
                    <th>前缀/礼包码</th>
                    <th>绑定礼包</th>
                    <th>数量/最大使用次数</th>
                    <th>剩余数量</th>
                    <th>创建时间</th>
                    <th>创建人</th>
                    <th class="jin-gc-column10">操作</th>
                </tr>
                </thead>
                <tbody id="content_down"></tbody>
            </table>
        </div>
        <div id="page_down"></div>
    </div>
    <!--礼包码查询-->
    <div class="tab-pane" id="gc_query">
        <div class="form-horizontal">
            <div class="form-group">
                <label for="code" class="col-sm-4 control-label ">礼包码</label>
                <div class="col-sm-5">
                    <input id="code" class="form-control" maxlength="30" placeholder="请输入一串礼包码"/>
                </div>
            </div>
            <div class="form-group">
                <label for="code_search" class="col-sm-4 control-label "></label>
                <div class="col-sm-5">
                    <button id="code_search" class="btn btn-primary">查找</button>
                </div>
            </div>
            <div id="content_query"></div>
        </div>
    </div>
</div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        通用礼包码：共用一个礼包码，一个角色只能用一次；<br>
        单一礼包码：N个礼包码，一个角色只能用一个；<br>
        单一礼包码（非限定）：N个礼包码，角色使用个数不受限定；<br>
        分组(仅通用码)：0不分组 , 大于0且相等分为一组,一个角色只能用该分组下面一个通用码,且只能用一次；<br>
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    var data = {};
    $(function () {
        // gsSelect('#group', '#server');
        // calendar('hour', '#time_start', '#time_end');
        gcCreate();
        gcDown();
        gcQuery();
        codeSelect();
        //codeGroupSelect();
        codeGroupSelect('#g')
    });

    //礼包码类型切换
    $("#code_type").on('change', function () {
        var c = '';
        switch ($(this).val()) {
            case '0':
                c +=
                    '<div class="form-group">' +
                    '<label for="prefix" class="col-sm-2 control-label">礼包码前缀</label>' +
                    '<div class="col-sm-10">' +
                    '<input id="prefix" class="form-control" maxlength="4" placeholder="自定义英文字母或数字前缀，建议在4个字符以内"/>' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="num" class="col-sm-2 control-label">生成数量</label>' +
                    '<div class="col-sm-10">' +
                    '<input id="num" class="form-control" maxlength="7" placeholder="礼包码数量，最大数量99999个，需要更多可分批生成"/>' +
                    '</div>' +
                    '</div>';
                break;
            case '1':
                c +=
                    '<div class="form-group">' +
                    '<label for="prefix" class="col-sm-2 control-label">礼包码</label>' +
                    '<div class="col-sm-10">' +
                    '<input id="prefix" class="form-control" maxlength="20" placeholder="自定义一个英文字母或数字组合而成的通用礼包码"/>' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="num" class="col-sm-2 control-label">最大使用次数</label>' +
                    '<div class="col-sm-10">' +
                    '<input id="num" class="form-control" maxlength="7" placeholder="此码可被使用的次数"/>' +
                    '</div>' +
                    '</div>';
                break;
            case '2':
                c +=
                    '<div class="form-group">' +
                    '<label for="prefix" class="col-sm-2 control-label">礼包码前缀</label>' +
                    '<div class="col-sm-10">' +
                    '<input id="prefix" class="form-control" maxlength="4" placeholder="自定义英文字母或数字前缀，建议在4个字符以内"/>' +
                    '</div>' +
                    '</div>' +
                    '<div class="form-group">' +
                    '<label for="num" class="col-sm-2 control-label">生成数量</label>' +
                    '<div class="col-sm-10">' +
                    '<input id="num" class="form-control" maxlength="5" placeholder="礼包码数量，最大数量99999个，需要更多可分批生成"/>' +
                    '</div>' +
                    '</div>';
                break;
            default:
                break;
        }
        $("#content_code").html(c);
    });

    //切换页面
    $('ul').on('click', 'a[href="#gc_down"]', function () {
        jsonDown();
    });

    function jsonDown() {
        // var btn = [
        //     "<div class='btn-group btn-group-sm'>" +
        //     "<button data-type='down' class='btn btn-success'>下载</button>" +
        //     "<button data-type='delete' class='btn btn-danger'>删除</button>" +
        //     "</div>"
        // ];
        var btn = function (json) {
            var btn1 = [
                "<div class='btn-group btn-group-sm'>" +
                "<button data-data-id='"+json.group_id+"' data-type='update'  class='btn btn-primary'>修改</button>" +
                "<button data-type='down' data-num='300000' class='btn btn-success'>下载</button>" +
                "<button data-type='delete' class='btn btn-danger'>删除</button>" +
                "</div>"
            ];
            var btn2 = [
                "<div class='btn-group btn-group-sm'>" +
                "<button data-data-id='"+json.group_id+"' data-type='update'  class='btn btn-primary'>修改</button>" +
                "<button data-type='down' data-num='300000' class='btn btn-success'>下载1</button>" +
                "<button data-type='down' data-num='600000' class='btn btn-success'>下载2</button>" +
                "<button data-type='down' data-num='900000' class='btn btn-success'>下载3</button>" +
                "<button data-type='down' data-num='1200000' class='btn btn-success'>下载4</button>" +
                "<button data-type='delete' class='btn btn-danger'>删除</button>" +
                "</div>"
            ];

            // 5万个礼包以上分开两次下载
            if (json.num < 300000) {
                return btn1;
            } else {
                return btn2;
            }
        }
        var url = location + "&jinIf=912";
        var arr = ['gc_id', 'code_type','gift_type', 'group_id', 'group_name', 'time_start', 'time_end', 'prefix', 'gift_id', 'num', 'remainder', 'ct', 'cu', btn];
        var id = ["#content_down", "#page_down"];
        var data = {
            page: 1
        };
        $(document).ready(tableList(url, data, id, arr));
    }

    //礼包码生成
    function gcCreate() {
        $("#send").on('click', function () {
            var data = {
                // si: $("#server").val(),
                si: '999',
                time_start: $("#time_start").val(),
                time_end: $("#time_end").val(),
                gift_id: $("#gift_id").val(),
                gift_type: $("#gift_type").val(),
                code_type: $("#code_type").val(),
                prefix: $("#prefix").val(),
                num: $("#num").val()
            };
            var code_group = $("#g").val();
            if (code_group === 'null') {
                data.code_group = '';
            } else {
                data.code_group = code_group;
            }
            $.ajax({
                type: "POST",
                url: location.href + '&jinIf=911',
                data: data,
                dataType: 'json',
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']
                    });
                },
                success: function () {
                    layer.closeAll('loading');
                    layer.alert("生成成功", {icon: 1});
                },
                error: function () {
                    layer.closeAll('loading');
                    layer.alert("生成失败", {icon: 2});
                }
            });
        });
    }

    //礼包码下载
    function gcDown() {
        $('#content_down').on('click', 'button[data-type="down"]', function () {
            $.ajax({
                type: "POST",
                url: location.href + '&jinIf=951',
                data: {
                    gc_id: $(this).parents('tr').find('td').eq(0).text(),
                    num: $(this).attr('data-num')
                },
                dataType: "json",
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']
                    });
                },
                success: function (output) {
                    layer.closeAll('loading');
                    location.href = output;
                },
                error: function () {
                    layer.closeAll('loading')
                    layer.msg('文件下载失败');
                }
            });
        }).on('click', 'button[data-type="delete"]', function () {
            var gc_id = $(this).parents('tr').find('td').eq(0).text();
            layer.alert('确认删除[' + gc_id + '号礼包码组]？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=914",
                    data: {
                        gc_id: gc_id
                    },
                    success: function () {
                        layer.alert('删除成功', {icon: 1}, function (index) {
                            layer.close(index);
                            jsonDown();
                        });
                    }
                });
            });
        }).on('click', 'button[data-type="update"]', function () {
            var gc_id = $(this).parents('tr').find('td').eq(0).text();
            var time_start = $(this).parents('tr').find('td').eq(5).text();
            var time_end = $(this).parents('tr').find('td').eq(6).text();
            var gc_num = $(this).parents('tr').find('td').eq(9).text();
            var gift_type = $(this).parents('tr').find('td').eq(2).text();
            var group_ids = $(this).attr('data-data-id');
            layer.open({
                type: 1,
                closeBtn: 2,
                title: '修改',
                area: ['400px', '400px;'],
                btn: ['修改', '取消'],
                btnAlign: 'c',
                shadeClose: true, //点击遮罩关闭
                content: '<div class="jin-child">' +
                '<div class="input-group"><span class="input-group-addon">分组</span><input id="gift_type_son" type="text" class="form-control" value="' +
                gift_type + '"></div>' +
                '<div class="input-group"><span class="input-group-addon">开始时间</span><input id="time_start1" type="text" class="form-control" value="' +
                time_start + '"></div>' +
                '<div class="input-group"><span class="input-group-addon">结束时间</span><input id="time_end1" type="text" class="form-control" value="' +
                time_end + '"></div>' +
                '<div class="input-group"><span class="input-group-addon">最大数量</span><input id="gc_num" type="text" class="form-control" value="' +
                gc_num + '"></div>' +
                '<div class="input-group"><span class="input-group-addon">渠道限制</span><input id="group_ids" type="text" class="form-control" value="' +
                group_ids + '"></div>' +
                '</div>',
                yes: function (index) {
                    $.ajax({
                        type: "POST",
                        url: location.href + '&jinIf=913',
                        data: {
                            gc_id: gc_id,
                            gift_type:$('#gift_type_son').val(),
                            time_start: $('#time_start1').val(),
                            time_end: $('#time_end1').val(),
                            gc_num: $('#gc_num').val(),
                            group_ids:$('#group_ids').val()
                        },
                        success: function () {
                            layer.close(index);
                            layer.alert('修改成功', {icon: 1}, function (index) {
                                layer.close(index);
                                jsonDown();
                            });
                        }
                    });
                }
            });
            calendar('hour', '#time_start1', '#time_end1');
            return false;
        });
    }

    //礼包码查询
    function gcQuery() {
        $("#code_search").on('click', function () {
            $.ajax({
                type: "POST",
                url: location.href + '&jinIf=9121',
                data: {
                    code: $("#code").val()
                },
                dataType: 'json',
                beforeSend: function () {
                    layer.load(2, {
                        shade: [0.3, '#fff']//0.3透明度的白色背景
                    });
                },
                success: function (json) {
                    layer.closeAll('loading');
                    var c = '';
                    if (json) {
                        c +=
                            '<div class="table-responsive">' +
                            '<table class="table table-striped text-center">' +
                            '<thead>' +
                            '<tr>' +
                            '<th>礼包码</th>' +
                            '<th>对应礼包</th>' +
                            '<th>生效时间</th>' +
                            '<th>过期时间</th>' +
                            '<th>状态</th>' +
                            '</tr>' +
                            '</thead>' +
                            '<tbody>' +
                            '<tr>' +
                            '<td>' + json.code + '</td>' +
                            '<td>' + json.gift_id + '</td>' +
                            '<td>' + json.time_start + '</td>' +
                            '<td>' + json.time_end + '</td>' +
                            '<td>' + json.state + '</td>' +
                            '</tr>' +
                            '</tbody>' +
                            '</table>' +
                            '</div>';
                    } else {
                        c = '<h1 class="text-center jin-none"><span class="label label-danger">礼包码不存在，请检查输入是否正确</span></h1>';
                    }
                    $('#content_query').html(c);
                },
                error: function () {
                    layer.closeAll('loading');
                    layer.msg('数据获取失败，请勿频繁刷新');
                }
            });
        });
    }
<?php echo '</script'; ?>
>
<?php }
}
