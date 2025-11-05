<?php
/* Smarty version 3.1.30, created on 2024-04-24 18:12:48
  from "D:\pro\WebSiteYiXing\app\Admin\View\test\sqlSelect.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6628db200b5839_25221648',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '2413f6455da4c5e09c8bbc98b6a0cebf66772cb3' => 
    array (
      0 => 'D:\\pro\\WebSiteYiXing\\app\\Admin\\View\\test\\sqlSelect.html',
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
function content_6628db200b5839_25221648 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.26.sqlSelect.css" rel="stylesheet">
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title"><span>SQL语句查询</span></div>
<div class="alert alert-info">
    <div id="group_server"></div>
</div>
<!--查询div-->
<hr/>
<div class="jin-search-div">
    <input type="text" id="sql" class="form-control"
           placeholder="请填写SQL查询语句，仅可查询log数据库，如需查询另外两个库，请带上对应的数据库名前缀">
    <a id="jin_search" class="btn btn-success"><span class="glyphicon glyphicon-search"></span></a>
</div>
<hr/>
<div class="table-responsive jin-table-12px">
    <table id="content" class="table table-striped text-center jin-retention-table">
        <!--<thead>-->
        <!--<tr>-->
        <!--<th></th>-->
        <!--</tr>-->
        <!--</thead>-->
        <!--<tbody id="content"></tbody>-->
    </table>
</div>
<div id="page"></div>
<div class="jin-explain">
    <b>说明</b>：
    <div>
        ① 后台已自动拼接limit，所以SQL语句不需要再加limit；
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 type="text/javascript">
    gsSelect('#group', '#server');
    $("#jin_search").on('click', function () {
        if ($.trim($("#sql").val()) === '' || $.trim($("#server").val()) === '') {
            layer.alert("请输入SQL语句", {icon: 0});
        } else {
            get(1);
        }
    });
    function get(page) {
        var data = {
            gi: $("#group").val(),
            si: $("#server").val(),
            sql: $("#sql").val(),
            page: page
        };
        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=912",
            data: data,
            dataType: "json",
            success: function (json) {
                total = json[json.length - 1];
                var c = '';

                if (total > 0) {
                    c += '<thead>';
                    c += '<tr>';
                    for (var k in json[0]) {
                        c += '<th>' + k + '</th>';
                    }
                    c += '</tr>';
                    c += '</thead>';
                }
                for (var i = 0; i < json.length - 1; i++) {   //取数据填表
                    c += '<tr>';
                    for (var k in json[i]) {
                        c += '<td>' + json[i][k] + '</td>';
                    }
                    c += '</tr>';
                }
                $('#content').html(c);
                var p = pageList(total, data.page);
                $("#page").html(p).find("a[data-pn=" + data.page + "]").parent().addClass("active");
            },
            error: function () {
                layer.msg('数据库连接失败，请检查SQL语句是否正确');
                $('#content').html('');
                $("#page").html('');
            }
        });
    }
    $("#page").on('click', 'a', function () {   //为a标签动态绑定事件
        var page = $(this).attr("data-pn");  //获取链接里的页码
        switch (page) {
            case 'omit':
                break;
            case 'min':
                layer.msg('已经是第一页');
                break;
            case 'max':
                layer.msg('已经是最后一页');
                break;
            default:
                get(page);
                break;
        }
    }).on('click', 'button', function () {
        var reg = new RegExp("^[0-9]*$");//数字正则验证
        var page = $(this).parents("div:first").find("input").val();
        if (reg.test(page) && page !== '') {
            get(page);
        } else {
            layer.msg('请填写纯数字页码');
        }
    });
<?php echo '</script'; ?>
><?php }
}
