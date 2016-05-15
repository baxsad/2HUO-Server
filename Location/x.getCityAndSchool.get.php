<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 5/11/16
 * Time: 6:11 下午
 */

require("../db.class.php");

$json = array(
    'error'=>-2,
    'message'=>'请求错误',
    'data'=>[],
);

$sql = "select cityID,cityName from city";
$response = $DB->getData($sql);

if(!$response)
{
    echo json_encode($json);
}

$cityIds = array();

foreach($response as $city)
{
    $cityIds[] = $city['cityID'];
}

$schools = $DB->getData('select name,id,cityId from school WHERE cityId in ('.implode(',',$cityIds).')');

foreach($response as $key=>$val)
{
    $schoolArray = array();
    foreach($schools as $school)
    {
        if(intval($school['cityId']) == intval($val['cityID']))
        {
            $schoolArray[] = $school;
        }
    }
    $response[$key]['school'] = $schoolArray;
}

$json['error'] = 0;
$json['message'] = '成功';
$json['data'] = $response;
echo json_encode($json);