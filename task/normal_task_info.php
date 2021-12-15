<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
$taskid = fun_check_int($_GET['id']);
//查询完成情况
$sql = "SELECT `sc_normal_task`.`taskid`,`sc_normal_task`.`task_name`,`sc_normal_task_info`.`do_time`,`sc_normal_task_info`.`remark` FROM `sc_normal_task_info` INNER JOIN `sc_normal_task` ON `sc_normal_task_info`.`taskid` = `sc_normal_task`.`taskid` WHERE `sc_normal_task`.`taskid` = '$taskid'";
$result = $db->query($sql);
$pages = new page($result->num_rows,12);
$sqllist = $sql . " ORDER BY `sc_normal_task_info`.`do_time` DESC" . $pages->limitsql;
$result = $db->query($sqllist);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/system_base.css" type="text/css" rel="stylesheet" />
<link href="css/main.css" type="text/css" rel="stylesheet" />
<link rel="shortcut icon" href="../images/logo/xel.ico" />
<script language="javascript" type="text/javascript" src="../js/jquery-1.6.4.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/main.js"></script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_search">
  <h4>日常任务完成情况</h4>
</div>
<div id="table_list">
  <?php if($result->num_rows){ ?>
  <form action="memory_task_do.php" name="system_list" method="post">
    <table>
      <tr>
        <th width="4%">ID</th>
        <th width="10%">名称</th>
        <th width="10%">完成时间</th>
        <th width="50%">备注</th>
        <th width="4%">status</th>
        <th width="4%">Edit</th>
      </tr>
      <?php
      while($row = $result->fetch_assoc()){
		  $taskid = $row['taskid'];
	  ?>
      <tr>
        <td><input type="checkbox" name="id[]" value="<?php echo $taskid; ?>" /></td>
        <td><?php echo $row['task_name']; ?></td>
        <td><?php echo $row['do_time']; ?></td>
        <td><?php echo $row['remark']; ?></td>
        <td><?php echo $array_status[$row['task_status']]; ?></td>
        <td><a href="memory_task_info.php?id=<?php echo $taskid; ?>&action=do"><img src="../images/system_ico/edit_10_10.png" width="10" height="10" /></a></td>
      </tr>
      <?php } ?>
    </table>
    <div id="checkall">
      <input name="all" type="button" class="select_button" id="CheckedAll" value="全选" />
      <input type="button" name="other" class="select_button" id="CheckedRev" value="反选" />
      <input type="button" name="reset" class="select_button" id="CheckedNo" value="清除" />
      <input type="submit" name="submit" id="submit" value="删除" class="select_button" onclick="JavaScript:return confirm('系统提示:确定删除吗?')" disabled="disabled" />
      <input type="hidden" name="action" value="del" />
    </div>
  </form>
  <div id="page">
    <?php $pages->getPage();?>
  </div>
  <?php
  }else{
	  echo "<p class=\"tag\">系统提示：暂无记录！</p>";
  }
  ?>
</div>
<?php include "../footer.php"; ?>
</body>
</html>