<link rel="stylesheet" href="<?php echo base_url();?>assets/css/easydialog.css" type="text/css" media="screen"/>
<script
	src="<?php echo base_url();?>assets/js/easydialog/easydialog.min.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/highslide-full.min.js" type="text/javascript">
</script>
<script src="<?php echo base_url();?>assets/js/highslide.config.js" type="text/javascript">
</script>
<link href="<?php echo base_url();?>assets/css/highslide.css" type="text/css" rel="stylesheet"/>

<script type="text/javascript">
var event_sk = "<?php echo $event_sk?>";
var version = "<?php echo $event_version?>";
var event_name = "<?php echo $event_name?>";
</script>

<section id="main" class="column">
<article class="module width_full">
<header>
<h3 class="tabs_involved"><font color="#787878"><?php echo $event_name." "?></font><?php echo  lang('v_rpt_el_eventStatistics')?></h3>
<ul class="tabs2">
   	<li><a id='eventnum' href="javascript:changeChartData('<?php echo  lang('v_rpt_el_eventMsgs')?>')"><?php echo  lang('v_rpt_el_eventMsgs')?></a></li>
	<li><a id='eventnumperactiveuser' href="javascript:changeChartData('<?php echo  lang('v_rpt_el_MsgsInActive')?>')"><?php echo  lang('v_rpt_el_MsgsInActive')?></a></li>
	<li><a id='eventnumperstartnum' href="javascript:changeChartData('<?php echo  lang('v_rpt_el_MsgsInSessions')?>')"><?php echo  lang('v_rpt_el_MsgsInSessions')?></a></li>
	
</ul>

</header>
<div class="module_content">
         <div id="container"  class="module_content" style="height:300px">
		</div>
</div>

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
var reportType;
var eventMsgNum=[];
var eventMsgNumActive=[];
var eventMsgNumSession=[];

var trendeventMsgNum=[];
var trendeventMsgNumActive=[];
var trendeventMsgNumSession=[];

var markEventIndex=[];
var dateMark=[];
var threndIndex=-1;
var thrend=0;
var category=[];
var tooltipdata=[];
var tooltiptrenddata=[];
var tooltipname='';
var tooltipmarkevent=[];
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
                        return this.value;
                    }
                }
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
                                content=content+'<span style="color:#4572A7">'+tooltipname+'</span>:'+tooltipdata[i]+'<br>';
                                content=content+'<span style="color:#89A54E"><?php echo lang('V_Trendvalue')?></span>'+':'+tooltiptrenddata[i];
                            }                 
                        }
                    }
                    return content;
                 }
	        },
            plotOptions: {
                column:{
					showInLegend:false
                  },
                spline: {
                    cursor:'pointer',
                    marker: {
                        radius: 1,
                        lineColor: '#666666',
                        lineWidth: 1
                    },events:{
						click:function(e){
							sendBack(e);
							}
                        }
                },series:{
                	cursor:'pointer',
                	events:{
        				click:function(e){
        					if(!markEventIndex.content(e.point.series.index)){
        						sendBack(e);
        						return;
        						}
    						var rights=e.point.rights==1?'<?php echo lang('m_public')?>':'<?php echo lang('m_private')?>';
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
            },
            legend:{
                labelFormatter: function() {
                	return this.name
                }
             },
            series: [
        
            ]
        };
	var myurl = "<?php echo site_url().'/report/eventlist/getChartDataAll/'?>"+event_sk+"/"+version;
	renderCharts(myurl);
});

</script>
<script type="text/javascript">
var trenddata;
var optionsLength=0;
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
        	chartData = data.dataList;
        	trenddata = data.trend;
    		var newUsers = [];
    		var categories = [];
    	    for(var i=0;i<chartData.length;i++)
    	    {
    		    var marketData = chartData[i];    		
    		    eventMsgNum.push(parseFloat(marketData.count));
    		    eventMsgNumActive.push(parseFloat(parseFloat(marketData.userper).toFixed(2)));    	    			
    		    eventMsgNumSession.push(parseFloat(parseFloat(marketData.sessionper).toFixed(2))); 

    		    trendeventMsgNum.push(parseFloat(trenddata[i].count));
    		    trendeventMsgNumActive.push(parseFloat(parseFloat(trenddata[i].userper).toFixed(2)));    	    			
    		    trendeventMsgNumSession.push(parseFloat(parseFloat(trenddata[i].sessionper).toFixed(2)));
    		       		  
    		    categories.push(marketData.datevalue.substr(0,10));
    		}
    	    category=categories;
    	    tooltipname=event_name;
    	    tooltipdata=eventMsgNum;
                options.series[0]={};
    		    options.series[0].data = eventMsgNum;
    		    options.series[0].name=event_name;
    		    options.xAxis.labels.step = parseInt(categories.length/10);
    		    options.xAxis.categories = categories; 
    		    options.title.text = "<?php echo  $reportTitle['eventMsgNum'] ; ?>";
    		    optionsLength=1;
    		    //content markevent
			    var marklist=data.marklist;
			    var defdate=data.defdate;
			    var markevents=data.markevents;
			    if(marklist.length>=1){
			    	$.each(marklist,function(index,item){
			    		markEventIndex[index]=options.series.length;
			    		seriesIndex=markEventIndex[index];
				    	options.series[seriesIndex]={};
				    	options.series[seriesIndex].type='column';
				    	options.series[seriesIndex].name="<?php echo lang('m_dateevents');?>";
				    	options.colors=[];
				    	options.colors[seriesIndex]="#DB9D00";
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
						dateMark[seriesIndex]=contentdata;
				    	options.series[seriesIndex].data=prepare(contentdata,options,index);
					    });
				    }
			//end content
				threndIndex=options.series.length;
				thrend=threndIndex;
				tooltiptrenddata=trendeventMsgNum;
			    options.series[threndIndex]={};
    		    options.series[threndIndex].data = trendeventMsgNum;
    		    options.series[threndIndex].name="<?php echo lang('V_Trendvalue')?>";
    		    options.series[threndIndex].dashStyle= 'shortdot';
    		    options.xAxis.labels.step = parseInt(categories.length/10);
    		    options.xAxis.categories = categories; 
				
    	        chart = new Highcharts.Chart(options);
    		    chart_canvas.unblock();
    		});  
    }
  	    
</script>

<script type="text/javascript">
function changeChartData(type)
{ 
	reportType=type;
	if(reportType=='<?php echo  lang('v_rpt_el_eventMsgs')?>')
    {
		 options.series[0].data = eventMsgNum;
		 options.title.text = "<?php echo  $reportTitle['eventMsgNum'] ; ?>";
		 options.series[threndIndex].data = trendeventMsgNum;
		 tooltipdata=eventMsgNum;
		 tooltiptrenddata=trendeventMsgNum;
		 for(var j=0;j<markEventIndex.length;j++){
				if(thrend!=markEventIndex){
					options.series[markEventIndex[j]].data=prepare(dateMark[markEventIndex[j]],options,j);
					}
			}
	     chart = new Highcharts.Chart(options);	
    }

    if(reportType=='<?php echo  lang('v_rpt_el_MsgsInActive')?>')
    {
    	options.series[0].data = eventMsgNumActive;
		 options.title.text = "<?php echo  $reportTitle['eventMsgNumActive'] ; ?>";
		 options.series[threndIndex].data = trendeventMsgNumActive;
		 tooltipdata=eventMsgNumActive;
		 tooltiptrenddata=trendeventMsgNumActive;
		 for(var j=0;j<markEventIndex.length;j++){
				if(thrend!=markEventIndex){
					options.series[markEventIndex[j]].data=prepare(dateMark[markEventIndex[j]],options,j);
					}
			}
	     chart = new Highcharts.Chart(options);	
			
    }
    if(reportType=='<?php echo  lang('v_rpt_el_MsgsInSessions')?>')
    {
    	 options.series[0].data = eventMsgNumSession;
		 options.title.text = "<?php echo  $reportTitle['eventMsgNumSession'] ; ?>";
		 options.series[threndIndex].data = trendeventMsgNumSession;
		 tooltipdata=eventMsgNumSession;
		 tooltiptrenddata=trendeventMsgNumSession;
		 for(var j=0;j<markEventIndex.length;j++){
				if(thrend!=markEventIndex){
					options.series[markEventIndex[j]].data=prepare(dateMark[markEventIndex[j]],options,j);
					}
			}
	     chart = new Highcharts.Chart(options);	
    }
}
</script>
<?php include 'application/views/manage/pointmark_base.php';?>