<?php

namespace Model\Xoa;

class ChangelogModel extends XoaModel
{
    //更新日志查询
    function selectChangelog()
    {
        $sql = "select * from changelog order by c_id desc";
        $res = $this->go($sql, 'sa');
        foreach ($res as &$r) {
            $r['content'] = explode('|', $r['content']);
        }
        return $res;
    }

    //版本号显示
    function selectVersion()
    {
        $sql = "select version from changelog order by c_id desc";
        $res = $this->go($sql, 's');
        define('VERSION', implode($res));
        define('HTML_VERSION', '?_v=' . VERSION);
    }
}