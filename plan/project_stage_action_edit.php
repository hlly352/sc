<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
$infoid   = fun_check_int($_GET['infoid']);
$actionid = $_GET['actionid'];
$date     = fun_getdate();
$sqlwhere = '';
if($actionid){
	$sqlwhere = " AND `sc_project_stage_action`.`actionid` = '$actionid'";
}
//查询计划详情
$sql = "SELECT `sc_project_stage_action`.`finish_time`,`sc_project_stage_action`.`actionid`,`sc_project_plan_info`.`stage_name`,`sc_project_stage_action`.`action_name`,`sc_project_stage_action`.`start_date`,`sc_project_stage_action`.`end_date`,DATEDIFF(`sc_project_stage_action`.`end_date`,`sc_project_stage_action`.`start_date`) AS `offset_date`,`sc_project_stage_action`.`weights`,`sc_project_stage_action`.`is_action` FROM `sc_project_stage_action` INNER JOIN `sc_project_plan_info` ON `sc_project_plan_info`.`infoid` = `sc_project_stage_action`.`infoid` WHERE `sc_project_plan_info`.`infoid` = '$infoid' $sqlwhere ORDER BY `sc_project_stage_action`.`start_date` ASC";
$result = $db->query($sql);
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
<script src="../js/code/highcharts.js"></script>
<script src="../js/code/modules/exporting.js"></script>
<script src="../js/code/modules/xrange.js"></script>
<script src="../js/code/modules/oldie.js"></script>
<script src="../js/code/themes/grid-light.js"></script>
<script type="text/javascript">
	$(function(){
		$('.action').live('click',function(){
			//删除已有的输入框
			$('#do_time').remove();
			//把时间单元格变为可输入
			var inp = '<input type="text" id="do_time" value="<?php echo date('Y-m-d H:i:s') ?>" onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\',isShowClear:false,readOnly:true})">';
			$(this).parent().prev().append(inp);
			$(this).attr('class','complete');
			$(this).children().attr('src','../images/system_ico/query.png');
		})
		//点击确定完成动作
		$('.complete').live('click',function(){
			var id = $(this).attr('id');
			var actionid = id.substr((id.lastIndexOf('_') + 1));
			var do_time = $('#do_time').val();
			$.ajax({
				'url':'../ajax_function/set_project_action_time.php',
				'data':{actionid:actionid,do_time:do_time},
				'type':'post',
				'success':function(data){
					if(data){
						window.location.reload();
					}
				}
			})
			
		})
		//撤销操作
		$('.cancel').live('click',function(){
			var id = $(this).attr('id');
			var actionid = id.substr((id.lastIndexOf('_') + 1));
			$.ajax({
				'url':'../ajax_function/cancel_project_action_time.php',
				'data':{actionid:actionid},
				'type':'post',
				'success':function(data){
					if(data){
						window.location.reload();
					}
				}
			})			
		})
	})
</script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_search">
  <h4>项目实施情况</h4>
</div>
<div id="table_list">
  <?php if($result->num_rows){ ?>
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
        <th>Edit</th>
      </tr>
      <?php
      	$total_date = 0;
      	while($row = $result->fetch_assoc()){
      		$total_date += $row['offset_date'];
      		$actionid = $row['actionid'];
      		//规定时间内未完成的步骤标红
      		$is_bg = '';
      		if($row['start_date'] <= $date && $row['end_date'] && $row['is_action'] == 0){
      			$is_bg = 'style="background-color:orange"';
      		}
		  ?>
      <tr <?php echo $is_bg; ?>>
        <td><input type="checkbox" name="id[]" <?php echo $row['is_action'] == 1?'disabled':'' ?> value="<?php echo $actionid; ?>" /></td>
        <td><?php echo $row['stage_name']; ?></td>
        <td><?php echo $row['action_name']; ?></td>
        <td><?php echo $row['start_date'] ?></td>
 		<td><?php echo $row['end_date'] ?></td>
 		<td><?php echo $row['offset_date']; ?></td>     
        <td><?php echo $row['weights']."%" ?></td>
        <td><?php echo $row['finish_time'] ?></td>
        <td>
        	<a href="#" class="<?php echo $row['is_action'] == 0?'action':'cancel' ?>" id="action_<?php echo $actionid; ?>">
        	 	<?php if($row['is_action'] == 0){ ?>
        			<img src="../images/system_ico/edit.png" width="20">
        		<?php }else{
        			echo '<img src="../images/system_ico/cancel.png" width="20">';
        			} ?>
        	</a>
        </td>
      </tr>
      <?php } ?>
	<tr>
		<td colspan="5">合计</td>
		<td><?php echo $total_date; ?></td>
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="9">
			<input type="button" value="返回" onclick="window.location.href='project_plan_list.php'" class="button"/>
		</td>
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
<?php include "../footer.php"; ?>
</body>
</html>
