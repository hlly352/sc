<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
$sdate = $_GET['start_date']?trim($_GET['start_date']):date('Y-m-25',strtotime(date('Y-m-d').'-1 month'));
$edate = $_GET['end_date']?trim($_GET['end_date']):date('Y-m-d',strtotime($sdate.'+1 month -1day'));

//按照时间查找任务
$sql = "SELECT GROUP_CONCAT(CONCAT(`sc_memory_task`.`task_name`,' (',`sc_memory_task_info`.`offset_time`,IF(`sc_memory_task`.`type` = 'M',' 记忆',' 练习'),')')) AS `task_content`,`sc_memory_task_info`.`do_date`,GROUP_CONCAT(`sc_memory_task_info`.`offset_time`) AS `tot_time` FROM `sc_memory_task_info` INNER JOIN `sc_memory_task` ON `sc_memory_task_info`.`taskid` = `sc_memory_task`.`taskid` WHERE `sc_memory_task_info`.`do_date` BETWEEN '$sdate' AND '$edate' GROUP BY `sc_memory_task_info`.`do_date`";
$result = $db->query($sql);
$sql_time = $sql." ORDER BY `sc_memory_task_info`.`do_date` ASC";
$result_time = $db->query($sql_time);
if($result_time->num_rows){
	$arr_date = $arr_time = $arr_memory = $arr_exercise = array();
  while($row_time = $result_time->fetch_assoc()){
    $do_date = $row_time['do_date'];
    $arr_date[] = $do_date;
    $arr_time[] = get_real_time($row_time['tot_time'],'min');
    //计算分项目分钟数
    $sql_type = "SELECT `sc_memory_task`.`type`,GROUP_CONCAT(`sc_memory_task_info`.`offset_time`) AS `type_total_time` FROM `sc_memory_task_info` INNER JOIN `sc_memory_task` ON `sc_memory_task`.`taskid` = `sc_memory_task_info`.`taskid` WHERE `sc_memory_task_info`.`do_date` = '$do_date' GROUP BY `sc_memory_task`.`type`";
    $result_type = $db->query($sql_type);
    if($result_type->num_rows){
			while($row_type = $result_type->fetch_assoc()){
				$type = $row_type['type'];
				$total_time = get_real_time($row_type['type_total_time'],'min');
        //把对应的时间累加到对应的类别上
				if($type == 'M'){
					$arr_memory[$do_date]   += $total_time;
					$arr_exercise[$do_date] += 0;
				}elseif($type == 'E'){
					$arr_memory[$do_date]   += 0;
					$arr_exercise[$do_date] += $total_time;
				}
			}
		}
    $total_time = 0;
	}
}
if(is_array($arr_memory)){
  $arr_memory = array_values($arr_memory);
  $arr_exercise = array_values($arr_exercise);
}
// var_dump($arr_memory);

$pages = new page($result->num_rows,12);
$sqllist = $sql . " ORDER BY `sc_memory_task_info`.`do_date` DESC" . $pages->limitsql;
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
<script language="javascript" type="text/javascript" src="../js/code/highcharts.js"></script>
<script language="javascript" type="text/javascript" src="../js/code/modules/series-label.js" charset="utf-8"></script>
<script language="javascript" type="text/javascript" src="../js/My97DatePicker/WdatePicker.js" ></script>
<script language="javascript" type="text/javascript" src="../js/main.js"></script>
<script type="text/javascript">
	$(function(){
	 //把php 数组转换为js 数组
    var arr_date       =  eval(<?php echo json_encode($arr_date); ?>);
    var arr_time =  eval(<?php echo json_encode($arr_time);?>);
    var arr_memory =  eval(<?php echo json_encode($arr_memory);?>);
    var arr_exercise =  eval(<?php echo json_encode($arr_exercise);?>);

	 console.log(arr_time);
  //用时信息折线图 
    var credits =  {
                  enabled: false 
                };
    var title = {
      text: '每天用时'   
    };
    var subtitle = {
      text: 'sc'
    };
    var xAxis = {
      categories: arr_date
    };
    var yAxis = {
      title: {
         text: 'min'
      },
      plotLines: [{
         value: 0,
         width: 1,
         color: '#808080'
      }]
    };   
 
    var tooltip = {
       valueSuffix: 'min'
    }
 
    var legend = {
       layout: 'vertical',
       align: 'right',
       verticalAlign: 'middle',
       borderWidth: 0
    };
 
    var series =  [
       {
          name: '任务',
          data: arr_time
       },
       {
       	  name:'记忆',
       	  data:arr_memory
       },
       {
       	  name:'练习',
       	  data:arr_exercise
       }
    ];
	var plotOptions = {
        line: {
            dataLabels: {
                // 开启数据标签
                enabled: true          
            },
            // 开启鼠标跟踪，对应的提示框、点击事件会失效
            enableMouseTracking: true
        }
    };

    var json = {};
    json.credits = credits;
    json.title = title;
    json.subtitle = subtitle;
    json.xAxis = xAxis;
    json.yAxis = yAxis;
    json.tooltip = tooltip;
    json.legend = legend;
    json.series = series;
    json.plotOptions = plotOptions;
    $('#container').highcharts(json);
	})
</script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_search">
  <h4>记忆和练习任务时间统计</h4>
  <form action="" name="search" method="get">
    <table>
      <tr>
        <th>日期：</th>
        <td>
        	<input type="text" name="start_date" value="<?php echo $sdate ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})" class="input_txt" />
        	--
        	<input type="text" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})" name="end_date" value="<?php echo $edate ?>" class="input_txt"/>
        </td>
        <td><input type="submit" name="submit" value="查询" class="button" />
      </tr>
    </table>
  </form>
</div>
<div id="table_list">
  <?php if($result->num_rows){ ?>
  <form action="memory_task_do.php" name="system_list" method="post">
    <table>
      <tr>
        <th width="4%">ID</th>
        <th width="10%">日期</th>
        <th width="10%">总时间</th>
        <th width="50%">内容</th>
        <th width="4%">status</th>
        <th width="4%">Edit</th>
      </tr>
      <?php
      while($row = $result->fetch_assoc()){
		  $taskid = $row['taskid'];
		  $sub_time .= get_real_time($row['tot_time']).',';
	  ?>
      <tr>
        <td><input type="checkbox" name="id[]" value="<?php echo $taskid; ?>" /></td>
        <td><?php echo $row['do_date']; ?></td>
        <td><?php echo get_real_time($row['tot_time']); ?></td>
        <td><?php echo $row['task_content'] ?></td>
        <td><?php echo $array_status[$row['task_status']]; ?></td>
        <td><a href="memory_task_info.php?id=<?php echo $taskid; ?>&action=do"><img src="../images/system_ico/edit_10_10.png" width="10" height="10" /></a></td>
      </tr>
      <?php } ?>
      <tr>
      	<td colspan="2">合计</td>
      	<td><?php echo get_real_time($sub_time); ?></td>
      	<td></td>
      	<td></td>
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
  <div id="page">
    <?php $pages->getPage();?>
  </div>
  <?php
  }else{
	  echo "<p class=\"tag\">系统提示：暂无记录！</p>";
  }
  ?>
</div>
<div id="table_sheet">
  <h4>每天用时</h4>
</div>
<div id="container" style="width: 90%; height: 400px; margin: 0 auto"></div>
<?php include "../footer.php"; ?>
</body>
</html>
