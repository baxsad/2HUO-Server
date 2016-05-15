<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 5/6/16
 * Time: 11:39 上午
 */

require("../db.class.php");

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
);

if($_SERVER['REQUEST_METHOD']!="POST"){echo json_encode($json); return;};

$uid             = isset($_POST['uid'])?$_POST['uid']:'';
$aid             = isset($_POST['aid'])?$_POST['aid']:'';

if($aid == '' || $uid == '')
{
    $json['error'] = 1;
    $json['message'] = '参数错误';
    echo json_encode($json); return;
}
else
{
    $r = $DB->runSql("delete from address where aid = $aid AND uid = $uid");
    if($r)
    {

        $result = $DB->getLine("select count(*) as num_rows,aid from address WHERE uid = $uid");
        $count = $result['num_rows'];
        $r_aid = $result['aid'];
        if($count == 1)
        {
            $up = $DB->runSql("update address set defaultAddress = 1 WHERE uid = $uid and aid = $r_aid");
        }

        $json['error'] = 0;
        $json['message'] = '成功';
        echo json_encode($json);
    }
    else
    {
        $json['error'] = -1;
        $json['message'] = '失败';
        echo json_encode($json); return;
    }
}