<section class="column"  id="main">
	<h4 class="alert_warning"  id="msg"> 当前在线用户数：<?php echo $onlineuser;?></h4>

	<article class="module width_full" style='width:1040px;'>
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
             '#A47D7C', '#B5CA92','#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', 
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
      	    var loading_img = $("<img src='<?php echo base_url();?>assets/images/loader.gif'/>");
      		   
      	  
      	    
      	 	jQuery.getJSON(myurl, null, function(data) {
      	 	 // alert(data.dataList[0].date);
      	 	 // alert(data);
      	 	
          	 	    for(var i=0;i<data.headList.length;i++){
          	 	    	var appdata =[];
          	 	    	var categories=[];

          	 	    	options.series[i] = {};
          	 	    	options.series[i].name =data.headList[i];
          	 	    	for(var j=data.dataList.length-1;j>=0;j--){
          	 	    		categories.push(data.dataList[j].date);
          	 	    		
          	 	    			appdata.push(parseInt( data.dataList[j].datas[i],10));
          	 	    		
          	 	    	}
          	 	    options.series[i].data = appdata;
					options.xAxis.labels.step = parseInt(categories.length/10);
					options.xAxis.categories = categories; 
					<!--options.title.text = <?php echo lang('getui_data');?>;-->
          	 	    }
          	 	    chart = new Highcharts.Chart(options);
          		
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

