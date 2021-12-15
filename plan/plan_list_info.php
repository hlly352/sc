<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
$planid = trim($_GET['planid']);
if($_GET['submit']){
	$task_name = trim($_GET['task_name']);
	$sqlwhere = " WHERE `task_name` LIKE '%$task_name%'";
}
//查找计划详情
$sql = "SELECT `sc_plan_info`.`infoid`,`sc_category`.`cate_name`,`sc_plan_info`.`cateid`,`sc_plan_info`.`goal`,`sc_plan_info`.`remark`,`sc_plan_info`.`finish_time`,`sc_plan_info`.`is_complete` FROM `sc_plan_info` INNER JOIN `sc_category` ON `sc_plan_info`.`cateid` = `sc_category`.`id` WHERE `sc_plan_info`.`planid` = '$planid' GROUP BY `sc_plan_info`.`infoid`";
$result = $db->query($sql);
$array_goal_num = array();
if($result->num_rows){
  while($row_typeid = $result->fetch_assoc()){
    $array_goal_num[$row_typeid['cateid']] += 1;
  }
}
$pages = new page($result->num_rows,120);
$sqllist = $sql . " ORDER BY `sc_plan_info`.`cateid` ASC" . $pages->limitsql;
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
        <th width="10%">目标</th>
        <th width="10%">备注</th>
        <th width="10%">完成时间</th>
        <th width="10%">状态</th>
      </tr>
      <?php
      while($row = $result->fetch_assoc()){
		  $infoid    = $row['infoid'];
      $cateid    = $row['cateid'];
      $cate_name = $row['cate_name'];
	  ?>
      <tr>
        <td><input type="checkbox" name="id[]" <?php echo $row['task_status'] == '1'?'disabled':''; ?> value="<?php echo $infoid; ?>" /></td>
        <?php if($cols_num = $array_goal_num[$cateid]){ ?>
          <td rowspan="<?php echo $cols_num ?>"><?php echo $cate_name; ?></td>
        <?php } ?>
        <td><?php echo $row['goal']; ?></td>
        <td><?php echo $row['remark'] ?></td>    
        <td><?php echo $row['finish_time'] ?></td>    
         <td>
          <a href="#" class="<?php echo $row['is_complete'] == 0?'action':'cancel' ?>" id="info_<?php echo $infoid; ?>">
            <?php if($row['is_complete'] == 0){ ?>
              <img src="../images/system_ico/edit.png" width="20">
            <?php }else{
              echo '<img src="../images/system_ico/cancel.png" width="20">';
              } ?>
          </a>
        </td>    
      </tr>
      <?php
        $array_goal_num[$cateid] = null;
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