<section class="section_maeginstyle" id="highchart"
<?php if(!isset($delete)) {?>
style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;"<?php }?>>
	<article class="module width_full">
		<header>
 <div style="float:left;margin-left:2%;margin-top: 5px;">
	<?php   if(isset($add))
  {?>
  <a href="#" onclick="addreport()">
	<img src="<?php echo base_url();?>assets/images/addreport.png" title="<?php echo lang('s_suspend_title')?>" style="border:0"/></a>
<?php }if(isset($delete)){?>
 <a href="#" onclick="deletereport()">
	<img src="<?php echo base_url();?>assets/images/delreport.png" title="<?php echo lang('s_suspend_deltitle')?>" style="border:0"/></a>
	<?php }?>
  </div>
	<h3  class="h3_fontstyle">
	<?php echo  lang('v_rpt_ud_distribution')?></h3>			
		</header>
			<div id="container" class="module_content" style="height: 400px"></div>
	</article>
</section>

<script type="text/javascript">
var chart;
var options;
var productName=[];
var percentData=[];
var k=0;
var m=0;

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
	            xAxis:{
		                categories: [],
		                title: {
		                    text: ''
		                },
		                tickmarkPlacement: 'on'
	            	},
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
		        credits:{
					enabled:false
			        },
	            tooltip: {
	                formatter: function() {
	                    return ''+
	                        this.series.name +': '+ this.y +' ';
	                }
	            },
	            plotOptions: {
	            	bar : {
	    				dataLabels : {
	    					enabled : true,
	    					formatter : function() {
		    					var displayNum=productName.length;
		    					var averageNum=percentData.length/displayNum;
		    					if(m>=averageNum){
			    					for(i=0;i<displayNum;i++){
				    					if(this.series.name==productName[i]){
					    					k=i*percentData.length/displayNum;
					    					m=0;
					    					break;
				    					}
			    					}
		    					}
		    					m++;
	    						return '' + this.y+'('+percentData[k++]+')';
	    					}
	    				}
	    			}
	            },
	            legend: {
	            	enabled : true
	            },
		        series: [
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
	    	productName=[]; 
	    	percentData=[];
	    	var j=0;
	    	k=0;
			m=0;
			for (var key in data) {
				productName.push(key);
				var obj=data[key];
				var categories=[];
				var chartData=[];
				for(i=0;i<obj.length;i++)
				{
					categories.push(obj[i].segment_name);
					chartData.push(parseInt(obj[i].numbers));
					percentData.push(Highcharts.numberFormat(obj[i].percentage*100,1) +' %');
				}
				options.series[j]={};
				options.series[j].data = chartData;
				options.series[j].name=key;
				options.xAxis.categories = categories;
				j++;
	    	}
			if(j==1){
	    		options.legend.enabled=false;
	    	}
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
<script type="text/javascript">
function addreport()
{	
	if(confirm( "<?php echo  lang('w_isaddreport')?>"))
	{
		var reportname="usadgeduration";
	    var reportcontroller="usetime";
	    var data={ 
	    		 reportname:reportname,
  		  	     controller:reportcontroller,
	  		  	 height    :500,
	  		  	 type      :1,
	  		  	 position  :0
		  	     };
		jQuery.ajax({
						type :  "post",
						url  :  "<?php echo site_url()?>/report/dashboard/addshowreport",	
						data :  data,			
						success : function(msg) {
							if(msg=="")
							{
								alert("<?php echo lang('w_addreportrepeat') ?>");
							}
							else if(msg>=8)
							{
								alert("<?php echo  lang('w_overmaxnum');?>");
							}
							else
							{
								 alert("<?php echo lang('w_addreportsuccess') ?>");	
							}
									 
							},
							error : function(XmlHttpRequest, textStatus, errorThrown) {
								alert(<?php echo lang('t_error') 	; ?>);
							}
					});
		
	}
}

function deletereport()
{ 
	if(confirm( "<?php echo  lang('v_deletreport')?>"))
	{
		window.parent.deletereport("usadgeduration");	 	 	
	}
	return false;
	
}
</script>