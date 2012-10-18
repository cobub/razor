<section id="main" class="column">
		<?php if(isset($message)):?>
		<h4 class="alert_success"><?php echo $message;?></h4>		
		<?php endif;?>			
		<article class="module width_full">
		<header>
			<h3><?php echo  lang('v_rpt_ur_retention')?></h3>
			
		<select id="selectversion" name="selectversion" onchange='changeversion(value)'  style="position:relative;top:5px">
			
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
			<ul class="tabs" style="position:relative;top:-5px;">				
				<li><a id="week" href="#tab1"  ><?php echo  lang('t_week')?></a></li>
				<li><a id="month" href="#tab2" ><?php echo  lang('t_month')?></a></li>
			</ul>			
			</div>	
		</header>
		
	
		
		<div class="tab_container" id="contents">
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
			</div><!-- end of #tab2 -->
		</div><!-- end of .tab_container -->
		<div class="clear"></div>
		<footer>
			
		</footer>
	</article>
	<div class="clear"></div>
		<div class="spacer"></div>
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
var type=document.getElementById('selectversion').value;
$(document).ready(function() {	
	var userurl  = "<?php echo site_url();?>/report/userremain/getUserRemainweekMonthData/"+type;
	renderUserData(userurl);
});

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

		var weekobj = data.userremainweek;		
		var weekuserdata=eval(weekobj);
		var monthobj = data.userremainmonth;
		var monthuserdata=eval(monthobj);
		var weekmsg = "";
		var monthmsg="";
		for(i=0;i<weekuserdata.length;i++)
		{
			var start = weekuserdata[i].startdate;
			var end   = weekuserdata[i].enddate;
			var showtime = start+"~"+end;
			weekmsg = weekmsg+"<tr><td>";
			weekmsg = weekmsg + showtime;
			weekmsg = weekmsg + "</td><td>";
			weekmsg = weekmsg + weekuserdata[i].usercount;			
			weekmsg = weekmsg + "</td><td>";
			weekmsg = weekmsg + weekuserdata[i].week1;
			weekmsg = weekmsg + "</td><td>";
			weekmsg = weekmsg + weekuserdata[i].week2;
			weekmsg = weekmsg + "</td><td>";
			weekmsg = weekmsg + weekuserdata[i].week3;
			weekmsg = weekmsg + "</td><td>";
			weekmsg = weekmsg + weekuserdata[i].week4;
			weekmsg = weekmsg + "</td><td>";
			weekmsg = weekmsg + weekuserdata[i].week5;
			weekmsg = weekmsg + "</td><td>";
			weekmsg = weekmsg + weekuserdata[i].week6;
			weekmsg = weekmsg + "</td><td>";
			weekmsg = weekmsg + weekuserdata[i].week7;
			weekmsg = weekmsg + "</td><td>";
			weekmsg = weekmsg + weekuserdata[i].week8;					
			weekmsg = weekmsg + "</td></tr>";
		}	
		for(j=0;j<monthuserdata.length;j++)	
		{
			var start = monthuserdata[j].startdate;
			var end   = monthuserdata[j].enddate;
			var showtime = start+"~"+end;
			monthmsg = monthmsg+"<tr><td>";
			monthmsg = monthmsg + showtime;
			monthmsg = monthmsg + "</td><td>";
			monthmsg = monthmsg + monthuserdata[j].usercount;			
			monthmsg = monthmsg + "</td><td>";
			monthmsg = monthmsg + monthuserdata[j].month1;
			monthmsg = monthmsg + "</td><td>";
			monthmsg = monthmsg + monthuserdata[j].month2;
			monthmsg = monthmsg + "</td><td>";
			monthmsg = monthmsg + monthuserdata[j].month3;
			monthmsg = monthmsg + "</td><td>";
			monthmsg = monthmsg + monthuserdata[j].month4;
			monthmsg = monthmsg + "</td><td>";
			monthmsg = monthmsg + monthuserdata[j].month5;
			monthmsg = monthmsg + "</td><td>";
			monthmsg = monthmsg + monthuserdata[j].month6;
			monthmsg = monthmsg + "</td><td>";
			monthmsg = monthmsg + monthuserdata[j].month7;
			monthmsg = monthmsg + "</td><td>";
			monthmsg = monthmsg + monthuserdata[j].month8;					
			monthmsg = monthmsg + "</td></tr>";
		}
		chart_canvas.unblock();	
		document.getElementById('weekdata').innerHTML = weekmsg;		
		document.getElementById('monthdata').innerHTML = monthmsg;
					
		return false;
		});  
}
 function changeversion(value)
 {   
	 type=value;	 
	 var userurl  = "<?php echo site_url();?>/report/userremain/getUserRemainweekMonthData/"+type;
	 renderUserData(userurl);
 }
 </script>
