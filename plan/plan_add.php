<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once 'shell.php';
$action = fun_check_action($_GET['action']);
$categoryid = $_GET['categoryid'];
if($categoryid == 'W'){
	$parent_category = 'M';
}elseif($categoryid == 'M'){
	$parent_category = 'Y';
}
$date = fun_getdate();
//查询计划事件
$sql_cate = "SELECT `id`,CONCAT(`cate_code`,'-',`cate_name`) AS `cate` FROM `sc_category` WHERE `pid` = (SELECT `id` FROM `sc_category` WHERE `cate_code` = 'JHSX')";
//通过计划类型查询上一级的计划
$sql_parent_plan = "SELECT * FROM `sc_plan_data` INNER JOIN `sc_plan_info` ON `sc_plan_data`.`planid` = `sc_plan_info`.`planid` INNER JOIN `sc_category` ON `sc_plan_info`.`cateid` = `sc_category`.`id` WHERE `categoryid` = '$parent_category' AND `sc_plan_data`.`type` = 'S' AND `sc_plan_data`.`start_date` <= '$date' AND `sc_plan_data`.`end_date` >= '$date' ";
$array_goal_num = array();
$result_parent = $db->query($sql_parent_plan);
if($result_parent->num_rows){
  while($row_cateid = $result_parent->fetch_assoc()){
    $array_goal_num[$row_cateid['cateid']] += 1;
  }
}
$result_parent_plan = $db->query($sql_parent_plan);
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

	//选择计划类型传递到地址栏
	$('#categoryid').live('change',function(){
		var categoryid = $(this).val();
		var loc = window.location.href;
		loc = loc.substr(0,loc.indexOf('?'));
		location.assign(loc+'?action=add&categoryid='+categoryid);
	})
	//计算时间差
      	$('input[name=start_date],input[name=end_date]').live('blur',function(){
      		var start_date = $('input[name=start_date]').val();
      		var end_date   = $('input[name=end_date]').val();
      		start_date = new Date(start_date).getTime();
      		end_date   = new Date(end_date).getTime();
      		var offset_date = end_date - start_date;
      		offset_date = Math.ceil(offset_date /3600/1000/24);
      		$('#offset_date').val(offset_date);

      	})	
  //添加新的目标段
  $('input[class ^= add_date]').live('click',function(){
  		//获取上一个目标段的类型数
  		var index = $(this).parent().prevAll().children('input[name ^= goal]').attr('name');
  		index = index.substr((index.lastIndexOf('_') + 1),1);
  		var new_date = '<tr class="stages date_'+index+'"><td><td><th>目标：</th>        <td>        	<input type="text" name="goal_'+index+'[]"  class="input_txt" />        </td>        <th>备注：</th>        <td>        	<input type="text" name="remark_'+index+'[]" class="input_txt" />	        </td><td><input type="button" value="添加目标" class="add_date_'+index+' button" /> <input type="button" value="删除目标" class="remove_date_'+index+' button"></td></tr>';
  		$(this).parent().parent().after(new_date);
  		$(this).remove();
  		$('.remove_date_'+index).not('.remove_date_'+index+':last').remove();
  })
  //删除目标
  $('input[class ^= remove_date]').live('click',function(){
  		//删除当前行
  		$(this).parent().parent().remove();
  		var cla = $(this).attr('class');
  		var index = cla.substring((cla.lastIndexOf('_') + 1),(cla.lastIndexOf('button')));

  		//给前面一行加入按钮
		var add_date = '<input type="button" value="添加目标" class="add_date_'+index+' button">';
  		var remove_date = ' <input type="button"  value="删除目标" class="remove_date_'+index+' button" />';
  		index = parseInt(index);
  		$('.date_'+index+':last').children('td:last').append(add_date);
  		//当前类型有多少目标
  		var date_num = $('.date_'+index).size();
  		if(date_num > 1){
  			$('.date_'+index+':last').children('td:last').append(remove_date);
  		}

  })
  //添加新类型
  $('.add_new_stage').live('click',function(){
  		//查看一共有多少类型
  		var stage_num = $('.stage_name').size();
  		$('#remove_stage').remove();
  		var new_stage = '<tr class="stages date_'+stage_num+'">        <th>类型：'+(stage_num + 1)+'</th>        <td>     <select name="cateid[]" class="input_txt stage_num">            <option value="">请选择</option>              <?php
                  $result_cate = $db->query($sql_cate);
                  if($result_cate->num_rows){
                      while($row_cate = $result_cate->fetch_assoc()){
                         echo '<option value="'.$row_cate['id'].'">'.$row_cate['cate'].'</option>';
                      }
                  }
              ?>          </select>        	<input type="button" value="添加类型" class="button add_new_stage"> <input type="button" value="删除类型" class="button" id="remove_stage">       </td>        <th>目标：</th>        <td>        	<input type="text" name="goal_'+stage_num+'[]" class="input_txt" />        </td>        <th>备注：</th>        <td>        	<input type="text" name="remark_'+stage_num+'[]"  class="input_txt"  />	        </td>        <td>        	<input type="button" value="添加目标" class="add_date_'+stage_num+' button" />        </td>      </tr>';
  		$('.stages:last').after(new_stage);
  		$(this).remove();
  })
  //删除类型
  $('#remove_stage').live('click',function(){
  		var stage_num = $('select[name ^= cateid]').size();
  		$('.date_'+(stage_num - 1)).remove();
  		//添加按钮
  		var add_stage = '<input type="button" value="添加类型" class="button add_new_stage">';
  		var remove_stage = '<input type="button"  value="删除类型" class="button" id="remove_stage" />';
  		var last_stage = $('select[name ^= cateid]:last'); 
  		last_stage.after(add_stage);
  		//项目数大于1时添加删除按钮
  		if(stage_num > 2){
  			last_stage.parent().append(remove_stage);
  		}
  })
  //提交时判断项目名称和目标是否填写
  $("#submit").click(function(){
    var start_date = $("#start_date").val();
    if(!start_date){
      $("#start_date").focus();
      return false;
    }
    var end_date = $('#end_date').val();
    if(!end_date){
    	$('#end_date').focus();
    	return false;
    }
  })
})
</script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_list">
  <?php if($result_parent_plan->num_rows){ ?>
    <table>
      <tr>
        <th width="4%">ID</th>
        <th width="10%">类型</th>
        <th width="10%">目标</th>
        <th width="10%">备注</th>
        <th width="10%">完成时间</th>
        <th width="10%">状态</th>
      </tr>
      <?php
      while($rows = $result_parent_plan->fetch_assoc()){
		  $infoid = $rows['infoid'];
      $cateid = $rows['cateid'];
      $cate_name = $rows['cate_name'];
	  ?>
      <tr>
        <td><input type="checkbox" name="id[]" <?php echo $row['task_status'] == '1'?'disabled':''; ?> value="<?php echo $infoid; ?>" /></td>
        <?php if($cols_num = $array_goal_num[$cateid]){ ?>
          <td rowspan="<?php echo $cols_num ?>"><?php echo $cate_name; ?></td>
        <?php } ?>
        <td><?php echo $rows['goal']; ?></td>
        <td><?php echo $rows['remark'] ?></td>   
        <td><?php echo $rows['finish_time'] ?></td> 
        <td>
        	<a href="#" class="<?php echo $rows['is_complete'] == 0?'action':'cancel' ?>" id="info_<?php echo $infoid; ?>">
        	 	<?php if($rows['is_complete'] == 0){ ?>
        			<img src="../images/system_ico/edit.png" width="20">
        		<?php }else{
        			echo '<img src="../images/system_ico/cancel.png" width="20">';
        			} ?>
        	</a>
        </td>   
      </tr>
      <?php
        $array_goal_num[$cateid] = null;
       } }?>
    </table>
</div>
<div id="table_sheet">
  <?php if($action == "add"){ ?>
  <h4>添加新项目</h4>
  <form action="plan_do.php" name="system" method="post" enctype="multipart/form-data">
    <table>
       <tr>
      	<th>计划类型：</th>
      	<td>
      		<select name="categoryid" id="categoryid" class="input_txt">
      			<?php
      				foreach($array_plan_category as $ks=>$category){
      					$is_select = $categoryid == $ks?'selected':'';
      					echo '<option '.$is_select.' value="'.$ks.'">'.$category.'</option>';
      				}
      			?>
      		</select>
      	</td>
        <th>开始日期：</th>
        <td>
            <input type="text" id="start_date"  name="start_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})" class="input_txt" />
        </td>
        <th>结束日期：</th>
		<td>
            <input type="text" id="end_date"  name="end_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})" class="input_txt" />
        </td>   
        <td>
          总天数:
          <input type="text" readonly id="offset_date" class="input_txt" />
        </td>
      </tr>  
      <tr class="stages date_0">
        <th>类型：1</th>
        <td>
        	 <select name="cateid[]" class="input_txt stage_name">
            <option value="">请选择</option>
              <?php
                  $result_cate = $db->query($sql_cate);
                  if($result_cate->num_rows){
                      while($row_cate = $result_cate->fetch_assoc()){
                         echo '<option value="'.$row_cate['id'].'">'.$row_cate['cate'].'</option>';
                      }
                  }
              ?>
          </select> 
        	<input type="button" value="添加类型" class="button add_new_stage">
        </td>
        <th>目标：</th>
        <td>
        	<input type="text" name="goal_0[]" class="input_txt" />
        </td>
        <th>备注：</th>
        <td>
        	<input type="text" name="remark_0[]" class="input_txt" />	
        </td>
        <td>
        	<input type="button" value="添加目标" class="add_date_0 button" />
        </td>
      </tr>
      <tr id="note">
        <th>备注：</th>
        <td colspan="6">
        	<input type="text" name="note" class="input_txt" />
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
