<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
//查询所有的一级类目
$sqlwhere = " AND `pid` = (SELECT `id` FROM `sc_category` WHERE `cate_code` = 'ZC')";
$sql_cate = "SELECT `id`,CONCAT(`cate_code`,'-',`cate_name`) AS `cate` FROM `sc_category` WHERE `pid` = '0'";
$result_cate = $db->query($sql_cate);
if($_GET['submit']){
    $cateid = trim($_GET['cateid']);
    if($cateid){
    	$sqlwhere = " AND `pid` = '$cateid'";
	}
}
//查询收入记录
$sql = "SELECT `cate_name`,`plan_value`,`real_value`,(`plan_value` - `real_value`) AS `surplus` FROM `sc_category` WHERE `status` = '1' $sqlwhere";

$result = $db->query($sql);
$pages = new Page($result->num_rows,15);
$sqllist = $sql." ORDER BY `id` ASC".$page->limitsql;
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
<script type="text/javascript" src="../js/My97DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="../js/main.js"></script>
<script language="javascript" type="text/javascript" src="../js/code/highcharts.js"></script>
<script language="javascript" type="text/javascript" src="../js/code/modules/series-label.js" charset="utf-8"></script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_search">
  <h4>类目汇总</h4>
  <form action="" method="get">
    <table id="search">
      <tr>
        <th>类目：</th>
        <td>
           <select name="cateid" class="input_txt">
                  <option value="">所有</option>
                  <?php
                    if($result_cate->num_rows){
                        while($row_cate = $result_cate->fetch_assoc()){
                            echo '<option value="'.$row_cate['id'].'">'.$row_cate['cate'].'</option>';
                        }
                    }
                  ?>
              </select>            
        </td>
        <td>
          <input type="submit" name="submit" value="查询" class="button">
        </td>
      </tr>
    </table>
   
  </form>
</div>
<div id="table_list">
  <?php if($result->num_rows){ ?>
  <form action="systemdo.php" name="system_list" method="post">
    <table>
      <tr>
        <th width="4%">ID</th>
        <th width="10%">类目</th>
        <th width="10%">总计划</th>
        <th width="10%">实际使用</th>
        <th width="20%">结余</th>
        <th width="4%">status</th>
      </tr>
      <?php
      $i = 1;
      while($row = $result->fetch_assoc()){
    ?>
      <tr>
        <td><input type="checkbox" name="id[]" value="<?php echo $incomeid; ?>" /></td>
        <td><?php echo $row['cate_name']; ?></td>
        <td><?php echo $row['plan_value'] ?></td>
        <td><?php echo $row['real_value'] ?></td>
        <td><?php echo $row['surplus'] ?></td>  
        <td><a href="memory_task_edit.php?id=<?php echo $infoid; ?>&action=edit"><img src="../images/system_ico/edit_10_10.png" width="10" height="10" /></a></td>
      </tr>
      <?php 
        $i++;
      } ?>
    </table>
    <div id="page">
	   <?php echo $pages->getPage(); ?>
    </div>
  </form>
  <?php
  }else{
    echo "<p class=\"tag\">系统提示：暂无记录！</p>";
  }
  ?>
</div>
<div>
</div>
<div id="container" style="width: 90%; height: 400px; margin: 0 auto"></div>
<?php include "../footer.php"; ?>
</body>
</html>
