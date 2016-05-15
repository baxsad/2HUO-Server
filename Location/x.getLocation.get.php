<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 5/11/16
 * Time: 2:16 下午
 */

require("../db.class.php");

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
    'data'=>[],
);



$sql = "select * from province";

$r = $DB->getData($sql);

$proIds = array();

if($r)
{
    foreach($r as $p)
    {
        $proIds[] = $p['proID'];
    }

    $city_s = $DB->getData('SELECT * FROM city WHERE proID in ('.implode(',',$proIds).')');

    foreach($r as $key => $val)
    {

        $cityArray = array();
        foreach($city_s as $city)
        {
            if(intval($city['proID']) == intval($r[$key]['proID']))
            {
                $cityArray[] = $city;
            }
        }
        $r[$key]['city'] = $cityArray;
    }

    $json['error'] = 0;
    $json['message'] = '成功';
    $json['data'] = $r;
    echo json_encode($json);
}
else
{
    $json['error'] = 0;
    $json['message'] = '成功但是没有数据';
    echo json_encode($json);
}