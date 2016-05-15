<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 5/9/16
 * Time: 5:27 下午
 */

require("../db.class.php");

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
);

if($_SERVER['REQUEST_METHOD']!="POST"){echo json_encode($json); return;};

$uid             = isset($_POST['uid'])?$_POST['uid']:'';
$pid             = isset($_POST['pid'])?$_POST['pid']:'';
$isLike          = isset($_POST['isLike'])?$_POST['isLike']:'';


if($uid == '' || $pid == '' || $isLike == '')
{
    $json['error'] = 1;
    $json['message'] = '参数错误';
    echo json_encode($json); return;
}
else
{
    $isLike = intval($isLike);
    $r = null;

    $result = $DB->getLine("select count(*) as num_rows from likePost where uid = $uid and pid = $pid ");
    $count = $result['num_rows'];

    $sql = null;
    if($isLike == 1 || $isLike == '1')
    {
        if($count == 1)
        {
            $sql = "update likePost set isLike = 1 where uid = $uid and pid = $pid";
        }
        else
        {
            $sql = "insert into likePost (uid,pid,isLike) VALUES ($uid,$pid,1)";
        }
        $r = $DB->runSql($sql);
    }
    else if($isLike == 0)
    {
        if($count == 1)
        {
            $sql = "update likePost set isLike = 0 where uid = $uid and pid = $pid";
        }
        else
        {
            $sql = "insert into likePost (uid,pid,isLike) VALUES ($uid,$pid,0)";
        }
        $r = $DB->runSql($sql);
    }
    else
    {
        $json['error'] = -1;
        $json['message'] = "params error".$sql;
        echo json_encode($json); return;
    }
    if($r)
    {
        $json['error'] = 0;
        $json['message'] = "成功";
        echo json_encode($json); return;
    }
    else
    {
        $json['error'] = -1;
        $json['message'] = "request error".$sql;
        echo json_encode($json); return;
    }
}