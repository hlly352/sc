<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/upload.php';
require_once 'shell.php';
$dotime = fun_gettime();
if($_POST['submit']){
	$action     = $_POST['action'];
	$task_name  = $_POST['task_name'];
	$start_date = $_POST['start_date'];
	$ruleid     = $_POST['ruleid'];
	$content    = $_POST['content'];
	$remark     = $_POST['remark'];
	$type       = $_POST['task_type'];
	if($action == "add"){
		$sql = "INSERT INTO `sc_repeat_task` (`task_name`,`start_date`,`ruleid`,`content`,`dotime`,`task_status`,`remark`,`type`) VALUES('$task_name','$start_date','$ruleid','$content','$dotime','1','$remark','$type')";
		$db->query($sql);
		if($taskid = $db->insert_id){
			//查询规则对应的日期
			$sql_rule = "SELECT `info` FROM `sc_repeat_rule_info` WHERE `ruleid` = '$ruleid' ORDER BY `order` ASC";
			$result_rule = $db->query($sql_rule);
			if($result_rule->num_rows){
				$str_info = '("'.$taskid.'","'.$start_date.'","0"),';
				while($row_rule = $result_rule->fetch_assoc()){
					$info_date = $row_rule['info'];
					$start_date = date('Y-m-d',strtotime($start_date."+ {$info_date} days"));
					$str_info .= '("'.$taskid.'","'.$start_date.'","0"),';
				}
			}
			$str_info = rtrim($str_info,',');
			$sql_info = "INSERT INTO `sc_repeat_task_info`(`taskid`,`todo_date`,`status`) VALUES $str_info";
			$db->query($sql_info);
			if($db->affected_rows){
				header('location:repeat_task_list.php');
			}
		}
	}elseif($action == "edit"){
		$systemid = $_POST['systemid'];
		$system_status = $_POST['system_status'];
		$sql = "UPDATE `db_system` SET `system_type` = '$system_type',`system_name` = '$system_name',`system_dir` = '$system_dir',`system_order` = '$system_order',`system_status` = '$system_status' WHERE `systemid` = '$systemid'";
		$db->query($sql);
		header("location:".$_SERVER['HTTP_REFERER']);
	}elseif($action == "del"){
		$array_taskid = $_POST['id'];
		$taskid = fun_convert_checkbox($array_taskid);
		$sql_task = "UPDATE `sc_repeat_task` SET `task_status` = '0' WHERE `taskid` IN($taskid)";
		echo $sql_task;
		$db->query($sql_task);
		header("location:".$_SERVER['HTTP_REFERER']);
	}
	
}
?>
