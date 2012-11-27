<section class="section_maeginstyle"  id="highchart" <?php if(!isset($delete)) {?>
style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;"<?php }?>>
	<article class="module width_full" >
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
	<?php echo  lang('v_rpt_op_top10')?></h3>
			<ul class="tabs2">
				<li><a ct="activeuser"
					href="javascript:changeReportType('activeuser')"><?php echo  lang('t_activeUsers')?></a></li>
				<li><a ct="newuser" href="javascript:changeReportType('newuser')"><?php echo  lang('t_newUsers')?></a></li>
			</ul>
	</header>
		<div id="container" class="module_content" style="height: 400px"></div>
	</article>
</section>

<script type="text/javascript">
var chart;
var options;
var newUserData = [];
var activeUserData = [];
var reportTitle = '<?php echo $reportTitle['activeUserReport'] ?>';

$(document).ready(function() {
	options = {
	        chart: {
	            renderTo: 'container'
		 	        },
 	       colors: [
	 	           	'#4572A7', 
	 	           	'#AA4643', 
	 	           	'#89A54E', 
	 	           	'#80699B', 
	 	           	'#3D96AE', 
	 	           	'#DB843D', 
	 	           	'#92A8CD', 
	 	           	'#A47D7C', 
	 	           	'#B5CA92',
		 	        '#058DC7', 
		 	        '#50B432',
		 	        '#ED561B', 
		 	        '#DDDF00',
		 	        '#24CBE5',
		 	        '#64E572', 
		 	        '#FF9655',			 	        
			 	    '#FFF263', 
			 	    '#6AF9C4'
		      ],
	        title: {
	            text: '   '
	        },
	        subtitle:{text:''},
	        tooltip: {
	        	formatter: function () {  
                    return '<b>' + this.point.name + '</b>: ' + Highcharts.numberFormat(this.percentage, 1) + ' %';  
                } 
            },
	        credits:{
				enabled:false
		        },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: 0,
                y: 40,
                floating: false,
                borderWidth: 1,
                backgroundColor: '#FFFFFF'
            },
            
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,1) +' %';
                        }
                    },
            showInLegend: true
                }
            },
	        series: [{	            
	          	type:'pie'
	        }]
	    };
    
	var osDataURL  = "<?php echo site_url();?>/report/operator/getOperatorData";
	renderCharts(osDataURL);	
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

		var obj = data.activeUserData;
		for(i=0;i<obj.length;i++)
		{
			var pieObj = {};
			pieObj.name = obj[i].devicesupplier_name;
			pieObj.sliced = false;
			pieObj.y = obj[i].percentage;
			pieObj.selected = false;
			activeUserData.push(pieObj);
		}

		var objNewUserData = data.newUserData;
		for(i=0;i<objNewUserData.length;i++)
		{
			var pieObj = {};
			pieObj.name = objNewUserData[i].devicesupplier_name;
			pieObj.sliced = false;
			pieObj.y = objNewUserData[i].percentage;
			pieObj.selected = false;
			newUserData.push(pieObj);
		}
		
		options.series[0].data = activeUserData;
		options.title.text = reportTitle;
		options.subtitle.text = '<?php echo $reportTitle['timePhase'];?>';
		chart = new Highcharts.Chart(options);
		
		chart_canvas.unblock();
		});  
}


function changeReportType(reportType)
{
	if(reportType == "activeuser")
	{
		options.series[0].data = activeUserData;
		options.title.text = '<?php echo $reportTitle['activeUserReport'] ?>';
		chart = new Highcharts.Chart(options);
	}

	if(reportType == "newuser")
	{
		options.series[0].data = newUserData;
		options.title.text = '<?php echo $reportTitle['newUserReport'] ?>';
		chart = new Highcharts.Chart(options);
	}
}

$(".tab_content").hide();
$("ul.tabs2 li:first").addClass("active").show(); 
$(".tab_content:first").show();

$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active");
	$(this).addClass("active"); //Add "active" class to selected tab
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
		var reportname="carrier";
	    var reportcontroller="operator";
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
								alert("<?php echo lang('w_addreportrepeat') ;?>");
							}
							else if(msg>=8)
							{
								alert("<?php echo  lang('w_overmaxnum');?>");
							}
							else
							{
								 alert("<?php echo lang('w_addreportsuccess'); ?>");									
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
		window.parent.deletereport("carrier");		 	 	
	}
	return false;
	
}	
</script>
