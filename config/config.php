<?php
//配置连接数据库参数
$db_host       = "localhost";  //数据库主机
$db_user       = "root";       //数据库用户名
$db_pw         = "jiatailong";           //数据库密码
$db_dataname   = "sc";         //数据库名
$db_chareset   = "utf8";       //数据库连接字符串
//密码前缀
define('ALL_PW',"JTL");
//基础
$array_status = array('1'=>'有效','0'=>'无效');
$array_is_status = array('1'=>'是','0'=>'否');
$array_finish_status = array('1'=>'完成','0'=>'进行');
$array_week = array("日","一","二","三","四","五","六");
$array_system_type = array('A'=>'我的系统','C'=>'我的模具','D'=>'我的注塑','B'=>'公用系统');
//登录
$array_login_status = array('A'=>'登录成功','B'=>'密码错误','C'=>'账号关闭','D'=>'账号不存在');
//人事
$array_employee_status = array('1'=>'在职','0'=>'离职');
$array_position_type = array('A'=>'总经理','B'=>'经理主管','C'=>'班组长','D'=>'员工');
$array_education_type = array('A'=>'小学','B'=>'初中','C'=>'高中','D'=>'中专','E'=>'大专','F'=>'本科');
$array_work_shift = array('A'=>'正常班8H','D'=>'白班12H','E'=>'夜班12H');
//PDCA
$array_pdca_status = array('P'=>'计划','D'=>'执行','C'=>'检查','A'=>'处理');
$array_pdca_result = array('A'=>'未接受','B'=>'执行中','C'=>'按时完成','D'=>'超期完成','E'=>'超期未完成');
$array_pdca_update_type = array('A'=>'工作反馈','B'=>'申请延期','C'=>'申请关闭');
//我的办公
$array_office_approve_status = array('A'=>'审核','B'=>'同意','C'=>'退回');
$array_express_paytype = array('A'=>'寄方付','B'=>'收方付');
$array_job_plan_type = array('A'=>'日计划','B'=>'周计划','C'=>'月计划','D'=>'年计划');
$array_routine_work_type = array('A'=>'按星期','B'=>'按月','C'=>'固定日期');
//用车
$array_vehicle_type = array('A'=>'公车','B'=>'外叫车');
$array_vehicle_dotype = array('A'=>'返工','B'=>'接送客户','C'=>'补货','D'=>'拜访','E'=>'送货','F'=>'其他');
$array_vehicle_roundtype = array('A'=>'单程','B'=>'往返');
$array_vehicle_category = array('A'=>'小车','B'=>'货车1T','C'=>'货车2T','D'=>'货车3T','E'=>'货车5T','F'=>'货车10T');
$array_vehicle_pathtype = array('A'=>'市区','B'=>'长途');
$array_express_get_status = array('1'=>'已领','0'=>"未领");
$array_express_apply_status = array('1'=>'是','0'=>"否");
//模具数据
$array_mould_cavity_type = array('A'=>'1*1','B'=>'1*2','C'=>'1*4','D'=>'1+1','E'=>'2+2');
$array_mould_quality_grade = array('A+','A','B','C');
$array_mould_assembler = array('A'=>'一组','B'=>'二组','C'=>'三组','D'=>'四组');
$array_mould_inout_status = array(0=>'未回',1=>'已回');
$array_tax_rate = array('0.13','0.16','0.17','0.05','0.03','0.00');
//物料系统
$array_order_status = array(0=>'未下单',1=>'已下单');
$array_inout_dotype = array('I'=>'入库','O'=>'出库');
//刀具数据
$array_cutter_texture = array('A'=>'钨钢','B'=>'合金','C'=>'预硬钢');
//模具报价
$array_quote_status = array(0=>'报价',1=>'成交');
//热处理数据
$array_mould_heat = array('tempered'=>'调质/Tempered','hardened'=>'淬火/Hardened','nitridation'=>'氮化/Nitridation');
//材料名称
$array_mould_material = array('cavity'=>'型腔/Cavity','core'=>'型芯/Core','silde'=>'滑块/Slide','lifter'=>'斜顶/Lifter','insert'=>'镶件/Insert','electrode'=>'电极/Electrode');
$array_mould_materials = array('base'=>'模架/Mode Base','cavity'=>'型腔1/Cavity','cavitys'=>'型腔2/Cavity','core'=>'型芯1/Core','cores'=>'型芯2/Core','silde'=>'滑块/Slide','lifter'=>'斜顶/Lifter','inserts'=>'镶件/Insert','electrode'=>'电极/Electrode');

//模具配件数据
$array_mold_standard = array('Inserts'=>'镶件、日期章/Inserts','sleeve'=>'顶杆、顶管/Ejection Pin\\Sleeve','connector'=>'水管、油管接头/Connector','components'=>'标准件/Standard Components','hotrunner'=>'热流道/Hot Runner','tempcontroller'=>'温控器/Temp Controller','cylinder'=>'油缸/Hydro-cylinder');
//模具设计项目
$array_mould_design = array('scanning'=>'扫描测绘/Scanning','cad'=>'结构设计/CAD','cam'=>'CAM设计/CAM','cae'=>'CAE分析/CAE');
//模具加工费数据
$array_mould_manufacturing = array('maching'=>'一般机床/Maching','grinding'=>'磨床/Grinding','cnc'=>'数控机床/CNC','precision_cnc'=>'精密数控机床','wc'=>'线切割/W.C.','edm'=>'电火花/EDM','polish'=>'抛光/Polish','fitting'=>'钳工/Fitting','laser'=>'激光烧焊/Laser Welding','texture'=>'皮纹/Texture cost');
//模具配件品牌
$array_standard_supplier = array('品牌一','品牌二','品牌三','品牌四');
//客户管理
//客户等级
$array_customer_grade = array(0=>'一般',1=>'重要',2=>'关键');
//报价汇总币种
$array_currency = array('rmb_vat'=>'人民币(含税)','rmb'=>'人民币(未税)','usd'=>'美元','eur'=>'欧元','jpy'=>'日元');
//订单启动
//图纸类型
$array_drawing_type = array('2D','3D','样品');
//重点要求
$array_require = array('关键尺寸','外观要求','后处理');
//装夹方式
$array_install_way = array('自动装夹','螺孔、螺丝','码仔');
//唧嘴SR
$array_ji_sr = array('平面','90度','SR');
//模具要求
$array_mould_require = array('实验模','生产模');
//模具类型
$array_mould_type = array('标准','气辅','低压','高温','包胶','多色','高光','多腔');
//模具形式
$array_mould_way = array('2板','3板','3板简化版','2板倒装');
//型腔/型芯方式
$array_cavity_mode = array('原生/镶件','原生/原生','镶件/原生','镶件/镶件');
//组合互换
$array_mould_group = array('共用模架','共用热流道','共用配件','互换镶件');
//图纸标准
$array_drawing_standard = array('公制','英制','美制');
//难度系数
$array_difficulty_degree = array('0.6','0.7','0.8','0.9','1.0','1.1','1.2','1.3');
//质量等级
$array_quality_degree = array('A+','A','B','C');
//浇口类型
$array_injection_type = array('点浇口','直接浇口','侧浇口','潜水口','热流道点浇口','热流道大水口','热流道阀浇口');
//阀针类型
$array_needle_type = array('无','气阀','油阀');
//流道类型
$array_runner_type = array('冷流道','热流道');
//热流道品牌
$array_hot_runner_supplier = array('Yudo','Synvative','Moldmaster');
//冷却加热介质
$array_cool_medium = array('冰水','温水','热水','热油');
//特殊冷却加热
$array_sepcial_cool = array('铍铜','3D打印','冷却棒','加热棒','急冷急热');
//顶出系统
$array_ejection_system = array('顶针','司筒','顶块','直顶','斜顶','推板','气顶','二次顶出','油压顶出
');
//取件方式
$array_pickup_way = array('自动掉落','机械手','手工');
//项目名称
$array_project_name = array('模架','模架A板','模架B板','模架顶针板','型腔','型芯','滑块','斜顶','镶件');
//材料硬度
$array_material_hard = array('HRC18-28','HRC28-32','HRC38-42','HRC48-52','HRC28~32','HRC37~41','HRC32~36','HRC31~36','HRC15~20','HRC33-37');
//特殊处理
$array_special_handle = array('无','氮化','涂层','热处理','热处理+氮化','热处理+涂层');
//表面要求
$array_surface_require = array('刀纹','去刀纹','省模','抛光','火花纹','蚀纹','镜面');
//打样胶料
$array_draw_material = array('自购','客供','代客购买');
//寄样方式
$array_draw_post = array('国内快递','国际快递','客户自提');
//包装方式
$array_pack_method = array('木箱','铁架','踏板');
//运输方式
$array_mould_transport = array('海运','空运','汽运','火车');
//交付地点
$array_hand_over = array('本厂','国内','国外');
//结算方式
$array_settle_way = array('EXW','FOB','CIF','DDP');
//产品检查报告
$array_product_check = array('自检','客检','第三方');
//模具外观喷漆
$array_surface_spray = array('无','客户指定颜色');
//热流道、运水、动作铭牌
$array_action_plate = array('热流道','运水','动作铭牌');
//客户铭牌
$array_customer_plate = array('客户','本厂');
//吊环、备件、电极
$array_mould_ring = array('吊环','备件','电极','末次样品');
//模具手册
$array_mould_handbook = array('随箱发送','单独寄客户');
//试模报告、样品检测报告
$array_sample_check = array('不要','每次','仅末次');
//模具包装方式
$array_mould_pack = array('木箱','铁架','踏板');
// 图纸检查对照表
$array_drawing_check = array('客户','本厂');
//客户评审方式
$array_judge_method = array('按2D模具结构图','按全3D','现场评审');
//客户确认方式
$array_customer_confirm = array('书面','现场确认');
//项目进度汇报
$array_project_progress = array('每周','每月','每天');
//出错处理
$array_error_report = array('自行补救','客户方案');
//皮纹
$array_skin_texture = array('模德','客户指定','普通');
//材料品牌
$array_material_county = array('国产','进口');
$array_material_supplier = array('HASCO','DME','LKM','仿HASCO','仿DME','仿LKM','其他');
// 材料牌号
$array_material_specification = array("45#(国产)","S50C(国产)","P20(国产)","P20(进口)","718/718H(国产)","718/718H(进口)","738/738H(国产)","2738(国产)","738/738H(进口)","2311(国产)","2311(进口)","2312(国产)","2312(进口)","NAK80(国产)","NAK80(进口)","2711(进口)","Cr12(国产)","H13(国产)","H13(进口)","S136(国产)","S136(进口)","8407(国产)","8407(进口)","8402(国产)","8402(进口)","2344(国产)","2344(进口)","2344SER(国产)","2344SER(进口)","2343(国产)","2343(进口)","2343SER(国产)","2343SER(进口)","DAC-S(进口)","CENA1(进口)","PX4(进口)","PX5(进口)","S-STAR(进口)","2083(国产)","2083(进口)","Cu","S45C","SUS420J2",'1.1730','1.2343','1.2343SER','1.2344','1.2344SER','1.2312','SKD61','1.2311','1.2738','1.2738HH','XPM','XPMESR','GEST80','GEST80ESR','1.2083','1.2083ESR','S136H','420');
//品牌
$array_supplier = array('HASCO','DME','LKM','盘起','仿HASCO','仿DME','仿LKM','仿盘起','其他');
//油缸
$array_cylinder = array('Merkle','HPS','HEB','Parker','仿Merkle','仿HPS','仿HEB','仿Parker','其他');
//水管接头
$array_water_connector = array('HASCO','DME','Staubli','日东','仿HASCO','仿DME','仿Staubli','日东','其他');
//气动接头
$array_air_connector = array('HASCO','DME','Staubli','日东','仿HASCO','仿DME','仿Staubli','仿日东','其他');
//油压接头
$array_oil_connector = array('HASCO','DME','LKM','仿HASCO','仿DME','仿LKM','其他');
//售后服务
$array_service_fee = array('收费','免费');
//物料申请
//物料类型
$array_mould_other_material = array('易耗品','办公用品','福利品','其它');
//物料状态
$array_mould_material_status = array('C'=>'通过','D'=>'退回','E'=>'询价中','F'=>'已下单','G'=>'已入库');
//项目资料类型
$array_project_data_type = array(array('技术资料',array('project_data'=>'客户项目资料','mould_data'=>'客户模具资料','drawing'=>'客户2D图纸','flow_analysis'=>'模流分析')),array('项目启动会',array('project_review'=>'评审记录','dfm_report'=>'DFM报告','progress'=>'进度规划','customer_confirm'=>'客户方案确认')),array('模具试模',array('trial_mode'=>'试模报告','sample_photo'=>'试模视频、样品照片','red_photo'=>'机上红丹照片')),array('模具交付及售后',array('after_sale_confirm'=>'客户交付确认','out_factory'=>'出厂检查表','annex_list'=>'附件清单','car_photo'=>'装箱、装车照片','delivery_note'=>'放行条、送货单','service'=>'售后服务记录','customer_indication'=>'客户终验收表')),array('项目总结',array('project_sum'=>'总结报告')));
$array_project_data = array('technical_info'=>array('技术资料',array('project_data'=>'客户项目资料','mould_data'=>'客户模具资料','drawing'=>'客户2D图纸','flow_analysis'=>'模流分析')),'project_start'=>array('项目启动会',array('project_review'=>'评审记录','dfm_report'=>'DFM报告','progress'=>'进度规划','customer_confirm'=>'客户方案确认')),'mould_processing'=>array('模具加工',array('manufactring_crafts'=>'加工工艺','manufactring_red_photo'=>'红丹照片')),'mould_try'=>array('模具试模',array('trial_mode'=>'试模报告','sample_photo'=>'试模视频、样品照片','red_photo'=>'机上红丹照片')),'delivery_service'=>array('模具交付及售后',array('after_sale_confirm'=>'客户交付确认','out_factory'=>'出厂检查表','annex_list'=>'剩余物料清单','car_photo'=>'装箱、装车照片','delivery_note'=>'放行条、送货单','service'=>'售后服务记录','customer_indication'=>'客户终验收表')),'project_sum'=>array('项目总结',array('project_sum'=>'总结报告')));
//改模资料
$array_mould_modify = array('last_report'=>'上次试模报告','customer_data'=>'客户改模资料','modify_data'=>'内部改模资料','modify_plan'=>'改模计划','drawing_connection'=>'图纸联络单','before_check'=>'装模前检查表','try_apply'=>'试模申请','dan_photo'=>'机上红丹照片','sample_photo'=>'样品照片','try_report'=>'试模报告','sample_check'=>'样品检测报告','sample_delivery'=>'样品交付');
//设计输出
$array_design_out = array('design_plan'=>'设计计划','design_review'=>'设计评审','drawing_concat'=>'图纸联络单');
//加工资料
$array_processing_data = array('processing_technology'=>'加工工艺','processing_plan'=>'加工计划','check_report'=>'机上检测报告','maching_red_photo'=>'红丹照片','installation_report'=>'装模前检查报告');
//品质控制
$array_quality_data = array('part_report'=>'零件检测报告','product_report'=>'产品检测报告','error_report'=>'出错报告');
//会议室
$array_meetingroom = array('two'=>'二楼','three'=>'三楼');
//设计计划
$array_design_plan = array('dfm','program','product','start','2d','3d_v1','3d_v2','customer_ok','mold','hot','finishing','mold_nc','embryo','machining','nc_finishing','parts','standard','mold_2d','other_parts','sun_word');
$array_design_plan_excel = array('R'=>'dfm','S'=>'program','T'=>'product','U'=>'start','V'=>'2d','W'=>'3d_v1','X'=>'3d_v2','Y'=>'customer_ok','Z'=>'mold','AA'=>'hot','AB'=>'finishing','AC'=>'mold_nc','AD'=>'embryo','AE'=>'machining','AF'=>'nc_finishing','AG'=>'parts','AH'=>'standard','AI'=>'mold_2d','AJ'=>'other_parts','AK'=>'sun_word');
//模具更改资料内容
$array_data_content = array('1'=>'更改','新模','研发设计部设变','异常更改','模图完善','客户设变');
//模具更改接收部门
$array_data_dept = array('1'=>'总经办','2'=>'市场部','7'=>'项目部','5'=>'采购部','10'=>'编程','17'=>'线割组','9'=>'生产部','6'=>'研发设计部','8'=>'品质部','11'=>'钳工1组','12'=>'钳工2组','15'=>'省模','16'=>'火花机','18'=>'机加工','4'=>'人事');
//设计评审资料
$array_design_review = array('shrink_check','pl_confirm','gum_method','mold_size','insert','eject_method','cool_design','eject_stroke','positioning_method','base_size');
//模具更改联络单图档用途
$array_mould_change_use = array('K'=>'开粗','J'=>'精光','A'=>'按特殊要求:');
//模具检查表项目等级
$array_mould_check_degree = array('A'=>'一般','B'=>'重要');
//项目计划内容
$array_project_plan = array('design_review','design_order','material_arrive','design_3D','parts_order','parts_figure','drilling','cnc_rough','heat_treatment','grinder','wire_cutting','cnc_start','cnc_in','light_knife','cnc_copper','edm','fit_mould','provincial_mould','assembly_mould','try_mould');
//塑胶报价项目内容
$array_plastic_quote = array('material_unit_price'=>'原料单价','weight'=>'净重(g)','material_head'=>'料头(g)','attrition_rate'=>'损耗率(%)','attrition'=>'损耗','total_material'=>'小计','ton'=>'吨数(T)','machine_cost'=>'机台费用','cycle'=>'周期(秒)','cavity_num'=>'穴数','machine_total'=>'小计','protective_film'=>'保护膜','outsourced'=>'外购配件','accessories_total'=>'小计','print'=>'印刷','plating'=>'电镀/镭雕','spray_paint'=>'喷漆费用','print_total'=>'小计','package'=>'包装费用','transport'=>'运费','package_total'=>'小计');
//注塑报价注塑机品牌
$array_plastic_brand = array('海天'=>array('MA600 Ⅱ S/130','MA900 Ⅱ S/280','MA1200 Ⅱ S/400','MA1600 Ⅱ S/570','MA2000 Ⅱ S/750','MA2500 Ⅱ S/1000','MA2800 Ⅱ S/1350','MA3200 Ⅱ S/1700','MA3800 Ⅱ S/2250','MA4700 Ⅱ S/3200','MA5300 Ⅱ S/4500','MA6000 Ⅱ S/4500','MA7000 Ⅱ S/5000','MA8000 Ⅱ S/6800','MA9000 Ⅱ S/6800','MA10000 Ⅱ S/8400','MA12000 Ⅱ S/7400'),'那发克'=>array('MA600 Ⅱ S/130','MA900 Ⅱ S/280','MA1200 Ⅱ S/400','MA1600 Ⅱ S/570','MA2000 Ⅱ S/750','MA2500 Ⅱ S/1000','MA2800 Ⅱ S/1350','MA3200 Ⅱ S/1700','MA3800 Ⅱ S/2250','MA4700 Ⅱ S/3200','MA5300 Ⅱ S/4500','MA6000 Ⅱ S/4500','MA7000 Ⅱ S/5000','MA8000 Ⅱ S/6800','MA9000 Ⅱ S/6800','MA10000 Ⅱ S/8400','MA12000 Ⅱ S/7400'),'三菱'=>array('三菱一','三菱二','三菱三'));
//注塑机吨位
$array_plastic_tonnage = array('60T'=>'60','90T'=>'90','120T'=>'120','160T'=>'160','200T'=>'200','250T'=>'250','280T'=>'280','320T'=>'320','380T'=>'380','470T'=>'470','530T'=>'530','600T'=>'600','700T'=>'700','800T'=>'800','900T'=>'900','1000T'=>'1000','1200T'=>'1200');
//加工计划
$array_manufactring_plan = array('arrive_material','3d_design','arrive_spare','spare_picture','drilling','cnc_rough','heat','grinder','cnc_start','cnc_middle','cnc_knife','wire_cutting','cnc_copper','edm','fit','saving_model','install_model','try_model');
//日常任务重复次数
$array_normal_repeat = array('A'=>'一次','B'=>'每周','C'=>'每月','D'=>'每年');
$array_week = ['星期天','星期一','星期二','星期三','星期四','星期五','星期六'];
//任务类型
$array_task_type = array('M'=>'记忆任务','E'=>'练习任务');
//日期搜索条件
$sdate = $edate = '';
//月末后五天月份加1
$cur_date = date('d');
if(1 <= $cur_date && $cur_date < 25){
	$time_str = '';
}else{
	$time_str = '+1 month';
}
$edate = $_GET['edate']?$_GET['edate']:date('Y-m-d',strtotime(date('Y-m-24',time()).$time_str));
$sdate = $_GET['sdate']?$_GET['sdate']:date('Y-m-25',strtotime($edate."-1 month"));
//计划类型
$array_plan_type = array('A'=>'健康','B'=>'能力','C'=>'财务','D'=>'信仰','E'=>'时间','F'=>'亲情');
//计划分类
$array_plan_category = array('Y'=>'年计划','M'=>'月计划','W'=>'周计划');
//规则类型
$array_rule_type = array('M'=>'记忆','E'=>'练习');
//固定资产来源
$array_fixed_source = array('A'=>'网购','B'=>'商店购买','C'=>'赠送');
