<?php 
	require_once '../global_mysql_connect.php';
	require_once '../function/function.php';

	$item_id = trim($_POST['item_id']);
	$sql = "SELECT * FROM `sc_category` WHERE `pid` = '$item_id' AND `status` = '1'";
	$result = $db->query($sql);
	if($result->num_rows){
		$arr_cate = array();
		while($row = $result->fetch_assoc()){
			$arr_cate[$row['id']] = $row['cate_code'].'-'.$row['cate_name'];
		}
	}
	echo json_encode($arr_cate);




