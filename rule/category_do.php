<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/upload.php';
require_once 'shell.php';
$dotime = fun_gettime();
if($_POST['submit']){
	$action = $_POST['action'];
	//添加分类类型
	if($action == "add"){
		$pid           = htmlspecialchars(trim($_POST['pid']));
		$cate_name = htmlspecialchars(trim($_POST['category_name'])); 
		$cate_code = htmlspecialchars(trim(strtoupper($_POST['category_code']))); 
		//查询父类的full_path
		$sql_parent_full_path = "SELECT `full_path` FROM `sc_category` WHERE `id` = '$pid'";
		$result_parent_full_path = $db->query($sql_parent_full_path);
		if($result_parent_full_path->num_rows){
			$parent_full_path = $result_parent_full_path->fetch_assoc()['full_path'];
		}
		$path = $parent_full_path?$parent_full_path:'0';
		$sql = "INSERT INTO `sc_category` (`pid`,`cate_code`,`cate_name`,`path`,`cate_time`,`status`,`plan_value`,`real_value`) VALUES ('$pid','$cate_code','$cate_name','$path','$dotime','1','0','0')";
		$db->query($sql);
		$cur_id = $db->insert_id;
		$sql_add_id = "UPDATE `sc_category` SET `full_path` = CONCAT(`path`,',','$cur_id') WHERE `id` = '$cur_id'";
		$db->query($sql_add_id);
		if($db->affected_rows){
			header('location:category_list.php');
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
