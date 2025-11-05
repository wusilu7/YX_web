<?php
/* Smarty version 3.1.30, created on 2023-12-25 12:32:28
  from "/lnmp/www/app/Admin/View/player/cheater1.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_658905dce3b256_43815518',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'e7134dee5bf4e68063c9cfb6067ebf76fea8b0b5' => 
    array (
      0 => '/lnmp/www/app/Admin/View/player/cheater1.html',
      1 => 1678771402,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_658905dce3b256_43815518 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>安卓外挂检测</span></div>
<div class="alert alert-info hide">
    <div id="group_server"></div>
</div>
<!--查询div-->
<hr/>
<div class="jin-search-div">
    <div class="form-group" id="group_server_6"></div>
    <div>
        <label for="time_start">日期：</label>
        <input size="16" type="text" id="time_start" class="form-control jin-datetime"
               placeholder="开始日期">
        -
        <input size="16" type="text" id="time_end" class="form-control jin-datetime"
               placeholder="结束日期">
        <input size="16" type="checkbox" id="ischeck1" value="1">
        <label for="ischeck1" style="margin-left: 0px;">去重</label>
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
        <a id="jin_excel" class="btn btn-danger">保存到Excel</a>
        <a id="ban_login" class="btn btn-danger">封号</a>
    </div>
    <div>
        <label for="acc">筛选：</label>
        <input id="code" type="text" class="form-control jin-search-input" placeholder="设备">
        <input id="acc" type="text" class="form-control jin-search-input" placeholder="账号">
        <input id="char" type="text" class="form-control jin-search-input" placeholder="角色ID/角色名">
        <input id="pack" type="text" class="form-control jin-search-input" placeholder="包名">
    </div>
    <div>
        <label for="check_result" style="font-size: 13px;">检测结果：</label>
        <select  id="check_result"></select>
        <label for="risk" style="font-size: 13px;">风险环境：</label>
        <select  id="risk" class="form-control jin-search-input"></select>
        <label for="risk_level" style="font-size: 13px;">风险等级：</label>
        <select  id="risk_level" class="form-control jin-search-input"></select>
        <label for="defense_result" style="font-size: 13px;">防御结果：</label>
        <select  id="defense_result" class="form-control jin-search-input">
            <option value="999">全部</option>
            <option value="0">检测</option>
            <option value="1">闪退</option>
        </select>
    </div>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr id="thead">
            <th class="jin-server-column1">
                <input id="all_choose" type="checkbox">
                <label for="all_choose">全选</label>
            </th>
            <th>渠道</th>
            <th>设备名</th>
            <th>设备类型</th>
            <th>设备号</th>
            <th>账号</th>
            <th>角色ID</th>
            <th>角色名</th>
            <th>ip</th>
            <th>包名</th>
            <th>时间</th>
            <th>检测结果</th>
            <th>风险环境</th>
            <th>风险等级</th>
            <th>防御结果</th>
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
    $(document).ready(gsSelect3('#g'));
    var url = "http://croodsadmin.xuanqu100.com/?p=I&c=Player&a=cheater1";
    var acc_check = function (json) {
        return '<input type="checkbox" value="' + json['acc'] + '" />'
    };
    var gi = function (json) {
        return '渠道'+json['gi']+'--'+json['si']+'';
    };
    var arr = [acc_check,gi,'device_name','device_type','code', 'acc', 'char_id', 'char_name','ip','pack','time','check_result','risk','risk_level','defense_result'];
    var id = ["#content", "#page"];
    var data = {};
    $(function () {
        gsSelect('#group', '#server', '#platform');
        calendar('minute', '#time_start', '#time_end');
        var check_result = [
            {
                id: 0,
                text: '无结果'
            },
            {
                id: 1,
                text: '安装了外挂'
            },
            {
                id: 4,
                text: '设备黑名单'
            },
            {
                id: 8,
                text: '加速'
            },
            {
                id: 16,
                text: '隐藏 Root'
            },
            {
                id: 32,
                text: '隐藏安装包'
            },
            {
                id: 64,
                text: '模块注入'
            },
            {
                id: 128,
                text: '内存修改'
            },
            {
                id: 256,
                text: '破解'
            }
        ];
        $("#check_result").select2({
            data: check_result,
            width: "300px",
            multiple: true
        });
        var risk = [
            {
                id: 0,
                text: '设备环境正常'
            },
            {
                id: 1,
                text: '无 SIM 卡'
            },
            {
                id: 2,
                text: 'root'
            },
            {
                id: 3,
                text: '模拟器'
            },
            {
                id: 4,
                text: '虚拟机'
            },
            {
                id: 5,
                text: '云手机'
            }
        ];
        $("#risk").select2({
            data: risk,
            width: "300px",
            multiple: true
        });

        var risk_level = [
            {
                id: 0,
                text: '无风险'
            },
            {
                id: 1,
                text: '无 SIM 卡'
            },
            {
                id: 2,
                text: '安装了模拟点击类外挂'
            },
            {
                id: 3,
                text: 'Root 或模拟器'
            },
            {
                id: 4,
                text: '安装了修改器外挂'
            },
            {
                id: 5,
                text: '云手机'
            },
            {
                id: 6,
                text: '隐藏 Root 或者安装包'
            },
            {
                id: 7,
                text: '设备黑名单'
            },
            {
                id: 10,
                text: '认定为外挂或恶意行为'
            },
            {
                id: 11,
                text: '签名非官方的破解版'
            },
            {
                id: 12,
                text: '插入异常模块的破解版'
            },
            {
                id: 13,
                text: '隐藏root,安装GG修改器'
            },
            {
                id: 14,
                text: '系统函数被HOOK'
            },
            {
                id: 15,
                text: '恶意隐藏安装包'
            },
            {
                id: 16,
                text: 'vpn专用挂'
            },
            {
                id: 17,
                text: '隐藏内存修改'
            },
            {
                id: 18,
                text: '修改器隐藏ROOT'
            }

        ];
        $("#risk_level").select2({
            data: risk_level,
            width: "300px",
            multiple: true
        });

    });
    function getCharge() {
        data.page       = 1;
        data.time_start = $('#time_start').val();
        data.time_end   = $('#time_end').val();
        data.acc        = $("#acc").val();
        data.code       = $("#code").val();
        data.char       = $('#char').val();
        data.pack       = $('#pack').val();
        data.check_result      = $('#check_result').val();
        data.risk      = $('#risk').val();
        data.defense_result      = $('#defense_result').val();
        data.risk_level      = $('#risk_level').val();
        data.ischeck1       = $('#ischeck1').is(':checked') ? $('#ischeck1').val() : '';
        data.gi = $("#g").val();
        tableList(url, data, id, arr);
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        getCharge();
    });
    // 导出Excel
    $("#jin_excel").on('click', function () {
        data.page       = 'excel';
        data.time_start = $('#time_start').val();
        data.time_end   = $('#time_end').val();
        data.acc        = $("#acc").val();
        data.code       = $("#code").val();
        data.char       = $('#char').val();
        data.pack      = $('#pack').val();
        data.check_result      = $('#check_result').val();
        data.risk      = $('#risk').val();
        data.defense_result      = $('#defense_result').val();
        data.risk_level      = $('#risk_level').val();
        data.ischeck1       = $('#ischeck1').is(':checked') ? $('#ischeck1').val() : '';
        data.gi = $("#g").val();
        $.ajax({
            type: "post",
            url: url,
            data: data,
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
                layer.closeAll('loading');
                layer.msg('文件下载失败，请缩小筛选条件后再次下载');
            }
        });
    });
    $("#ban_login").on('click', function () {  // 点击后本地数据库
        var acc_str = getChoose();
        console.log(acc_str);
        if (acc_str == '') {
            layer.msg('请选择对象!');
            return false;
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '封号',
            area: ['500px', '450px'],
            btn: ['确认', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content:'<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">目标渠道</span>' +
            '<div class="select_group_div" >'+
            '<select  id="g_other" class="selectpicker show-tick " multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
            '</div>'+
            '</div><br>' +
            '<div class="input-group"><span class="input-group-addon">理由</span><input id="reason" type="text" class="form-control jin-search-input" placeholder="限制理由(展示给玩家看的)"></div><br>' +
            '<div class="input-group"><span class="input-group-addon">默认理由</span><select id="reason1" class="form-control jin-search-input">' +
            '<option value="使用非法软件">使用非法软件</option>' +
            '<option value="异常账号">异常账号</option>' +
            '</select></div><br>' +
            '<div class="input-group"><span class="input-group-addon">是否移除排行榜以及禁产出一年</span><select id="del_power" class="form-control jin-search-input">' +
            '<option value="1">是</option>' +
            '<option value="0">否</option>' +
            '</select></div>' +
            '</div>',
            success: function (index) {
                obj11 = {id: '#g_other'};
                obj11.url = "?p=Admin&c=Operation&a=group&jinIf=943";
                groups(obj11);
            },
            yes: function (index1) {
                if ($("#g_other").val() == '') {
                    layer.msg('请选择渠道!');
                    return false;
                }
                user_name = '<?php echo $_smarty_tpl->tpl_vars['user_name']->value;?>
';
                reason = $("#reason").val();
                if(reason==''){
                    reason = $("#reason1").val();
                }
                $.ajax({
                    type: "POST",
                    url: location.href + "&jinIf=921",
                    data: {
                        content: acc_str,
                        gig: $("#g_other").val(),
                        del_power :$("#del_power").val(),
                        reason:reason,
                        user_name : user_name
                    },
                    dataType: "json",
                    beforeSend: function () {
                        layer.load(2, {
                            shade: [0.3, '#fff']//0.3透明度的白色背景
                        });
                    },
                    success: function (json) {
                        layer.closeAll('loading');
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    }
                });
            }
        });
    });

    // 全选
    $('#all_choose').click(function() {
        var check_on = $(this).is(':checked');
        if (check_on) {
            $('#content').find('input[type="checkbox"]').attr('checked', true);
        } else {
            $('#content').find('input[type="checkbox"]').attr('checked', false);
        }
    });
    // 获取选中的服务器
    function getChoose() {
        var mail_id = '';
        $('#content input[type="checkbox"]:checked').each(function(index, el) {
            if (index == 0) {
                mail_id = $(el).val();
            } else {
                mail_id += '\n' + $(el).val();
            }
        });
        return mail_id;
    }

    $('#content').on('click', 'tr', function() {
        var cb = $(this).find('td:first>input');
        if (! cb.is(':checked')) {
            cb.attr('checked', true);
            $(this).attr('style', 'background: #aba5618c');
        } else {
            cb.attr('checked', false);
            $(this).removeAttr('style', 'background: #aba5618c');
        }
    });


<?php echo '</script'; ?>
>
<?php }
}
