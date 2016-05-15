<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 5/5/16
 * Time: 6:35 下午
 */

require("../db.class.php");

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
    'data' => [],
);

$cityId = isset($_GET['cityId'])?$_GET['cityId']:'';
$lastId = isset($_GET['lastId'])?$_GET['lastId']:'';

$sql = '';

if($cityId == '')
{
    if($lastId == '')
    {
        $sql = "select school.*,(select COUNT(*) from post where sid = school.id AND status = 0) as count from school ORDER BY COUNT DESC";
    }
    else
    {
        $sql = "select school.*,(select COUNT(*) from post where sid = school.id AND status = 0) as count from school WHERE id > $lastId ORDER BY COUNT DESC LIMIT 15";
    }
}
else
{
    if($lastId == '')
    {
        $sql = "select school.*,(select COUNT(*) from post where sid = school.id AND status = 0) as count from school WHERE cityId = $cityId ORDER BY COUNT DESC";
    }
    else
    {
        $sql = "select school.*,(select COUNT(*) from post where sid = school.id AND status = 0) as count from school WHERE cityId = $cityId AND id > $lastId ORDER BY COUNT DESC limit 15";
    }
}

$SS = $DB->getData($sql);

if($SS)
{
    $json['error'] = 0;
    $json['message'] = '成功';
    $json['data'] = $SS;
    echo json_encode($json);
}
else
{
    $json['error'] = 0;
    $json['message'] = '成功';
    $json['data'] = [];
    echo json_encode($json);
}