//二级联动带函数
function gsSelect(g, s, p, fn) {
    obj1 = {dom: g};
    obj2 = {dom: s};
    obj3 = {dom: p};
    groupSelect(obj1);
    fn = isExist(fn);
    $(g).on('change', function () { //渠道改变的时候
        // if($(this).val() == -1){ //hf加
        //     if (fn !== '') {
        //         $(function () {
        //             fn();
        //         });
        //     }
        //     return false;
        // }
        obj2.data = {gi: $(this).val()};
        $(s).empty();
        $(p).empty();
        if (p != '') {
            platformSelect(obj3);
        }
        if (s != '') {
            serverSelect(obj2);//获取服务器列表
        }
    });
    $(s).on('change', function () {
        gi = $(g).val();
        si = $(s).val();
        if (si !== null) {
            $.ajax({
                type: "POST",
                url: "?p=Admin&c=Operation&a=changeServer&jinIf=919",
                data: {
                    si: si
                },
                success: function () {
                    if (fn !== '') {
                        $(function () {
                            fn();
                        });
                    }
                }
            });
        }
    });
}

//client 特制版
function gsSelect2(g, s1, s2, fn) {
    obj1 = {dom: g}; 
    obj2 = {dom: s1};
    obj3 = {dom: s2};
    groupSelect(obj1);
    fn = isExist(fn);
    $(g).on('change', function () { //渠道改变的时候
        
        obj2.data = {gi: $(this).val()};
        $(s1).empty();
        $(s2).empty();

        if (s1 != '') {
            serverSelect(obj2);//获取服务器列表
        }

        obj3.data = {gi: $(this).val()};
        
        if (s2 != '') {
            serverSelect2(obj3);
        }

    });
    $(s1).on('change', function () {
        gi = $(g).val();
        si1 = $(s1).val();
       
        if (si1 !== null) {
            $.ajax({
                type: "POST",
                url: "?p=Admin&c=Operation&a=changeServer&jinIf=919",
                data: {
                    si1: si1
                },
                success: function () {
                    if (fn !== '') {
                        $(function () {
                            fn();
                        });
                    }
                }
            });
        }
    });
    $(s2).on('change', function () {
        gi = $(g).val();
        si2 = $(s2).val();
       
        if (si2 !== null) {
            $.ajax({
                type: "POST",
                url: "?p=Admin&c=Operation&a=changeServer&jinIf=919",
                data: {
                    si2: si2
                },
                success: function () {
                    if (fn !== '') {
                        $(function () {
                            fn();
                        });
                    }
                }
            });
        }
    });
}

function gsSelect5(g1, g2) {
    obj1 = {id: g1};
    obj1.url = "?p=Admin&c=Operation&a=group&jinIf=942";

    obj2 = {id: g2};
    obj2.url = "?p=Admin&c=Operation&a=group&jinIf=942";

    twogroups(obj1);
    twogroups(obj2);
}

function gsSelect6(g1, g2) {
    obj1 = {id: g1};
    obj1.url = "?p=Admin&c=Operation&a=group&jinIf=942";

    obj2 = {id: g2};
    obj2.url = "?p=Admin&c=Operation&a=group&jinIf=943";

    twogroups(obj1);
    groups(obj2);
}

//复选下拉框
function gsSelect3(g, p, s, fn) {
    obj1 = {id: g};
    obj1.url = "?p=Admin&c=Operation&a=group&jinIf=943";

    obj2 = {id: p};
    obj3 = {id: s};

    groups(obj1);

    $(p).empty();
    platform(obj2);
    
    $(g).on('change', function () { //渠道改变的时候
        $.cookie('cookie_g', $(g).val(), {expires: 30});
        obj3.gi = $(obj1.id).val();
        obj3.url = "?p=Admin&c=Operation&a=server&jinIf=943";

        if (s != '') {
            servers(obj3);//获取服务器列表
        }
    });
}

function groups(obj) {
    $.ajax({
        type: "POST",
        url: obj.url,
        dataType: 'json',
        data: data,
        success: function (res) {
            var c = '';
            var li = '';
            
            for (var i = 0; i < res.length; i++) {
                c +='<optgroup label="'+ res[i][0] +'">'
                li += '<li class="dropdown-header " data-optgroup="'+ i +'"><span class="text">'+ res[i][0] +'</span></li>';

                var x
                for (var j = 1; j < res[i].length; j++) {
                    if (i == 0) {
                        x = -1;

                        for (var j = 1; j < res[i].length; j++) {
                            var aa = j  - 1;
                            c += '<option value="'+ res[i][j].group_id +'">'+ res[i][j].group_name +'</option>';
                            li += '<li data-original-index="'+ aa +'">' +
                                    '<a tabindex="0" data-tokens="null">' +
                                        '<span class="text text_content">'+ res[i][j].group_name +'</span><span class="glyphicon glyphicon-ok check-mark"></span>'+
                                    '</a>'+
                                '</li>';
                        }
                    } else {
                        x += res[i-1].length;

                        for (var j = 1; j < res[i].length; j++) {
                            var aa = j + x -i;
                            c += '<option value="'+ res[i][j].group_id +'">'+ res[i][j].group_name +'</option>';
                            li += '<li data-original-index="'+ aa +'">' +
                                    '<a tabindex="0" data-tokens="null">' +
                                        '<span class="text text_content">'+ res[i][j].group_name +'</span><span class="glyphicon glyphicon-ok check-mark"></span>'+
                                    '</a>'+
                                '</li>';
                        }
                    }
                }

                li += '<li class="divider" data-optgroup="2div"></li>';
                c += '</optgroup>';
            }

            $(obj.id).html(c);

            var pre = $(obj.id).prev();
            $(pre).attr("id","group");
            $('#group ul').html(li);
            $('.selectpicker').selectpicker('refresh');
        }
    });
}

function twogroups(obj) {
    $.ajax({
        type: "POST",
        url: obj.url,
        dataType: 'json',
        data: data,
        success: function (res) {
            var c = '';
            var li = '';
            for (var i = 0; i < res.length; i++) {
                c += '<option value="'+ res[i].group_id +'">'+ res[i].group_name +'</option>';
                li += '<li data-original-index="'+ i +'">' +
                            '<a tabindex="0" data-tokens="null">' +
                                '<span class="text">'+ res[i].group_name +'</span><span class="glyphicon glyphicon-ok check-mark"></span>'+
                            '</a>'+
                        '</li>';
            }
            $(obj.id).html(c);

            var pre = $(obj.id).prev();
            $(pre).attr("id","group");
            $('#group ul').html(li);
            $('.selectpicker').selectpicker('refresh');
        }
    });
}

function grouptype(obj) {
    $.ajax({
        type: "POST",
        url: obj.url,
        dataType: 'json',
        data: data,
        success: function (res) {
            var c = '';
            var li = '';
            for (var i = 0; i < res.length; i++) {
                c += '<option value="'+ res[i].id +'">'+ res[i].type_name +'</option>';
                li += '<li data-original-index="'+ i +'">' +
                            '<a tabindex="0" data-tokens="null">' +
                                '<span class="text">'+ res[i].type_name +'</span><span class="glyphicon glyphicon-ok check-mark"></span>'+
                            '</a>'+
                        '</li>';
            }
            $(obj.id).html(c);

            var pre = $(obj.id).prev();
            $(pre).attr("id","group");
            $('#group ul').html(li);
            $('.selectpicker').selectpicker('refresh');
        }
    });
}

function platform(obj) {
    var c = '';
    var li = '';
    c = '<option value="0">全部</option><option value="8">ios</option><option value="11">android</option>';
    li = '<li data-original-index="0" >' +
            '<a tabindex="0" data-tokens="null">' +
                '<span class="text">全部</span><span class="glyphicon glyphicon-ok check-mark"></span>'+
            '</a>'+
        '</li>'+
        '<li data-original-index="8" >' +
            '<a tabindex="8" data-tokens="null">' +
                '<span class="text">ios</span><span class="glyphicon glyphicon-ok check-mark"></span>'+
            '</a>'+
        '</li>'+
        '<li data-original-index="11" >' +
            '<a tabindex="11" data-tokens="null">' +
                '<span class="text">android</span><span class="glyphicon glyphicon-ok check-mark"></span>'+
            '</a>'+
        '</li>';
    $(obj.id).html(c);
    var pre = $(obj.id).prev();
    $(pre).attr("id","platform_top");
    $('#platform_top ul').html(li);
}  
function servers(obj) {
    
    $.ajax({
        type: "POST",
        url: obj.url,
        dataType: 'json',
        data: obj,
        success: function (res) {
            var c = '';
            var li = '';

            if (res) {
                for (var i = 0; i < res.length; i++) {
                    c +='<optgroup label="'+ res[i][0] +'">'
                    li += '<li class="dropdown-header " data-optgroup="'+ i +'"><span class="text">'+ res[i][0] +'</span></li>';

                    var x
                    for (var j = 1; j < res[i].length; j++) {
                        if (i == 0) {
                            x = -1;

                            for (var j = 1; j < res[i].length; j++) {
                                var aa = j  - 1;
                                c += '<option value="'+ res[i][j].server_id +'">'+ res[i][j].name +'</option>';
                                li += '<li data-original-index="'+ aa +'">' +
                                        '<a tabindex="0" data-tokens="null">' +
                                            '<span class="text text_content">'+ res[i][j].name +'</span><span class="glyphicon glyphicon-ok check-mark"></span>'+
                                        '</a>'+
                                    '</li>';
                            }
                        } else {
                            x += res[i-1].length;

                            for (var j = 1; j < res[i].length; j++) {
                                var aa = j + x -i;
                                c += '<option value="'+ res[i][j].server_id +'">'+ res[i][j].name +'</option>';
                                li += '<li data-original-index="'+ aa +'">' +
                                        '<a tabindex="0" data-tokens="null">' +
                                            '<span class="text text_content">'+ res[i][j].name +'</span><span class="glyphicon glyphicon-ok check-mark"></span>'+
                                        '</a>'+
                                    '</li>';
                            }
                        }
                    }

                    li += '<li class="divider" data-optgroup="2div"></li>';
                    c += '</optgroup>';
                }
                $(obj.id).html(c);

                var pre = $(obj.id).prev();
                $(pre).attr("id", "server_top");
                $('#server_top ul').html(li);
                $(obj.id).on('change',function () {
                    $.cookie('cookie_s', $(obj.id).val(), {expires: 30});
                });
                $('.selectpicker').selectpicker('refresh');
            } else {
                $(obj.id).html(c);

                var pre = $(obj.id).prev();
                $(pre).attr("id", "server_top");
                $('#server_top ul').html(li);
                $('.selectpicker').selectpicker('refresh');
            }             
        }
    });
}

function gsSelect4(g) {
    obj = {id: g};
    obj.url = "?p=Admin&c=Operation&a=group&jinIf=942";

    $.ajax({
        type: "POST",
        url: obj.url,
        dataType: 'json',
        data: data,
        success: function (res) {
            var c = '';
            var li = '';
            for (var i = 0; i < res.length; i++) {
                c += '<option value="'+ res[i].group_id +'">'+ res[i].group_name +'</option>';
                li += '<li data-original-index="'+ i +'" onclick=selected('+ i +')>' +
                            '<a tabindex="0" data-tokens="null">' +
                                '<span class="text">'+ res[i].group_name +'</span><span class="glyphicon glyphicon-ok check-mark"></span>'+
                            '</a>'+
                        '</li>';
            }
            $(obj.id).html(c);

            var pre = $(obj.id).prev();
            $(pre).attr("id","group");
            $('#group ul').html(li);
        }
    });   
}   
// 渠道类型
function groupSelect(obj) {
    obj = isExist(obj, {});
    obj.dom = isExist(obj.dom, "#group");
    obj.width = isExist(obj.width, "230px");
    obj.url = isExist(obj.url, "?p=Admin&c=Operation&a=group&jinIf=942");
    obj.id = isExist(obj.id, "group_id");
    obj.text = isExist(obj.text, "group_name");
    jinSelect(obj);
}

// 平台类型
function platformSelect(obj) {
    var dom = isExist(obj.dom, "#platform");
    var width = "150px";
    var val = isExist($.cookie('s_' + dom), 0);//默认值
    var multiple = false;//默认单选
    var placeholder = '请选择';//默认值

    var arr = [
        {
            id : 0,
            text : '全部'
        }, {
            id : 8,
            text : 'ios'
        }, {
            id : 11,
            text : 'android'
        }
    ];
    $(dom).select2({
        data: arr,
        placeholder: placeholder,
        theme: "classic",
        width: width,
        multiple: multiple
    }).val(val).trigger('change');
    $(dom).on('change', function () {
        //设置选项cookie
        var val = $(dom).val();
        $.cookie('s_' + dom, val, {expires: 30});
    });
}

// 服务器列表
function serverSelect(obj) {
    obj = isExist(obj, {});
    obj.dom = isExist(obj.dom, "#server");
    obj.url = isExist(obj.url, "?p=Admin&c=Operation&a=server&jinIf=942");
    obj.id = isExist(obj.id, "server_id");
    obj.text = isExist(obj.text, "name");
    jinSelect(obj);
}

function serverSelect2(obj) {
    obj = isExist(obj, {});
    obj.dom = isExist(obj.dom, "#server2");
    obj.url = isExist(obj.url, "?p=Admin&c=Operation&a=server&jinIf=942");
    obj.id = isExist(obj.id, "server_id");
    obj.text = isExist(obj.text, "name");
    jinSelect(obj);
}

//货币类型
function moneySelect(obj) {
    obj = isExist(obj, {});
    obj.dom = isExist(obj.dom, "#money_type");
    obj.url = isExist(obj.url, "?p=Admin&c=Data2&a=selectMoney&jinIf=941");
    obj.id = isExist(obj.id, "val");
    obj.text = isExist(obj.text, "coin");
    jinSelect(obj);
}
//货币类型(多选)
function moneySelects() {
    $.ajax({
        type: "POST",
        url: "?p=Admin&c=Data2&a=selectMoney&jinIf=942",
        dataType: 'json',
        success: function (res) {
            var c = '<optgroup>';
            for (var j = 1; j < res.length; j++) {
                c += '<option value="'+ res[j].val +'">'+ res[j].coin +'</option>';;
            }
            c += '</optgroup>';
            $("#money_type").html(c);
            $('.selectpicker').selectpicker('refresh');
        }
    });
}

//聊天类型
function chatType(obj) {
    obj = isExist(obj, {});
    obj.dom = isExist(obj.dom, "#chat_type");
    obj.url = isExist(obj.url, "?p=Admin&c=Player&a=chat&jinIf=941");
    jinSelect(obj);
}

//定时任务类型
function timingType(obj) {
    obj = isExist(obj, {});
    obj.dom = isExist(obj.dom, "#Timing_type");
    obj.url = isExist(obj.url, "?p=Admin&c=Operation&a=selectTiming&jinIf=941");
    obj.id = isExist(obj.id, "value");
    obj.text = isExist(obj.text, "name");
    jinSelect(obj);
}

//礼包码类型
function codeSelect(obj) {
    obj = isExist(obj, {});
    obj.dom = isExist(obj.dom, "#code_type");
    obj.url = isExist(obj.url, "?p=Admin&c=Operation&a=gc&jinIf=941");
    jinSelect(obj);
}

//礼包码渠道
function codeGroupSelect(obj1) {
    obj1 = {id: g};
    obj1.url = "?p=Admin&c=Operation&a=gc&jinIf=945";
    groups(obj1);
}

// 模板类型
function templateSelect(obj) {
    obj = isExist(obj, {});
    obj.dom = isExist(obj.dom, "#template");
    obj.url = isExist(obj.url, location.href + "&jinIf=942");
    obj.id = isExist(obj.id, "id");
    obj.text = isExist(obj.text, "temp_title");
    jinSelect(obj);
}

//玩家充值类型
function chargeMoneySelect(obj) {
    obj = isExist(obj, {});
    obj.dom = isExist(obj.dom, "#charge_money");
    obj.url = isExist(obj.url, "?p=Admin&c=Player&a=reChargeNum&jinIf=941");
    obj.id = isExist(obj.id, "id");
    obj.text = isExist(obj.text, "num");
    jinSelect(obj);
}
//玩家充值类型
function chargeMoneySelect1(obj) {
    obj = isExist(obj, {});
    obj.dom = isExist(obj.dom, "#charge_money");
    obj.url = isExist(obj.url, "?p=Admin&c=Player&a=reChargeNum&jinIf=942");
    obj.id = isExist(obj.id, "id");
    obj.text = isExist(obj.text, "num");
    jinSelect(obj);
}
//道具下拉框搜索渲染数据
function getItems (ids,fn) {
    $.ajax({
        type: "POST",
        url: "?p=Admin&c=Data2&a=selectItem&jinIf=943",
        dataType: 'json',
        data: {},
        success: function (item) {
            items = item;
            if(fn!=''){
                fn();
            }
            for (var k=0;k<ids.length;k++){
                $(ids[k]).on('shown.bs.select',function(e) {
                    var _this = $(this);
                    _this.prev().find("input").keyup (function () {
                        var cc='';
                        if($(this).val()==''){
                            return false;
                        }
                        for(var i in item){
                            if(item[i].name.indexOf($(this).val())!=-1){
                                cc+='<option value="'+item[i].id+'">'+item[i].name+'</option>';
                            }
                        }
                        _this.html(cc);
                        _this.selectpicker('refresh');
                    })
                });
            }
        }
    });
}

function getCurrencyOpt(id) {
    $.ajax({
        type: "POST",
        url: "?p=Admin&c=Data2&a=selectMoney&jinIf=943",
        dataType: 'json',
        data: {},
        success: function (item) {
            $(id).on('shown.bs.select',function(e) {
                var _this = $(this);
                _this.prev().find("input").keyup (function () {
                    var cc='';
                    if($(this).val()==''){
                        return false;
                    }
                    for(var i in item){
                        if(item[i].indexOf($(this).val())!=-1||i.indexOf($(this).val())!=-1){
                            cc+='<option value="'+i+'">'+item[i]+'('+i+')</option>';
                        }
                    }
                    _this.html(cc);
                    _this.selectpicker('refresh');
                })
            });
        }
    });
}

function getItemSource(id) {
    $.ajax({
        type: "POST",
        url: "?p=Admin&c=Data2&a=selectItem&jinIf=9431",
        dataType: 'json',
        data: {},
        success: function (item) {
            $(id).on('shown.bs.select',function(e) {
                var _this = $(this);
                _this.prev().find("input").keyup (function () {
                    var cc='';
                    if($(this).val()==''){
                        return false;
                    }
                    for(var i in item){
                        if(item[i].indexOf($(this).val())!=-1||i.indexOf($(this).val())!=-1){
                            cc+='<option value="'+i+'">'+item[i]+'('+i+')</option>';
                        }
                    }
                    _this.html(cc);
                    _this.selectpicker('refresh');
                })
            });
        }
    });
}
//生成奖励标签
function ItemsTag(iii) {
    var ii=arguments[1]?arguments[1]:1;
    var getItems_arr = [];  //需要渲染数据的道具下拉框放入这个数组
    for (var i=ii;i<=iii;i++){
        getItems_arr.push("#item_id"+i);
        $("#reward_type_son"+i).html(
            '<div id="reward_type_son_son1'+i+'"><select id="item_id'+i+'"  class="selectpicker"  data-live-search="true" data-actions-box="true" title="请选择道具"></select></div>' +
            '<div id="reward_type_son_son2'+i+'" style="display: none;">' +
            '<select id="money_id'+i+'" style="width: 180px; height: 32px; line-height: 32px;">' +
            '<option value="1">体力</option>' +
            '<option value="2">钻石</option>' +
            '<option value="3">金币</option>' +
            '<option value="4">小宝箱钥匙</option>' +
            '<option value="5">大宝箱钥匙</option>' +
            '<option value="6">石材</option>' +
            '<option value="7">木材</option>' +
            '<option value="8">锻造石</option>' +
            '<option value="9">天赋石</option>' +
            '<option value="10">兽粮</option>' +
            '<option value="11">星币</option>' +
            '<option value="12">命运钥匙</option>' +
            '<option value="13">星币(掉落用)</option>' +
            '<option value="14">经验(掉落用)</option>' +
            '</select></div>');

        (function (i) {
            $("#reward_type"+i).change(function () {
                if($(this).val()=='[奖励道具]'){
                    $("#reward_type_son_son1"+i).show();
                    $("#reward_type_son_son2"+i).hide();
                    $(this).css("position","relative").css("top","0px");
                    $("#item_num"+i).css("position","relative").css("top","0px");
                    $("#item_num"+i).val('');
                }else{
                    $("#reward_type_son_son2"+i).show();
                    $("#reward_type_son_son1"+i).hide();
                    $(this).css("position","relative").css("top","0px");
                    $("#item_num"+i).css("position","relative").css("top","0px");
                    $("#item_num"+i).val('');
                }
            });
        })(i)
    }
    getItems(getItems_arr,getSa);
}
//修改活动页面奖励渲染
function reward_shadow(reward_arr, iii) {
    var ii = arguments[2] ? arguments[2] : 1;
    reward_arr.pop();//移除最后一个 空的
    var reward = [];
    for (let k = ii; k <= iii; k++) {
        reward[k] = [];
        reward_arr_son = reward_arr.shift();
        if(reward_arr_son){
            $(".fee_rate"+k).css('display', 'block').addClass("show_input");
            reward[k].push(reward_arr_son);
            if(reward[k][0].indexOf('[奖励货币]')!=-1){
                $("#reward_type"+k+" option[value='[奖励货币]']").attr("selected","selected");
                $("#reward_type_son_son2" + k).show();
                $("#reward_type_son_son1" + k).hide();
                $("#reward_type_son_son3" + k).hide();
                $("#reward_type_son_son4" + k).hide();
                //--------------reward[k][0].slice(reward[k][0].indexOf('(')+1,reward[k][0].indexOf(','))  截取 ( 和 , 之间的内容
                $("#money_id"+k+" option[value='"+reward[k][0].slice(reward[k][0].indexOf('(')+1,reward[k][0].indexOf(','))+"']").attr("selected","selected");
                $("#item_num"+k).val(reward[k][0].slice(reward[k][0].indexOf(',')+1,-1));
                $("#probability"+k).val(reward[k][0].slice(0,reward[k][0].indexOf('=')));
            } else if (reward[k][0].indexOf('[奖励物品]') != -1) {
                $("#reward_type"+k+" option[value='[奖励道具]']").attr("selected","selected");
                $("#reward_type_son_son1" + k).show();
                $("#reward_type_son_son2" + k).hide();
                $("#reward_type_son_son3" + k).hide();
                $("#reward_type_son_son4" + k).hide();
                var cc='';
                for(var i in items){
                    if(items[i].name.indexOf(reward[k][0].slice(reward[k][0].indexOf('(')+1,reward[k][0].indexOf(',')))!=-1){
                        cc+='<option value="'+items[i].id+'">'+items[i].name+'</option>';
                    }
                }
                $("#item_id" + k).html(cc);
                $("#item_id" + k + " option[value=" + reward[k][0].slice(reward[k][0].indexOf('(') + 1, reward[k][0].indexOf(',')) + "]").attr("selected", "selected");
                $("#item_id" + k).selectpicker('refresh');
                $("#item_num" + k).val(reward[k][0].slice(reward[k][0].indexOf(',') + 1, -1));
                $("#probability" + k).val(reward[k][0].slice(0, reward[k][0].indexOf('=')));
                if (cc == '') {
                    $("#item_num" + k).val('');
                }
            } else if (reward[k][0].indexOf('[奖励时装]') != -1) {
                $("#reward_type" + k + " option[value='[奖励时装]']").attr("selected", "selected");
                $("#reward_type_son_son3" + k).show();
                $("#reward_type_son_son1" + k).hide();
                $("#reward_type_son_son2" + k).hide();
                $("#reward_type_son_son4" + k).hide();
                rewardSetNum(k, false);
                $("#item_num" + k).val(reward[k][0].slice(reward[k][0].indexOf(',') + 1, -1));
            } else if (reward[k][0].indexOf('[奖励宠物]') != -1) {
                $("#reward_type" + k + " option[value='[奖励宠物]']").attr("selected", "selected");
                $("#reward_type_son_son4" + k).show();
                $("#reward_type_son_son1" + k).hide();
                $("#reward_type_son_son2" + k).hide();
                $("#reward_type_son_son3" + k).hide();
                rewardSetNum(k, false);
                $("#item_num" + k).val(reward[k][0].slice(reward[k][0].indexOf(',') + 1, -1));
            }
            // Promise 链控制异步请求顺序
            fetchData("#fashion_id" + k, 1).then(function (selector) {
                setTimeout(() => {
                    $(selector).val(reward[k][0].slice(reward[k][0].indexOf('(') + 1, reward[k][0].indexOf(',')));
                    $(selector).selectpicker('refresh');
                }, 100);
                return fetchData("#pet_id" + k, 2);
            }).then(function (selector) {
                setTimeout(() => {
                    $(selector).val(reward[k][0].slice(reward[k][0].indexOf('(') + 1, reward[k][0].indexOf(',')));
                    $(selector).selectpicker('refresh');
                }, 100);
            }).catch(function (error) {
                console.log(error);
            });
        }
    }
}
//奖励拼接
function reward(iii) {
    var ii = arguments[1] ? arguments[1] : 1;
    var t_reward = '';
    //[奖励装备]和[奖励道具]都转化成[奖励物品]
    for (var i = ii; i <= iii; i++) {
        if ($('#item_num' + i).val() != '') {
            var type = $("#reward_type" + i).val();
            switch (type) {
                case '[奖励道具]':
                    t_reward += '[奖励物品](' + $('#item_id' + i).val() + ',' + $('#item_num' + i).val() + ');';
                    break;
                case '[奖励货币]':
                    t_reward += '[奖励货币](' + $('#money_id' + i).val() + ',' + $('#item_num' + i).val() + ');';
                    break;
                case '[奖励时装]':
                    t_reward += '[奖励时装](' + $('#fashion_id' + i).val() + ',' + $('#item_num' + i).val() + ');';
                    break;
                case '[奖励宠物]':
                    t_reward += '[奖励宠物](' + $('#pet_id' + i).val() + ',' + $('#item_num' + i).val() + ');';
                    break;
                default:
                    ;
            }
        }
    }
    return t_reward;
}
//奖励拼接
function reward_rand(iii) {
    var ii = arguments[1] ? arguments[1] : 1;
    var t_reward = '';
    //[奖励装备]和[奖励道具]都转化成[奖励物品]
    for (var i = ii; i <= iii; i++) {
        if ($('#item_num' + i).val() != '' && $('#probability' + i).val() != '') {
            var type = $("#reward_type" + i).val();
            switch (type) {
                case '[奖励道具]':
                    t_reward += $('#probability' + i).val() + '=[奖励物品](' + $('#item_id' + i).val() + ',' + $('#item_num' + i).val() + ');';
                    break;
                case '[奖励货币]':
                    t_reward += $('#probability' + i).val() + '=[奖励货币](' + $('#money_id' + i).val() + ',' + $('#item_num' + i).val() + ');';
                    break;
                case '[奖励时装]':
                    t_reward += $('#probability' + i).val() + '=[奖励时装](' + $('#fashion_id' + i).val() + ',' + $('#item_num' + i).val() + ');';
                    break;
                case '[奖励宠物]':
                    t_reward += $('#probability' + i).val() + '=[奖励宠物](' + $('#pet_id' + i).val() + ',' + $('#item_num' + i).val() + ');';
                    break;
                default:
                    ;
            }
        }
    }
    return t_reward;
}
//js GET方法
function GetQueryValue(queryName) {
    var query = decodeURI(window.location.search.substring(1));
    var vars = query.split("&");
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split("=");
        if (pair[0] == queryName) { return pair[1]; }
    }
    return null;
}
//活动时间渲染
function FixedTime(json) {
    //开启时间和结束时间判断
    String.prototype.trim = function (char, type) {
        if (char) {
            if (type == 'left') {
                return this.replace(new RegExp('^\\'+char+'+', 'g'), '');
            } else if (type == 'right') {
                return this.replace(new RegExp('\\'+char+'+$', 'g'), '');
            }
            return this.replace(new RegExp('^\\'+char+'+|\\'+char+'+$', 'g'), '');
        }
        return this.replace(/^\s+|\s+$/g, '');
    };
    OpenTime = json.OpenTime.split(';');
    EndTime = json.EndTime.split(';');
    Fixed_OpenTime='';
    Delay_OpenTime='';
    Fixed_EndTime ='';
    Delay_EndTime ='';
    for (var i=0;i<OpenTime.length;i++){
        if(OpenTime[i].indexOf('固定日期')!=-1){
            Fixed_OpenTime = OpenTime[i];
        }
        if(OpenTime[i].indexOf('开服日期')!=-1){
            Delay_OpenTime = OpenTime[i];
        }
    }
    for (var i=0;i<EndTime.length;i++){
        if(EndTime[i].indexOf('固定日期')!=-1){
            Fixed_EndTime = EndTime[i];
        }
        if(EndTime[i].indexOf('开服日期')!=-1){
            Delay_EndTime = EndTime[i];
        }
    }

    //固定日期去除左右多余字符并分割成数组
    if(Fixed_OpenTime!=''){
        Fixed_OpenTime = Fixed_OpenTime.substring(8).trim(')', 'right').split(',');
        Fixed_OpenTime.pop();
        Fixed_OpenTime =Fixed_OpenTime.join(',');
        $('#Fixed_OpenTime').val(Fixed_OpenTime);
    }
    if(Delay_OpenTime!=''){
        Delay_OpenTime = Delay_OpenTime.substring(8).trim(')', 'right').split(',');
        $('#OPS_OpenTime1').val(Delay_OpenTime[0]);
        $('#OPS_OpenTime2').val(Delay_OpenTime[1]);
        $('#OPS_OpenTime3').val(Delay_OpenTime[2]);
    }
    if(Fixed_EndTime!=''){
        Fixed_EndTime = Fixed_EndTime.substring(8).trim(')', 'right').split(',');
        Fixed_EndTime.pop();
        Fixed_EndTime =Fixed_EndTime.join(',');
        $('#Fixed_EndTime').val(Fixed_EndTime);
    }
    if(Delay_EndTime!=''){
        Delay_EndTime = Delay_EndTime.substring(8).trim(')', 'right').split(',');
        $('#OPS_EndTime1').val(Delay_EndTime[0]);
        $('#OPS_EndTime2').val(Delay_EndTime[1]);
        $('#OPS_EndTime3').val(Delay_EndTime[2]);
    }
}

function calendar1(minView, id1, id2) {
    var format = 'yyyy,m,d,h,i,s';
    $(id1).datetimepicker({
        format: format,
        minView: minView,
        language: 'zh-CN',
        autoclose: true,
        clearBtn: true,
        weekStart: 1
    }).on("click", function () {
        $(id1).datetimepicker("setEndDate", $(id2).val());
    });
    $(id2).datetimepicker({
        format: format,
        minView: minView,
        language: 'zh-CN',
        autoclose: true,
        clearBtn: true,
        weekStart: 1
    }).on("click", function () {
        $(id2).datetimepicker("setStartDate", $(id1).val());
    });
}
//下拉框选项核心函数
function jinSelect(obj) {
    var dom = isExist(obj.dom);
    var width = isExist(obj.width, "150px");
    var id = isExist(obj.id);
    var text = isExist(obj.text);
    var data = isExist(obj.data);
    var val = isExist(obj.val, isExist($.cookie('s_' + dom), 0));//默认值
    var multiple = isExist(obj.multiple, false);//默认单选
    var placeholder = isExist(obj.placeholder, '请选择');//默认值
    $.ajax({
        type: "POST",
        url: obj.url,
        dataType: 'json',
        data: data,
        success: function (res) {
            //console.log(res);
            var arr = [];
            // if (id === '' || text === '') {
            //     arr[0] = {//索引版配置
            //         id: -1,
            //         text: '全部'
            //     }
            // } else {
            //     arr[0] = {//关联数组版配置
            //         id: -1,
            //         text: '全部'
            //     }
            // }
            for (var i = 0; i < res.length; i++) {
                if (id === '' || text === '') {
                    arr[i] = {//索引版配置
                        id: i,
                        text: res[i]
                    }
                } else {
                    arr[i] = {//关联数组版配置
                        id: res[i][id],
                        text: res[i][text]
                    }
                }
            }
            $(dom).select2({
                data: arr,
                placeholder: placeholder,
                theme: "classic",
                width: width,
                multiple: multiple
            }).val(val).trigger('change');
        }
    });
    $(dom).on('change', function () {
        //设置选项cookie
        var val = $(dom).val();
        $.cookie('s_' + dom, val, {expires: 30});
    });
}

// 初始化并填充奖励类型的下拉选择框
function extendedItemsTag(obj) {
    var ii = arguments[1] ? arguments[1] : 1;
    var getItems_arr = [];
    for (var i = ii; i <= obj.num; i++) {
        getItems_arr.push("#item_id" + i);
        $("#reward_type_son" + i).html(`
            <div id="reward_type_son_son1${i}">
                <select id="item_id${i}" class="selectpicker" data-live-search="true" data-actions-box="true" title="请选择道具">
                    ${obj.options.map(option => `<option value="${option.val}">${option.coin}</option>`).join('')}
                </select>
            </div>
            <div id="reward_type_son_son2${i}" style="display: none;">
                <select id="money_id${i}" style="width: 180px; height: 32px; line-height: 32px;">
                    ${obj.options.map(option => `<option value="${option.val}">${option.coin}</option>`).join('')}
                </select>
            </div>
            <div id="reward_type_son_son3${i}" style="display: none;">
                <select id="fashion_id${i}" class="selectpicker" data-live-search="true" data-actions-box="true" title="请选择时装"></select>
            </div>
            <div id="reward_type_son_son4${i}" style="display: none;">
                <select id="pet_id${i}" class="selectpicker" data-live-search="true" data-actions-box="true" title="请选择宠物"></select>
            </div>
        `);
        // 为每个下拉框添加事件监听和数据填充逻辑
        (function (i) {
            $("#reward_type" + i).change(function () {
                switch ($(this).val()) {
                    case '[奖励道具]':
                        $("#reward_type_son_son1" + i).show();
                        $("#reward_type_son_son2" + i).hide();
                        $("#reward_type_son_son3" + i).hide();
                        $("#reward_type_son_son4" + i).hide();
                        rewardSetNum(i, true);
                        break;
                    case '[奖励货币]':
                        $("#reward_type_son_son2" + i).show();
                        $("#reward_type_son_son1" + i).hide();
                        $("#reward_type_son_son3" + i).hide();
                        $("#reward_type_son_son4" + i).hide();
                        rewardSetNum(i, true);
                        break;
                    case '[奖励时装]':
                        fetchData("#fashion_id" + i, 1);
                        $("#reward_type_son_son3" + i).show();
                        $("#reward_type_son_son1" + i).hide();
                        $("#reward_type_son_son2" + i).hide();
                        $("#reward_type_son_son4" + i).hide();
                        rewardSetNum(i, false, 1);
                        break;
                    case '[奖励宠物]':
                        fetchData("#pet_id" + i, 2);
                        $("#reward_type_son_son4" + i).show();
                        $("#reward_type_son_son1" + i).hide();
                        $("#reward_type_son_son2" + i).hide();
                        $("#reward_type_son_son3" + i).hide();
                        rewardSetNum(i, false, 1);
                        break;
                }
            });
        })(i);
    }
    getItems(getItems_arr, getSa);
}

// 获取奖励信息
function fetchData(selector, type) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: `?p=Admin&c=Active&a=payGift&jinIf=929&type=${type}`,
            method: 'GET',
            success: function (response) {
                var data = response;
                if (typeof response === 'string') {
                    data = JSON.parse(response);
                }
                $(selector).empty();
                data.forEach(function (item) {
                    $(selector).append($('<option>', {
                        value: item.send_id,
                        text: item.send_name
                    }));
                });
                $(selector).selectpicker('refresh');
                resolve(selector);
            },
            error: function (error) {
                console.error(`请求数据失败, 类型: ${type}`, error);
                reject(error);
            }
        });
    });
}

// 根据奖励类型设置数量输入框的值和可编辑状态
function rewardSetNum(i, editable, value = '') {
    $("#item_num" + i).val(value);
    $("#item_num" + i).attr('disabled', !editable);
}