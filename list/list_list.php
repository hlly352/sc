<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
//查询已用的类型
$sql_type = "SELECT * FROM `sc_category` INNER JOIN `sc_list` ON `sc_category`.`id` = `sc_list`.`typeid` WHERE `sc_list`.`status` = '1' GROUP BY `sc_list`.`typeid`";
$result_type = $db->query($sql_type);
if($_GET['submit']){
	$list_number = trim($_GET['list_number']);
	$sqlwhere = " AND `sc_list`.`list_number` LIKE '%$list_number%'";
  $typeid = trim($_GET['typeid']);
  if($typeid){
      $sqlwhere .= " AND `sc_list`.`typeid` = '$typeid'";
  }
}
$sql = "SELECT `sc_category`.`cate_code`,`sc_category`.`cate_name`,`sc_list`.`list_number`,`sc_list`.`status`,`sc_list`.`listid`,COUNT(`sc_list_info`.`infoid`) AS `list_count` FROM `sc_list` INNER JOIN `sc_category` ON `sc_list`.`typeid` = `sc_category`.`id` LEFT JOIN `sc_list_info` ON `sc_list`.`listid` = `sc_list_info`.`listid` WHERE `sc_list`.`status` = '1' $sqlwhere GROUP BY `sc_list`.`listid`";
$result = $db->query($sql);
$pages = new page($result->num_rows,120);
$sqllist_ = $sql . " ORDER BY `sc_list`.`dodate` DESC" . $pages->limitsql;
$result = $db->query($sqllist_);
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
  <h4>清单列表</h4>
  <form action="" name="search" method="get">
    <table>
      <tr>
        <th>清单号：</th>
        <td>
          <input type="text" name="list_number" class="input_txt" />
        </td>
        <th>类型：</th>
        <td>
            <select name="typeid" class="input_txt">
                <option value="">请选择</option>
                <?php
                  if($result_type->num_rows){
                      while($row_type = $result_type->fetch_assoc()){
                        echo '<option value="'.$row_type['id'].'">'.$row_type['cate_code'].'-'.$row_type['cate_name'].'</option>';
                      }
                  }
                ?>
            </select>
        </td>
        <td><input type="submit" name="submit" value="查询" class="button" />
          <input type="button" name="button" value="添加" class="button" onclick="location.href='list_add.php?action=add'" />
          <input type="text" style="display:none;" /></td>
      </tr>
    </table>
  </form>
</div>
<div id="table_list">
  <?php if($result->num_rows){ ?>
  <form action="list_do.php" name="system_list_" method="post">
    <table>
      <tr>
        <th width="4%">ID</th>
        <th width="10%">类型</th>
        <th width="10%">清单号</th>
        <th width="10%">项数</th>
        <th width="4%">status</th>
        <th width="4%">Edit</th>
        <th width="4%">Info</th>
      </tr>
      <?php while($row = $result->fetch_assoc()){
		      $listid = $row['listid'];
	    ?>
      <tr>
        <td><input type="checkbox" name="id[]" value="<?php echo $listid; ?>" /></td>
        <td><?php echo $row['cate_name']; ?></td>
        <td><?php echo $row['list_number']; ?></td>
        <td><?php echo $row['list_count']; ?></td>
        <td><?php echo $array_status[$row['status']]; ?></td>
        <td>
          <a href="list_add.php?listid=<?php echo $listid ?>&action=edit"><img src="../images/system_ico/edit.png" width="25" /></a>
        </td>
        <td>
          <a href="list_info.php?listid=<?php echo $listid; ?>"><img src="../images/system_ico/info.png" width="25" /></a>
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
</div>
<?php include "../footer.php"; ?>
</body>
</html>
