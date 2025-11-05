<?php
/* Smarty version 3.1.30, created on 2024-04-24 18:07:41
  from "D:\pro\WebSiteYiXing\app\Admin\View\data1\selectTop.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6628d9ed3e3299_49365882',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6c14846db897e605af43dbb623dd88b018e7fede' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\data1\\selectTop.html',
      1 => 1704262932,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_6628d9ed3e3299_49365882 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->

<div class="jin-content-title"><span>充值TOP</span></div>
<div class="alert alert-info">
    <div id="group_server"></div>
</div>
<hr/>
<div class="jin-search-div">
    <label for="time_start">日期：</label>
    <input size="16" type="text" id="time_start" class="form-control jin-datetime" placeholder="开始日期">
    -
    <input size="16" type="text" id="time_end" class="form-control jin-datetime" placeholder="结束日期">
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    <a id="server_summary" class="btn btn-success">服务器汇总</a>
    <!--<a id="group_summary" class="btn btn-success">渠道汇总</a>-->
    <a id="jin_excel" class="btn btn-danger">保存到Excel</a>
    <a id="role_search" class="btn btn-success" style="float: right;"><span class="glyphicon glyphicon-search"></span></a>
    <input type="text" id="role_value" class="form-control" placeholder="角色ID查询" style="width: 20%; float: right;">
</div>
<hr/>

<div class="tab-content">
    <!--累计-->
    <div class="tab-pane active" id="top_total">
        <div class="table-responsive">
            <table class="table table-hover text-center">
                <thead>
                <tr  id="thead">
                    <th>排名</th>
                    <th>服务器</th>
                    <th>渠道</th>
                    <th>账号</th>
                    <th>角色ID</th>
                    <th>角色名</th>
                    <th>等级</th>
                    <th>累计充值金额</th>
                    <th>停充天数</th>
                    <th>未登录天数</th>
                    <th>最后充值时间</th>
                    <th>最后登录时间</th>
                </tr>
                </thead>
                <tbody id="content_total"></tbody>
            </table>
        </div>
    </div>
    <div id="page"></div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    gsSelect('#group', '#server', '#platform');
    calendar('month', '#time_start', '#time_end');
    var url = location + "&jinIf=912";
    var arr = ['rank', 'si','gi','account', 'char', 'char_name','level', 'fee','stop_pay_days', 'stop_login_days', 'last_pay_time', 'logout_time'];
    var id = ["#content_total", "#page"];
    var data = {};
    //请求数据
    function getTop() {
        if(eval('<?php echo $_smarty_tpl->tpl_vars['wbGroup']->value;?>
').indexOf($("#group").val())>=0){ //当前所选渠道是英文渠道，金额显示外币
            arr[7] = 'fee1';
            $("#thead").find('th').eq(7).html("累计充值金额");
        }else {
            arr[7] = 'fee';
            $("#thead").find('th').eq(7).html("累计充值金额");
        }
        data.page = 1;
        data.group      = $("#group").val();
        data.pi         = $('#platform').val();
        data.si         = $("#server").val();//放在里面，不然si的值会不正确
        data.time_start = $('#time_start').val();//查询开始时间
        data.time_end   = $('#time_end').val();//查询结束时间
        data.role_value = $('#role_value').val();//角色查询
        tableList(url, data, id, arr);
    }
    // 普通查询
    $("#jin_search").on('click', function () {
        data.check_type = 912;
        getTop();
    });
    // 服务器汇总
    $("#server_summary").on('click', function () {
        data.check_type = 998;
        getTop();
    });
    // 渠道汇总
    $("#group_summary").on('click', function () {
        data.check_type = 999;
        giCollect(getTop);
    });
    //切换页面
    $('#today').on('click', function () {
        data.type = 1;
        id = ["#content_today", "#page"];
        getTop();
    })
    $('#total').on('click', function () {
        data.type = 2;
        id = ["#content_total", "#page"];
        getTop();
    })
    $('#role_search').on('click', function () {
        data.check_type = 912;
        getTop();
    })

    // 导出Excel
    $("#jin_excel").on('click', function () {
        data.page = 'excel';
        data.time_start = $('#time_start').val();//查询开始时间;
        data.time_end = $('#time_end').val();//查询结束时间
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
<?php echo '</script'; ?>
>
<?php }
}
