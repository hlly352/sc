<?php
	require_once '../global_mysql_connect.php';
	require_once '../function/function.php';
	$rule_type = $_POST['rule_type'];
	//获取对应的规则
	$sql = "SELECT * FROM `sc_repeat_rule` WHERE `rule_status` = '1' AND `type` = '$rule_type'";
	$res = $db->query($sql);
	if($res->num_rows){
		$arr_rule = array();
		while($row = $res->fetch_assoc()){
			$arr_rule[$row['ruleid']] = $row['rule_name'];
		}
	}
	echo json_encode($arr_rule);