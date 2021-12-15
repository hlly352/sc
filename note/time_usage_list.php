<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
$sqldate = " AND `sc_time_info`.`date` BETWEEN '$sdate' AND '$edate'";
//查询所有的事项
$sql_cate = "SELECT `sc_category`.`cate_name`,`sc_category`.`id` FROM `sc_time_info` INNER JOIN `sc_category` ON `sc_category`.`id` = `sc_time_info`.`cateid` WHERE `sc_category`.`status` = '1' AND `sc_time_info`.`type` = 'I' GROUP BY `sc_category`.`id`";
$result_cate = $db->query($sql_cate);
if($_GET['submit']){
    $cateid = trim($_GET['cateid']);
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
$sql = "SELECT * FROM  `sc_time_info` INNER JOIN `sc_category` ON `sc_time_info`.`cateid` = `sc_category`.`id`  WHERE `sc_time_info`.`status` = '1' AND `sc_category`.`pid` = (SELECT `id` FROM `sc_category` WHERE `cate_code` = 'ZY') $sqlwhere $sqldate ORDER BY `date` ASC";
$res = $db->query($sql);
$arr_date = $arr_time = array();
if($res->num_rows){
    while($rows = $res->fetch_assoc()){
        $arr_date[$rows['date']] = $rows['date'];
        $total_time = get_real_time($rows['offset_time'],'min');
        $arr_time[$rows['id']]['cate_name'] = $rows['cate_name'];
        $arr_time[$rows['id']]['data'][$rows['date']] += $total_time;
    }
}
$arr_date = array_keys($arr_date);
$arr_times = array();
$i = 0;
foreach($arr_time as $key=>$time){
  foreach($time as $ks=>$val){
      if(is_array($val)){
          foreach($arr_date as $k=>$v){
              if($val[$v]){
                $arr_data[] = $val[$v];
              }else{
                $arr_data[] = 0;
              }
          }
      }else{
          $arr_val = $val;
      }
  }
  $arr_times[$i]['data'] = $arr_data;
  $arr_times[$i]['name'] = $arr_val;
  $arr_data = $arr_val = array();
  $i++;
}

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
<script language="javascript" type="text/javascript" src="../js/code/highcharts.js"></script>
<script language="javascript" type="text/javascript" src="../js/code/modules/series-label.js" charset="utf-8"></script>
  <script type="text/javascript">
  $(function(){
   //把php 数组转换为js 数组
    var arr_date =  eval(<?php echo json_encode($arr_date); ?>);
    var arr_time =  eval(<?php echo json_encode($arr_times);?>);
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
 
    var series =  arr_time;
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
  <h4>时间记录</h4>
  <form action="" method="get">
    <table id="search">
      <tr>
        <th>事项：</th>
        <td>
           <select name="cateid" class="input_txt">
                  <option value="">所有</option>
                  <?php
                    if($result_cate->num_rows){
                        while($row_cate = $result_cate->fetch_assoc()){
                            echo '<option value="'.$row_cate['id'].'">'.$row_cate['cate_name'].'</option>';
                        }
                    }
                  ?>
              </select>            
        </td>
        <th>日期：</th>
        <td>
          <input type="text" name="sdate" class="input_txt" value="<?php echo $sdate; ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})">
          --
          <input type="text" name="edate" class="input_txt" value="<?php echo $edate; ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})">
        </td>
        <td>
          <input type="submit" name="submit" value="查询" class="button">
          <input type="button" value="添加" onfocus="window.location.href='time_usage_add.php?action=add'" class="button" />
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
        <th width="4%">ID</th>
        <th width="10%">事项</th>
        <th width="10%">日期</th>
        <th width="10%">开始时间</th>
        <th width="10%">结束时间</th>
        <th width="10%">用时</th>
        <th width="20%">备注</th>
        <th width="4%">status</th>
      </tr>
      <?php
      $i = 1;
      while($row = $result->fetch_assoc()){
      $infoid = $row['infoid'];
      $colors = $row['todo_date'] == date('Y-m-d')?'style="color:red"':'';
      $total_seconds += $row['offset_min'] * 60;

    ?>
      <tr>
        <td><input type="checkbox" name="id[]" value="<?php echo $infoid; ?>" /></td>
        <td><?php echo $row['cate_name']; ?></td>
        <td><?php echo $row['date'] ?></td>
        <td><?php echo $row['start_time'] ?></td>
        <td><?php echo $row['end_time'] ?></td>
        <td><?php echo $row['offset_time'] ?></td>
        <td><?php echo $row['remark'] ?></td>       
        <td><a href="memory_task_edit.php?id=<?php echo $infoid; ?>&action=edit"><img src="../images/system_ico/edit_10_10.png" width="10" height="10" /></a></td>
      </tr>
      <?php 
        $i++;
      } ?>
  <tr>
    <td colspan="5">合计</td>
    <td>
      <?php 
        $hour = floor($total_seconds / 3600);
        $min  = floor(($total_seconds - ($hour * 3600)) / 60);
        $sec  = $total_seconds - ($hour * 3600) - ($min * 60);
        echo $hour.':'.(($min<10)?('0'.$min):$min).':'.(($sec<10)?('0'.$sec):$sec);
       ?>
    </td>
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
