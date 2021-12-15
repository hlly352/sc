<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/upload.php';
require_once 'shell.php';
$dotime = fun_gettime();
if($_POST['submit']){
	$action    = $_POST['action'];
	$arr_expend_cate = $_POST['expend_cate'];
	$arr_proportion  = $_POST['proportion'];
	$rule_name = $_POST['rule_name'];
	$remark    = $_POST['remark'];
	$rule_info = $_POST['rule_info'];
	if($action == "add"){
		//添加规则总表
		$sql = "INSERT INTO `sc_expend_rule` (`rule_name`,`remark`,`dotime`,`rule_status`,`is_select`) VALUES ('$rule_name','$remark','$dotime','1','0')";
		$db->query($sql);
		if($ruleid = $db->insert_id){
			//加入规则详情
			foreach($arr_expend_cate as $k=>$expend_cate){
				$proportion = $arr_proportion[$k];
				if($expend_cate){
					$str_info .= '("'.$ruleid.'","'.($k+1).'","'.$expend_cate.'","'.$proportion.'"),';
				}
			}
			$str_info = rtrim($str_info,',');
			$sql_info = "INSERT INTO `sc_expend_rule_info`(`ruleid`,`order`,`expend_cate`,`proportion`) VALUES $str_info";
			$db->query($sql_info);
			if($db->affected_rows){
				header('location:expend_list.php');
			}
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
