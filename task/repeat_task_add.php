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
<script type="text/javascript" src="../js/My97DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="../js/main.js"></script>
<script language="javascript" type="text/javascript">
function change_rule_type(){
    var rule_type = $('#rule_type').val();
    //通过规则类型获取对应的规则
    $.ajax({
        'url':'../ajax_function/change_rule_type.php',
        'data':{rule_type:rule_type},
        'type':'post',
        'dataType':'json',
        'async':true,
        'success':function(data,status){
            var opt = '';
            $('#ruleid').empty();
            for(k in data){
                 opt += '<option value="'+k+'">'+data[k]+'</option>';
            }
          $('#ruleid').append(opt);
        }
    })
}
$(function(){
  change_rule_type();
  $('#rule_type').live('change',function(){
      change_rule_type();
  })
	$("#submit").click(function(){
		var task_name = $("#task_name").val();
		if(!task_name){
			$("#task_name").focus();
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
  <h4>记忆任务添加</h4>
  <form action="repeat_task_do.php" name="system" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <th width="20%">任务名称：</th>
        <td width="80%">
            <input type="text" id="task_name" name="task_name" class="input_txt" />
            <span class="tag"> *必填</span></td>
        </td>
      </tr>
      <tr>
        <th>规则类型：</th>
        <td>
          <select name="task_type" class="input_txt" id="rule_type">
            <?php
              foreach($array_rule_type as $k=>$rule){
                  echo '<option value="'.$k.'">'.$rule.'</option>';
              }
            ?>
          </select>
        </td>
      </tr>
      <tr class="new_rule">
        <th>规则：</th>
        <td>
            <select name="ruleid" id="ruleid" class="input_txt">
               
            </select>
        </td>
      </tr>
      <tr>
          <th>日期：</th>
          <td>
              <input type="text" name="start_date" onfocus="WdatePicker({Fmtdate:'yyyy-MM-dd',isClear:false,readOnly:true})" value="<?php echo date('Y-m-d') ?>" class="input_txt" />
          </td>
      </tr>
      <tr>
          <th>内容：</th>
          <td>
              <input type="text" name="content" class="input_txt" />
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