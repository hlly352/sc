<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once 'shell.php';
$action = fun_check_action($_GET['action']);
//查询重要事项
$sql_cate = "SELECT `id`,CONCAT(`cate_code`,'-',`cate_name`) AS `cate` FROM `sc_category` WHERE `pid` = (SELECT `id` FROM `sc_category` WHERE `cate_code` = 'ZY')";
$result_cate = $db->query($sql_cate);
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
<script type="text/javascript">
</script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_sheet">
  <?php if($action == "add"){ ?>
  <h4>时间记录添加</h4>
  <form action="time_usage_do.php" name="system" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <th width="20%">事项：</th>
        <td width="80%">
           <select name="cateid" class="input_txt">
                <option value="">请选择</option>
                <?php
                    if($result_cate->num_rows){
                        while($row_cate = $result_cate->fetch_assoc()){
                            echo '<option value="'.$row_cate['id'].'">'.$row_cate['cate'].'</option>';
                        }
                    }
                ?>
           </select>
        </td>
      </tr>
      <tr>
          <th>日期：</th>
          <td>
              <input type="text" name="date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isClear:false,readOnly:true})" value="<?php echo $row['do_date'] ?>" class="input_txt" />
          </td>
      </tr>
      <tr>
          <th>开始时间：</th>
          <td>
            <input type="text" name="start_time" onfocus="WdatePicker({dateFmt:'HH:mm',isClear:false,readOnly:true})" value="<?php echo $row['start_time'] ?>" class="input_txt" />
          </td>
      </tr>
    <tr>
          <th>结束时间：</th>
          <td>
            <input type="text" name="end_time" onfocus="WdatePicker({dateFmt:'HH:mm',isClear:false,readOnly:true})" value="<?php echo $row['end_time'] ?>" class="input_txt" />
          </td>
      </tr>
      <tr>
          <th>用时：</th>
          <td>
            <input type="text" name="offset_time" readonly class="input_txt" />
          </td>
      </tr>    
      <tr id="remark">
        <th>备注：</th>
        <td>
          <input type="text" name="remark" value="<?php echo $row['remark'] ?>" class="input_txt" />
      </tr>
      <tr>
        <th>&nbsp;</th>
        <td><input type="submit" name="submit" id="submit" value="确定" class="button" />
          <input type="button" name="button" value="返回" class="button" onclick="javascript:history.go(-1);" />
          <input type="hidden" name="action" value="<?php echo $action; ?>" />
      </tr>
    </table>
  </form>
  <?php
    }
  ?>
</div>
<?php include "../footer.php"; ?>
</body>
</html>