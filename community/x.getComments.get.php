<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 5/10/16
 * Time: 1:50 下午
 */

require("../db.class.php");

$pid = isset($_GET['pid'])?$_GET['pid']:'';
$lastId = isset($_GET['lastId'])?$_GET['lastId']:'';

if($lastId == '' || $lastId == null){$lastId = 0;};

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
    'data' => [],
);

if($pid == '')
{
    $json['error'] = 1;
    $json['message'] = '参数错误';
    echo json_encode($json);
    return;
}

$sql = "select *,(select nick from user_info where user_info.uid = postComments.atUserId) as atUser from postComments where pid = $pid ORDER BY postComments.createdTime DESC";

$response = $DB->getData($sql);

$json['message'] = '未获取到数据';

if(!$response){echo json_encode($json);return;}

$uid_s = array();

foreach($response as $p)
{
    $uid_s[] = $p['uid'];
}

$users = $DB->getData('SELECT * FROM user_info WHERE uid in ('.implode(',',$uid_s).')');

foreach ($users as $user) {
    $users[$user['uid']]=$user;
}

foreach ($response as $key => $val) {
    $response[$key]['user'] = $users[$val['uid']];
    unset($response[$key]['uid']);
}

$json['error'] = 0;
$json['message'] = '成功';
$json['data'] = $response != null ? $response : [];
echo json_encode($json);