<section class="section_maeginstyle"  id="highchart"
<?php if(!isset($delete)) {?>
style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;"<?php }?>>
		<h4 class="alert_success" id='msg' style="display:none;"></h4>
<article class="module width_full">
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
     <?php echo  lang('v_rpt_ve_changingTrends')?></h3>	  
		<ul class="tabs2">
				<li><a  href="javascript:changeChartType('<?php echo  lang('t_newUsers')?>')"><?php echo  lang('t_newUsers')?></a></li>
				<li><a  href="javascript:changeChartType('<?php echo  lang('t_activeUsers')?>')"><?php echo  lang('t_activeUsers')?></a></li>
		</ul>	
	  </header>
	     <div id="container"  class="module_content" style="height:300px">
		</div>
		<div class="clear"></div>	
	</article>	
</section>
<script type="text/javascript">
var chartName = '<?php echo  lang('t_newUsers')?>';
var chart;
//When page loads...
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
var options;
var ismarktochart=false;
var markEventIndex=[];//array of datemark index
var dateMark=[];// array of datemarke data
var thrend=0;	//thrend index
var category=[];
var tooltipmarkevent=[];
var tooltipdata=new Array(new Array(),new Array());
var tooltipname=new Array(new Array(),new Array());
var colors=['#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', '#DB843D', '#92A8CD', 
             '#A47D7C', '#B5CA92','#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', 
             '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92'];
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
                        return Highcharts.numberFormat(this.value, 0);
                    }
                }
            },
            tooltip: {
	            crosshairs: true,
	            formatter: function() {
			        var content=this.x+'<br>';
			        var m=0;
			        for(var i=0;i<category.length;i++){
				        if(category[i]==this.x){
					        m=i;
					        break;
				        }
				    }
			        if(this.series.name=='<?php echo lang('m_dateevents');?>'){
                           content=tooltipmarkevent[m];
                    }else{
	                    for(var j=0;j<tooltipname.length;j++){
		                    content=content+'<span style="color:'+colors[j]+'">'+tooltipname[j]+'</span>:'+tooltipdata[j][m]+'<br>';
		                }
                    }
			        return content;
		        }
	        },
	        credits:{
				enabled:false
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
	var myurl="<?php echo site_url()?>/report/version/getVersionData";
	renderCharts(myurl);
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
    	var version_array = [];
    	for(var key in data.content)
    	{
        	version_array.push(key);
        }
		for(var j=0;j<version_array.length;j++)
		{
    		version = version_array[j];
    		var eachVersionData = data.content[version];
    		var categories = [];
			var newUsers = [];
			var reportTitle;
    		for(var i=0;i<eachVersionData.length;i++)
    		{
        		var eachVersionDataItem = eachVersionData[i];           		
        		if(chartName=="<?php echo  lang('t_newUsers')?>")
        		{
       			 newUsers.push(parseInt(eachVersionDataItem.newusers,10));
      		     reportTitle="<?php echo $reportTitle['newUser'] ?>";
            	}            		   
        		if(chartName=="<?php echo  lang('t_activeUsers')?>")
        		{
        			newUsers.push(parseInt(eachVersionDataItem.startusers,10));
            		reportTitle="<?php echo $reportTitle['activeUser'] ?>";
            	}            		
	    		categories.push(eachVersionDataItem.datevalue.substr(0,10));
        	}
    		options.series[j] = {};
    		if(version == "")
		    {
		    	options.series[j].name = "<?php echo  lang('t_unknow')?>";
		    }
		    else
		    {
    		    options.series[j].name =version;
		    }
		    category=categories;
		    tooltipdata[j]= newUsers;	 
		    tooltipname[j]= options.series[j].name;
		    options.series[j].data = newUsers;
			options.xAxis.labels.step = parseInt(categories.length/10);
			options.xAxis.categories = categories; 
			options.title.text = reportTitle;
    	}
		 //content markevent
		    var marklist=data.marklist;
		    var defdate=data.defdate;
		    var markevents=data.markevents;
		    if(!ismarktochart){
		    	$.each(marklist,function(index,item){
			    	if(index>0){return;}
			    	ismarktochart=true;
			    	var length=options.series.length;
			    	markEventIndex[index]=length;
		    		options.series[length]={};
		    		options.series[length].type='column';
			    	options.series[length].name="<?php echo lang('m_dateevents');?>";
			    	options.colors=[];
			    	options.colors[length]="#DB9D00";
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
					dateMark[length]=contentdata;
			    	options.series[length].data=prepare(contentdata,options,index);
				    });
			    }
		//end content    	    
	    chart = new Highcharts.Chart(options);
		chart_canvas.unblock();
		});  
    }
  	    
</script>
<script type="text/javascript">
function changeChartType(type)
{	
	chartName = type;
	var myurl="<?php echo site_url()?>/report/version/getVersionData";
	renderCharts(myurl);
}
</script>
<script type="text/javascript">
function addreport()
{	
	if(confirm( "<?php echo  lang('w_isaddreport')?>"))
	{
		var reportname="versionview";
	    var reportcontroller="version";
	    var data={ 
	    		 reportname:reportname,
  		  	     controller:reportcontroller,
	  		  	 height    :400,
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
		window.parent.deletereport("versionview");		 	 	   
	}
	return false;
	
}
</script>

<?php include 'application/views/manage/pointmark_base.php';?>