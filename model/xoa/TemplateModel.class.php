<?php

namespace Model\Xoa;

class TemplateModel extends XoaModel
{
    //提取模板下拉框
    function selectTemplate($type)
    {
        $sql = "select id,temp_title from template where temp_type=?";
        $res = $this->go($sql, 'sa', $type);
        return $res;
    }

    //保存模板
    function insertTemplate($type)
    {
        $temp_title = POST('temp_title');
        $temp_info = json_encode($_POST);
        $sql = "insert into template(temp_type,temp_title,temp_info) values(?,?,?)";
        return $this->go($sql, 'i', [$type, $temp_title, $temp_info]);
    }

    //删除模板
    function deleteTemplate()
    {
        $id = POST('id');
        $sql = "delete from template where id=?";
        return $this->go($sql, 'd', $id);
    }

    // 读取模板内容
    function selectTemplateInfo()
    {
        $sql = "select temp_info from template where id=?";
        return implode($this->go($sql, 's', POST('id')));//这是个json串
    }
}