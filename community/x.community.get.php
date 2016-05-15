<?php
/**
 * Created by PhpStorm.
 * User: iURCoder
 * Date: 4/30/16
 * Time: 10:49 上午
 */

require("../db.class.php");

$SS = $DB->getData('select a.*,(select count(*) from post where cid=a.cid and status = 0) as count
from community a ORDER BY COUNT DESC ');

$json = array(
    'error'=>'0',
    'message'=>'sucess',
    'data'=>$SS,
    'date'=>time(),
);

echo json_encode($json);