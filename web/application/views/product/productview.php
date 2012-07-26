<section id="main" class="column">
		<?php if(isset($message)):?>
		<h4 class="alert_success"><?php echo $message;?></h4>
		<?php else:?>
			<h4 id='msg' class="alert_success"><?php echo lang('productview_alertinfo') ?></h4>
		<?php endif;?>
		
		<article class="module width_full">
			<header><h3><?php echo lang('productview_headertitle') ?></h3>
			<span class="relative r">
                	<a href="#this" class="bottun4" onclick="sever('server1','server1c1');"><font>?</font></a>
                	<div class="server333" id="server1" style="width:620px;">
                       <div class="ser_title">
                           <b class="l"><?php echo lang('productview_helptitle') ?></b>                          
                           <a class="r" href="#this" id="server1c1"><img src="<?php echo base_url(); ?>assets/images/server_close.gif" /></a>
                       </div>
                       
                                <style>
								.ser_txt font{
									width:95px
								}
								</style>
                       <div class="ser_txt">
                           <dl>
                               <dt>
                               	<font><?php echo lang('productview_helpnewuser') ?></font>
                                <small><?php echo lang('productview_helpnewinstall') ?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo lang('productview_helpactuser') ?></font>
                                <small><?php echo lang('productview_helpleastone') ?></small>
                                <div class="clear"></div>
                               </dd>
                               <dt>
                               	<font><?php echo lang('productview_helpnewuserper') ?></font>
                                <small><?php echo lang('productview_helpnewactper') ?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo lang('productview_helpstartnum') ?></font>
                                <small><?php echo lang('productview_helpstartednum') ?></small>
                                <div class="clear"></div>
                               </dd>
                               <dt>
                               	<font><?php echo lang('productview_helpupgradeuser') ?></font>
                                <small><?php echo lang('productview_helpappupgrade') ?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo lang('productview_helpavgtime') ?></font>
                               	<small><?php echo lang('productview_helponetime') ?></small>
                                <div class="clear"></div>
                               </dd>
                               
                           </dl>
                       </div>
                	</div>
                </span>			
			</header>
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th></th> 
    				<th><?php echo lang('productview_protheadstartnum') ?></th> 
    				<th><?php echo lang('productview_protheadactuser') ?></th> 
    				<th><?php echo lang('productview_protheadnewuser') ?></th> 
    				<th><?php echo lang('productview_protheadnewper') ?></th>
    				<th><?php echo lang('productview_protheadupgradeuser') ?></th>
    				<th><?php echo lang('productview_protheadavgtime') ?></th>
				</tr> 
			</thead> 
			<tbody> 
			<?php if(isset($today1)):?>
			<tr> 
    				<td><?php echo lang('productview_protbodytoday') ?></td> 
                    <td><?php echo $today1->sessions;?></td> 
    				<td><?php echo $today1->startusers;?></td> 
    				<td><?php echo $today1->newusers;?></td> 
    				<td><?php echo percent($today1->newusers,$today1->startusers)?></td>
    				<td><?php echo $today1->upgradeusers;?></td>
    				<td><?php echo round($today1->usingtime/1000,2);?></td>
    				
				</tr> 
			<?php endif;?>
			
			<?php if(isset($yestoday)):?>
			<tr> 
    				<td><?php echo lang('productview_protbodyyesterday') ?></td> 
    				 <td><?php echo $yestoday->sessions;?></td> 
    				<td><?php echo $yestoday->startusers;?></td> 
    				<td><?php echo $yestoday->newusers;?></td> 
    				<td><?php echo percent($yestoday->newusers,$yestoday->startusers)?></td>
    				<td><?php echo $yestoday->upgradeusers;?></td>
    				<td><?php echo round($yestoday->usingtime/1000,2);?></td>
				</tr> 
			<?php endif;?>
			</tbody>			
			</table>
		</article>
		
			<article class="module width_full">
			<header><h3><?php echo lang('productview_summarytitle') ?></h3>
			<span class="relative r">
			<a href="#this" class="bottun4" onclick="sever('server2','server1c2');"><font>?</font></a>
                	<div class="server333" id="server2" style="width:620px;">
                       <div class="ser_title">
                           <b class="l"><?php echo lang('productview_summhelptitle') ?></b>                          
                           <a class="r" href="#this" id="server1c2"><img src="<?php echo base_url(); ?>assets/images/server_close.gif" /></a>
                       </div>
                       
                                <style>
								.ser_txt font{
									width:95px
								}
								</style>
                       <div class="ser_txt">
                           <dl>
                               <dt>
							    <font><?php echo lang('productview_summhelptotal') ?></font>
							    <small><?php echo lang('productview_summhelpstart') ?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo lang('productview_summhelptotalstart') ?></font>
                               	<small><?php echo lang('productview_summhelpsumstart') ?></small>
                                <div class="clear"></div>
                               </dd>                                                      
                               <dt>
                               	<font><?php echo lang('productview_summhelpweekact') ?></font>
                               	<small><?php echo lang('productview_summhelpweekactuser') ?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo lang('productview_summhelpmonth') ?></font>
                               	<small><?php echo lang('productview_summhelpstartmonth') ?></small>
                                <div class="clear"></div>
                               </dd>
                               <dt>
                               	<font><?php echo lang('productview_summhelpweekrate') ?></font>
                               	<small><?php echo lang('productview_summhelpacttotal') ?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               <font><?php echo lang('productview_summhelpmonthrate') ?></font>
                               	<small><?php echo lang('productview_summhelpactiveto') ?></small>
                                <div class="clear"></div>
                               </dd>

                           </dl>
                       </div>
                	</div>
                </span>	
			</header>
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo lang('productview_summtheadtotal') ?></th> 
    				<th><?php echo lang('productview_summtheadtstart') ?></th> 
    				<th><?php echo lang('productview_summtheadwact') ?></th> 
    				<th><?php echo lang('productview_summtheadwrate') ?></th>
    				<th><?php echo lang('productview_summtheadmact') ?></th>
    				<th><?php echo lang('productview_summtheadmrate') ?></th>
				</tr> 
			</thead> 
			<tbody> 
			<?php if(isset($overall)):?>
			<tr> 
    				<td><?php echo $today1->allusers;?></td> 
    				<td><?php echo $today1->allsessions;?></td> 
    				<td><?php echo $overall['7dayactive'];?></td> 
    				<td><?php echo percent($overall['7dayactive'],$today1->allusers)?></td>
    				<td><?php echo $overall['1month'];?></td>
    				<td><?php echo percent($overall['1month'],$today1->allusers)?></td>
				</tr> 
			<?php endif;?>
			</tbody>			
			</table>
		</article>
		
		<article class="module width_full">
			<header><h3 class="tabs_involved"><?php echo lang('productview_trendtitle') ?></h3>                                                                                                                                      
			<div class="submit_link">
			<select onchange="switchTimePhase(this.options[this.selectedIndex].value)" id='startselect'>
				<option value=today selected ><?php echo  lang('allview_today')?></option>
				<option value=yestoday><?php echo  lang('allview_yesterday')?></option>
				<option value=last7days><?php echo  lang('allview_7days')?></option>
				<option value=last30days><?php echo  lang('allview_30days')?></option>
				<option value=any><?php echo  lang('allview_anytime')?></option>			
			</select>
			<div id='selectcurTime'><input type="text"
				id="dpTimeFrom"> <input type="text" id="dpTimeTo"> <input type="submit"
				id='timebtn' value="<?php echo  lang('allview_timebtn')?>" class="alt_btn" onclick="onAnyTimeClicked()"></div>
			</div>				
			</div>   			
			</header>
			<div class="tab_container">
				<div id="tab1" class="tab_content">
					<div class="module_content">
						<article class="width_full">
						<div id="container"  class="module_content" style="height:300px">
		
		</div>
						</article>
						</div>
						<div class="clear"></div>
					</div>
				</div>
					<footer>
			<ul class="tabs2">
				<li><a ct="newUser" href="javascript:changefirstchartName('startuser')"><?php echo  lang('productview_jsstartinfo')?></a></li>
				<li><a ct="totalUser" href="javascript:changefirstchartName('newuser')"><?php echo  lang('productview_jsnewinfo')?></a></li>				
			</ul>
		</footer>
		</article>
	
	   <article class="module width_full">     
		<header>
			<h3><?php echo  lang('userbasicanalyze_headeinfo')?></h3>
			<div class="submit_link">
			<select onchange="selectChange(this.options[this.selectedIndex].value)" id='select'>
				<option value=过去一周 selected ><?php echo  lang('allview_lastweek')?></option>
				<option value=过去一个月><?php echo  lang('allview_lastmonth')?></option>
				<option value=过去三个月><?php echo  lang('allview_last3month')?></option>
				<option value=全部><?php echo  lang('allview_alltime')?></option>
				<option value=任意时间段><?php echo  lang('allview_anytime')?></option>
			</select>
			<div id='selectTime'><input type="text"
				id="dpFrom"> <input type="text" id="dpTo"> <input type="submit"
				id='btn' value="<?php echo  lang('allview_timebtn')?>" class="alt_btn" onclick="onAnyTimeButtonClicked()"></div>
			</div>
		</header>
		<article class="width_full">
		<div id="usercontainer"  class="module_content" style="height:300px">
		</div>
		</article>
		<div class="clear"></div>
		<footer>
			<ul class="tabs3">
				<li><a ct="newUser" href="javascript:changeChartName('chartNewUser')"><?php echo  lang('userbasicanalyze_newuser')?></a></li>
				<li><a ct="totalUser" href="javascript:changeChartName('chartTotalUser')"><?php echo  lang('userbasicanalyze_totaluser')?></a></li>
				<li><a ct="activeUser" href="javascript:changeChartName('chartActiveUser')"><?php echo  lang('userbasicanalyze_activeuser')?></a></li>
				<li><a ct="startUser" href="javascript:changeChartName('chartStartUser')"><?php echo  lang('userbasicanalyze_startnum')?></a></li>
				<li><a ct="averageUsingTime" href="javascript:changeChartName('chartAverageUsingTime')"><?php echo  lang('userbasicanalyze_avgtime')?></a></li>
			</ul>
		</footer>
	</article>
	<div class="clear"></div>
	<div class="spacer"></div>

	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('userbasicanalyze_detaildata')?></h3>
			<span class="relative r">                	
                	<a href="<?php echo site_url(); ?>/report/productbasic/exportdetaildata" class="bottun4 hover" ><font><?php echo  lang('userbasicanalyze_exportinfo')?></font>
                	<a href="#this" class="bottun4" onclick="sever('server3','server3c3');"><font>?</font></a>
                	<div class="server333" id="server3" style="width:620px;">
                       <div class="ser_title">
                           <b class="l"><?php echo  lang('userbasicanalyze_settitle')?></b>                          
                           <a class="r" href="#this" id="server3c3"><img src="<?php echo base_url(); ?>assets/images/server_close.gif" /></a>
                       </div>
                       
                                <style>
								.ser_txt font{
									width:95px
								}
								</style>
                       <div class="ser_txt">
                           <dl>
                               <dt>
                               	<font><?php echo  lang('userbasicanalyze_remindnew')?></font>
                                <small><?php echo  lang('userbasicanalyze_remindnewinstall')?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo  lang('userbasicanalyze_remindtotal')?></font>
                                <small><?php echo  lang('userbasicanalyze_remindrappstart')?></small>
                                <div class="clear"></div>
                               </dd>
                               <dt>
                               	<font><?php echo  lang('userbasicanalyze_remindactive')?></font>
                                <small><?php echo  lang('userbasicanalyze_remindleastone')?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo  lang('userbasicanalyze_remindstartnum')?></font>
                                <small><?php echo  lang('userbasicanalyze_remindappstartnum')?></small>
                                <div class="clear"></div>
                               </dd>
                               <dt>
                               	<font><?php echo  lang('userbasicanalyze_remindavgtime')?></font>
                               	<small><?php echo  lang('userbasicanalyze_reminduseravgtime')?></small>
                                <div class="clear"></div>
                               </dt>                              
                           </dl>
                       </div>
                	</div>
                </span>		
		</header>
		<div style="height:500px">
		<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo  lang('userbasicanalyze_theaddate')?></th> 
    				<th><?php echo  lang('userbasicanalyze_theadnew')?></th> 
    				<th><?php echo  lang('userbasicanalyze_theadtotal')?></th> 
    				<th><?php echo  lang('userbasicanalyze_theadactive')?></th> 
    				<th><?php echo  lang('userbasicanalyze_theadstart')?></th>
    				<th><?php echo  lang('userbasicanalyze_theadavgtime')?></th>
				</tr> 
			</thead> 
			<tbody id="content">
		
			</tbody>
		</table> 
		</div>
		<footer>
		<div id="pagination"  class="submit_link">
		</div>
		</footer>
	</article>	
		<div class="clear"></div>
		<div class="spacer"></div>
	
		
</section>
	
<script>
var chartDetailName = 'chartNewUser';
var timePhase = '7day';
var fromTime;
var toTime;
var fromCurTime;
var toCurTime;
var username;
var chartname = 'startuser';
var timephase = 'today';
var name;
var titledetailName='';
var chartdetailData;
//When page loads...
dispalyOrHideTimeSelect();
dispalyOrHideCurTimeSelect();
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

$("ul.tabs3 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content
$(document).ready(function() {
	getfirstchartdata();
	getChartData();
	
});
function dispalyOrHideCurTimeSelect()
{
	 var value = document.getElementById('startselect').value;
	 if(value=='any')
	 {
		 document.getElementById('selectcurTime').style.display="inline";
	 }
	 else
	 { 
		 document.getElementById('selectcurTime').style.display="none";
	 }
} 
function dispalyOrHideTimeSelect()
{
	 var value = document.getElementById('select').value;
	 if(value=='任意时间段')
	 {
		 document.getElementById('selectTime').style.display="inline";
	 }
	 else
	 { 
		 document.getElementById('selectTime').style.display="none";
	 }
} 
//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});

//On Click Event
$("ul.tabs3 li").click(function() {
	$("ul.tabs3 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
</script>
<script type="text/javascript">
function onAnyTimeClicked(){
	fromCurTime = document.getElementById('dpTimeFrom').value;
	toCurTime = document.getElementById('dpTimeTo').value;
	getfirstchartdata();
}
function  onAnyTimeButtonClicked()
{  
	fromTime = document.getElementById('dpFrom').value;
	toTime = document.getElementById('dpTo').value;
	getChartData();
}                            
</script>

<script type="text/javascript">
	$(function() {
		$("#dpFrom" ).datepicker();
	});
	$( "#dpFrom" ).datepicker({ dateFormat: "yy-mm-dd" });
	$(function() {
		$( "#dpTo" ).datepicker();
	});
	$( "#dpTo" ).datepicker({ dateFormat: "yy-mm-dd" });
	$(function() {
		$( "#dpTimeFrom" ).datepicker();
	});
	$( "#dpTimeFrom" ).datepicker({ dateFormat: "yy-mm-dd" });
	$(function() {
		$( "#dpTimeTo" ).datepicker();
	});
	$( "#dpTimeTo" ).datepicker({ dateFormat: "yy-mm-dd" });
</script>
<script type="text/javascript">
function changefirstchartName(changename)
{	
	changeChartTitleName(timephase,changename);
	chartname = changename;
	var data = chartdata;
	var categories = [];
	var newUsers = [];
	var obj = data.content;
    for(var i=0;i<obj.length;i++)
    {
	    if(chartname=="startuser")
		    newUsers.push(parseInt(obj[i].startusers,10));
	    if(chartname=="newuser")
	    	newUsers.push(parseInt(obj[i].newusers,10));
    	categories.push(obj[i].hour);
    }
    
	options.series[0].data = newUsers;
	options.xAxis.labels.step = parseInt(categories.length/10);
	options.xAxis.categories = categories;  
	options.title.text = titlename;
	                                         
	chart = new Highcharts.Chart(options);          
	//getfirstchartdata(); 
}
function switchTimePhase(time)
{
	dispalyOrHideCurTimeSelect();
	timephase=time;
	if(time!="any"){
		getfirstchartdata();
		}
	
}
function getfirstchartdata()
{
	changeChartTitleName(timephase,chartname);
	var myurl="";
	if(timephase=='any')
	{		
		myurl="<?php echo site_url()?>/report/productbasic/getTypeAnalyzeData/"+timephase+"/"+fromCurTime+"/"+toCurTime;
	}
	else
	{
		myurl = "<?php echo site_url()?>/report/productbasic/getTypeAnalyzeData/"+timephase;
	}
	renderCharts(myurl);	
}

function changeChartTitleName(timephase,chartname){
	if (timephase == "today") {
		if(chartname=="startuser"){
			titlename = "<?php echo lang('probasictitle_todaystart') ?>";
		}
		if(chartname=="newuser"){
			titlename ="<?php echo lang('probasictitlee_todaynew')?>" ;
			
		}
	}
	if (timephase == "yestoday") {
		if(chartname=="startuser"){
			titlename ="<?php echo lang('probasictitle_yesterdaystart') ?>";
		}
		else
			titlename ="<?php echo lang('probasictitlee_yesterdaynew') ?>";
	}	
	if (timephase == "last7days") {
		if(chartname=="startuser"){
			titlename ="<?php echo lang('probasictitle_7daysstart')?>";
		}
		else
			titlename ="<?php echo lang('probasictitlee_7daysnew') ?>";
	}
	if (timephase == "last30days") {
		if(chartname=="startuser"){
			titlename ="<?php echo  lang('probasictitle_30daysstart') ?>";
		}
		else
			titlename ="<?php echo lang('probasictitlee_30daysnew') ?>";
	}  
	if (timephase == "any") {
		if(chartname=="startuser"){
			titlename ="<?php echo  lang('probasictitle_anystart') ?>";
		}
		else
			titlename ="<?php echo lang('probasictitlee_anynew') ?>";
	}  
}

function changeChartName(name)
{
	changeDetailChartTitlename(timePhase,name);
	chartDetailName = name;
	var data=chartdetailData; 
	var categories = [];
	var newUsers = [];
	var obj = data.content; 
    for(var i=0;i<obj.length;i++)
    {
	    if(chartDetailName=="chartNewUser")
		    newUsers.push(parseInt(obj[i].newusers,10));
	    if(chartDetailName=="chartTotalUser"){
		    if(obj[i].allusers==null){}
		    else
			    newUsers.push(parseInt(obj[i].allusers,10));
		    }
	    if(chartDetailName=="chartActiveUser")
		    newUsers.push(parseInt(obj[i].startusers,10));
	    if(chartDetailName=="chartStartUser")
		    newUsers.push(parseInt(obj[i].sessions,10));
	    if(chartDetailName=="chartAverageUsingTime")
		    newUsers.push(parseInt(obj[i].usingtime,10));
	    categories.push(obj[i].datevalue.substr(0,10));
    }
   	//alert(chart);
	//alert(chart.series);
	optiondetail.series[0].data = newUsers;
	serie = optiondetail.series[0];
	serie.type='spline';
	if(chartDetailName == 'chartTotalUser')     
	{
		serie = optiondetail.series[0];
	    serie.type = 'area';
	    }
	 optiondetail.xAxis.labels.step = parseInt(categories.length/10);
	 optiondetail.xAxis.categories = categories;  
	 optiondetail.title.text = titledetailName;
	 optiondetail.series[0].name = username;
	 chartdetail = new Highcharts.Chart(optiondetail);   
	//getChartData();
}

function selectChange(value)
{
	if(value=='过去一周')
    {
    	timePhase='7day';
     }
    if(value=='过去一个月')
    {
    	timePhase='1month';
        
     }
    if(value=='过去三个月')
    {
    	timePhase='3month';
        
     }
    if(value=='全部')
    {
    	timePhase='all';
     }
    if(value=='任意时间段')
    {
    	timePhase='any';
    }
    if(timePhase!='any')
    {
        getChartData();
    }
    dispalyOrHideTimeSelect();           
}
</script>
<script type="text/javascript">
var chart;
var options;
var chartdata;    
var titlename="<?php echo lang('probasictitle_todaystart') ?>" ;

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
	            labels:{rotation:0,y:10,x:0}
	        },
	        yAxis: {
	            title: {
	                text: ''
	            },
	            labels: {
	                formatter: function() {
	                    return Highcharts.numberFormat(this.value, 0);
	                }
	            },min:0
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
	            enabled:false
	         },
	        series: [{	            
	            marker: {
	                symbol: 'circle'
	            }
	        }]
	    };
});

function renderCharts(myurl)
{	
	 var chart_canvas = $('#container');
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
	   
	    jQuery.getJSON(myurl, null, function(data) {  
		chartdata=data;             
		var categories = [];
		var newUsers = [];
		var obj = data.content;
	    for(var i=0;i<obj.length;i++)
	    {
		    if(chartname=="startuser")
			    newUsers.push(parseInt(obj[i].startusers,10));
		    if(chartname=="newuser")
		    	newUsers.push(parseInt(obj[i].newusers,10));
	    	categories.push(obj[i].hour);
	    }
	    
		options.series[0].data = newUsers;
		options.xAxis.labels.step = parseInt(categories.length/10);
		options.xAxis.categories = categories;  
		options.title.text = titlename;
		options.series[0].name = "<?php echo lang('productview_jsstartinfo') ?>";
		chart = new Highcharts.Chart(options);
		
		chart_canvas.unblock();
		});  
}
</script>
<script type="text/javascript">

function getChartData() 
{
	var myurl = ""; 
	changeDetailChartTitlename(timePhase,chartDetailName);
	if(timePhase=='any')
	{		
		myurl="<?php echo site_url()?>/report/productbasic/getUsersDataByTime/"+timePhase+"/"+fromTime+"/"+toTime;
	}
	else
	{
		myurl="<?php echo site_url()?>/report/productbasic/getUsersDataByTime/"+timePhase;
	}
  
	renderuserCharts(myurl);
}
function changeDetailChartTitlename(timePhase,chartDetailName){
	if (timePhase == "7day") {
		if(chartDetailName=="chartActiveUser"){
			titledetailName = "<?php echo lang('usertitle_actva7days') ?>";
		}
		if(chartDetailName=="chartTotalUser"){
			titledetailName ="<?php echo lang('usertitlee_totalweek')?>" ;	
		}
		if(chartDetailName=="chartStartUser"){
			titledetailName ="<?php echo lang('usertitle_start7days')?>" ;	
		}
		if(chartDetailName=="chartNewUser"){
			titledetailName ="<?php echo lang('usertitlee_lastweeknew')?>" ;	
		}
		if(chartDetailName=="chartAverageUsingTime"){
			titledetailName ="<?php echo lang('usertitle_average7days')?>" ;	
		}
	}
	if (timePhase == "1month") {
		if(chartDetailName=="chartActiveUser"){
			titledetailName ="<?php echo lang('usertitle_actva30days') ?>";
		}
		if(chartDetailName=="chartTotalUser"){
			titledetailName ="<?php echo lang('usertitlee_totalmonth')?>" ;	
		}
		if(chartDetailName=="chartStartUser"){
			titledetailName ="<?php echo lang('usertitle_start30days')?>" ;	
		}
		if(chartDetailName=="chartNewUser"){
			titledetailName ="<?php echo lang('usertitlee_lastmonthnenw')?>" ;	
		}
		if(chartDetailName=="chartAverageUsingTime"){
			titledetailName ="<?php echo lang('usertitle_averagemonth')?>" ;	
		}
	}	
	if (timePhase == "3month") {
		if(chartDetailName=="chartActiveUser"){
			titledetailName ="<?php echo lang('usertitle_actva3month')?>";
		}
		if(chartDetailName=="chartTotalUser"){
			titledetailName ="<?php echo lang('usertitlee_total3month')?>" ;	
		}
		if(chartDetailName=="chartStartUser"){
			titledetailName ="<?php echo lang('usertitle_start3month')?>" ;	
		}
		if(chartDetailName=="chartNewUser"){
			titledetailName ="<?php echo lang('usertitlee_last3monthnew')?>" ;	
		}
		if(chartDetailName=="chartAverageUsingTime"){
			titledetailName ="<?php echo lang('usertitle_average3month')?>" ;	
		}
	}
	if (timePhase == "all") {
		if(chartDetailName=="chartActiveUser"){
			titledetailName ="<?php echo  lang('usertitle_actvaall') ?>";
		}
		if(chartDetailName=="chartTotalUser"){
			titledetailName ="<?php echo lang('usertitlee_totalall')?>" ;	
		}
		if(chartDetailName=="chartStartUser"){
			titledetailName ="<?php echo lang('usertitle_startall')?>" ;	
		}
		if(chartDetailName=="chartNewUser"){
			titledetailName ="<?php echo lang('usertitlee_lastalltimenew')?>" ;	
		}
		if(chartDetailName=="chartAverageUsingTime"){
			titledetailName ="<?php echo lang('usertitle_averageall')?>" ;	
		}
	}    
	if (timePhase == "any") {
		if(chartDetailName =="chartActiveUser"){
			titledetailName ="<?php echo  lang('usertitle_actvaanytime') ?>";
		}
		if(chartDetailName=="chartTotalUser"){
			titledetailName ="<?php echo lang('usertitlee_totalanytime')?>" ;	
		}
		if(chartDetailName=="chartStartUser"){
			titledetailName ="<?php echo lang('usertitle_startanytime')?>" ;	
		}
		if(chartDetailName=="chartNewUser"){
			titledetailName ="<?php echo lang('usertitlee_lastanytimenew')?>" ;	
		}
		if(chartDetailName=="chartAverageUsingTime"){
			titledetailName ="<?php echo lang('usertitle_averageanytime')?>" ;	
		}
	}  
}
</script>


<script type="text/javascript">
var chart;
var chartdetail;
var chart_canvas;
var optiondetail;
function renderuserCharts(myurl)
{
//	if(chart)
//	{
//		chart.destroy();
//	}
	
	optiondetail = {
        chart: {
            renderTo: 'usercontainer',
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
            min:0,
            title: {
                text: ''
            },
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
            enabled:false
         },
        series: [{
            
            marker: {
                symbol: 'circle'
            }
           

        }]
    };

	   chart_canvas = $('#usercontainer');
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

	    jQuery.getJSON(myurl, null, function(data) {  
		   // alert("ddddddd");
		    chartdetailData=data;
			var categories = [];
			var newUsers = [];
			var obj = data.content;
		    for(var i=0;i<obj.length;i++)
		    {
			    if(chartDetailName=="chartNewUser")
			    	newUsers.push(parseInt(obj[i].newusers,10));				    
			    if(chartDetailName=="chartTotalUser"){
				    if(obj[i].allusers==null){}
				    else
					    newUsers.push(parseInt(obj[i].allusers,10));
				    }
			    if(chartDetailName=="chartActiveUser")
				    newUsers.push(parseInt(obj[i].startusers,10));
			    if(chartDetailName=="chartStartUser")
				    newUsers.push(parseInt(obj[i].sessions,10));
			    if(chartDetailName=="chartAverageUsingTime")
				    newUsers.push(parseInt(obj[i].usingtime,10));
			    categories.push(obj[i].datevalue.substr(0,10));
		    }
		   	//alert(chart);
			//alert(chart.series);
			optiondetail.series[0].data = newUsers;
			//alert(chartName);
			 if(chartDetailName == 'chartTotalUser')
			    {
				    serie = optiondetail.series[0];
			       	serie.type = 'area';
				}
			 optiondetail.xAxis.labels.step = parseInt(categories.length/10);
			 optiondetail.xAxis.categories = categories;  
			 optiondetail.title.text = titledetailName;
			 optiondetail.series[0].name = username;
			 chartdetail = new Highcharts.Chart(optiondetail);
			 chart_canvas.unblock();
			});
}
</script>

<script type="text/javascript">
			function pageselectCallback(page_index, jq){
				var myurl="<?php echo site_url()?>/report/productbasic/getDetailData/"+page_index;
				jQuery.ajax({
					type : "post",
					url : myurl,
					success : function(msg) {
						var container = document.getElementById("content");
						setTBodyInnerHTML(container,msg);
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						//document.getElementById('msg').innerHTML = "加载数据出错";
						
					},
					beforeSend : function() {
						//document.getElementById('msg').innerHTML = '正在加载数据，请稍候...';

					},
					complete : function() {
						//document.getElementById('msg').innerHTML = '加载数据完成';
					}
				});
                return false;
            }
           
            /** 
             * Callback function for the AJAX content loader.
             */
            function initPagination() {
                var num_entries = <?php if(isset($pagenum)){echo $pagenum;} else{echo 90;} ?>/<?php echo PAGE_NUMS;?>;
                // Create pagination element
                $("#pagination").pagination(num_entries, {
                    num_edge_entries: 2,
                    prev_text: '<?php echo  lang('allview_jsbeforepage')?>',       //上一页按钮里text 
                    next_text: '<?php echo  lang('allview_jsnextpage')?>',       //下一页按钮里text            
                    num_display_entries: 8,
                    callback: pageselectCallback,
                    items_per_page:1
                });
             }
                    
            // Load HTML snippet with AJAX and insert it into the Hiddenresult element
            // When the HTML has loaded, call initPagination to paginate the elements        
            $(document).ready(function(){      
            	initPagination();
            	pageselectCallback(0,0);
            });    


            function setTBodyInnerHTML(tbody, html) {
          	  var temp = tbody.ownerDocument.createElement('div');
          	  temp.innerHTML = '<table><tbody id=\"content\">' + html + '</tbody></table>';
          	  tbody.parentNode.replaceChild(temp.firstChild.firstChild, tbody);
          	}       
</script>



<script type="text/javascript">
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
