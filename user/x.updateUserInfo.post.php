<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 5/13/16
 * Time: 3:38 下午
 */


require("../db.class.php");

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
    'data'=>[],
);


if($_SERVER['REQUEST_METHOD']!="POST"){echo json_encode($json); return;};

$uid               = isset($_POST['uid'])?$_POST['uid']:'';
$nick             = isset($_POST['nick'])?$_POST['nick']:'';
$sid             = isset($_POST['sid'])?$_POST['sid']:'';
$des            = isset($_POST['des'])?$_POST['des']:'';
$sex           = isset($_POST['sex'])?$_POST['sex']:'';
$age         = isset($_POST['age'])?$_POST['age']:'';
$avatar     = isset($_POST['avatar'])?$_POST['avatar']:'';

if($nick == '' && $sid == '' && $des == '' && $sex == '' && $age == '' && $avatar == '')
{
    $json['error'] = -1;
    $json['message'] = '参数错误';
    $json['data'] = null;
    echo json_encode($json);
    return;
}

$user = $DB->getLine("select * from user_info where uid = $uid");

$nick = $nick == '' ? $user['nick'] : $nick;
$sid = $sid == '' ? $user['sid'] : $sid;
$des = $des == '' ? $user['des'] : $des;
$sex = $sex == '' ? $user['sex'] : $sex;
$age = $age == '' ? $user['age'] : $age;
$avatar = $avatar == '' ? $user['avatar'] : $avatar;

$sql = "update user_info set nick='$nick' ,des='$des', sid=$sid, avatar='$avatar',sex='$sex',age=$age";
$up = $DB->runSql($sql);

$user['nick'] = $nick;
$user['sid'] = $sid;
$user['des'] = $des;
$user['sex'] = $sex;
$user['age'] = $age;
$user['avatar'] = $avatar;

$school = $DB->getLine("select * from school where id=$sid");
$user['school'] = $school;

if($up)
{
    $json['error'] = 0;
    $json['message'] = '成功';
    $json['data'] = $user;
    echo json_encode($json);
}
else
{
    $json['error'] = 1;
    $json['message'] = $sql;
    $json['data'] = null;
    echo json_encode($json);
}