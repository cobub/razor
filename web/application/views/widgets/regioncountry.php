<section class="section_maeginstyle" id="highchart"
<?php if(!isset($delete)) {?>
style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;"<?php }?>>
	<!-- Country -->
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
 <?php echo lang('v_rpt_re_top10Nations') ?></h3>
			<ul class="tabs2">
				<li><a ct="activeuser"
					href="javascript:changeReportType('activeuser')"><?php echo lang('t_activeUsers') ?></a></li>
				<li><a ct="newuser" href="javascript:changeReportType('newuser')"><?php echo lang('t_newUsers') ?></a></li>
			</ul>
		</header>
		<div id="container" class="module_content" style="height: 400px"></div>
	</article>
</section>

<script type="text/javascript">
var chart;
var options;

var countryActiveUserData =[];
var countryActiveUserCategories=[];
var countryActiveUserPercentCategories=[]; 

var countryNewUserData=[];
var countryNewUserCategories=[];
var countryNewUserPercentCategories=[]; 
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
	var countryDataURL  = "<?php echo site_url();?>/report/region/getCountryData";
	renderCountryCharts(countryDataURL);	
});

function renderCountryCharts(myurl)
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
		      
			var obj = data.activeUserData;
			for(i=0;i<obj.length;i++)
			{
				countryActiveUserCategories.push(obj[i].country);
				countryActiveUserData.push(parseInt(obj[i].access));
				countryActiveUserPercentCategories.push(Math.round(obj[i].percentage*1000)/10);
			}
	
			var objNewUserData = data.newUserData;
			for(i=0;i<objNewUserData.length;i++)
			{
				countryNewUserCategories.push(objNewUserData[i].country);
				countryNewUserData.push(parseInt(objNewUserData[i].access));
				countryNewUserPercentCategories.push(Math.round(objNewUserData[i].percentage*1000)/10);
			}
			
			changeReportType('activeuser');
			chart_canvas.unblock();
		});  
}
function changeReportType(reportType)
{
	if(reportType == "activeuser")
	{
		options.chart.renderTo = "container";
		options.series[0].data = countryActiveUserData;
		options.series[1].data = countryActiveUserData;
		options.xAxis[0].categories = countryActiveUserCategories;
		options.xAxis[1].categories = countryActiveUserPercentCategories;
		options.title.text = '<?php echo $reportTitle['activeUserReport'] ?>';
		options.subtitle.text = '<?php echo $reportTitle['timePhase'] ?>';
		chart = new Highcharts.Chart(options);
	}

	if(reportType == "newuser")
	{ 
		options.chart.renderTo = "container";
		options.series[0].data = countryNewUserData;
		options.series[1].data = countryNewUserData;
		options.xAxis[0].categories = countryNewUserCategories;
		options.xAxis[1].categories = countryNewUserPercentCategories;
		options.title.text = '<?php echo $reportTitle['newUserReport'] ?>';
		options.subtitle.text = '<?php echo $reportTitle['timePhase'] ?>';
		chart = new Highcharts.Chart(options);
	}
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
		var reportname="regioncountry";
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
		window.parent.deletereport("regioncountry");		 	
	}
	return false;
	
}
</script>