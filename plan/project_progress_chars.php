<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
$planid = fun_check_int($_GET['id']);
//查询计划详情
$sql = "SELECT *,DATEDIFF(`sc_project_plan_info`.`end_date`,`sc_project_plan_info`.`start_date`) AS `offset_date` FROM `sc_project_plan_info` INNER JOIN `sc_project_plan` ON `sc_project_plan_info`.`planid` = `sc_project_plan`.`planid` WHERE `sc_project_plan`.`planid` = '$planid' ORDER BY `sc_project_plan_info`.`order`";
$result_count = $db->query($sql);
if($result_count->num_rows){
	$arr_stage_num = $arr_stage = array();
	while($row_count = $result_count->fetch_assoc()){
		$arr_stage_num[$row_count['order']] = $row_count['stage_name'];
		$order = $row_count['order'];
		$arr_info['y'] = intVal($row_count['order']);
		$arr_info['x'] =  $row_count['start_date'];
		$arr_info['x2'] = $row_count['end_date'];
		$arr_info['partialFill'] = $row_count['progress'] / 100; 
		$project_name = $row_count['project_name'];
		$arr_stage[] = $arr_info;
	}
}
$arr_stage_num = array_values($arr_stage_num);
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
<style type="text/css">
    #container {
    min-width: 500px;
    max-width: 80%;
    height: 500px;
    margin: 1em auto;
}

</style>
<script type="text/javascript">
	 var arr_stage_num =  eval(<?php echo json_encode($arr_stage_num);?>);
	 var arr_stage =  [];
	 var data = eval(<?php echo json_encode($arr_stage) ?>);
	 //把数据转换成需要的格式
	 for(k in data){
	 	for(i in data[k]){
	 		var arr_sub = new Object();
	 		var start_time = data[k].x.split('-');
	 		var end_time = data[k].x2.split('-');
	 		arr_sub.y = data[k].y;
	 		arr_sub.x = Date.UTC(start_time[0],(start_time[1] - 1),start_time[2]);
	 		arr_sub.x2 = Date.UTC(end_time[0],(end_time[1] - 1),end_time[2]);
	 		arr_sub.partialFill = data[k].partialFill
	 	}
	 	arr_stage[k] = arr_sub;
	 }
	 console.log(arr_stage);
	$(function(){
		Highcharts.chart('container', {
    chart: {
        type: 'xrange'
    },
    title: {
        text: '项目进展'
    },
    xAxis: {
        type: 'datetime',
        dateTimeLabelFormats: {
            week: '%Y/%m/%d'
        }
    },
    yAxis: {
        title: {
            text: ''
        },
        categories: arr_stage_num,
        reversed: true
    },
    tooltip: {
        dateTimeLabelFormats: {
            day: '%Y/%m/%d'
        }
    },
    series: [{
        name: '<?php echo $project_name; ?>',
         pointPadding: 2,
         groupPadding: 2,
        borderColor: 'gray',
        pointWidth: 20,
        data: arr_stage,
        dataLabels: {
            enabled: true
        }
    }]
});         
	})
</script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_search">
  <h4>项目进度甘特图</h4>
</div>
<div id="table_list">
  <?php if($result->num_rows){ ?>
  <form action="systemdo.php" name="system_list" method="post">
    <table>
      <tr>
        <th width="4%">ID</th>
        <th width="10%">阶段名</th>
        <th width="10%">开始日期</th>
        <th width="10%">结束日期</th>
        <th width="10%">用时</th>
        <th width="20%">备注</th>
        <th width="4%">进度</th>
      </tr>
      <?php
      	$total_date = 0;
      	while($row = $result->fetch_assoc()){
      		$total_date += $row['offset_date'];
		  ?>
      <tr>
        <td><input type="checkbox" name="id[]" value="<?php echo $infoid; ?>" /></td>
        <td><?php echo $row['stage_name']; ?></td>
        <td><?php echo $row['start_date'] ?></td>
 		<td><?php echo $row['end_date'] ?></td>
 		<td><?php echo $row['offset_date']; ?></td>
        <td><?php echo $row['remark'] ?></td>       
        <td><?php echo $row['progress']."%" ?></td>
      </tr>
      <?php } ?>
	<tr>
		<td colspan="4">合计</td>
		<td><?php echo $total_date; ?></td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td colspan="7">
			<input type="button" value="返回" onclick="window.history.go(-1)" class="button"/>
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
<div id="container"></div>
<?php include "../footer.php"; ?>
</body>
</html>
