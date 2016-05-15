<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 4/29/16
 * Time: 4:31 下午
 */

require("db.class.php");

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
    'banner' => [],
    'homeFooterMenu' => [],
    'post' => [],
);

$uid = isset($_GET['uid'])?$_GET['uid']:'';

$SS = $DB->getData("select * from banner where name ='home' OR name = 'homeFooterMenu'");

$banner = array();
$homeFooterMenu = array();

foreach($SS as $b)
{
    if($b['name'] == 'home')
    {
        $banner[] = $b;
    }
    if($b['name'] == 'homeFooterMenu')
    {
        $homeFooterMenu[] = $b;
    }
}

$p = null;

if($uid != '')
{
    $p = $DB->getData("select *,(select isLike from likePost where uid = $uid AND pid = post.pid) as postLike
                   ,(select COUNT(*) from likePost where isLike = 1 AND pid = post.pid) as postLikeCount
                   from post WHERE status=0  ORDER BY createTime DESC limit 3");
}
else
{
    $p = $DB->getData("select *,(select COUNT(*) from likePost where isLike = 1 AND pid = post.pid) as postLikeCount
                   from post WHERE status=0  ORDER BY createTime DESC limit 3");
}

$uid_s = array();
$sid_s = array();
$aid_s = array();

foreach($p as $c)
{
    $uid_s[] = $c['uid'];
    $sid_s[] = $c['sid'];
    $aid_s[] = $c['aid'];
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

foreach ($p as $key => $val) {
    $p[$key]['user'] = $users[$val['uid']];
    $p[$key]['school'] = $schools[$val['sid']];
    $p[$key]['address'] = $address[$val['aid']];
    unset($p[$key]['uid']);
    unset($p[$key]['sid']);
    unset($p[$key]['aid']);
    if(isset($p[$key]['postLike']))
    {
        if($p[$key]['postLike'] == null)
        {
            $p[$key]['isLike'] = 0;
        }
        else
        {
            $p[$key]['isLike'] = intval($response[$key]['postLike']);
        }

    }else{
        $p[$key]['isLike'] = 0;
    }
    if(isset($p[$key]['postLikeCount']))
    {
        if($p[$key]['postLikeCount'] == null)
        {
            $p[$key]['likeCount'] = 0;
        }
        else
        {
            $p[$key]['likeCount'] = intval($p[$key]['postLikeCount']);
        }

    }else{
        $p[$key]['likeCount'] = 0;
    }
    unset($p[$key]['postLike']);
    unset($p[$key]['postLikeCount']);
    $images = explode(',',$p[$key]['images']);
    $p[$key]['images'] = $images;
}

if($SS && $p)
{
    $json['error'] = 0;
    $json['message'] = '成功';
    $json['banner'] = $banner;
    $json['homeFooterMenu'] = $homeFooterMenu;
    $json['post'] = $p;
    echo json_encode($json);
    return;
}
else
{
    $json['error'] = 0;
    $json['message'] = 'no data';
    $json['banner'] = [];
    $json['homeFooterMenu'] = [];
    $json['post'] = [];
    echo json_encode($json);
    return;
}
