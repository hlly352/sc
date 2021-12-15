// JavaScript Document
//数字判断
var ri_a = /^\d+$///非负整数(正整数+0)
var ri_b = /^[0-9]*[1-9][0-9]*$/ //正整数
var ri_c = /^((-\d+)|(0+))$/ //非正整数(负整数+0) 
var ri_d = /^-[0-9]*[1-9][0-9]*$/ //负整数
var ri_e = /^-?\d+$/ //整数
var rf_a = /^\d+(\.\d+)?$/ //非负浮点数(正浮点数+0)
var rf_b = /^(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*))$/ //正浮点数
var rf_c = /^((-\d+(\.\d+)?)|(0+(\.0+)?))$/ //非正浮点数(负浮点数+0)
var rf_d = /^(-(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*)))$/ //负浮点数
var rf_e = /^(-?\d+)(\.\d+)?$/ //浮点数
//邮箱地址判断
var email_reg = /^[A-Za-zd]+([-_.][A-Za-zd]+)*@([A-Za-zd]+[-.])+[A-Za-zd]{2,5}$/;
var zimu_reg= /^[A-Za-z]+$/;
$(function(){
  $('input[name ^= start_time],input[name ^= end_time]').live('blur',function(){
            var start_time = $('input[name = start_time]').val();
            var end_time   = $('input[name = end_time]').val();
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
                $('input[name ^= offset_time]').val(offset_time);
            }
       })	
	//菜单下拉
	$('#menu ul li').mouseover(function(){
		$(this).children('ul').show();
	});
	$('#menu ul li').mouseleave(function(){
		$(this).children('ul').hide();
	});
	//全选，反选，清除 
	$("#CheckedAll").click(function(){
		$('[name^=id]:checkbox').attr('checked',true);
		$('[id=submit]').attr('disabled',false);
	});
	$("#CheckedNo").click(function(){
		$('[name^=id]:checkbox').attr('checked',false);
		$('[id=submit]').attr('disabled',true);
	});
	$("#CheckedRev").click(function(){
		$('[name^=id]:checkbox').each(function(){
			this.checked=!this.checked;
		});
		flag=false;
		if(!$('[name^=id]:checkbox').filter(':checked').length){
			flag=true;
		}
		$('[id=submit]').attr('disabled',flag);
	});
	//checkbox id 选择
	$('[name^=id]:checkbox').click(function(){
		flag=false;
		if(!$('[name^=id]:checkbox').filter(':checked').length){
			flag=true;
		}
		$('[id=submit]').attr('disabled',flag);
	});
	//隔行换色
	$("#table_list tr:even,#table_sheet tr:even").addClass("even");
	//input txt 获取焦点
	$(".input_txt:input").focus(function(){
	$(this).addClass("focus");
	}).blur(function(){
		$(this).removeClass("focus");
	})
	//form_list 鼠标滑动高亮
	$("#table_list tr").mouseover(function(){					
		$(this).addClass('highlight').siblings().removeClass('highlight');
	})
	$("#add_file").click(function(){
		$(this).after("<br /><input type=\"file\" name=\"file[]\" class=\"input_files\"");
	})
    /************点击完成操作*****************/
      $('.action').live('click',function(){
      //删除已有的输入框
      $('#do_time').remove();
      var current_time = getDate();
      //把时间单元格变为可输入
      var remark_content = $(this).parent().prev().prev().text();
      var remark = '<input type="text" id="remark" value="'+remark_content+'">';
      var inp = '<input type="text" id="do_time" value="'+current_time+'" onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm:ss\',isShowClear:false,readOnly:true})">';
      $(this).parent().prev().append(inp);
      $(this).parent().prev().prev().empty().append(remark);
      $(this).attr('class','complete');
      $(this).children().attr('src','../images/system_ico/query.png');
    })
    //点击确定完成动作
    $('.complete').live('click',function(){
      var id = $(this).attr('id');
      var infoid = id.substr((id.lastIndexOf('_') + 1));
      var do_time = $('#do_time').val();
      var remark  = $('#remark').val();
      $.ajax({
        'url':'../ajax_function/set_time_plan_time.php',
        'data':{infoid:infoid,do_time:do_time,remark:remark},
        'type':'post',
        'success':function(data){
          if(data){
            window.location.reload();
          }
        }
      })
      
    })
    //撤销操作
    $('.cancel').live('click',function(){
      var id = $(this).attr('id');
      var infoid = id.substr((id.lastIndexOf('_') + 1));
      $.ajax({
        'url':'../ajax_function/cancel_time_plan_time.php',
        'data':{infoid:infoid},
        'type':'post',
        'success':function(data){
          if(data){
            window.location.reload();
          }
        }
      })      
    })
})
//复制地址
function copyToClipboard(txt) {    
     if(window.clipboardData) {    
        window.clipboardData.clearData();    
        window.clipboardData.setData("Text", txt);    
        alert("Your request has been processed successfully.");    
      } else if(navigator.userAgent.indexOf("Opera") != -1) {    
       window.location = txt;    
      } else if (window.netscape) {    
      try {    
        netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");    
       } catch (e) {    
        alert("被浏览器拒绝！\n请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true'");    
       }    
      var clip = Components.classes['@mozilla.org/widget/clipboard;1'].createInstance(Components.interfaces.nsIClipboard);    
      if (!clip)    
       return;    
      var trans = Components.classes['@mozilla.org/widget/transferable;1'].createInstance(Components.interfaces.nsITransferable);    
      if (!trans)    
       return;    
       trans.addDataFlavor('text/unicode');    
      var str = new Object();    
      var len = new Object();    
      var str = Components.classes["@mozilla.org/supports-string;1"].createInstance(Components.interfaces.nsISupportsString);    
      var copytext = txt;    
       str.data = copytext;    
       trans.setTransferData("text/unicode",str,copytext.length*2);    
      var clipid = Components.interfaces.nsIClipboard;    
      if (!clip)    
       return false;    
       clip.setData(trans,null,clipid.kGlobalClipboard);    
       alert("Your request has been processed successfully.");    
      }    
}
//比较日期/时间
function GetDateDiff(startTime, endTime, diffType) {
	 //将xxxx-xx-xx的时间格式，转换为 xxxx/xx/xx的格式 
	 startTime = startTime.replace(/\-/g, "/");
	 endTime = endTime.replace(/\-/g, "/");
	 
	 //将计算间隔类性字符转换为小写
	 diffType = diffType.toLowerCase();
	 var sTime = new Date(startTime);      //开始时间
	 var eTime = new Date(endTime);  //结束时间
	 //作为除数的数字
	 var divNum = 1;
	 switch (diffType) {
		 case "second":
		 divNum = 1000;
		 break;
		 case "minute":
		 divNum = 1000 * 60;
		 break;
		 case "hour":
		 divNum = 1000 * 3600;
		 break;
		 case "day":
		 divNum = 1000 * 3600 * 24;
		 break;
		 case "year":
		 divNum = 1000 * 3600 * 24 *365;
		 break;
		 default:
		 break;
	}
	return parseInt((eTime.getTime() - sTime.getTime()) / parseInt(divNum));
}
 //判断是否在数组中
  function is_arr(str,arr){
    var len = arr.length-1;
    while(len>=0){
      if(str === arr[len]){
      return true;
    }
      len--;

    }
    return false;
  }
 function get_employee(){
     var deptid = $('#dept').val();
    //获取当前部门的人员
    $.post('../ajax_function/get_dept_employee.php',{deptid:deptid},function(data){
      var select_num = $('.select_employee').size();
      var array_select = new Array();
      for(var j=0;j<select_num;j++){
        var employeeid = $('.select_employee').eq(j).attr('employeeid');
        array_select.push(employeeid);
      }
      $('#employee').empty();
      for(var i=0;i<data.length;i++){
        if(!is_arr(data[i].employeeid,array_select)){
          var span = '<span class="employee" id="employee_'+data[i].employeeid+'" style="padding:5px;cursor:pointer;color:blue">'+data[i].employee_name+'<span>';
          $('#employee').append(span);
       }
      }
    },'json')
  }
  //获取当前页面跳转时滚动到了什么位置
  function gettop(){
  	$('a').live('click',function(){
    var href = $(this).attr('href');
    var top  = window.scrollY;
    if(href.indexOf('?') != -1){
      href += '&top='+top;
    } else{
      href += '?top='+top;
    }
    $(this).attr('href',href);
  })
  }
  //获取地址栏中页面的位置，并进行设置
  function settop(){
	  var string = window.location.search;
	  if(string){
	    var str = string.substr(1);
	    str = str.split('&');
	    for(k in str){
	      if(str[k].indexOf('top') != -1){
	        var top_val = str[k].substr(str[k].indexOf('=')+1);
	      }
	    }
	  }
	  top_val = parseInt(top_val);
	  window.scrollTo(0,top_val);
  }
    //搜索时如果不输入日期则日期清空
    $('#search tr td *:not(input[name $= date],input[name=submit])').live('focus',function(){
          var inp = $(this).val();
          $('input[name=sdate]').val('');
      })
    //获取当前时间
function getDate() {

    var timezone = 8;
    var offset_GMT = new Date().getTimezoneOffset();
    var nowDate = new Date().getTime();

    var today = new Date(nowDate + offset_GMT * 60 * 1000 + timezone * 60 * 60 * 1000);
    var date = today.getFullYear() + "-" + twoDigits(today.getMonth() + 1) + "-" + twoDigits(today.getDate());
    var time = twoDigits(today.getHours()) + ":" + twoDigits(today.getMinutes()) + ":" + twoDigits(today.getSeconds());
    return  date + ' ' + time;
}
function twoDigits(val) {
    if (val < 10) return "0" + val;
    return val;
}