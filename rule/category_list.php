<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
$pid = htmlspecialchars(trim($_GET['pid']));
//通过pid查询子类
$str_pid_where = $pid?" AND `pid` = '$pid'":' AND `pid` = \'0\'';
//查询已用规则
$sql_thing = "SELECT DISTINCT(`thingid`) AS `thingid` FROM `sc_other_thing_info`";
$result_thing = $db->query($sql_thing);
$array_thingid = array();
if($result_thing->num_rows){
       while($row_thingid = $result_thing->fetch_assoc()){
        $array_thingid[] = $row_thingid['thingid'];
    }
}
if($_GET['submit']){
	$category_name = trim($_GET['category_name']);
	$category_code = trim($_GET['category_code']);
	$sqlwhere = " AND `cate_name` LIKE '%$category_name%' AND `cate_code` LIKE '%$category_code%'";
  $str_pid_where = '';
}
$sql_pid = "SELECT * FROM `sc_category` WHERE `status` = '1'";
$result_pid = $db->query($sql_pid);
//查询所有的pid
$arr_pid = array();
if($result_pid->num_rows){
    while($row_pid = $result_pid->fetch_assoc()){
        $arr_pid[$row_pid['pid']] = $row_pid['pid'];
    }
}
//根据条件查询
$sql = $sql_pid.$str_pid_where.$sqlwhere;
$result_show = $db->query($sql);
if($result_show ->num_rows){
    $path = $result_show->fetch_assoc()['path'];
}
//面包屑
$sql_header_show = "SELECT `cate_name` FROM `sc_category` WHERE `id` IN($path)";
$result_header_show = $db->query($sql_header_show);
$str_header = '';
if($result_header_show->num_rows){
    while($row_header = $result_header_show->fetch_assoc()){
      $str_header .= $row_header['cate_name'].'>>';
    }
}

$pages = new page($result->num_rows,12);
$sqllist = $sql . " ORDER BY `id` DESC" . $pages->limitsql;
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
        <th>分类名称：</th>
        <td>
        	<input type="text" name="category_name" value="<?php echo $_GET['category_name'] ?>" class="input_txt" />
        </td>
        <th>分类代码:</th>
        <td>
        	<input type="text" name="category_code" value="<?php echo $_GET['category_code'] ?>" class="input_txt" />
        </td>
        <td><input type="submit" name="submit" value="查询" class="button" />
          <input type="button" name="button" value="添加" class="button" onclick="location.href='category_add.php?action=add'" />
          <input type="text" style="display:none;" /></td>
      </tr>
    </table>
  </form>
</div>
<p style="background:#eee;color:orange;padding-left:7px"><?php echo $str_header ?></p>
<div id="table_list">
  <?php if($result->num_rows){ ?>
  <form action="other_thing_do.php" name="system_list" method="post">
    <table>
      <tr>
        <th width="4%">ID</th>
        <th width="10%">事项分类名称</th>
        <th width="10%">事项分类代码</th>
        <th width="4%">status</th>
        <th width="4%">Edit</th>
        <th width="4%">Info</th>
      </tr>
      <?php while($row = $result->fetch_assoc()){
		      $id = $row['id'];
	    ?>
      <tr>
        <td><input type="checkbox" name="id[]" <?php if(in_array($thingid,$array_thingid)){ echo 'disabled'; } ?> value="<?php echo $thingid; ?>" /></td>
        <td><?php echo $row['cate_name']; ?></td>
        <td><?php echo $row['cate_code']; ?></td>
        <td><?php echo $array_status[$row['status']]; ?></td>
        <td>
            <a href="systemae.php?id=<?php echo $systemid; ?>&action=edit">
                <img src="../images/system_ico/edit_10_10.png" width="15" />
            </a>
        </td>
        <td>
          <?php if(in_array($id,$arr_pid)){ ?>
            <a href="category_list.php?pid=<?php echo $id; ?>">
                <img src="../images/system_ico/info_8_10.png" width="15" />
            </a>
          <?php } ?>
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
