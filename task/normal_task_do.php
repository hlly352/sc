<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/upload.php';
require_once 'shell.php';
$dotime = fun_gettime();
$date   = fun_getdate();
if($_POST['submit']){
	$action     = $_POST['action'];
	$task_name  = $_POST['task_name'];
	$todo_date = $_POST['todo_date'];
	$repeat_rule  = $_POST['repeat_rule'];
	$content    = $_POST['content'];
	$remark     = $_POST['remark'];
	if($action == "add"){
		$sql = "INSERT INTO `sc_normal_task` (`task_name`,`repeat_rule`,`todo_date`,`content`,`dotime`,`task_status`,`remark`) VALUES('$task_name','$repeat_rule','$todo_date','$content','$dotime','1','$remark')";

		$db->query($sql);
			header('location:normal_task_list.php');
	}elseif($action == "edit"){
		$do_time = $_POST['do_date'];
		$taskid = $_POST['taskid'];
		//插入到任务详情表中
		$sql = "INSERT INTO `sc_normal_task_info`(`taskid`,`do_time`,`remark`,`time`) VALUES('$taskid','$do_time','$remark','$dotime')";
		$db->query($sql);
		if($db->affected_rows){
			//把一次和每年的任务标记为已经完成
			$sql_status = "UPDATE `sc_normal_task` SET `task_status` = '2' WHERE `taskid` = '$taskid' AND `repeat_rule` IN('A','D')";
			$db->query($sql_status);
			//标记最后一次完成日期
			$sql_last_date = "UPDATE `sc_normal_task` SET `last_dodate` = '$date' WHERE `taskid` = '$taskid'";
			$db->query($sql_last_date);
		}
		header("location:normal_task_list.php");
	}elseif($action == "del"){
		$array_taskid = $_POST['id'];
		//删除任务
		$taskid = fun_convert_checkbox($array_taskid);
		$sql = "DELETE FROM `sc_normal_task_info` WHERE `taskid` IN($taskid)";
		$db->query($sql);
		//删除任务详情
		$sql_task = "DELETE FROM `sc_normal_task` WHERE `taskid` IN($taskid)";
		$db->query($sql_task);
		header("location:".$_SERVER['HTTP_REFERER']);
	}
}
?>
