<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
$taskid = trim($_POST['id']);
//更改状态
$sql = "UPDATE `sc_normal_task` SET `task_status` = IF(`task_status` = '1','0','1') WHERE `taskid` = '$taskid'";
$db->query($sql);
if($db->affected_rows){
	$sql_status = "SELECT `task_status`,IF(`last_dodate` = CURDATE(),'1','0') AS `tips`,IF(`todo_date` = CURDATE(),'1','0') AS `is_do` FROM `sc_normal_task` WHERE `taskid` = '$taskid'";
	$result_status = $db->query($sql_status);
	if($result_status->num_rows){
		$row = $result_status->fetch_row();
		$task_status = $row[0];
		$tips        = $row[1];
		$is_do       = $row[2];
		if($task_status == '1' && !($tips) && $is_do){
			echo '3';
		}else{
			echo $task_status;
		}
	}
}
