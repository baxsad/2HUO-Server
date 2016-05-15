<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 5/6/16
 * Time: 11:09 上午
 */

require("../db.class.php");

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
);


if($_SERVER['REQUEST_METHOD']!="POST"){echo json_encode($json); return;};

$uid             = isset($_POST['uid'])?$_POST['uid']:'';
$sid             = isset($_POST['sid'])?$_POST['sid']:'';
$name            = isset($_POST['name'])?$_POST['name']:'';
$phone           = isset($_POST['phone'])?$_POST['phone']:'';
$location        = isset($_POST['location'])?$_POST['location']:'';
$numberCard      = isset($_POST['numberCard'])?$_POST['numberCard']:'';
$defaultAddress  = 0;

if($uid == '' || $sid == '' || $name == '' || $phone == '' || $location == '' || $numberCard == '')
{
    $json['error'] = 1;
    $json['message'] = '参数错误';
    echo json_encode($json); return;
}
else
{
    $result = $DB->getLine("select count(*) as num_rows from address where uid = $uid ");
    $count = $result['num_rows'];

    if($count > 9)
    {
        $json['error'] = 2;
        $json['message'] = '地址信息超过限定条数';
        echo json_encode($json); return;
    }

    /* 如果数据库内没有记录就直接设置为默认地址 */
    if($count==0){$defaultAddress  = 1;};

    $sql = "insert into address (uid,sid,userName,phone,location,numberCard,defaultAddress) VALUES ($uid,$sid,'$name','$phone','$location','$numberCard',$defaultAddress)";

    $r = $DB->runSql($sql);
    if($r)
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