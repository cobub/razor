<section id="main" class="column">
			<?php if(isset($message)):?>
		<h4 class="alert_success"><?php echo $message;?></h4>		
		<?php endif;?>	
			<article class="module width_3_quarter">
			<header><h3><?php echo  lang('v_overallAnalytics')?></h3>	
				<ul class="tabs2">
				<li><a ct="newUser" href="javascript:changeChartName('<?php echo  lang('t_newUsers')?>')"><?php echo  lang('t_newUsers')?></a></li>
				<li><a ct="activeUser" href="javascript:changeChartName('<?php echo  lang('t_activeUsers')?>')"><?php echo  lang('t_activeUsers')?></a></li>
				<li><a ct="startUser" href="javascript:changeChartName('<?php echo  lang('t_sessions')?>')"><?php echo  lang('t_sessions')?></a></li>
			</ul>	
			</header>
				<div class="module_content">
				<article>
				<div id="container"  class="module_content" style="height:250px">
		
		</div>
			   </article>
		
				<div class="clear"></div>
			</div>		
		</article><!-- end of stats article -->			
		<article class="module width_quarter">
			<header><h3><?php echo  lang('v_overview')?></h3></header>
			<article class="stats_overview width_full">
			
					<p class="overview_day"><?php echo  lang('v_totalUsers')?></p>
					<p class="overview_count"><?php echo isset($today_totaluser)?$today_totaluser:0;?></p>
					<hr>
					<div class="overview_today">
						<p class="overview_day"><?php echo  lang('g_today')?></p>
						<p class="overview_count"><?php echo isset($today_newuser)?$today_newuser:0;?></p>
						<p class="overview_type"><?php echo  lang('t_newUsers')?></p>
						<p class="overview_count"><?php echo isset($today_startuser)?$today_startuser:0;?></p>
						<p class="overview_type"><?php echo  lang('t_activeUsers')?></p>
						<p class="overview_count"><?php echo isset($today_startcount)?$today_startcount:0;?></p>
						<p class="overview_type"><?php echo  lang('t_sessions')?></p>
					</div>
					<div class="overview_previous">
						<p class="overview_day"><?php echo  lang('g_yesterday')?></p>
						<p class="overview_count"><?php echo isset($yestoday_newuser)?$yestoday_newuser:0;?></p>
						<p class="overview_type"><?php echo  lang('t_newUsers')?></p>
						<p class="overview_count"><?php echo isset($yestoday_startuser)?$yestoday_startuser:0;?></p>
						<p class="overview_type"><?php echo  lang('t_activeUsers')?></p>
						<p class="overview_count"><?php echo isset($yestoday_startcount)?$yestoday_startcount:0;?></p>
						<p class="overview_type"><?php echo  lang('t_sessions')?></p>
					</div>
					
					
				</article>
		</article>
			<div class="clear"></div>
		<div class="spacer"></div>	
			<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('v_apps')?>
		 <?php echo anchor('/manage/product/create/',lang('m_new_newApp')); ?></h3>
		
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo  lang('v_app')?></th> 
    				<th><?php echo  lang('v_platform')?></th>
    				<th><?php echo  lang('v_totalUsers')?></th> 
    				<th><?php echo  lang('t_newUsers')?></th> 
    				<th><?php echo  lang('t_activeUsers')?></th> 
    				<th><?php echo  lang('t_sessions')?></th>
    				<th><?php echo  lang('g_actions')?></th>
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
    				<?php echo anchor('/report/productbasic/view/'.$row['id'],lang('v_viewReport'));?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    				<a href="javascript:if(confirm('<?php echo  lang('v_deleteAppPrompt')?>'))location='<?php echo site_url();?>/manage/product/delete/<?php echo $row['id'] ;?>'"><?php echo  lang('g_delete')?></a>
				</tr> 
			<?php } endif;?>
			
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->	
		<div class="clear"></div>
		<div class="spacer"></div>		
		<article class="module module width_full">
		<header><h3><?php echo lang('v_CR_news'); ?></h3></header>
		<iframe src="http://news.cobub.com/index.php?/news/getnews"  width="100%" 
		height="250px" frameborder="0" scrolling="no" ></iframe>		
		</article>
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
<script type="text/javascript">
var chart;
var options;
var chartName="<?php echo  lang('t_newUsers')?>";
var newUserData = [];
var activeUserData = [];
var sessionData = [];
$(document).ready(function() {
	options = {
	        chart: {
	            renderTo: 'container',
	            type: 'spline'
	        },
	        title: {
	            text: ' '
	        },
	        subtitle: {
	            text: '<?php echo $reportTitle['timePase'] ?>'
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
	var myurl  = "<?php echo site_url();?>/report/console/getConsoleDatainfo";	
	renderCharts(myurl);		
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
		var categories = [];		
		var obj = data.content;		
	    for(var i=0;i<obj.length;i++)
	    {		   
	    	newUserData.push(parseInt(obj[i].newusers,10));
	    	activeUserData.push(parseInt(obj[i].startusers,10));		   
	    	sessionData.push(parseInt(obj[i].sessions,10));		  
	    	categories.push(obj[i].datevalue);
	    }
		options.series[0].data = newUserData;
		options.xAxis.labels.step = parseInt(categories.length/10);
		options.xAxis.categories = categories;  
		options.title.text = "<?php echo $reportTitle['newUser'] ?>";
		options.series[0].name = chartName;
		chart = new Highcharts.Chart(options);
		chart_canvas.unblock();
		});  
}
function changeChartName(name)
{	
	chartName = name;	
	if(chartName=='<?php echo  lang('t_newUsers')?>')
	{
		options.series[0].data = newUserData;
		options.title.text = "<?php echo $reportTitle['newUser'] ?>";
		options.series[0].name = chartName;
		chart = new Highcharts.Chart(options);
	}

	if(chartName=='<?php echo  lang('t_activeUsers')?>')
	{
		options.series[0].data = activeUserData;
		options.title.text = "<?php echo $reportTitle['activeUser'] ?>";
		options.series[0].name = chartName;
		chart = new Highcharts.Chart(options);
	}
	if(chartName=='<?php echo  lang('t_sessions')?>')
	{
		options.series[0].data = sessionData;
		options.title.text = "<?php echo $reportTitle['session'] ?>";
		options.series[0].name = chartName;
		chart = new Highcharts.Chart(options);
	}
	
}
</script>
 
<script type="text/javascript">
//When page loads...
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content


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



	