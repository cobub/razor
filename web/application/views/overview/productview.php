<section id="main" class="column" style="height:1800px;">
		<?php if(isset($message)):?>
		<h4 class="alert_success"><?php echo $message;?></h4>		
		<?php endif;?>
		
		<article class="module width_full">
			<header><h3><?php echo lang('v_rpt_pb_overviewRecently') ?></h3>					
			</header>
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th></th> 
    				<th><?php echo lang('t_sessions') ?></th> 
    				<th><?php echo lang('t_activeUsers') ?></th> 
    				<th><?php echo lang('t_newUsers') ?></th> 
    				<th><?php echo lang('t_percentOfNewUsers') ?></th>
    				<th><?php echo lang('t_upgradeUsers') ?></th>
    				<th><?php echo lang('t_averageUsageDuration') ?></th>
				</tr> 
			</thead> 
			<tbody> 
			<?php if(isset($today1)):?>
			<tr> 
    				<td><?php echo lang('g_today') ?></td> 
                    <td><?php echo $today1->sessions;?></td> 
    				<td><?php echo $today1->startusers;?></td> 
    				<td><?php echo $today1->newusers;?></td> 
    				<td><?php echo percent($today1->newusers,$today1->startusers)?></td>
    				<td><?php echo $today1->upgradeusers;?></td>
    				<td><?php echo round(($today1->usingtime/$today1->sessions)/1000,2).lang('g_s');?></td>
    				
				</tr> 
			<?php endif;?>
			
			<?php if(isset($yestoday)):?>
			<tr> 
    				<td><?php echo lang('g_yesterday') ?></td> 
    				 <td><?php echo $yestoday->sessions;?></td> 
    				<td><?php echo $yestoday->startusers;?></td> 
    				<td><?php echo $yestoday->newusers;?></td> 
    				<td><?php echo percent($yestoday->newusers,$yestoday->startusers)?></td>
    				<td><?php echo $yestoday->upgradeusers;?></td>
    				<td><?php echo round(($yestoday->usingtime/$yestoday->sessions)/1000,2).lang('g_s');?></td>
				</tr> 
			<?php endif;?>
			</tbody>			
			</table>
		</article>
		
			<article class="module width_full">
			<header><h3><?php echo lang('v_rpt_pb_generalSituation') ?></h3>			
			</header>
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo lang('t_accumulatedUsers') ?></th> 
    				<th><?php echo lang('t_accumulatedStarts') ?></th> 
    				<th><?php echo lang('t_activeUsersWeekly') ?></th> 
    				<th><?php echo lang('t_activeRateWeekly') ?></th>
    				<th><?php echo lang('t_activeUsersMonthly') ?></th>
    				<th><?php echo lang('t_activeRateMonthly') ?></th>
				</tr> 
			</thead> 
			<tbody> 
			<?php if(isset($overall)):?>
			<tr> 
    				<td><?php echo $overall['alltime'];?></td> 
    				<td><?php echo $today1->allsessions;?></td> 
    				<td><?php echo $overall['7dayactive'];?></td> 
    				<td><?php if($overall['alltime']==0){echo '0.0%';}else{echo percent($overall['7dayactive'],$overall['alltime']);} ?></td>
    				<td><?php echo $overall['1month'];?></td>
					<td><?php if($overall['alltime']==0){echo '0.0%';}else{echo percent($overall['1month'],$overall['alltime']);} ?></td>
				</tr> 
			<?php endif;?>
			</tbody>			
			</table>
		</article>
		
		<article class="module width_full">
			<header><h3 class="tabs_involved"><?php echo lang('v_rpt_pb_timeTrendOfUsers') ?></h3>                                                                                                                                      
			<div class="submit_link">
			<select onchange="switchTimePhase(this.options[this.selectedIndex].value)" id='startselect'>
				<option value=today selected ><?php echo  lang('g_today')?></option>
				<option value=yestoday><?php echo  lang('g_yesterday')?></option>
				<option value=last7days><?php echo  lang('g_last7days')?></option>
				<option value=last30days><?php echo  lang('g_last30days')?></option>
				<option value=any><?php echo  lang('g_anytime')?></option>			
			</select>
			<div id='selectcurTime'><input type="text"
				id="dpTimeFrom"> <input type="text" id="dpTimeTo"> <input type="submit"
				id='timebtn' value="<?php echo  lang('g_search')?>" class="alt_btn" onclick="onAnyTimeClicked()"></div>
			</div>				
			</div>   			
			</header>
			<div class="tab_container">
				<div id="tab1" class="tab_content">
					<div class="module_content">
						<article>
						<div id="container"  class="module_content" style="height:300px">
		
		</div>
						</article>
						</div>
						<div class="clear"></div>
					</div>
				</div>
					<footer>
			<ul class="tabs2">
				<li><a ct="newUser" href="javascript:changefirstchartName('startuser')"><?php echo  lang('t_activeUsers')?></a></li>
				<li><a ct="totalUser" href="javascript:changefirstchartName('newuser')"><?php echo  lang('t_newUsers')?></a></li>				
			</ul>
		</footer>
		</article>
	
	   <article class="module width_full">     
		<header>
			<h3><?php echo  lang('v_rpt_pb_overviewOfUserBehavior')?></h3>
			<ul class="tabs3">
				<li><a ct="newUser" href="javascript:changeChartName('<?php echo  lang('t_newUsers')?>')"><?php echo  lang('t_newUsers')?></a></li>
				<li><a ct="totalUser" href="javascript:changeChartName('<?php echo  lang('t_accumulatedUsers')?>')"><?php echo  lang('t_accumulatedUsers')?></a></li>
				<li><a ct="activeUser" href="javascript:changeChartName('<?php echo  lang('t_activeUsers')?>')"><?php echo  lang('t_activeUsers')?></a></li>
				<li><a ct="startUser" href="javascript:changeChartName('<?php echo  lang('t_sessions')?>')"><?php echo  lang('t_sessions')?></a></li>
				<li><a ct="averageUsingTime" href="javascript:changeChartName('<?php echo  lang('t_averageUsageDuration')?>')"><?php echo  lang('t_averageUsageDuration')?></a></li>
			</ul>
		</header>
		<article>
		<div id="usercontainer"  class="module_content" style="height:300px">
		</div>
		</article>
		<div class="clear"></div>		
	</article>
	<div class="clear"></div>
	<div class="spacer"></div>

	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('v_rpt_pb_userDataDetail')?></h3>
				<span class="relative r">
				<a href="<?php echo site_url(); ?>/report/productbasic/exportdetaildata" class="bottun4 hover" >
				<font><?php echo  lang('g_exportToCSV');?></font></a>
			</span>					
		</header>
		
		<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo  lang('g_date')?></th> 
    				<th><?php echo  lang('t_newUsers')?></th> 
    				<th><?php echo  lang('t_accumulatedUsers')?></th> 
    				<th><?php echo  lang('t_activeUsers')?></th> 
    				<th><?php echo  lang('t_sessions')?></th>
    				<th><?php echo  lang('t_averageUsageDuration')?></th>
				</tr> 
			</thead> 
			<tbody id="content">		     
	    <?php $num = count($dashboardDetailData);?>	    	
			</tbody>
		</table> 
		
		<footer>
		<div id="pagination"  class="submit_link">
		</div>
		</footer>
	</article>	
		<div class="clear"></div>
		<div class="spacer"></div>
	
		
</section>
	
<script>
var chartDetailName = '<?php echo  lang('t_newUsers')?>';
var fromCurTime;
var toCurTime;
var chartname = 'startuser';
var timephase = 'today';
var name;
var newUser=[];
var totalUser=[];
var activeUser=[];
var sessionNum=[];
var avgUsage=[];
//When page loads...
dispalyOrHideCurTimeSelect();
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

$("ul.tabs3 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content
$(document).ready(function() {
	getfirstchartdata();
	//load Overview of User Behavior report
	var myurl="<?php echo site_url()?>/report/productbasic/getUsersDataByTime";
    renderuserCharts(myurl);
    initPagination();
	pageselectCallback(0,null);	
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
</script>

<script type="text/javascript">
$(function() {
	$( "#dpTimeFrom" ).datepicker();
});
$( "#dpTimeFrom" ).datepicker({ dateFormat: "yy-mm-dd" });
$(function() {
	$( "#dpTimeTo" ).datepicker();
});
$( "#dpTimeTo" ).datepicker({ dateFormat: "yy-mm-dd" });
</script>
<!-- Overview of User Behavior report -->
<script type="text/javascript">
var chart_detailcanvas;
var optiondetail;
var chartdetail;
function renderuserCharts(myurl)
{
	optiondetail = {
        chart: {
            renderTo: 'usercontainer',
            type: 'spline'
        },
        title: {
            text: '   '
        },
        subtitle: {
            text: '<?php echo $reportTitle['timePase']; ?>'
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

	  chart_detailcanvas = $('#usercontainer');
	    var loading_img = $("<img src='<?php echo base_url();?>/assets/images/loader.gif'/>");
	    chart_detailcanvas.block({
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
		  	var categories = [];		
			var obj = data.content;
		    for(var i=0;i<obj.length;i++)
		    {			  
			   newUser.push(parseInt(obj[i].newusers,10));				    
			   totalUser.push(parseInt(obj[i].allusers,10));
			   activeUser.push(parseInt(obj[i].startusers,10));			  
			   sessionNum.push(parseInt(obj[i].sessions,10));	
			   var usagetime ;
	       		if(obj[i].sessions==0)
	           	{
	       			usagetime = 0;
	       		}
	       		else
	           	{
	       			usagetime = (obj[i].usingtime/obj[i].sessions)/1000;
	           	}
	       		avgUsage.push(parseFloat(parseFloat(usagetime,10).toFixed(2)));			  
			    categories.push(obj[i].datevalue.substr(0,10));
		    }		   
			 optiondetail.series[0].data = newUser;				
			 optiondetail.xAxis.labels.step = parseInt(categories.length/10);
			 optiondetail.xAxis.categories = categories;  
			 optiondetail.title.text = "<?php echo $reportTitle['newUser'] ?>";
			 optiondetail.series[0].name = chartDetailName;
			 chartdetail = new Highcharts.Chart(optiondetail);
			 chart_detailcanvas.unblock();
			});
}
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
	if(time!="any")
	{
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
			titlename = "<?php echo lang('t_activeUsersT') ?>";
		}
		if(chartname=="newuser"){
			titlename ="<?php echo lang('t_newUserT')?>" ;
			
		}
	}
	if (timephase == "yestoday") {
		if(chartname=="startuser"){
			titlename ="<?php echo lang('t_activeUsersY') ?>";
		}
		else
			titlename ="<?php echo lang('t_newUserY') ?>";
	}	
	if (timephase == "last7days") {
		if(chartname=="startuser"){
			titlename ="<?php echo lang('t_activeUsersW')?>";
		}
		else
			titlename ="<?php echo lang('t_newUserW') ?>";
	}
	if (timephase == "last30days") {
		if(chartname=="startuser"){
			titlename ="<?php echo  lang('t_activeUsersM') ?>";
		}
		else
			titlename ="<?php echo lang('t_newUserM') ?>";
	}  
	if (timephase == "any") {
		if(chartname=="startuser"){
			titlename ="<?php echo  lang('t_activeUsersA') ?>";
		}
		else
			titlename ="<?php echo lang('t_newUsersA') ?>";
	}  
}



function changeChartName(name)
{	
	chartDetailName = name;	
	if(chartDetailName=="<?php echo  lang('t_newUsers')?>")
	{
		 optiondetail.series[0].data = newUser;			 
		 optiondetail.title.text = "<?php echo $reportTitle['newUser'] ?>";	
		 optiondetail.series[0].name = chartDetailName;	
		 chartdetail = new Highcharts.Chart(optiondetail);
		
	}	
	if(chartDetailName=="<?php echo  lang('t_accumulatedUsers')?>")
	{
		optiondetail.series[0].data = totalUser;			 
		optiondetail.title.text = "<?php echo $reportTitle['totalUser'] ?>";
		 optiondetail.series[0].name = chartDetailName;			
		chartdetail = new Highcharts.Chart(optiondetail);
		
	}
	if(chartDetailName=="<?php echo  lang('t_activeUsers')?>")
	{
		optiondetail.series[0].data =activeUser ;
		optiondetail.title.text = "<?php echo $reportTitle['activeUser'] ?>";
		 optiondetail.series[0].name = chartDetailName;		
		chartdetail = new Highcharts.Chart(optiondetail);
	}
	if(chartDetailName=="<?php echo  lang('t_sessions')?>")
	{
		optiondetail.series[0].data =sessionNum ;
		optiondetail.title.text = "<?php echo $reportTitle['sessionNum'] ?>";
		 optiondetail.series[0].name = chartDetailName;		
		chartdetail = new Highcharts.Chart(optiondetail);
	}
	if(chartDetailName=="<?php echo  lang('t_averageUsageDuration')?>")
	{
		optiondetail.series[0].data =avgUsage ;		
		optiondetail.title.text = "<?php echo $reportTitle['avgUsage'] ?>";	
		 optiondetail.series[0].name = chartDetailName;
		chartdetail = new Highcharts.Chart(optiondetail);
	}
	 
	
}
</script>
<script type="text/javascript">
var chart;
var options;
var chartdata;    
var titlename="<?php echo lang('t_activeUsersT') ?>" ;

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
		options.series[0].name = "<?php echo lang('t_activeUsers') ?>";
		chart = new Highcharts.Chart(options);		
		chart_canvas.unblock();
		});  
}
</script>
<script type="text/javascript">
var dashboarddetaildata = eval(<?php echo "'".json_encode($dashboardDetailData)."'"?>);
function pageselectCallback(page_index, jq){			
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";   
	var index = page_index*<?php echo PAGE_NUMS?>;
	var pagenum = <?php echo PAGE_NUMS?>;	
	var msg = "";	
	for(i=0;i<pagenum && (index+i)<dashboarddetaildata.length ;i++)
	{ 
		 var avgusagetime ;
 		if(dashboarddetaildata[i+index].start==0)
     	{
 			avgusagetime = 0;
 		}
 		else
     	{
 			avgusagetime =(dashboarddetaildata[i+index].aver/dashboarddetaildata[i+index].start)/1000;
     	}
		msg = msg+"<tr><td>";
		msg = msg+ dashboarddetaildata[i+index].date;
		msg = msg+"</td><td>";
		msg = msg+ dashboarddetaildata[i+index].newuser;
		msg = msg+"</td><td>";
		msg = msg+ dashboarddetaildata[i+index].total;
		msg = msg+"</td><td>";
		msg = msg+ dashboarddetaildata[i+index].active;
		msg = msg+"</td><td>";
		msg = msg+ dashboarddetaildata[i+index].start;
		msg = msg+"</td><td>";
		msg = msg+ (avgusagetime).toFixed(2)+"<?php echo lang('g_s') ?></td>";
		msg = msg+"</tr>";
	}
	
   document.getElementById('content').innerHTML = msg;				
   return false;
 }
           
            /** 
             * Callback function for the AJAX content loader.
             */
            function initPagination() {
            	var num_entries = <?php if(isset($num)) echo $num; ?>/<?php echo PAGE_NUMS;?>;
                // Create pagination element
                $("#pagination").pagination(num_entries, {
                    num_edge_entries: 2,
                    prev_text: '<?php echo  lang('g_previousPage')?>',
                    next_text: '<?php echo  lang('g_nextPage')?>',           
                    num_display_entries: 4,
                    callback: pageselectCallback,
                    items_per_page:1               
                });
             }
      
</script>