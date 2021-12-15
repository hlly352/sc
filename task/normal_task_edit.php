<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once 'shell.php';
$action = fun_check_action($_GET['action']);
$taskid   = fun_check_int($_GET['id']);
//查询任务详情
$sql = "SELECT * FROM `sc_normal_task` WHERE `taskid` = '$taskid'";
$result = $db->query($sql);
if($result->num_rows){
	$row = $result->fetch_assoc();
	$rule = $row['repeat_rule'];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/system_base.css" type="text/css" rel="stylesheet" />
<link href="css/main.css" type="text/css" rel="stylesheet" />
<link rel="shortcut icon" href="../images/logo/xel.ico" />
<script language="javascript" type="text/javascript" src="../js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="../js/My97DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="../js/main.js"></script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_sheet">
  <?php if($action == "edit"){ ?>
  <h4>日常任务完成编辑</h4>
  <form action="normal_task_do.php" name="system" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <th width="20%">任务名称：</th>
        <td width="80%"><?php echo $row['task_name'] ?></td>
      </tr>
      <tr>
        <th>计划日期：</th>
        <td>
			<?php
        		if($rule == 'A' || $rule == 'D'){
        			echo $row['todo_date'];
        		}elseif($rule == 'B'){
        			echo $array_week[$row['todo_date']];
        		}elseif($rule == 'C'){
        			echo '每月'.$row['todo_date'].'号';
        		}
        	?>        	
        </td>
      </tr>
      <tr>
          <th>完成时间：</th>
          <td>
              <input type="text" name="do_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',isClear:false,readOnly:true})" value="<?php echo date('Y-m-d H:i:s') ?>" class="input_txt" />
          </td>
      </tr>   
      <tr id="remark">
        <th>备注：</th>
        <td>
        	<input type="text" name="remark" value="<?php echo $row['remark'] ?>" class="input_txt" />
      </tr>
      <tr>
        <th>&nbsp;</th>
        <td>
        	<input type="submit" name="submit" id="submit" value="确定" class="button" />
          	<input type="button" name="button" value="返回" class="button" onclick="javascript:history.go(-1);" />
          	<input type="hidden" name="action" value="<?php echo $action; ?>" />
          	<input type="hidden" name="taskid" value="<?php echo $row['taskid']; ?>" />
        </td>
      </tr>
    </table>
  </form>
  <?php
  	}
  ?>
</div>
<?php include "../footer.php"; ?>
</body>
</html>