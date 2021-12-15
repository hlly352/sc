<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
$infoid = trim($_POST['infoid']);
//取消完成时间，然后把进度从阶段表中减去
$sql = "UPDATE `sc_plan_info` SET `is_complete` = '0',`finish_time` = '' WHERE `infoid` = '$infoid'";

$db->query($sql);
if($db->affected_rows){
		echo '1';
}