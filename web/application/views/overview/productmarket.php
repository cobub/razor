<script type="text/javascript">
var market = 'default';
var time = '7day';
var type = 'new';
var fromTime='';
var toTime='';
var jsondata;
</script>
<section id="main" class="column">
	<!-- <h4 class="alert_success" id='msg'>欢迎使用UMS</h4> -->

	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_mk_channelList') ?></h3>		
		</header>


		<table class="tablesorter" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo lang('v_man_au_channelName') ?></th>
					<th><?php echo lang('v_rpt_mk_newToday') ?></th>
					<th><?php echo lang('v_rpt_mk_newYesterday') ?></th>
					<th><?php echo lang('v_rpt_mk_activeToday') ?></th>
					<th><?php echo lang('v_rpt_mk_activeYesterday') ?></th>
					<th><?php echo lang('t_accumulatedUsers') ?></th>
					<th><?php echo lang('t_activeRateWeekly') ?></th>
					<th><?php echo lang('t_activeRateMonthly') ?></th>
					<!--  th>时段内新增（%）</th>-->

				</tr>
			</thead>
			<tbody>
	<?php 
	$todayDataArray = $todayData->result_array();
	$yestaodayDataArray = $yestodayData->result_array();
	$sevenDayActive = $sevendayactive->result_array();
	$thirtyDayActive = $thirty_day_active->result_array();
//	$today_newuser_array = $today_newuser;
	for ($i=0;$i<$count;$i++)
	{?>
		<tr>
					<td><?php echo $todayDataArray[$i]['channel_name']?></td>
					<td><?php echo $todayDataArray[$i]['newusers']
	?></td>
					<td><?php echo $yestaodayDataArray[$i]['newusers']?></td>
					<td><?php echo $todayDataArray[$i]['startusers']?></td>
					<td><?php echo $yestaodayDataArray[$i]['startusers']?></td>
					<td><?php echo $todayDataArray[$i]['allusers']?></td>
					<td><?php if($todayDataArray[$i]['allusers']==0){echo '0.0%';}else{echo percent($sevenDayActive[$i]['startusers'],$todayDataArray[$i]['allusers']);} 
	?></td>
					<td><?php if($todayDataArray[$i]['allusers']==0){echo '0.0%';}else{echo percent($thirtyDayActive[$i]['startusers'],$todayDataArray[$i]['allusers']);} 
	?></td>
					<!--  td><?php // echo ($new_user_time_phase[$i]*100)."%" ; ?></td>-->
				</tr>
		<?php }?>

	</tbody>
		</table>
	</article>


	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_mk_timeSegmentAnalysis') ?></h3>
			<ul class="tabs2">
				<li><a id='newuser'
					href="javascript:chooseType('newuser')"><?php echo lang('t_newUsers') ?></a></li>
				<li><a id='activeuser'
					href="javascript:chooseType('activeuser')"><?php echo lang('t_activeUsers') ?></a></li>
				<li><a id='session'
					href="javascript:chooseType('sessionnum')"><?php echo lang('t_sessions') ?></a></li>
				<li><a id='avgusage'
					href="javascript:chooseType('avgusage')"><?php echo lang('t_averageUsageDuration') ?></a></li>
				<li><a id='weekrate'
					href="javascript:chooseType('weekrate')"><?php echo lang('t_activeRateWeekly') ?></a></li>
				<li><a id='monthrate'
					href="javascript:chooseType('monthrate')"><?php echo lang('t_activeRateMonthly') ?></a></li>

			</ul>
		</header>


		<div class="module_content">
			<div id="container" class="module_content" style="height: 300px"></div>
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
var type="newuser";
var  allusers= new Array();
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
		                        return Highcharts.numberFormat(this.value, 0);
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
	var myurl="<?php echo site_url();?>/report/market/getMarketData/"+type;	
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
          		var data_array =[];
          		for(var key in data.content)
              	{
              		data_array.push(key);              		
          		}
          		for(var j=0;j<data_array.length;j++)
          	    {          		
          			var reportData=[];    
          			var reportTitle;      			
          		    var marketData = data_array[j];          		   
          		    var eachmarketdata = data.content[marketData];          		   
          		    var categories = [];
          		    for(var i=0;i<eachmarketdata.length;i++)
          		    {
              		           var eachdata = eachmarketdata[i];                 		    
                		    	 if(type=='newuser')
                   		    	{
                		    		 reportData.push(parseFloat(eachdata.newusers,10)); 
                		    		 reportTitle="<?php echo $reportTitle['newUser'] ?>";     
                   		    	}

                   		    	if(type=='activeuser')
                   		    	{
                      		    	 reportData.push(parseFloat(eachdata.activeusers,10));
                       		    	 reportTitle="<?php echo $reportTitle['activeUser'] ?>";       
                   		    	}
                   		    	if(type=='sessionnum')
                   		    	{
                      		    	 reportData.push(parseFloat(eachdata.sessions,10));  
                       		    	reportTitle="<?php echo $reportTitle['Session'] ?>";
                   		    	}
                   		    	if(type=='avgusage')
                   		    	{
                   		    		var average ;
                            		if(eachdata.sessions==0)
                                	{
                                		average = 0;
                            		}
                            		else
                                	{
                 						average = (eachdata.usingtime*1.0/eachdata.sessions)/1000;
                                	}
                            		reportData.push(parseFloat(parseFloat(average,10).toFixed(2)));
                            		reportTitle="<?php echo $reportTitle['avgUsageDuration'] ?>";
                   		    	}

                   		    	if(type=='weekrate')                       		    	
                   		    	{  
                       		    		allusers[i]=eachdata.allusersacc;
                    					var weekrealuser;
                    					if(allusers[i]==0)
                    		           	{
                    		       			weekrealuser = 0;
                    		       		}
                    		       		else
                    		           	{
                    		       			weekrealuser = eachdata.startusers*100.0/allusers[i];
                    		           	}
                    				reportData.push(parseFloat(parseFloat(weekrealuser,10).toFixed(1)));                       			
                   		    		reportTitle="<?php echo $reportTitle['activeWeekly'] ?>";
                   		    	} 

                       		 	if(type=='monthrate')                       		    	
                   		    	{
                           		 	allusers[i]=eachdata.allusersacc;
                           		 	var monthrealuser;
                					if(allusers[i]==0)
                		           	{
                						monthrealuser = 0;
                		       		}
                		       		else
                		           	{
                		       			monthrealuser = eachdata.startusers*100.0/allusers[i];
                		           	}
                				    reportData.push(parseFloat(parseFloat(monthrealuser,10).toFixed(1)));    
                              	    reportTitle="<?php echo $reportTitle['activeMonthly'] ?>";
                   		    	}  
                   			
                   		    categories.push(eachdata.datevalue.substr(0,10));  
          		    }
            		 options.series[j]={};
           			if(marketData=="")
           				{
           				options.series[j].name="<?php echo lang('t_unknow');?>";
           				}
           			else
           				{
           				  options.series[j].name=marketData;
           				}     
       				 	    			  
           				 options.series[j].data = reportData;
           				 options.title.text = reportTitle;
            			 options.xAxis.labels.step = parseInt(categories.length/10);
                  		 options.xAxis.categories = categories;        			   		         			  		   
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
    	myurl="<?php echo site_url();?>/report/market/getMarketData/"+type;    	
    	renderCharts(myurl);
    	 
    }
</script>