<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
if($_GET['submit']){
	$task_name = trim($_GET['task_name']);
	$sqlwhere = " WHERE `task_name` LIKE '%$task_name%'";
}
$sql = "SELECT `sc_plan_data`.`note`,`sc_plan_data`.`planid`,`sc_plan_data`.`categoryid`,`sc_plan_data`.`start_date`,`sc_plan_data`.`end_date`,`sc_plan_data`.`status`,`sc_plan_data`.`dotime`,count(`sc_plan_info`.`infoid`) AS `count` FROM `sc_plan_data` LEFT JOIN `sc_plan_info` ON `sc_plan_data`.`planid` = `sc_plan_info`.`planid` WHERE `sc_plan_data`.`status` = '1' AND `type` = 'S' GROUP BY `sc_plan_data`.`planid`";
$result = $db->query($sql);
$pages = new page($result->num_rows,12);
$sqllist = $sql . " ORDER BY `sc_plan_data`.`dotime` DESC" . $pages->limitsql;
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
  <h4>计划列表</h4>
  <form action="" name="search" method="get">
    <table>
      <tr>
        <th>名称：</th>
        <td><input type="text" name="task_name" class="input_txt" /></td>
        <td><input type="submit" name="submit" value="查询" class="button" />
          <input type="button" name="button" value="添加" class="button" onclick="location.href='systemae.php?action=add'" />
          <input type="text" style="display:none;" /></td>
      </tr>
    </table>
  </form>
</div>
<div id="table_list">
  <?php if($result->num_rows){ ?>
  <form action="memory_task_do.php" name="system_list" method="post">
    <table>
      <tr>
        <th width="4%">ID</th>
        <th width="10%">类型</th>
        <th width="10%">开始日期</th>
        <th width="10%">结束日期</th>
        <th width="10%">项数</th>
        <th width="15%">备注</th>
        <th width="4%">状态</th>
        <th width="4%">生成时间</th>
        <th width="4%">Info</th>
      </tr>
      <?php
      while($row = $result->fetch_assoc()){
		  $planid = $row['planid'];
	  ?>
      <tr>
        <td><input type="checkbox" name="id[]" <?php echo $row['task_status'] == '1'?'disabled':''; ?> value="<?php echo $planid; ?>" /></td>
        <td><?php echo $array_plan_category[$row['categoryid']]; ?></td>
        <td><?php echo $row['start_date']; ?></td>
        <td><?php echo $row['end_date']; ?></td>
        <td><?php echo $row['count'] ?></td>
        <td><?php echo $row['note'] ?></td>
        <td><?php echo $array_status[$row['status']]; ?></td>
        <td><?php echo $row['dotime'] ?></td>
        <td><a href="plan_list_info.php?planid=<?php echo $planid; ?>"><img src="../images/system_ico/info.png" width="25"/></a></td>
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