<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
if($_GET['submit']){
	$task_name = trim($_GET['task_name']);
	$sqlwhere = " AND `task_name` LIKE '%$task_name%'";
}
$taskid = trim($_GET['id']);
if($taskid){
	$sqlwhere .= " AND `taskid` = '$taskid'";
}
//获取当天的日期，星期数，和号数
$week_num = date('w');
$date     = fun_getdate();
$day_num  = date('d');
$sql = "SELECT * FROM `sc_normal_task` WHERE `task_status` IN('0','1','2') $sqlwhere GROUP BY `sc_normal_task`.`taskid`";
$result = $db->query($sql);
$pages = new page($result->num_rows,12);
$sqllist = $sql . " ORDER BY `sc_normal_task`.`dotime` DESC,`sc_normal_task`.`taskid` ASC" . $pages->limitsql;
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
<script language="javascript" type="text/javascript" src="../js/main.js"></script>
<script type="text/javascript">
  $(function(){
    //点击状态按钮更改状态
    $('.status').live('click',function(){
        var id = $(this).attr('id');
        id = id.substr(id.indexOf('_')+1);
        var that = $(this);
        $.ajax({
          'url':'../ajax_function/switch_normal_task_status.php',
          'data':{id:id},
          'type':'post',
          'success':function(data){
              if(data == 1){
                  that.children().css('background-position','-19px -15px');
              }else if(data == 0){
                  that.children().css('background-position','-24px -55px');
                  that.parent().prev().empty();
                  that.parent().parent().css('background-color','');
              }else if(data == 3){
                   that.children().css('background-position','-19px -15px');
                   that.parent().parent().css('background-color','red');
                   that.parent().prev().append('  <a href="normal_task_edit.php?id='+id+'&action=edit"><img src="../images/system_ico/edit.png" width="25"/></a>');
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
  <h4>日常任务列表</h4>
  <form action="" name="search" method="get">
    <table>
      <tr>
        <th>名称：</th>
        <td><input type="text" name="task_name" class="input_txt" /></td>
        <td><input type="submit" name="submit" value="查询" class="button" />
          <input type="button" name="button" value="添加" class="button" onclick="location.href='systemae.php?action=add'" />
          <input type="text" style="display:none;" /></td>
      </tr>
    </table>
  </form>
</div>
<div id="table_list">
  <?php if($result->num_rows){ ?>
  <form action="normal_task_do.php" name="system_list" method="post">
    <table>
      <tr>
        <th width="4%">ID</th>
        <th width="10%">任务名称</th>
        <th width="10%">频率</th>
        <th width="10%">实施时间</th>
        <th width="15%">任务内容</th>
        <th width="15%">备注</th>
        <th width="4%">Edit</th>
        <th width="4%">Status</th>
        <th width="4%">Info</th>
      </tr>
      <?php
      while($row = $result->fetch_assoc()){
      $status = '';
      //根据状态设置图标
      if($row['task_status'] == 1){
          $status = 'background-position:-19px -15px';
        }else{
          $status = 'background-position:-24px -55px';
        }     
		  $taskid = $row['taskid'];
		  $rule   = $row['repeat_rule'];
		  $todo_date = $row['todo_date'];
		  //查询当天是否有完成记录
		  $sql_info = "SELECT * FROM `sc_normal_task_info` WHERE `taskid` = '$taskid' AND SUBSTR(`do_time`,1,10) = '$date'";
		  $result_info = $db->query($sql_info);
		  $is_bg = '';
		  if(((($rule == 'A' || $rule == 'D') && $todo_date == $date) || ($rule == 'B' && $todo_date == $week_num) || ($rule == 'C' && $todo_date == $day_num)) && !$result_info->num_rows && $row['task_status'] == '1'){
		  	$is_bg = 'red';
		  }
	  ?>
      <tr style="background-color:<?php echo $is_bg; ?>">
        <td><input type="checkbox" <?php echo in_array($row['task_status'],array('1','2'))?'disabled':''; ?> name="id[]" value="<?php echo $taskid; ?>" /></td>
        <td><?php echo $row['task_name']; ?></td>
        <td><?php echo $array_normal_repeat[$row['repeat_rule']]; ?></td>
        <td>
        	<?php
        		if($rule == 'A' || $rule == 'D'){
        			echo $row['todo_date'];
        		}elseif($rule == 'B'){
        			echo $array_week[$row['todo_date']];
        		}elseif($rule == 'C'){
        			echo '每月'.$row['todo_date'].'号';
        		}
        	?>
        </td>
        <td><?php echo $row['content']; ?></td>
        <td><?php echo $row['remark']; ?></td>
        <td>
        	<?php
			  if(((($rule == 'A' || $rule == 'D') && $todo_date == $date) || ($rule == 'B' && $todo_date == $week_num) || ($rule == 'C' && $todo_date == $day_num)) && $row['task_status'] == '1' && !$result_info->num_rows){
			?>
				<a href="normal_task_edit.php?id=<?php echo $taskid; ?>&action=edit"><img src="../images/system_ico/edit.png" width="25"/></a>
			<?php  } ?>
        	
        </td>
        <td>
          <?php 
              if($row['task_status'] == '2'){
                  echo '<img src="../images/system_ico/query.png" width="25">';
              }else{
           ?>
              <a href="#" class="status" id="status_<?php echo $taskid ?>">
                <p style="width:70px;margin:3px auto;height:15px;background-image:url('../images/system_ico/switch.png');background-repeat:no-repeat;<?php echo $status ?>"></p>
              </a>
          <?php } ?>
        </td>
        <td>
        	<a href="normal_task_info.php?id=<?php echo $taskid; ?>"><img src="../images/system_ico/info.png" width="25"/></a>
        </td>
      </tr>
      <?php } ?>
    </table>
    <div id="checkall">
      <input name="all" type="button" class="select_button" id="CheckedAll" value="全选" />
      <input type="button" name="other" class="select_button" id="CheckedRev" value="反选" />
      <input type="button" name="reset" class="select_button" id="CheckedNo" value="清除" />
      <input type="submit" name="submit" id="submit" value="删除" class="select_button" onclick="JavaScript:return confirm('系统提示:确定删除吗?')" disabled="disabled" />
      <input type="hidden" name="action" value="del" />
    </div>
  </form>
  <div id="page">
    <?php $pages->getPage();?>
  </div>
  <?php
  }else{
	  echo "<p class=\"tag\">系统提示：暂无记录！</p>";
  }
  ?>
</div>
<?php include "../footer.php"; ?>
</body>
</html>