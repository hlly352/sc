<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/upload.php';
require_once 'shell.php';
$dotime = fun_gettime();
$dodate   = fun_getdate();
if($_POST['submit']){
	$action              = $_POST['action'];
	$array_cate_id       = $_POST['cate_id'];
	$array_name          = $_POST['name'];
	$array_specification = $_POST['specification'];
	$array_source        = $_POST['source'];
	$array_name          = $_POST['name'];
	$array_amount        = $_POST['amount'];
	$array_date          = $_POST['date'];
	$array_remark        = $_POST['remark'];
	$type                = $_POST['type'];      
	if($action == "add"){
		//加入收入信息
		$str_add = '';
		foreach($array_cate_id as $k=>$cate_id){
			$name  	       = $array_name[$k];
			$specification = $array_specification[$k];
			$source        = $array_source[$k];
			$amount        = $array_amount[$k];
			$date          = $array_date[$k];
			$remark        = $array_remark[$k];
			$str_add .= "('$cate_id','$name','$specification','$source','$amount','$remark','1','$dotime','$dodate')".',';
			
			//把资产金额更新到对应的分类中
			$sql_income_expend = "UPDATE `sc_category` SET `real_value` = `real_value` + '$amount' WHERE `id` = '$cate_id'";
			$db->query($sql_income_expend);				
		}
		$str_add = rtrim($str_add,',');
		//加入项目详情
			$sql_info = "INSERT INTO `sc_fixed_assets`(`cateid`,`name`,`specification`,`source`,`amount`,`remark`,`status`,`dotime`,`dodate`) VALUES $str_add";
			
			$db->query($sql_info);
		if($db->affected_rows){
			header('location:fixed_assets_list.php');
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