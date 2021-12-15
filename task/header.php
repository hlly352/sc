<div id="header">
  <h4>系统设置 <em>V1.0 BY Hillion</em><em style="display: none;">xdq</em></h4>
</div>
<div id="menu">
  <ul>
    <!-- <li class="menulevel"><a href="/system_config/">帮助</a></li> -->
    <li class="menulevel"><a href="#">添加任务</a>
        <ul>
          <li><a href="normal_task_add.php?action=add">日常任务</a></li>
          <li><a href="repeat_task_add.php?action=add">重复任务</a></li>
        </ul>
    </li>
    <li class="menulevel"><a href="#">任务列表</a>
        <ul>
          <li><a href="normal_task_list.php">日常任务</a></li>
          <li><a href="repeat_task_list.php">重复任务</a></li>
        </ul>
    </li>
    <li class="menulevel"><a href="task_summary.php">完成状态</a></li>
    <li class="menulevel"><a href="/myjtl/">内网首页</a></li>
  </ul>
  <span>
    <?php echo $_SESSION['employee_info']['employee_name']; ?>
    <a href="../passport/logout.php">退出</a>
  </span>
  <div class="clear"></div>
</div>
