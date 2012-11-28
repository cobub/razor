<section class="section_maeginstyle" 
style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;">	
	   <article class="module width_full">     
		<header>
			<h3 class="h3_fontstyle"><?php   if(isset($add))
  {?>
  <a href="#" onclick="addreport()">
	<img src="<?php echo base_url();?>assets/images/addreport.png" title="<?php echo lang('s_suspend_title')?>" style="border:0"/></a>
<?php } echo  lang('v_rpt_pb_overviewOfUserBehavior')?></h3>
			<ul class="tabs3">
				<li><a ct="newUser" href="javascript:changeChartName('<?php echo  lang('t_newUsers')?>')"><?php echo  lang('t_newUsers')?></a></li>
				<li><a ct="totalUser" href="javascript:changeChartName('<?php echo  lang('t_accumulatedUsers')?>')"><?php echo  lang('t_accumulatedUsers')?></a></li>
				<li><a ct="activeUser" href="javascript:changeChartName('<?php echo  lang('t_activeUsers')?>')"><?php echo  lang('t_activeUsers')?></a></li>
				<li><a ct="startUser" href="javascript:changeChartName('<?php echo  lang('t_sessions')?>')"><?php echo  lang('t_sessions')?></a></li>
				<li><a ct="averageUsingTime" href="javascript:changeChartName('<?php echo  lang('t_averageUsageDuration')?>')"><?php echo  lang('t_averageUsageDuration')?></a></li>
			</ul>
		</header>		
		<div id="usercontainer"  class="module_content" style="height:260px">
		</div>
		</article>
</section>
	
<script>
var chartDetailName = '<?php echo  lang('t_newUsers')?>';
var newUser=[];
var newUsertrend=[];
var totalUser=[];
var totalUsertrend=[];
var activeUser=[];
var activeUsertrend=[];
var sessionNum=[];
var sessionNumtrend=[];
var avgUsage=[];
var avgUsagettrend=[];
var show_thrend='<?php if(isset($common)){echo $common['show_thrend'];}else{echo 1;}?>';
var show_markevent='<?php if(isset($common)){echo $common['show_markevent'];}else{echo 1;}?>';
var productNames=[];
var thrend=0;
var dateMark=[];
var category=[];
var tooltipdata=[];
var tooltiptrenddata=[];
var tooltipmarkevent=[];
//When page loads...
$(".tab_content").hide(); //Hide all content
$("ul.tabs3 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content
$(document).ready(function() {	
	//load Overview of User Behavior report
	var myurl="<?php echo site_url()?>/report/productbasic/getUsersDataByTime?date="+new Date().getTime();
    renderuserCharts(myurl);    
});
//On Click Event
$("ul.tabs3 li").click(function() {
	$("ul.tabs3 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
</script>
<!-- Overview of User Behavior report -->
<script type="text/javascript">
var chart_detailcanvas;
var optiondetail;
var chartdetail;
var length=0;
var optionsLength=0;
var markEventIndex=[];
function renderuserCharts(myurl)
{
	optiondetail = {
        chart: {
            renderTo: 'usercontainer',
            type: 'spline'
        },
        title: {
            text: '   '
        },
        subtitle: {
            text: '<?php echo $reportTitle['timePase']; ?>'
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
        credits:{
			enabled:false
	        },
        tooltip: {
       	 crosshairs: true,
         formatter: function() {
             var content=this.x+'<br>';
             for(var i=0;i<category.length;i++){
                 if(category[i]==this.x){
              	   if(this.series.name=='<?php echo lang('m_dateevents');?>'){
                         content=tooltipmarkevent[i];
                     }else{
                         content=content+'<span style="color:#4572A7">'+chartDetailName+'</span>:'+tooltipdata[i]+'<br>';
                         content=content+'<span style="color:#89A54E"><?php echo lang('V_Trendvalue')?></span>'+':'+tooltiptrenddata[i];
                     }                 
                 }
             }
             return content;
          } 
        },
        plotOptions: {
            spline: {
                marker: {
                    radius: 1,
                    lineColor: '#666666',
                    lineWidth: 1
                }
            },series:{
                cursor:'pointer',
            	events:{
    				click:function(e){
							if(show_markevent=='1'){
								if(!markEventIndex.content(e.point.series.index)){
		    						sendBack(e);
		    						return;
		    					}
								var rights=e.point.config;
								if(typeof rights=='undefined'){return;}
								rights=rights.rights==1?'<?php echo lang('m_public')?>':'<?php echo lang('m_private')?>';
		    					var content='<div><?php echo lang('m_marktime')?>:'+e.point.date+'</div>';
		    					content+='<div><?php echo lang('m_user')?>:'+e.point.username+'</div>';
		    					content+='<div><?php echo lang('m_title')?>:'+e.point.title+'</div>';
		    					content+='<div><?php echo lang('m_description')?>:'+e.point.description+'</div>';
		    					content+='<div><?php echo lang('m_rights')?>:'+rights+'</div>';
		    					 hs.htmlExpand(null, {
		                             pageOrigin: {
		                                 x: e.pageX,
		                                 y: e.pageY
		                             },
		                             headingText: '<?php echo lang('m_eventsDetail')?>',
		                             maincontentText:content,
		                             width: 200
		                         });
							}
        				}
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
	  chart_detailcanvas = $('#usercontainer');
	    var loading_img = $("<img src='<?php echo base_url();?>/assets/images/loader.gif'/>");
	    chart_detailcanvas.block({
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
			if(typeof(data.length)=='undefined'){
				contentCharts(data);	
			}else{
				compareCharts(data);
				}
			optionsLength=optiondetail.series.length;			
			if(show_markevent=='1'){
				 //content markevent
			    var marklist=data.marklist;
			    var defdate=data.defdate;
			    var markevents=data.markevents;
			    if(marklist.length>=1){
			    	$.each(marklist,function(index,item){
				    	var seriesLength=optiondetail.series.length;
				    	markEventIndex[index]=seriesLength;
				    	optiondetail.series[seriesLength]={};
				    	optiondetail.series[seriesLength].type='column';
				    	optiondetail.series[optionsLength].name="<?php echo lang('m_dateevents');?>";
				    	optiondetail.colors=[];
				    	optiondetail.colors[optionsLength]="#DB9D00";
				    	var contentdata=[];
				    	for(var j=0;j<defdate.length;j++){
							var markevent=null;
				    		$.each(markevents,function(i,o){
				    			if(item.userid==o.userid){
									if(defdate[j]==o.marktime){
										markevent=o;
									}	
								}
					    	});
							if(markevent!=null){
								tooltipmarkevent[j]=markevent.title;
								contentdata.push(markevent);
							}else{
								contentdata.push(null);
							}	
						}
				    	dateMark[seriesLength]=contentdata;
				    	optiondetail.series[seriesLength].data=prepare(contentdata,optiondetail,index);
					    });
				    }
			//end content
				}
			if(show_thrend=='1'){
		 	 length = optiondetail.series.length;
			 thrend=length;
			 optiondetail.series[length]={};
			 optiondetail.series[length].dashStyle='shortdot';
			 optiondetail.series[length].data = newUsertrend;	
			optiondetail.series[length].name =  "<?php echo lang('V_Trendvalue')?>";
			}
			optionsLength=optiondetail.series.length;
			 chartdetail = new Highcharts.Chart(optiondetail);
			 chart_detailcanvas.unblock();
			});
}

//content data
function contentCharts(data){
		var categories = [];		
		var trendobj = data.trendcontent;
		var obj = data.content;
	    for(var i=0;i<obj.length;i++)
	    {			  
		   newUser.push(parseInt(obj[i].newusers,10));	
		   totalUser.push(parseInt(obj[i].allusers,10));
		   activeUser.push(parseInt(obj[i].startusers,10));			  
		   sessionNum.push(parseInt(obj[i].sessions,10));				    
		   var usagetime ;
		   var usagetimetrend;
		   //show thrend
		   newUsertrend.push(parseInt(trendobj[i].newusers,0));
		   totalUsertrend.push(parseInt(trendobj[i].allusers,10));
		   activeUsertrend.push(parseInt(trendobj[i].startusers,10));			  
		   sessionNumtrend.push(parseInt(trendobj[i].sessions,10));
		   if(obj[i].sessions==0){
			   usagetimetrend = 0;
       			usagetime = 0;
			   }else{
				   if(trendobj[i].sessions!=0){
					   usagetimetrend=(trendobj[i].usingtime/trendobj[i].sessions)/1000;
				   }else{
					   usagetimetrend=0;
					   }
				  
       			usagetime = (obj[i].usingtime/obj[i].sessions)/1000;
				}
			  	avgUsagettrend.push(parseFloat(parseFloat(usagetimetrend,10).toFixed(2)));	  
	       		avgUsage.push(parseFloat(parseFloat(usagetime,10).toFixed(2)));	
			    categories.push(obj[i].datevalue.substr(0,10));
			    optiondetail.xAxis.labels.step = parseInt(categories.length/10);
				optiondetail.xAxis.categories = categories;  
				optiondetail.title.text = "<?php echo $reportTitle['newUser'] ?>";
	    }		   
	    category=categories;
	     optiondetail.series[0]={};
		 optiondetail.series[0].data = newUser;	
		 optiondetail.series[0].name = chartDetailName;
		 tooltipdata=newUser;
		 tooltiptrenddata=newUsertrend;
}

//compare product
function compareCharts(data){
	var categories = [];
	optiondetail.legend.enabled=true;
	$.each(data,function(index,item){
		var obj = item.content;
		productNames[index]=item.name;
		newUser[index]=[];
		totalUser[index]=[];
		activeUser[index]=[];
		sessionNum[index]=[];
		avgUsage[index]=[];
	    for(var i=0;i<obj.length;i++)
	    {			  
		   newUser[index].push(parseInt(obj[i].newusers,10));	
		   totalUser[index].push(parseInt(obj[i].allusers,10));
		   activeUser[index].push(parseInt(obj[i].startusers,10));			  
		   sessionNum[index].push(parseInt(obj[i].sessions,10));				    
		   var usagetime ;	
       		if(obj[i].sessions==0)
           	{
       			usagetime = 0;
       		}
       		else
           	{
       			usagetime = (obj[i].usingtime/obj[i].sessions)/1000;
           	}
       		avgUsage[index].push(parseFloat(parseFloat(usagetime,10).toFixed(2)));	
		    if(index==0){
			    categories.push(obj[i].datevalue.substr(0,10));
			    optiondetail.xAxis.labels.step = parseInt(categories.length/10);
				optiondetail.xAxis.categories = categories;  
				optiondetail.title.text = "<?php echo $reportTitle['newUser'] ?>";
		    }
	    }
	    //alert(newUser[index]);		   
	     optiondetail.series[index]={};
		 optiondetail.series[index].data = newUser[index];	
		 optiondetail.series[index].name = item.name;//+':'+chartDetailName;
		 optiondetail.tooltip.formatter=null;
		 optiondetail.tooltip.shared=true;
		});//end each
}

</script>
<script type="text/javascript">

function changeChartName(name)
{	
	chartDetailName = name;	
	if(chartDetailName=="<?php echo  lang('t_newUsers')?>")
	{
		if(productNames.length==0){
			 optiondetail.series[0].data = newUser;			 
			 optiondetail.title.text = "<?php echo $reportTitle['newUser'] ?>";	
			 optiondetail.series[0].name = chartDetailName;	

			 optiondetail.series[thrend]={};
			 optiondetail.series[thrend].dashStyle='shortdot';
			 optiondetail.series[thrend].data = newUsertrend;	
			 optiondetail.series[thrend].name =  "<?php echo lang('V_Trendvalue')?>";
			 tooltipdata=newUser;
			 tooltiptrenddata=newUsertrend;
			 for(var j=0;j<markEventIndex.length;j++){
					if(thrend!=markEventIndex){
						optiondetail.series[markEventIndex[j]].data=prepare(dateMark[markEventIndex[j]],optiondetail,j);
					}
				}
			 
		}else{
			for(var i=0;i<productNames.length;i++){
				 optiondetail.series[i].data = newUser[i];			 
				 optiondetail.title.text = "<?php echo $reportTitle['newUser'] ?>";	
				 optiondetail.series[i].name = productNames[i];//+':'+chartDetailName;	
				}
			}
		
	}	
	if(chartDetailName=="<?php echo  lang('t_accumulatedUsers')?>")
	{
		if(productNames.length==0){
		optiondetail.series[0].data = totalUser;			 
		optiondetail.title.text = "<?php echo $reportTitle['totalUser'] ?>";
		 optiondetail.series[0].name = chartDetailName;		

			optiondetail.series[thrend]={};
			 optiondetail.series[thrend].dashStyle='shortdot';
			 optiondetail.series[thrend].data = totalUsertrend;	
			 optiondetail.series[thrend].name =  "<?php echo lang('V_Trendvalue')?>";
			 tooltipdata=totalUser;
			 tooltiptrenddata=totalUsertrend;
			 for(var j=0;j<markEventIndex.length;j++){
					if(thrend!=markEventIndex){
						optiondetail.series[markEventIndex[j]].data=prepare(dateMark[markEventIndex[j]],optiondetail,j);
						}
				}
		}else{
			for(var i=0;i<productNames.length;i++){
				optiondetail.series[i].data = totalUser[i];			 
				optiondetail.title.text = "<?php echo $reportTitle['totalUser'] ?>";
				 optiondetail.series[i].name =productNames[i];//+':'+ chartDetailName;	
				}
			} 	
		
	}
	if(chartDetailName=="<?php echo  lang('t_activeUsers')?>")
	{
		if(productNames.length==0){
		optiondetail.series[0].data =activeUser ;
		optiondetail.title.text = "<?php echo $reportTitle['activeUser'] ?>";
		 optiondetail.series[0].name = chartDetailName;	

			optiondetail.series[thrend]={};
			 optiondetail.series[thrend].dashStyle='shortdot';
			 optiondetail.series[thrend].data = activeUsertrend;	
			 optiondetail.series[thrend].name =  "<?php echo lang('V_Trendvalue')?>";
			 tooltipdata=activeUser;
			 tooltiptrenddata=activeUsertrend;
			 for(var j=0;j<markEventIndex.length;j++){
					if(thrend!=markEventIndex){
						optiondetail.series[markEventIndex[j]].data=prepare(dateMark[markEventIndex[j]],optiondetail,j);
						}
				}
		}else{
			for(var i=0;i<productNames.length;i++){
				optiondetail.series[i].data =activeUser[i] ;
				optiondetail.title.text = "<?php echo $reportTitle['activeUser'] ?>";
				 optiondetail.series[i].name = productNames[i];//[i]+':'+chartDetailName;		
				}
			}
	}
	if(chartDetailName=="<?php echo  lang('t_sessions')?>")
	{
		if(productNames.length==0){
		optiondetail.series[0].data =sessionNum ;
		optiondetail.title.text = "<?php echo $reportTitle['sessionNum'] ?>";
		 optiondetail.series[0].name = chartDetailName;		

		 optiondetail.series[thrend]={};
			 optiondetail.series[thrend].dashStyle='shortdot';
			 optiondetail.series[thrend].data = sessionNumtrend;	
			 optiondetail.series[thrend].name =  "<?php echo lang('V_Trendvalue')?>";
			 tooltipdata=sessionNum;
			 tooltiptrenddata=sessionNumtrend;
			 for(var j=0;j<markEventIndex.length;j++){
					if(thrend!=markEventIndex){
						optiondetail.series[markEventIndex[j]].data=prepare(dateMark[markEventIndex[j]],optiondetail,j);
						}
				}
		}else{
				for(var i=0;i<productNames.length;i++){
					optiondetail.series[i].data =sessionNum[i] ;
					optiondetail.title.text = "<?php echo $reportTitle['sessionNum'] ?>";
					 optiondetail.series[i].name = productNames[i];//+':'+chartDetailName;	
					}
			}
	}
	if(chartDetailName=="<?php echo  lang('t_averageUsageDuration')?>")
	{
		if(productNames.length==0){
			
		optiondetail.series[0].data =avgUsage ;		
		optiondetail.title.text = "<?php echo $reportTitle['avgUsage'] ?>";	
		optiondetail.series[0].name = chartDetailName;  

		 optiondetail.series[thrend]={};
		 optiondetail.series[thrend].dashStyle='shortdot';
		 optiondetail.series[thrend].data = avgUsagettrend;	
		 optiondetail.series[thrend].name =  "<?php echo lang('V_Trendvalue')?>";
		 tooltipdata=avgUsage;
		 tooltiptrenddata=avgUsagettrend;
			for(var j=0;j<markEventIndex.length;j++){
				if(thrend!=markEventIndex){
					optiondetail.series[markEventIndex[j]].data=prepare(dateMark[markEventIndex[j]],optiondetail,j);
					}
			}
		}else{
			for(var i=0;i<productNames.length;i++){
				 optiondetail.series[i].data =avgUsage[i] ;		
				 optiondetail.title.text = "<?php echo $reportTitle['avgUsage'] ?>";	
				 optiondetail.series[i].name = productNames[i];//+":"+chartDetailName;  
				}
			}
	}
		chartdetail = new Highcharts.Chart(optiondetail);
}
</script>
<?php include 'application/views/manage/pointmark_base.php';?>