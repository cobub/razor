<?php
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */
?>
<section class="section_maeginstyle" id="highchart"
<?php if(!isset($delete)) {?>
style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;"<?php }?>>
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
	<?php echo lang('v_rpt_re_funnelModel');?></h3>
	</header>
		<div id="container" class="module_content" style="height: 300px"></div>
	</article>	
</section>

<script type="text/javascript">
var options ;
var show_thrend=1;
var show_markevent=1;
var markEventIndex=[];
var myurl='<?php echo site_url()?>/report/funnels/getChartData';
$(document).ready(function() {
	options = {
            chart: {
                renderTo: 'container',
                type:'line'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ' '
            },
            xAxis: {
            	categories:'',
            	labels: {
                    rotation: -45,
                    align: 'right',
                    style: {
                        fontSize: '11px',
                        fontFamily: ' sans-serif'
                    }
                }
            },
            yAxis: {
            	allowDecimals:false,
                title: { text:'<?php echo lang('v_rpt_re_funneleventC');?>'},
                min:0
            },
            plotOptions: {
                column: {
                    pointPadding: 0.3,
                    borderWidth: 0,
                    showInLegend:false
                },series:{
                	cursor:'pointer',
                	events:{
        				click:function(e){
        					if(show_markevent=='1'){
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
                    }
            },
            tooltip: {
                    crosshairs: true,
                     shared: true
                  ,
	            formatter:function(){
	            	var msg='';
		            if(typeof(this.points)!='undefined'&&this.points.length>0){
			            msg='<?php echo lang("g_date")?>:'+this.x+'<br/>';
						for(i=0;i<this.points.length;i++){
							var point=this.points[i];
							var unitprice=point.total==null?0:point.total;
							msg+='<?php echo lang('v_rpt_re_funnelTarget');?>:'+point.series.name +','+'<?php echo lang("v_rpt_re_count");?>'+':'+point.y+","+"<?php echo lang('v_rpt_re_unitprice');?>:"+unitprice+"<br/>";
							}
						return msg;
			            }
		            return msg;
	                }
	        },
            credits: {
                enabled: false
            },
            series: [
                
            ]
        };
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
        	if(typeof data.common!='undefined'){
				show_thrend=data.common.show_thrend;
				show_markevent=data.common.show_markevent;
				//means compare ways
				compareProductContent(data.result);
            }else{
            	contentConversion(data);
             }
    	    chart = new Highcharts.Chart(options);
    		chart_canvas.unblock();
    		});  
    }
//produc
function compareProductContent(dataresult){
	var time_array =[];
	
	$.each(dataresult,function(index,item){
		var counts=[];
		if(index==0){//push time_array
			for(var date in item.unitprice[0]){
				time_array.push(date);
				}
			}
			for(var date in item.date){
				for(var u in item.unitprice[0]){
						if(date==u){
						counts.push({y:item.date[u],unitprice:item.unitprice[0][u],total:item.unitprice[0][u]});
						}
					}
			}
			var l=options.series.length;
			options.series[l] = {};
     	    options.series[l].data = counts;
     	    options.series[l].name = item.name;
     	    if(index==1){
     	    	options.xAxis.categories = time_array; 
     	    	options.xAxis.labels.step = parseInt(time_array.length/10);
         	   }
		});
	options.title.text = '<?php echo lang('v_rpt_re_funnelTargettrend');?>';
	options.subtitle.text = '<?php echo $reportTitle['timePhase'];?>';

}
//
function contentConversion(data){
	var dataList=data.dataList;
	var time_array =[];
      	 for(var j=0;j<dataList.length;j++)
         {
         	options.series[j] = {};
     	    options.series[j].data = dataList[j].eventnum;
     	    options.series[j].name = dataList[j].targetname;
     	    var num_array = [];
     	    var temp_item = dataList[j];
     	    for(var k=0;k<temp_item.eventnum.length;k++)
     	    {
         	    if(dataList[j].eventnum[k]!=null)
         	    {
     	    	num_array.push({y:parseInt(dataList[j].eventnum[k]),unitprice:dataList[j]['unitprice'],total:dataList[j]['unitprice']});
         	    }
         	}
         	options.series[j].data = num_array;
         	options.series[j].unitprice=dataList[j]['unitprice'];
         }
        	for(var i=0;i<data.defdate.length;i++){
        		time_array.push(data.defdate[i]);
            }
        	options.xAxis.categories = time_array; 
     	    options.xAxis.labels.step = parseInt(time_array.length/10);
	options.title.text = '<?php echo lang('v_rpt_re_funnelTargettrend');?>';
	options.subtitle.text = '<?php echo $reportTitle['timePhase'];?>';
	 //content markevent
    var marklist=data.marklist;
    var defdate=data.defdate;
    var markevents=data.markevents;
    if(marklist.length>=1){
    	$.each(marklist,function(index,item){
	    	var seriesLength=options.series.length;
	    	markEventIndex[index]=seriesLength;
	    	options.series[seriesLength]={};
	    	options.series[seriesLength].type='column';
	    	options.series[seriesLength].name="<?php echo lang('m_dateevents');?>";
	    	//options.plotOptions.column.showInLengend=false;
	    	options.colors=[];
	    	options.colors[seriesLength]="#DB9D00";
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
					contentdata.push(markevent);
				}else{
					contentdata.push(null);
					}	
			}
	    	options.series[seriesLength].data=prepare(contentdata,options,index);
		    });
	    }
//end content
}
</script>
<script type="text/javascript">
function addreport()
{	
	if(confirm( "<?php echo  lang('w_isaddreport')?>"))
	{
		var reportname="conversions";
	    var reportcontroller="funnels";
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
		window.parent.deletereport("conversions"); 	 
	 	 	
	}
	return false;
	
}	
</script>
<?php include 'application/views/manage/pointmark_base.php';?>