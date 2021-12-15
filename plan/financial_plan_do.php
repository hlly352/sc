<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/upload.php';
require_once 'shell.php';
$dotime = fun_gettime();
$dodate   = fun_getdate();
if($_POST['submit']){
	$action       = $_POST['action'];
	$categoryid   = $_POST['categoryid'];
	$start_date   = $_POST['start_date'];
	$end_date     = $_POST['end_date'];
	$array_item_id = $_POST['item_id'];
	$note         = $_POST['note'];
	if($action == "add"){
		//加入项目列表
		$sql = "INSERT INTO `sc_plan_data` (`start_date`,`end_date`,`status`,`note`,`dodate`,`dotime`,`categoryid`,`type`) VALUES('$start_date','$end_date','1','$note','$dodate','$dotime','$categoryid','F')";
		$db->query($sql);
		$planid = $db->insert_id;
		$str_add =  '';
		//没有在比例表中的项目添加到详情中
		foreach($array_item_id as $k=>$item_id){
			$amount = $_POST['amount'][$k];
			$thing  = $_POST['thing'][$k];
			$remark = $_POST['remark'][$k];
			$str_add .= "('$planid','$item_id','$amount','$thing','$remark','1')".',';
			//更新类目中的计划金额
			$sql_amount = "UPDATE `sc_category` SET `plan_value` = `plan_value` + '$amount' WHERE `id` = '$item_id'";
			$db->query($sql_amount);
		}
		//查找所有的收入信息
		$sql_income = "SELECT SUM(`amount`) AS `total_amount` FROM `sc_finance_info` WHERE `type` = 'I' AND `date` BETWEEN '$start_date' AND '$end_date'";
	
		$result_income = $db->query($sql_income);
		if($result_income->num_rows){
			$total_amount = $result_income->fetch_assoc()['total_amount'];
		}
		//查询当前支出分配比例规则
		$sql_rule = "SELECT `sc_expend_rule_info`.`expend_cate`,`sc_expend_rule_info`.`proportion` FROM `sc_expend_rule_info` INNER JOIN `sc_expend_rule` ON `sc_expend_rule_info`.`ruleid` = `sc_expend_rule`.`ruleid` WHERE `sc_expend_rule`.`is_select` = '1'";
		$result_rule = $db->query($sql_rule);
		if($result_rule->num_rows){
			while($row_rule = $result_rule->fetch_assoc()){
				$expend_cate = $row_rule['expend_cate'];
				$cur_amount = $total_amount * $row_rule['proportion'] /100;
				$amount = number_format($amount,2,',','');
				//添加比例表中的项目到详情表中
				$str_add .= "('$planid','$expend_cate','$cur_amount','比例规则','','1'),";
				//更新类目中的计划金额
				$sql_plan_amount = "UPDATE `sc_category` SET `plan_value` = `plan_value` + '$cur_amount' WHERE `id` = '$expend_cate'";
				$db->query($sql_plan_amount);				
			}
		}
		$str_add = rtrim($str_add,',');
		
		//加入项目详情
		$sql_info = "INSERT INTO `sc_plan_info`(`planid`,`cateid`,`amount`,`goal`,`remark`,`status`) VALUES $str_add";
		$db->query($sql_info);
		if($db->affected_rows){
			header('location:financial_plan_list.php');
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