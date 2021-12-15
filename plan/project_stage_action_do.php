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
	$infoid     = $_POST['infoid'];
	$array_action_name = $_POST['action_name'];
	$array_start_date  = $_POST['start_date'];
	$array_end_date    = $_POST['end_date'];
	$array_weights   = $_POST['weights'];
	if($action == "add"){
		$str_add = '';
		foreach($array_action_name as $k=>$action_name){
			$start_date = $array_start_date[$k];
			$end_date   = $array_end_date[$k];
			$weights    = $array_weights[$k];
			$str_add .= "('$infoid','$action_name','$weights','$start_date','$end_date','$dotime'),";
		}
		$str_add = rtrim($str_add,',');
		$sql = "INSERT INTO `sc_project_stage_action` (`infoid`,`action_name`,`weights`,`start_date`,`end_date`,`dotime`) VALUES $str_add";
		$db->query($sql);
		if($db->affected_rows){
			header('location:project_stage_plan_add.php?action=add&infoid='.$infoid);
		}
	}elseif($action == "del"){
		$arr_actionid = $_POST['id'];
		$str_actionid = fun_convert_checkbox($arr_actionid);	
		//删除当前实施步骤	
		$sql = "DELETE FROM `sc_project_stage_action` WHERE `actionid` IN($str_actionid)";
		$db->query($sql);
		header("location:".$_SERVER['HTTP_REFERER']);
	}
	
}
?>