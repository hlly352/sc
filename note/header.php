<div id="header">
  <h4>系统设置 <em>V1.0 BY Hillion</em><em style="display: none;">xdq</em></h4>
</div>
<div id="menu">
  <ul>
    <!-- <li class="menulevel"><a href="/system_config/">帮助</a></li> -->
    <li class="menulevel"><a href="#">添加记录</a>
      <ul>
          <li><a href="time_usage_add.php?action=add">时间</a></li>
          <li><a href="all_day_add.php?action=add">整天</a></li>
          <li><a href="income_add.php?action=add">收入</a></li>
          <li><a href="expend_add.php?action=add">支出</a></li>
          <li><a href="fixed_assets_add.php?action=add">固定资产</a></li>
          <li><a href="skill_add.php?action=add">技能</a></li>
          <li><a href="soft_power_add.php?action=add">品质</a></li>
      </ul>
    </li>
    <li class="menulevel"><a href="time_usage_list.php">时间记录</a></li>
    <li class="menulevel"><a href="all_day_list.php">整天记录</a></li>
    <li class="menulevel"><a href="income_list.php">收入记录</a></li>
    <li class="menulevel"><a href="expend_list.php">支出记录</a></li>
    <li class="menulevel"><a href="fixed_assets_list.php">固定资产</a></li>
    <li class="menulevel"><a href="skill_list.php">技能</a></li>
    <li class="menulevel"><a href="soft_power_list.php">品质</a></li>
    <li class="menulevel"><a href="#">实时动态</a>
      <ul>
          <li><a href="finance_dynamic.php">财务动态</a></li>
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
