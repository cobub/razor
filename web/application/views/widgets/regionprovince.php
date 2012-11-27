<section class="section_maeginstyle" id="highchart" 
<?php if(!isset($delete)) {?>
style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;"<?php }?>>
	<!--Region -->
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
   <h3 class="h3_fontstyle">
	<?php  echo lang('v_rpt_re_top10Provinces') ?></h3>
			<ul class="tabs3">
				<li><a ct="activeuser"
					href="javascript:changeReportType('regionactiveuser')"><?php echo lang('t_activeUsers') ?></a></li>
				<li><a ct="newuser" href="javascript:changeReportType('regionnewuser')"><?php echo lang('t_newUsers') ?></a></li>
			</ul>
   </header>
		<div id="containerRegion" class="module_content" style="height: 400px"></div>
	</article>	
</section>

<script type="text/javascript">
var chartRegion;
var options;

var regionActiveUserData =[];
var regionActiveUserCategories=[];
var regionActiveUserPercentCategories=[]; 

var regionNewUserData=[];
var regionNewUserCategories=[];
var regionNewUserPercentCategories=[]; 

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
	                    text: null
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
      					return this.value +'%';
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
	          			name:'<?php echo lang('v_rpt_re_count') ?>'
	        		},
			        {
	        			type: 'scatter',
						xAxis:1,
						name:''
					}
	        ]
  };

  
$(document).ready(function() {	
	var regionDataURL  = "<?php echo site_url();?>/report/region/getRegionData";
	renderRegionCharts(regionDataURL)
});


function renderRegionCharts(myurl)
{	
	 var chart_canvas = $('#containerRegion');
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
		      
			var obj = data.regionActiveUserData;
			for(i=0;i<obj.length;i++)
			{
				regionActiveUserCategories.push(obj[i].region);
				regionActiveUserData.push(parseInt(obj[i].access));
				regionActiveUserPercentCategories.push(Math.round(obj[i].percentage*1000)/10);
			}
	
			var objNewUserData = data.regionNewUserData;
			for(i=0;i<objNewUserData.length;i++)
			{
				regionNewUserCategories.push(objNewUserData[i].region);
				regionNewUserData.push(parseInt(objNewUserData[i].access));
				regionNewUserPercentCategories.push(Math.round(objNewUserData[i].percentage*1000)/10);
			}
			
			changeReportType('regionactiveuser');
			chart_canvas.unblock();
		});  
}

function changeReportType(reportType)
{
	if(reportType == "regionactiveuser")
	{
		options.chart.renderTo = "containerRegion";
		options.series[0].data = regionActiveUserData;
		options.series[1].data = regionActiveUserData;
		options.xAxis[0].categories = regionActiveUserCategories;
		options.xAxis[1].categories = regionActiveUserPercentCategories;
		options.title.text = '<?php echo $reportTitle['regionActiveUserReport'] ?>';
		options.subtitle.text = '<?php echo $reportTitle['timePhase'] ?>';
		chart = new Highcharts.Chart(options);
	}

	if(reportType == "regionnewuser")
	{ 
		options.chart.renderTo = "containerRegion";
		options.series[0].data = regionNewUserData;
		options.series[1].data = regionNewUserData;
		options.xAxis[0].categories = regionNewUserCategories;
		options.xAxis[1].categories = regionNewUserPercentCategories;
		options.title.text = '<?php echo $reportTitle['regionNewUserReport'] ?>';
		options.subtitle.text = '<?php echo $reportTitle['timePhase'] ?>';
		chart = new Highcharts.Chart(options);
	}
}

$(".tab_content").hide();
$("ul.tabs3 li:first").addClass("active").show(); 
$(".tab_content:first").show(); 

$("ul.tabs3 li").click(function() {
	$("ul.tabs3 li").removeClass("active");
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
		var reportname="regionprovince";
	    var reportcontroller="region";
	    var data={ 
	    		 reportname:reportname,
  		  	     controller:reportcontroller,
	  		  	 height    :480,
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
		window.parent.deletereport("regionprovince");	
	}
	return false;
	
}
</script>