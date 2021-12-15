<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once 'shell.php';
$employeeid = $_SESSION['employee_info']['employeeid'];
//查找总经办
$sql_manager = "SELECT `employeeid` FROM `db_employee` WHERE `positioinid` = 'A'";
$result_manager = $db->query($sql_manager);
if($result_manager->num_rows){
	$array_manager = array();
	while($row_manager = $result_manager->fetch_assoc()){
		$array_manager[] = $row_manager['employeeid'];
	}
}
$sql_employee = "SELECT `db_employee`.`employee_name`,`db_employee`.`account`,`db_employee`.`employee_number`,`db_employee`.`phone`,`db_employee`.`extnum`,`db_employee`.`email`,`db_employee`.`photo_filedir`,`db_employee`.`photo_filename`,`db_department`.`dept_name`,`db_personnel_position`.`position_name`,`db_superior`.`employee_name` AS `superior_name` FROM `db_employee` INNER JOIN `db_department` ON `db_department`.`deptid` = `db_employee`.`deptid` INNER JOIN `db_personnel_position` ON `db_personnel_position`.`positionid` = `db_employee`.`positionid` LEFT JOIN `db_employee` AS `db_superior` ON `db_superior`.`employeeid` = `db_employee`.`superior` WHERE `db_employee`.`employeeid` = '$employeeid'";
$result_employee = $db->query($sql_employee);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../css/base.css?v=514" type="text/css" rel="stylesheet" />
<link href="../css/myjtl.css?v=520" type="text/css" rel="stylesheet" />
<link rel="shortcut icon" href="../images/logo/xel.ico" />
<script language="javascript" type="text/javascript" src="../js/jquery-1.6.4.min.js"></script>
<script language="javascript" type="text/javascript">
$(function(){
	$("#myjtl_work_list p:first,#myjtl_today_list p:first").css({'border-top':'none'});
	$("#myjtl_work_list p,#myjtl_today_list p").click(function(){
		var id = $(this).attr('id');
		var display = $("#"+id+"_list").css('display');
		if(display == 'none'){
			$("#"+id+"_list").show();
			$(this).css({'border-bottom':'1px solid #DDD','background':'#F2F2F2'});
		}else{
			$("#"+id+"_list").hide();
			$(this).css({'border-bottom':'none'});
		}
		$("#myjtl_work_list ul").not($("#"+id+"_list")).hide();
		$("#myjtl_today_list ul").not($("#"+id+"_list")).hide();
		$("#myjtl_work_list p").not($("#"+id)).css({'border-bottom':'none','background':'#F9F9F9'});
		$("#myjtl_today_list p").not($("#"+id)).css({'border-bottom':'none','background':'#F9F9F9'});
	})
	//计算总共时间
setInterval(
 function GetTime(firstDate = '1989-09-10'){
    // 1.对事件进行处理
    var firsttime = Date.parse(firstDate + " 00:00:00");
    var secondtime = Date.parse(new Date());
    // 2.获取时间间隔
    var timespan = secondtime - firsttime;
    //获取年
    var c_year = 1000*60*60*24*365;
    var year = Math.floor(timespan / c_year);
    //获取月
    var c_month = 1000*60*60*24*30;
    var month = Math.floor((timespan - c_year * year) / c_month);
    // 3.获取天
    var c_day = 1000*60*60*24;
    var day = Math.floor((timespan - c_year * year - c_month * month)  / c_day);
    // 4.获取小时
    var c_hour = 1000*60*60;
    var hour = Math.floor((timespan  - c_year * year - c_month * month - c_day * day)/ c_hour);
    // 5.获取分钟
    var c_min = 1000*60;
    var minute = Math.floor((timespan  - c_year * year - c_month * month - c_day * day - c_hour * hour)/ c_min);
    // 6.获取秒
    var second = Math.floor((timespan  - c_year * year - c_month * month - c_day * day - c_hour * hour - c_min * minute)/ 1000);
    var res =  year + '年' + (month<10?('0'+month):month) + '月' + (day<10?('0'+day):day) + "日" + (hour<10?('0'+hour):hour) + "时" + (minute<10?('0'+minute):minute) + "分" + (second<10?('0'+second):second) + '秒';
    document.getElementById('text').innerHTML = res;
}
	,1000);
})
</script>
<title>希尔林信息平台</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="myjtl_tag">
  <!-- <h4>MY WORK >></h4> -->
<p id="text" style="color:red"></p>
</div>
<div id="myjtl">
  <div id="myjtl_left">
    <div id="myjtl_work_list">
      <?php
	  /*********************需要做计划的提醒*******************************/
	  //应做项目计划
	  //前一天到结束，一直提醒
	  $before_day = date('Y-m-d',strtotime(date('Ymd')));
	  $cur_date = date('Y-m-d');
	  $sql_project_plan = "SELECT `sc_project_plan_info`.`infoid`,`sc_project_plan_info`.`stage_name` FROM `sc_project_plan_info`  WHERE `start_date` <= '$before_day' AND `end_date` >= '$cur_date' AND `infoid` NOT IN(SELECT DISTINCT(`infoid`) FROM `sc_project_stage_action`)";
	  $result_project_plan = $db->query($sql_project_plan);
	  $total_plan = $result_project_plan->num_rows;
	  ?>
      <h4>日常工作</h4>
      <p id="my_approve"<?php echo $total_plan?' style="color:#F00;"':'' ?>>【我的计划】您有<span class="tasknum"><?php echo $total_plan; ?></span>个计划未处理</p>
      <ul id="my_approve_list" style="display:none;">
        <?php  if($total_plan){ ?>
		 <?php
		if($result_project_plan->num_rows){
			while($row_project_plan = $result_project_plan->fetch_assoc()){
		?>
        <li><a href="/plan/project_stage_plan_add.php?action=add&infoid=<?php echo $row_project_plan['infoid'] ?>">【项目计划】<?php echo '阶段名:'.$row_project_plan['stage_name']; ?></a></li>
        <?php
			}
		}
		?>
        <?php
		}else{
			echo "<li>【计划】暂无</li>";
		}
		?>
      </ul>
    
    </div>
    <?php
  /*****************需要完成的任务*******************************/
	 //今天的记忆VS练习任务
	 $sql_repeat_task = "SELECT `sc_repeat_task`.`taskid`,`sc_repeat_task`.`task_name`,`sc_repeat_task`.`type` FROM `sc_repeat_task` INNER JOIN `sc_repeat_task_info` ON `sc_repeat_task_info`.`taskid` = `sc_repeat_task`.`taskid` WHERE `sc_repeat_task`.`task_status` = '1' AND `sc_repeat_task_info`.`do_date` = '' AND `sc_repeat_task_info`.`todo_date` ='".date('Y-m-d')."' GROUP BY `sc_repeat_task`.`taskid`";
	 $result_repeat_task = $db->query($sql_repeat_task);
	//今天的日常任务
	$week_num = date('w');
	$day_num  = date('d');
	$date     = date('Y-m-d');
	$sql_normal_task = "SELECT * FROM `sc_normal_task` WHERE `last_dodate` != '$date' AND (IF((`repeat_rule` = 'A'),(`todo_date` = '$date'),'') OR IF((`repeat_rule` = 'B'),(`todo_date` = '$week_num'),'') OR IF((`repeat_rule` = 'C'),(`todo_date` = '$day_num'),'') OR IF((`repeat_rule` = 'D'),(`todo_date` = '$date'),'')) AND `task_status` = '1'";
	$result_normal_task = $db->query($sql_normal_task);
	 //为完成的项目阶段实施步骤
	 $sql_project_action = "SELECT `action_name`,`actionid`,`infoid` FROM `sc_project_stage_action` WHERE `is_action` = '0' AND '$date' BETWEEN `start_date` AND `end_date`";
	 $result_project_action = $db->query($sql_project_action);
	 //总共待计划
	 $total_task = $result_plan->num_rows +  $result_repeat_task->num_rows + $normal_task_num = $result_normal_task->num_rows+$result_project_action->num_rows;    	
	?>
	<div id="myjtl_today_list">
		<h4>今日任务</h4>
	  	<p id="my_today"<?php echo $total_task?' style="color:#F00;"':'' ?>>
	  		【今日任务】您有
	  		<span class="tasknum">
	  			<?php echo $total_task; ?>
	  		</span>
	  		个任务未完成
	  	</p>
	    <ul id="my_today_list" style="display:none">
	      <?php if($total_task >0){?>
			<?php
				if($result_repeat_task->num_rows){
					while($row_repeat_task = $result_repeat_task->fetch_assoc()){
					$type = $row_repeat_task['type'];
					if($type == 'M'){
			?>
						<li>
							<a href="/task/repeat_task_info.php?action=do&id=<?php echo $row_repeat_task['taskid'] ?>"><?php echo '【练习任务】'.$row_repeat_task['task_name']; ?></a>
						</li>
			 <?php
			 		}else{
			?>
						<li>
							<a href="/task/exercise_task_info.php?action=do&id=<?php echo $row_repeat_task['taskid'] ?>"><?php echo '【练习任务】'.$row_repeat_task['task_name']; ?></a>
						</li>
			<?php
			 		}
				}
			}
			?>
			<?php
				if($result_normal_task->num_rows){
					while($row_normal_task = $result_normal_task->fetch_assoc()){

			?>
				<li>
					<a href="/task/normal_task_list.php?action=do&id=<?php echo $row_normal_task['taskid'] ?>">
						<?php echo '【日常任务】'.$row_normal_task['task_name']; ?>
					</a>
				</li>
			 <?php
				}
			}
			?>
			<?php
				if($result_project_action->num_rows){
					while($row_project_action = $result_project_action->fetch_assoc()){

			?>
			<li>
				<a href="/plan/project_stage_action_edit.php?action=edit&infoid=<?php echo $row_project_action['infoid'] ?>&actionid=<?php echo $row_project_action['actionid'] ?>">
					<?php echo '【项目任务】'.$row_project_action['action_name']; ?>
				</a>
			</li>
			 <?php
				}
			}
			?>
			<?php

			}else{
				echo "<li>【任务】暂无</li>";
			}
			?>
	    </ul>		
	</div>
	<?php
		//查询当周计划
    	$sql_week_plan = "SELECT `sc_category`.`cate_name`,`sc_plan_data`.`planid`,`sc_plan_info`.`cateid`,`sc_plan_info`.`goal` FROM `sc_plan_info` INNER JOIN `sc_plan_data` ON `sc_plan_info`.`planid` = `sc_plan_data`.`planid` INNER JOIN `sc_category` ON `sc_plan_info`.`cateid` = `sc_category`.`id` WHERE `sc_plan_data`.`start_date` <= '$date' AND `sc_plan_data`.`end_date` >= '$date' AND `sc_plan_data`.`categoryid` = 'W' AND `sc_plan_info`.`is_complete` = '0'";
    	$result_week_plan = $db->query($sql_week_plan);			
	?>
    <div id="myjtl_task_list">
      <h4>周计划</h4>
      <ul>
        <?php 
        	if($result_week_plan->num_rows){ 
        		while($row_week_plan = $result_week_plan->fetch_assoc()){
        			$cate_name = $row_week_plan['cate_name'];
        			$goal      = $row_week_plan['goal'];
        ?>
        <li><a href="/plan/plan_list_info.php?planid=<?php echo $row_week_plan['planid'] ?>"><?php echo '【'.$cate_name.'】'.$goal; ?></a></li>
        <?php 
    			}
    		}else{
			echo "<li class=\"msg\">【周计划】暂无</li>";
		}?>
      </ul>
    </div>
    <?php
    if($result_employee->num_rows){
		//查询最后一次登录时间
		$sql_login_log = "SELECT `dotime` FROM `db_login_log` WHERE `employeeid` = '$employeeid' AND `login_status` = 'A' ORDER BY `logid` DESC LIMIT 1,1";
		$result_login_log = $db->query($sql_login_log);
		if($result_login_log->num_rows){
			$array_login_log = $result_login_log->fetch_assoc();
			$last_logintime = $array_login_log['dotime'];
		}else{
			$last_logintime = '--';
		}
		//获取员工照片
		$array_employee = $result_employee->fetch_assoc();
		$photo_filedir = $array_employee['photo_filedir'];
		$photo_filename = $array_employee['photo_filename'];
		$photo_path = "../upload/personnel/".$photo_filedir.'/'.$photo_filename;
		$photo = is_file($photo_path)?"<img src=\"".$photo_path."\" />":"<img src=\"../images/no_photo_98_140.png\" width=\"98\" height=\"140\" />";
	?>
    <div id="myjtl_employee">
      <h4>个人信息</h4>
      <dl>
        <dt><?php echo $photo; ?></dt>
        <dd><?php echo $array_employee['employee_number']; ?></dd>
        <dd><?php echo $array_employee['employee_name'].'('.$array_employee['account'].')'; ?></dd>
        <dd><?php echo $array_employee['dept_name']; ?></dd>
        <dd><?php echo $array_employee['position_name']; ?></dd>
        <dd>上级领导：<?php echo $array_employee['superior_name']; ?></dd>
        <dd><!-- 电话： --><?php echo $array_employee['phone']; ?></dd>
        <dd>分机：<?php echo $array_employee['extnum']; ?></dd>
        <dd>上次登录：<?php echo $last_logintime; ?></dd>
      </dl>
      <ul>
        <li><a href="employee_info.php">资料修改</a></li>
        <li><a href="account_password.php">密码修改</a></li>
        <li><a href="../passport/logout.php">退出</a></li>
      </ul>
    </div>
    <?php } ?>
  </div>
  <div id="myjtl_right">
    <?php
    foreach($array_system_type as $system_type_key=>$system_type_value){
		if(in_array($system_type_key,array('A','C','D'))){ //我的系统
		    $sql_system = "SELECT `db_system`.`system_name`,`db_system`.`image_filedir`,`db_system`.`image_filename`,`db_system`.`system_dir` FROM `db_system_employee` INNER JOIN `db_system` ON `db_system`.`systemid` = `db_system_employee`.`systemid` WHERE `db_system`.`system_type` = '$system_type_key' AND `db_system`.`system_status` = 1 AND `db_system_employee`.`employeeid` = '$employeeid' ORDER BY `db_system`.`system_order` ASC,`db_system`.`systemid` ASC";
		}elseif($system_type_key == 'B'){ //公共系统
			$sql_system = "SELECT `system_name`,`image_filedir`,`image_filename`,`system_dir` FROM `db_system` WHERE `system_type` = '$system_type_key' AND `system_status` = 1 ORDER BY `system_order` ASC,`systemid` ASC";
		}
		// echo $sql_system;
		$result_system = $db->query($sql_system);

	?>
	<?php if($result_system->num_rows){ ?>
    <div id="myjtl_program_list">
      <h4><?php echo $system_type_value; ?></h4>
      <?php
      if($result_system->num_rows){
		  //最新公告
		  $sql_notice = "SELECT * FROM `db_notice` WHERE DATEDIFF(CURDATE(),DATE_FORMAT(`dotime`,'%Y-%m-%d')) <= 7 AND `notice_status` = 1";
		  $result_notice = $db->query($sql_notice);
		  while($row_system = $result_system->fetch_assoc()){
			  $image_filedir = $row_system['image_filedir'];
			  $image_filename = $row_system['image_filename'];
			  $image_filepath = "../upload/system/".$image_filedir.'/'.$image_filename;
			  $image_info = (is_file($image_filepath))?"<img src=\"".$image_filepath."\" />":"<img src=\"../images/no_image_60_60.png\" width=\"60\" height=\"60\" />";
	  ?>
      <dl>
        <dt><a href="<?php echo $row_system['system_dir']; ?>"><?php echo $image_info; ?></a></dt>
        <dd><?php echo $row_system['system_name']; ?><?php if($row_system['system_dir'] == '/notice/' && $result_notice->num_rows) echo "<font color=red>[".$result_notice->num_rows."]</font>"; ?></dd>
      </dl>
      <?php } ?>
      <div class="clear"></div>
      <?php } ?>
    </div>
    <?php }} ?>
  </div>
  <div class="clear"></div>
</div>
<div id="footer">
  <p>CopyRight ©2019 Suzhou Hillion Technology Co.,Ltd All Rights Reserved.</p>
</div>

</body>
</html>
