//菜单收缩开始
$(document).ready(function () {
    var is_show = false;
    $(".jin-sidebar-c").each(function() {
        if (!$(this).is(':hidden')) {
            is_show = true;
            return true;
        }
    });
    if (!is_show) {
        $(".jin-sidebar-c").eq(0).slideDown(200).siblings(".jin-sidebar-c").slideUp(200);
    }

    $(".jin-sidebar-t").click(function () {
        $(this).next(".jin-sidebar-c").slideToggle(200).siblings(".jin-sidebar-c").slideUp(200);
    })
});

$("#group_server").html(
    '<label for="group">渠道：</label>' +
    '<select id="group">' +
    '<option value=""></option>' +
    '</select>' +
    '<label for="platform">平台：</label>' +
    '<select id="platform">' +
    '<option value=""></option>' +
    '</select>' +
    '<label for="server">服务器：</label>' +
    '<select id="server">' +
    '<option value=""></option>' +
    '</select>'
);

$("#group_server_2").html(
    '<label for="group">渠道：</label>' +
    '<select id="group">' +
    '<option value=""></option>' +
    '</select>' +
    '<label for="server">服务器：</label>' +
    '<select id="server">' +
    '<option value=""></option>' +
    '</select>'
);

$("#group_only").html(
    '<label for="group">渠道：</label>' +
    '<select id="group">' +
    '<option value=""></option>' +
    '</select>'
);

$("#group_server_3").html(
    '<label for="group">渠道：</label>' +
    '<select id="group">' +
    '<option value=""></option>' +
    '</select>' +
    '<label for="platform">平台：</label>' +
    '<select id="platform">' +
    '<option value=""></option>' +
    '</select>' 
);

$("#group_server_4").html(
    '<label for="group">渠道：</label>' +
    '<select id="group">' +
    '<option value=""></option>' +
    '</select>' +
    '<label for="server">服务器：</label>' +
    '<select id="server">' +
    '<option value=""></option>' +
    '</select>' +
    '<label for="server2">服务器：</label>' +
    '<select id="server2">' +
    '<option value=""></option>' +
    '</select>'
);

$("#group_server_5").html(
    '<label class="select_label control-label">渠道:</label>'+
    '<div class="select_group_div">'+
        '<select id="g" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'+
    '<label class="select_label control-label">平台:</label>'+
    '<div class="select_platform_div">'+
        '<select id="p" class="selectpicker show-tick form-control" data-live-search="false" title="全部"></select>'+
    '</div>'+
    '<label class="select_label control-label">服务器:</label>'+
    '<div class="select_server_div">'+
        '<select id="s" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'
);

$("#group_server_5_mobel").html(
    '<label class="select_label control-label">渠道:</label>'+
    '<div class="select_group_div">'+
    '<select id="g" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'+
    '<label class="select_label control-label">平台:</label>'+
    '<div class="select_platform_div">'+
    '<select id="p" class="selectpicker show-tick form-control" data-live-search="false" title="全部"></select>'+
    '</div>'+
    '<label class="select_label control-label">服务器:</label>'+
    '<div class="select_server_div">'+
    '<select id="s" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'
);
// $("#group_server_5_mobel").html(
//     '<label class="col-sm-1 control-label">渠道：</label>'+
//     '<div class="col-sm-3">'+
//         '<select id="g" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
//     '</div>'+
//     '<label class="col-sm-1 control-label">平台：</label>'+
//     '<div class="col-sm-2">'+
//         '<select id="p" class="selectpicker show-tick form-control" data-live-search="false" title="全部"></select>'+
//     '</div>'+
//     '<label class="col-sm-1 control-label">服务器：</label>'+
//     '<div class="col-sm-4">'+
//         '<select id="s" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
//     '</div>'
// );
$("#group_server_6").html(
    '<label class="select_label control-label">渠道:</label>'+
    '<div class="select_group_div">'+
        '<select id="g" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'
);
$("#group_server_6_mobel").html(
    '<div class="col-sm-3">'+
    '<select id="g" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'
);

$("#group_server_7").html(
    '<label class="select_label control-label">渠道:</label>'+
    '<div class="select_group_div">'+
        '<select id="g" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'+
    '<label class="select_label control-label">平台:</label>'+
    '<div class="select_platform_div">'+
        '<select id="p" class="selectpicker show-tick form-control" data-live-search="true" title="全部"></select>'+
    '</div>'
);
$("#group_server_7_mobel").html(
    '<label class="col-sm-1 control-label">渠道：</label>'+
    '<div class="col-sm-3">'+
    '<select id="g" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'+
    '<label class="col-sm-1 control-label">平台：</label>'+
    '<div class="col-sm-2">'+
    '<select id="p" class="selectpicker show-tick form-control" data-live-search="false" title="全部"></select>'+
    '</div>'
);

$("#group_server_8").html(
    '<label class="select_label control-label">渠道:</label>'+
    '<div class="select_group_div">'+
        '<select id="g" class="selectpicker show-tick form-control" data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'+
    '<label class="select_label control-label">平台:</label>'+
    '<div class="select_platform_div">'+
        '<select id="p" class="selectpicker show-tick form-control" data-live-search="false" title="全部"></select>'+
    '</div>'+
    '<label class="select_label control-label">服务器:</label>'+
    '<div class="select_server_div">'+
        '<select id="s" class="selectpicker show-tick form-control" data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'
);

$("#group_server_9").html(
    '<label class="select_label control-label">渠道:</label>'+
    '<div class="select_group_div">'+
        '<select id="g" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'+
    '<label class="col-sm-1 control-label">服务器:</label>'+
    '<div class="select_server_div">'+
        '<select id="s" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'
);

$("#group_server_10").html(
    '<label class="col-sm-12 control-label">需要同步的渠道:</label>'+
    '<div class="col-sm-2">'+
        '<select id="g1" class="selectpicker show-tick form-control" data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'+
    '<label class="col-sm-12 control-label">同步到的渠道:</label>'+
    '<div class="col-sm-2">'+
        '<select id="g2" class="selectpicker show-tick form-control" data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'+
    '<a id="jin_search" class="btn btn-warning"><span class="glyphicon glyphicon-search"></span></a>'
);

$("#group_server_11").html(
    '<div class="select_group_div" style="width: 18%;">'+
    '<select id="g1" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'+
    '<div class="select_server_div" style="width: 20%;">'+
    '<select id="s" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'
);

$("#group_server_12").html(
    '<label class="col-sm-12 control-label">需要同步的渠道:</label>'+
    '<div class="col-sm-2">'+
    '<select id="g1" class="selectpicker show-tick form-control" data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'+
    '<label class="select_label control-label">同步到的渠道:</label>'+
    '<div class="select_group_div">'+
    '<select id="g" class="selectpicker show-tick form-control" multiple data-live-search="true" data-actions-box="true" title="请选择"></select>'+
    '</div>'+
    '<a id="jin_search" class="btn btn-warning"><span class="glyphicon glyphicon-search"></span></a>'
);
//以下为function
//日期查询开始
function calendarOne(minView, id) {
    var type=arguments[2]?arguments[2]:false;
    if(type){
        nowdate = CurentTime();
        if(minView=='month'){
            $(id).val(nowdate)
        }else{
            $(id).val(nowdate+' 00:00:00')
        }
    }
    var format = '';
    switch (minView) {
        case 'month':
            format = 'yyyy-mm-dd';
            break;
        case 'hour':
            format = 'yyyy-mm-dd hh:ii:ss';
            break;
        default:
            break;
    }
    $(id).datetimepicker({
        format: format,
        minView: minView,
        language: 'zh-CN',
        autoclose: true,
        clearBtn: true,
        weekStart: 1
    });
}

function calendar(minView, id1, id2,type=false) {
    if(type){
        nowdate = CurentTime();
        if(minView=='month'){
            $(id1).val(nowdate)
        }else{
            $(id1).val(nowdate+' 00:00:00')
        }
    }
    var format = '';
    switch (minView) {
        case 'month':
            format = 'yyyy-mm-dd';
            break;
        case 'hour':
            format = 'yyyy-mm-dd hh:00:00';
            break;
        case 'minute':
            format = 'yyyy-mm-dd hh:ii:00';
            minView =0;
            break;
        case 'second':
            format = 'yyyy-mm-dd hh:ii:ss';
            break;
        default:
            break;
    }
    $(id1).datetimepicker({
        format: format,
        minView: minView,
        language: 'zh-CN',
        autoclose: true,
        clearBtn: true,
        weekStart: 1
    });
    $(id2).datetimepicker({
        format: format,
        minView: minView,
        language: 'zh-CN',
        autoclose: true,
        clearBtn: true,
        weekStart: 1
    });
}

// 月-日 选择器
function createMonthDayPicker(id) {
    $(id).datetimepicker({
        format: 'mm-dd',   // 显示格式为月-日
        startView: 2,      // 从月份视图开始
        minView: 2,        // 最小视图为天
        autoclose: true,   // 选择时间后自动关闭
        language: 'zh-CN', // 语言为中文
        clearBtn: true,    // 显示清除按钮
        todayHighlight: true // 高亮显示今天的日期
    });
}

// 时-分 选择器
function createTimePicker(id) {
    $(id).datetimepicker({
        format: 'hh:ii',   // 只显示小时和分钟
        startView: 1,      // 从小时视图开始
        minView: 0,        // 最小视图为分钟
        maxView: 1,        // 最大视图为小时
        autoclose: true,   // 选择时间后自动关闭
        language: 'zh-CN', // 语言为中文
        clearBtn: true,    // 显示清除按钮
        todayHighlight: true // 高亮显示今天的日期
    });
}

//当前日期加时间(如:2009-06-12 12:00)
function CurentTime() {
    var now = new Date();
    var year = now.getFullYear();       //年
    var month = now.getMonth() + 1;     //月
    var day = now.getDate();            //日
    var clock = year + "-";
    if(month < 10)
        clock += "0";
    clock += month + "-";
    if(day < 10)
        clock += "0";
    clock += day;
    return(clock);
}

//变量是否存在判断
function isExist(param, value) {
    if (typeof(param) === 'undefined') {
        if (typeof(value) === 'undefined') {
            param = '';
        } else {
            param = value;
        }
    }
    return param;
}

//获取复选框值
function checkedValue(name) {
    var value = [];
    $('input[name=' + name + ']:checked').each(function () {
        value.push($(this).val());
    });
    return value;
}

//全选取消全选
function checkedAll(name) {
    $("#check_all_" + name).click(function () {
        if (this.checked) {
            $("input[name=" + name + "]").prop("checked", true);
        } else {
            $("input[name=" + name + "]").prop("checked", false);
        }
    });
    $("input[name=" + name + "]").click(function () {
        if (checkedValue(name).length === $("input[name=" + name + "]").length) {
            $("#check_all_" + name).prop("checked", true);
        } else {
            $("#check_all_" + name).prop("checked", false);
        }
    });
    $(document).ready(function () {
        if (checkedValue(name).length === $("input[name=" + name + "]").length) {
            $("#check_all_" + name).prop("checked", true);
        }
    });
}

//时间戳转换
function add0(m) {
    return m < 10 ? '0' + m : m
}
function getDate(stamp) {
    var time = new Date(stamp);
    var y = time.getFullYear();
    var m = time.getMonth() + 1;
    var d = time.getDate();
    var h = time.getHours();
    var mm = time.getMinutes();
    var s = time.getSeconds();
    return y + '-' + add0(m) + '-' + add0(d) + ' ' + add0(h) + ':' + add0(mm) + ':' + add0(s);
}

function checkSummaryTime(check_type, time_start, time_end, days_start, days_end) {
    var oneDay     = 24 * 60 * 60;
    if (time_start == '') {
        var time_s = Date.parse(new Date().toLocaleDateString()) / 1000;
    } else {
        var time_s = Date.parse(new Date(time_start)) / 1000;

    }
    if (time_end == '') {
        var time_e = Date.parse(new Date().toLocaleDateString()) / 1000 + oneDay;
    } else {
        var time_e = Date.parse(new Date(time_start)) / 1000 + oneDay;
    }
    var time = (time_e - time_s) / oneDay;
    if ((check_type == 998) && (time > days_start)) {
        layer.msg('服务器汇总最多只能查看' + days_start + '天');
        return false;
    }
    if ((check_type == 999) && (time > days_end)) {
        layer.msg('服务器汇总最多只能查看' + days_end + '天');
        return false;
    }
}

// 日期转时间戳
function getTimeStamp(time = new Date()) {
    return Date.parse(time) / 1000;
}
