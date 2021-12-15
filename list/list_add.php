<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once 'shell.php';
$action = fun_check_action($_GET['action']);
//查询所有的清单类型
$sql_subject = "SELECT `id`,`cate_code`,`cate_name` FROM `sc_category` WHERE `pid` = (SELECT `id` FROM `sc_category` WHERE `cate_code` = 'QD')";

$result_subject = $db->query($sql_subject);
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
<script language="javascript" type="text/javascript">
$(function(){
    $('#add').live('click',function(){
        var info_num = $('#remark').prevAll('.new_list').size() + 1;

        var add_info = '<tr class="new_list">        <th>'+info_num+'</th>        <td><input type="text" name="list_info[]" id="system_name" class="input_txt" />         </td>  <th>说明：</th>        <td>        	<input type="text" name="list_description[]" class="input_txt" />        	<button type="button" id="add" class="button">添加</button>   <button type="button" id="rem" class="button">删除</button>        </td></tr>';
        $(this).next().remove();
        $(this).remove();
        $('#remark').before(add_info);

     })
    $('#rem').live('click',function(){
        var add_but = '<button type="button" id="add" class="button">添加</button>';
        var rem_but = ' <button type="button" id="rem" class="button">删除</button>';
        $('.new_list:last').remove();
        $('.new_list:last').children('td:last').append(add_but);
        var new_list_num = $('.new_list').size();
        if(new_list_num > 1){
             $('.new_list:last').children('td:last').append(rem_but);
        }

    })
})
</script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_sheet">
  <?php if($action == "add"){ ?>
  <h4>清单添加</h4>
  <form action="list_do.php" name="system" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <th width="20%">清单类型：</th>
        <td colspan="3">
            <select name="typeid" class="input_txt">
                <?php
                  if($result_subject->num_rows){
                      while($row_subject = $result_subject->fetch_assoc()){
                          echo '<option value="'.$row_subject['id'].'">'.$row_subject['cate_code'].'-'.$row_subject['cate_name'].'</option>';
                      }
                  }
                ?>
            </select>
        </td>
      </tr>
      <tr class="new_list">
        <th>规则详情：1</th>
        <td>
        	<input type="text" name="list_info[]" id="system_name" class="input_txt" />
        </td>
        <th>说明：</th>
        <td>
        	<input type="text" name="list_description[]" class="input_txt" />
        	<button type="button" id="add" class="button">添加</button>
        </td>
      </tr>
      <tr id="remark">
        <th>备注：</th>
        <td colsapn="3"><input type="text" name="remark" class="input_txt" />
          <span class="tag"> *必填</span></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <td><input type="submit" name="submit" id="submit" value="确定"class="button" />
          <input type="button" name="button" value="返回" class="button" onclick="javascript:history.go(-1);" />
          <input type="hidden" name="action" value="<?php echo $action; ?>" /></td>
      </tr>
    </table>
  </form>
  <?php
  }elseif($action == "edit"){
	  $listid = fun_check_int($_GET['listid']);
	  //查询清单信息
	  $sql_list = "SELECT `sc_list`.`list_number`,`sc_item_subject`.`subject_name`,`sc_list`.`dotime` FROM `sc_list` INNER JOIN `sc_item_subject` ON `sc_list`.`typeid` = `sc_item_subject`.`subjectid` WHERE `sc_list`.`listid` = '$listid'";
	  $result_list = $db->query($sql_list);
	  $arr_list = array();
	  if($result_list->num_rows){
	  	$arr_list = $result_list->fetch_assoc();
	  }
	  ?>
	  <h4>清单信息</h4>
	  <div id="table_sheet">
	  	<table>
		  	<tr>
		  		<th>清单号：</th>
		  		<td><?php echo $arr_list['list_number'] ?></td>
		  		<th>类型：</th>
		  		<td><?php echo $arr_list['subject_name'] ?></td>
		  		<th>时间：</th>
		  		<td><?php echo $arr_list['dotime'] ?></td>
		  	</tr>
		</table>
	  </div>
	  <h4>清单添加</h4>
  <form action="list_do.php" name="system" method="post" enctype="multipart/form-data">
    <table>
      <tr class="new_list">
        <th>清单详情：1</th>
        <td>
        	<input type="text" name="list_info[]" id="system_name" class="input_txt" />
        </td>
        <th>说明：</th>
        <td>
        	<input type="text" name="list_description[]" class="input_txt" />
        	<button type="button" id="add" class="button">添加</button>
        </td>
      </tr>
      <tr id="remark">
        <th>备注：</th>
        <td colsapn="3"><input type="text" name="remark" class="input_txt" />
          <span class="tag"> *必填</span></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <td><input type="submit" name="submit" id="submit" value="确定"class="button" />
          <input type="button" name="button" value="返回" class="button" onclick="javascript:history.go(-1);" />
          <input type="hidden" name="action" value="add" /></td>
          <input type="hidden" name="listid" value="<?php echo $listid; ?>">
      </tr>
    </table>
  </form>
  <?php
	  }else{
		  echo "<p class=\"tag\">系统提示：暂无记录！</p>";
	  }
  
  ?>
</div>
<?php include "../footer.php"; ?>
</body>
</html>