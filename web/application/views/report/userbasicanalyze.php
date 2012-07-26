<section id="main" class="column">
		<?php if(isset($message)):?>
		<h4 class="alert_success"><?php echo $message;?></h4>
		<?php else:?>
			<h4 id='msg' class="alert_success"><?php echo  lang('userbasicanalyze_alertinfo')?></h4>
		<?php endif;?>
		
		<article class="module width_full">
		<header>
			<h3><?php echo  lang('userbasicanalyze_headeinfo')?></h3>
			<div class="submit_link">
			<select onchange="selectChange(this.options[this.selectedIndex].value)" id='select'>
				<option value=过去一周 selected ><?php echo  lang('allview_lastweek')?></option>
				<option value=过去一个月><?php echo  lang('allview_lastmonth')?></option>
				<option value=过去三个月><?php echo  lang('allview_last3month')?></option>
				<option value=全部><?php echo  lang('allview_alltime')?></option>
				<option value=任意时间段><?php echo  lang('allview_anytime')?></option>
			</select>
			<div id='selectTime'><input type="text"
				id="dpFrom"> <input type="text" id="dpTo"> <input type="submit"
				id='btn' value="<?php echo  lang('allview_timebtn')?>" class="alt_btn" onclick="onAnyTimeButtonClicked()"></div>
			</div>
		</header>
		<article class="width_full">
		<div id="container"  class="module_content" style="height:300px">
		</div>
		</article>
		<div class="clear"></div>
		<footer>
			<ul class="tabs2">
				<li><a ct="newUser" href="javascript:changeChartName('chartNewUser')"><?php echo  lang('userbasicanalyze_newuser')?></a></li>
				<li><a ct="totalUser" href="javascript:changeChartName('chartTotalUser')"><?php echo  lang('userbasicanalyze_totaluser')?></a></li>
				<li><a ct="activeUser" href="javascript:changeChartName('chartActiveUser')"><?php echo  lang('userbasicanalyze_activeuser')?></a></li>
				<li><a ct="startUser" href="javascript:changeChartName('chartStartUser')"><?php echo  lang('userbasicanalyze_startnum')?></a></li>
				<li><a ct="averageUsingTime" href="javascript:changeChartName('chartAverageUsingTime')"><?php echo  lang('userbasicanalyze_avgtime')?></a></li>
			</ul>
		</footer>
	</article>
	<div class="clear"></div>
	<div class="spacer"></div>

	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('userbasicanalyze_detaildata')?></h3>
			<span class="relative r">                	
                	<a href="<?php echo site_url(); ?>/report/userbasic/exportdetaildata" class="bottun4 hover" ><font><?php echo  lang('userbasicanalyze_exportinfo')?></font>
                	<a href="#this" class="bottun4" onclick="sever('server1','server1c1');"><font>?</font></a>
                	<div class="server333" id="server1" style="width:620px;">
                       <div class="ser_title">
                           <b class="l"><?php echo  lang('userbasicanalyze_settitle')?></b>                          
                           <a class="r" href="#this" id="server1c1"><img src="<?php echo base_url(); ?>assets/images/server_close.gif" /></a>
                       </div>
                       
                                <style>
								.ser_txt font{
									width:95px
								}
								</style>
                       <div class="ser_txt">
                           <dl>
                               <dt>
                               	<font><?php echo  lang('userbasicanalyze_remindnew')?></font>
                                <small><?php echo  lang('userbasicanalyze_remindnewinstall')?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo  lang('userbasicanalyze_remindtotal')?></font>
                                <small><?php echo  lang('userbasicanalyze_remindrappstart')?></small>
                                <div class="clear"></div>
                               </dd>
                               <dt>
                               	<font><?php echo  lang('userbasicanalyze_remindactive')?></font>
                                <small><?php echo  lang('userbasicanalyze_remindleastone')?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo  lang('userbasicanalyze_remindstartnum')?></font>
                                <small><?php echo  lang('userbasicanalyze_remindappstartnum')?></small>
                                <div class="clear"></div>
                               </dd>
                               <dt>
                               	<font><?php echo  lang('userbasicanalyze_remindavgtime')?></font>
                               	<small><?php echo  lang('userbasicanalyze_reminduseravgtime')?></small>
                                <div class="clear"></div>
                               </dt>                              
                           </dl>
                       </div>
                	</div>
                </span>		
		</header>
		<div style="height:500px">
		<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo  lang('userbasicanalyze_theaddate')?></th> 
    				<th><?php echo  lang('userbasicanalyze_theadnew')?></th> 
    				<th><?php echo  lang('userbasicanalyze_theadtotal')?></th> 
    				<th><?php echo  lang('userbasicanalyze_theadactive')?></th> 
    				<th><?php echo  lang('userbasicanalyze_theadstart')?></th>
    				<th><?php echo  lang('userbasicanalyze_theadavgtime')?></th>
				</tr> 
			</thead> 
			<tbody id="content">
		
			</tbody>
		</table> 
		</div>
		<footer>
		<div id="pagination"  class="submit_link">
		</div>
		</footer>
	</article>
	<div class="clear"></div>
		<div class="spacer"></div>
</section>
<script type="text/javascript">
var chartName = 'chartNewUser';
var timePhase = '7day';
var fromTime;
var toTime;
var name;

//When page loads...
dispalyOrHideTimeSelect();
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content
$(document).ready(function() {
	getChartData();
});


function dispalyOrHideTimeSelect()
{
	 var value = document.getElementById('select').value;
	 if(value=='任意时间段')
	 {
		 document.getElementById('selectTime').style.display="inline";
	 }
	 else
	 { 
		 document.getElementById('selectTime').style.display="none";
	 }
} 


function changeChartName(name)
{
	chartName = name;
	getChartData();
}

function selectChange(value)
{
	if(value=='过去一周')
    {
    	timePhase='7day';
     }
    if(value=='过去一个月')
    {
    	timePhase='1month';
        
     }
    if(value=='过去三个月')
    {
    	timePhase='3month';
        
     }
    if(value=='全部')
    {
    	timePhase='all';
     }
    if(value=='任意时间段')
    {
    	timePhase='any';
    }
    if(timePhase!='any')
    {
        getChartData();
    }
    dispalyOrHideTimeSelect();           
}

//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(".tab_content").hide(); //Hide all tab content
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("ct"); //Find the href attribute value to identify the active tab + content
	$('#'+activeTab).fadeIn(); //Fade in the active ID content
	return true;
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
function onAnyTimeButtonClicked()
{  
	fromTime = document.getElementById('dpFrom').value;
	toTime = document.getElementById('dpTo').value;
	getChartData();
}

</script>

<script type="text/javascript">

function getChartData()
{
	var myurl = "";
	if(chartName == 'chartNewUser')
	{
		name = "<?php echo  lang('userbasicanalyze_chartnewname')?>";
		if(timePhase=='any')
		{		
			myurl="<?php echo site_url()?>/report/userbasic/getNewUsersByTime/"+timePhase+"/"+fromTime+"/"+toTime;
		}
		else
		{
			myurl="<?php echo site_url()?>/report/userbasic/getNewUsersByTime/"+timePhase;
		}
	}

	if(chartName == 'chartActiveUser')
	{
		name ="<?php echo  lang('userbasicanalyze_chartactname')?>";
		if(timePhase=='any')
		{		
			myurl="<?php echo site_url()?>/report/userbasic/getActiveUserByTimePhase/"+timePhase+"/"+fromTime+"/"+toTime;
		}
		else
		{
			myurl="<?php echo site_url()?>/report/userbasic/getActiveUserByTimePhase/"+timePhase;
		}
	}

	if(chartName == 'chartStartUser')
	{
		name = "<?php echo  lang('userbasicanalyze_chartstartname')?>";
		if(timePhase=='any')
		{		
			myurl="<?php echo site_url()?>/report/userbasic/getStartUserByTimePhase/"+timePhase+"/"+fromTime+"/"+toTime;
		}
		else
		{
			myurl="<?php echo site_url()?>/report/userbasic/getStartUserByTimePhase/"+timePhase;
		}
	}


	if(chartName == 'chartAverageUsingTime')
	{
		name = "<?php echo  lang('userbasicanalyze_chartavgname')?>";
		if(timePhase=='any')
		{		
			myurl="<?php echo site_url()?>/report/userbasic/getAverageUsingTime/"+timePhase+"/"+fromTime+"/"+toTime;
		}
		else
		{
			myurl="<?php echo site_url()?>/report/userbasic/getAverageUsingTime/"+timePhase;
		}
	}

	if(chartName == 'chartTotalUser')
	{
		name = "<?php echo  lang('userbasicanalyze_chartnumname')?>";
		if(timePhase=='any')
		{		
			myurl="<?php echo site_url()?>/report/userbasic/getTotalUserByTimePhase/"+timePhase+"/"+fromTime+"/"+toTime;
		}
		else
		{
			myurl="<?php echo site_url()?>/report/userbasic/getTotalUserByTimePhase/"+timePhase;
		}
	}

//	alert(myurl);
	renderCharts(myurl);
}
</script>


<script type="text/javascript">
var chart;
var chart_canvas;
function renderCharts(myurl)
{
	if(chart)
	{
		chart.destroy();
	}
	
	var options = {
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
            min:0,
            title: {
                text: ''
            },
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
            enabled:false
         },
        series: [{
            
            marker: {
                symbol: 'circle'
            }
           

        }]
    };

	   chart_canvas = $('#container');
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
			var categories = [];
			var newUsers = [];
			var obj = data.content;
			var total = 0;
		    for(var i=0;i<obj.length;i++)
		    {	    
			    if(obj[i].totalaccess == null && chartName == 'chartTotalUser')
			    {
				    
			    }
			    else
			    {
			    	total = parseInt(obj[i].totalaccess,10);
			    }
		    	newUsers.push(total);
		    	categories.push(obj[i].startdate);
		    }
		   	//alert(chart);
			//alert(chart.series);
			options.series[0].data = newUsers;
			 if(chartName == 'chartTotalUser')
			    {
				    serie = options.series[0];
			       	serie.type = 'area';
				}
			 options.xAxis.labels.step = parseInt(categories.length/10);
			 options.xAxis.categories = categories;  
			 options.title.text = data.title;
			 options.series[0].name = name;
			 chart = new Highcharts.Chart(options);
			 chart_canvas.unblock();
			});
}
</script>

<script type="text/javascript">
			function pageselectCallback(page_index, jq){
				var myurl="<?php echo site_url()?>/report/userbasic/getDetailData/"+page_index;
				jQuery.ajax({
					type : "post",
					url : myurl,
					success : function(msg) {
						var container = document.getElementById("content");
						setTBodyInnerHTML(container,msg);
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						//document.getElementById('msg').innerHTML = "加载数据出错";
						
					},
					beforeSend : function() {
						//document.getElementById('msg').innerHTML = '正在加载数据，请稍候...';

					},
					complete : function() {
						//document.getElementById('msg').innerHTML = '加载数据完成';
					}
				});
                return false;
            }
           
            /** 
             * Callback function for the AJAX content loader.
             */
            function initPagination() {
                var num_entries = 90/<?php echo PAGE_NUMS;?>;
                // Create pagination element
                $("#pagination").pagination(num_entries, {
                    num_edge_entries: 2,
                    prev_text: '<?php echo  lang('allview_jsbeforepage')?>',       //上一页按钮里text 
                    next_text: '<?php echo  lang('allview_jsnextpage')?>',       //下一页按钮里text            
                    num_display_entries: 8,
                    callback: pageselectCallback,
                    items_per_page:1
                });
             }
                    
            // Load HTML snippet with AJAX and insert it into the Hiddenresult element
            // When the HTML has loaded, call initPagination to paginate the elements        
            $(document).ready(function(){      
            	initPagination();
            	pageselectCallback(0,0);
            });    


            function setTBodyInnerHTML(tbody, html) {
          	  var temp = tbody.ownerDocument.createElement('div');
          	  temp.innerHTML = '<table><tbody id=\"content\">' + html + '</tbody></table>';
          	  tbody.parentNode.replaceChild(temp.firstChild.firstChild, tbody);
          	}       
</script>
<script type="text/javascript">

function sever(txt, colse) {
	$("#" + txt).slideToggle("slow");
	$("#" + colse).click(function() {
		$("#" + txt).slideUp("slow");
	});
	
}

</script>

