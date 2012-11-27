<section id="main" class="column" style="height:5300px;overflow-x:hidden;">
		<?php if(isset($message)):?>
		<h4 class="alert_success"><?php echo $message;?></h4>		
		<?php endif;?>
		
		<article class="module width_full">
			<header><h3><?php echo lang('v_rpt_pb_overviewRecently') ?></h3>					
			</header>
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th></th> 
    				<th><?php echo lang('t_sessions') ?></th> 
    				<th><?php echo lang('t_activeUsers') ?></th> 
    				<th><?php echo lang('t_newUsers') ?></th> 
    				<th><?php echo lang('t_percentOfNewUsers') ?></th>
    				<th><?php echo lang('t_upgradeUsers') ?></th>
    				<th><?php echo lang('t_averageUsageDuration') ?></th>
				</tr> 
			</thead> 
			<tbody> 
			<?php if(isset($today1)):?>
			<tr> 
    				<td><?php echo lang('g_today') ?></td> 
                    <td><?php echo $today1->sessions;?></td> 
    				<td><?php echo $today1->startusers;?></td> 
    				<td><?php echo $today1->newusers;?></td> 
    				<td><?php echo percent($today1->newusers,$today1->startusers)?></td>
    				<td><?php echo $today1->upgradeusers;?></td>
    				<td><?php echo round(($today1->usingtime/$today1->sessions)/1000,2).lang('g_s');?></td>
    				
				</tr> 
			<?php endif;?>
			
			<?php if(isset($yestoday)):?>
			<tr> 
    				<td><?php echo lang('g_yesterday') ?></td> 
    				 <td><?php echo $yestoday->sessions;?></td> 
    				<td><?php echo $yestoday->startusers;?></td> 
    				<td><?php echo $yestoday->newusers;?></td> 
    				<td><?php echo percent($yestoday->newusers,$yestoday->startusers)?></td>
    				<td><?php echo $yestoday->upgradeusers;?></td>
    				<td><?php echo round(($yestoday->usingtime/$yestoday->sessions)/1000,2).lang('g_s');?></td>
				</tr> 
			<?php endif;?>
			</tbody>			
			</table>
		</article>
		
			<article class="module width_full">
			<header><h3><?php echo lang('v_rpt_pb_generalSituation') ?></h3>			
			</header>
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo lang('t_accumulatedUsers') ?></th> 
    				<th><?php echo lang('t_accumulatedStarts') ?></th> 
    				<th><?php echo lang('t_activeUsersWeekly') ?></th> 
    				<th><?php echo lang('t_activeRateWeekly') ?></th>
    				<th><?php echo lang('t_activeUsersMonthly') ?></th>
    				<th><?php echo lang('t_activeRateMonthly') ?></th>
				</tr> 
			</thead> 
			<tbody> 
			<?php if(isset($overall)):?>
			<tr> 
    				<td><?php echo $overall['alltime'];?></td> 
    				<td><?php echo $today1->allsessions;?></td> 
    				<td><?php echo $overall['7dayactive'];?></td> 
    				<td><?php if($overall['alltime']==0){echo '0.0%';}else{echo percent($overall['7dayactive'],$overall['alltime']);} ?></td>
    				<td><?php echo $overall['1month'];?></td>
					<td><?php if($overall['alltime']==0){echo '0.0%';}else{echo percent($overall['1month'],$overall['alltime']);} ?></td>
				</tr> 
			<?php endif;?>
			</tbody>			
			</table>
		</article>		 
		<div style="height:330px;">
		<iframe src="<?php echo site_url() ?>/report/productbasic/adduserbehavorviewreport"  frameborder="0" scrolling="no"style="width:100%;height:100%;"></iframe>		
		</div>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('v_rpt_pb_userDataDetail')?></h3>
				<span class="relative r">
				<a href="<?php echo site_url(); ?>/report/productbasic/exportdetaildata" class="bottun4 hover" >
				<font><?php echo  lang('g_exportToCSV');?></font></a>
			</span>					
		</header>
		
		<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo  lang('g_date')?></th> 
    				<th><?php echo  lang('t_newUsers')?></th> 
    				<th><?php echo  lang('t_accumulatedUsers')?></th> 
    				<th><?php echo  lang('t_activeUsers')?></th> 
    				<th><?php echo  lang('t_sessions')?></th>
    				<th><?php echo  lang('t_averageUsageDuration')?></th>
				</tr> 
			</thead> 
			<tbody id="content">		     
	    <?php $num = count($dashboardDetailData);?>	    	
			</tbody>
		</table> 
		
		<footer>
		<div id="pagination"  class="submit_link">
		</div>
		</footer>
	</article>	
	<div id="addreportregion" style="width:100%">
	</div>	
	<div class="clear"></div>
	<div class="spacer"></div>	
	<div id="btn" style="margin: 10px 3% 0 3%;">
	<table>
	<tr>
	<td width="30%"><a href="javascript:void(0)" class="run" onclick="addwidgetsreport()" id="fullBtn"><?php echo lang('w_addfullreport') ?></a></td>
	
	</tr>
	</table>
	</div>
	
		
</section>
	
<script>
//When page loads...
$(document).ready(function() {	
    initPagination();
	pageselectCallback(0,null);	
	addreportwidgets();
});

function deletereport(deletename)
{	
 $('#'+deletename).remove();
  var data={ 
	       reportname:deletename,
	       type:1
	     };
   jQuery.ajax({
				type :  "post",
				url  :  "<?php echo site_url()?>/report/dashboard/deleteshowreport",	
				data :  data,			
				success : function(msg) {	    					
				},
				error : function(XmlHttpRequest, textStatus, errorThrown) {
					alert("<?php echo lang('t_error') ?>");
				}
			});
}
</script>
<script type="text/javascript">
function addreportwidgets()
{
	var reportinfo=eval(<?php if(isset($addreport)){$report=$addreport->result();echo "'".json_encode($report)."'";}?>);
	if(reportinfo!=eval())
	{	
		var realtype;
		var reporthtml="";
		var divclass;
		for(i=0;i<reportinfo.length;i++)
		{			
			reporthtml = reporthtml+ "<iframe id='"+reportinfo[i].reportname+"' src='"+reportinfo[i].src+"/del/'   frameborder='0' scrolling='no'style='width:100%;height:"+reportinfo[i].height+"px;margin: 10px 3% 0 0.3%;'>";
			reporthtml = reporthtml+"</iframe>";		
		}
		$('#addreportregion').html(reporthtml);
    }	
}
</script>
<script type="text/javascript">
var dashboarddetaildata = eval(<?php echo "'".json_encode($dashboardDetailData)."'"?>);
function pageselectCallback(page_index, jq){			
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";   
	var index = page_index*<?php echo PAGE_NUMS?>;
	var pagenum = <?php echo PAGE_NUMS?>;	
	var msg = "";	
	for(i=0;i<pagenum && (index+i)<dashboarddetaildata.length ;i++)
	{ 
		 var avgusagetime ;
 		if(dashboarddetaildata[i+index].start==0)
     	{
 			avgusagetime = 0;
 		}
 		else
     	{
 			avgusagetime =(dashboarddetaildata[i+index].aver/dashboarddetaildata[i+index].start)/1000;
     	}
		msg = msg+"<tr><td>";
		msg = msg+ dashboarddetaildata[i+index].date;
		msg = msg+"</td><td>";
		msg = msg+ dashboarddetaildata[i+index].newuser;
		msg = msg+"</td><td>";
		msg = msg+ dashboarddetaildata[i+index].total;
		msg = msg+"</td><td>";
		msg = msg+ dashboarddetaildata[i+index].active;
		msg = msg+"</td><td>";
		msg = msg+ dashboarddetaildata[i+index].start;
		msg = msg+"</td><td>";
		msg = msg+ (avgusagetime).toFixed(2)+"<?php echo lang('g_s') ?></td>";
		msg = msg+"</tr>";
	}
	$('#content').html(msg);
   //document.getElementById('content').innerHTML = msg;				
   return false;
 }
           
/** 
* Callback function for the AJAX content loader.
 */
function initPagination() {
  var num_entries = <?php if(isset($num)) echo $num; ?>/<?php echo PAGE_NUMS;?>;
  // Create pagination element
  $("#pagination").pagination(num_entries, {
     num_edge_entries: 2,
     prev_text: '<?php echo  lang('g_previousPage')?>',
     next_text: '<?php echo  lang('g_nextPage')?>',           
     num_display_entries: 4,
     callback: pageselectCallback,
     items_per_page:1               
           });
             }
      
</script>
<!-- easydialog -->
<script type="text/javascript">

//add  widgets
 function addwidgetsreport()
  {	
	easyDialog.open({
		container : {
			header : '<?php echo  lang('w_addreport'); ?>',
			content :'<iframe id="widgetslist"  src="<?php echo site_url(); ?>/report/dashboard/loadwidgetslist" frameborder="0" scrolling="no" style="height:400px;"></iframe>',
			yesFn :addreportwidget ,
			noFn : true
		}, 
		fixed : false
	});
}

 var addreportwidget = function(){	
		var obj ;
		var reportvalue;
		 if (document.all)
		 {    //IE
			 obj = document.frames["widgetslist"].document;
	     }
		 else
		 {
			 //Firefox    
			 obj = document.getElementById("widgetslist").contentDocument;
	     }	  
		var item = obj.getElementsByName("reportname"); 
		var canadd=obj.getElementById("overnum").innerHTML; 
		if(canadd=="")
		{
			var reportgroup = new Array();  
		    for (var i = 0; i < item.length; i++)  
		    {
		    	var str = item[i].value;  
		    	var report=str.split("/");
		        var reportcontroller=report[0];
		        var reportname=report[1];			  	    
		        var height= report[2];		          	        
		        if(item[i].checked==true && document.getElementById(reportname)==null)				    		    	  
		    	{            	 
			    	var data={
				  		  	     reportname:reportname,
				  		  	     controller:reportcontroller,
					  		  	 height    :height,
					  		  	 type      :1
					  		  	 
				  		  	    }; 	  
						jQuery.ajax({
										type :  "post",
										url  :  "<?php echo site_url()?>/report/dashboard/addshowreport",	
										data :  data,			
										success : function(msg) {
										if(msg)
										{											
											document.getElementById("addreportregion").innerHTML+=msg;																								
										}		 
										},
										error : function(XmlHttpRequest, textStatus, errorThrown) {
											alert("<?php echo lang('t_error') ?>");
										}
									});					   		   
				 }	
		    	
		    	if(document.getElementById(reportname)!=null&&item[i].checked==false)
				 {    
							var div = document.getElementById(reportname);
						    var parent = div.parentElement;
						    parent.removeChild(div);    		    	
						    	  var data={
						  		  	     reportname:reportname,
						  		  	     type:1
						  		  	    };
								jQuery.ajax({
												type :  "post",
												url  :  "<?php echo site_url()?>/report/dashboard/deleteshowreport",	
												data :  data,			
												success : function(msg) {						       						        		
												},
												error : function(XmlHttpRequest, textStatus, errorThrown) {
													alert("<?php echo lang('t_error') ?>");
												}
											}); 
				}			    
						
			   	   	     
		    }	     
		 
		}
		else
		{
			return false;
		}
 };	    


</script>
<!-- easydialog --> 

