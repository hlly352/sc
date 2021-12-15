<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
$planid = trim($_GET['planid']);
if($_GET['submit']){
	$task_name = trim($_GET['task_name']);
	$sqlwhere = " WHERE `task_name` LIKE '%$task_name%'";
}
$sql = "SELECT `sc_plan_data`.`start_date`,`sc_plan_data`.`end_date`,`sc_category`.`id`,`sc_plan_info`.`is_complete`,`sc_plan_info`.`infoid`,`sc_category`.`cate_name`,`sc_plan_info`.`goal`,`sc_plan_info`.`remark`,`sc_plan_info`.`status`,`sc_plan_info`.`amount`,ROUND(((`sc_plan_info`.`amount`/(SELECT SUM(`sc_plan_info`.`amount`) FROM `sc_plan_info` WHERE `planid` = '$planid')) * 100),2) AS `plan_precent` FROM `sc_plan_info` INNER JOIN `sc_plan_data` ON `sc_plan_info`.`planid` = `sc_plan_data`.`planid` INNER JOIN `sc_category` ON `sc_plan_info`.`cateid` = `sc_category`.`id` WHERE `sc_plan_data`.`planid` = '$planid'  GROUP BY `sc_plan_info`.`infoid`";

//获取计划和实际的用时，用于图标的数据
$result_total = $db->query($sql);
$array_plan_amount = $array_real_amount = $array_cate_name = $array_total_data = array();
if($result_total->num_rows){
  $str_real_cateid = '';
  $arr_real_cateid = array();
	while($row_total = $result_total->fetch_assoc()){
		$arr_plan = array($row_total['cate_name'].$row_total['amount'].'H',floatval($row_total['plan_precent']));
		$array_plan_amount[]  = $arr_plan;
		
		$array_cate_name[] = $row_total['cate_name'];
		$arr_plan_data[] = intval($row_total['amount'] * 60);
    $cur_cateid = $row_total['id'];
    $sdate = $row_total['start_date'];
    $edate = $row_total['end_date'];

    //查询实际的用时
$sql_real_amount = "SELECT `sc_category`.`cate_name`,`sc_category`.`id`,SUM(`sc_time_info`.`offset_min`) AS `real_amount`,ROUND((SUM(`sc_time_info`.`offset_min`)/(SELECT SUM(`offset_min`) FROM `sc_time_info` WHERE  `date` BETWEEN '$sdate' AND '$edate')) * 100,2) AS `real_precent` FROM `sc_time_info` INNER JOIN `sc_category` ON `sc_time_info`.`cateid` = `sc_category`.`id` WHERE  `sc_time_info`.`cateid` = '$cur_cateid' AND `sc_time_info`.`date` BETWEEN '$sdate' AND '$edate' GROUP BY `sc_category`.`id`";
$result_real_amount = $db->query($sql_real_amount);
if($result_real_amount->num_rows){
    while($row_real_amount = $result_real_amount->fetch_assoc()){
      $arr_real = array($row_real_amount['cate_name'].$row_real_amount['real_amount'].'min',floatval($row_real_amount['real_precent']));
      $array_real_amount[]  = $arr_real;
      $arr_real_data[] = floatval($row_real_amount['real_amount']);
      $array_sub_real[$row_real_amount['id']] =  floatval($row_real_amount['real_amount']);
    }
}else{
    $array_real_amount[] = array($row_total['cate_name'],0);
    $arr_real_data[] = 0;
	}
  
}
}

$array_total_data = array(
		array('name'=>'计划',
			  'data'=>$arr_plan_data
			  ),
		array('name'=>'实际',
			  'data'=>$arr_real_data
			)
	);
$result  = $db->query($sql);
$pages   = new page($result->num_rows,120);
$sqllist = $sql . " ORDER BY `sc_plan_info`.`cateid` ASC" . $pages->limitsql;
$result  = $db->query($sqllist);
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
<script language="javascript" type="text/javascript" src="../js/code/highcharts.js"></script>
<script language="javascript" type="text/javascript" src="../js/code/modules/series-label.js" charset="utf-8"></script>
<script type="text/javascript">
    $(function(){
    /************饼图************************/
    //把数组转换成json格式
    var plan_data = eval(<?php echo json_encode($array_plan_amount) ?>);
    var real_data = eval(<?php echo json_encode($array_real_amount) ?>);
    var cate_name = eval(<?php echo json_encode($array_cate_name) ?>);
    var total_data = eval(<?php echo json_encode($array_total_data) ?>);
	 var chart = {
       plotBackgroundColor: null,
       plotBorderWidth: null,
       plotShadow: false
   };
   var title = {
      text: '当期时间计划用时'   
   };  
   var title1 = {
   	  text:'当期时间实际用时'
   }
   var colors =  ['#058DC7', '#50B432', '#ED561B', '#DDDF00',
				 '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'];
   var tooltip = {
      pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
   };
    var plotOptions = {
      pie: {
         allowPointSelect: true,
         cursor: 'pointer',
         dataLabels: {
            enabled: true,
            format: '<b>({point.name})</b>: {point.percentage:.1f} %',
            style: {
               color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
            }
         }
      }
   };
   var series= [{
      type: 'pie',
      name: '计划用时',
      data: plan_data
   }];  
   var series1= [{
   	  type: 'pie',
   	  name: '实际用时',
   	  data: real_data
   }];   
   // Radialize the colors
   Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function (color) {
      return {
         radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
         stops: [
            [0, color],
            [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
         ]
      };
   });
   //计划金额饼图  
   var json = {};   
   json.chart = chart; 
   json.title = title;     
   json.tooltip = tooltip;  
   json.series = series;
   json.colors = colors;
   json.plotOptions = plotOptions;
   $('#container').highcharts(json);  
   //实际金额饼图
   var json1 = {};
   json1.chart = chart; 
   json1.title = title1;     
   json1.tooltip = tooltip;  
   json1.series = series1;
   json1.colors = colors;
   json1.plotOptions = plotOptions;
   $('#container1').highcharts(json1);     
/****************饼图结束**********************/	
/*****************柱状图开始**************************/      
var chart = Highcharts.chart('container2',{
	chart: {
		type: 'column'
	},
	title: {
		text: '计划与实际对比图'
	},
	colors: ['#058DC7', '#50B432', '#ED561B', '#DDDF00',
				 '#24CBE5', '#64E572', '#FF9655', '#FFF263', '#6AF9C4'],
	xAxis: {
		categories: cate_name,
		crosshair: true
	},
	yAxis: {
		min: 0,
		title: {
			text: '用时 (min)'
		}
	},
	tooltip: {
		// head + 每个 point + footer 拼接成完整的 table
		headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
		pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
		'<td style="padding:0"><b>{point.y:.1f} min</b></td></tr>',
		footerFormat: '</table>',
		shared: true,
		useHTML: true
	},
	plotOptions: {
		column: {
			borderWidth: 0
		}
	},
	series: total_data
});
/*****************柱状图结束**************************/      
    })	
</script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_search">
  <h4>计划列表</h4>
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
  <form action="memory_task_do.php" name="system_list" method="post">
    <table>
      <tr>
        <th width="4%">ID</th>
        <th width="10%">类型</th>
        <th width="10%">计划</th>
        <th width="10%">事情</th>
        <th width="10%">备注</th>
        <th width="10%">用时</th>
        <th width="10%">支出详情</th>
      </tr>
      <?php
      while($row = $result->fetch_assoc()){
		  $infoid = $row['infoid'];
      $id     = $row['id'];
      $sdate  = $row['start_date'];
      $edate  = $row['end_date'];
      $real_min = $array_sub_real[$id];
      $real_time = convert_time($real_min);
	  ?>
      <tr>
        <td><input type="checkbox" name="id[]" <?php echo $row['task_status'] == '1'?'disabled':''; ?> value="<?php echo $infoid; ?>" /></td>
         <td><?php echo $row['cate_name']; ?></td>
        <td><?php echo $row['amount'] ?></td>    
        <td><?php echo $row['goal']; ?></td>
        <td><?php echo $row['remark'] ?></td>
        <td><?php echo $real_time; ?></td>   
        <td>
          <a href="/note/time_usage_list.php?submit=查询&cateid=<?php echo $id ?>">
            <img src="../images/system_ico/info.png" width="25">
          </a>
        </td>    
      </tr>
      <?php  } ?>
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
<div style="text-align:center; margin:0 auto;">
	<div id="container" style="width: 45%; border-radius:9px ; border:red solid 1px;height: 400px; display:inline-block"></div>
	<div id="container1" style="width: 45%; border-radius:9px ; border:red solid 1px;height: 400px; display:inline-block"></div>
</div>
<div id="container2" style="width:100% ; height:400px;"></div>
</body>
</html>