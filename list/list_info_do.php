<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/upload.php';
require_once 'shell.php';
if($_POST['submit']){
	$action = $_POST['action'];
	$arr_infoid = $_POST['id'];
	$infoid = fun_convert_checkbox($arr_infoid);
	if($action == 'del'){
		//删除清单详情
		$sql = "DELETE FROM `sc_list_info` WHERE `infoid` IN($infoid)";
		$db->query($sql);
		header('location:'.$_SERVER['HTTP_REFERER']);
	}
}

