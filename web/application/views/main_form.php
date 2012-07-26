<section id="main" class="column">
			<?php if(isset($message)):?>
		<h4 class="alert_success"><?php echo $message;?></h4>
		<?php else:?>
			<h4 id='msg' class="alert_success"><?php echo  lang('allview_myapplication')?></h4>
		<?php endif;?>
		
		<article class="module width_3_quarter">
		<header><h3 class="tabs_involved"><?php echo  lang('mainform_headeinfo')?> <?php echo anchor('/product/create/',lang('mainform_headelinkinfo')); ?></h3>
		
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo  lang('mainform_appnamethead')?></th> 
    				<th><?php echo  lang('mainform_platform')?></th>
    				<th><?php echo  lang('mainform_usernumthead')?></th> 
    				<th><?php echo  lang('mainform_newthead')?></th> 
    				<th><?php echo  lang('mainform_startthead')?></th> 
    				<th><?php echo  lang('mainform_startnumthead')?></th>
    				<th><?php echo  lang('mainform_actionthead')?></th>
				</tr> 
			</thead> 
			<tbody> 
			
			 <?php if(isset($androidList)):
			 	for($i=0;$i<count($androidList);$i++)
			 {
			 	$row = $androidList[$i];
			 ?>
				<tr> 
    				<td><?php echo $row['name'];?></td> 
    				<td><?php echo $row['platform']?></td>
    				<td><?php echo $row['totaluser'];?></td> 
    				<td><?php echo $row['newuser'];?></td> 
    				<td><?php echo $row['startuser'];?></td>
    				<td><?php echo $row['startcount'];?></td>
    				
    				<td>
    				<?php echo anchor('/report/productbasic/view/'.$row['id'],lang('mainform_viewtbodyinfo'));?>
    				<a href="javascript:if(confirm('<?php echo  lang('mainform_tbodydeleteinfo')?>'))location='<?php echo site_url();?>/product/delete/<?php echo $row['id'] ;?>'"><?php echo  lang('mainform_tbodydelete')?></a>
				</tr> 
			<?php } endif;?>
			
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		
		<article class="module width_quarter">
			<header><h3><?php echo  lang('mainform_appsurveyinfo')?></h3></header>
			<article class="stats_overview width_full">
			
					<p class="overview_day"><?php echo  lang('mainform_appsumuserinfo')?></p>
					<p class="overview_count"><?php echo isset($today_totaluser)?$today_totaluser:0;?></p>
					<hr>
					<div class="overview_today">
						<p class="overview_day"><?php echo  lang('mainform_apptodayinfo')?></p>
						<p class="overview_count"><?php echo isset($today_newuser)?$today_newuser:0;?></p>
						<p class="overview_type"><?php echo  lang('mainform_appnewinfo')?></p>
						<p class="overview_count"><?php echo isset($today_startuser)?$today_startuser:0;?></p>
						<p class="overview_type"><?php echo  lang('mainform_appstartinfo')?></p>
						<p class="overview_count"><?php echo isset($today_startcount)?$today_startcount:0;?></p>
						<p class="overview_type"><?php echo  lang('mainform_appstartnumainfo')?></p>
					</div>
					<div class="overview_previous">
						<p class="overview_day"><?php echo  lang('mainform_appyesterdayinfo')?></p>
						<p class="overview_count"><?php echo isset($yestoday_newuser)?$yestoday_newuser:0;?></p>
						<p class="overview_type"><?php echo  lang('mainform_appinfonew')?></p>
						<p class="overview_count"><?php echo isset($yestoday_startuser)?$yestoday_startuser:0;?></p>
						<p class="overview_type"><?php echo  lang('mainform_appinfostart')?></p>
						<p class="overview_count"><?php echo isset($yestoday_startcount)?$yestoday_startcount:0;?></p>
						<p class="overview_type"><?php echo  lang('mainform_appinfostartnum')?></p>
					</div>
					
					
				</article>
		</article>
		
		<div class="clear"></div>
		<div class="spacer"></div>
			<article class="module width_full">
			<header><h3><?php echo  lang('mainform_visittrend')?></h3>
			<div class="submit_link"><select onchange="selectChange(this.value)" id='select'>
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
				<div class="module_content">
				<article class="width_full">
				<div id="container"  class="module_content" style="height:300px">
		
		</div>
			   </article>
		
				<div class="clear"></div>
			</div>
			<footer>
			<ul class="tabs2">
				<li><a ct="newUser" href="javascript:changeChartName('chartNewUser')"><?php echo  lang('mainform_ultabnewuser')?></a></li>
				<li><a ct="activeUser" href="javascript:changeChartName('chartActiveUser')"><?php echo  lang('mainform_ultabactiuser')?></a></li>
				<li><a ct="startUser" href="javascript:changeChartName('chartStartUser')"><?php echo  lang('mainform_ultabstartuser')?></a></li>
			</ul>
		</footer>
		</article><!-- end of stats article -->
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
<script type="text/javascript">
var chart;
var options;
var name;
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
	            labels: {
	                formatter: function() {
	                    return Highcharts.numberFormat(this.value, 0);
	                }
	            },
	            min:0
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
	getChartData();
});
var titleName='';
var chartdata;
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
	    titleName=getTitle(chartName,timePhase);
	jQuery.getJSON(myurl, null, function(data) { 
		chartdata=data; 
		var categories = [];
		var newUsers = [];
		var obj = data.content;
	    for(var i=0;i<obj.length;i++)
	    {
		    if(chartName=='chartNewUser'){
		    	newUsers.push(parseInt(obj[i].newusers,10));
		    }
		    if(chartName=='chartActiveUser'){
		    	newUsers.push(parseInt(obj[i].startusers,10));
		    }
		    if(chartName=='chartStartUser'){
		    	newUsers.push(parseInt(obj[i].sessions,10));
		    }
		    
	    	
	    	categories.push(obj[i].datevalue);
	    }
	    
		options.series[0].data = newUsers;
		options.xAxis.labels.step = parseInt(categories.length/10);
		options.xAxis.categories = categories;  
		options.title.text = titleName;
		options.series[0].name = name;
		chart = new Highcharts.Chart(options);
		chart_canvas.unblock();
		});  
}


function  getTitle(chartName,timePhase){
	if(chartName=='chartNewUser'){
		if(timePhase=='any'){return  "<?php echo lang('producttitle_newtrend'); ?>";}
		if(timePhase=='all'){return '<?php echo lang('producttitle_newa11'); ?>';}
		if(timePhase=='3month'){return'<?php echo lang('producttitle_new3month'); ?>';}
		if(timePhase=='1month'){return'<?php echo lang('producttitle_newmonth'); ?>';}
		if(timePhase=='7day'){return  '<?php echo lang('producttitle_newweek'); ?>';}
	}
	if(chartName=='chartActiveUser'){
		if(timePhase=='any'){return  '<?php echo lang('producttitle_acttrend'); ?>';}
		if(timePhase=='all'){return  '<?php echo lang('producttitle_actall'); ?>';}
		if(timePhase=='3month'){return  '<?php echo lang('producttitle_act3month'); ?>';}
		if(timePhase=='1month'){return  '<?php echo lang('producttitle_actmonth'); ?>';}
		if(timePhase=='7day'){return  '<?php echo lang('producttitle_actweek'); ?>';}
	}
	if(chartName=='chartStartUser'){
		if(timePhase=='any'){return  '<?php echo lang('producttitle_starttrend'); ?>';}
		if(timePhase=='all'){return  '<?php echo lang('producttitle_startall'); ?>';}
		if(timePhase=='3month'){return  '<?php echo lang('producttitle_start3month'); ?>';}
		if(timePhase=='1month'){return  '<?php echo lang('producttitle_startmonth'); ?>';}
		if(timePhase=='7day'){return  '<?php echo lang('producttitle_startweek'); ?>';}
	}
	
	
}

</script>
 
<script type="text/javascript">
var chartName = 'chartNewUser';
var timePhase = '7day';
var fromTime;
var toTime;

//When page loads...
dispalyOrHideTimeSelect();
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content
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

function changeChartName(name)
{
	chartName = name;
	//getChartData();

	var categories = [];
	var newUsers = [];
	var obj = chartdata.content;
    for(var i=0;i<obj.length;i++)
    {
	    if(chartName=='chartNewUser'){
	    	newUsers.push(parseInt(obj[i].newusers,10));
	    }
	    if(chartName=='chartActiveUser'){
	    	newUsers.push(parseInt(obj[i].startusers,10));
	    }
	    if(chartName=='chartStartUser'){
	    	newUsers.push(parseInt(obj[i].sessions,10));
	    }
	    
    	
    	categories.push(obj[i].datevalue);
    }
    
	options.series[0].data = newUsers;
	options.xAxis.labels.step = parseInt(categories.length/10);
	options.xAxis.categories = categories;  
	options.title.text = titleName;
	options.series[0].name = name;
	chart = new Highcharts.Chart(options);

	
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

//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	//$(".tab_content").hide(); //Hide all tab content
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("ct"); //Find the href attribute value to identify the active tab + content
	$('#'+activeTab).fadeIn(); //Fade in the active ID content
	return true;
});

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
</script>

<script type="text/javascript">

function onAnyTimeButtonClicked()
{  
	fromTime = document.getElementById('dpFrom').value;
	toTime = document.getElementById('dpTo').value;
	getChartData();
}

</script>

<script type="text/javascript">

function getChartData()
{
	var myurl = "";
	if(chartName == 'chartNewUser')
	{
		name = "<?php echo  lang('mainform_chartnewuser')?>";
		if(timePhase=='any')
		{		
			myurl="<?php echo site_url()?>/product/getNewUsersByTimeJSON/"+timePhase+"/"+fromTime+"/"+toTime;
		}
		else
		{
			myurl="<?php echo site_url()?>/product/getNewUsersByTimeJSON/"+timePhase;
		}
	}

	if(chartName == 'chartActiveUser')
	{
		name = "<?php echo  lang('mainform_chartactiuser')?>";
		if(timePhase=='any')
		{		
			myurl="<?php echo site_url()?>/product/getNewUsersByTimeJSON/"+timePhase+"/"+fromTime+"/"+toTime;
		}
		else
		{
			myurl="<?php echo site_url()?>/product/getNewUsersByTimeJSON/"+timePhase;
		}
	}

	if(chartName == 'chartStartUser')
	{
		name = "<?php echo  lang('mainform_chartstartuser')?>";
		if(timePhase=='any')
		{		
			myurl="<?php echo site_url()?>/product/getNewUsersByTimeJSON/"+timePhase+"/"+fromTime+"/"+toTime;
		}
		else
		{
			myurl="<?php echo site_url()?>/product/getNewUsersByTimeJSON/"+timePhase;
		}
	}
	timeFlag = 5;
	renderCharts(myurl);
}
</script>



	