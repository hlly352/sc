<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once 'shell.php';
$action = fun_check_action($_GET['action']);
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
        var info_num = $('#remark').prevAll('tr').size();
        var add_info = '<tr class="new_rule">        <th>'+info_num+'</th>        <td><input type="text" name="rule_info[]" id="system_name" class="input_txt" />        <button type="button" id="add" class="button">添加</button>   <button type="button" id="rem" class="button">删除</button> </td>  </tr>';
        $(this).next().remove();
        $(this).remove();
        $('#remark').before(add_info);

     })
    $('#rem').live('click',function(){
        var add_but = '<button type="button" id="add" class="button">添加</button>';
        var rem_but = ' <button type="button" id="rem" class="button">删除</button>';
        $('.new_rule:last').remove();
        $('.new_rule:last').children('td').append(add_but);
        var new_rule_num = $('.new_rule').size();
        if(new_rule_num > 1){
             $('.new_rule:last').children('td').append(rem_but);
        }

    })
	$("#submit").click(function(){
		var rule_name = $("#rule_name").val();
		if(!rule_name){
			$("#rule_name").focus();
			return false;
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
  <h4>记忆规则添加</h4>
  <form action="repeat_rule_do.php" name="system" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <th width="20%">规则名称：</th>
        <td width="80%">
            <input type="text" id="rule_name" name="rule_name" class="input_txt" />
            <span class="tag"> *必填</span></td>
        </td>
      </tr>
      <tr> 
        <th>规则类型：</th>
        <td>
          <select class="input_txt" name="rule_type">
            <?php
                foreach($array_rule_type as $k=>$rule){
                  echo '<option value="'.$k.'">'.$rule.'</option>';
                }
            ?>
          </select>
        </td>
      </tr>
      <tr class="new_rule">
        <th>规则详情：1</th>
        <td><input type="text" name="rule_info[]" id="system_name" class="input_txt" />
        <button type="button" id="add" class="button">添加</button>
        </td>
      </tr>
      <tr id="remark">
        <th>备注：</th>
        <td><input type="text" name="remark" class="input_txt" />
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
	  $systemid = fun_check_int($_GET['id']);
	  $sql = "SELECT * FROM `db_system` WHERE `systemid` = '$systemid'";
	  $result = $db->query($sql);
	  if($result->num_rows){
		  $array = $result->fetch_assoc();
		  $image_filedir = $array['image_filedir'];
		  $image_filename = $array['image_filename'];
		  $image_filepath = "../upload/system/".$image_filedir.'/'.$image_filename;
		  $image_info = (is_file($image_filepath))?"<img src=\"".$image_filepath."\" />":"<img src=\"../images/no_image_60_60.png\" width=\"60\" height=\"60\" />";
  ?>
  <h4>系统程序修改</h4>
  <form action="systemdo.php" name="system" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <th width="20%">类型：</th>
        <td width="80%"><select name="system_type" id="system_type">
            <option value="">请选择</option>
            <?php foreach($array_system_type as $system_type_key=>$system_type_value){ ?>
            <option value="<?php echo $system_type_key; ?>"<?php if($system_type_key == $array['system_type']) echo " selected=\"selected\""; ?>><?php echo $system_type_value; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <th>名称：</th>
        <td><input type="text" name="system_name" id="system_name" value="<?php echo $array['system_name']; ?>" class="input_txt" />
          <span class="tag"> *必填</span></td>
      </tr>
      <tr>
        <th>目录：</th>
        <td><input type="text" name="system_dir" id="system_dir" value="<?php echo $array['system_dir']; ?>" class="input_txt" />
          <span class="tag"> *必填</span></td>
      </tr>
      <tr>
        <th>排序：</th>
        <td><input type="text" name="system_order" id="system_order" value="<?php echo $array['system_order']; ?>" class="input_txt" />
          <span class="tag"> *必填，数字</span></td>
      </tr>
      <tr>
        <th>状态：</th>
        <td><select name="system_status">
            <?php foreach($array_status as $status_key=>$status_value){ ?>
            <option value="<?php echo $status_key; ?>"<?php if($status_key == $array['system_status']) echo " selected=\"selected\""; ?>><?php echo $status_value; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <th>图标：</th>
        <td><?php echo $image_info; ?></td>
      </tr>
      <tr>
        <th>更新图标：</th>
        <td><input type="file" name="file" class="input_file" />
          <span class="tag"> *图片尺寸60*60，png格式</span></td>
      </tr>
      <tr>
        <th>&nbsp;</th>
        <td><input type="submit" name="submit" id="submit" value="确定"class="button" />
          <input type="button" name="button" value="返回" class="button" onclick="javascript:history.go(-1);" />
          <input type="hidden" name="systemid" value="<?php echo $systemid; ?>" />
          <input type="hidden" name="action" value="<?php echo $action; ?>" /></td>
      </tr>
    </table>
  </form>
  <?php
	  }else{
		  echo "<p class=\"tag\">系统提示：暂无记录！</p>";
	  }
  }
  ?>
</div>
<?php include "../footer.php"; ?>
</body>
</html>