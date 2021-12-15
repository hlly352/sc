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
	$cateid      = $_POST['cateid'];
	$date        = $_POST['date'];
	$start_time  = $_POST['start_time'];
	$end_time    = $_POST['end_time'];
	$offset_time = $_POST['offset_time'];
	//转换成分钟数
	$arr_offset  = explode(':', $offset_time);
	$offset_min  = $arr_offset[0] * 60 + $arr_offset[1];
	$remark      = $_POST['remark'];
	if($action == "add"){
		$sql = "INSERT INTO `sc_time_info` (`cateid`,`date`,`start_time`,`end_time`,`offset_time`,`offset_min`,`remark`,`dodate`,`dotime`,`status`,`type`) VALUES ('$cateid','$date','$start_time','$end_time','$offset_time','$offset_min','$remark','$dodate','$dotime','1','I')";
		$db->query($sql);
		$sql_cate = "UPDATE `sc_category` SET `real_value` = `real_value` + '$offset_min' WHERE `id` = '$cateid'";
		$db->query($sql_cate);		
		if($db->affected_rows){
			header('location:time_usage_list.php');
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
