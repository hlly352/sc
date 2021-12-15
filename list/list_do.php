<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/upload.php';
require_once 'shell.php';
$dodate = fun_getdate();
$dotime = fun_gettime();
if($_POST['submit']){
	$action = $_POST['action'];
	$typeid = $_POST['typeid'];
	$listid = $_POST['listid'];
	$remark = $_POST['remark'];
	$arr_list_info = $_POST['list_info'];
	$arr_list_description = $_POST['list_description'];
	if($action == "add"){
		//生成清单号
		if(!$listid){
			$sql_number = "SELECT MAX(substr(`list_number`,-2)+0) AS `max_number` FROM `sc_list` WHERE `dodate` = '$dodate'";
			$result_number = $db->query($sql_number);
			if($result_number->num_rows){
				$max_number = $result_number->fetch_row()[0];
				if($max_number){
					$list_number = date('Ymd').strtolen($max_number,2).($max_number + 1);
				}else{
					$list_number = date('Ymd').'01';
				}
			}
			//添加清单主表
			$sql_list = "INSERT INTO `sc_list`(`list_number`,`typeid`,`status`,`dodate`,`dotime`,`remark`) VALUES('$list_number','$typeid','1','$dodate','$dotime','$remark')";
			$db->query($sql_list);
			$listid = $db->insert_id;
			}
			//添加清单详情
			$sql_str = '';
			foreach($arr_list_info as $k=>$list_info){
				$description = $arr_list_description[$k];
				$sql_str .= '("'.$listid.'","'.$list_info.'","1","'.$dotime.'","'.$description.'"),';
			}
			$sql_str = rtrim($sql_str,',');
			$sql_info = "INSERT INTO `sc_list_info` (`listid`,`info_name`,`status`,`dotime`,`description`) VALUES $sql_str";
			$db->query($sql_info);
			if($db->affected_rows){
				header('location:list_list.php');
			}
		
	}elseif($action == "edit"){
		$systemid = $_POST['systemid'];
		$system_status = $_POST['system_status'];
		$sql = "UPDATE `db_system` SET `system_type` = '$system_type',`system_name` = '$system_name',`system_dir` = '$system_dir',`system_order` = '$system_order',`system_status` = '$system_status' WHERE `systemid` = '$systemid'";
		$db->query($sql);
		header("location:".$_SERVER['HTTP_REFERER']);
	}elseif($action == "del"){
		$array_listid = $_POST['id'];
		$listid = fun_convert_checkbox($array_listid);
		//删除清单列表
		$sql_list = "DELETE FROM `sc_list_info` WHERE `listid` IN($listid)";
		$db->query($sql_list);
		$sql = "DELETE FROM `sc_list` WHERE `listid` IN($listid)";
		$db->query($sql);
		if($db->affected_rows){	
			header("location:".$_SERVER['HTTP_REFERER']);
		}
		}	
	}

?>
