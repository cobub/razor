<section id="main" class="column">
		<?php if(isset($message)):?>
		<h4 class="alert_success"><?php echo $message;?></h4>
		<?php else:?>
			
		<?php endif;?>
		<?php $c_year = date('Y');
		      $c_month = date('m');
		      $c_day = date('d');
		      $c_timestamp = mktime(0,0,0,$c_month,$c_day,$c_year);
		      $c_weeknumber = date('W',$c_timestamp);
		?>
		<?php 
		if (!isset($timetype))
		{
			$timetype= '1month';
		}
		
		?>
	
		<article class="module width_full">
		<header>
			<h3><?php echo  lang('userremainview_headerinfo')?></h3>
			
			<div class="select_option fr" style="position:absolute; right:170px;margin-top:3px" >
    <div class="select_arrow fr"></div>
    <div id="selected_value" style="font-size:12px;" class="selected_value fr"><?php echo  lang('allview_choosetime')?></div>
    <div class="clear"></div>
    <div id="select_list_body" style="display: none;" class="select_list_body">
         <ul>
           <li><a class="" id="" href="javascript:timePhaseChanged('7day','<?php echo  lang('allview_lastweek')?>')"> <?php echo  lang('allview_lastweek')?></a>
           </li>
           <li><a class="" id="" href="javascript:timePhaseChanged('1month','<?php echo  lang('allview_lastmonth')?>');"> <?php echo  lang('allview_lastmonth')?></a>
           </li>
           <li><a class="" href="javascript:timePhaseChanged('3month','<?php echo  lang('allview_last3month')?>');"> <?php echo  lang('allview_last3month')?></a>
           </li>
           <li><a class="" href="javascript:timePhaseChanged('all','<?php echo  lang('allview_alltime')?>');">  <?php echo  lang('allview_alltime')?></a>
           </li>
           <li class="date_picker noClick">
             <a style=""><?php echo  lang('allview_anytime')?></a>
           </li>
           <li style="padding:0;display:none;" class="date_picker_box noClick">
	           	<div style="width:100%;padding-left:20px;" class="selbox">
	               <span><?php echo  lang('allview_datefrom')?></span>
	              <input type="text" name="dpFrom" id="dpFrom" value="" class="datainp first_date date"><br>
	               <span><?php echo  lang('allview_dateto')?></span>
	              <input type="text" name="dpTo"  id="dpTo" value="" class="datainp last_date date">
	            </div>
              	  <div class="" style="">
              	  	<input id="any" type="button" onclick="onBtn()" value="&nbsp;<?php echo  lang('allview_timebtn')?>&nbsp;" class="any" style="margin: 5px 60px 0 50px;">
              	  </div>
           </li>
         </ul>
    </div>    
  </div> 
			
			
			<ul class="tabs" style="position:absolute; right:62px; padding:0;">
			    
				
				<li><a id="week" href="#tab1"  onclick="chooseType('week')"><?php echo  lang('userremainview_weektab')?></a></li>
				<li><a id="month" href="#tab2" onclick="chooseType('month')"><?php echo  lang('userremainview_monthtab')?></a></li>
			</ul>
			<span class="relative r">
			<a href="#this" class="bottun4" onclick="sever('server','server1c');"><font>?</font></a>
                	<div class="server333" id="server" style="width:620px;">
                       <div class="ser_title">
                           <b class="l"><?php echo  lang('userremainview_settitle')?></b>                          
                           <a class="r" href="#this" id="server1c"><img src="<?php echo base_url(); ?>assets/images/server_close.gif" /></a>
                       </div>
                       
                                <style>
								.ser_txt font{
									width:70px
								}
								</style>
                       <div class="ser_txt">
                           <dl>
                               <dt><?php echo  lang('userremainview_helptitle')?>
                               </dt>
                           </dl>
                       </div>
                	</div>
                </span>	
			
		</header>
		
		<div class="tab_container" id="contents">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th><?php echo  lang('userremainview_firstweek')?></th> 
    				<th><?php echo  lang('userremainview_usernum')?></th> 
    				<th><?php echo  lang('userremainview_oneweek')?></th> 
    				<th><?php echo  lang('userremainview_twoweek')?></th> 
    				<th><?php echo  lang('userremainview_threeweek')?></th> 
    				<th><?php echo  lang('userremainview_fourweek')?></th> 
    				<th><?php echo  lang('userremainview_fiveweek')?></th> 
    				<th><?php echo  lang('userremainview_sixweek')?></th> 
    				<th><?php echo  lang('userremainview_sevenweek')?></th> 
    				<th><?php echo  lang('userremainview_eightweek')?></th> 
    				
				</tr> 
			</thead> 
			<tbody id='weekdata'> 
			<?php 
			if (isset($userremain))
			{
			foreach ($userremain->result() as $row)
			{?>
			
			<?php 
				    $time_w = date($row->startdate);
				    $time_c = date('Y-m-d');
				    $week_dis = floor((strtotime($time_c)-strtotime($time_w))/(60*60*24*7));
//				    echo $nun;
//				    $timearray = explode('-', $row->enddate);
//				    $year =$timearray[0];
//				    $month = $timearray[1];
//				    $day = $timearray[2];
//				    
//				    $timestamp = mktime(0,0,0,$month,$day,$year);
//                    $weeknumber = date('W',$timestamp);
                    
//                    $week_dis=  $c_weeknumber- $weeknumber;
                    
				    if ($week_dis>1)
				    {
				    ?>
				<tr> 
				
				    <td><?php 
				    if ($week_dis>1)
				    echo $row->startdate." ~ ".$row->enddate?></td>
				    
				    <td><?php
				    if ($week_dis>1)
				     echo $row->usercount?></td>
				    <td><?php
				    if ($week_dis>2)
				     echo $row->week1?></td>
				    <td><?php
				    if ($week_dis>3)
				    echo $row->week2?></td>
				    <td><?php 
				    if ($week_dis>4)
				    echo $row->week3?></td>
				    <td><?php
				    if ($week_dis>5)
				     echo $row->week4?></td>
   					<td><?php 
   					if ($week_dis>6)
   					echo $row->week5?></td> 
    				<td><?php 
    				if ($week_dis>7)
    				echo $row->week6?></td> 
    				<td><?php 
    				if ($week_dis>8)
    				echo $row->week7?></td> 
    				<td><?php 
    				if ($week_dis>9)
    				echo $row->week8?></td> 
    				
				</tr> 
			<?php }}}?>
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
			<div id="tab2" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th><?php echo  lang('userremainview_monthfirstusethead')?></th> 
    				<th><?php echo  lang('userremainview_monthusernumthead')?></th> 
    				<th><?php echo  lang('userremainview_onemonththead')?></th> 
    				<th><?php echo  lang('userremainview_twomonththead')?></th> 
    				<th><?php echo  lang('userremainview_threemonththead')?></th> 
    				<th><?php echo  lang('userremainview_fourmonththead')?></th> 
    				<th><?php echo  lang('userremainview_fivemonththead')?></th> 
    				<th><?php echo  lang('userremainview_sixmonththead')?></th> 
    				<th><?php echo  lang('userremainview_sevenmonththead')?></th> 
    				<th><?php echo  lang('userremainview_eightmonththead')?></th> 
    				
				</tr> 
			</thead> 
			<tbody id='monthdata'> 
			<?php 
			if (isset($userremain_m))
			{
			foreach ($userremain_m->result() as $row)
			{?>
			
			<?php 				    
				    $timearray = explode('-', $row->enddate);
				    $year = $timearray[0];
				    
				    $month = $timearray[1];
				    
                    $month_dis=  (int)$c_month- (int)$month+((int)$c_year-(int)$year)*12;
				    if ($month_dis>1)
				    {
				    ?>
				<tr> 
				
				    <td><?php 
				    if ($month_dis>1)
				    echo $row->startdate." ~ ".$row->enddate?></td>
				    
				    <td><?php
				    if ($month_dis>0)
				     echo $row->usercount?></td>
				    <td><?php
				    if ($month_dis>1)
				     echo $row->month1?></td>
				    <td><?php
				    if ($month_dis>2)
				    echo $row->month2?></td>
				    <td><?php 
				    if ($month_dis>3)
				    echo $row->month3?></td>
				    <td><?php
				    if ($month_dis>4)
				     echo $row->month4?></td>
   					<td><?php 
   					if ($month_dis>5)
   					echo $row->month5?></td> 
    				<td><?php 
    				if ($month_dis>6)
    				echo $row->month6?></td> 
    				<td><?php 
    				if ($month_dis>7)
    				echo $row->month7?></td> 
    				<td><?php 
    				if ($month_dis>8)
    				echo $row->month8?></td> 
    				
				</tr> 
			<?php }}}?>
			</tbody> 
			</table>
			</table>

			</div><!-- end of #tab2 -->
			
		</div><!-- end of .tab_container -->
		
		<div class="clear"></div>
		<footer></footer>

	</article>
	

<!--  	<article class="module width_full">-->
<!--		<header>-->
<!--			<h3 class="tabs_involved">用户流失趋势</h3>-->
<!--			-->
<!--		    <ul class="tabs2">-->
<!--			    -->
<!--				<li><a id="day" href="javascript:changeChartName('chartNewUser')">分析某日</a></li>-->
<!--				<li><a id="week" href="javascript:changeChartName('chartTotalUser')">分析某周</a></li>-->
<!--			</ul>-->
<!--			-->
<!--		</header>-->
<!--		-->
<!--		<div class="module_content">-->
<!--         <div id="container"  class="module_content" style="height:400px">-->
<!--		</div>-->
<!--</div>-->
<!--		<footer>-->
<!--		<div id="pagination"  class="submit_link">-->
<!--		</div>-->
<!--		</footer>-->
<!--	</article>-->
	
	<div class="clear"></div>
		<div class="spacer"></div>
</section>
<script type="text/javascript">
 var  requesttype;
function chooseType(a_type)
{
	requesttype =a_type; 
   // alert(requesttype);
    }
function xytt(txt, bg, colse) {
	var txt = txt;
	var bg = bg;
	$("#" + txt + " input").val("");
	var sHeight = document.body.clientHeight;
	var dheight = document.documentElement.clientHeight;
	var srctop = document.documentElement.scrollTop;
	if($.browser.safari) {
		srctop = window.pageYOffset;
	}
	$(".xy").css({
		"height" : dheight
	});
	dheight = (dheight - $("#" + txt).height()) / 2;
	$("#" + txt).show();
	$("#" + bg).show();
	$("#" + txt).css({
		"top" : (srctop + dheight) + "px"
	});
	$("#" + bg).css({
		"top" : (srctop ) + "px"
	});
	window.onscroll = function scall() {
		var srctop = document.documentElement.scrollTop;
		if($.browser.safari) {
			srctop = window.pageYOffset;
		}
		$("#" + txt).css({
			"top" : (srctop + dheight) + "px"
		});
		$("#" + bg).css({
			"top" : (srctop) + "px"
		});
		$("#fkicon").css({
			top : srctop + (innerHeights / 2)
		});
		window.onscroll = scall;
		window.onresize = scall;
		window.onload = scall;
	}
	$("." + colse).click(function() {
		$("#" + txt).hide();
		$("#" + bg).hide();
	})
}

function sever(txt, colse) {
	$("#" + txt).slideToggle("slow");
	$("#" + colse).click(function() {
		$("#" + txt).slideUp("slow");
	});
	
}

</script>

<script type="text/javascript">
var chart;
var options;
$(document).ready(function() {
	options = {
            chart: {
                renderTo: 'container',
                type: 'spline'
            },
            title: {
                text: '   '
            },
            subtitle: {
                text: ' '
            },
            xAxis: {
                labels:{rotation:300,y:40,x:0}
            },
            yAxis: {
                title: {
                    text: ''
                },
                min:0,
                labels: {
                    formatter: function() {
                        return Highcharts.numberFormat(this.value, 0);
                    }
                }
            },
            tooltip: {
                crosshairs: true,
                shared: true
            },
            plotOptions: {
                spline: {
                    marker: {
                        radius: 1,
                        lineColor: '#666666',
                        lineWidth: 1
                    }
                }
            },
            legend:{
                labelFormatter: function() {
                	return this.name
                }
             },
            series: [
        
            ]
        };
//	renderChart('week');
	
});

</script>

<script type="text/javascript">

function renderChart(type)
{
	var data = <?php echo $userremain_json;?>;
	var chart_canvas = $('#container');

	var newUsers = [];
	var categories = [];
    for(var i=0;i<data.length;i++)
    {
	    var marketData = data[i];
    	newUsers.push(parseFloat(marketData.usercount));
    	categories.push(marketData.startdate+"~"+marketData.enddate);
	}
	
	    options.series[0] = {
            name:'www'
        };
//        if(datetype=="eventnum")
//	        options.title.text = '事件消息数量';
//        if(datetype=="eventnum")
//		    options.title.text = '事件数量/活跃用户';
	    options.series[0].data = newUsers;
	    options.xAxis.labels.step = parseInt(categories.length/10);
	    options.xAxis.categories = categories; 
        
    chart = new Highcharts.Chart(options);

	}
     
    function renderCharts(myurl)
    {
    	 var chart_canvas = $('#tab1');
    	    var loading_img = $("<img src='<?php echo base_url();?>/assets/images/loader.gif'/>");
    		    
    	    chart_canvas.block({
    	        message: loading_img,
    	        css:{
    	            width:'32px',
    	            border:'none',
    	            background: 'none'
    	        },
    	        overlayCSS:{
    	            backgroundColor: '#FFF',
    	            opacity: 0.8
    	        },
    	        baseZ:997
    	    });
    	  //  alert(myurl);
    	jQuery.getJSON(myurl, null, function(data) {
        	//alert(data.datas.length);
        	var str ='';
			if(data.type=='month'){
				for(var i=0;i<data.datas.length;i++){

						str=str+"<tr><td>"+data.datas[i].startdate+"~"+data.datas[i].enddate+"</td><td>"+
						data.datas[i].usercount+"</td><td>"
						+data.datas[i].month1+"</td><td>"
						+data.datas[i].month2+"</td><td>"
						+data.datas[i].month3+"</td><td>"
						+data.datas[i].month4+"</td><td>"
						+data.datas[i].month5+"</td><td>"
						+data.datas[i].month6+"</td><td>"
						+data.datas[i].month7+"</td><td>"
						+data.datas[i].month8+"</td></tr>";
					}


				document.getElementById('monthdata').innerHTML=str;
				}
			if(data.type=='week'){
				for(var i=0;i<data.datas.length;i++){

					str=str+"<tr><td>"+data.datas[i].startdate+"~"+data.datas[i].enddate+"</td><td>"+
					data.datas[i].usercount+"</td><td>"
					+data.datas[i].week1+"</td><td>"
					+data.datas[i].week2+"</td><td>"
					+data.datas[i].week3+"</td><td>"
					+data.datas[i].week4+"</td><td>"
					+data.datas[i].week5+"</td><td>"
					+data.datas[i].week6+"</td><td>"
					+data.datas[i].week7+"</td><td>"
					+data.datas[i].week8+"</td></tr>";
				}

				
				document.getElementById('weekdata').innerHTML=str;
				}
        	
    		chart_canvas.unblock();
    		});  

		
    }
  	    
</script>
<script type="text/javascript">  
var timePhase = '1month';
var fromTime;
var toTime;
var myurlgetjson='';
$(document).ready(function(){  
	initTimeSelect();
	var time_type = '<?php echo $timetype?>';
	
	var temp;
	if(time_type == '7day')
		temp = "<?php echo lang('allview_lastweek') ?>";
	if(time_type == '1month')
		temp = "<?php echo lang('allview_lastmonth') ?>";
	if(time_type == '3month')
		temp = "<?php echo lang('allview_last3month') ?>";
	if(time_type == 'all')
		temp = "<?php echo lang('allview_alltime') ?>";
	if(time_type == 'any')
		temp = "<?php echo lang('allview_anytime') ?>";
	
	document.getElementById('selected_value').innerHTML = temp;
});  

function timePhaseChanged(value,text)
{   
	// 显示 加载图标
	var chart_canvas = $("#contents");
    var loading_img = $("<img src='<?php echo base_url();?>/assets/images/loader.gif'/>");

    chart_canvas.block({
        message: loading_img
        ,
        css:{
            width:'32px',
            border:'none',
            background: 'none'
        },
        overlayCSS:{
            backgroundColor: '#FFF',
            opacity: 0.8
        },
        baseZ:997
    });

	
    if(value=='any')
    {
    	timePhase='any';
    }	
    else
    {
    	timePhase=value; 
    	//window.location="<?php echo site_url()?>/report/userremain/getData/"+timePhase;
    	myurlgetjson="<?php echo site_url()?>/report/userremain/getData/"+timePhase;
    }
    document.getElementById('selected_value').innerHTML = text;
    if(requesttype=='month'){
		myurlgetjson="<?php echo site_url()?>/report/userremain/getData/"+timePhase+"/month";
        }else{
        	myurlgetjson="<?php echo site_url()?>/report/userremain/getData/"+timePhase+"/week";
            }
    renderCharts(myurlgetjson);
    chart_canvas.unblock();
}

function onBtn()
{  
	timePhase='any';
	fromTime = document.getElementById('dpFrom').value;
	toTime = document.getElementById('dpTo').value;
	document.getElementById('selected_value').innerHTML = "<?php echo  lang('userremainview_jsmsginfo')?>";
   // window.location="<?php echo site_url()?>/report/userremain/getData/"+timePhase+"/"+fromTime+"/"+toTime;
	myurlgetjson="<?php echo site_url()?>/report/userremain/getData/"+timePhase+"/"+fromTime+"/"+toTime;
	 renderCharts(myurlgetjson);
}

$(function() {
	$("#dpFrom" ).datepicker({dateFormat: "yy-mm-dd","setDate":new Date()});
});

$(function() {
	$( "#dpTo" ).datepicker({ dateFormat: "yy-mm-dd" ,"setDate":new Date()});
});

</script>
