<section class="section_maeginstyle" id="highchart"
<?php if(!isset($delete)) {?>
style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;"<?php }?>>
	<article class="module width_full" >
	<header>
	<div style="float:left;margin-left:2%;margin-top: 5px;">
	<?php   if(isset($add))
  {?>
  <a href="#" onclick="addreport()">
	<img   src="<?php echo base_url();?>assets/images/addreport.png" title="<?php echo lang('s_suspend_title')?>" style="border:0"/></a>
<?php }if(isset($delete)){?>
 <a href="#" onclick="deletereport()">
	<img src="<?php echo base_url();?>assets/images/delreport.png" title="<?php echo lang('s_suspend_deltitle')?>" style="border:0"/></a>
	<?php }?>
  </div>
	<h3 class="h3_fontstyle">			
	<?php  echo lang('v_rpt_pb_timeTrendOfUsers') ?></h3>                                                                                                                                      
			<select style="position:relative;top: 5px;" onchange="switchTimePhase(this.options[this.selectedIndex].value)" id='startselect'>
				<option value=today selected ><?php echo  lang('g_today')?></option>
				<option value=yestoday><?php echo  lang('g_yesterday')?></option>
				<option value=last7days><?php echo  lang('g_last7days')?></option>
				<option value=last30days><?php echo  lang('g_last30days')?></option>
				<option value=any><?php echo  lang('g_anytime')?></option>			
			</select>
			<div id='selectcurTime'><input type="text"
				id="dpTimeFrom"> <input type="text" id="dpTimeTo"> <input type="submit"
				id='timebtn' value="<?php echo  lang('g_search')?>" class="alt_btn" onclick="onAnyTimeClicked()"></div>
			<div style="position:relative;top:-22px">
			<ul class="tabs2">
				<li><a ct="newUser" href="javascript:changefirstchartName('startuser')"><?php echo  lang('t_activeUsers')?></a></li>
				<li><a ct="totalUser" href="javascript:changefirstchartName('newuser')"><?php echo  lang('t_newUsers')?></a></li>				
			</ul>
			</div>			  			
			</header>
			<div class="tab_container">
				<div id="tab1" class="tab_content">
					<div class="module_content">
						
						<div id="container"  class="module_content" style="height:300px;margin: 10px 3% 1% 3%;">
		
		                 </div>
					
						</div>
						<div class="clear"></div>
					</div>
				</div>				
		</article>	
		</section>	
<script>
var chartdata;
var fromCurTime;
var toCurTime;
var chartname = 'startuser';
var timephase = 'today';
//When page loads...
dispalyOrHideCurTimeSelect();
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

$(document).ready(function() {
	getfirstchartdata();	
});
function dispalyOrHideCurTimeSelect()
{
	 var value = document.getElementById('startselect').value;
	 if(value=='any')
	 {
		 document.getElementById('selectcurTime').style.display="inline";
	 }
	 else
	 { 
		 document.getElementById('selectcurTime').style.display="none";
	 }
} 
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
function onAnyTimeClicked(){	
	fromCurTime = document.getElementById('dpTimeFrom').value;
	toCurTime = document.getElementById('dpTimeTo').value;	
	getfirstchartdata();	
	var posttime="<?php if(!isset($delete)){echo "postime";}else{echo "";}?>";	
	if(posttime=="postime")
	{		
		window.parent.dealgettime('any',fromCurTime,toCurTime);
	}	
}                         
</script>

<script type="text/javascript">
$(function() {
	$( "#dpTimeFrom" ).datepicker();
});
$( "#dpTimeFrom" ).datepicker({ dateFormat: "yy-mm-dd" });
$(function() {
	$( "#dpTimeTo" ).datepicker();
});
$( "#dpTimeTo" ).datepicker({ dateFormat: "yy-mm-dd" });
</script>

<script type="text/javascript">
function changefirstchartName(changename)
{	
	changeChartTitleName(timephase,changename);
	chartname = changename;
	var data = chartdata;
	if(typeof(data.type)!='undefined'&&data.type.name=='compare'){//mean copare
		$.each(data.content,function(index,item){
			var categories = [];
			var newUsers = [];
			var obj = item.data;
			var realhour;
		    for(var i=0;i<obj.length;i++)
		    {
			    if(chartname=="startuser")
				    newUsers.push(parseInt(obj[i].startusers,10));
			    if(chartname=="newuser")
			    	newUsers.push(parseInt(obj[i].newusers,10));
			    realhour=obj[i].hour+":00";				    	
		    	categories.push(realhour);
		    }
		    options.series[index]={};
			options.series[index].data = newUsers;
			options.series[index].name=item.name;
			options.title.text = titlename;		
				if(index==0){
				options.xAxis.labels.step = parseInt(categories.length/10);
				options.xAxis.categories = categories;
				}  
			});
	}else{
		var categories = [];
		var newUsers = [];
		var obj = data.content;
	    for(var i=0;i<obj.length;i++)
	    {
		    if(chartname=="startuser")
			    newUsers.push(parseInt(obj[i].startusers,10));
		    if(chartname=="newuser")
		    	newUsers.push(parseInt(obj[i].newusers,10));
		    realhour=obj[i].hour+":00";				    	
	    	categories.push(realhour);
	    }
		options.series[0].data = newUsers;
		options.xAxis.labels.step = parseInt(categories.length/10);
		options.xAxis.categories = categories;  
		options.title.text = titlename;
	}
	chart = new Highcharts.Chart(options);          
	//getfirstchartdata(); 
}
function switchTimePhase(time)
{
	dispalyOrHideCurTimeSelect();
	timephase=time;	
	if(time!="any")
	{
		getfirstchartdata();
	}
	var posttime="<?php if(!isset($delete)){echo "postime";}else{echo "";}?>";	
	if(posttime=="postime")
	{		
		window.parent.dealgettime(timephase,"","");
	}
}
function getfirstchartdata()
{
	changeChartTitleName(timephase,chartname);
	var myurl="";
	if(timephase=='any')
	{		
		myurl="<?php echo site_url()?>/report/productbasic/getTypeAnalyzeData/"+timephase+"/"+fromCurTime+"/"+toCurTime;
	}
	else
	{
		myurl = "<?php echo site_url()?>/report/productbasic/getTypeAnalyzeData/"+timephase+"?date="+new Date().getTime();
	}
	renderCharts(myurl);	
}

function changeChartTitleName(timephase,chartname){
	if (timephase == "today") {
		if(chartname=="startuser"){
			titlename = "<?php echo lang('t_activeUsersT') ?>";
		}
		if(chartname=="newuser"){
			titlename ="<?php echo lang('t_newUserT')?>" ;
			
		}
	}
	if (timephase == "yestoday") {
		if(chartname=="startuser"){
			titlename ="<?php echo lang('t_activeUsersY') ?>";
		}
		else
			titlename ="<?php echo lang('t_newUserY') ?>";
	}	
	if (timephase == "last7days") {
		if(chartname=="startuser"){
			titlename ="<?php echo lang('t_activeUsersW')?>";
		}
		else
			titlename ="<?php echo lang('t_newUserW') ?>";
	}
	if (timephase == "last30days") {
		if(chartname=="startuser"){
			titlename ="<?php echo  lang('t_activeUsersM') ?>";
		}
		else
			titlename ="<?php echo lang('t_newUserM') ?>";
	}  
	if (timephase == "any") {
		if(chartname=="startuser"){
			titlename ="<?php echo  lang('t_activeUsersA') ?>";
		}
		else
			titlename ="<?php echo lang('t_newUsersA') ?>";
	}  
}
</script>
<script type="text/javascript">
var chart;
var options;
var chartdata;    
var titlename="<?php echo lang('t_activeUsersT') ?>" ;

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
	            labels:{rotation:0,y:10,x:0}
	        },
	        yAxis: {
	            title: {
	                text: ''
	            },
	            labels: {
	                formatter: function() {
	                    return Highcharts.numberFormat(this.value, 0);
	                }
	            },min:0
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
	            enabled:true
	         },
	        credits: {
	            enabled: false
	        },
	        series: [{	            
	            marker: {
	                symbol: 'circle'
	            }
	        }]
	    };
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
			chartdata=data;             
		    if(typeof(data.type)!='undefined'&&data.type.name=='compare'){
			    $.each(data.content,function(index,item){
			    	var categories = [];
					var newUsers = [];
					var obj=item.data;
					var realhour
				    for(var i=0;i<obj.length;i++)
				    {
					    if(chartname=="startuser")
						    newUsers.push(parseInt(obj[i].startusers,10));	    
					    if(chartname=="newuser")
					    	newUsers.push(parseInt(obj[i].newusers,10));
				    	 realhour=obj[i].hour+":00";				    	
				    	categories.push(realhour);
				    }
				    options.series[index]={};
				    options.series[index].data = newUsers;
					options.series[index].name = item.name;
					if(index==0)
					options.xAxis.labels.step = parseInt(categories.length/10);
					options.xAxis.categories = categories;  
				    });
			    }else{
					var categories = [];
					var newUsers = [];
					var obj = data.content;
				    for(var i=0;i<obj.length;i++)
				    {
					    if(chartname=="startuser")
						    newUsers.push(parseInt(obj[i].startusers,10));		    
					    if(chartname=="newuser")
					    	newUsers.push(parseInt(obj[i].newusers,10));
					    realhour=obj[i].hour+":00";				    	
				    	categories.push(realhour);
				    }
				    
					options.series[0].data = newUsers;
					options.xAxis.labels.step = parseInt(categories.length/10);
					options.xAxis.categories = categories;  
					options.series[0].name = "<?php echo lang('t_activeUsers') ?>";
					options.legend.enabled=false;
				    }  
		options.title.text = titlename;
		chart = new Highcharts.Chart(options);		
		chart_canvas.unblock();
		});  
}
</script>
<script type="text/javascript">
function addreport()
{	
	if(confirm( "<?php echo  lang('w_isaddreport')?>"))
	{
		var reportname="phaseusetime";
	    var reportcontroller="productbasic";
	    var data={ 
	    		 reportname:reportname,
  		  	     controller:reportcontroller,
	  		  	 height    :380,
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
		window.parent.deletereport("phaseusetime");	 	 	  
	}
	return false;
	
}
</script>