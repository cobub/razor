<?php
$language = $this->config->item('language');
?>

<section id="main" class="column" style='height:1500px;'>
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
					<p style="height:8px;"><br /></p>
					<p class="overview_count"><?php echo isset($today_totaluser)?$today_totaluser:0;?></p>
					<p style="height:6px;"><br /></p>
					<hr>
					<div class="overview_today" >
						<p class="overview_day"><?php echo  lang('g_today')?></p>
						<p style="height:8px;"><br /></p>
						<p class="overview_count"><?php echo isset($today_newuser)?$today_newuser:0;?></p>
						<p style="height:6px;"><br /></p>
						<p class="overview_type"><?php echo  lang('t_newUsers')?></p>
						<p  style="height:8px;"><br /></p>
						<p class="overview_count"><?php echo isset($today_startuser)?$today_startuser:0;?></p>
						<p style="height:6px;"><br /></p>
						<p class="overview_type"><?php echo  lang('t_activeUsers')?></p>
						<p  style="height:8px;"><br /></p>
						<p class="overview_count"><?php echo isset($today_startcount)?$today_startcount:0;?></p>
						<p style="height:6px;"><br /></p>
						<p class="overview_type"><?php echo  lang('t_sessions')?></p>
					</div>
					<div class="overview_previous">
						<p class="overview_day"><?php echo  lang('g_yesterday')?></p>
						<p style="height:8px;"><br /></p>
						<p class="overview_count"><?php echo isset($yestoday_newuser)?$yestoday_newuser:0;?></p>
						<p style="height:6px;"><br /></p>
						<p class="overview_type"><?php echo  lang('t_newUsers')?></p>
						<p style="height:8px;"><br /></p>
						<p class="overview_count"><?php echo isset($yestoday_startuser)?$yestoday_startuser:0;?></p>
						<p style="height:6px;"><br /></p>
						<p class="overview_type"><?php echo  lang('t_activeUsers')?></p>
						<p style="height:8px;"><br /></p>
						<p class="overview_count"><?php echo isset($yestoday_startcount)?$yestoday_startcount:0;?></p>
						<p style="height:6px;"><br /></p>
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
					<th></th>
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
					<td><input type="checkbox" name="pid" value="<?php echo $row['id'];?>"/></td> 
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
			<div style="margin:5px">
				<input type="submit" value="<?php echo lang('c_compare_product')?>" name="compareButton" class="alt_btn" id="submit" onclick="compareProduct();"/>
				<span style="padding:10px;vertical-align: middle;"><?php echo lang('c_compare2two4')?></span>
			</div>
			</div><!-- end of #tab1 -->
			
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->	
		<div class="clear"></div>
		<div class="spacer"></div>		
		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
<script type="text/javascript">
var chart;
var options;
var chartName="<?php echo  lang('t_newUsers')?>";
var newUserData = [];
var newUserData1 = [];
var newUserData2 = [];
var activeUserData = [];
var activeUserData1 = [];
var activeUserData2 = [];
var sessionData = [];
var sessionData1 = [];
var sessionData2 = [];

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
	        credits:{
				enabled:false
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
		var objoftrend = data.contentofTrend;		
	    for(var i=0;i<obj.length;i++)
	    {		   
	    	newUserData.push(parseInt(obj[i].newusers,10));
	    	newUserData1.push(parseInt(objoftrend[i].newusers,0));
	    	
	    	activeUserData.push(parseInt(obj[i].startusers,10));	
	    	activeUserData1.push(parseInt(objoftrend[i].startusers,0));
	    		   
	    	sessionData.push(parseInt(obj[i].sessions,10));		  
	    	sessionData1.push(parseInt(objoftrend[i].sessions,0));
	    	
	    	categories.push(obj[i].datevalue);
	    }
	   
	    options.series[0]={};
		options.series[0].data = newUserData;
		options.series[1]={};
		options.series[1].data = newUserData1;
		options.series[1].dashStyle='shortdot';
		options.xAxis.labels.step = parseInt(categories.length/10);
		options.xAxis.categories = categories;  
		options.title.text = "<?php echo $reportTitle['newUser'] ?>";
		options.series[0].name = chartName;
		options.series[1].name = "<?php echo lang('V_Trendvalue')?>";
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
		options.series[1].data = newUserData1;
		options.title.text = "<?php echo $reportTitle['newUser'] ?>";
		options.series[0].name = chartName;
		options.series[1].name = '<?php echo lang('V_Trendvalue')?>';
		chart = new Highcharts.Chart(options);
	}

	if(chartName=='<?php echo  lang('t_activeUsers')?>')
	{
		options.series[0].data = activeUserData;
		options.series[1].data = activeUserData1;
		options.title.text = "<?php echo $reportTitle['activeUser'] ?>";
		options.series[0].name = chartName;
		options.series[1].name = '<?php echo lang('V_Trendvalue')?>';
		chart = new Highcharts.Chart(options);
	}
	if(chartName=='<?php echo  lang('t_sessions')?>')
	{
		options.series[0].data = sessionData;
		options.series[1].data = sessionData1;
		options.title.text = "<?php echo $reportTitle['session'] ?>";
		options.series[0].name = chartName;
		options.series[1].name = '<?php echo lang('V_Trendvalue')?>';
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
<script type="text/javascript">
	//compare product
$(function(){
	if($('input[name=pid]').length<1){
		$("input[name=compareButton]").attr({disabled:"disabled"});
	}
});
function compareProduct(){
	var pids=new Array();
	$('input[name=pid]').each(function(index,item){
		if($(this).attr('checked')=='checked'){
			pids.push($(this).val());
			}
		});
	
	if(pids.length>4||pids.length<=1){alert('<?php echo lang('c_compare2two4')?>');return;}
	$('input[name=compareButton]').ajaxStart(function(){$(this).attr({disabled:'disabled'});});
	$.ajax({
		type:'post',
		url:'<?php echo site_url()?>/compare/compareProduct',
		data:{'pids':pids},
		dataType:'json',
		success:function(data,status){
			if('ok'==data){
				location.href='<?php echo site_url()?>/compare/compareProduct/compareConsole';
				return;
				}
			$('input[name=compareButton]').removeAttr('disabled');
			}
		});
}
</script>


	
