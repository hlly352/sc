<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
$taskid = fun_check_int($_GET['id']);
$sql = "SELECT * FROM  `sc_repeat_task_info` WHERE `taskid` = '$taskid' ORDER BY `todo_date` ASC $sqlwhere";
$result = $db->query($sql);
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
<div id="table_search">
  <h4>重复任务执行</h4>
</div>
<div id="table_list">
  <?php if($result->num_rows){ ?>
  <form action="systemdo.php" name="system_list" method="post">
    <table>
      <tr>
        <th width="4%">ID</th>
        <th width="10%">次数</th>
        <th width="10%">计划日期</th>
        <th width="10%">实际日期</th>
        <th width="10%">开始时间</th>
        <th width="10%">结束时间</th>
        <th width="10%">用时</th>
        <th width="20%">备注</th>
        <th width="4%">status</th>
      </tr>
      <?php
		  $i = 1;
      while($row = $result->fetch_assoc()){
		  $infoid = $row['infoid'];
		  $colors = $row['todo_date'] == date('Y-m-d')?'style="color:red"':'';

	  ?>
      <tr>
        <td><input type="checkbox" name="id[]" value="<?php echo $infoid; ?>" /></td>
        <td><?php echo $i; ?></td>
        <td <?php echo $colors; ?>><?php echo $row['todo_date']; ?></td>
        <td><?php echo $row['do_date'] ?></td>
        <td><?php echo $row['start_time'] ?></td>
 	<td><?php echo $row['end_time'] ?></td>
 	<td>
		<?php 
			$offset_time = date('H:i:s',(strtotime($row['end_time']."-8 hours")) - strtotime($row['start_time']));
			if($row['end_time'] && $row['start_time']){
				$parts = explode(':',$offset_time);
				$seconds += ($parts[0] * 3600) + ($parts[1] * 60) + $parts[2];
			}
			echo ($row['start_time'] && $row['end_time'])?$offset_time:''; ?></td>
        <td><?php echo $row['remark'] ?></td>       
        <td><a href="repeat_task_edit.php?id=<?php echo $infoid; ?>&action=edit"><img src="../images/system_ico/edit_10_10.png" width="10" height="10" /></a></td>
      </tr>
      <?php 
      	$i++;
      } ?>
	<tr>
		<td colspan="6">合计</td>
		<td>
			<?php 
				$hour = floor($seconds / 3600);
				$min  = floor(($seconds - ($hour * 3600)) / 60);
				$sec  = $seconds - ($hour * 3600) - ($min * 60);
				echo $hour.':'.(($min<10)?('0'.$min):$min).':'.(($sec<10)?('0'.$sec):$sec);
			 ?>
		</td>
		<td></td>
		<td></td>
	</tr>
    </table>
    <div id="checkall">
      <input name="all" type="button" class="select_button" id="CheckedAll" value="全选" />
      <input type="button" name="other" class="select_button" id="CheckedRev" value="反选" />
      <input type="button" name="reset" class="select_button" id="CheckedNo" value="清除" />
      <input type="submit" name="submit" id="submit" value="删除" class="select_button" onclick="JavaScript:return confirm('系统提示:确定删除吗?')" disabled="disabled" />
      <input type="hidden" name="action" value="del" />
    </div>
  </form>
  <?php
  }else{
	  echo "<p class=\"tag\">系统提示：暂无记录！</p>";
  }
  ?>
</div>
<?php include "../footer.php"; ?>
</body>
</html>
