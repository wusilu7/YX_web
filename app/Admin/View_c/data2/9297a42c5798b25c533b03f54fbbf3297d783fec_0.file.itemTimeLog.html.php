<?php
/* Smarty version 3.1.30, created on 2024-08-23 14:18:19
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\data2\itemTimeLog.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66c829abdd4f13_20821070',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '9297a42c5798b25c533b03f54fbbf3297d783fec' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\data2\\itemTimeLog.html',
      1 => 1724210184,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_66c829abdd4f13_20821070 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>系统功能日志</span></div>
<div class="alert alert-info">
    <div id="group_server"></div>
</div>
<!--查询div-->
<hr/>
<div class="jin-search-div">
    <div>
        <label for="time_start">日期：</label>
        <input size="16" type="text" id="time_start" class="form-control jin-datetime"
               placeholder="开始日期">
        -
        <input size="16" type="text" id="time_end" class="form-control jin-datetime"
               placeholder="结束日期">
    </div>
    <div>
        <label for="player_id">筛选：</label>
        <input id="log_id" type="text" class="form-control jin-search-input" placeholder="日志ID">
        <input id="player_id" type="text" class="form-control jin-search-input" placeholder="角色ID">
        <input id="opt" type="text" class="form-control jin-search-input" placeholder="opt_type">
        <select  id="s_type1" class="form-control jin-search-input">
            <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['s_type']->value, 'type');
if ($_from !== null) {
foreach ($_from as $_smarty_tpl->tpl_vars['type']->value) {
?>
            <option value="<?php echo $_smarty_tpl->tpl_vars['type']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['type']->value['name'];?>
(<?php echo $_smarty_tpl->tpl_vars['type']->value['id'];?>
)</option>
            <?php
}
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl);
?>

        </select>
        <input id="s_type2" type="text" class="form-control jin-search-input" placeholder="system_type">
        <input size="16" type="checkbox" id="ischeck1" value="1">
        <label for="ischeck1" style="margin-left: 0px;">查询合服前</label>
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
        <a id="jin_excel" class="btn btn-danger">保存到Excel</a>
        <br>
        <input id="param0" type="text" class="form-control jin-search-input" placeholder="param0">
        <input id="param1" type="text" class="form-control jin-search-input" placeholder="param1">
        <input id="param2" type="text" class="form-control jin-search-input" placeholder="param2">
        <input id="param3" type="text" class="form-control jin-search-input" placeholder="param3">
        <input id="param4" type="text" class="form-control jin-search-input" placeholder="param4">
        <input id="param5" type="text" class="form-control jin-search-input" placeholder="param5">
        <input id="param6" type="text" class="form-control jin-search-input" placeholder="param6">
    </div>
</div>
<hr/>
<div class="table-responsive">
    <table class="table table-striped text-center">
        <thead>
        <tr>
            <th>日期</th>
            <th>日志ID</th>
            <th>角色ID</th>
            <th class="OptType">opt_type</th>
            <th>system_type</th>
            <th class="param0">param0</th>
            <th class="param1">param1</th>
            <th class="param2">param2</th>
            <th class="param3">param3</th>
            <th class="param4">param4</th>
            <th class="param5">param5</th>
            <th class="param6">param6</th>
            <th class="param7">param7</th>
            <th class="param8">param8</th>
        </tr>
        </thead>
        <tbody id="content"></tbody>
    </table>
</div>
<div id="page"></div>
<div class="jin-explain">
    <b>说明</b>：
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    var change_acc = function () {
        var c = '<div class="btn-group btn-group-sm">' +
            '<a data-type="get_pw"  class="btn btn-success" style="margin-bottom: 2px;">拷贝密码</a><br>' +
            '<a data-type="delete_fashion"  class="btn btn-primary">移除时装</a><br>' +
            '<a data-type="delete_sc"  class="btn btn-success">移除首充</a><br>' +
            '<a data-type="is_fee"  class="btn btn-primary">是否充值?</a><br>' +
            '</div>';
        return c;
    };
    var url = location.href + "&jinIf=912";
    var arr = ['log_time', 'log_id', 'char_guid', 'opt_type','system_type', 'param0', 'param1', 'param2', 'param3', 'param4', 'param5', 'param6','param7','param8'];
    var id = ["#content", "#page"];
    var data = {};
    $(function () {
        gsSelect('#group', '#server', '#platform');
        calendar('minute', '#time_start', '#time_end');
    });
    $('#content').on('click', 'a[data-type="get_pw"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(2).text();
        $.ajax({
            type: "POST",
            url: location.href + '&jinIf=920',
            data: {
                si: $("#server").val(),
                char_id: char_id
            },
            dataType: "json",
            success: function (json) {
                layer.alert(json[0]+'<br>'+json[1], {icon: 1}, function (index) {
                    layer.close(index);
                });
            }
        });
    }).on('click', 'a[data-type="delete_fashion"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(2).text();
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '周目回档',
            area: ['300px', '200px'],
            btn: ['确定', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">时装ID</span><input id="fashion_id" type="number" class="form-control" value="7"></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=922',
                    data: {
                        si: $("#server").val(),
                        fashion_id:$("#fashion_id").val(),
                        char_id: char_id
                    },
                    success: function (json) {
                        layer.close(index);
                        layer.alert('成功', {icon: 1}, function (index) {
                            layer.close(index);
                        });
                    }
                });
            }
        });
    }).on('click', 'a[data-type="delete_sc"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(2).text();
        layer.alert('是否重置首充??', {icon: 0, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + '&jinIf=923',
                data: {
                    si: $("#server").val(),
                    char_id: char_id
                },
                success: function (json) {
                    layer.alert('成功', {icon: 1}, function (index) {
                        layer.close(index);
                    });
                }
            });
        });
    }).on('click', 'a[data-type="is_fee"]', function () {
        var char_id = $(this).parents('tr').find('td').eq(2).text();
        $this = $(this);
        $.ajax({
            type: "POST",
            url: location.href + '&jinIf=924',
            data: {
                char_id: char_id,
                si : $("#server").val()
            },
            dataType: "json",
            success: function (json) {
                $this.html(json);
            }
        });
    });
    function getServiceresult() {
        var time_start = $('#time_start').val();
        var time_end   = $('#time_end').val();
        if($('#s_type2').val()!=''){
            s_type = $('#s_type2').val();
        }else{
            s_type = $('#s_type1').val();
        }

        data.time_start  = time_start;//查询开始时间;
        data.time_end    = time_end;//查询结束时间

        data.player_id   = $("#player_id").val();
        data.group       = $('#group').val();
        data.pi          = $('#platform').val();
        data.si          = $('#server').val();
        data.page          = 1;
        data.opt          = $('#opt').val();
        data.param0          = $('#param0').val();
        data.param1          = $('#param1').val();
        data.param2          = $('#param2').val();
        data.param3          = $('#param3').val();
        data.param4          = $('#param4').val();
        data.param5          = $('#param5').val();
        data.param6          = $('#param6').val();
        data.s_type          = s_type;
        data.log_id          = $('#log_id').val();
        data.before      = $('#ischeck1').is(':checked') ? $('#ischeck1').val() : '';
        switch (s_type){
            case '2':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('客户端关卡ID');$("#param0").attr('placeholder','客户端关卡ID');
                $(".param1").html('地图id');$("#param1").attr('placeholder','地图id');
                $(".param2").html('客户端房间id');$("#param2").attr('placeholder','客户端房间id');
                $(".param3").html('房间神类型');$("#param3").attr('placeholder','房间神类型');
                $(".param4").html('客户端关卡类型');$("#param4").attr('placeholder','客户端关卡类型');
                $(".param5").html('运行章节');$("#param5").attr('placeholder','运行章节');
                $(".param6").html('服务器运行关卡数');$("#param6").attr('placeholder','服务器运行关卡数');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '3':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('是否胜利');$("#param0").attr('placeholder','是否胜利');
                $(".param1").html('石币百分比');$("#param1").attr('placeholder','石币百分比');
                $(".param2").html('天赋百分比');$("#param2").attr('placeholder','天赋百分比');
                $(".param3").html('兽粮百分比');$("#param3").attr('placeholder','兽粮百分比');
                $(".param4").html('祭品百分比');$("#param4").attr('placeholder','祭品百分比');
                $(".param5").html('运行章节');$("#param5").attr('placeholder','运行章节');
                $(".param6").html('客户端关卡数');$("#param6").attr('placeholder','客户端关卡数');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '4':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('当前时间');$("#param0").attr('placeholder','当前时间');
                $(".param1").html('体力增加值');$("#param1").attr('placeholder','体力增加值');
                $(".param2").html('客户端时间');$("#param2").attr('placeholder','客户端时间');
                $(".param3").html('体力恢复时间');$("#param3").attr('placeholder','体力恢复时间');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '5':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('兑换id');$("#param0").attr('placeholder','兑换id');
                $(".param1").html('货币类型');$("#param1").attr('placeholder','货币类型');
                $(".param2").html('商品数量');$("#param2").attr('placeholder','商品数量');
                $(".param3").html('价格');$("#param3").attr('placeholder','价格');
                $(".param4").html('事务id');$("#param4").attr('placeholder','事务id');
                $(".param5").html('体力兑换次数');$("#param5").attr('placeholder','体力兑换次数');
                $(".param6").html('是否免费');$("#param6").attr('placeholder','是否免费');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '6':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('领取章节奖励ID');$("#param0").attr('placeholder','领取章节奖励ID');
                $(".param1").html('章节数');$("#param1").attr('placeholder','章节数');
                $(".param2").html('事务id');$("#param2").attr('placeholder','事务id');
                $(".param3").html('周目');$("#param3").attr('placeholder','周目');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '7':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('通关章节');$("#param0").attr('placeholder','通关章节');
                $(".param1").html('上一次章节');$("#param1").attr('placeholder','上一次章节');
                $(".param2").html('客户端关卡类型');$("#param2").attr('placeholder','客户端关卡类型');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '9':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('宠物id');$("#param0").attr('placeholder','宠物id');
                $(".param1").html('宠物新等级');$("#param1").attr('placeholder','宠物新等级');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '10':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('宠物id');$("#param0").attr('placeholder','宠物id');
                $(".param1").html('宠物新等级');$("#param1").attr('placeholder','宠物新等级');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '11':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('地图ID');$("#param0").attr('placeholder','地图ID');
                $(".param1").html('param1');$("#param1").attr('placeholder','param1');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '12':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('宠物id');$("#param0").attr('placeholder','宠物id');
                $(".param1").html('param1');$("#param1").attr('placeholder','param1');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '13':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('装备槽');$("#param0").attr('placeholder','装备槽');
                $(".param1").html('新能力级');$("#param1").attr('placeholder','新能力级');
                $(".param2").html('当前经验');$("#param2").attr('placeholder','当前经验');
                $(".param3").html('事务id');$("#param3").attr('placeholder','事务id');
                $(".param4").html('请求序号');$("#param4").attr('placeholder','请求序号');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '14':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('装备槽');$("#param0").attr('placeholder','装备槽');
                $(".param1").html('新等级');$("#param1").attr('placeholder','新等级');
                $(".param2").html('事务id');$("#param2").attr('placeholder','事务id');
                $(".param3").html('请求升级序号');$("#param3").attr('placeholder','请求升级序号');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '15':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('增加天赋ID');$("#param0").attr('placeholder','增加天赋ID');
                $(".param1").html('天赋CID');$("#param1").attr('placeholder','天赋CID');
                $(".param2").html('天赋点');$("#param2").attr('placeholder','天赋点');
                $(".param3").html('已使用天赋点');$("#param3").attr('placeholder','已使用天赋点');
                $(".param4").html('总天赋点');$("#param4").attr('placeholder','总天赋点');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '16':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('章节');$("#param0").attr('placeholder','章节');
                $(".param1").html('关卡');$("#param1").attr('placeholder','关卡');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '20':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('建筑类型');$("#param0").attr('placeholder','建筑类型');
                $(".param1").html('建筑等级');$("#param1").attr('placeholder','建筑等级');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '21':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('建筑类型');$("#param0").attr('placeholder','建筑类型');
                $(".param1").html('建筑升级时间');$("#param1").attr('placeholder','建筑升级时间');
                $(".param2").html('建筑等级');$("#param2").attr('placeholder','建筑等级');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '23':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('当前时间');$("#param0").attr('placeholder','当前时间');
                $(".param1").html('增加值');$("#param1").attr('placeholder','增加值');
                $(".param2").html('更新时间');$("#param2").attr('placeholder','更新时间');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '24':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('当前时间');$("#param0").attr('placeholder','当前时间');
                $(".param1").html('增加值');$("#param1").attr('placeholder','增加值');
                $(".param2").html('更新时间');$("#param2").attr('placeholder','更新时间');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '25':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('当前时间');$("#param0").attr('placeholder','当前时间');
                $(".param1").html('增加值');$("#param1").attr('placeholder','增加值');
                $(".param2").html('更新时间');$("#param2").attr('placeholder','更新时间');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '29':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('持续时间');$("#param0").attr('placeholder','持续时间');
                $(".param1").html('物品id');$("#param1").attr('placeholder','物品id');
                $(".param2").html('物品数量');$("#param2").attr('placeholder','物品数量');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '30':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('建筑类型');$("#param0").attr('placeholder','建筑类型');
                $(".param1").html('物品id');$("#param1").attr('placeholder','物品id');
                $(".param2").html('物品数量');$("#param2").attr('placeholder','物品数量');
                $(".param3").html('周目');$("#param3").attr('placeholder','周目');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '33':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('宠物id');$("#param0").attr('placeholder','宠物id');
                $(".param1").html('宠物等级');$("#param1").attr('placeholder','宠物等级');
                $(".param2").html('宠物能力等级');$("#param2").attr('placeholder','宠物能力等级');
                $(".param3").html('概率');$("#param3").attr('概率');
                $(".param4").html('随机概率');$("#param4").attr('placeholder','随机概率');
                $(".param5").html('能力突破1');$("#param5").attr('placeholder','能力突破1');
                $(".param6").html('能力突破2');$("#param6").attr('placeholder','能力突破2');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '34':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('宠物id');$("#param0").attr('placeholder','宠物id');
                $(".param1").html('上阵槽');$("#param1").attr('placeholder','上阵槽');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '35':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('建筑类型');$("#param0").attr('placeholder','建筑类型');
                $(".param1").html('升级时间');$("#param1").attr('placeholder','升级时间');
                $(".param2").html('新等级');$("#param2").attr('placeholder','新等级');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '36':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('当前时间');$("#param0").attr('placeholder','当前时间');
                $(".param1").html('增加值');$("#param1").attr('placeholder','增加值');
                $(".param2").html('更新时间');$("#param2").attr('placeholder','更新时间');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '37':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('当前时间');$("#param0").attr('placeholder','当前时间');
                $(".param1").html('增加值');$("#param1").attr('placeholder','增加值');
                $(".param2").html('更新时间');$("#param2").attr('placeholder','更新时间');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '41':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('建筑类型');$("#param0").attr('placeholder','建筑类型');
                $(".param1").html('锻造槽');$("#param1").attr('placeholder','锻造槽');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '42':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('门上神:上1');$("#param0").attr('placeholder','门上神:上1');
                $(".param1").html('门上神:左2');$("#param1").attr('placeholder','门上神:左2');
                $(".param2").html('门上神:右3');$("#param2").attr('placeholder','门上神:右3');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '43':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('选择神');$("#param0").attr('placeholder','选择神');
                $(".param1").html('地图id');$("#param1").attr('placeholder','地图id');
                $(".param2").html('章节id');$("#param2").attr('placeholder','章节id');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '44':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('param0');$("#param0").attr('placeholder','param0');
                $(".param1").html('祭品值');$("#param1").attr('placeholder','祭品值');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '45':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('章节');$("#param0").attr('placeholder','章节');
                $(".param1").html('商品1');$("#param1").attr('placeholder','商品1');
                $(".param2").html('商品2');$("#param2").attr('placeholder','商品2');
                $(".param3").html('商品3');$("#param3").attr('placeholder','商品3');
                $(".param4").html('商品4');$("#param4").attr('placeholder','商品4');
                $(".param5").html('商品5');$("#param5").attr('placeholder','商品5');
                $(".param6").html('商品6');$("#param6").attr('placeholder','商品6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '46':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('服务器关卡数');$("#param0").attr('placeholder','服务器关卡数');
                $(".param1").html('商品id');$("#param1").attr('placeholder','商品id');
                $(".param2").html('标记是否购买');$("#param2").attr('placeholder','标记是否购买');
                $(".param3").html('服务器关卡类型');$("#param3").attr('placeholder','服务器关卡类型');
                $(".param4").html('物品guid');$("#param4").attr('placeholder','物品guid');
                $(".param5").html('商品5');$("#param5").attr('placeholder','商品5');
                $(".param6").html('商品6');$("#param6").attr('placeholder','商品6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '50':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('当前时间');$("#param0").attr('placeholder','当前时间');
                $(".param1").html('增加值');$("#param1").attr('placeholder','增加值');
                $(".param2").html('更新时间');$("#param2").attr('placeholder','更新时间');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '51':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('领取下标');$("#param0").attr('placeholder','领取下标');
                $(".param1").html('活动第几轮');$("#param1").attr('placeholder','活动第几轮');
                $(".param2").html('事务id');$("#param2").attr('placeholder','事务id');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '52':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('客户端章节id');$("#param0").attr('placeholder','客户端章节id');
                $(".param1").html('客户端房间id');$("#param1").attr('placeholder','客户端房间id');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('服务器章节');$("#param3").attr('placeholder','服务器章节');
                $(".param4").html('服务器关卡数');$("#param4").attr('placeholder','服务器关卡数');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '53':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('神之祝福id');$("#param0").attr('placeholder','神之祝福id');
                $(".param1").html('祝福价格');$("#param1").attr('placeholder','祝福价格');
                $(".param2").html('折扣');$("#param2").attr('placeholder','折扣');
                $(".param3").html('服务器章节');$("#param3").attr('placeholder','服务器章节');
                $(".param4").html('服务器关卡数');$("#param4").attr('placeholder','服务器关卡数');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '54':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('是否可以获取祭品');$("#param0").attr('placeholder','是否可以获取祭品');
                $(".param1").html('param1');$("#param1").attr('placeholder','param1');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('服务器章节');$("#param3").attr('placeholder','服务器章节');
                $(".param4").html('服务器关卡数');$("#param4").attr('placeholder','服务器关卡数');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '55':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('周目');$("#param0").attr('placeholder','周目');
                $(".param1").html('秘境/关卡类型');$("#param1").attr('placeholder','秘境/关卡类型');
                $(".param2").html('神罚值/无');$("#param2").attr('placeholder','神罚值/无');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '56':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('章节');$("#param0").attr('placeholder','章节');
                $(".param1").html('客户端关卡类型');$("#param1").attr('placeholder','客户端关卡类型');
                $(".param2").html('服务器关卡数');$("#param2").attr('placeholder','服务器关卡数');
                $(".param3").html('最大关卡数');$("#param3").attr('placeholder','最大关卡数');
                $(".param4").html('上报客户端关卡数');$("#param4").attr('placeholder','上报客户端关卡数');
                $(".param5").html('周目');$("#param5").attr('placeholder','周目');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '57':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('周目');$("#param0").attr('placeholder','周目');
                $(".param1").html('服务器章节');$("#param1").attr('placeholder','服务器章节');
                $(".param2").html('服务器关卡数');$("#param2").attr('placeholder','服务器关卡数');
                $(".param3").html('上一次关卡数');$("#param3").attr('placeholder','上一次关卡数');
                $(".param4").html('关卡小节时间');$("#param4").attr('placeholder','关卡小节时间');
                $(".param5").html('总持续时间');$("#param5").attr('placeholder','总持续时间');
                $(".param6").html('是否胜利');$("#param6").attr('placeholder','是否胜利');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '58':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('周目');$("#param0").attr('placeholder','周目');
                $(".param1").html('服务器章节');$("#param1").attr('placeholder','服务器章节');
                $(".param2").html('服务器关卡数');$("#param2").attr('placeholder','服务器关卡数');
                $(".param3").html('小关卡通关时长');$("#param3").attr('placeholder','小关卡通关时长');
                $(".param4").html('是否胜利');$("#param4").attr('placeholder','是否胜利');
                $(".param5").html('通关总时间');$("#param5").attr('placeholder','通关总时间');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '59':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('随机池id');$("#param0").attr('placeholder','随机池id');
                $(".param1").html('数据类型');$("#param1").attr('placeholder','数据类型');
                $(".param2").html('数据id');$("#param2").attr('placeholder','数据id');
                $(".param3").html('数值值');$("#param3").attr('placeholder','数值值');
                $(".param4").html('地图id');$("#param4").attr('placeholder','地图id');
                $(".param5").html('场景roomid');$("#param5").attr('placeholder','场景roomid');
                $(".param6").html('周目');$("#param6").attr('placeholder','周目');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '61':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('group_id');$("#param0").attr('placeholder','group_id');
                $(".param1").html('cid');$("#param1").attr('placeholder','cid');
                $(".param2").html('天赋点');$("#param2").attr('placeholder','天赋点');
                $(".param3").html('已使用天赋点');$("#param3").attr('placeholder','已使用天赋点');
                $(".param4").html('总天赋点');$("#param4").attr('placeholder','总天赋点');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '62':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('物品数量');$("#param0").attr('placeholder','物品数量');
                $(".param1").html('宝箱参数');$("#param1").attr('placeholder','宝箱参数');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '65':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('背包类型');$("#param0").attr('placeholder','背包类型');
                $(".param1").html('背包索引');$("#param1").attr('placeholder','背包索引');
                $(".param2").html('当前经验');$("#param2").attr('placeholder','当前经验');
                $(".param3").html('当前星数');$("#param3").attr('placeholder','当前星数');
                $(".param4").html('需要扣的经验');$("#param4").attr('placeholder','需要扣的经验');
                $(".param5").html('历史星数');$("#param5").attr('placeholder','历史星数');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '66':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('背包类型');$("#param0").attr('placeholder','背包类型');
                $(".param1").html('背包索引');$("#param1").attr('placeholder','背包索引');
                $(".param2").html('物品id');$("#param2").attr('placeholder','物品id');
                $(".param3").html('物品guid');$("#param3").attr('placeholder','物品guid');
                $(".param4").html('新物品id');$("#param4").attr('placeholder','新物品id');
                $(".param5").html('历史孔数');$("#param5").attr('placeholder','历史孔数');
                $(".param6").html('新孔数');$("#param6").attr('placeholder','新孔数');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '68':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('神罚技能id');$("#param0").attr('placeholder','神罚技能id');
                $(".param1").html('是否增加/减少');$("#param1").attr('placeholder','是否增加/减少');
                $(".param2").html('神罚id');$("#param2").attr('placeholder','神罚id');
                $(".param3").html('当前神罚值');$("#param3").attr('placeholder','当前神罚值');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '69':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('事务id');$("#param0").attr('placeholder','事务id');
                $(".param1").html('奖励宝箱id');$("#param1").attr('placeholder','奖励宝箱id');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '71':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('时装id');$("#param0").attr('placeholder','时装id');
                $(".param1").html('事务id');$("#param1").attr('placeholder','事务id');
                $(".param2").html('支付类型');$("#param2").attr('placeholder','支付类型');
                $(".param3").html('是否花钱购买');$("#param3").attr('placeholder','是否花钱购买');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '72':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('时装id');$("#param0").attr('placeholder','时装id');
                $(".param1").html('是否使用');$("#param1").attr('placeholder','是否使用');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '73':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('时装id');$("#param0").attr('placeholder','时装id');
                $(".param1").html('时装等级');$("#param1").attr('placeholder','时装等级');
                $(".param2").html('事务id');$("#param2").attr('placeholder','事务id');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '74':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('神之祝福id');$("#param0").attr('placeholder','神之祝福id');
                $(".param1").html('祝福价格');$("#param1").attr('placeholder','祝福价格');
                $(".param2").html('折扣');$("#param2").attr('placeholder折扣');
                $(".param3").html('服务器章节');$("#param3").attr('placeholder','服务器章节');
                $(".param4").html('服务器关卡数');$("#param4").attr('placeholder','服务器关卡数');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '75':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('建筑类型');$("#param0").attr('placeholder','建筑类型');
                $(".param1").html('建筑等级');$("#param1").attr('placeholder','建筑等级');
                $(".param2").html('物品id');$("#param2").attr('placeholder','物品id');
                $(".param3").html('物品数量');$("#param3").attr('placeholder','物品数量');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '76':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('是否免费');$("#param0").attr('placeholder','是否免费');
                $(".param1").html('等级');$("#param1").attr('placeholder','等级');
                $(".param2").html('事务id');$("#param2").attr('placeholder','事务id');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '77':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('当前经验');$("#param0").attr('placeholder','当前经验');
                $(".param1").html('最大经验');$("#param1").attr('placeholder','最大经验');
                $(".param2").html('等级');$("#param2").attr('placeholder','等级');
                $(".param3").html('通行证等级');$("#param3").attr('placeholder','通行证等级');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '78':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('当前经验');$("#param0").attr('placeholder','当前经验');
                $(".param1").html('最大经验');$("#param1").attr('placeholder','最大经验');
                $(".param2").html('等级');$("#param2").attr('placeholder','等级');
                $(".param3").html('通行证等级');$("#param3").attr('placeholder','通行证等级');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '79':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('购买时间');$("#param0").attr('placeholder','购买时间');
                $(".param1").html('购买次数');$("#param1").attr('placeholder','购买次数');
                $(".param2").html('充值id');$("#param2").attr('placeholder','充值id');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '80':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('服务器关卡数');$("#param0").attr('placeholder','服务器关卡数');
                $(".param1").html('免费复活次数');$("#param1").attr('placeholder','免费复活次数');
                $(".param2").html('是否免费');$("#param2").attr('placeholder','是否免费');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '81':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('随机神秘商店次数');$("#param0").attr('placeholder','随机神秘商店次数');
                $(".param1").html('免费次数');$("#param1").attr('placeholder','免费次数');
                $(".param2").html('额外次数');$("#param2").attr('placeholder','额外次数');
                $(".param3").html('商店类型');$("#param3").attr('placeholder','商店类型');
                $(".param4").html('事务id');$("#param4").attr('placeholder','事务id');
                $(".param5").html('付费扣费值');$("#param5").attr('placeholder','付费扣费值');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '82':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('狩猎经验');$("#param0").attr('placeholder','狩猎经验');
                $(".param1").html('计算次数');$("#param1").attr('placeholder','计算次数');
                $(".param2").html('事务id');$("#param2").attr('placeholder','事务id');
                $(".param3").html('最大周目');$("#param3").attr('placeholder','最大周目');
                $(".param4").html('最大章节');$("#param4").attr('placeholder','最大章节');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '83':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('狩猎石币');$("#param0").attr('placeholder','狩猎石币');
                $(".param1").html('计算次数');$("#param1").attr('placeholder','计算次数');
                $(".param2").html('事务id');$("#param2").attr('placeholder','事务id');
                $(".param3").html('最大周目');$("#param3").attr('placeholder','最大周目');
                $(".param4").html('最大章节');$("#param4").attr('placeholder','最大章节');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '84':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('目标等级');$("#param0").attr('placeholder','目标等级');
                $(".param1").html('需要珍珠数');$("#param1").attr('placeholder','需要珍珠数');
                $(".param2").html('事务id');$("#param2").attr('placeholder','事务id');
                $(".param3").html('经验差值');$("#param3").attr('placeholder','经验差值');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '85':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('物品guid');$("#param0").attr('placeholder','物品guid');
                $(".param1").html('物品id');$("#param1").attr('placeholder','物品id');
                $(".param2").html('是否免费');$("#param2").attr('placeholder','是否免费');
                $(".param3").html('重置后的槽数量');$("#param3").attr('placeholder','重置后的槽数量');
                $(".param4").html('重置的随机池');$("#param4").attr('placeholder','重置的随机池');
                $(".param5").html('事务id');$("#param5").attr('placeholder','事务id');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '86':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('服务器关卡数');$("#param0").attr('placeholder','服务器关卡数');
                $(".param1").html('攻击');$("#param1").attr('placeholder','攻击');
                $(".param2").html('当前血量');$("#param2").attr('placeholder','当前血量');
                $(".param3").html('最大血量');$("#param3").attr('placeholder','最大血量');
                $(".param4").html('速度');$("#param4").attr('placeholder','速度');
                $(".param5").html('客户端时间');$("#param5").attr('placeholder','客户端时间');
                $(".param6").html('时差');$("#param6").attr('placeholder','时差');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '89':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('重置类型');$("#param0").attr('placeholder','重置类型');
                $(".param1").html('礼包id');$("#param1").attr('placeholder','礼包id');
                $(".param2").html('支付类型');$("#param2").attr('placeholder','支付类型');
                $(".param3").html('校验结果');$("#param3").attr('placeholder','校验结果');
                $(".param4").html('是否观广告');$("#param4").attr('placeholder','是否观广告');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '90':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('服务器关卡数');$("#param0").attr('placeholder','服务器关卡数');
                $(".param1").html('攻击');$("#param1").attr('placeholder','攻击');
                $(".param2").html('当前血量');$("#param2").attr('placeholder','当前血量');
                $(".param3").html('最大血量');$("#param3").attr('placeholder','最大血量');
                $(".param4").html('是否处罚');$("#param4").attr('placeholder','是否处罚');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '91':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('服务器关卡数');$("#param0").attr('placeholder','服务器关卡数');
                $(".param1").html('怪物id');$("#param1").attr('placeholder','怪物id');
                $(".param2").html('血量');$("#param2").attr('placeholder','血量');
                $(".param3").html('最大血量');$("#param3").attr('placeholder','最大血量');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '92':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('宠物id');$("#param0").attr('placeholder','宠物id');
                $(".param1").html('事务id');$("#param1").attr('placeholder','事务id');
                $(".param2").html('购买类型');$("#param2").attr('placeholder','购买类型');
                $(".param3").html('是否购买');$("#param3").attr('placeholder','是否购买');
                $(".param4").html('物品数');$("#param4").attr('placeholder','物品数');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '93':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('服务器关卡数');$("#param0").attr('placeholder','服务器关卡数');
                $(".param1").html('暴击率');$("#param1").attr('placeholder','暴击率');
                $(".param2").html('攻击速度pct');$("#param2").attr('placeholder','攻击速度pct');
                $(".param3").html('连续射击');$("#param3").attr('placeholder','连续射击');
                $(".param4").html('param5');$("#param4").attr('placeholder','param4');
                $(".param5").html('param6');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '94':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('服务器关卡数');$("#param0").attr('placeholder','服务器关卡数');
                $(".param1").html('地图id');$("#param1").attr('placeholder','地图id');
                $(".param2").html('场景RoomID');$("#param2").attr('placeholder','场景RoomID');
                $(".param3").html('服务器关卡数');$("#param3").attr('placeholder','服务器关卡数');
                $(".param4").html('服务器关卡数');$("#param4").attr('placeholder','服务器关卡数');
                $(".param5").html('房间id');$("#param5").attr('placeholder','房间id');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '99':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('周目');$("#param0").attr('placeholder','周目');
                $(".param1").html('运行章节');$("#param1").attr('placeholder','运行章节');
                $(".param2").html('服务器关卡数');$("#param2").attr('placeholder','服务器关卡数');
                $(".param3").html('客户端上报小节数');$("#param3").attr('placeholder','客户端上报小节数');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '100':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('服务器关卡数');$("#param0").attr('placeholder','服务器关卡数');
                $(".param1").html('是否惩罚');$("#param1").attr('placeholder','是否惩罚');
                $(".param2").html('当前层级');$("#param2").attr('placeholder','当前层级');
                $(".param3").html('目标层级');$("#param3").attr('placeholder','目标层级');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '101':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('服务器关卡数');$("#param0").attr('placeholder','服务器关卡数');
                $(".param1").html('是否惩罚');$("#param1").attr('placeholder','是否惩罚');
                $(".param2").html('没有技能给祭品');$("#param2").attr('placeholder','没有技能给祭品');
                $(".param3").html('给与祭品数量');$("#param3").attr('给与祭品数量');
                $(".param4").html('是否允许获取祭品');$("#param4").attr('placeholder','是否允许获取祭品');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '102':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('上一次突破等级');$("#param0").attr('placeholder','上一次突破等级');
                $(".param1").html('新突破等级');$("#param1").attr('placeholder','新突破等级');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '103':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('宠物等级');$("#param0").attr('placeholder','宠物等级');
                $(".param1").html('宠物突破等级');$("#param1").attr('placeholder','宠物突破等级');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '105':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('获取值');$("#param0").attr('placeholder','获取值');
                $(".param1").html('持续时间');$("#param1").attr('placeholder','持续时间');
                $(".param2").html('更新时间');$("#param2").attr('placeholder','更新时间');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '106':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('获取值');$("#param0").attr('placeholder','获取值');
                $(".param1").html('持续时间');$("#param1").attr('placeholder','持续时间');
                $(".param2").html('更新时间');$("#param2").attr('placeholder','更新时间');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '108':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('获取值');$("#param0").attr('placeholder','获取值');
                $(".param1").html('持续时间');$("#param1").attr('placeholder','持续时间');
                $(".param2").html('更新时间');$("#param2").attr('placeholder','更新时间');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '109':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('获取值');$("#param0").attr('placeholder','获取值');
                $(".param1").html('持续时间');$("#param1").attr('placeholder','持续时间');
                $(".param2").html('更新时间');$("#param2").attr('placeholder','更新时间');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '110':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('获取值');$("#param0").attr('placeholder','获取值');
                $(".param1").html('持续时间');$("#param1").attr('placeholder','持续时间');
                $(".param2").html('更新时间');$("#param2").attr('placeholder','更新时间');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '111':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('随机类型');$("#param0").attr('placeholder','随机类型');
                $(".param1").html('随机id');$("#param1").attr('placeholder','随机id');
                $(".param2").html('随机值');$("#param2").attr('placeholder','随机值');
                $(".param3").html('掉落池');$("#param3").attr('placeholder','掉落池');
                $(".param4").html('掉落总数量');$("#param4").attr('placeholder','掉落总数量');
                $(".param5").html('事务ID');$("#param5").attr('placeholder','事务ID');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '112':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('第几天');$("#param0").attr('placeholder','第几天');
                $(".param1").html('实际天数');$("#param1").attr('placeholder','实际天数');
                $(".param2").html('完成时间');$("#param2").attr('placeholder','完成时间');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '113':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('领取下标');$("#param0").attr('placeholder','领取下标');
                $(".param1").html('是否第一期');$("#param1").attr('placeholder','是否第一期');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '114':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('第几期');$("#param0").attr('placeholder','第几期');
                $(".param1").html('开启时间');$("#param1").attr('placeholder','开启时间');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '115':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('第几期');$("#param0").attr('placeholder','第几期');
                $(".param1").html('开启时间');$("#param1").attr('placeholder','开启时间');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '116':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('领取天数');$("#param0").attr('placeholder','领取天数');
                $(".param1").html('第几期');$("#param1").attr('placeholder','第几期');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '117':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('领取天数');$("#param0").attr('placeholder','领取天数');
                $(".param1").html('第几期');$("#param1").attr('placeholder','第几期');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '118':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('最大周目');$("#param0").attr('placeholder','最大周目');
                $(".param1").html('事务id');$("#param1").attr('placeholder','事务id');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '120':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('Video表格id');$("#param0").attr('placeholder','Video表格id');
                $(".param1").html('客户端参数');$("#param1").attr('placeholder','客户端参数');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '121':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('Video表格id');$("#param0").attr('placeholder','Video表格id');
                $(".param1").html('客户端参数');$("#param1").attr('placeholder','客户端参数');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '122':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('服务器关卡数');$("#param0").attr('placeholder','服务器关卡数');
                $(".param1").html('地图id');$("#param1").attr('placeholder','地图id');
                $(".param2").html('场景RoomID');$("#param2").attr('placeholder','场景RoomID');
                $(".param3").html('服务器关卡数');$("#param3").attr('placeholder','服务器关卡数');
                $(".param4").html('是否惩罚');$("#param4").attr('placeholder','是否惩罚');
                $(".param5").html('房间ID');$("#param5").attr('placeholder','房间ID');
                $(".param6").html('最大关卡数');$("#param6").attr('placeholder','最大关卡数');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '123':
                $(".OptType").html('服务器关卡类型');$("#opt").attr('placeholder','服务器关卡类型');
                $(".param0").html('服务器关卡数');$("#param0").attr('placeholder','服务器关卡数');
                $(".param1").html('地图id');$("#param1").attr('placeholder','地图id');
                $(".param2").html('场景RoomID');$("#param2").attr('placeholder','场景RoomID');
                $(".param3").html('服务器关卡数');$("#param3").attr('placeholder','服务器关卡数');
                $(".param4").html('是否惩罚');$("#param4").attr('placeholder','是否惩罚');
                $(".param5").html('房间ID');$("#param5").attr('placeholder','房间ID');
                $(".param6").html('最大关卡数');$("#param6").attr('placeholder','最大关卡数');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '124':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('等级');$("#param0").attr('placeholder','等级');
                $(".param1").html('突破等级');$("#param1").attr('placeholder','突破等级');
                $(".param2").html('事务ID');$("#param2").attr('placeholder','事务ID');
                $(".param3").html('客户端请求等级');$("#param3").attr('placeholder','客户端请求等级');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '125':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('上一次突破等级');$("#param0").attr('placeholder','上一次突破等级');
                $(".param1").html('等级');$("#param1").attr('placeholder','等级');
                $(".param2").html('突破等级');$("#param2").attr('placeholder','突破等级');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            case '126':
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('头像类型');$("#param0").attr('placeholder','头像类型');
                $(".param1").html('头像类型');$("#param1").attr('placeholder','头像类型');
                $(".param2").html('事务ID');$("#param2").attr('placeholder','事务ID');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
            default:
                $(".OptType").html('OptType');$("#opt").attr('placeholder','OptType');
                $(".param0").html('param0');$("#param0").attr('placeholder','param0');
                $(".param1").html('param1');$("#param1").attr('placeholder','param1');
                $(".param2").html('param2');$("#param2").attr('placeholder','param2');
                $(".param3").html('param3');$("#param3").attr('placeholder','param3');
                $(".param4").html('param4');$("#param4").attr('placeholder','param4');
                $(".param5").html('param5');$("#param5").attr('placeholder','param5');
                $(".param6").html('param6');$("#param6").attr('placeholder','param6');
                $(".param7").html('param7');$("#param7").attr('placeholder','param7');
                $(".param8").html('param8');$("#param8").attr('placeholder','param8');
                break;
        }

        tableList(url, data, id, arr);
    }
    $("#jin_excel").on('click', function () {
        var time_start = $('#time_start').val();
        var time_end   = $('#time_end').val();
        if($('#s_type2').val()!=''){
            s_type = $('#s_type2').val();
        }else{
            s_type = $('#s_type1').val();
        }

        data.time_start  = time_start;//查询开始时间;
        data.time_end    = time_end;//查询结束时间

        data.player_id   = $("#player_id").val();
        data.group       = $('#group').val();
        data.pi          = $('#platform').val();
        data.si          = $('#server').val();
        data.page = 'excel';
        data.opt          = $('#opt').val();
        data.param0          = $('#param0').val();
        data.param1          = $('#param1').val();
        data.param2          = $('#param2').val();
        data.param3          = $('#param3').val();
        data.param4          = $('#param4').val();
        data.param5          = $('#param5').val();
        data.param6          = $('#param6').val();
        data.s_type          = s_type;
        data.log_id          = $('#log_id').val();
        $.ajax({
            type: "post",
            url: location.href + '&jinIf=951',
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
    // 普通查询
    $("#jin_search").on('click', function () {
        data.check_type = 912;
        getServiceresult();
    });
<?php echo '</script'; ?>
>
<?php }
}
