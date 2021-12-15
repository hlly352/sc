<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
$dodate = trim($_GET['dodate']);
if($dodate){
	$sqlpie = " AND `sc_time_info`.`date` = '$dodate'";
}else{
	$sqlpie = " AND `sc_time_info`.`date` = (SELECT MAX(`date`) FROM `sc_time_info`)";
}
//查询所有的事项
$sql_thing = "SELECT `sc_category`.`cate_name`,`sc_category`.`id` FROM `sc_category` INNER JOIN `sc_time_info` ON `sc_category`.`id` = `sc_time_info`.`cateid` WHERE `sc_time_info`.`status` = '1' AND `sc_time_info`.`type` = 'D' GROUP BY `sc_category`.`id`";
$result_thing = $db->query($sql_thing);
if($_GET['submit']){
    $id = trim($_GET['id']);
    if($cateid){
    	$sqlwhere = " AND `sc_time_info`.`cateid` = '$cateid'";
	}
    $sdate = trim($_GET['sdate']);
    if($sdate){
        $sqldate = " AND `sc_time_info`.`date` BETWEEN '$sdate' AND '$edate'";
    }else{
        $sqldate = '';
    }
}
//查询时间记录
$sql = "SELECT `sc_time_info`.`date`,GROUP_CONCAT(CONCAT(`sc_category`.`cate_name`,'(',`sc_time_info`.`offset_time`,')') SEPARATOR '|') AS `item`,GROUP_CONCAT(CONCAT(`sc_category`.`cate_name`,'(',`sc_time_info`.`remark`,')') SEPARATOR '|') AS `remark`,GROUP_CONCAT(`sc_time_info`.`offset_time`) AS `offset_time` FROM  `sc_time_info` INNER JOIN `sc_category` ON `sc_category`.`id` = `sc_time_info`.`cateid` WHERE `sc_time_info`.`status` = '1' $sqlwhere $sqldate GROUP BY `sc_time_info`.`date` ORDER BY `sc_time_info`.`date` DESC";
$result = $db->query($sql);
//查询被点击的当日的时间信息
$sql_current = "SELECT `sc_category`.`cate_name`,GROUP_CONCAT(`sc_time_info`.`offset_time`) AS `offset_time` FROM `sc_time_info` INNER JOIN `sc_category` ON `sc_time_info`.`cateid` = `sc_category`.`id` WHERE  `sc_time_info`.`status` = '1' $sqlpie GROUP BY `sc_time_info`.`cateid`";

$result_current = $db->query($sql_current);
$arr_pie = array();
$other_mins = 24 * 60;
$other_percent = 100;
if($result_current->num_rows){
	while($row_current = $result_current->fetch_assoc()){
		$arr_sub = array();
		$thing_name = $row_current['cate_name'];
		$offset_time_hour = get_real_time($row_current['offset_time']);
		$offset_time_min = get_real_time($row_current['offset_time'],'min');
		$other_mins -= $offset_time_min;
		//获取百分比
		$precent = $offset_time_min/60/24*100;
	 	$precent = round($precent,2);
	 	$arr_sub[] = $thing_name.'('.$offset_time_hour;
	 	$arr_sub[] = $precent;
	 	$arr_pie[] = $arr_sub;
	 	$other_percent -= $precent/100;
	}
	//获取其它的时间
	$other_hour = floor($other_mins/60);
	$other_hour = $other_hour < 10 ? '0'.$other_hour:$other_hour;
	$other_min  = $other_mins%60;
	$other_min  = $other_min < 10 ? '0'.$other_min:$other_min;
	$arr_other = ['其它('.$other_hour.':'.$other_min,$other_percent];
}
$arr_pie[] = $arr_other;
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
<script language="javascript" type="text/javascript" src="../js/code/highcharts.js"></script>
<script language="javascript" type="text/javascript" src="../js/code/modules/series-label.js" charset="utf-8"></script>
  <script type="text/javascript">
  $(function(){
   //把php 数组转换为js 数组
    var arr_date =  eval(<?php echo json_encode($arr_date); ?>);
    var arr_pie =  eval(<?php echo json_encode($arr_pie);?>);
   var chart = {
       plotBackgroundColor: null,
       plotBorderWidth: null,
       plotShadow: false
   };
   var title = {
      text: '当天时间使用情况'   
   };      
   var tooltip = {
      pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
   };
   var plotOptions = {
      pie: {
         allowPointSelect: true,
         cursor: 'pointer',
         dataLabels: {
            enabled: true,
            format: '<b>{point.name}H)</b>: {point.percentage:.1f} %',
            style: {
               color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
            }
         }
      }
   };
   var series= [{
      type: 'pie',
      name: 'Browser share',
      data: arr_pie
   }];     
      
   var json = {};   
   json.chart = chart; 
   json.title = title;     
   json.tooltip = tooltip;  
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
  <h4>整天记录</h4>
  <form action="" method="get">
    <table id="search">
      <tr>
        <th>事项：</th>
        <td>
           <select name="cateid" class="input_txt">
                  <option value="">所有</option>
                  <?php
                    if($result_thing->num_rows){
                        while($row_thing = $result_thing->fetch_assoc()){
                            echo '<option value="'.$row_thing['id'].'">'.$row_thing['cate_name'].'</option>';
                        }
                    }
                  ?>
              </select>            
        </td>
        <th>日期：</th>
        <td>
          <input type="text" name="sdate" class="input_txt" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})">
          --
          <input type="text" name="edate" class="input_txt" value="<?php echo $edate; ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})">
        </td>
        <td>
          <input type="submit" name="submit" value="查询" class="button">
          <input type="button" value="添加" onfocus="window.location.href='all_day_add.php?action=add'" class="button" />
        </td>
      </tr>
    </table>
   
  </form>
</div>
<div id="table_list">
  <?php if($result->num_rows){ ?>
  <form action="systemdo.php" name="system_list" method="post">
    <table>
      <tr>
        <th>ID</th>
        <th>日期</th>
        <th>事项</th>
        <th>总时间</th>
        <th>备注</th>
        <th>图表</th>
        <th>Info</th>
      </tr>
      <?php
      $str_tot_time = '';
      while($row = $result->fetch_assoc()){
      	$dayid = $row['dayid'];
    ?>
      <tr>
        <td><input type="checkbox" name="id[]" value="<?php echo $dayid; ?>" /></td>
        <td><?php echo $row['date'] ?></td>
        <td><?php echo $row['item'] ?></td>
        <td><?php echo get_real_time($row['offset_time']); ?></td>
        <td><?php echo $row['remark'] ?></td>      
        <td>
        	<a href="all_day_list.php?dodate=<?php echo $row['date'] ?>">
        		<img src="../images/system_ico/pie.png" width="25" />
        	</a>
        </td>
        <td>
        	<a href="">
        		<img src="../images/system_ico/info.png" width="25">
        	</a>
        </td>
      </tr>
      <?php 
        $str_tot_time .= get_real_time($row['offset_time']).',';
      } ?>
  <tr>
    <td colspan="3">合计</td>
    <td>
      <?php 
      	 echo get_real_time($str_tot_time);
       ?>
    </td>
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
  <?php
  }else{
    echo "<p class=\"tag\">系统提示：暂无记录！</p>";
  }
  ?>
</div>
<div id="container" style="width: 90%; height: 400px; margin: 0 auto"></div>
<?php include "../footer.php"; ?>
</body>
</html>
