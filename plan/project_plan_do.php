<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/upload.php';
require_once 'shell.php';
$dotime = fun_gettime();
$date   = fun_getdate();
if($_POST['submit']){
	$action       = $_POST['action'];
	$project_name = $_POST['project_name'];
	$aims         = $_POST['aims'];
	$remark       = $_POST['remark'];
	$array_stage_name = $_POST['stage_name'];
	if($action == "add"){
		//加入项目列表
		$sql = "INSERT INTO `sc_project_plan` (`project_name`,`aims`,`plan_status`,`remark`,`dotime`) VALUES('$project_name','$aims','1','$remark','$dotime')";
		$db->query($sql);
		$planid = $db->insert_id;
		$str_add = '';
		foreach($array_stage_name as $k=>$stage_name){
			$array_start_date = $_POST['start_date_'.$k];
			$array_end_date   = $_POST['end_date_'.$k];
			foreach($array_start_date as $ks=>$start_date){
				$end_date = $array_end_date[$ks];
				$str_add .= "('$planid','$k','$stage_name','$start_date','$end_date')".',';
				}
		}
		$str_add = rtrim($str_add,',');
		//加入项目详情
		$sql_info = "INSERT INTO `sc_project_plan_info`(`planid`,`order`,`stage_name`,`start_date`,`end_date`) VALUES $str_add";
		$db->query($sql_info);
		if($db->affected_rows){
			header('location:project_plan_list.php');
		}
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
		$taskid = fun_convert_checkbox($array_taskid);
		$sql = "DELETE FROM `sc_memory_task_info` WHERE `taskid` IN($taskid)";
		$db->query($sql);
		$sql_task = "DELETE FROM `sc_memory_task` WHERE `taskid` IN($taskid)";
		$db->query($sql_task);
		header("location:".$_SERVER['HTTP_REFERER']);
	}
	
}
?>