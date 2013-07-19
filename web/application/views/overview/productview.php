<section id="main" class="column" style="overflow-x:hidden;">
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
    				<td><?php echo $today1->allusers;?></td> 
    				<td><?php echo $today1->allsessions;?></td> 
    				<td><?php if(empty($overall->week_activeuser)){echo 0;}else{echo $overall->week_activeuser;} ?></td> 
    				<td><?php if(empty($overall->week_percent)){echo '0.0%';}else{echo round($overall->week_percent*100,1).'%';}?></td>
    				<td><?php if(empty($overall->month_activeuser)){echo 0;}else{echo $overall->month_activeuser;} ?></td>
					<td><?php if(empty($overall->month_percent)){echo '0.0%';}else{echo round($overall->month_percent*100,1).'%';}?></td>
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
					 changesectionheight(-1);     					
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
    	changesectionheight(reportinfo.length);
		var realtype;
		var reporthtml="";
		var divclass;
		var src;
		for(i=0;i<reportinfo.length;i++)
		{		
			src="<?php echo site_url() ?>"+reportinfo[i].src;	
			reporthtml = reporthtml+ "<iframe id='"+reportinfo[i].reportname+"' src='"+src+"/del/'   frameborder='0' scrolling='no'style='width:100%;height:"+reportinfo[i].height+"px;margin: 10px 3% 0 0.3%;'>";
			reporthtml = reporthtml+"</iframe>";		
		}
		$('#addreportregion').html(reporthtml);
    }	
    else
    {
    	changesectionheight(0);
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
			//get checkbox checked num
			 var checknum=0
			 for (var i = 0; i < item.length; i++)  
			 {	       		                  	        
			    if(item[i].checked==true)				    		    	  
			    { 		    	  	 
				    checknum++			   		   
				}			   	   	     
			 }			
			 changesectionheight(checknum);
			 //deal with add or delete reports
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
<!-- adjust the height -->
<script type="text/javascript">
function changesectionheight(reportnum)
{
	if(reportnum!=0&&reportnum!=-1)
	{
		if(reportnum<=2)
		{
			var realheight=1300+550*reportnum;				
			document.getElementById("main").style.height =''+realheight+'px';	
			document.getElementById("sidebar").style.height =''+realheight+'px';
		}
		else
		{
			var realheight=1300+500*reportnum;				
			document.getElementById("main").style.height =''+realheight+'px';
			document.getElementById("sidebar").style.height =''+realheight+'px';
		}
							
	}
   else if(reportnum==-1)
   {
	   var cstr=document.getElementById("main").style.height;	  
	   var clength=cstr.length;	  
	   var cheight=cstr.substring(0, clength-2);	   
	   var realheight=parseInt(cheight)+500*reportnum; 
	   document.getElementById("main").style.height =''+realheight+'px';	
	   document.getElementById("sidebar").style.height =''+realheight+'px'; 
   }	
	else
	{
		document.getElementById("main").style.height ="1300px" ;
		document.getElementById("sidebar").style.height ="1300px" ;			
	}	
}
</script>
<!-- adjust the height -->
