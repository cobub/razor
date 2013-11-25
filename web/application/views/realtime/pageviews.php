<script src="<?php echo base_url();?>assets/js/jquery.easyui.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datagrid-detailview.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/jquery.easyui.css"></link>

<section id="main" class="column"  style="height:1600px">
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_realtime_pageviews_title') ?>  </h3>
		</header>
    	<div id="container"  class="module_content" style="height:300px"></div>
		<footer>
		</footer>
	</article>
	
	
	<article class="module width_full">
		<table id="dg" class="width_full" 
             url="<?php echo site_url();?>/realtime/pageviews/getActivities/<?php echo $productId?>"
            pagination="false" sortName="event" sortOrder="desc"
            title="<?php echo lang('v_rpt_realtime_pageviews_table_tile');?>"
            singleSelect="true" fitColumns="true" border="false">
        <thead  >
            <tr >
                <th field="name" width="100%"><?php echo lang('v_rpt_realtime_pageview');?></th>
                <th field="size" width="100%"><?php echo lang('v_rpt_realtime_pageview_count');?></th>
            </tr>
        </thead>
    </table>
    <footer>
		</footer>
	</article>
</section>
<script type="text/javascript">
$(function(){
    $('#dg').datagrid({
        detailFormatter:function(index,row){
            return '<div id="ddv-' + index + '" style="padding:5px 0"></div>';
        }
    });
});

function flashTable(){ 
    $("#dg").datagrid("reload"); 
} 
</script>

<script type="text/javascript">
var options;

var myurl = "<?php echo site_url()?>/realtime/pageviews/getActivityByMinutes/<?php echo $productId?>";
$(document).ready(function() {
    window.setInterval(flashTable,30000);
	options = {
            chart: {
                renderTo: 'container',
                type:'spline'
            },
            title: {
                text: '   '
            },
            subtitle: {
                text: ' '
            },
            xAxis: {
            	categories:'',
           	    labels:{rotation:320,y:40,x:0}
            },
            yAxis: {
                title: { text:''},
                min:0
            },
            plotOptions: {
                column: {
                    pointPadding: 0.3,
                    borderWidth: 0
                }
            },
            tooltip: {
                formatter: function() {
                    return this.series.name +': '+ this.y +'<br>'+'<?php echo lang("v_rpt_realtime_onlineuser_time");?>'+':'+this.x;
                }
            },
            credits: {
                enabled: false
            },
            series: [
                {
  	             data:''
                }
            ]
        };
	renderCharts();
	window.setInterval(getdata,30000);
	
});
var chart_canvas = $('#container');
    function renderCharts()
    {		
    	 
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
    	   getdata();
    	 
    } 

    function getdata()
    {
    	var onlineUsersData = [];
    	jQuery.getJSON(myurl, null, function(data) { 
    		if(data==null)
        	{
        		chart_canvas.unblock();
        		return;
            }
    		var obj = data;
    		if(obj==null)
    		{
        		chart_canvas.unblock();
        		return;
            }
    		var categories = [];
    	    for(var j=0;j<obj.length;j++)
    	    {
            	
    	    	onlineUsersData.push(parseInt(obj[j].size));
            	//errorCountPerSessionData.push(parseFloat(obj[j].percentage));
		    	categories.push(obj[j].minutes);
    	    }
    	    
   		    options.series[0].data = onlineUsersData;
			options.xAxis.labels.step = parseInt(categories.length/10);
      		options.series[0].name = '<?php echo lang("v_rpt_realtime_pageview_count");?>';
			options.xAxis.categories = categories;
			options.yAxis.allowDecimals=false; 
			options.title.text = '<?php echo $reportTitle['title'] ?>';
			options.subtitle.text = '<?php echo $reportTitle['subtitle'];?>';
    	    chart = new Highcharts.Chart(options);
    		chart_canvas.unblock();
    		}); 
        }
</script>