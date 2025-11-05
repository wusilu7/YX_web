<?php
/* Smarty version 3.1.30, created on 2024-09-03 11:03:57
  from "D:\phpStudy\PHPTutorial\WWW\WebSiteYiXing\app\Admin\View\active\updateExpeditionPass.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_66d67c9db1e175_51228193',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '99fa83d7bcaded4e786837e314d15d6de2d50fb0' => 
    array (
      0 => 'D:\\phpStudy\\PHPTutorial\\WWW\\WebSiteYiXing\\app\\Admin\\View\\active\\updateExpeditionPass.html',
      1 => 1725332619,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_66d67c9db1e175_51228193 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<!--|↓↓↓↓↓↓|-->
<link href="<?php echo CSS;?>
jin/3.07.sa.css" rel="stylesheet">
<div class="jin-content-title"><span>远征通行证修改</span></div>
<hr/>
<div class="form-horizontal col-sm-6 col-sm-offset-3">
    <div class="form-group">
        <label for="ID" class="col-sm-2 control-label">编号</label>
        <div class="col-sm-10">
            <input id="ID" class="form-control" readonly/>
        </div>
    </div>
    <div class="form-group">
        <label for="OpenDate" class="col-sm-2 control-label">开放日期</label>
        <div class="col-sm-10">
            <input id="OpenDate" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="OpenTime" class="col-sm-2 control-label">开放时间</label>
        <div class="col-sm-10">
            <input id="OpenTime" class="form-control"/>
        </div>
    </div>
    <div class="form-group">
        <label for="Duration" class="col-sm-2 control-label">持续时间（天）</label>
        <div class="col-sm-10">
            <input id="Duration" class="form-control"/>
        </div>
    </div>
    <div class="btn-group center jin-sa-btn">
        <button data-type="update" class="btn  btn-success">修改</button>
    </div>
</div>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 type="text/javascript">
    // 用于保存日期前缀
    var datePrefix = '';

    $(function () {
        // 创建开放日期选择器
        createMonthDayPicker("#OpenDate");

        // 创建开放时间选择器
        createTimePicker("#OpenTime");

        // 渲染修改数据
        loadUpdateData();

        // 提交修改
        $(".btn-group .btn-success").click(function () {
            submitUpdateData();
        });
    });

    /* 渲染修改数据 */
    function loadUpdateData() {
        $.ajax({
            type: "post",
            url: `${location.href}&jinIf=920`,
            dataType: "json",
            success: function (responseData) {
                // 设置ID
                $("#ID").val(responseData.ID);
                $("#ID").attr('data-data-gi', responseData.gi);
                $("#ID").attr('data-data-sign', responseData.gi_sign);

                // 分离日期前缀（如[每年活动]）并保存
                var openDate = responseData.OpenDate;
                var prefixMatch = openDate.match(/^\[.*?\]/);
                if (prefixMatch) {
                    datePrefix = prefixMatch[0];
                    openDate = openDate.replace(datePrefix, '');
                } else {
                    datePrefix = '';
                }

                // 将OpenDate从原始格式转换为日期选择器格式并赋值
                var formattedDate = convertOpenDate(openDate);
                $('#OpenDate').val(formattedDate);

                // 将OpenTime从原始格式转换为时间选择器格式并赋值
                var formattedTime = convertOpenTime(responseData.OpenTime);
                $('#OpenTime').val(formattedTime);

                // 设置持续时间
                var durationInDays = Math.floor(responseData.Duration / 86400);
                $('#Duration').val(durationInDays);
            }
        });
    }

    /* 格式化开放日期 */
    function convertOpenDate(openDate, toPickerFormat = true) {
        if (toPickerFormat) {
            var matches = openDate.match(/\((\d+),(\d+)\)/);
            if (matches) {
                var month = parseInt(matches[1], 10) + 1;
                var day = matches[2];
                month = month < 10 ? '0' + month : month;
                day = day < 10 ? '0' + day : day;
                return month + '-' + day;
            }
            return '';
        } else {
            var parts = openDate.split('-');
            if (parts.length === 2) {
                var month = parseInt(parts[0], 10) - 1;
                var day = parseInt(parts[1], 10);
                return `(${month},${day})`;
            }
            return '';
        }
    }

    /* 格式化开放时间 */
    function convertOpenTime(openTime, toPickerFormat = true) {
        if (toPickerFormat) {
            // 将原始格式 "(hh,mm);" 转换为时间选择器格式 "hh:mm"
            return openTime.replace(/[()]/g, '').replace(';', '').replace(',', ':');
        } else {
            // 将时间选择器格式 "hh:mm" 转换为原始格式 "(hh,mm);"
            return '(' + openTime.replace(':', ',') + ');';
        }
    }

    /* 提交修改数据 */
    function submitUpdateData() {
        var id = $("#ID").val();
        var gi = $("#ID").attr('data-data-gi');
        var sign = $("#ID").attr('data-data-sign');
        var openDate = $('#OpenDate').val();
        var openTime = $('#OpenTime').val();
        var duration = $('#Duration').val();

        // 将OpenDate转换回原始格式，并重新拼接上保存的前缀
        var originalOpenDate = convertOpenDate(openDate, false);
        if (datePrefix) {
            originalOpenDate = datePrefix + originalOpenDate;
        }

        var originalOpenTime = convertOpenTime(openTime, false);

        // 持续时间转换为秒数
        var durationInSeconds = parseInt(duration, 10) * 86400;

        $.ajax({
            type: "POST",
            url: location.href + "&jinIf=913",
            dataType: "json",
            data: {
                ID: id,
                gi: gi,
                sign: sign,
                OpenDate: originalOpenDate,
                OpenTime: originalOpenTime,
                Duration: durationInSeconds
            },
            beforeSend: function () {
                layer.load(2, {shade: [0.3, '#fff']});
            },
            success: function (json) {
                layer.closeAll('loading');
                layer.alert('成功', {icon: 1}, function (index) {
                    layer.close(index);
                    window.location.reload();
                });
            }
        });
    }
<?php echo '</script'; ?>
>
<?php }
}
