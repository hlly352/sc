<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/upload.php';
require_once 'shell.php';
$dotime = fun_gettime();
if($_POST['submit']){
	$action      = $_POST['action'];
	$infoid      = $_POST['infoid'];
	$taskid      = $_POST['taskid'];
	$do_date     = $_POST['do_date'];
	$start_time  = $_POST['start_time'];
	$end_time    = $_POST['end_time'];
	$offset_time = $_POST['offset_time']; 
	$remark      = $_POST['remark'];
	if($action == "edit"){
		$sql = "UPDATE `sc_memory_task_info` SET `do_date` = '$do_date',`start_time` = '$start_time',`end_time` = '$end_time',`offset_time` = '$offset_time',`remark` = '$remark',`dotime` = '$dotime' WHERE `infoid` = '$infoid'";
		$db->query($sql);
		if($db->affected_rows){
			header('location:memory_task_info.php?id='.$taskid);
		}
	}
}
?>