<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
//查询已用规则
$sql_rule = "SELECT DISTINCT(`rule_name`) AS `ruleid` FROM `sc_memory_task`";
$result_rule = $db->query($sql_rule);
$array_ruleid = array();
if($result_rule->num_rows){
       while($row_ruleid = $result_rule->fetch_assoc()){
        $array_ruleid[] = $row_ruleid['ruleid'];
    }
}
if($_GET['submit']){
	$rule_name = trim($_GET['rule_name']);
	$sqlwhere = " WHERE `rule_name` LIKE '%$rule_name%'";
}
$sql = "SELECT `sc_memory_rule`.`ruleid`,`sc_memory_rule`.`rule_name`,COUNT(`sc_memory_rule_info`.`infoid`) AS `count`,GROUP_CONCAT(`sc_memory_rule_info`.`info` ORDER BY `order` ASC SEPARATOR '-') AS `info`,SUM(`sc_memory_rule_info`.`info`) AS `sum`,`sc_memory_rule`.`rule_status` FROM `sc_memory_rule` INNER JOIN `sc_memory_rule_info` ON `sc_memory_rule`.`ruleid` = `sc_memory_rule_info`.`ruleid` WHERE `sc_memory_rule`.`type` = 'E' GROUP BY `sc_memory_rule`.`ruleid` $sqlwhere";
$result = $db->query($sql);
$pages = new page($result->num_rows,12);
$sqllist = $sql . " ORDER BY `sc_memory_rule`.`dotime` DESC,`sc_memory_rule`.`ruleid` ASC" . $pages->limitsql;
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
  <h4>记忆规则列表</h4>
  <form action="" name="search" method="get">
    <table>
      <tr>
        <th>名称：</th>
        <td><input type="text" name="rule_name" class="input_txt" /></td>
        <td><input type="submit" name="submit" value="查询" class="button" />
          <input type="button" name="button" value="添加" class="button" onclick="location.href='exercise_rule_add.php?action=add'" />
          <input type="text" style="display:none;" /></td>
      </tr>
    </table>
  </form>
</div>
<div id="table_list">
  <?php if($result->num_rows){ ?>
  <form action="memory_rule_do.php" name="system_list" method="post">
    <table>
      <tr>
        <th width="4%">ID</th>
        <th width="10%">名称</th>
        <th width="10%">次数</th>
        <th width="10%">总时间</th>
        <th width="40%">info</th>
        <th width="4%">status</th>
        <th width="4%">Edit</th>
      </tr>
      <?php while($row = $result->fetch_assoc()){
		      $ruleid = $row['ruleid'];
	    ?>
      <tr>
        <td><input type="checkbox" name="id[]" <?php if(in_array($ruleid,$array_ruleid)){ echo 'disabled'; } ?> value="<?php echo $ruleid; ?>" /></td>
        <td><?php echo $row['rule_name']; ?></td>
        <td><?php echo $row['count']; ?></td>
        <td><?php echo $row['sum']; ?></td>
        <td><?php echo $row['info']; ?></td>
        <td><?php echo $array_status[$row['rule_status']]; ?></td>
        <td><a href="systemae.php?id=<?php echo $systemid; ?>&action=edit"><img src="../images/system_ico/edit_10_10.png" width="10" height="10" /></a></td>
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
