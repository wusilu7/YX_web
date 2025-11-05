<?php

namespace I\Controller;

use Model\Xoa\GameverModel;

class GameverController extends IController
{
    function getVer_new()
    {
        if (GET('gi') != '') {
            $gm = new GameverModel;
            echo $gm->iVer_new();
        }
    }

    function hideIver(){
        $gm = new GameverModel;
        echo $gm->hideIver();
    }


    function selectVer(){
        if (GET('gi') != '') {
            $gm = new GameverModel;
            $arr= $gm->sVer();
            $c='';
            foreach ($arr as $k=> $a){
                $c.='<h4>版本号:' . $a['version'] . '</h4>';
                $c.='<h4>版本日期:' . $a['vdate'] . '</h4>';
                $c.='<div class="text">' . $a['content'] . '</div>';
            }
            echo '
 <!DOCTYPE html>
 <html>
   <head>
     <title>更新日志</title>
    <link href="/app/Admin/Public/css/bootstrap.css" rel="stylesheet">
    <link href="/app/Admin/Public/css/jin/3.00.content.css?_v=1.2.2.20171108" rel="stylesheet">
    <link href="/app/Admin/Public/css/jin/3.99.changelog.css" rel="stylesheet">
   </head>
   <body>
     <div class="jin-content-title"><span>更新日志</span></div>
<div class="col-sm-6 col-sm-offset-3">
    <div class="panel panel-default">
        <div class="panel-heading">更新日志</div>
        <div id="content" class="panel-body">'.$c.'
        </div>
    </div>
</div>
<!--接上面-->
</div>
   </body>
 </html>
 ';
        }
    }
}