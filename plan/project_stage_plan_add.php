<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once 'shell.php';
$infoid = fun_check_int($_GET['infoid']);
$action = fun_check_action($_GET['action']);
//查询计划详情
$sql_action = "SELECT `sc_project_stage_action`.`finish_time`,`sc_project_stage_action`.`actionid`,`sc_project_plan_info`.`stage_name`,`sc_project_stage_action`.`action_name`,`sc_project_stage_action`.`start_date`,`sc_project_stage_action`.`end_date`,DATEDIFF(`sc_project_stage_action`.`end_date`,`sc_project_stage_action`.`start_date`) AS `offset_date`,`sc_project_stage_action`.`weights`,`sc_project_stage_action`.`is_action` FROM `sc_project_stage_action` INNER JOIN `sc_project_plan_info` ON `sc_project_plan_info`.`infoid` = `sc_project_stage_action`.`infoid` WHERE `sc_project_plan_info`.`infoid` = '$infoid' ORDER BY `sc_project_stage_action`.`start_date` ASC";
$result_action = $db->query($sql_action);
//查找项目阶段详情
$sql = "SELECT * FROM `sc_project_plan_info` INNER JOIN `sc_project_plan` ON `sc_project_plan_info`.`planid` = `sc_project_plan`.`planid` WHERE `sc_project_plan_info`.`infoid` = '$infoid'";
$result = $db->query($sql);
if($result->num_rows){
	$row = $result->fetch_assoc();
}
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
<script type="text/javascript" src="../js/My97DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript">
$(function(){
    $('#add').live('click',function(){
        var info_num = $('.new_rule').size() + 1;

        var add_info = ' <tr class="new_rule">        <th>计划详情：'+info_num+'</th>        <td>        	<input type="text" name="action_name[]" id="system_name" class="input_txt" />        </td>    <th>开始日期：</th>        <td>        	<input type="text" name="start_date[]" onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd\',isShowClear:false,readOnly:true})" class="input_txt" />        </td>        <th>结束日期：</th>        <td>        	<input type="text" name="end_date[]" onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd\',isShowClear:false,readOnly:true})" class="input_txt" />        </td>     <th>权重：</th>        <td>        	<input type="text" name="weights[]" class="input_txt" />      	</td>      	<td>        	<button type="button" id="add" class="button">添加</button>   <button type="button" id="rem" class="button">删除</button>        </td>      </tr>     ';
        $(this).next().remove();
        $(this).remove();
        $('#buts').before(add_info);

     })
    $('#rem').live('click',function(){
        var add_but = '<button type="button" id="add" class="button">添加</button>';
        var rem_but = ' <button type="button" id="rem" class="button">删除</button>';
        $('.new_rule:last').remove();
        $('.new_rule:last').children('td:last').append(add_but);
        var new_rule_num = $('.new_rule').size();
        if(new_rule_num > 1){
             $('.new_rule:last').children('td:last').append(rem_but);
        }

    })
})
</script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
  <?php if($action == "add"){ ?>
  <div id="table_search">
    <h4>实施步骤</h4>
  </div>
<div id="table_list">
  <?php if($result_action ->num_rows){ ?>
  <form action="project_stage_action_do.php" name="system_list" method="post">
    <table>
      <tr>
        <th>ID</th>
        <th>阶段名</th>
        <th>实施步骤</th>
        <th>开始日期</th>
        <th>结束日期</th>
        <th>用时</th>
        <th>权重</th>
        <th>完成时间</th>
      </tr>
      <?php
        $total_date = 0;
        while($row_action = $result_action->fetch_assoc()){
          $total_date += $row_action['offset_date'];
          $total_weights += $row_action['weights'];
          $actionid = $row_action['actionid'];
      ?>
      <tr>
        <td><input type="checkbox" name="id[]" <?php echo $row_action['is_action'] == 1?'disabled':'' ?> value="<?php echo $actionid; ?>" /></td>
        <td><?php echo $row_action['stage_name']; ?></td>
        <td><?php echo $row_action['action_name']; ?></td>
        <td><?php echo $row_action['start_date'] ?></td>
    <td><?php echo $row_action['end_date'] ?></td>
    <td><?php echo $row_action['offset_date']; ?></td>     
        <td><?php echo $row_action['weights']."%" ?></td>
        <td><?php echo $row_action['finish_time'] ?></td>
      </tr>
      <?php } ?>
  <tr>
    <td colspan="5">合计</td>
    <td><?php echo $total_date; ?></td>
    <td><?php echo $total_weights.'%'; ?></td>
    <td></td>
  </tr>
    </table>
    <div id="checkall">
      <input name="all" type="button" class="select_button" id="CheckedAll" value="全选" />
      <input type="button" name="other" class="select_button" id="CheckedRev" value="反选" />
      <input type="button" name="reset" class="select_button" id="CheckedNo" value="清除" />
      <input type="submit" name="submit" id="submit" value="删除" class="select_button" onclick="JavaScript:return confirm('系统提示:确定删除吗?')" disabled="disabled" />
      <input type="hidden" name="action" value="del" />
    </div>
  </form>
  <?php
  }else{
    echo "<p class=\"tag\">系统提示：暂无记录！</p>";
  }
  ?>
</div>
<div id="table_sheet">
  <h4>阶段计划</h4>
  <form action="project_stage_action_do.php" name="system" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <th>项目名称：</th>
        <td><?php echo $row['project_name'] ?></td>
        <th>阶段名称：</th>
        <td><?php echo $row['stage_name'] ?></td>
      	<th>时间段：</th>
      	<td><?php echo $row['start_date'].' 至 '.$row['end_date'] ?></td>
      </tr>     
      <tr class="new_rule">
        <th>计划详情：1</th>
        <td>
        	<input type="text" name="action_name[]"  class="input_txt" />
        </td>
        <th>开始日期：</th>
        <td>
        	<input type="text" name="start_date[]" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})" class="input_txt" />
        </td>
        <th>结束日期：</th>
        <td>
        	<input type="text" name="end_date[]" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})" class="input_txt" />
        </td>
        <th>权重：</th>
        <td>
        	<input type="text" name="weights[]" class="input_txt" />
      	</td>
      	<td>
        	<button type="button" id="add" class="button">添加</button>
        </td>
      </tr>
      <tr id="buts">
        <td colspan="9" style="text-align:center">
        	<input type="submit" name="submit" id="submit" value="确定" class="button" />
            <input type="button" name="button" value="返回" class="button" onclick="javascript:location.href='project_plan_list.php';" />
            <input type="hidden" name="infoid" value="<?php echo $infoid ?>" />
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