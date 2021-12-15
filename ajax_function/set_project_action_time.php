<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
$actionid = trim($_POST['actionid']);
$do_time  = trim($_POST['do_time']);
//设置完成时间，然后把进度累加到阶段表中
$sql = "UPDATE `sc_project_stage_action` SET `is_action` = '1',`finish_time` = '$do_time' WHERE `actionid` = '$actionid'";

$db->query($sql);
if($db->affected_rows){
	$sql_progress = "UPDATE `sc_project_plan_info` INNER JOIN (SELECT `infoid`,`weights` FROM `sc_project_stage_action` WHERE `actionid` = '$actionid') AS `action` ON `sc_project_plan_info`.`infoid` = `action`.`infoid` SET `sc_project_plan_info`.`progress` = `sc_project_plan_info`.`progress` + `action`.`weights`";
	$db->query($sql_progress);
	if($db->affected_rows){
		echo '1';
	}
}