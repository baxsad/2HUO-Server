<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 5/6/16
 * Time: 11:10 上午
 */

require("../db.class.php");

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
    'data'=>[],
);

$def = isset($_GET['def'])?$_GET['def']:'';
$uid = isset($_GET['uid'])?$_GET['uid']:'';

$sql = "select * from address where uid = $uid order by defaultAddress DESC ";;

if($def == '1'){$sql = "select * from address where uid = $uid AND defaultAddress=1";};

$r = $DB->getData($sql);

if($r)
{

    $sid_s = array();
    foreach($r as $ad)
    {
        $sid_s[] = $ad['sid'];
    }
    $schools = $DB->getData('SELECT * FROM school WHERE id in ('.implode(',',$sid_s).')');
    foreach ($schools as $school) {
        $schools[$school['id']]=$school;
    }
    foreach ($r as $key => $val) {
        $r[$key]['school'] = $schools[$val['sid']];
        unset($r[$key]['sid']);
    }

    $json['error'] = 0;
    $json['message'] = '成功';
    $json['data'] = $r;
    echo json_encode($json);
}
else
{
    $json['error'] = 0;
    $json['message'] = '成功';
    $json['data'] = [];
    echo json_encode($json);
}