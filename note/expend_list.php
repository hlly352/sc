<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
$sqldate = " AND `sc_finance_info`.`date` BETWEEN '$sdate' AND '$edate'";
//查询所有的事项
$sql_thing = "SELECT `id`,`cate_code`,`cate_name` FROM `sc_category` WHERE `id` IN(SELECT `sc_category`.`pid` FROM `sc_finance_info` INNER JOIN `sc_category` ON `sc_category`.`id` = `sc_finance_info`.`cateid` WHERE `sc_category`.`status` = '1' AND `sc_finance_info`.`type` = 'O')";
$result_thing = $db->query($sql_thing);
if($_GET['submit']){
    $itemid = trim($_GET['itemid']);
    $cate_id = trim($_GET['cate_id']);
    if($cate_id){
        $sqlwhere = " AND `sc_finance_info`.`cateid` = '$cate_id'";
    }else{
      if($itemid){
        $sqlwhere = " AND `sc_finance_info`.`cateid` IN(SELECT `id` FROM `sc_category` WHERE `pid` = '$itemid')";
      }else{
        $sqlwhere = " ";
      }
  }
    $sdate = trim($_GET['sdate']);
    if($sdate){
        $sqldate = " AND `sc_finance_info`.`date` BETWEEN '$sdate' AND '$edate'";
    }else{
        $sqldate = '';
    }
}
//查询支出记录
$sql = "SELECT `sc_finance_info`.`cateid`,`sc_finance_info`.`financeid`,`sc_category`.`cate_name`,`sc_finance_info`.`amount`,`sc_finance_info`.`date`,`sc_finance_info`.`remark` FROM  `sc_finance_info` INNER JOIN `sc_category` ON `sc_finance_info`.`cateid` = `sc_category`.`id` WHERE `sc_finance_info`.`status` = '1' AND `sc_finance_info`.`type` = 'O' $sqlwhere $sqldate ";
//计算总金额
$result_total = $db->query($sql);
  $total_income_amount = 0;
  $array_income_amount = array();
if($result_total->num_rows){
  while($row_total = $result_total->fetch_assoc()){
    $total_income_amount += $row_total['amount'];
    //计算每个项目的总金额
    $arr_item[$row_total['cateid']] = $row_total['cate_name'];
    $arr_income[$row_total['cateid']] += $row_total['amount'];
  }
}
if(is_array($arr_item)){
  foreach($arr_item as  $k=>$name){
     $array_income_amount[] = array($name.$arr_income[$k],ROUND((($arr_income[$k]/$total_income_amount) * 100),2));
  }
}
$result = $db->query($sql);
$pages = new Page($result->num_rows,15);
$sqllist = $sql." ORDER BY `sc_finance_info`.`date` ASC".$page->limitsql;
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
<script type="text/javascript" src="../js/My97DatePicker/WdatePicker.js"></script>
<script language="javascript" type="text/javascript" src="../js/main.js"></script>
<script language="javascript" type="text/javascript" src="../js/code/highcharts.js"></script>
<script language="javascript" type="text/javascript" src="../js/code/modules/series-label.js" charset="utf-8"></script>
  <script type="text/javascript">
  $(function(){
    //选择主类自动更新子类
    $('#item_id').live('change',function(){
        var item_id = $('#item_id').val();
        var opt = '<option value="">--请选择--</option>';
        $.ajax({
            'url':'../ajax_function/get_sub_cate.php',
            'data':{item_id:item_id},
            'type':'post',
            'dataType':'json',
            'success':function(data){
                for(k in data){
                  opt += '<option value="'+k+'">'+data[k]+'</option>';
                }
                $('#cate_id').empty().append(opt);
            }
        })
    })
    var total_data = eval(<?php echo json_encode($array_income_amount) ?>);
   var chart = {
       plotBackgroundColor: null,
       plotBorderWidth: null,
       plotShadow: false
   };
   var title = {
      text: '当期财务支出金额'   
   };  
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
            format: '<b>({point.name}元)</b>: {point.percentage:.1f} %',
            style: {
               color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
            }
         }
      }
   };
   var series= [{
      type: 'pie',
      name: '支出金额',
      data: total_data
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
      
   var json = {};   
   json.chart = chart; 
   json.title = title;     
   json.tooltip = tooltip;  
   json.series = series;
   json.colors = colors;
   json.plotOptions = plotOptions;
   $('#container').highcharts(json); 
  })
</script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_search">
  <h4>支出记录</h4>
  <form action="" method="get">
    <table id="search">
      <tr>
        <th>主类：</th>
        <td>
           <select name="itemid" id="item_id" class="input_txt">
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
        <th>子类：</th>
        <td>
          <select name="cate_id" id="cate_id" class=input_txt></select>
        </td>
        <th>日期：</th>
        <td>
          <input type="text" name="sdate" class="input_txt" value="<?php echo $sdate; ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})">
          --
          <input type="text" name="edate" class="input_txt" value="<?php echo $edate; ?>" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})">
        </td>
        <td>
          <input type="submit" name="submit" value="查询" class="button">
          <input type="button" value="添加" onfocus="window.location.href='expend_add.php?action=add'" class="button" />
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
        <th width="10%">项目</th>
        <th width="10%">金额</th>
        <th width="10%">日期</th>
        <th width="20%">备注</th>
        <th width="4%">status</th>
      </tr>
      <?php
      $i = 1;
      while($row = $result->fetch_assoc()){
      $financeid = $row['financeid'];
    ?>
      <tr>
        <td><input type="checkbox" name="id[]" value="<?php echo $incomeid; ?>" /></td>
        <td><?php echo $row['cate_name']; ?></td>
        <td><?php echo $row['amount'] ?></td>
        <td><?php echo $row['date'] ?></td>
        <td><?php echo $row['remark'] ?></td>       
        <td><a href="memory_task_edit.php?id=<?php echo $infoid; ?>&action=edit"><img src="../images/system_ico/edit_10_10.png" width="10" height="10" /></a></td>
      </tr>
      <?php 
        $i++;
      } ?>
  <tr>
    <td colspan="2">合计</td>
    <td><?php echo $total_income_amount; ?></td>
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
    <div id="page">
     <?php echo $pages->getPage(); ?>
    </div>
  </form>
  <?php
  }else{
    echo "<p class=\"tag\">系统提示：暂无记录！</p>";
  }
  ?>
</div>
<div>
</div>
<div id="container" style="width: 90%; height: 400px; margin: 0 auto"></div>
<?php include "../footer.php"; ?>
</body>
</html>
