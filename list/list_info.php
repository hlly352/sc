<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
$listid = trim($_GET['listid']);
//查询已用规则
$sql_type = "SELECT `sc_list_type`.`typeid`,`sc_list_type`.`type_name` FROM `sc_list_info` INNER JOIN `sc_list_type` ON `sc_list_info`.`typeid` = `sc_list_type`.`typeid` GROUP BY `sc_list_type`.`typeid`";
$result_type = $db->query($sql_type);

if($_GET['submit']){
	$principle_name = trim($_GET['principle_name']);
	$sqlwhere = " AND `sc_list_info`.`principle_name` LIKE '%$principle_name%'";
  $typeid = trim($_GET['typeid']);
  if($typeid){
      $sqlwhere .= " AND `sc_list_info`.`typeid` = '$typeid'";
  }
}
$sql = "SELECT `sc_list_info`.`infoid`,`sc_category`.`cate_name`,`sc_list_info`.`info_name`,`sc_list_info`.`description`,`sc_list_info`.`status` FROM `sc_list_info` INNER JOIN `sc_list` ON `sc_list_info`.`listid` = `sc_list`.`listid` INNER JOIN `sc_category` ON `sc_category`.`id` = `sc_list`.`typeid` WHERE `sc_list`.`listid` = '$listid' AND `sc_list_info`.`status` = '1' $sqlwhere";
$result = $db->query($sql);
$pages = new page($result->num_rows,12);
$sqllist = $sql . " ORDER BY `sc_list_info`.`infoid` DESC" . $pages->limitsql;
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
  <h4>清单详情</h4>
  <form action="" name="search" method="get">
    <table>
      <tr>
        <th>名称：</th>
        <td>
          <input type="text" name="principle_name" class="input_txt" />
        </td>
        <td><input type="submit" name="submit" value="查询" class="button" />
          <input type="button" name="button" value="添加" class="button" onclick="location.href='other_thing_add.php?action=add'" />
          <input type="text" style="display:none;" /></td>
      </tr>
    </table>
  </form>
</div>
<div id="table_list">
  <?php if($result->num_rows){ ?>
  <form action="list_info_do.php" name="system_list" method="post">
    <table>
      <tr>
        <th width="4%">ID</th>
        <th width="10%">类型</th>
        <th width="10%">内容</th>
        <th width="10%">描述</th>
        <th width="4%">status</th>
        <th width="4%">Edit</th>
        <th width="4%">Info</th>
      </tr>
      <?php while($row = $result->fetch_assoc()){
		      $infoid = $row['infoid'];
	    ?>
      <tr>
        <td><input type="checkbox" name="id[]" value="<?php echo $infoid; ?>" /></td>
        <td><?php echo $row['cate_name']; ?></td>
        <td><?php echo $row['info_name']; ?></td>
        <td><?php echo $row['description']; ?></td>
        <td><?php echo $array_status[$row['status']]; ?></td>
        <td>
          <a href="systemae.php?id=<?php echo $systemid; ?>&action=edit"><img src="../images/system_ico/edit.png" width="25" /></a>
        </td>
        <td>
          <a href="list_info.php?id=<?php echo $systemid; ?>&action=edit"><img src="../images/system_ico/info.png" width="25" /></a>
        </td>
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
     <p>
     	<input type="button" onclick="return window.history.back()" value="返回" class="button">
     </p>
<?php include "../footer.php"; ?>
</body>
</html>
