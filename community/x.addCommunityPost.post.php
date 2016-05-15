<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 5/1/16
 * Time: 11:53 上午
 */

require("../db.class.php");

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
);

if($_SERVER['REQUEST_METHOD']!="POST"){echo json_encode($json); return;};

$uid             = isset($_POST['uid'])?$_POST['uid']:'';
$sid             = isset($_POST['sid'])?$_POST['sid']:'';
$aid             = isset($_POST['aid'])?$_POST['aid']:'';
$cid             = isset($_POST['cid'])?$_POST['cid']:'';
$title           = isset($_POST['title'])?$_POST['title']:'';
$content         = isset($_POST['content'])?$_POST['content']:'';
$images          = isset($_POST['images'])?$_POST['images']:'';
$transactionMode = isset($_POST['transactionMode'])?($_POST['transactionMode'] == 0 ? 'online' : 'outline'):'';
$createTime      = time();
$updateTime      = time();
$originalPrice   = isset($_POST['originalPrice'])?floatval($_POST['originalPrice']):0;
$presentPrice    = isset($_POST['presentPrice'])?floatval($_POST['presentPrice']):0;
$isLike          = 0;
$likeCount       = 0;
$status          = 0;

if($uid || $sid || $aid  || $cid || $originalPrice || $presentPrice || $title !='' || $content !='')
{
    $uid = intval($uid);
    $cid = intval($cid);
    $sid = intval($sid);
    $aid = intval($aid);
    $originalPrice = floatval($originalPrice);
    $presentPrice = floatval($presentPrice);
    if(!is_int($uid) || !is_int($cid) || !is_int($sid) || !is_int($aid) || !is_float($originalPrice) || !is_float($presentPrice)){
        /*不是整形*/
        $json['error'] = -1;
        $json['message'] = '数据格式错误';
        echo json_encode($json);
        return;
    }
}
else
{
    // uid 不存在
    $json['error'] = 1;
    $json['message'] = '缺少参数';
    echo json_encode($json);
    return;

}

$sql = "INSERT INTO post (uid,cid,sid,aid,title,content,images,transactionMode,p_type,createTime,updateTime,originalPrice,presentPrice,isLike,likeCount,status)
VALUES($uid,$cid,$sid,$aid,'$title','$content','$images','$transactionMode','sale','$createTime','$updateTime',$originalPrice,$presentPrice,$isLike,$likeCount,$status)";
$response = $DB->runSql($sql);

if($response)
{
    $json['error'] = 0;
    $json['message'] = '成功';
    echo json_encode($json);
    return;
}
else
{
    $json['error'] = 2;
    $json['message'] = $sql;
    echo json_encode($json);
    return;
}


