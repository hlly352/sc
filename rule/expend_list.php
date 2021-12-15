<?php
require_once '../global_mysql_connect.php';
require_once '../function/function.php';
require_once '../class/page.php';
require_once 'shell.php';
//查询已用规则
$sql_rule = "SELECT DISTINCT(`rule_name`) AS `ruleid` FROM `sc_memory_task`";
$result_rule = $db->query($sql_rule);
$array_ruleid = array();
if($result_rule->num_rows){
       while($row_ruleid = $result_rule->fetch_assoc()){
        $array_ruleid[] = $row_ruleid['ruleid'];
    }
}
if($_GET['submit']){
	$rule_name = trim($_GET['rule_name']);
	$sqlwhere = " AND `sc_expend_rule`.`rule_name` LIKE '%$rule_name%'";
}
$sql = "SELECT `sc_expend_rule`.`is_select`,`sc_expend_rule`.`ruleid`,`sc_expend_rule`.`rule_name`,SUM(`sc_expend_rule_info`.`proportion`) AS `sum`,GROUP_CONCAT(CONCAT(`sc_category`.`cate_name`,':',`sc_expend_rule_info`.`proportion`,'%') SEPARATOR '|') AS `info`,`sc_expend_rule`.`rule_status`,`sc_expend_rule`.`remark` FROM `sc_expend_rule` INNER JOIN `sc_expend_rule_info` ON `sc_expend_rule`.`ruleid` = `sc_expend_rule_info`.`ruleid` INNER JOIN `sc_category` ON `sc_expend_rule_info`.`expend_cate` = `sc_category`.`id` WHERE `sc_expend_rule`.`rule_status` = '1' $sqlwhere GROUP BY `sc_expend_rule`.`ruleid` ";

$result = $db->query($sql);
$pages = new page($result->num_rows,12);
$sqllist = $sql . " ORDER BY `sc_expend_rule`.`dotime` DESC,`sc_expend_rule`.`ruleid` ASC" . $pages->limitsql;
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
        //若是选中状态，不可更改
        var is_select = $(this).attr('data');
        if(is_select == 1){
          alert('不可全部关闭');
          return false;
        }
        var id = $(this).attr('id');
        id = id.substr(id.indexOf('_')+1);
        var that = $(this);
        $.ajax({
          'url':'../ajax_function/switch_expend_rule_status.php',
          'data':{id:id},
          'type':'post',
          'success':function(data){
              if(data == 1){
                  that.attr('data','1').children().css('background-position','-19px -15px');
                  $('.status').not('#status_'+id).attr('data','0').children().css('background-position','-24px -55px');
              }else if(data == 0){
                  location.reload();
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
  <h4>支出规则列表</h4>
  <form action="" name="search" method="get">
    <table>
      <tr>
        <th>名称：</th>
        <td><input type="text" name="rule_name" class="input_txt" /></td>
        <td><input type="submit" name="submit" value="查询" class="button" />
          <input type="button" name="button" value="添加" class="button" onclick="location.href='memory_rule_add.php?action=add'" />
          <input type="text" style="display:none;" /></td>
      </tr>
    </table>
  </form>
</div>
<div id="table_list">
  <?php if($result->num_rows){ ?>
  <form action="memory_rule_do.php" name="system_list" method="post">
    <table>
      <tr>
        <th width="4%">ID</th>
        <th width="10%">名称</th>
        <th width="40%">info</th>
        <th width="10%">总比例</th>
        <th width="20%">备注</th>
        <th width="4%">status</th>
        <th width="6%">当前规则</th>
        <th width="4%">Edit</th>
      </tr>
      <?php while($row = $result->fetch_assoc()){
		    $ruleid = $row['ruleid'];
        $status = '';
        //根据状态设置图标
        if($row['is_select'] == 1){
            $status = 'background-position:-19px -15px';
        }else{
            $status = 'background-position:-24px -55px';
        }            
	    ?>
      <tr>
        <td><input type="checkbox" name="id[]" <?php if(in_array($ruleid,$array_ruleid)){ echo 'disabled'; } ?> value="<?php echo $ruleid; ?>" /></td>
        <td><?php echo $row['rule_name']; ?></td>
        <td><?php echo $row['info']; ?></td>
        <td><?php echo $row['sum']; ?></td>
        <td><?php echo $row['remark']; ?></td>
        <td><?php echo $array_status[$row['rule_status']]; ?></td>
        <td>
        	<a href="#" class="status" data="<?php echo $row['is_select']; ?>" id="status_<?php echo $ruleid ?>">
                <p style="width:70px;margin:3px auto;height:15px;background-image:url('../images/system_ico/switch.png');background-repeat:no-repeat;<?php echo $status ?>"></p>
              </a>
        </td>
        <td><a href="systemae.php?id=<?php echo $systemid; ?>&action=edit"><img src="../images/system_ico/edit_10_10.png" width="10" height="10" /></a></td>
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
