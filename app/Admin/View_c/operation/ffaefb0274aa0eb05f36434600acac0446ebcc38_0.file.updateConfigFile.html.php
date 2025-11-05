<?php
/* Smarty version 3.1.30, created on 2024-05-07 16:31:14
  from "/lnmp/www/app/Admin/View/operation/updateConfigFile.html" */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.30',
  'unifunc' => 'content_6639e6d28f2cb8_65835568',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    'ffaefb0274aa0eb05f36434600acac0446ebcc38' => 
    array (
      0 => '/lnmp/www/app/Admin/View/operation/updateConfigFile.html',
      1 => 1715070568,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:../common/1header.html' => 1,
    'file:../common/2footer.html' => 1,
  ),
),false)) {
function content_6639e6d28f2cb8_65835568 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_subTemplateRender("file:../common/1header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<link href="<?php echo CSS;?>
jin/3.06.server.css" rel="stylesheet">
<style>
    /* bootstrapTable 工具栏 */
    .fixed-table-toolbar {
        float: left !important;
    }

    .search-input {
        border-radius: 5px !important;
    }
</style>
<!--|↓↓↓↓↓↓|-->
<div class="jin-content-title">
    <span>更新配置表</span>
</div>
<div class="jin-server-select"></div>
<div class="table-responsive">
    <table id="table"></table>
</div>
<hr>
<!--|↑↑↑↑↑↑|-->
<?php $_smarty_tpl->_subTemplateRender("file:../common/2footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<?php echo '<script'; ?>
 type="text/javascript">
    $(document).ready(function () {
        getConfigFile();
    });

    // 获取配置文件
    function getConfigFile() {
        $.ajax({
            type: "GET",
            url: location.href + "&jinIf=912",
            dataType: 'json',
            success: function (json) {
                $('#table').bootstrapTable({
                    data: json,
                    search: true,
                    columns: [
                        {
                            field: 'filename',
                            title: '文件名'
                        },
                        {
                            field: 'last_modified',
                            title: '更新时间'
                        },
                        {
                            field: 'operation',
                            title: '操作',
                            formatter: actionFormatter
                        }
                    ]
                });
            }
        });
    }

    // 格式化操作列
    function actionFormatter(value, row, index) {
        return `
            <div class="btn-group btn-group-sm">
                <a data-type="downloadConfig" href="${row.file_path}" class="btn btn-primary" download style="width: 80px;">下载</a>
            </div>
            <div class="btn-group btn-group-sm">
                <form id="uploadForm-${index}" enctype="multipart/form-data" style="display: inline-block; width: 180px; margin-top: 3px">
                    <input id="file-${index}" accept=".xls,.xlsx" type="file" name="file"/>
                </form>
                <a data-type="updateConfig" data-history="${row.filename}" data-index="${index}" class="btn btn-danger" style="width: 80px;">上传</a>
            </div>
        `;
    }

    // 上传
    $(document).on('click', 'a[data-type="updateConfig"]', function (e) {
        e.preventDefault();
        var $form = $(this).prev('form');
        var fileInput = $form.find('input[type="file"]')[0];
        // 检查是否选择文件
        if (fileInput.files.length === 0) {
            layer.msg('请选择文件后再上传！')
            return;
        }
        var uploadedFile = fileInput.files[0];
        var uploadedFilename = uploadedFile.name;
        var historyFileName = $(this).attr('data-history');
        // 检查上传的文件名是否与历史文件名一致
        if (uploadedFilename !== historyFileName) {
            layer.msg('上传的文件名与预期的文件名不一致！');
            return;
        }
        // 创建 FormData 对象，添加文件
        var formData = new FormData();
        formData.append('file', uploadedFile);
        // 禁用按钮防止重复提交
        $(this).prop('disabled', true).text('上传中...');
        $.ajax({
            type: 'POST',
            url: location.href + "&jinIf=913",
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function (response) {
                layer.msg(response.msg, {icon: response.code});
            },
            error: function (xhr, status, error) {
                layer.msg('上传失败：' + error, {icon: 2});
            },
            complete: function () {
                // 恢复按钮状态
                $('a[data-type="updateConfig"]').prop('disabled', false).text('上传');
                // 重新获取文件信息
                getConfigFile();
            }
        });
    });
<?php echo '</script'; ?>
><?php }
}
