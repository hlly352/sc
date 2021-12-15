<div id="header">
  <h4>系统设置 <em>V1.0 BY Hillion</em><em style="display: none;">xdq</em></h4>
</div>
<div id="menu">
  <ul>
    <!-- <li class="menulevel"><a href="/system_config/">帮助</a></li> -->
    <li class="menulevel"><a href="#">添加规则</a>
      <ul>
          <li><a href="repeat_rule_add.php?action=add">重复任务</a></li>
          <li><a href="category_add.php?action=add">无限分类</a></li>
          <li><a href="expend_add.php?action=add">支出比例</a></li>
      </ul>
    </li>
    <li class="menulevel"><a href="#">规则列表</a>
      <ul>
          <li><a href="repeat_rule_list.php">重复规则</a></li>
          <li><a href="category_list.php">无限分类</a></li>
          <li><a href="expend_list.php">支出比例</a></li>
      </ul>
    </li>
    <li class="menulevel"><a href="/myjtl/">内网首页</a></li>
  </ul>
  <span>
    <?php echo $_SESSION['employee_info']['employee_name']; ?>
    <a href="../passport/logout.php">退出</a>
  </span>
  <div class="clear"></div>
</div>
