<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
$ruleid = trim($_POST['id']);
//更改状态
$sql_is_select = "UPDATE `sc_expend_rule` SET `is_select` = IF(`is_select` = '1','0','1') WHERE `ruleid` = '$ruleid'";
$db->query($sql_is_select);
//把其它更改为不选中
$sql = "UPDATE `sc_expend_rule` SET `is_select` = '0' WHERE `ruleid` != '$ruleid'";
$db->query($sql);
if($db->affected_rows){
	echo '1';
}else{
	echo '0';
}