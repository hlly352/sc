<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once 'shell.php';
$action = fun_check_action($_GET['action']);
//查询普通事件和重要事件
$sql_item = "SELECT `id`,CONCAT(`cate_code`,'-',`cate_name`) AS `cate` FROM `sc_category` WHERE `pid` = (SELECT `id` FROM `sc_category` WHERE `cate_code` = 'ZC') AND `id` NOT IN(SELECT `expend_cate` FROM `sc_expend_rule_info` INNER JOIN `sc_expend_rule` ON `sc_expend_rule_info`.`ruleid` = `sc_expend_rule`.`ruleid` WHERE `sc_expend_rule`.`is_select` = '1')";
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
  //添加新阶段
  $('.add_new_item').live('click',function(){
      //查看一共有多少阶段
      var item_num = $('.item_id').size();
      $('#remove_item').remove();
      var prev_time = $(this).parent().prev().prev().prev().prev().prev().children().val();

      var new_item = '<tr class="items date_'+item_num+'">        <th>计划：'+(item_num + 1)+'</th>        <td>        <select name="item_id[]" class="input_txt item_id">     <option value="">请选择</option>         <?php
              $result_item = $db->query($sql_item);
                  if($result_item->num_rows){
                      while($row_item = $result_item->fetch_assoc()){
                         echo '<option value="'.$row_item['id'].'">'.$row_item['cate'].'</option>';
                      }
                  }
              ?>          </select>         </td>       <th>金额：</th>        <td>         <input type="text" name="amount[]" class="input_txt"   />         </td>    <th>重要事项：</th><td><input type="text" name="thing[]" class="input_txt" /><th>备注：</th><td><input type="text" name="remark[]" class="input_txt"/></td> </td>   <td>      <input type="button" value="添加阶段" class="button add_new_item"> <input type="button" value="删除阶段" class="button" id="remove_item">        </td>      </tr>';
      $('.items:last').after(new_item);
      $(this).remove();
  })
  //删除阶段
  $('#remove_item').live('click',function(){
      var item_num = $('.item_id').size();
      $('.date_'+(item_num - 1)).remove();
      //添加按钮
      var add_item = '<input type="button" value="添加阶段" class="button add_new_item">';
      var remove_item = ' <input type="button"  value="删除阶段" class="button" id="remove_item" />';
      var last_item = $('.items:last').children('td:last');
      last_item.append(add_item);
      //项目数大于1时添加删除按钮
      if(item_num > 2){
        last_item.append(remove_item);
      }
  })
  //提交时判断项目名称和目标是否填写
  $("#submit").click(function(){
    
  })
})
</script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_sheet">
  <?php if($action == "add"){ ?>
  <h4>添加财务计划</h4>
  <form action="financial_plan_do.php" name="system" method="post" enctype="multipart/form-data">
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
        <th>总天数:</th>
        <td>
          <input type="text" readonly id="offset_date" class="input_txt" />
        </td>
      </tr>  
      <tr class="items date_0">
        <th>计划：1</th>
        <td>
          <select name="item_id[]" class="input_txt item_id">
            <option value="">请选择</option>
              <?php
                  $result_item = $db->query($sql_item);
                  if($result_item->num_rows){
                      while($row_item = $result_item->fetch_assoc()){
                         echo '<option value="'.$row_item['id'].'">'.$row_item['cate'].'</option>';
                      }
                  }
              ?>
          </select>
        </td>
        <th>金额：</th>
        <td>
          <input type="text" name="amount[]" value="" class="input_txt" />
        </td>
        <th>重要事项：</th>
        <td>
          <input type="text" name="thing[]"  class="input_txt" />
        </td>
        <th>备注：</th>
        <td>
          <input type="text" name="remark[]" class="input_txt" />
        </td>
        <td>
          <input type="button" value="添加阶段" class="button add_new_item">
        </td>
      </tr>
      <tr>
      	<th>备注：</th>
      	<td>
      		<input type="text" name="note" class="input_txt" />
      	</td>
      </tr>
      <tr>
        <td colspan="11" style="text-align:center">
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
