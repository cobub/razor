<section id="main" class="column">
		
<h4 class="alert_info" id='msg' style="display: none;"></h4>
		
	<article class="module width_full" >
	<header>
	<h3 class="h3_fontstyle">		
	<?php  echo lang('getui_data'); ?></h3>
			<ul class="tabs2">
				<li><a id='newuser'
					href="javascript:chooseType('user')"><?php echo lang('getui_newuser');?></a></li>
				<li><a id='activeuser'
					href="javascript:chooseType('online')"><?php echo lang('getui_online');?></a></li>
				<li><a id='session'
					href="javascript:chooseType('push')"><?php echo lang('getui_push');?></a></li>
				<li><a id='avgusage'
					href="javascript:chooseType('receive')"><?php echo lang('getui_recive');?></a></li>
				<li><a id='weekrate'
					href="javascript:chooseType('click')"><?php echo lang('getui_click');?></a></li>
				
		</header>
		<div class="module_content">
			<div id="container" class="module_content" style="height: 300px"></div>
		</div>
		<input type="hidden" id='appid' name="appid" value="<?php echo $appid?>" />
	</article>

		
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?echo lang('getui_applist');?></h3>
		
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php  echo lang('getui_appname');?></th> 
    				<th><?php echo lang('getui_option')?></th> 
				</tr> 
			</thead> 
			<tbody>	
						<?php    
						for ($i=0; $i < count($arr); $i++) { 
							$app = $arr[$i][0]; ?>
						<tr>
						    <td><?php echo $app['name'];?></td>
							<td><?php echo anchor('/plugin/getui/report/?type=user&id='.$app['id'], lang('getui_view'));?></td>
						</tr>

						<?php }?>

				</tbody>
			</table>
			</div><!-- end of #tab1 -->
	
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->

		<div class="clear"></div>
		<div class="spacer"></div>
	</section>


<script type="text/javascript">
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("ct"); //Find the href attribute value to identify the active tab + content
	$('#'+activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
</script>

<script type="text/javascript">
var chart;
var options;
var type="user";
var optionsLength=0;
var markEventIndex=[];//save all markevent series index
var  allusers= new Array();
var category=[];
var tooltipmarkevent=[];
var tooltipdata=new Array(new Array(),new Array());
var tooltipname=[];
var colors=['#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', '#DB843D', '#92A8CD', 
            '#A47D7C', '#B5CA92', '#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', 
            '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92'];
$(document).ready(function() {	
	options = {
		            chart: {
		                renderTo: 'container',
		                type: 'spline'
		            },
		            title: {
		                text: ''
		            },
		            subtitle: {
		                text: ''
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
		                        return Highcharts.numberFormat(this.value, 0);
		                    }
		                }
		            },
			        credits:{
						enabled:false
				        },
				    tooltip: {
				        crosshairs: true,
				        shared:false,
				        
				    },
		            plotOptions: {
			            column: {
	                    showInLegend:false
	                	},
		                spline: {
		                    marker: {
		                        radius: 1,
		                        lineColor: '#666666',
		                        lineWidth: 1
		                    }
		                },series:{
		                	cursor:'pointer'
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
	 
 var appid=document.getElementById('appid').value;
	var myurl="<?php echo site_url();?>/plugin/getui/report/getdata/?type="+type+"&appid="+appid;	
	// alert(myurl);
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
      	 	 // alert(data.dataList[0].date);
      	 	 // alert(data);
      	 	       var d =data.headList.length;
          	 	    for(var i=d-1;i<data.headList.length;i++){
          	 	    	var appdata =[];
          	 	    	var categories=[];

          	 	    	options.series[0] = {};
          	 	    	options.series[0].name =data.headList[i];
          	 	    	for(var j=data.dataList.length-1;j>=0;j--){
          	 	    		categories.push(data.dataList[j].date);
          	 	    		
          	 	    			appdata.push(parseInt( data.dataList[j].datas[i],10));
          	 	    		
          	 	    	}
          	 	    options.series[0].data = appdata;
          	 	  
					options.xAxis.labels.step = parseInt(categories.length/10);
					options.xAxis.categories = categories; 
					<!--options.title.text = <?php echo lang('getui_data');?>;-->
          	 	    }
          	 	    chart = new Highcharts.Chart(options);
          		chart_canvas.unblock();
          		});  
    }
  	    
</script>
<script type="text/javascript">

    function chooseType(typename)
    {     
        type=typename;    	
        var appid=document.getElementById('appid').value;
    	myurl="<?php echo site_url();?>/plugin/getui/report/getdata/?type="+type+"&appid="+appid;	
    	renderCharts(myurl);
    	 
    }
</script>


