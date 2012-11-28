<section class="column" style="height:900px;" id="highchart" <?php if(!isset($delete)) {?>
style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;"<?php }?>>
		<?php if(isset($message)):?>
		<h4 class="alert_success"><?php echo $message;?></h4>		
		<?php endif;?>			
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
		<?php echo  lang('v_rpt_ur_retention')?></h3>
		<select id="selectversion" name="selectversion" onchange='changeversion(value)'  style="position:relative;top:5px;display: none;<?php //if(!isset($show_version)){echo 'inline';}else{echo 'none';}?>">
			
			<option value="all" selected><?php echo lang('v_rpt_el_allVersion') ; ?></opton>			
			<?php if(isset($productversion)) 
			{
				foreach ($productversion->result() as $row)
				{?>
				<option value="<?php echo $row->version_name; ?>"><?php echo $row->version_name; ?></option>	
			<?php 	}
			}?>
			
		</select>
			<div class="submit_link">
			<ul class="tabs2" style="position:relative;top:-5px;">				
				<li><a id="week" href="#tab1"><?php echo  lang('t_week')?></a></li>
				<li><a id="month" href="#tab2"><?php echo  lang('t_month')?></a></li>
			</ul>			
			</div>	
		</header>
		<div id="contents">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th><?php echo  lang('v_rpt_ur_firstUseWeek')?></th> 
    				<th><?php echo  lang('t_newUsers')?></th>
    				<th><?php echo  lang('v_rpt_ur_one_weeks')?></th> 
    				<th><?php echo  lang('v_rpt_ur_two_weeks')?></th> 
    				<th><?php echo  lang('v_rpt_ur_three_weeks')?></th> 
    				<th><?php echo  lang('v_rpt_ur_four_weeks')?></th> 
    				<th><?php echo  lang('v_rpt_ur_five_weeks')?></th> 
    				<th><?php echo  lang('v_rpt_ur_six_weeks')?></th> 
    				<th><?php echo  lang('v_rpt_ur_seven_weeks')?></th> 
    				<th><?php echo  lang('v_rpt_ur_eight_weeks')?></th> 
    				
				</tr> 
			</thead> 
			<tbody id='weekdata'> 			
			</tbody> 
			</table>
			<footer>
			<div id="weekpage" class="submit_link"></div>
			</footer>
			</div><!-- end of #tab1 -->			
			<div id="tab2" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th><?php echo  lang('v_rpt_ur_first_use_month')?></th> 
    				<th><?php echo  lang('t_numberofUsers')?></th> 
    				<th><?php echo  lang('v_rpt_ur_one_month')?></th> 
    				<th><?php echo  lang('v_rpt_ur_two_months')?></th> 
    				<th><?php echo  lang('v_rpt_ur_three_months')?></th> 
    				<th><?php echo  lang('v_rpt_ur_four_months')?></th> 
    				<th><?php echo  lang('v_rpt_ur_five_months')?></th> 
    				<th><?php echo  lang('v_rpt_ur_six_months')?></th> 
    				<th><?php echo  lang('v_rpt_ur_seven_months')?></th> 
    				<th><?php echo  lang('v_rpt_ur_eight_months')?></th> 
    				
				</tr> 
			</thead> 
			<tbody id='monthdata'> 			
			</tbody> 
			</table>
			<footer>
			<div id="monthpage" class="submit_link"></div>
			</footer>
			</div><!-- end of #tab2 -->
		</div><!-- end of .tab_container -->	
	</article>
	<div class="clear"></div>
		<div class="spacer"></div>
</section>
<script type="text/javascript">
var color=["","#CCCCFF","#CCCCCC","#999999"];			
			
//When page loads...
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab 
$(".tab_content:first").show(); //Show first tab content

//On Click Event
$("ul.tabs2 li").click(function() {
	$(".tab_content").hide();
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
	$(activeTab).show(); //Fade in the active ID content
	return false;
});
</script>
<script type="text/javascript">
var type=document.getElementById('selectversion').value;
$(document).ready(function() {	
	var userurl  = "<?php echo site_url();?>/report/userremain/getUserRemainweekMonthData/"+type;
	renderUserData(userurl);
});
var weekobj;
var monthobj;
var productNames=[];
var w_timepart=[];
var m_timepart=[];
function renderUserData(myurl)
{	
	 var chart_canvas = $('#contents');
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
	    	weekobj=data.userremainweek;
	    	monthobj=data.userremainmonth;
	    	var weektr="";
	    	var monthtr="";
			if(weekobj.length>1&&typeof(weekobj[0].name)!='undefined'){
			if(weekobj.length>1||monthobj.length>1){
				if(typeof weekobj[0].data!='undefined'){
					$.each(weekobj,function(index,item){
						productNames[index]=item.name;
						$.each(item.data,function(i,o){
							w_timepart[i]={};
							w_timepart[i].startdate=o.startdate;
							w_timepart[i].enddate=o.enddate;
							});
					});
					$.each(monthobj,function(index,item){
						productNames[index]=item.name;
						$.each(item.data,function(i,o){
							m_timepart[i]={};
							m_timepart[i].startdate=o.startdate;
							m_timepart[i].enddate=o.enddate;
							});
					});
					}
				}
			if(weekobj.length>1&&typeof(weekobj[0].data)!='undefined'){
				$('#tab1 th:eq(0)').after('<th><?php echo lang('v_app')?></th>');
				weekMaxlength=w_timepart.length;
				CompareWeekData();
				initWeekPagination();
			    //weektr=CompareWeekData(w_timepart,productNames,weektr,weekobj);
			    chart_canvas.unblock();	
			}
			if(monthobj.length>1&&typeof(monthobj[0].data)!='undefined'){
				$('#tab2 th:eq(0)').after('<th><?php echo lang('v_app')?></th>');
				monthMaxlength=m_timepart.length;
				CompareMonthData();
				//monthtr=CompareMonthData(m_timepart,productNames,monthtr,monthobj);
				initMonthPagination();
				chart_canvas.unblock();	
			}
		}
			else{//content compare data
				var weekuserdata=eval(weekobj);
				var monthuserdata=eval(monthobj);
				for(i=0;i<weekuserdata.length;i++)
				{
					var start = weekuserdata[i].startdate;
					var end   = weekuserdata[i].enddate;
					var showtime = start+"~"+end;
					weektr = weektr+"<tr><td>";
					weektr = weektr + showtime;
					weektr = weektr + "</td><td>";
					weektr = weektr + weekuserdata[i].usercount;			
					weektr = weektr + "</td><td>";
					weektr = weektr + weekuserdata[i].week1;
					weektr = weektr + "</td><td>";
					weektr = weektr + weekuserdata[i].week2;
					weektr = weektr + "</td><td>";
					weektr = weektr + weekuserdata[i].week3;
					weektr = weektr + "</td><td>";
					weektr = weektr + weekuserdata[i].week4;
					weektr = weektr + "</td><td>";
					weektr = weektr + weekuserdata[i].week5;
					weektr = weektr + "</td><td>";
					weektr = weektr + weekuserdata[i].week6;
					weektr = weektr + "</td><td>";
					weektr = weektr + weekuserdata[i].week7;
					weektr = weektr + "</td><td>";
					weektr = weektr + weekuserdata[i].week8;					
					weektr = weektr + "</td></tr>";
				}	
				for(j=0;j<monthuserdata.length;j++)	
				{
					var start = monthuserdata[j].startdate;				
					var end   = monthuserdata[j].enddate;
					var showtime = start+"~"+end;
					monthtr = monthtr+"<tr><td>";
					monthtr = monthtr + showtime;
					monthtr = monthtr + "</td><td>";
					monthtr = monthtr + monthuserdata[j].usercount;			
					monthtr = monthtr + "</td><td>";
					monthtr = monthtr + monthuserdata[j].month1;
					monthtr = monthtr + "</td><td>";
					monthtr = monthtr + monthuserdata[j].month2;
					monthtr = monthtr + "</td><td>";
					monthtr = monthtr + monthuserdata[j].month3;
					monthtr = monthtr + "</td><td>";
					monthtr = monthtr + monthuserdata[j].month4;
					monthtr = monthtr + "</td><td>";
					monthtr = monthtr + monthuserdata[j].month5;
					monthtr = monthtr + "</td><td>";
					monthtr = monthtr + monthuserdata[j].month6;
					monthtr = monthtr + "</td><td>";
					monthtr = monthtr + monthuserdata[j].month7;
					monthtr = monthtr + "</td><td>";
					monthtr = monthtr + monthuserdata[j].month8;					
					monthtr = monthtr + "</td></tr>";
				}
				chart_canvas.unblock();	
				//document.getElementById('weekdata').innerHTML=weektr;	
				$('#weekdata').html(weektr);	
				//document.getElementById('monthdata').innerHTML = monthtr;
				$('#monthdata').html(monthtr);
			}
		return false;
		});  
}
 function changeversion(value)
 {   
	 type=value;	 
	 var userurl  = "<?php echo site_url();?>/report/userremain/getUserRemainweekMonthData/"+type;
	 renderUserData(userurl);
 }
 var weekMaxlength=0;
 var weekStart=0;
 var weekpageindex=0;
 var pagesize=7;
 //compare week data
 function CompareWeekData(){
	 var weektr="";
	 weekStart=(weekpageindex)*pagesize;
	 $.each(w_timepart,function(index,item){
			var sameCount=0;
			if(index>=weekStart&&index<weekStart+pagesize){
				weektr+='<tr><td rowspan='+(productNames.length)+'>'+(item.startdate+'~'+item.enddate)+'</td>';
				$.each(weekobj,function(i,o){										
					weektr+='<td style="background:'+color[i]+'">'+o.name+'</td>';
					if(o.data.length==0){
						weektr+="<td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>";
					}else{
					$.each(o.data,function(idx,week){
						if(item.startdate==week.startdate&&item.enddate==week.enddate){
							sameCount++;
							weektr = weektr + "<td style='background:"+color[i]+"'>";
							weektr = weektr + week.usercount;			
							weektr = weektr + "</td><td style='background:"+color[i]+"'>";
							weektr = weektr + week.week1;
							weektr = weektr + "</td><td style='background:"+color[i]+"'>";
							weektr = weektr + week.week2;
							weektr = weektr + "</td><td style='background:"+color[i]+"'>";
							weektr = weektr + week.week3;
							weektr = weektr + "</td><td style='background:"+color[i]+"'>";
							weektr = weektr + week.week4;
							weektr = weektr + "</td><td style='background:"+color[i]+"'>";
							weektr = weektr + week.week5;
							weektr = weektr + "</td><td style='background:"+color[i]+"'>";
							weektr = weektr + week.week6;
							weektr = weektr + "</td><td style='background:"+color[i]+"'>";
							weektr = weektr + week.week7;
							weektr = weektr + "</td><td style='background:"+color[i]+"'>";
							weektr = weektr + week.week8;					
							weektr = weektr + "</td>";
							}
						});
					if(sameCount==0){
						weektr+="<td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>";
						}
					}
					weektr+='</tr>';
				});
				}
			
		});
	 	$('#weekdata').html(weektr);
	 }


 var monthMaxlength=0;
 var monthStart=0;
 var monthpageindex=0;
 //Compare month data
 function CompareMonthData(){
	 var monthtr="";
	 monthStart=(monthpageindex)*pagesize;
	 $.each(m_timepart,function(index,item){
			var sameCount=0;
			if(index>=monthStart&&index<monthStart+pagesize){
			monthtr+='<tr><td rowspan='+(productNames.length)+'>'+(item.startdate+'~'+item.enddate)+'</td>';
			$.each(monthobj,function(i,o){
				
				monthtr+='<td style="background:'+color[i]+'">'+o.name+'</td>';
				if(o.data.length==0){
					monthtr+="<td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>";
				}else{
				$.each(o.data,function(idx,month){
					if(item.startdate==month.startdate&&item.enddate==month.enddate){
						sameCount++;
						monthtr+="<td style='background:"+color[i]+"'>";
						monthtr = monthtr + month.usercount;			
						monthtr = monthtr + "</td><td style='background:"+color[i]+"'>";
						monthtr = monthtr + month.month1;
						monthtr = monthtr + "</td><td style='background:"+color[i]+"'>";
						monthtr = monthtr + month.month2;
						monthtr = monthtr + "</td><td style='background:"+color[i]+"'>";
						monthtr = monthtr + month.month3;
						monthtr = monthtr + "</td><td style='background:"+color[i]+"'>";
						monthtr = monthtr + month.month4;
						monthtr = monthtr + "</td><td style='background:"+color[i]+"'>";
						monthtr = monthtr + month.month5;
						monthtr = monthtr + "</td><td style='background:"+color[i]+"'>";
						monthtr = monthtr + month.month6;
						monthtr = monthtr + "</td><td style='background:"+color[i]+"'>";
						monthtr = monthtr + month.month7;
						monthtr = monthtr + "</td><td style='background:"+color[i]+"'>";
						monthtr = monthtr + month.month8;					
						monthtr = monthtr + "</td></tr>";
						}
					});
				if(sameCount==0){
					monthtr+="<td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>";
					}
				}
				monthtr+='</tr>';
			});
	 }
		});
	 $('#monthdata').html(monthtr);
	 }
 </script>
<script type="text/javascript">
function addreport()
{	
	if(confirm( "<?php echo  lang('w_isaddreport')?>"))
	{
		var reportname="userremain";
	    var reportcontroller="userremain";
	    var data={ 
	    		 reportname:reportname,
  		  	     controller:reportcontroller,
	  		  	 height    :480,
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
		window.parent.deletereport("userremain");	 	 	
	}
	return false;
	
}
/** 
 * Callback function for the AJAX content loader.
 */
function initWeekPagination() {
    var weeknum_enteries = Math.ceil(weekMaxlength/pagesize);
    // Create pagination element
    $("#weekpage").pagination(weeknum_enteries, {
        num_edge_entries: 2,
        prev_text: '<?php echo lang('g_previousPage') ?>',       //上一页按钮里text 
        next_text: '<?php echo lang('g_nextPage') ?>',       //下一页按钮里text            
        num_display_entries: 4,
        callback: weekpageselectCallback,
        items_per_page:1
    });
 }
 function initMonthPagination(){
	 var monthnum_entries = Math.ceil(monthMaxlength/pagesize);
	  $("#monthpage").pagination(monthnum_entries, {
	        num_edge_entries: 2,
	        prev_text: '<?php echo lang('g_previousPage') ?>',       //上一页按钮里text 
	        next_text: '<?php echo lang('g_nextPage') ?>',       //下一页按钮里text            
	        num_display_entries: 4,
	        callback: monthpageselectCallback,
	        items_per_page:1
	    });
}
function weekpageselectCallback(page_index, jq){
	weekpageindex=page_index;
	CompareWeekData();
	return false;
}
function monthpageselectCallback(page_index, jq){
	monthpageindex=parseInt(page_index);
	CompareMonthData();
	return false;	
}
</script>