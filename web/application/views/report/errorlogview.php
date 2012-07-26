<section id="main" class="column">
<h4 class="alert_success" id='msg'><?php echo  lang('errorlogview_alertinfo')?></h4>
<article class="module width_full">
<header>
<h3><?php echo  lang('errorlogview_headeinfo')?></h3>
<div class="submit_link" >
<select onchange=selectChange(this.value) id='select'>
	<option selected value='7day'><?php echo  lang('allview_lastweek')?></option>
	<option value='1month'><?php echo  lang('allview_lastmonth')?></option>
	<option  value='3month'><?php echo  lang('allview_last3month')?></option>
	<option value='all'><?php echo  lang('allview_alltime')?></option>
	<option value='any'><?php echo  lang('allview_anytime')?></option>
</select>
<div id='selectTime'><input type="text" id="dpFrom"> <input type="text"
	id="dpTo"> <input type="submit" id='btn' value="<?php echo  lang('allview_timebtn')?>" class="alt_btn"
	onclick="onAnyTimeButtonClicked()"></div>
</div>
	  </header>
 <article class="width_full">
	     <div id="container"  class="module_content" style="height:300px">
		</div>
		</article>
		<div class="clear"></div>
		
<footer>
<ul class="tabs2">
	<li><a idd="errornum11" href="javascript:changetypename('errorNumber')"><?php echo  lang('errorlogview_errornum')?></a></li>
	<li><a idd="errorstartnum11" href="javascript:changetypename('errorAndStart')"><?php echo  lang('errorlogview_errorstart')?></a></li>
</ul>
</footer>
</article>
<script type="text/javascript">
var timePhase = '7day';
var changetype='errorNumber';
var titile='';
var jsondata;
var fromTime;
var toTime;
//When page loads...
dispalyOrHideTimeSelect();
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

dispalyOrHideTimeSelect();
function changetypename(name)
{
	changetype = name;
	getChartData();
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
    	timePhase='any';
    }	
    else
    {
    	timePhase=value;
    	getChartData();

        }
   dispalyOrHideTimeSelect();           
}
</script>

<script type="text/javascript">
	$(function() {
		$("#dpFrom" ).datepicker();
	});
	$( "#dpFrom" ).datepicker({ dateFormat: "yy-mm-dd" });
	$(function() {
		$( "#dpTo" ).datepicker();
	});
	$( "#dpTo" ).datepicker({ dateFormat: "yy-mm-dd" });
	//设置活动的li
	$("ul.tabs2 li").click(function() {
		$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
		$(".tab_content").hide(); //Hide all tab content
		$(this).addClass("active"); //Add "active" class to selected tab
		var activeTab = $(this).find("a").attr("idd"); //Find the href attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return true;
	});		
</script>
<!-- report -->
<script type="text/javascript">
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
    
	changetype = "errorNumber";
	getChartData();
});
var  chardata ;
var version;
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
    	    
    	    chardata = data;
    	    
    		var version_array = [];
        	for(var key in data.content)
        	{
            	version_array.push(key);

            }
        	titile = getTitle(timePhase,changetype);
    	    for(var j=0;j<version_array.length;j++)
    	    {
    	    	version = version_array[j];
    	    	var eachVersionData = data.content[version];
        		var categories = [];
    			var newUsers = [];
    		//	alert(changetype);
    			for(var i=0;i<eachVersionData.length;i++)
        		{
            		var eachVersionDataItem = eachVersionData[i];
            		if(changetype=="errorNumber")
            		    newUsers.push(parseInt(eachVersionDataItem.count,10));
            		if(changetype=="errorAndStart")
            			newUsers.push(parseInt(eachVersionDataItem.percentage,10));
		    		categories.push(eachVersionDataItem.datevalue);

            	}
    			options.series[j] = {};
        		if(version == "")
    		    {
    		    	options.series[j].name = "<?php echo  lang('versioncontrast_jsunknowinfo')?>";
    		    }
    		    else
    		    {
        		    options.series[j].name = version;
    		    }
       		 options.series[j].data = newUsers;
  			options.xAxis.labels.step = parseInt(categories.length/10);
  			options.xAxis.categories = categories; 
  			options.title.text = title;
    	    }
    	  //  alert("dfs");
    	    chart = new Highcharts.Chart(options);
    	 
    		chart_canvas.unblock();
    		});  
    }
    function getTitle(timePhase,changetype){
        if(changetype=='errorNumber'){
            if(timePhase=='7day'){title="<?php echo lang('errortitle_error7days')?>";}
            if(timePhase=='1month'){title="<?php echo lang('errortitle_error30days') ?>";}
            if(timePhase=='3month'){title="<?php echo lang('errortitle_error3month')?>";}
            if(timePhase=='all'){title='<?php echo lang('errortitle_errorall')?>';}
            if(timePhase=='any'){title='<?php echo lang('errortitle_erroranytime')?>';}
            }
        if(changetype=='errorAndStart'){
        	if(timePhase=='7day'){title='<?php  echo lang('errortitle_starterror7days')?>';}
            if(timePhase=='1month'){title='<?php echo lang('errortitle_starterror30days')?>';}
            if(timePhase=='3month'){title='<?php echo lang('errortitle_starterror3month')?>';}
            if(timePhase=='all'){title='<?php echo lang('errortitle_starterrorall')?>';}
            if(timePhase=='any'){title='<?php echo lang('errortitle_starterroranytime')?>';}
            }
    }
    
  	    
</script>
<script type="text/javascript">

function getChartData()
{
	var myurl = "";
	if(timePhase=='any')
	{		
		myurl="<?php echo site_url()?>/report/errorlog/geterroralldata/"+changetype+"/"+timePhase+"/"+fromTime+"/"+toTime;
	}
	else
	{
		myurl="<?php echo site_url()?>/report/errorlog/geterroralldata/"+changetype+"/"+timePhase;
	//	alert(myurl);
	}
	renderCharts(myurl);
}
</script>
<script type="text/javascript">
function onAnyTimeButtonClicked()
{  
	fromTime = document.getElementById('dpFrom').value;
	toTime = document.getElementById('dpTo').value;
	getChartData();
}
</script>
