<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
$infoid = trim($_POST['infoid']);
$do_time  = trim($_POST['do_time']);
$remark   = trim($_POST['remark']);
//设置完成时间，然后把进度累加到阶段表中
$sql = "UPDATE `sc_plan_info` SET `is_complete` = '1',`finish_time` = '$do_time',`remark` = '$remark' WHERE `infoid` = '$infoid'";
$db->query($sql);
if($db->affected_rows){
	echo '1';
}