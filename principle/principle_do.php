<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/upload.php';
require_once 'shell.php';
$dotime = fun_gettime();
if($_POST['submit']){
	$action = $_POST['action'];
	$typeid = $_POST['typeid'];
	$principle_name = $_POST['principle_name'];  
	if($action == "add"){
		$sql = "INSERT INTO `sc_principle_info` (`typeid`,`principle_name`,`status`,`dotime`) VALUES ('$typeid','$principle_name','1','$dotime')";
		$db->query($sql);
		if($db->affected_rows){
			header('location:principle_list.php');
		}
		
	}elseif($action == "edit"){
		$systemid = $_POST['systemid'];
		$system_status = $_POST['system_status'];
		$sql = "UPDATE `db_system` SET `system_type` = '$system_type',`system_name` = '$system_name',`system_dir` = '$system_dir',`system_order` = '$system_order',`system_status` = '$system_status' WHERE `systemid` = '$systemid'";
		$db->query($sql);
		header("location:".$_SERVER['HTTP_REFERER']);
	}elseif($action == "del"){
		$array_thingid = $_POST['id'];
		$thingid = fun_convert_checkbox($array_thingid);
		$sql = "DELETE FROM `sc_other_thing` WHERE `thingid` IN($thingid)";
		$db->query($sql);
		if($db->affected_rows){	
			header("location:".$_SERVER['HTTP_REFERER']);
		}
		}	
	}

?>
