<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 5/6/16
 * Time: 11:49 上午
 */

require("../db.class.php");

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
);

if($_SERVER['REQUEST_METHOD']!="POST"){echo json_encode($json); return;};

$aid             = isset($_POST['aid'])?$_POST['aid']:'';
$uid             = isset($_POST['uid'])?$_POST['uid']:'';
$sid             = isset($_POST['sid'])?$_POST['sid']:'';
$name            = isset($_POST['name'])?$_POST['name']:'';
$phone           = isset($_POST['phone'])?$_POST['phone']:'';
$location        = isset($_POST['location'])?$_POST['location']:'';
$numberCard      = isset($_POST['numberCard'])?$_POST['numberCard']:'';
$defaultAddress  = isset($_POST['defaultAddress'])?$_POST['defaultAddress']:'';

if($defaultAddress != '')
{
    if($aid == ''){$json['error'] = 1;$json['message'] = '参数错误';echo json_encode($json); return;};
    $DB -> runSql("update address set defaultAddress = 0 WHERE uid = $uid AND defaultAddress = 1");
    $up = $DB->runSql("update address set defaultAddress = 1 where aid = $aid and uid=$uid");
    if($up)
    {
        $json['error'] = 0;
        $json['message'] = '成功';
        echo json_encode($json); return;
    }
    else
    {
        $json['error'] = -1;
        $json['message'] = '失败';
        echo json_encode($json); return;
    }
}
else
{
    if($aid == '' || $uid == '' || $sid == '' || $name == '' || $phone == '' || $location == '' || $numberCard == '')
    {
        $json['error'] = 1;
        $json['message'] = '参数错误';
        echo json_encode($json); return;
    }
    else
    {
        $up = $DB->runSql("update address set uid = $uid,sid=$sid,userName='$name',phone='$phone',location='$location',numberCard='$numberCard' where aid=$aid");
        if($up)
        {
            $json['error'] = 0;
            $json['message'] = '成功';
            echo json_encode($json); return;
        }
        else
        {
            $json['error'] = -1;
            $json['message'] = '失败';
            echo json_encode($json); return;
        }
    }
}