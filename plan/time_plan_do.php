<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/upload.php';
require_once 'shell.php';
$dotime = fun_gettime();
$dodate   = fun_getdate();
$sdate = date('Y-m-25');
$edate = date('Y-m-24',(strtotime($sdate.'+1 month')));

if($_POST['submit']){
	$action       = $_POST['action'];
	$categoryid   = $_POST['categoryid'];
	$start_date   = $_POST['start_date'];
	$end_date     = $_POST['end_date'];
	$array_cateid = $_POST['cateid'];
	$note         = $_POST['note'];
	if($action == "add"){
		//加入项目列表
		$sql = "INSERT INTO `sc_plan_data` (`start_date`,`end_date`,`status`,`note`,`dodate`,`dotime`,`categoryid`,`type`) VALUES('$start_date','$end_date','1','$note','$dodate','$dotime','$categoryid','T')";
		$db->query($sql);
		$planid = $db->insert_id;
		$str_add =  '';
		//没有在比例表中的项目添加到详情中
		foreach($array_cateid as $k=>$cateid){
			$amount = $_POST['amount'][$k];
			$goal  = $_POST['goal'][$k];
			$remark = $_POST['remark'][$k];
			$str_add .= "('$planid','$cateid','$amount','$goal','$remark','1')".',';
			//把计划的时间添加到对应的类目上
			$sql_cate = "UPDATE `sc_category` SET `plan_value` = `plan_value` + ('$amount' * 60) WHERE `id` = '$cateid'";
			$db->query($sql_cate);
			}
		$str_add = rtrim($str_add,',');
		
		//加入项目详情
		$sql_info = "INSERT INTO `sc_plan_info`(`planid`,`cateid`,`amount`,`goal`,`remark`,`status`) VALUES $str_add";
		$db->query($sql_info);
		if($db->affected_rows){
			header('location:time_plan_list.php');
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