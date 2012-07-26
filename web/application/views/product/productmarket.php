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
<h3 class="tabs_involved"><?php echo lang('productmarket_headertitle') ?></h3>
			<span class="relative r">
                	<a href="#this" class="bottun4" onclick="sever('server1','server1c1');"><font>?</font></a>
                	<div class="server333" id="server1" style="width:620px;">
                       <div class="ser_title">
                           <b class="l"><?php echo lang('productmarket_remindexplain') ?></b>                          
                           <a class="r" href="#this" id="server1c1"><img src="<?php echo base_url(); ?>assets/images/server_close.gif" /></a>
                       </div>
                       
                                <style>
								.ser_txt font{
									width:110px
								}
								</style>
                       <div class="ser_txt">
                           <dl>
                               <dt>
                               	<font><?php echo lang('productmarket_remindaddnew') ?></font>
                               	<small><?php echo lang('productmarket_remindfirstnew') ?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo lang('productmarket_remindactive') ?></font>
                               	<small><?php echo lang('productmarket_remindusernum') ?></small>
                                <div class="clear"></div>
                               </dd>
                               <dt>
                               	<font><?php echo lang('productmarket_remindweekrate') ?></font>
                               	<small><?php echo lang('productmarket_remindtotaluser') ?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo lang('productmarket_remindmonthrate') ?></font>
                               	<small><?php echo lang('productmarket_remindmonthnum') ?></small>
                                <div class="clear"></div>
                               </dd>
                               
                           </dl>
                       </div>
                	</div>
                </span>			
</header>


<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo lang('productmarket_theadchannelname') ?></th>
			<th><?php echo lang('productmarket_theadtodaynew') ?></th>
			<th><?php echo lang('productmarket_theadyesterdaynew') ?></th>
			<th><?php echo lang('productmarket_theadtodayact') ?></th>
			<th><?php echo lang('productmarket_theadyesterdayact') ?></th>
		    <th><?php echo lang('productmarket_theadsumuser') ?></th>
			<th><?php echo lang('productmarket_theadweekrate') ?></th>
			<th><?php echo lang('productmarket_theadmonthrate') ?></th>
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
			<td><?php if($todayDataArray[$i]['allusers']==0){echo '0.0%';}else{echo round($sevenDayActive[$i]['startusers']*100.0/$todayDataArray[$i]['allusers'],2)."%";} 
	?></td>			
			<td><?php if($todayDataArray[$i]['allusers']==0){echo '0.0%';}else{echo round($thirtyDayActive[$i]['startusers']/$todayDataArray[$i]['allusers'],2)."%" ;} 
	?></td>
			<!--  td><?php // echo ($new_user_time_phase[$i]*100)."%" ; ?></td>-->
		</tr>
		<?php }?>

	</tbody>
</table>
</article>


<article class="module width_full">
<header>
  <h3 class="tabs_involved"><?php echo lang('productmarket_timeanaly') ?></h3>
  <div class="submit_link">
<select onchange=selectChange(this.value)
	id='select'>
	<option value=过去一周 selected><?php echo lang('allview_lastweek') ?></option>
	<option value=过去一个月><?php echo lang('allview_lastmonth') ?></option>
	<option value=过去三个月><?php echo lang('allview_last3month') ?></option>
	<option value=全部><?php echo lang('allview_alltime') ?></option>
	<option value=任意时间段><?php echo lang('allview_anytime') ?></option>
</select>
<div id='selectTime'><input type="text" id="dpFrom"> <input type="text"
	id="dpTo"> <input type="submit" id='btn' value="<?php echo lang('allview_timebtn') ?>" class="alt_btn"
	onclick="onBtn()">
</div>
</div>
 
</header>

   
<div class="module_content">
         <div id="container"  class="module_content" style="height:300px">
		</div>
</div>

<footer>
 <ul class="tabs2">
	<li><a id='7day' href="javascript:chooseType('new')"><?php echo lang('productmarket_timeultabnew') ?></a></li>
	<li><a id='1month' href="javascript:chooseType('active')"><?php echo lang('productmarket_timeultabact') ?></a></li>
	<li><a id='all' href="javascript:chooseType('startcount')"><?php echo lang('productmarket_timeultabstart') ?></a></li>
	<li><a id='all' href="javascript:chooseType('average')"><?php echo lang('productmarket_timeultabavgtime') ?></a></li>
	<li><a id='all' href="javascript:chooseType('weekactive')"><?php echo lang('productmarket_timeultabweekrate') ?></a></li>
	<li><a id='all' href="javascript:chooseType('monthactive')"><?php echo lang('productmarket_timeultabmonthrate') ?></a></li>

  </ul>
</footer>
</article>




</section>

<script type="text/javascript">
var  chartdata;
var  allusers= new Array();
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
	$(function() {
		$("#dpFrom" ).datepicker();
	});
	$( "#dpFrom" ).datepicker({ dateFormat: "yy-mm-dd" });
	$(function() {
		$( "#dpTo" ).datepicker();
	});
	$( "#dpTo" ).datepicker({ dateFormat: "yy-mm-dd" });
	</script>



<script type="text/javascript">
    dispalyOrHideTimeSelect();
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

    function chooseMarket(market_id)
    {
//        alert(market_id);
        market = market_id;
        getdata();
       
    }
    function chooseType(a_type)
    {
        type =a_type;  
        if(a_type=="weekactive"||a_type=="monthactive"){
            getdata();
        }else{
        	var data = chartdata;
            var data_array =[];
    		for(var key in data.content){
        		data_array.push(key);
    		}
    		for(var j=0;j<data_array.length;j++)
    	    {
    		    var marketData = data_array[j];
    		    var eachmarketdata = data.content[marketData];
    		    var categories = [];
    			var newUsers = [];
    			//alert(marketContent.length);
    		    for(var i=0;i<eachmarketdata.length;i++)
    		    {
        		    var eachdata = eachmarketdata[i];
    			 	if(type=='new')
    			 	{
    	    			newUsers.push(parseFloat(eachdata.newusers,10));
    			 	}
    			 	if(type=='active')
        			{
    					newUsers.push(parseFloat(eachdata.startusers,10));
        			}
    			 	if(type=='startcount')
        			{
    					newUsers.push(parseFloat(eachdata.allusers,10));
        			}
    			 	if(type=='average')
        			{
            			var average ;
            			if(eachdata.startusers==0){
                			average = 0;
            			}else{
    						average = eachdata.usingtime*1.0/eachdata.startusers;
                			}
    					newUsers.push(parseFloat(average,10));
        			}
        			if(type=="weekactive"){
            		//	alert(allusers);
            			if(allusers[i]!=0){
                			newUsers.push(parseFloat(eachdata.startusers*1.0/allusers[i],10));
                			}else{
                				newUsers.push(parseFloat(0,10)+"%");
                    			}
        				
            			}
        			if(type=="monthactive"){
        				if(allusers[i]!=0){
                			newUsers.push(parseFloat(eachdata.startusers*1.0/allusers[i],10));
                			}else{
                				newUsers.push(parseFloat(0,10)+"%");
                    			}
        				
        			}
        			 	
       		    	categories.push(eachdata.datevalue.substr(0,10));
    		    }
    		options.series[j]={};
    		if(marketData==""){
    			options.series[j].name="未知";
    			}else{
    			options.series[j].name=marketData;
    				}
    		    options.series[j].data = newUsers;
    		    options.xAxis.labels.step = parseInt(categories.length/10);
    		    options.xAxis.categories = categories; 
    		    options.title.text = getTitle(type);
    	    }
    	    chart = new Highcharts.Chart(options);
    	
    	
            }
        
        	
    }

    function getTitle(type){
        var titlename="";
       // alert(type+"  "+time);
			if(type=="new"){
				if(time=="1month"){
					titlename=" <?php echo lang('producttitleinfo_newmonth');?>";
				}
				if(time=="3month"){
					titlename="<?php echo lang('producttitleinfo_new3month');?>";
					}
				if(time=="all"){
					titlename="  <?php echo lang('producttitleinfo_newall')?>";
					}
				if(time=="any"){
					titlename=" <?php echo lang('producttitleinfo_newanytime')?>";
					}
				if(time=="7day"){
					
					titlename="  <?php echo lang('producttitleinfo_new7days')?>";//alert(titlename);
					}
			}
			if(type=="active"){
				if(time=="1month"){
					titlename="  <?php echo lang('producttitleinfo_actmonth');?>";
				}
				if(time=="3month"){
					titlename="  <?php echo lang('producttitleinfo_act3month')?>";
					}
				if(time=="all"){
					titlename="  <?php echo lang('producttitleinfo_actall')?>";
					}
				if(time=="any"){
					titlename="  <?php echo lang('producttitleinfo_actanytime')?>";
					}
				if(time=="7day"){
					titlename="  <?php echo lang('producttitleinfo_act7days')?>";
					}
			}
			if(type=="startcount"){
				if(time=="1month"){
					titlename="  <?php echo lang('producttitleinfo_startmonth');?>";
				}
				if(time=="3month"){
					titlename="  <?php echo lang('producttitleinfo_start3month')?>";
					}
				if(time=="all"){
					titlename="  <?php echo lang('producttitleinfo_startall')?>";
					}
				if(time=="any"){
					titlename="  <?php echo lang('producttitleinfo_startanytime')?>";
					}
				if(time=="7day"){
					titlename="  <?php echo lang('producttitleinfo_start7days')?>";
					}
			}
			if(type=="average"){
				if(time=="1month"){
					titlename="  <?php echo lang('producttitleinfo_timemonth');?>";
				}
				if(time=="3month"){
					titlename=" <?php echo lang('producttitleinfo_time3month')?>";
					}
				if(time=="all"){
					titlename="  <?php echo lang('producttitleinfo_timeall')?>";
					}
				if(time=="any"){
					titlename="  <?php echo lang('producttitleinfo_timeanytime')?>";
					}
				if(time=="7day"){
					titlename="  <?php echo lang('producttitleinfo_time7days')?>";
					}
			}
			if(type=="weekactive"){
				if(time=="1month"){
					titlename="  <?php echo lang('producttitleinfo_percentmonth');?>";
				}
				if(time=="3month"){
					titlename="  <?php echo lang('producttitleinfo_percent3month')?>";
					}
				if(time=="all"){
					titlename=" <?php echo lang('producttitleinfo_percentall')?>";
					}
				if(time=="any"){
					titlename="  <?php echo lang('producttitleinfo_percentanytime')?>";
					}
				if(time=="7day"){
					titlename="  <?php echo lang('producttitleinfo_percent7days')?>";
					}
			}
			if(type=="monthactive"){
				
				if(time=="1month"){
					titlename="  <?php echo lang('producttitleinfo_lastmonthper');?>";
				}
				if(time=="3month"){
					titlename="  <?php echo lang('producttitleinfo_last3monthper')?>";
					}
				if(time=="all"){
					titlename="  <?php echo lang('producttitleinfo_lastallper')?>";
					}
				if(time=="any"){
					titlename="  <?php echo lang('producttitleinfo_lastanytimeper')?>";
					}
				
				if(time=="7day"){
					titlename="  <?php echo lang('producttitleinfo_last7days')?>";
					}
				
			}
			return titlename;
        }
    
	function onBtn()
	{  
		fromTime = document.getElementById('dpFrom').value;
		toTime = document.getElementById('dpTo').value;
		getdata();
	}

	function dispalyOrHideTimeSelect()
	{
		value = document.getElementById('select').value;
		 if(value=='任意时间段')
		 {
			 document.getElementById('selectTime').style.display="inline";

		 }
		 else
		 {			 
			 document.getElementById('selectTime').style.display="none";
		 } 
	}

   
    function DownloadJSON2CSV(objArray)
    {
        var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;

        var str = '';

        for (var i = 0; i < array.length; i++) {
            var line = '';

            for (var index in array[i]) {
                line += array[i][index] + ',';
            }

            line.slice(0,line.Length-1); 

            str += line + '\r\n';
        }
        window.open( "data:text/csv;charset=utf-8," + escape(str));
    }
   
    function getdata()
    {
       
		if(time=='any')
		{		
			myurl="<?php echo site_url()?>/report/market/getMarketData/"+market+"/"+time+"/"+type+"/"+fromTime+"/"+toTime;

		}
		else
		{
			myurl="<?php echo site_url()?>/report/market/getMarketData/"+market+"/"+time+"/"+type;
		}

		renderCharts(myurl);		
     }
    </script>
    
    <script type="text/javascript">

     
    function renderCharts(myurl)
    {
       // alert(myurl);
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
    		for(var key in data.content){
        		data_array.push(key);
    		}
    		for(var j=0;j<data_array.length;j++)
    	    {
    		    var marketData = data_array[j];
    		    var eachmarketdata = data.content[marketData];
    		    var categories = [];
    			var newUsers = [];
    			//alert(marketContent.length);
    		    for(var i=0;i<eachmarketdata.length;i++)
    		    {
        		    var eachdata = eachmarketdata[i];
        		    if(type!="weekactive"&&type!="monthactive"){
       		    	 allusers[i]=eachdata.allusers;
        		    	//alert(allusers);
        		    }
        		   
    			 	if(type=='new')
    			 	{
    			 		chartdata=data;
    	    			newUsers.push(parseFloat(eachdata.newusers,10));
    			 	}
    			 	if(type=='active')
        			{chartdata=data;
						newUsers.push(parseFloat(eachdata.startusers,10));
        			}
    			 	if(type=='startcount')
        			{
    			 		chartdata=data;
						newUsers.push(parseFloat(eachdata.allusers,10));
        			}
    			 	if(type=='average')
        			{chartdata=data;
            			var average ;
            			if(eachdata.startusers==0){
                			average = 0;
            			}else{
							average = eachdata.usingtime*1.0/eachdata.startusers;
                			}
						newUsers.push(parseFloat(average,10));
        			}
        			if(type=="weekactive"){
            			
        				if(allusers[i]!=0){
                			newUsers.push(Math.round(parseFloat(eachdata.startusers*1.0/allusers[i],10),2));
                			}else{
                				newUsers.push(Math.round(parseFloat(0,10)),2);
                    			}
            			}
        			if(type=="monthactive"){
        				if(allusers[i]!=0){
                			newUsers.push(Math.round(parseFloat(eachdata.startusers*1.0/allusers[i],10),2));
                			}else{
                				newUsers.push(Math.round(parseFloat(0,10)),2);
                    			}
        			}
        			 	
       		    	categories.push(eachdata.datevalue.substr(0,10));
    		    }
			options.series[j]={};
			if(marketData==""){
				options.series[j].name="未知";
				}else{
				options.series[j].name=marketData;
					}
    		    options.series[j].data = newUsers;
    		    options.xAxis.labels.step = parseInt(categories.length/10);
    		    options.xAxis.categories = categories; 
    		    options.title.text = getTitle(type);
    	    }
    	    chart = new Highcharts.Chart(options);
    		chart_canvas.unblock();
    		});  
    	
    }
  	    
</script>
    
    
    <script type="text/javascript">

    
    function selectChange(value)
    {
        
        if(value=='过去一个月')
        {
            time='1month';
            getdata(); 
            
         }
        if(value=='过去三个月')
        {
            time='3month';
            getdata(); 
            
         }
        if(value=='全部')
        {
            time='all';
            getdata(); 
         }
        if(value=='任意时间段')
        {
            time='any';
        }	
        if(value=='过去一周')
        {
            time='7day';
            getdata();     
//            window.location.href='<?php echo site_url()?>/report/market/viewMarket/+market+/+time+/+type';      
         }
        dispalyOrHideTimeSelect();           
	}
     
     </script>
     <script type="text/javascript">
//function ptrbg(e) {
function xytt(txt, bg, colse) {
	var txt = txt;
	var bg = bg;
	$("#" + txt + " input").val("");
	var sHeight = document.body.clientHeight;
	var dheight = document.documentElement.clientHeight;
	var srctop = document.documentElement.scrollTop;
	if($.browser.safari) {
		srctop = window.pageYOffset;
	}
	$(".xy").css({
		"height" : dheight
	});
	dheight = (dheight - $("#" + txt).height()) / 2;
	$("#" + txt).show();
	$("#" + bg).show();
	$("#" + txt).css({
		"top" : (srctop + dheight) + "px"
	});
	$("#" + bg).css({
		"top" : (srctop ) + "px"
	});
	window.onscroll = function scall() {
		var srctop = document.documentElement.scrollTop;
		if($.browser.safari) {
			srctop = window.pageYOffset;
		}
		$("#" + txt).css({
			"top" : (srctop + dheight) + "px"
		});
		$("#" + bg).css({
			"top" : (srctop) + "px"
		});
		$("#fkicon").css({
			top : srctop + (innerHeights / 2)
		});
		window.onscroll = scall;
		window.onresize = scall;
		window.onload = scall;
	}
	$("." + colse).click(function() {
		$("#" + txt).hide();
		$("#" + bg).hide();
	})
}

function sever(txt, colse) {
	$("#" + txt).slideToggle("slow");
	$("#" + colse).click(function() {
		$("#" + txt).slideUp("slow");
	});
	
}

</script>
     