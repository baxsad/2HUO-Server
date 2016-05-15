<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 5/10/16
 * Time: 2:23 下午
 */
require("../db.class.php");

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
);

if($_SERVER['REQUEST_METHOD']!="POST"){echo json_encode($json); return;};

$uid             = isset($_POST['uid'])?$_POST['uid']:'';
$pid             = isset($_POST['pid'])?$_POST['pid']:'';
$atUserId        = isset($_POST['atUserId'])?$_POST['atUserId']:0;
$createdTime     = time();
$biddingPrice    = isset($_POST['biddingPrice'])?$_POST['biddingPrice']:'';
$content    = isset($_POST['content'])?$_POST['content']:'';

if($uid == '' || $pid == '' || $biddingPrice == '' || content == '')
{
    $json['error'] = 1;
    $json['message'] = '参数错误';
    echo json_encode($json); return;
}

$sql = "insert into postComments (pid,uid,content,atUserId,createdTime,biddingPrice) VALUES ($pid,$uid,'$content',$atUserId,'$createdTime',$biddingPrice)";

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
    $json['message'] = $sql;
    echo json_encode($json); return;
}