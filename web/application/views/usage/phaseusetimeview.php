<section id="main" class="column" style="height:1700px">
  <div style="height:380px;">
		<iframe src="<?php echo site_url() ?>/report/productbasic/addphaseusetimereport"  frameborder="0" scrolling="no"style="width:100%;height:100%;"></iframe>		
  </div>  
  <article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('v_rpt_pb_timeTrendOfUsers_detail')?></h3>
			<span class="relative r" id="export"> <a
				href="javascript:void(0)" onclick="exportphasetime()"
				class="bottun4 hover"><font><?php echo  lang('g_exportToCSV')?></font></a>
			</span>
		</header>

		<table class="tablesorter" cellspacing="0">
			<thead id="timephasetitle">
			   <?php if(isset($type)) {}else{ ?> 
				<tr>
				  <th></th>
				  <th><?php echo  lang('t_activeUsers')?></th>
				  <th><?php echo  lang('t_newUsers')?></th>
				</tr>
				<?php }?>
			</thead>
			<tbody id="timephaseinfo">
			</tbody>
	</table>	
	</article>
	<div class="spacer"></div>
</section>
<script type="text/javascript">
var realtime;
var fromtime;
var totime;
function dealgettime(timephase,fromCurTime,toCurTime)  
{
	//for export
	realtime=timephase;
	fromtime=fromCurTime;
	totime=toCurTime;
	//for export
	var myurl="";
	if(timephase=='any')
	{		
		myurl="<?php echo site_url()?>/report/productbasic/getTypeAnalyzeData/"+timephase+"/"+fromCurTime+"/"+toCurTime;
	}
	else
	{
		myurl = "<?php echo site_url()?>/report/productbasic/getTypeAnalyzeData/"+timephase+"?date="+new Date().getTime();
	}
	jQuery.getJSON(myurl, null, function(data) {
		var msg = "";
		var pname=[];
		<?php if(isset($type)) { ?>//means load compare data
			//
			$('#export').find('a').removeAttr('click');
			$('#export').find('a').removeAttr('click').attr(
					{
						href:'<?php echo site_url()?>/report/productbasic/exportComparePhaseusetime/'+realtime+'/'+fromtime+'/'+totime
					}
					);
			changeDetailtype(data);
			<?php }
			 else
			{ ?>
		
			var phasetimedetail = data.content;		
			for(i=0;i<phasetimedetail.length ;i++)
			{ 			
			  var hour=phasetimedetail[i].hour+":00";
			  msg=msg+"<tr><td>";
			  msg=msg+hour+"</td><td>";
			  msg=msg+phasetimedetail[i].startusers+"</td><td>";
			  msg=msg+phasetimedetail[i].newusers+"</td>";
			  msg=msg+"</tr>";
			} 
			$('#timephaseinfo').html(msg);  
		<?php } ?>   	
	});  
  
}

function changeDetailtype(detaildata){
	var maxlength=0;
	var detailtitlecontent="<tr><th></th>";
	var detailcontent="<tr><td><?php echo  lang('t_date_part')?></td>";
	var obj=detaildata.content;
	for (i=0;i<obj.length;i++) {
		maxlength=obj[i].data.length;
		detailtitlecontent=detailtitlecontent+"<th colspan='2'>"+obj[i].name+"</th>";
		detailcontent=detailcontent+"<td><?php echo  lang('t_activeUsers')?></td>";
		detailcontent=detailcontent+"<td><?php echo  lang('t_newUsers')?></td>";
	}
	detailtitlecontent=detailtitlecontent+"</tr>";
	detailcontent=detailcontent+"</tr>";
	for(i=0;i<maxlength;i++){
		for(j=0;j<obj.length;j++){
			var newobj=obj[j].data;
			if(j==0){
				detailcontent=detailcontent+"<tr><td>"+newobj[i].hour+":00</td>";
			}
			detailcontent=detailcontent+"<td>"+newobj[i].startusers+"</td>";
			detailcontent = detailcontent+"<td>"+newobj[i].newusers+"</td>";
		}
		detailcontent=detailcontent+"</tr>";
	}	
	$('#timephasetitle').html(detailtitlecontent);
	$('#timephaseinfo').html(detailcontent);
} 

function exportphasetime()
{
	window.location.href="<?php echo site_url()?>/report/productbasic/timephaseexport/"+realtime+"/"+fromtime+"/"+totime;	
}

$(document).ready(function() {
	dealgettime("today","","") ;		
});	
</script>