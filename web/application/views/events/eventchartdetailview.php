<script type="text/javascript">
var event_sk = "<?php echo $event_sk?>";
var version = "<?php echo $event_version?>";
var event_name = "<?php echo $event_name?>";
</script>

<section id="main" class="column">
<article class="module width_full">
<header>
<h3 class="tabs_involved"><font color="#787878"><?php echo $event_name." "?></font><?php echo  lang('v_rpt_el_eventStatistics')?></h3>
<ul class="tabs2">
   	<li><a id='eventnum' href="javascript:changeChartData('<?php echo  lang('v_rpt_el_eventMsgs')?>')"><?php echo  lang('v_rpt_el_eventMsgs')?></a></li>
	<li><a id='eventnumperactiveuser' href="javascript:changeChartData('<?php echo  lang('v_rpt_el_MsgsInActive')?>')"><?php echo  lang('v_rpt_el_MsgsInActive')?></a></li>
	<li><a id='eventnumperstartnum' href="javascript:changeChartData('<?php echo  lang('v_rpt_el_MsgsInSessions')?>')"><?php echo  lang('v_rpt_el_MsgsInSessions')?></a></li>
	
</ul>

</header>
<div class="module_content">
         <div id="container"  class="module_content" style="height:300px">
		</div>
</div>

</div>
</article>


</section>
<script type="text/javascript">
//When page loads...
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
</script>

<script type="text/javascript">
var chart;
var options;
var reportType;
var eventMsgNum=[];
var eventMsgNumActive=[];
var eventMsgNumSession=[];
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
                text: '<?php echo $reportTitle['timePase']; ?>'
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
                        return this.value;
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
	var myurl = "<?php echo site_url().'/report/eventlist/getChartDataAll/'?>"+event_sk+"/"+version;
	renderCharts(myurl);
});

</script>
<script type="text/javascript">
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
        	chartData = data;
    		var newUsers = [];
    		var categories = [];
    	    for(var i=0;i<data.length;i++)
    	    {
    		    var marketData = data[i];    		
    		    eventMsgNum.push(parseFloat(marketData.count));
    		    eventMsgNumActive.push(parseFloat(parseFloat(marketData.userper).toFixed(2)));    	    			
    		    eventMsgNumSession.push(parseFloat(parseFloat(marketData.sessionper).toFixed(2)));    		  
    		    categories.push(marketData.datevalue.substr(0,10));
    		}
			
    		    options.series[0] = {
                    name:event_name
                };
    		    options.series[0].data = eventMsgNum;
    		    options.xAxis.labels.step = parseInt(categories.length/10);
    		    options.xAxis.categories = categories; 
    		    options.title.text = "<?php echo  $reportTitle['eventMsgNum'] ; ?>";
    	        chart = new Highcharts.Chart(options);
    		    chart_canvas.unblock();
    		});  
    }
  	    
</script>

<script type="text/javascript">
function changeChartData(type)
{ 
	reportType=type;
	if(reportType=='<?php echo  lang('v_rpt_el_eventMsgs')?>')
    {
		 options.series[0].data = eventMsgNum;
		 options.title.text = "<?php echo  $reportTitle['eventMsgNum'] ; ?>";
	     chart = new Highcharts.Chart(options);	
    }

    if(reportType=='<?php echo  lang('v_rpt_el_MsgsInActive')?>')
    {
    	options.series[0].data = eventMsgNumActive;
		 options.title.text = "<?php echo  $reportTitle['eventMsgNumActive'] ; ?>";
	     chart = new Highcharts.Chart(options);	
			
    }
    if(reportType=='<?php echo  lang('v_rpt_el_MsgsInSessions')?>')
    {
    	 options.series[0].data = eventMsgNumSession;
		 options.title.text = "<?php echo  $reportTitle['eventMsgNumSession'] ; ?>";
	     chart = new Highcharts.Chart(options);	
			
    }
}
</script>



