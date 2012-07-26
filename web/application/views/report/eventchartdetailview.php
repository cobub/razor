<?php 
if (!isset($from))
{
    $to = date('Y-m-d',time());
    $from = date('Y-m-d',strtotime("-7 day"));
}

?>
<script type="text/javascript">
var time=<?php echo isset($timetype)?'"'.$timetype.'"':'"'."7day".'"'?>;
var fromTime=<?php echo isset($from)?'"'.$from.'"':'""'?>;
var toTime=<?php echo isset($to)?'"'.$to.'"':'""'?>;
var event_sk = "<?php echo $event_sk?>";
var version = "<?php echo $event_version?>";
var event_name = "<?php echo $event_name?>";
var datetype = "eventnum";
var chartData;
var titlename;
</script>

<section id="main" class="column">
<h4 class="alert_success" id='msg'><font color="#787878"><?php echo $event_name." "?></font><?php echo  lang('eventchartdetail_headeinfo')?>
<div class="submit_link" style="margin-top:-8px">
<select onchange=selectChange(this.value)
	id='select'>
	<option value='7day'><?php echo  lang('allview_lastweek')?></option>
	<option value='1month'><?php echo  lang('allview_lastmonth')?></option>
	<option value='3month'><?php echo  lang('allview_last3month')?></option>
	<option value='all'><?php echo  lang('allview_alltime')?></option>
	<option value='any'><?php echo  lang('allview_anytime')?></option>
</select>
<div id='selectTime'>
    <input type="text" id="dpFrom"> 
    <input type="text"	id="dpTo"> 
	<input type="submit" id='btn' value="<?php echo  lang('allview_timebtn')?>" class="alt_btn"	onclick="onBtn()">
</div>
</div>
</h4>


<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo  lang('eventchartdetail_sumheadeinfo')?></h3>
<ul class="tabs2">
   	<li><a id='eventnum' href="javascript:chooseType('eventnum')"><?php echo  lang('eventchartdetail_eventnum')?></a></li>
	<li><a id='eventnumperactiveuser' href="javascript:chooseType('eventnumperactiveuser')"><?php echo  lang('eventchartdetail_eventnactive')?></a></li>
	<li><a id='eventnumperstartnum' href="javascript:chooseType('eventnumperstartnum')"><?php echo  lang('eventchartdetail_evenstart')?></a></li>
	
</ul>

</header>
<div class="module_content">
         <div id="container"  class="module_content" style="height:300px">
		</div>
</div>
<footer></footer>
</div>




</article>


</section>
<script type="text/javascript">
//这里必须最先加载
    document.getElementById('select').value= time;
    if(time=='any')
    {
    	document.getElementById('dpFrom').value = fromTime;
    	document.getElementById('dpTo').value = toTime;

    }
</script>
<script type="text/javascript">
dispalyOrHideTimeSelect();
$(function() {
	$("#dpFrom" ).datepicker();
});
$( "#dpFrom" ).datepicker({ dateFormat: "yy-mm-dd" });
$(function() {
	$( "#dpTo" ).datepicker();
});
$( "#dpTo" ).datepicker({ dateFormat: "yy-mm-dd" });
//When page loads...
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$("ul.tabs3 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
$("ul.tabs3 li").click(function() {
	$("ul.tabs3 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
function onBtn()
{  
	time='any';
	fromTime = document.getElementById('dpFrom').value;
	toTime = document.getElementById('dpTo').value;
	getdata();
}
function dispalyOrHideTimeSelect()
{
	 var value = document.getElementById('select').value;
	 if(value=='any')
	 {
		 document.getElementById('selectTime').style.display="inline";
	 }
	 else
	 {			 
		 document.getElementById('selectTime').style.display="none";
	 } 
}

function selectChange(value)
{
    if(value=='any')
    {
   	   changeChartTitleName(time,datetype);
        time='any';
    }	
    else
    {
   	   changeChartTitleName(time,datetype);
        time=value;
        getdata();
    }
   
    dispalyOrHideTimeSelect();           
}

function chooseType(type)
{
	datetype = type;

	changeChartTitleName(time,type);
	changeChartData(datetype);
}

function getdata()
{
	var myurl;
    changeChartTitleName(time,datetype);
		if(time=='any')
			myurl = "<?php echo site_url().'/report/eventlist/getChartDataAll/'?>"+event_sk+"/"+version+"/"+time+"/"+fromTime+"/"+toTime;
		else
			myurl = "<?php echo site_url().'/report/eventlist/getChartDataAll/'?>"+event_sk+"/"+version+"/"+time;
			
	
	
	renderCharts(myurl);
}

</script>

<script type="text/javascript">
var chart;
var options;
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
                text: ' '
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
	getdata();
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
    		    
    		    if(datetype=='eventnum')
    		    {
        	    		newUsers.push(parseFloat(marketData.count));
            			
    		    }

    		    if(datetype=='eventnumperactiveuser')
    		    {
    		    		newUsers.push(parseFloat(marketData.userper));
    	    			
    		    }
    		    if(datetype=='eventnumperstartnum')
    		    {
    		    		newUsers.push(parseFloat(marketData.sessionper));
    	    			
    		    }

    		    
    		    	categories.push(marketData.datevalue.substr(0,10));
    		}
			
    		    options.series[0] = {
                    name:event_name
                };
//                if(datetype=="eventnum")
//    		        options.title.text = '事件消息数量';
//                if(datetype=="eventnum")
//        		    options.title.text = '事件数量/活跃用户';
    		    options.series[0].data = newUsers;
    		    options.xAxis.labels.step = parseInt(categories.length/10);
    		    options.xAxis.categories = categories; 
    		    options.title.text = titlename;
    	        
    	    chart = new Highcharts.Chart(options);
    		chart_canvas.unblock();
    		});  
    }
  	    
</script>

<script type="text/javascript">
function changeChartData(type)
{ 
	var data = chartData;
	//alert(data);

	var newUsers = [];
	var categories = [];
    for(var i=0;i<data.length;i++)
    {
	    var marketData = data[i];
	    
	    if(type=='eventnum')
	    {
	    		newUsers.push(parseFloat(marketData.count));
    			
	    }
	    if(type=='eventnumperactiveuser')
	    {
	    		newUsers.push(parseFloat(marketData.userper));
    			
	    }
	    if(type=='eventnumperstartnum')
	    {
	    		newUsers.push(parseFloat(marketData.sessionper));
    			
	    }
	    	categories.push(marketData.datevalue.substr(0,10));
	}
	
	    options.series[0] = {
            name:event_name
        };
//        if(datetype=="eventnum")
//	        options.title.text = '事件消息数量';
//        if(datetype=="eventnum")
//		    options.title.text = '事件数量/活跃用户';
	    options.series[0].data = newUsers;
	    options.xAxis.labels.step = parseInt(categories.length/10);
	    options.xAxis.categories = categories; 
	    options.title.text = titlename;
        
    chart = new Highcharts.Chart(options);
	

}

function changeChartTitleName(timephase,chartname){
	if (timephase == "7day") {
		if(chartname=="eventnum"){
			titlename = "<?php echo lang('eventchartdetail_eventnum7days') ?>";
		}
		if(chartname=="eventnumperactiveuser"){
			titlename ="<?php echo lang('eventchartdetail_eventactiveuser7days')?>" ;
			
		}
		if(chartname=="eventnumperstartnum"){
			titlename ="<?php echo lang('eventchartdetail_eventstartnum7days')?>" ;
			
		}
	}
	if (timephase == "1month") {
		if(chartname=="eventnum"){
			titlename = "<?php echo lang('eventchartdetail_eventnum30days') ?>";
		}
		if(chartname=="eventnumperactiveuser"){
			titlename ="<?php echo lang('eventchartdetail_eventactiveuser30days')?>" ;
			
		}
		if(chartname=="eventnumperstartnum"){
			titlename ="<?php echo lang('eventchartdetail_eventstartnum30days')?>" ;
			
		}
	}	
	if (timephase == "3month") {
		if(chartname=="eventnum"){
			titlename = "<?php echo lang('eventchartdetail_eventnum3month') ?>";
		}
		if(chartname=="eventnumperactiveuser"){
			titlename ="<?php echo lang('eventchartdetail_eventactiveuser3month')?>" ;
			
		}
		if(chartname=="eventnumperstartnum"){
			titlename ="<?php echo lang('eventchartdetail_eventstartnum3month')?>" ;
			
		}
	}
	if (timephase == "all") {
		if(chartname=="eventnum"){
			titlename = "<?php echo lang('eventchartdetail_eventnumall') ?>";
		}
		if(chartname=="eventnumperactiveuser"){
			titlename ="<?php echo lang('eventchartdetail_eventactiveuserall')?>" ;
			
		}
		if(chartname=="eventnumperstartnum"){
			titlename ="<?php echo lang('eventchartdetail_eventstartnumall')?>" ;
			
		}
	}  
	if (timephase == "any") {
		if(chartname=="eventnum"){
			titlename = "<?php echo lang('eventchartdetail_eventnumanytime') ?>";
		}
		if(chartname=="eventnumperactiveuser"){
			titlename ="<?php echo lang('eventchartdetail_eventactiveuseranytime')?>" ;
			
		}
		if(chartname=="eventnumperstartnum"){
			titlename ="<?php echo lang('eventchartdetail_eventstartnumanytime')?>" ;
			
		}
}
}
</script>



