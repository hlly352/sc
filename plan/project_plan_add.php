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
$(function(){
  //添加新的时间段
  $('input[class ^= add_date]').live('click',function(){
  		//获取上一个时间段的阶段数
  		var index = $(this).parent().prevAll().children('input[name ^= start_date]').attr('name');
  		index = index.substr((index.lastIndexOf('_') + 1),1);
  		console.log(index);
  		var new_date = '<tr class="stages date_'+index+'"><td><td><th>开始时间：</th>        <td>        	<input type="text" name="start_date_'+index+'[]" value="<?php echo date('Y-m-d') ?>" class="input_txt" onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd\',isShowClear:false,readOnly:true})"/>        </td>        <th>结束时间：</th>        <td>        	<input type="text" name="end_date_'+index+'[]" value="<?php echo date('Y-m-d') ?>" class="input_txt" onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd\',isShowClear:false,readOnly:true})" />	        </td><td><input type="button" value="添加时间" class="add_date_'+index+' button" /> <input type="button" value="删除时间" class="remove_date_'+index+' button"></td></tr>';
  		$(this).parent().parent().after(new_date);
  		$(this).remove();
  		$('.remove_date_'+index).not('.remove_date_'+index+':last').remove();
  })
  //删除时间
  $('input[class ^= remove_date]').live('click',function(){
  		//删除当前行
  		$(this).parent().parent().remove();
  		var cla = $(this).attr('class');
  		var index = cla.substring((cla.lastIndexOf('_') + 1),(cla.lastIndexOf('button')));

  		//给前面一行加入按钮
		var add_date = '<input type="button" value="添加时间" class="add_date_'+index+' button">';
  		var remove_date = ' <input type="button"  value="删除时间" class="remove_date_'+index+' button" />';
  		index = parseInt(index);
  		$('.date_'+index+':last').children('td:last').append(add_date);
  		//当前阶段有多少时间
  		var date_num = $('.date_'+index).size();
  		if(date_num > 1){
  			$('.date_'+index+':last').children('td:last').append(remove_date);
  		}

  })
  //添加新阶段
  $('.add_new_stage').live('click',function(){
  		//查看一共有多少阶段
  		var stage_num = $('.stage_name').size();
  		$('#remove_stage').remove();
  		var new_stage = '<tr class="stages date_'+stage_num+'">        <th>阶段：'+(stage_num + 1)+'</th>        <td>        	<input type="text" name="stage_name[]"  class="input_txt stage_name" />        	<input type="button" value="添加阶段" class="button add_new_stage"> <input type="button" value="删除阶段" class="button" id="remove_stage">       </td>        <th>开始时间：</th>        <td>        	<input type="text" name="start_date_'+stage_num+'[]" value="<?php echo date('Y-m-d') ?>" class="input_txt" onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd\',isShowClear:false,readOnly:true})"/>        </td>        <th>结束时间：</th>        <td>        	<input type="text" name="end_date_'+stage_num+'[]" value="<?php echo date('Y-m-d') ?>" class="input_txt" onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd\',isShowClear:false,readOnly:true})" />	        </td>        <td>        	<input type="button" value="添加时间" class="add_date_'+stage_num+' button" />        </td>      </tr>';
  		$('.stages:last').after(new_stage);
  		$(this).remove();
  })
  //删除阶段
  $('#remove_stage').live('click',function(){
  		var stage_num = $('input[name ^= stage_name]').size();
  		$('.date_'+(stage_num - 1)).remove();
  		//添加按钮
  		var add_stage = '<input type="button" value="添加阶段" class="button add_new_stage">';
  		var remove_stage = '<input type="button"  value="删除阶段" class="button" id="remove_stage" />';
  		var last_stage = $('input[name ^= stage_name]:last'); 
  		last_stage.after(add_stage);
  		//项目数大于1时添加删除按钮
  		if(stage_num > 2){
  			last_stage.parent().append(remove_stage);
  		}
  })
  //提交时判断项目名称和目标是否填写
  $("#submit").click(function(){
    var project_name = $("#project_name").val();
    if(!project_name){
      $("#project_name").focus();
      return false;
    }
    var aims = $('#aims').val();
    if(!aims){
    	$('#aims').focus();
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
  <h4>添加新项目</h4>
  <form action="project_plan_do.php" name="system" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <th>项目名称：</th>
        <td colspan="6">
            <input type="text" id="project_name"  name="project_name" class="input_txt" />
            <span class="tag"> *必填</span></td>
        </td>
      </tr>
      <tr>
        <th>预期目标：</th>
        <td colspan="6">
            <input type="text" id="aims"  name="aims" class="input_txt" />
            <span class="tag"> *必填</span></td>
        </td>
      </tr>      
      <tr class="stages date_0">
        <th>阶段：1</th>
        <td>
        	<input type="text" name="stage_name[]"  class="input_txt stage_name" />
        	<input type="button" value="添加阶段" class="button add_new_stage">
        </td>
        <th>开始时间：</th>
        <td>
        	<input type="text" name="start_date_0[]" value="<?php echo date('Y-m-d') ?>" class="input_txt" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})"/>
        </td>
        <th>结束时间：</th>
        <td>
        	<input type="text" name="end_date_0[]" value="<?php echo date('Y-m-d') ?>" class="input_txt" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})" />	
        </td>
        <td>
        	<input type="button" value="添加时间" class="add_date_0 button" />
        </td>
      </tr>
      <tr id="remark">
        <th>备注：</th>
        <td colspan="6">
        	<input type="text" name="remark" class="input_txt" />
        </td>
      </tr>
      <tr>
        <td colspan="7" style="text-align:center">
        	<input type="submit" name="submit" id="submit" value="确定"class="button" />
          	<input type="button" name="button" value="返回" class="button" onclick="javascript:history.go(-1);" />
          	<input type="hidden" name="action" value="<?php echo $action; ?>" />
        </td>
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