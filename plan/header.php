<div id="header">
  <h4>系统设置 <em>V1.0 BY Hillion</em><em style="display: none;">xdq</em></h4>
</div>
<div id="menu">
  <ul>
    <li class="menulevel"><a href="#">添加计划</a>
        <ul>
          <li>
            <a href="project_plan_add.php?action=add">
              项目计划</a>
          </li>
          <li>
            <a href="plan_add.php?action=add">
              事件计划</a>
          </li> 
          <li>
            <a href="financial_plan_add.php?action=add">
              财务计划</a>
          </li>    
          <li>
            <a href="time_plan_add.php?action=add">
              时间计划</a>
          </li>                                  
        </ul>
    </li>
    <li class="menulevel"><a href="#">计划列表</a>
        <ul>
          <li>
            <a href="project_plan_list.php">项目计划</a>
          </li>
          <li>
            <a href="plan_list.php">事件计划</a>
          </li>
          <li>
            <a href="financial_plan_list.php">财务计划</a>
          </li>
          <li>
            <a href="time_plan_list.php">时间计划</a>
          </li>          
        </ul>
    </li>
    <!-- <li class="menulevel"><a href="memory_task_summary.php">完成状态</a></li> -->
    <li class="menulevel"><a href="/myjtl/">内网首页</a></li>
  </ul>
  <span>
    <?php echo $_SESSION['employee_info']['employee_name']; ?>
    <a href="../passport/logout.php">退出</a>
  </span>
  <div class="clear"></div>
</div>
