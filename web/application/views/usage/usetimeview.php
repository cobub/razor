<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('v_rpt_ud_distribution')?></h3>			
		</header>
			<div id="container" class="module_content" style="height: 400px"></div>
	</article>
</section>

<script type="text/javascript">
var chart;
var options;

$(document).ready(function() {
	options = {
			 chart: {
	                renderTo: 'container',
	                type: 'bar'
	            },
	            title: {
	                text: ''
	            },
	            subtitle: {
	                text: ''
	            },
	            xAxis: [
	            	{
		                categories: [],
		                title: {
		                    text: ''
		                },
		                tickmarkPlacement: 'on'
	            	},
	            	{
	        			categories : [],
	        			title : {
	        				text : null
	        			},
	        			labels: {
	        				formatter: function() {
	        					return this.value;
	        				}
	        			},
	        			opposite: true
	        		}
	            ],
	            yAxis: {
	                min: 0,
	                title: {
	                    text: '',
	                    align: 'high'
	                },
	                labels: {
	                    overflow: 'justify'
	                }
	            },
	            tooltip: {
	                formatter: function() {
	                    return ''+
	                        this.series.name +': '+ this.y +' ';
	                }
	            },
	            plotOptions: {
	            	bar : {
	    				pointWidth:20,
	    				dataLabels : {
	    					enabled : true,
	    					formatter : function() {
	    						return '' + this.y;
	    					}
	    				}
	    			},
	    			scatter : {
	    				marker : {
	    					enabled : false
	    				}
	    			}
	            },
	            legend: {
	            	enabled : false
	            },
	            credits: {
	                enabled: false
	            },
		        series: [{	 
			        		xAxis:0,           
		          			name:'<?php echo lang("v_rpt_ud_distribution");?>'
		        		},
				        {
		        			type: 'scatter',
							xAxis:1,
							name:''
						}
		        ]
	    };
    
	var usingTimeDataUrl  = "<?php echo site_url();?>/report/usetime/getUsingTimeData";
	renderCharts(usingTimeDataUrl);
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
		var obj = data.usingTimeData;
		var categories = [];
		var chartData = [];
		var percentData = [];
		for(i=0;i<obj.length;i++)
		{
			categories.push(obj[i].segment_name);
			chartData.push(parseInt(obj[i].numbers));
			percentData.push(Highcharts.numberFormat(obj[i].percentage*100,1) +' %');
		}
		
		options.series[0].data = chartData;
		options.series[1].data = chartData;
		options.xAxis[0].categories = categories;
		options.xAxis[1].categories = percentData;
		options.title.text = '<?php echo $reportTitle['reportName'] ?>';
		options.subtitle.text = '<?php echo $reportTitle['timePase'] ?>';
		chart = new Highcharts.Chart(options);
		chart_canvas.unblock();
		});  
}

$(".tab_content").hide(); 
$("ul.tabs2 li:first").addClass("active").show(); 
$(".tab_content:first").show(); 

$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active");
	$(this).addClass("active");
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});

</script>
