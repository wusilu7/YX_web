<?php
/* Smarty version 3.1.30, created on 2024-03-09 18:12:49
  from "/lnmp/www/app/Admin/View/player/suggestion.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65ec3621298c10_51547670',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'bd1cc227d8f2ba0585b9994d98d954ad5c136a14' => 
    array (
      0 => '/lnmp/www/app/Admin/View/player/suggestion.html',
      1 => 1709979089,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_65ec3621298c10_51547670 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<!--|↓↓↓↓↓↓|-->
<style type="text/css">
    .col-sm-1 {
        width: 90px;
        padding-top: 8px;
    }
    .alert-info{
        color: white;
        padding-bottom: 50px;
    }
    .form-group{
        margin-bottom: 35px;
    }
    #server_top .dropdown-header .text{
        font-size: 20px;
        color: black;
        font-weight: 500;
    }
    .text_content{
        margin-left: 35px;
    }
    .dropdown-header{
        padding: 10px 20px;
    }
    #server_top{
        width: 100px
    }
    #group{
        width: 100%
    }
</style>

<div class="jin-content-title"><span>玩家反馈</span></div>
<div class="alert alert-info">
    <div id="group_server_9"></div>
</div>
<!--查询区-->
<hr/>
<input id="player_name" maxlength="20" type="text" class="form-control jin-search-input" placeholder="角色ID">
<a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
<input type="text" id="roleid_mail_back_id" class="form-control" placeholder="请按反馈ID填写，多个反馈之间用逗号分隔" style="width:30%;display:inline">
<a id="marks" class="btn btn-success">标记</a>
<a id="rebacks" class="btn btn-danger">取消标记</a>
<hr/>
<!--数据区-->
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th class="jin-server-column1">
                <input id="all_choose" type="checkbox">
                <label for="all_choose">全选</label>
            </th>
            <th>反馈ID</th>
            <th>角色ID</th>
            <th>内容</th>
            <th>反馈时间</th>
            <th>操作</th>
            <th style="display: none;">服务器</th>
            <th>标记</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>

<div id="page"></div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ① 查询框留空表示查询当前服全部角色信息；
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 type="text/javascript">
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
    // 点击批量维护
    $('#marks').click(function() {
        var arr = getChoose();
        if (arr.server_id == '') {
            layer.alert('请选择！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            all_open(arr.server_id, arr.name,'标记',1);
        }
    });
    $('#rebacks').click(function() {
        var arr = getChoose();
        if (arr.server_id == '') {
            layer.alert('请选择！', {icon: 2}, function (index) {
                layer.close(index);
                return false;
            });
        } else {
            all_open(arr.server_id, arr.name,'标记',0);
        }
    });

    // 批量维护
    function all_open(server_id, name,info,status) {
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=9131",
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
                data.page = 1;
                data.si = $("#s").val();
                data.player_name = $("#player_name").val();
                tableList(url, data, id, arr)
                // var cc='';
                // for (var i=0;i<json.length;i++){
                //     cc+='<span style="color: red;">'+json[i].si+'服务器:</span>'+json[i].msg+'<br>'
                // }
                // layer.alert(cc, {icon: 6,area:["600px","400px"]}, function (index) {
                //     layer.close(index);
                // });
            }
        });
    }

    function getChoose() {
        var server_id = '';
        var name = '';
        var first_server = '';

        $('#content input[type="checkbox"]:checked').each(function(index, el) {
            if (index == 0) {
                server_id = $(el).val();
                name = $(el).parent('td').siblings('td').eq(1).text();
                first_server = $(el).val();
            } else {
                server_id += ',' + $(el).val();
                name += ',' + $(el).parent('td').siblings('td').eq(1).text();
            }
        });

        return {
            'server_id': server_id,
            'name': name,
            'first_server':first_server
        };
    }

    var gii = function (json) {
        return  '<div style="display: none;">'+
            +json.si+
            '</div>'

        // return '<div style="text-align:left;">'
        //     +json.EquipInfo+
        //     '</div>'
    };
    var sta = function(json){
        if (json.mark ==1) {
            return '<div style="background-color：red">'+
                '已标记'+
                '</div>';
        }else{
            return '<div style="color：green">'+
                '未标记'+
                '</div>';
        }
    }
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
            'data-data-info11="'+json.info11+'"  value="' + json.id + '" />';
    }
    //gsSelect('#group', '#server', '', getSuggestion);
    gsSelect3('#g', '', '#s');
    var url = location.href + "&jinIf=912";
    var btn = [
        '<div class="btn-group btn-group-sm">' +
        '<a data-type="reply1" class="btn btn-primary">回复邮件</a>' +
        // '<a data-type="history" class="btn btn-success">历史反馈</a>' +
        '</div>'
    ];
    var arr = [checkbox,'id', 'char_id', 'content', 'create_at', btn,gii,sta];
    //var arr = ['id', 'char_id', 'content', 'create_at'];
    var id = ["#content", "#page"];
    var data = {};

    $("#jin_search").on('click', function () {
        data.page = 1;
        data.si = $("#s").val();
        data.player_name = $("#player_name").val();
        tableList(url, data, id, arr)
    });

    $("#roleid_mail_back").on('click', function () {
        sugges_id = $("#roleid_mail_back_id").val();

        if (sugges_id) {
            layer.open({
                type: 1,
                closeBtn: 2,
                title: '批量回复',
                area: ['400px', '350px'],
                btn: ['回复', '取消'],
                btnAlign: 'c',
                shadeClose: true, //点击遮罩关闭
                content: '<div class="jin-child">' +
                    '<div class="input-group"><span class="input-group-addon">反馈ID</span><input id="sugges_id" type="text" class="form-control" value="' +
                    sugges_id + '" readonly></div>' +
                    '<div class="input-group"><textarea id="reply_content" style="width: 365px;height: 161px;">您的反馈我们已收到，感谢您的支持与理解。</textarea></div>' +
                    '</div>',
                yes: function (index) {
                    $.ajax({
                        type: "POST",
                        url: location.href + '&jinIf=913',
                        data: {
                            sugges_id : sugges_id,
                            reply_content : $('#reply_content').val(),
                            sugges_ids : 1
                        },
                        dataType: 'json',
                        success: function (json) {
                            console.log(json.status);
                            if (json.status == 1) {
                                layer.close(index);
                                layer.alert(json.msg, {icon: 1}, function (index) {
                                    layer.close(index);
                                });
                            } else {
                                layer.alert(json.msg, {icon: 2});
                            }
                        }
                    });
                },
                cancel: function () {
                }
            })
        } else {
            layer.alert("请填写角色ID");
        }
    });
    $('#content').on('click', 'a[data-type="reply1"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(2).text();
        var si = $(this).parents('tr').find('td').eq(6).text();
        window.open('/?p=Admin&c=Mb&a=mailSend&char_id='+char_id+'&si='+si,'_blank');
    })

    $('#content').on('click', 'a[data-type="reply"]', function () {//基础修改
        var sugges_id = $(this).parents('tr').find('td').eq(0).text();
        var char_id = $(this).parents('tr').find('td').eq(1).text();
        var feedback = $(this).parents('tr').find('td').eq(2).text();
        var feedback_time = $(this).parents('tr').find('td').eq(3).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '问题反馈回复',
            area: ['400px', '450px'],
            btn: ['回复', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
                '<div class="input-group"><span class="input-group-addon">反馈ID</span><input id="sugges_id" type="text" class="form-control" value="' +
                sugges_id + '" readonly></div>' +
                '<div class="input-group"><span class="input-group-addon">角色ID</span><input id="char_id" type="text" class="form-control" value="' +
                char_id + '" readonly></div>' +
                '<div class="input-group"><span class="input-group-addon">内容</span><input id="feedback" type="text" class="form-control" value="' +
                feedback + '" readonly></div>' +
                '<div class="input-group"><span class="input-group-addon">反馈时间</span><input id="feedback_time" type="text" class="form-control" value="' +
                feedback_time + '" readonly></div>' +
                '<div class="input-group"><span class="input-group-addon">回复</span><textarea name="reply_content" id="reply_content" cols="40" rows="5">您的反馈我们已收到，感谢您的支持与理解。</textarea></div>' +
                '</div>',
            yes: function (index) {
                var reply_content = $('#reply_content').val();
                if (reply_content == '') {
                    layer.alert('请填写回复内容', {icon: 2});
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=913',
                    data: {
                        sugges_id: $('#sugges_id').val(),
                        reply_content: reply_content
                    },
                    dataType: 'json',

                    success: function (json) {
                        console.log(json.status);
                        if (json.status == 1) {
                            layer.close(index);
                            layer.alert(json.msg, {icon: 1}, function (index) {
                                layer.close(index);
                            });
                        } else {
                            layer.alert(json.msg, {icon: 2});
                        }
                    }
                });
            },
            cancel: function () {
            }
        });
    }).on('click', 'a[data-type="history"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(1).text();
        location.href += '&char_id=' + char_id;
    });
<?php echo '</script'; ?>
>
<?php }
}
