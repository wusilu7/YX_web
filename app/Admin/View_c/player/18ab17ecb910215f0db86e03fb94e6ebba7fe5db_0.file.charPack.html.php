<?php
/* Smarty version 3.1.30, created on 2024-01-20 14:53:51
  from "C:\Users\Administrator\Desktop\pro\WebSiteYiXing\app\Admin\View\player\charPack.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_65ab6dff907625_10128001',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '18ab17ecb910215f0db86e03fb94e6ebba7fe5db' => 
    array (
      0 => 'C:\\Users\\Administrator\\Desktop\\pro\\WebSiteYiXing\\app\\Admin\\View\\player\\charPack.html',
      1 => 1704262933,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_65ab6dff907625_10128001 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<link href="<?php echo CSS;?>
jin/3.25.charPack.css" rel="stylesheet">
<div class="jin-content-title"><span>背包&装备查询</span></div>
<div class="alert alert-info">
    <div id="group_server"></div>
</div>
<!--查询div-->
<hr/>
<div class="jin-search-div">
    <div>
        <label class="control-label">
            <select id="char_type">
                <option value="1">角色名</option>
                <option value="2">角色ID</option>
            </select>
        </label>
        <input id="char" type="text" class="form-control jin-search-input" placeholder="请按所选查找类型填写">
        <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
    </div>
</div>
<hr/>
<!--导航区-->
<ul class="nav nav-tabs">
    <li class="active"><a href="#base_info" data-toggle="tab">基础信息</a></li>
    <li><a href="#equip_info" data-toggle="tab">装备信息</a></li>
    <li><a href="#bag_info" data-toggle="tab">背包信息</a></li>
    <li><a href="#fashion_info" data-toggle="tab">时装信息</a></li>
    <li><a href="#baby_info" data-toggle="tab">宠物信息</a></li>
    <li><a href="#talent_info" data-toggle="tab">天赋信息</a></li>
</ul>
<!-- 面板区 -->
<div class="tab-content">
    <!--基础信息-->
    <div class="tab-pane active" id="base_info">
        <!--主输入区-->
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th>帐号</th>
                    <th>角色ID</th>
                    <th>角色名</th>
                    <th>体力</th>
                    <th>珍珠</th>
                </tr>
                </thead>
                <tbody id="content_base"></tbody>
            </table>
        </div>
    </div>
    <!--装备信息-->
    <div class="tab-pane" id="equip_info">
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th class="jin-pack-column1">GUID</th>
                    <th>装备ID</th>
                    <th>装备名称</th>
                    <th>宝石槽位</th>
                    <th>数量</th>
                    <th>星级</th>
                    <th>背包索引</th>
                </tr>
                </thead>
                <tbody id="content_equip"></tbody>
            </table>
        </div>
    </div>
    <!--背包信息-->
    <div class="tab-pane" id="bag_info">
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th class="jin-pack-column1">GUID</th>
                    <th>物品ID</th>
                    <th>物品名称</th>
                    <th>数量</th>
                    <th>星级</th>
                    <th>背包索引</th>
                    <th>操作</th>
                </tr>
                </thead>
                <tbody id="content_bag"></tbody>
            </table>
        </div>
        <div id="page_q"></div>
    </div>
    <div class="tab-pane" id="fashion_info">
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th>时装ID</th>
                    <th>等级</th>
                </tr>
                </thead>
                <tbody id="content_fashion_info"></tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane" id="baby_info">
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th>宠物ID</th>
                    <th>是否上阵</th>
                    <th>等级</th>
                    <th>能力</th>
                    <th>觉醒</th>
                </tr>
                </thead>
                <tbody id="content_baby_info"></tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane" id="talent_info">
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center">
                <thead>
                <tr>
                    <th>天赋ID</th>
                    <th>等级</th>
                </tr>
                </thead>
                <tbody id="content_talent_info"></tbody>
            </table>
        </div>
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>


<?php echo '<script'; ?>
>
    gsSelect('#group', '#server');
    var data = {};
    function charPack(data) {
        var c = '';
        $.ajax({
            type: "post",
            url: location.href + "&jinIf=931",
            data: data,
            dataType: "json",
            beforeSend: function () {
                layer.load(2, {
                    shade: [0.3, '#fff']//0.3透明度的白色背景
                });
            },
            success: function (json) {
                layer.closeAll('loading');
                switch (json) {
                    case -1:
                        layer.alert('角色不存在，请重新输入');
                        break;
                    default:
                        var base_info = json.base_info;
                        var equip_info = json.equip_info;
                        var bag_info = json.bag_info;
                        var fashion_info = json.fashion_info;
                        var baby_info = json.baby_info;
                        var talent_info = json.talent_info;
                        //基础信息
                        var c = '';
                        c += '<tr>';
                        c += '<td>' + base_info.acc_name + '</td>';
                        c += '<td>' + base_info.char_guid + '</td>';
                        c += '<td>' + base_info.char_name + '</td>';
                        c += '<td>' + base_info.gold + '</td>';
                        c += '<td>' + base_info.money + '</td>';
//                        c += '<td>' + base_info.bind_money + '</td>';
                        c += '</tr>';
                        $('#content_base').html(c);
                        //装备信息
                        c = '';
                        for (var i = 0; i < equip_info.length; i++) {
                            c += '<tr>';
                            c += '<td>' + equip_info[i].item_guid + '</td>';
                            c += '<td>' + equip_info[i].item_id + '</td>';
                            c += '<td>' + equip_info[i].item_name + '</td>';
                            c += '<td>' + equip_info[i].slot1 + '<br>'+equip_info[i].slot2+'<br>'+equip_info[i].slot3+'</td>';
                            c += '<td>' + equip_info[i].item_count + '</td>';
                            c += '<td>' + equip_info[i].star + '</td>';
                            c += '<td>' + equip_info[i].index + '</td>';
                            c += '</tr>';
                        }
                        $('#content_equip').html(c);
                        //背包信息
                        c = '';
                        for (i = 0; i < bag_info.length; i++) {
                            c += '<tr>';
                            c += '<td>' + bag_info[i].item_guid + '</td>';
                            c += '<td>' + bag_info[i].item_id + '</td>';
                            c += '<td>' + bag_info[i].item_name + '</td>';
                            c += '<td>' + bag_info[i].item_count + '</td>';
                            c += '<td>' + bag_info[i].star + '</td>';
                            c += '<td>' + bag_info[i].index + '</td>';
                            c += '<td><a data-type="delete" class="btn btn-danger">移除</a></td>';
                            c += '</tr>';
                        }
                        $('#content_bag').html(c);
                        //时装信息
                        c = '';
                        for (i = 0; i < fashion_info.length; i++) {
                            c += '<tr>';
                            c += '<td>' + fashion_info[i].fashion_id + '</td>';
                            c += '<td>' + fashion_info[i].level + '</td>';
                            c += '</tr>';
                        }
                        $('#content_fashion_info').html(c);
                        //宠物信息
                        c = '';
                        for (i = 0; i < baby_info.length; i++) {
                            c += '<tr>';
                            c += '<td>' + baby_info[i].babyid + '</td>';
                            c += '<td>' + baby_info[i].isuse + '</td>';
                            c += '<td>' + baby_info[i].level1 + '</td>';
                            c += '<td>' + baby_info[i].level2 + '</td>';
                            c += '<td>' + baby_info[i].level3 + '</td>';
                            c += '</tr>';
                        }
                        $('#content_baby_info').html(c);
                        //天赋信息
                        c = '';
                        for (i = 0; i < talent_info.length; i++) {
                            c += '<tr>';
                            c += '<td>' + talent_info[i].talentid + '</td>';
                            c += '<td>' + talent_info[i].level + '</td>';
                            c += '</tr>';
                        }
                        $('#content_talent_info').html(c);
                        break;
                }

            },
            error: function () {
                layer.closeAll('loading');
                layer.msg('数据获取失败，请勿频繁刷新');
            }
        });
    }
    $('#content_bag').on('click', 'a[data-type="delete"]', function () {
        var bag_index = $(this).parents('tr').find('td').eq(5).text();
        layer.alert('确认操作吗？', {icon: 0, shadeClose: true, btn: ['确定', '取消']}, function () {
            $.ajax({
                type: "POST",
                url: location.href + "&jinIf=941",
                data: {
                    si: $("#server").val(),
                    char_type: $("#char_type").val(),
                    char: $("#char").val(),
                    bag_index:bag_index
                },
                success: function (json) {
                    if (json==1){
                        layer.alert('删除成功', {icon: 1}, function (index) {
                            layer.close(index);
                            charPack(data);
                        });
                    }else{
                        layer.alert('删除失败或没权限', {icon: 2}, function (index) {
                            layer.close(index);
                            charPack(data);
                        });
                    }

                }
            });
        });
    });


    $("#jin_search").on('click', function () {
        data.si = $("#server").val();
        data.char_type = $("#char_type").val();//查找类型
        data.char = $("#char").val();//角色
        charPack(data);
    });
<?php echo '</script'; ?>
><?php }
}
