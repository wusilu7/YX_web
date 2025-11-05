//JIN_20170608_修复页码最后一个li元素的右圆角问题
//JIN_20171023_修复tableList翻页事件重复加载导致页面卡顿的问题|优化页码第一页的前一页和最后一页的后一页的点击事件|增加页码输入框正则验证
var is_first = true;//是否首次加载
function tableList(url, data, id, arr, length) {
    contentList(url, data, id, arr, length);
    if (is_first) {
        $(id[1]).on('click', 'a', function () {   //为a标签动态绑定事件
            data.page = $(this).attr("data-pn");  //获取链接里的页码
            switch (data.page) {
                case 'omit':
                    break;
                case 'min':
                    layer.msg('已经是第一页');
                    break;
                case 'max':
                    layer.msg('已经是最后一页');
                    break;
                default:
                    contentList(url, data, id, arr, length);
                    break;
            }
        }).on('click', 'button', function () {
            var reg = new RegExp("^[0-9]*$");//数字正则验证
            data.page = $(this).parents("div:first").find("input").val();
            if (reg.test(data.page) && data.page !== '') {
                contentList(url, data, id, arr, length);
            } else {
                layer.msg('请填写纯数字页码');
            }
        });
        is_first = false;//首次加载标志更新状态
    }
}

//有分页表格
function contentList(url, data, id, arr, length) {
    var c = '';
    var p = '';
    var l = length ? length : 1;
    $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        beforeSend: function () {
            layer.load(2, {
                shade: [0.3, '#fff']//0.3透明度的白色背景
            });
        },
        success: function (json) { //从服务器端返回的是json格式的数据
            layer.closeAll('loading');
            total = json[json.length - l];
            for (var i = 0; i < json.length - l; i++) {   //取数据填表
                c += '<tr>';
                for (var j = 0; j < arr.length; j++) {
                    if (arr[j] instanceof Array) {
                        c += '<td>' + arr[j][0] + '</td>';
                    } else if (typeof arr[j] === "function") {//展示一些自定义函数
                        c += '<td>' + arr[j](json[i]) + '</td>';
                    } else {
                        if(json[i][(arr[j])] === undefined || json[i][(arr[j])] === null || json[i][(arr[j])] === ""){
                            c += '<td> 0</td>';
                        }else {
                            c += '<td>' + json[i][(arr[j])] + '</td>';
                        }
                    }
                }
                c += '</tr>';
            }
            $(id[0]).html(c); //把拼接的表格放到id=content的<tbody>标签中

            if (data['weight']) {
                $("#content tr").eq(0).css('font-weight', 600);
            }
            
            //分页
            p = pageList(total, data.page); //分页调用专门的分页方法
            $(id[1]).html(p).find("a[data-pn=" + data.page + "]").parent().addClass("active");
            if (l !== 1) {
                for (var m = 2; m <= l; m++) {
                    $(id[m]).html(json[json.length - (l - m + 1)]);
                }
            }
        },
        error: function () {
            layer.closeAll('loading');
            layer.msg('暂时无法获取数据');
        }
    });
}

//页码
function pageList(total, page) {
    var p = '';//s用来存储页码部分
    if (total > 1) {
        var left = parseInt(page) - 1;
        var right = parseInt(page) + 1;
        if (left < 1) {
            left = 'min';
        }
        if (right > total) {
            right = 'max';
        }
        p += '<form class="form-inline jin-page-list" onkeydown="if(event.keyCode===13)return false;"><ul class="pagination center">';
        p += '<li><a data-pn="' + left + '">«</a></li>';
        if (total > 6) {
            if (page < 4) {
                for (var j = 1; j <= 4; j++) {
                    p += '<li><a data-pn="' + j + '">' + j + '</a></li>';
                }
                p += '<li><a data-pn="omit">...</a></li>';
                p += '<li><a data-pn="' + total + '">' + total + '</a></li>';
            }
            if (page >= 4 && page < (total - 2)) {
                p += '<li><a data-pn=1>1</a></li>';
                p += '<li><a data-pn="omit">...</a></li>';
                p += '<li><a data-pn="' + left + '">' + left + '</a></li>';
                p += '<li><a data-pn="' + page + '">' + page + '</a></li>';
                p += '<li><a data-pn="' + right + '">' + right + '</a></li>';
                p += '<li><a data-pn="omit">...</a></li>';
                p += '<li><a data-pn="' + total + '">' + total + '</a></li>';
            }
            if (page >= (total - 2)) {
                p += '<li><a data-pn=1>1</a></li>';
                p += '<li><a data-pn="omit">...</a></li>';
                for (j = total - 3; j <= total; j++) {
                    p += '<li><a data-pn="' + j + '">' + j + '</a></li>';
                }
            }
        } else {
            for (j = 1; j <= total; j++) {
                p += '<li><a data-pn="' + j + '">' + j + '</a></li>';
            }
        }
        p += '<div class="input-group jin-skip"><input type="text" class="form-control" placeholder="页"> <span class="input-group-btn"> <button class="btn btn-default" type="button">跳转</button> </span> </div>';
        p += '<li><a data-pn="' + right + '">»</a></li>';
        p += '</ul></form>';
    }
    if (total === 0) {
        p = '<h1 class="text-center jin-none"><span class="label label-danger">无匹配数据</span></h1>';
    }
    return p;
}

//无分页表格
function noPageContentList11(url, data, id, arr,type) {
    var c = '';
    $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        beforeSend: function () {
            layer.load(2, {
                shade: [0.3, '#fff']//0.3透明度的白色背景
            });
        },
        success: function (json) {
            layer.closeAll('loading');
            for (var i = 0; i < json.length; i++) {   //取数据填表
                c += '<tr>';
                for (var j = 0; j < arr.length; j++) {
                    if ( j == 1) {//
                        c += '<td style="display: none">' + arr[j](json[i]) + '</td>';
                    }else
                    {
                        if (arr[j] instanceof Array) {
                            c += '<td class="a">' + arr[j][0] + '</td>';
                        } else if (typeof arr[j] === "function") {//展示一些自定义函数
                            c += '<td class="b">' + arr[j](json[i]) + '</td>';
                        } else {
                            if (typeof json[i]['server_status'] != "undefined") {
                                if (json[i]['server_status'] == 0) {
                                    if (arr[j] == 'name') {
                                        c += '<td class="server_red">' + json[i][(arr[j])] + '</td>';
                                    } else {
                                        c += '<td class="c">' + json[i][(arr[j])] + '</td>';
                                    }
                                } else if (json[i]['server_status'] == 1) {
                                    if (arr[j] == 'name') {
                                        c += '<td class="server_green">' + json[i][(arr[j])] + '</td>';
                                    } else {
                                        c += '<td class="d">' + json[i][(arr[j])] + '</td>';
                                    }
                                }
                            } else {
                                c += '<td class="e">' + json[i][(arr[j])] + '</td>';
                            }
                        }
                    }

                }

                c += '</tr>';
            }
            $(id).html(c);
            if(type){
                if ($.cookie('cookie_gs')) {
                    var gs = eval('[' + $.cookie('cookie_gs') + ']');
                    for (var i in gs) {
                        $('#content').find('input[type="checkbox"][value="'+gs[i]+'"]').attr('checked', true);
                        $('#content').find('input[type="checkbox"][value="'+gs[i]+'"]').parent().parent().attr('style', 'background: #aba5618c');
                    }
                }
            }
        },
        error: function () {
            layer.closeAll('loading');
            layer.msg('暂时无法获取数据');
        }
    });
}
function noPageContentList12(url, data, id, arr,type) {
    var c = '';
    $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        beforeSend: function () {
            layer.load(2, {
                shade: [0.3, '#fff']//0.3透明度的白色背景
            });
        },
        success: function (json) {
            layer.closeAll('loading');
            for (var i = 0; i < json.length; i++) {   //取数据填表
                c += '<tr>';
                for (var j = 0; j < arr.length; j++) {
                    if ( j == 0) {//
                        c += '<td style="display: none">' + json[i][(arr[j])] + '</td>';
                    }else
                    {
                        if (arr[j] instanceof Array) {
                            c += '<td>' + arr[j][0] + '</td>';
                        } else if (typeof arr[j] === "function") {//展示一些自定义函数
                            c += '<td>' + arr[j](json[i]) + '</td>';
                        } else {
                            if (typeof json[i]['server_status'] != "undefined") {
                                if (json[i]['server_status'] == 0) {
                                    if (arr[j] == 'name') {
                                        c += '<td class="server_red">' + json[i][(arr[j])] + '</td>';
                                    } else {
                                        c += '<td>' + json[i][(arr[j])] + '</td>';
                                    }
                                } else if (json[i]['server_status'] == 1) {
                                    if (arr[j] == 'name') {
                                        c += '<td class="server_green">' + json[i][(arr[j])] + '</td>';
                                    } else {
                                        c += '<td>' + json[i][(arr[j])] + '</td>';
                                    }
                                }
                            } else {
                                c += '<td>' + json[i][(arr[j])] + '</td>';
                            }
                        }
                    }

                }

                c += '</tr>';
            }
            $(id).html(c);
            if(type){
                if ($.cookie('cookie_gs')) {
                    var gs = eval('[' + $.cookie('cookie_gs') + ']');
                    for (var i in gs) {
                        $('#content').find('input[type="checkbox"][value="'+gs[i]+'"]').attr('checked', true);
                        $('#content').find('input[type="checkbox"][value="'+gs[i]+'"]').parent().parent().attr('style', 'background: #aba5618c');
                    }
                }
            }
        },
        error: function () {
            layer.closeAll('loading');
            layer.msg('暂时无法获取数据');
        }
    });
}
function noPageContentList(url, data, id, arr,type) {
    var c = '';
    $.ajax({
        type: "post",
        url: url,
        data: data,
        dataType: "json",
        beforeSend: function () {
            layer.load(2, {
                shade: [0.3, '#fff']//0.3透明度的白色背景
            });
        },
        success: function (json) {
            layer.closeAll('loading');
            for (var i = 0; i < json.length; i++) {   //取数据填表
                c += '<tr>';
                for (var j = 0; j < arr.length; j++) {
                    if (arr[j] instanceof Array) {
                        c += '<td>' + arr[j][0] + '</td>';
                    } else if (typeof arr[j] === "function") {//展示一些自定义函数
                        c += '<td>' + arr[j](json[i]) + '</td>';
                    } else {
                        if (typeof json[i]['server_status'] != "undefined") {
                            if (json[i]['server_status'] == 0) {
                                if (arr[j] == 'name') {
                                    c += '<td class="server_red">' + json[i][(arr[j])] + '</td>';
                                } else {
                                    c += '<td>' + json[i][(arr[j])] + '</td>';
                                }
                            } else if (json[i]['server_status'] == 1) {
                                if (arr[j] == 'name') {
                                    c += '<td class="server_green">' + json[i][(arr[j])] + '</td>';
                                } else {
                                    c += '<td>' + json[i][(arr[j])] + '</td>';
                                }
                            }
                        } else {
                            c += '<td>' + json[i][(arr[j])] + '</td>';
                        }
                    }
                }

                c += '</tr>';
            }
            $(id).html(c);
            if(type){
                if ($.cookie('cookie_gs')) {
                    var gs = eval('[' + $.cookie('cookie_gs') + ']');
                    for (var i in gs) {
                        $('#content').find('input[type="checkbox"][value="'+gs[i]+'"]').attr('checked', true);
                        $('#content').find('input[type="checkbox"][value="'+gs[i]+'"]').parent().parent().attr('style', 'background: #aba5618c');
                    }
                }
            }
        },
        error: function () {
            layer.closeAll('loading');
            layer.msg('暂时无法获取数据');
        }
    });
}

//渠道汇总弹窗
function giCollect(actions,off) {
    console.log(off);
    $.ajax({
        type: "POST",
        url: "?p=Admin&c=Operation&a=group&jinIf=945",
        dataType: 'json',
        success: function (res) {
            var con = '<div>' +
                '<input type="checkbox" id="check_all_groups" class="regular-checkbox big-checkbox"/><label for="check_all_groups"></label>' +
                '<label class="jin-all-text" for="check_all_groups">勾选所有</label></div>';
            for (var i=0;i<res.length;i++){
                con += '<div style="width: 290px; display: inline-block;">'+
                    '<input type="checkbox"  name="groups" id="' + res[i].group_id + '" value="' + res[i].group_id + '" class="regular-checkbox"/>' +
                    '<label for="' + res[i].group_id+ '"></label><label class="jin-checkbox-text" for="' + res[i].group_id+ '">' + res[i].group_name + '</label>' +
                    '</div>';
            }
            var docW = window.screen.width;
            if(docW < 768){
                layerW = '80%';
                layerH = '80%';
            }else{
                layerW = '600px';
                layerH = '450px;';
            }
            layer.open({
                type: 1,
                closeBtn: 2,
                title: '选取渠道',
                area: [layerW, layerW],
                btn: ['确定', '取消'],
                btnAlign: 'c',
                shadeClose: true, //点击遮罩关闭
                content: con,
                yes: function (index) {
                    layer.close(index);
                    if(off){
                        data1.groups = checkedValue('groups').join(",");
                    }else{
                        data.groups = checkedValue('groups').join(",");
                    }
                    actions();
                },
                cancel: function () {
                }
            });
            checkedAll('groups');
        }
    });
}
