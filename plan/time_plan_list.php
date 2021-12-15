<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
$sqldate = " AND `dodate` BETWEEN '$sdate' AND '$edate'";
if($_GET['submits']){
	$sdate = trim($_GET['sdate']);
	if($sdate){
		$sqldate = " AND `date` BETWEEN '$sdate' AND '$edate'";
	}else{
		$sqldate = '';
	}
}
//计算各项资金金额
//总收入金额
$be_sdate = date('Y-m-d',strtotime($sdate.'-1 month'));
$be_edate = date('Y-m-d',strtotime($edate.'-1 month'));
$sql_plan_time = "SELECT SUM(`sc_plan_info`.`amount`) FROM `sc_plan_info` INNER JOIN `sc_plan_data` ON `sc_plan_info`.`planid` = `sc_plan_data`.`planid` WHERE `sc_plan_data`.`categoryid` = 'M' AND `sc_plan_data`.`type` = 'T' AND `sc_plan_data`.`dodate` BETWEEN '$be_sdate' AND '$be_edate'";
$result_plan_time = $db->query($sql_plan_time);
if($result_plan_time->num_rows){
	$plan_time = $result_plan_time->fetch_row()[0];
}
//实际总用时
$sql_useage_time = "SELECT SUM(`offset_min`) FROM `sc_time_info` WHERE `type` = 'I' AND `status` = '1' $sqldate";

$result_useage_time = $db->query($sql_useage_time);
if($result_useage_time->num_rows){
  $useage_time = $result_useage_time->fetch_row()[0];
}

if($_GET['submit']){
	$task_name = trim($_GET['task_name']);
	$sqlwhere = " WHERE `task_name` LIKE '%$task_name%'";
}
//查询财务计划
$sql = "SELECT `sc_plan_data`.`note`,`sc_plan_data`.`planid`,`sc_plan_data`.`categoryid`,`sc_plan_data`.`start_date`,`sc_plan_data`.`end_date`,`sc_plan_data`.`status`,`sc_plan_data`.`dotime`,count(`sc_plan_info`.`infoid`) AS `count` FROM `sc_plan_data` LEFT JOIN `sc_plan_info` ON `sc_plan_data`.`planid` = `sc_plan_info`.`planid` WHERE `sc_plan_data`.`status` = '1' AND `type` = 'T' GROUP BY `sc_plan_data`.`planid`";
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
<script type="text/javascript" src="../js/My97DatePicker/WdatePicker.js"></script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_search">
	<h4>资金汇总</h4>
		<form>
			<table>
				<tr>
					<th>日期：</th>
					<td>
						<input type="text" name="sdate" value="<?php echo $sdate; ?>" onfocus="WdatePicker({'FmtDate':'yyyy-MM-dd',isClear:true,readOnly})" class="input_txt" />
						--
						<input type="text" name="edate" value="<?php echo $edate; ?>" onfocus="WdatePicker({'FmtDate':'yyyy-MM-dd',isClear:true,readOnly})" class="input_txt" />
					</td>
					<td>
						<input type="submit" name="submits" value="查询" class="button" />
					</td>
				</tr>		
			</table>
		</form>
</div>
<div id="table_list">
	<table>
		<tr>
			<th>计划用时</th>
			<th>实际总用时</th>
			</tr>
		<tr>
			<td><?php echo convert_time($plan_time * 60) ?></td>
			<td><?php echo convert_time($useage_time) ?></td>
		</tr>
	</table>
</div>
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
        <th width="10%">备注</th>
        <th width="4%">状态</th>
        <th width="8%">生成时间</th>
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
        <td><a href="time_plan_list_info.php?planid=<?php echo $planid; ?>"><img src="../images/system_ico/info.png" width="25"/></a></td>
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