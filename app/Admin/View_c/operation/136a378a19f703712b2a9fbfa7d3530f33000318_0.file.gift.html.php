<?php
/* Smarty version 3.1.30, created on 2024-04-24 18:13:05
  from "D:\pro\WebSiteYiXing\app\Admin\View\operation\gift.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6628db31ab15c9_84268566',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '136a378a19f703712b2a9fbfa7d3530f33000318' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\operation\\gift.html',
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
function content_6628db31ab15c9_84268566 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.21.gift.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>礼包配置</span></div>

<ul class="nav nav-tabs">
    <li class="active"><a href="#gift_send" data-toggle="tab">礼包生成</a></li>
    <li><a href="#gift_query" data-toggle="tab">礼包查询</a></li>
</ul>
<div class="tab-content">
    <!--礼包发送-->
    <div class="tab-pane active" id="gift_send">
        <!--主输入区-->
        <div class="form-horizontal">
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label ">礼包名称</label>
                <div class="col-sm-10">
                    <input id="title" maxlength="20" class="form-control" placeholder="礼包名称，即玩家收到激活码邮件的标题"/>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-sm-2 control-label ">礼包说明</label>
                <div class="col-sm-10">
            <textarea id="content" maxlength="200" class="form-control" rows="6"
                      placeholder="对该礼包的简单说明，即玩家收到激活码邮件的内容"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-sm-2 control-label jin-gift-gray">货币</label>
                <div class="col-sm-10">
                    <hr/>
                </div>
            </div>
            <div class="form-group">
                <label for="money_type1" class="col-sm-2 control-label">货币类型 ①</label>
                <div class="col-sm-3">
                    <select id="money_type1">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="money_num1" class="col-sm-2 control-label ">货币数量 ①</label>
                <div class="col-sm-10">
                    <input id="money_num1" class="form-control" placeholder="货币数量"/>
                </div>
            </div>
            <div class="form-group">
                <label for="money_type2" class="col-sm-2 control-label">货币类型 ②</label>
                <div class="col-sm-3">
                    <select id="money_type2">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="money_num2" class="col-sm-2 control-label ">货币数量 ②</label>
                <div class="col-sm-10">
                    <input id="money_num2" class="form-control" placeholder="货币数量"/>
                </div>
            </div>
            <div class="form-group">
                <label for="money_type3" class="col-sm-2 control-label">货币类型 ③</label>
                <div class="col-sm-3">
                    <select id="money_type3">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="money_num3" class="col-sm-2 control-label ">货币数量 ③</label>
                <div class="col-sm-10">
                    <input id="money_num3" class="form-control" placeholder="货币数量"/>
                </div>
            </div>
            <div class="form-group">
                <label for="money_typ4" class="col-sm-2 control-label">货币类型 ④</label>
                <div class="col-sm-3">
                    <select id="money_type4">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="money_num4" class="col-sm-2 control-label ">货币数量 ④</label>
                <div class="col-sm-10">
                    <input id="money_num4" class="form-control" placeholder="货币数量"/>
                </div>
            </div>
            <div class="form-group">
                <label for="money_type5" class="col-sm-2 control-label">货币类型 ⑤</label>
                <div class="col-sm-3">
                    <select id="money_type5">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="money_num5" class="col-sm-2 control-label ">货币数量 ⑤</label>
                <div class="col-sm-10">
                    <input id="money_num5" class="form-control" placeholder="货币数量"/>
                </div>
            </div>
            <div class="form-group">
                <label for="money_type6" class="col-sm-2 control-label">货币类型 ⑥</label>
                <div class="col-sm-3">
                    <select id="money_type6">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="money_num6" class="col-sm-2 control-label ">货币数量 ⑥</label>
                <div class="col-sm-10">
                    <input id="money_num6" class="form-control" placeholder="货币数量"/>
                </div>
            </div>
            <div class="form-group">
                <label for="content" class="col-sm-2 control-label jin-gift-gray">道具</label>
                <div class="col-sm-10">
                    <hr/>
                </div>
            </div>
            <div class="form-group">
                <label for="item_type1" class="col-sm-2 control-label ">道具id ①</label>
                <div class="col-sm-10">
                    <select id="item_type1"  class="selectpicker"  data-live-search="true" data-actions-box="true" title="请选择道具"></select>
                </div>
            </div>
            <div class="form-group">
                <label for="item_num1" class="col-sm-2 control-label ">道具数量 ①</label>
                <div class="col-sm-10">
                    <input id="item_num1" class="form-control" placeholder="①"/>
                </div>
            </div>
            <div class="form-group">
                <label for="item_type2" class="col-sm-2 control-label ">道具id ②</label>
                <div class="col-sm-10">
                    <select id="item_type2"  class="selectpicker"  data-live-search="true" data-actions-box="true" title="请选择道具"></select>
                </div>
            </div>
            <div class="form-group">
                <label for="item_num2" class="col-sm-2 control-label ">道具数量 ②</label>
                <div class="col-sm-10">
                    <input id="item_num2" class="form-control" placeholder="②"/>
                </div>
            </div>
            <div class="form-group">
                <label for="item_type3" class="col-sm-2 control-label ">道具id ③</label>
                <div class="col-sm-10">
                    <select id="item_type3"  class="selectpicker"  data-live-search="true" data-actions-box="true" title="请选择道具"></select>
                </div>
            </div>
            <div class="form-group">
                <label for="item_num3" class="col-sm-2 control-label ">道具数量 ③</label>
                <div class="col-sm-10">
                    <input id="item_num3" class="form-control" placeholder="③"/>
                </div>
            </div>
            <div class="form-group">
                <label for="item_type4" class="col-sm-2 control-label ">道具id ④</label>
                <div class="col-sm-10">
                    <select id="item_type4"  class="selectpicker"  data-live-search="true" data-actions-box="true" title="请选择道具"></select>
                </div>
            </div>
            <div class="form-group">
                <label for="item_num4" class="col-sm-2 control-label ">道具数量 ④</label>
                <div class="col-sm-10">
                    <input id="item_num4" class="form-control" placeholder="④"/>
                </div>
            </div>
            <div class="form-group">
                <label for="item_type5" class="col-sm-2 control-label ">道具id ⑤</label>
                <div class="col-sm-10">
                    <select id="item_type5"  class="selectpicker"  data-live-search="true" data-actions-box="true" title="请选择道具"></select>
                </div>
            </div>
            <div class="form-group">
                <label for="item_num5" class="col-sm-2 control-label ">道具数量 ⑤</label>
                <div class="col-sm-10">
                    <input id="item_num5" class="form-control" placeholder="⑤"/>
                </div>
            </div>
            <!--发送-->
            <div class="center">
                <button id="send" class="btn btn-primary">生成礼包</button>
            </div>
        </div>
    </div>
    <!--礼包查询-->
    <div class="tab-pane" id="gift_query">
        <div class="table-responsive">
            <table class="table table-hover text-center">
                <thead>
                <tr>
                    <th>礼包编号</th>
                    <th>礼包名称</th>
                    <th>礼包说明</th>
                    <th>货币</th>
                    <th>道具</th>
                    <th>创建人</th>
                    <th>创建时间</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody id="content_q"></tbody>
            </table>
        </div>
        <div id="page_q"></div>
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
>
    $(document).ready(gift());
    $(function () {
        getItems(["#item_type1","#item_type2","#item_type3","#item_type4","#item_type5"],'');
    });
    function gift() {
        var obj1 = {}, obj2 = {}, obj3 = {}, obj4 = {}, obj5 = {}, obj6 = {};
        obj1.dom = "#money_type1";
        obj2.dom = "#money_type2";
        obj3.dom = "#money_type3";
        obj4.dom = "#money_type4";
        obj5.dom = "#money_type5";
        obj6.dom = "#money_type6";
        moneySelect(obj1);
        moneySelect(obj2);
        moneySelect(obj3);
        moneySelect(obj4);
        moneySelect(obj5);
        moneySelect(obj6);
        $("#send").on('click', function () {
            var money = '';
            var mt1 = $("#money_type1").val();
            var mn1 = $("#money_num1").val();
            var mt2 = $("#money_type2").val();
            var mn2 = $("#money_num2").val();
            var mt3 = $("#money_type3").val();
            var mn3 = $("#money_num3").val();
            var mt4 = $("#money_type4").val();
            var mn4 = $("#money_num4").val();
            var mt5 = $("#money_type5").val();
            var mn5 = $("#money_num5").val();
            var mt6 = $("#money_type6").val();
            var mn6 = $("#money_num6").val();
            if (mt1 && mn1) {
                money += mt1 + '#' + mn1 + ';';
            }
            if (mt2 && mn2) {
                money += mt2 + '#' + mn2 + ';';
            }
            if (mt3 && mn3) {
                money += mt3 + '#' + mn3 + ';';
            }
            if (mt4 && mn4) {
                money += mt4 + '#' + mn4 + ';';
            }
            if (mt5 && mn5) {
                money += mt5 + '#' + mn5 + ';';
            }
            if (mt6 && mn6) {
                money += mt6 + '#' + mn6 + ';';
            }
            var item = '';
            var it1 = $("#item_type1").val();
            var in1 = $("#item_num1").val();
            var it2 = $("#item_type2").val();
            var in2 = $("#item_num2").val();
            var it3 = $("#item_type3").val();
            var in3 = $("#item_num3").val();
            var it4 = $("#item_type4").val();
            var in4 = $("#item_num4").val();
            var it5 = $("#item_type5").val();
            var in5 = $("#item_num5").val();
            if (it1 && in1) {
                item += it1 + '#' + in1 + ';';
            }
            if (it2 && in2) {
                item += it2 + '#' + in2 + ';';
            }
            if (it3 && in3) {
                item += it3 + '#' + in3 + ';';
            }
            if (it4 && in4) {
                item += it4 + '#' + in4 + ';';
            }
            if (it5 && in5) {
                item += it5 + '#' + in5 + ';';
            }
            var receiver = $("#receiver").val();
            var data = {
                title: $("#title").val(),
                content: $("#content").val(),
                money: money,
                item: item
            };
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=911",
                data: data,
                dataType: 'json',
                success: function (res) {
                    layer.alert("礼包生成成功，编号为" + res);
                },
                error: function () {
                }
            });
        });
    }

    //切换页面
    $('ul').on('click', 'a[href="#gift_query"]', function () {
        jsonQuery();
    });
    //切换下拉选项
    function act() {
        if ($("#gift_query").hasClass("active")) {
            jsonQuery();
        }
    }
    $("#group").on('change', function () {
        act();
    });
    $("#type").on('change', function () {
        act();
    });

    function jsonQuery() {
        var url = location + "&jinIf=912";
        var btn = [
            '<a data-type="update" class="btn btn-primary">修改</a>'
        ];
        var arr = ['gift_id', 'title', 'content', 'money', 'item', 'cu', 'ct',btn];
        var id = ["#content_q", "#page_q"];
        var data = {
            page: 1,
            gi: $("#group").val(),
            type: $("#type").val()
        };
        $(document).ready(tableList(url, data, id, arr));
    }

    $('#content_q').on('click', 'a[data-type="update"]', function() {
        var id = $(this).parents('tr').find('td').eq(0).text();
        var title = $(this).parents('tr').find('td').eq(1).text();
        var content = $(this).parents('tr').find('td').eq(2).text();
        var money = $(this).parents('tr').find('td').eq(3).text();
        var item = $(this).parents('tr').find('td').eq(4).text();
        if(money==0){
            money='';
        }
        if(item==0){
            item='';
        }
        layer.open({
            type: 1,
            closeBtn: 2,
            title: '礼包修改',
            area: ['400px', '500px'],
            btn: ['修改', '取消'],
            btnAlign: 'c',
            shadeClose: true, //点击遮罩关闭
            content: '<div class="jin-child">' +
            '<div class="input-group"><span class="input-group-addon">礼包名</span><input id="title1" type="text" class="form-control" value="' +
            title + '"></div>' +
            '<div class="input-group"><span class="input-group-addon">礼包说明</span><textarea id="content1"  rows="10"  class="form-control">' +
            content + '</textarea></div>' +
            '<div class="input-group"><span class="input-group-addon">货币</span><textarea id="money1"  rows="2"  class="form-control">' +
            money + '</textarea></div>' +
            '<div class="input-group"><span class="input-group-addon">道具</span><textarea id="item1"  rows="2"  class="form-control">' +
            item + '</textarea></div>' +
            '</div>',
            yes: function (index) {
                $.ajax({
                    type: "POST",
                    url: location.href + '&jinIf=913',
                    data: {
                        id: id,
                        title: $('#title1').val(),
                        content: $('#content1').val(),
                        money: $('#money1').val(),
                        item: $('#item1').val()
                    },
                    success: function () {
                        layer.close(index);
                        layer.alert('修改成功', {icon: 1}, function (index1) {
                            layer.close(index1);
                            jsonQuery();
                        });
                    }
                });
            }
        });
        return false;
    })
<?php echo '</script'; ?>
><?php }
}
