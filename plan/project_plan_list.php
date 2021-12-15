<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
$date = fun_getdate();
if($_GET['submit']){
	$project_name = trim($_GET['project_name']);
	$sqlwhere = " AND `sc_project_plan`.`project_name` LIKE '%$project_name%'";
}
$sql = "SELECT SUM(`sc_project_stage_action`.`weights`) AS `weights`,`sc_project_plan_info`.`infoid`,`sc_project_plan_info`.`order`,`sc_project_plan_info`.`planid`,`sc_project_plan`.`project_name`,`sc_project_plan_info`.`stage_name`,`sc_project_plan_info`.`start_date`,`sc_project_plan_info`.`end_date`,`sc_project_plan_info`.`progress`,DATEDIFF(`sc_project_plan_info`.`end_date`,`sc_project_plan_info`.`start_date`) AS `offset_date`,DATEDIFF(CURDATE(),`sc_project_plan_info`.`start_date`) AS `ready_date` FROM `sc_project_plan` INNER JOIN `sc_project_plan_info` ON `sc_project_plan`.`planid` = `sc_project_plan_info`.`planid` LEFT JOIN `sc_project_stage_action` ON `sc_project_plan_info`.`infoid` = `sc_project_stage_action`.`infoid` WHERE `plan_status` = '1' $sqlwhere GROUP BY `sc_project_plan_info`.`infoid`";

$result = $db->query($sql);
$pages = new page($result->num_rows,12);
$sqllist = $sql . " ORDER BY `sc_project_plan`.`dotime` DESC ,`sc_project_plan_info`.`order` ASC" . $pages->limitsql;
$result = $db->query($sqllist);
//获取项目的阶段数
$result_count = $db->query($sqllist);
$arr_stage_num = $arr_info_num = array();
if($result_count->num_rows){
	while($row_count = $result_count->fetch_assoc()){
		$arr_stage_num[$row_count['planid']] += 1;
		$arr_info_num[$row_count['planid']][$row_count['order']] += 1;

	}
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
<script language="javascript" type="text/javascript" src="../js/main.js"></script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_search">
  <h4>项目列表</h4>
  <form action="" name="search" method="get">
    <table>
      <tr>
        <th>项目名称：</th>
        <td><input type="text" name="project_name" class="input_txt" /></td>
        <td><input type="submit" name="submit" value="查询" class="button" />
          <input type="button" name="button" value="添加" class="button" onclick="location.href='project_plan_add.php?action=add'" />
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
        <th width="10%">项目名称</th>
        <th width="10%">阶段名</th>
        <th width="15%">开始时间</th>
        <th width="15%">结束时间</th>
        <th width="5%">计划用时</th>
        <th width="5%">已过时间</th>
        <th width="5%">已过时间百分比</th>
        <th width="5%">计划进度</th>
        <th width="5%">完成进度</th>
        <th width="4%">Add</th>
        <th width="4%">Edit</th>
        <th width="4%">甘特图</th>
      </tr>
      <?php
      while($row = $result->fetch_assoc()){
		  $planid = $row['planid'];
		  $order  = $row['order'];
		  $ready_date_rate = $bg_weights = $bg_progress = '';
		  //计算过去的时间百分比
		  if($row['start_date'] <= $date && $row['end_date'] >= $date){
		  		$ready_date_rate = ROUND(($row['ready_date'] / $row['offset_date']),2) * 100;
		  		//完成进度小于已过时间的阶段标为红色
		  		if($ready_date_rate  > $row['weights']){
		  			$bg_weights = 'style="background-color:red"';
		  		}
				if($ready_date_rate  > $row['progress']){
		  			$bg_progress = 'style="background-color:red"';
		  		}		  		
		  		$ready_date_rate .= '%';
		  }
	  ?>
      <tr>
 <?php if(array_key_exists($planid,$arr_stage_num) && $arr_stage_num[$planid] > 0){ ?>
        	<td rowspan="<?php echo $arr_stage_num[$planid]; ?>">
        		<input type="checkbox" name="id[]" value="<?php echo $planid; ?>" />
        	</td>
        <?php
        	}
        ?>      	
        <?php if(array_key_exists($planid,$arr_stage_num) && $arr_stage_num[$planid] > 0){ ?>
        	<td rowspan="<?php echo $arr_stage_num[$planid]; ?>"><?php echo $row['project_name']; ?></td>
        <?php
        	}
        ?>
        <?php if(array_key_exists($order,$arr_info_num[$planid]) && $arr_info_num[$planid][$order] > 0){ ?>
        	  <td rowspan="<?php echo $arr_info_num[$planid][$order] ?>"><?php echo $row['stage_name']; ?></td> 
        <?php } ?>
        <td><?php echo $row['start_date']; ?></td>
        <td><?php echo $row['end_date']; ?></td>
        <td><?php echo $row['offset_date']; ?></td>
        <td><?php echo $row['ready_date']; ?></td>
        <td><?php echo $ready_date_rate; ?></td>
        <td <?php echo $bg_weights; ?>><?php echo $row['weights']?$row['weights'].'%':''; ?></td>
        <td <?php echo $bg_progress; ?>><?php echo $row['progress']?$row['progress'].'%':''; ?></td>
        <td>
        	<a href="project_stage_plan_add.php?action=add&infoid=<?php echo $row['infoid'] ?>"><img src="../images/system_ico/add.png" width="20" /></a>
        </td>
        <td>
          <a href="project_stage_action_edit.php?action=edit&infoid=<?php echo $row['infoid'] ?>"><img src="../images/system_ico/edit.png" width="20" /></a>
        </td>
 		   <?php if(array_key_exists($planid,$arr_stage_num) && $arr_stage_num[$planid] > 0){ ?>
        	<td rowspan="<?php echo $arr_stage_num[$planid]; ?>">
        		<a href="project_progress_chars.php?id=<?php echo $planid; ?>"><img src="../images/system_ico/charts.png" width="25" /></a>
        	</td>
        <?php
        	}
        ?>  
      </tr>
      <?php 
      	$arr_stage_num[$planid] = 0;
      	$arr_info_num[$planid][$order] = 0;
      } ?>
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