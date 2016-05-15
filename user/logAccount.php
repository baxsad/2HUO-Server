<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 5/1/16
 * Time: 11:54 上午
 */

require("../db.class.php");

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
    'data'=>null,
);

$nick = $platId = $platName = $avatar = '';

if($_SERVER['REQUEST_METHOD']!="POST"){echo json_encode($json); return;};


$nick = $_POST['nick'];
$platId = $_POST['platId'];
$platName = $_POST['platName'];
$avatar = $_POST['avatar'];

$des = '';
$sex = '';
$createTime = time();
$lastTimeLogin = time();

if($nick == '' || $platId == '')
{
    $json['error'] = 1;
    $json['message'] = '参数错误';
    echo json_encode($json);
    return;
}

$token = md5($platId.$platName,false);

$account = $DB->getLine("select * from user_info where token = '$token'");


if($account)
{
    $sid = $account['sid'];
    $DB->runSql("update user_info set lastTimeLogin = $lastTimeLogin");
    $school = $DB->getLine("select * from school where id=$sid");
    $account['school'] = $school;
    $json['error'] = 0;
    $json['message'] = "成功";
    $json['data'] = $account;
    echo json_encode($json);
    return;
}

$sql = "insert into user_info (nick,sex,des,platName,platId,avatar,token,createTime,lastTimeLogin)
                         VALUES
                         ('$nick','$sex','$des','$platName',$platId,'$avatar','$token','$createTime','$lastTimeLogin')";

$response = $DB->runSql($sql);

if($response)
{
    $account = $DB->getLine("select * from user_info where token = '$token'");
    if($account)
    {
        $sid = $account['sid'];
        $school = $DB->getLine("select * from school where id=$sid");
        $account['school'] = $school;
        $json['error'] = 0;
        $json['message'] = "成功";
        $json['data'] = $account;
        echo json_encode($json);
        return;
    }
    else
    {
        $json['error'] = 2;
        $json['message'] = '注册成功查找失败';
        echo json_encode($json);
        return;
    }
}
else
{
    $json['error'] = 2;
    $json['message'] = $DB->error();
    echo json_encode($json);
    return;
}