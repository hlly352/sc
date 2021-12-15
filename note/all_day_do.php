<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/upload.php';
require_once 'shell.php';
$dotime = fun_gettime();
$dodate = fun_getdate();
if($_POST['submit']){
	$action      = $_POST['action'];
	$date     = $_POST['date'];
	$arr_cateid  = $_POST['cateid'];
	$arr_start_time  = $_POST['start_time'];
	$arr_end_time    = $_POST['end_time'];
	$arr_offset_time = $_POST['offset_time'];
	$arr_remark      = $_POST['remark'];
	if($action == "add"){
		//把每个事项的信息分开到sql语句中
		$sql_str = '';
		foreach($arr_start_time as $k=>$start_time){
			$cateid   	 = $arr_cateid[$k];
			$end_time  	 = $arr_end_time[$k];
			$offset_time = $arr_offset_time[$k];
			$remark      = $arr_remark[$k];
			//拼接sql 语句
			$sql_str .= '("'.$date.'","'.$cateid.'","'.$start_time.'","'.$end_time.'","'.$offset_time.'","'.$remark.'","'.$dodate.'","'.$dotime.'","1","D"),';
		}
		$sql_str = rtrim($sql_str,',');
		//添加到时间详情表中
		$sql = "INSERT INTO `sc_time_info` (`date`,`cateid`,`start_time`,`end_time`,`offset_time`,`remark`,`dodate`,`dotime`,`status`,`type`) VALUES $sql_str";
		$db->query($sql);
		if($db->affected_rows){
			header('location:all_day_list.php');
		}
		
	}elseif($action == "edit"){
		$systemid = $_POST['systemid'];
		$system_status = $_POST['system_status'];
		$sql = "UPDATE `db_system` SET `system_type` = '$system_type',`system_name` = '$system_name',`system_dir` = '$system_dir',`system_order` = '$system_order',`system_status` = '$system_status' WHERE `systemid` = '$systemid'";
		$db->query($sql);
		header("location:".$_SERVER['HTTP_REFERER']);
	}elseif($action == "del"){
		$array_ruleid = $_POST['id'];
		$ruleid = fun_convert_checkbox($array_ruleid);
		$sql = "DELETE FROM `sc_memory_rule_info` WHERE `ruleid` IN($ruleid)";
		$db->query($sql);
		$sql_info = "DELETE FROM `sc_memory_rule` WHERE `ruleid` IN($ruleid)";
		$db->query($sql_info);
		header("location:".$_SERVER['HTTP_REFERER']);
	}
	}

?>
