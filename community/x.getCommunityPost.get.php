<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 5/3/16
 * Time: 11:27 上午
 */

require("../db.class.php");

$uid = isset($_GET['uid'])?$_GET['uid']:'';
$cid = isset($_GET['cid'])?$_GET['cid']:'';
$sid = isset($_GET['sid'])?$_GET['sid']:'';
$lastId = isset($_GET['lastId'])?$_GET['lastId']:'';

if($lastId == '' || $lastId == null){$lastId = 0;};

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
    'data' => [],
);

if($cid == '' && $sid == '')
{
    $json['error'] = 1;
    $json['message'] = '参数错误';
    echo json_encode($json);
    return;
}

if($cid != '' && $sid == '')
{
    $sql = $lastId != 0 ? "select *,(select COUNT(*) from likePost where isLike = 1 AND pid = post.pid) as postLikeCount FROM post
                       where post.cid = $cid AND post.pid = $lastId AND status = 0
                       ORDER BY post.createTime DESC LIMIT 20":
        "select *,(select COUNT(*) from likePost where isLike = 1 AND pid = post.pid) as postLikeCount FROM post
                       where post.cid = $cid AND status = 0
                       ORDER BY post.createTime DESC LIMIT 20";

    if($uid != '')
    {
        $sql = $lastId != 0 ? "select *,(select isLike from likePost where uid = $uid AND pid = post.pid) as postLike,(select COUNT(*) from likePost where isLike = 1 AND pid = post.pid) as postLikeCount FROM post
                       where post.cid = $cid AND post.pid = $lastId AND status = 0
                       ORDER BY post.createTime DESC LIMIT 20":
            "select *,(select isLike from likePost where uid = $uid AND pid = post.pid) as postLike,(select COUNT(*) from likePost where isLike = 1 AND pid = post.pid) as postLikeCount FROM post
                       where post.cid = $cid AND status = 0
                       ORDER BY post.createTime DESC LIMIT 20";
    }
}

if($cid != '' && $sid != '')
{
    $sql = $lastId != 0 ? "select *,(select COUNT(*) from likePost where isLike = 1 AND pid = post.pid) as postLikeCount FROM post
                       where post.cid = $cid AND post.pid = $lastId AND status = 0 AND post.sid = $sid
                       ORDER BY post.createTime DESC LIMIT 20":
        "select *,(select COUNT(*) from likePost where isLike = 1 AND pid = post.pid) as postLikeCount FROM post
                       where post.cid = $cid AND status = 0 AND post.sid = $sid
                       ORDER BY post.createTime DESC LIMIT 20";

    if($uid != '')
    {
        $sql = $lastId != 0 ? "select *,(select isLike from likePost where uid = $uid AND pid = post.pid) as postLike,(select COUNT(*) from likePost where isLike = 1 AND pid = post.pid) as postLikeCount FROM post
                       where post.cid = $cid AND post.pid = $lastId AND status = 0 AND post.sid = $sid
                       ORDER BY post.createTime DESC LIMIT 20":
            "select *,(select isLike from likePost where uid = $uid AND pid = post.pid) as postLike,(select COUNT(*) from likePost where isLike = 1 AND pid = post.pid) as postLikeCount FROM post
                       where post.cid = $cid AND status = 0 AND post.sid = $sid
                       ORDER BY post.createTime DESC LIMIT 20";
    }
}

if($cid == '' && $sid != '')
{
    $sql = $lastId != 0 ? "select *,(select COUNT(*) from likePost where isLike = 1 AND pid = post.pid) as postLikeCount FROM post
                       where post.pid = $lastId AND status = 0 AND post.sid = $sid
                       ORDER BY post.createTime DESC LIMIT 20":
        "select *,(select COUNT(*) from likePost where isLike = 1 AND pid = post.pid) as postLikeCount FROM post
                       where status = 0 AND post.sid = $sid
                       ORDER BY post.createTime DESC LIMIT 20";

    if($uid != '')
    {
        $sql = $lastId != 0 ? "select *,(select isLike from likePost where uid = $uid AND pid = post.pid) as postLike,(select COUNT(*) from likePost where isLike = 1 AND pid = post.pid) as postLikeCount FROM post
                       where post.pid = $lastId AND status = 0 AND post.sid = $sid
                       ORDER BY post.createTime DESC LIMIT 20":
            "select *,(select isLike from likePost where uid = $uid AND pid = post.pid) as postLike,(select COUNT(*) from likePost where isLike = 1 AND pid = post.pid) as postLikeCount FROM post
                       where status = 0 AND post.sid = $sid
                       ORDER BY post.createTime DESC LIMIT 20";
    }
}

$response = $DB->getData($sql);

if(!$response){echo json_encode($json);return;}

$uid_s = array();
$sid_s = array();
$aid_s = array();
foreach($response as $p)
{
    $uid_s[] = $p['uid'];
    $sid_s[] = $p['sid'];
    $aid_s[] = $p['aid'];
}

$users = $DB->getData('SELECT * FROM user_info WHERE uid in ('.implode(',',$uid_s).')');
$schools = $DB->getData('SELECT * FROM school WHERE id in ('.implode(',',$sid_s).')');
$address = $DB->getData('SELECT * FROM address WHERE aid in ('.implode(',',$aid_s).')');

foreach ($users as $user) {
    $users[$user['uid']]=$user;
}

foreach ($schools as $school) {
    $schools[$school['id']]=$school;
}

foreach ($address as $ad) {
    $address[$ad['aid']]=$ad;
}

foreach ($response as $key => $val) {
    $response[$key]['user'] = $users[$val['uid']];
    $response[$key]['school'] = $schools[$val['sid']];
    $response[$key]['address'] = $address[$val['aid']];
    unset($response[$key]['uid']);
    unset($response[$key]['sid']);
    unset($response[$key]['aid']);
    if(isset($response[$key]['postLike']))
    {
        if($response[$key]['postLike'] == null)
        {
            $response[$key]['isLike'] = 0;
        }
        else
        {
            $response[$key]['isLike'] = intval($response[$key]['postLike']);
        }

    }else{
        $response[$key]['isLike'] = 0;
    }
    if(isset($response[$key]['postLikeCount']))
    {
        if($response[$key]['postLikeCount'] == null)
        {
            $response[$key]['likeCount'] = 0;
        }
        else
        {
            $response[$key]['likeCount'] = intval($response[$key]['postLikeCount']);
        }

    }else{
        $response[$key]['likeCount'] = 0;
    }
    unset($response[$key]['postLike']);
    unset($response[$key]['postLikeCount']);
    $images = explode(',',$response[$key]['images']);
    $response[$key]['images'] = $images;
}

$json['error'] = 0;
$json['message'] = '成功';
$json['data'] = $response != null ? $response : [];
echo json_encode($json);