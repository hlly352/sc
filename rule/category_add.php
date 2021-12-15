<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once 'shell.php';
$action = fun_check_action($_GET['action']);
//查找所有分类
$sql_cate = "SELECT * FROM `sc_category` WHERE `status` = '1' ORDER BY `full_path` ASC";
$result_cate = $db->query($sql_cate);
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
<script language="javascript" type="text/javascript">
$(function(){
    $('#submit').click(function(){
    	var category_name = $('#category_name').val();
    	var category_code = $('#category_code').val();
      but = '';
      //验证代码是否重复
      $.ajax({
          'url':'../ajax_function/check_category_code.php',
          'data':{category_code:category_code},
          'type':'post',
          'dataType':'text',
          'async':false,
          'success':function(data){
              if(data == 1){
                but = 1;
              }
          }
      })  
      //验证不通过提示
      if(but == 1){
        alert('代码不能重复');
        $('#category_code').focus();
        return false;
      }
    	if(!category_name){
    		alert('请输入分类名称');
    		$('#category_name').focus();
    		return false;
    	}
    	if(!category_code){
    		alert('请输入分类代码');
    		$('#category_code').focus();
    		return false;
    	}
    })
})
</script>
<title>系统设置-希尔林</title>
</head>

<body>
<?php include "header.php"; ?>
<div id="table_sheet">
  <?php if($action == "add"){ ?>
  <h4>事项分类添加</h4>
  <form action="category_do.php" name="system" method="post" enctype="multipart/form-data">
    <table>
      <tr>
        <th width="20%">父类</th>
        <td width="80%">
            <select name="pid">
                <option value="0">总类</option>
                <?php
                    if($result_cate->num_rows){
                        while($row_cate = $result_cate->fetch_assoc()){
                            $str_count = strlen($row_cate['path']);
               
                            $suff_str = str_repeat('-',$str_count);
                            echo '<option value="'.$row_cate['id'].'">'.$suff_str.$row_cate['cate_name'].'</option>';
                        }
                    }
                ?>
            </select>
        </td>
      </tr>
      <tr class="new_rule">
        <th width="20%">事项分类名:</th>
        <td width="80%">
        	<input type="text" name="category_name" id="category_name" class="input_txt" />
        </td>
      </tr>
      <tr>
        <th>分类代码:</th>
        <td>
        	<input type="text" name="category_code" class="input_txt" id="category_code" />
        </td>
      </tr>
      <tr id="sub">
        <th>&nbsp;</th>
        <td><input type="submit" name="submit" id="submit" value="确定"class="button" />
          <input type="button" name="button" value="返回" class="button" onclick="javascript:history.go(-1);" />
          <input type="hidden" name="action" value="<?php echo $action; ?>" /></td>
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