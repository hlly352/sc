<?php
	require_once '../global_mysql_connect.php';
	require_once '../function/function.php';

	//接收数据
	$category_code = htmlspecialchars(trim(strtoupper($_POST['category_code'])));
	$sql = "SELECT * FROM `sc_category` WHERE `cate_code` = '$category_code'";
	$res = $db->query($sql);
	if($res->num_rows){
		echo 1;
	}