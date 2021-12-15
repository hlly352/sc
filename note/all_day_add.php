<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once 'shell.php';
$action = fun_check_action($_GET['action']);
//查询普通事件和重要事件
$sql_cate = "SELECT `id`,CONCAT(`cate_code`,'-',`cate_name`) AS `cate` FROM `sc_category` WHERE `pid` IN (SELECT `id` FROM `sc_category` WHERE `cate_code` IN('ZY','RC','YL'))";

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
<script language="javascript" type="text/javascript">
$(function(){
      //计算时间差
       $('input[name ^= start_time],input[name ^= end_time]').live('blur',function(){
            var item_index = $(this).parent().parent().attr('class');
            item_index = item_index.substr(item_index.lastIndexOf('_')+1);
            var start_time = $('input[name ^= start_time]').eq(item_index).val();
            var end_time   = $('input[name ^= end_time]').eq(item_index).val();
            //更改结束时间自动更改下一事项开始时间
            $('input[name ^= start_time]').eq(item_index + 1).val(end_time);
            if(start_time && end_time){
                var arr_start_time = start_time.split(':');
                var arr_end_time   = end_time.split(':');
                //分钟
                var start_min = arr_start_time[1];
                var end_min   = arr_end_time[1];
                //小时
                var start_hour = arr_start_time[0];
                var end_hour   = arr_end_time[0];
                if(end_min >= start_min){
                    var offset_min = end_min - start_min;
                }else{
                    var offset_min = parseInt(end_min) + 60 - parseInt(start_min);
                    end_hour = parseInt(end_hour) - 1;
                }
                var offset_hour = end_hour - start_hour;
                var offset_time = (offset_hour < 10?'0'+offset_hour:offset_hour)+':'+(offset_min < 10?'0'+offset_min:offset_min);
                $('input[name ^= offset_time]').eq(item_index).val(offset_time);
            }
          //对总时间进行累计
          var hour = min = total_time = 0; 
          $('input[name ^= offset_time]').each(function(){
              var sub_time = $(this).val();
              if(sub_time){
                var arr_time = sub_time.split(':');
                    hour     += parseInt(arr_time[0]);
                    min      += parseInt(arr_time[1]);
                    if(min >= 60){
                        hour += 1;
                        min -= 60;
                    }
                  }
          })
          total_time = (hour < 10 ? ('0'+hour):hour)+':'+(min < 10 ? ('0'+min):min);
          $('#total_time').val(total_time);
        })
  //添加新阶段
  $('.add_new_item').live('click',function(){
      //查看一共有多少阶段
      var item_num = $('.thingid').size();
      $('#remove_item').remove();
      var prev_time = $(this).parent().prev().prev().prev().prev().prev().children().val();

      var new_item = '<tr class="items date_'+item_num+'">        <th>事项：'+(item_num + 1)+'</th>        <td>        <select name="cateid[]" class="input_txt thingid">     <option value="">请选择</option>         <?php
              $result_cate = $db->query($sql_cate);
                  if($result_cate->num_rows){
                      while($row_cate = $result_cate->fetch_assoc()){
                         echo '<option value="'.$row_cate['id'].'">'.$row_cate['cate'].'</option>';
                      }
                  }
              ?>          </select>         </td>        <th>开始时间：</th>        <td>         <input type="text" name="start_time[]" value="'+prev_time+'" class="input_txt" onfocus="WdatePicker({dateFmt:\'HH:mm\',isShowClear:false,readOnly:true})"/>        </td>        <th>结束时间：</th>        <td>         <input type="text" name="end_time[]" class="input_txt"  onfocus="WdatePicker({dateFmt:\'HH:mm\',isShowClear:false,readOnly:true})" />         </td>    <th>用时：</th><td><input type="text" readonly name="offset_time[]" class="input_txt" /><th>备注：</th><td><input type="text" name="remark[]" class="input_txt"/></td> </td>   <td>      <input type="button" value="添加阶段" class="button add_new_item"> <input type="button" value="删除阶段" class="button" id="remove_item">        </td>      </tr>';
      $('.items:last').after(new_item);
      $(this).remove();
  })
  //删除阶段
  $('#remove_item').live('click',function(){
      var item_num = $('.thingid').size();
      $('.date_'+(item_num - 1)).remove();
      //添加按钮
      var add_item = '<input type="button" value="添加阶段" class="button add_new_item">';
      var remove_item = ' <input type="button"  value="删除阶段" class="button" id="remove_item" />';
      var last_item = $('.items:last').children('td:last');
      last_item.append(add_item);
      //项目数大于1时添加删除按钮
      if(item_num > 2){
        last_item.append(remove_item);
      }
  })
  //提交时判断项目名称和目标是否填写
  $("#submit").click(function(){
    
  })
})
</script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_sheet">
  <?php if($action == "add"){ ?>
  <h4>添加整天</h4>
  <form action="all_day_do.php" name="system" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <th>日期：</th>
        <td colspan="7">
            <input type="text" id="date"  name="date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd',isShowClear:false,readOnly:true})" class="input_txt" />
            <span class="tag"> *必填</span></td>
        </td>
        <th>总时间：</th>
        <td>
          <input type="text" readonly id="total_time" class="input_txt" />
        </td>
      </tr>
      <tr class="items date_0">
        <th>事项：1</th>
        <td>
          <select name="cateid[]" class="input_txt thingid">
            <option value="">请选择</option>
              <?php
                  $result_cate = $db->query($sql_cate);
                  if($result_cate->num_rows){
                      while($row_cate = $result_cate->fetch_assoc()){
                         echo '<option value="'.$row_cate['id'].'">'.$row_cate['cate'].'</option>';
                      }
                  }
              ?>
          </select>
        </td>
        <th>开始时间：</th>
        <td>
          <input type="text" name="start_time[]" class="input_txt" onfocus="WdatePicker({dateFmt:'HH:mm',isShowClear:false,readOnly:true})"/>
        </td>
        <th>结束时间：</th>
        <td>
          <input type="text" name="end_time[]" value="<?php echo date('H:i') ?>" class="input_txt" onfocus="WdatePicker({dateFmt:'HH:mm',isShowClear:false,readOnly:true})" />
        </td>
        <th>用时：</th>
        <td>
          <input type="text" name="offset_time[]" readonly class="input_txt" />
        </td>
        <th>备注：</th>
        <td>
          <input type="text" name="remark[]" class="input_txt" />
        </td>
        <td>
          <input type="button" value="添加阶段" class="button add_new_item">
        </td>
      </tr>
      <tr>
        <td colspan="11" style="text-align:center">
          <input type="submit" name="submit" id="submit" value="确定"class="button" />
            <input type="button" name="button" value="返回" class="button" onclick="javascript:history.go(-1);" />
            <input type="hidden" name="action" value="<?php echo $action; ?>" />
        </td>
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
