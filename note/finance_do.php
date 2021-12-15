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
	$array_cate_id = $_POST['cate_id'];
	$array_amount = $_POST['amount'];
	$array_date = $_POST['date'];
	$array_remark = $_POST['remark'];
	$type  = $_POST['type'];      
	if($action == "add"){
		//加入收入信息
		$str_add = '';
		foreach($array_cate_id as $k=>$cate_id){
			$amount = $array_amount[$k];
			$date = $array_date[$k];
			$remark = $array_remark[$k];
			$str_add .= "('$cate_id','$amount','$date','$remark','1','$dotime','$dodate','$type')".',';
			
			//更新实际收入和支出信息
			$sql_income_expend = "UPDATE `sc_category` SET `real_value` = `real_value` + '$amount' WHERE `id` = '$cate_id'";
			$db->query($sql_income_expend);
			if($type == 'I'){
				//收入按按比例加入到计划支出中
				//查询比例信息
				$sql_proportion = "SELECT `sc_expend_rule_info`.`expend_cate`,`sc_expend_rule_info`.`proportion` FROM `sc_expend_rule_info` INNER JOIN `sc_expend_rule` ON `sc_expend_rule_info`.`ruleid` = `sc_expend_rule`.`ruleid` WHERE `sc_expend_rule`.`is_select` = '1'";
				$result_proportion = $db->query($sql_proportion);
				if($result_proportion->num_rows){
					while($row_proportion = $result_proportion->fetch_assoc()){
						$cateids = $row_proportion['expend_cate'];
						$proportion = $row_proportion['proportion'];
						$cur_plan_amount = $amount * $proportion / 100;
						//计划金额更新到类目中
						$sql_cate = "UPDATE `sc_category` SET `plan_value` = `plan_value` + '$cur_plan_amount' WHERE `id` = '$cateids'";
						echo $sql_cate.'<br>';
						$db->query($sql_cate);
						//把收入更新到对应时间的计划支出表中
						//查找当前收入时间对应的支出计划
						$sql_finance_plan = "UPDATE `sc_plan_info` SET `sc_plan_info`.`amount` = `sc_plan_info`.`amount` + '$cur_plan_amount' WHERE `sc_plan_info`.`planid` IN (SELECT `planid` FROM `sc_plan_data` WHERE `sc_plan_data`.`start_date` <= '$date' AND `sc_plan_data`.`end_date` >= '$date') AND `sc_plan_info`.`cateid` = '$cateids'";
						$db->query($sql_finance_plan);			
					}
				}
			}elseif($type == 'O'){
				//把支出信息添加到总类目中
				$sql_expend = "UPDATE `sc_category` SET `real_value` = `real_value` + '$amount' WHERE `id` = (SELECT `pid` FROM (SELECT `pid` FROM `sc_category` WHERE `id` = '$cate_id') AS `a`)";
				$db->query($sql_expend);
			}			
		}
		$str_add = rtrim($str_add,',');
		//加入项目详情
			$sql_info = "INSERT INTO `sc_finance_info`(`cateid`,`amount`,`date`,`remark`,`status`,`dotime`,`dodate`,`type`) VALUES $str_add";
			
			$db->query($sql_info);
		if($db->affected_rows){
			if($type == 'I'){
				header('location:income_list.php');
			}elseif($type == 'O'){
				header('location:expend_list.php');
			}
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