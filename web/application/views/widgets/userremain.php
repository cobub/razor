<section class="column" style="height:900px;" id="highchart" <?php if(!isset($delete)) {?>
style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;"<?php } ?>>
		<?php if(isset($message)):?>
		<h4 class="alert_success"><?php echo $message; ?></h4>		
		<?php endif; ?>			
		<article class="module width_full">
		<header>
		<div style="float:left;margin-left:2%;margin-top: 7px;">
	<?php   if(isset($add))
  {?>
  <a href="#" onclick="addreport()">
	<img src="<?php echo base_url(); ?>assets/images/addreport.png" title="<?php echo lang('s_suspend_title')?>" style="border:0"/></a>
<?php }if(isset($delete)){ ?>
 <a href="#" onclick="deletereport()">
	<img src="<?php echo base_url(); ?>assets/images/delreport.png" title="<?php echo lang('s_suspend_deltitle')?>" style="border:0"/></a>
	<?php } ?>
	  </div>		
	<h3 class="h3_fontstyle">        
		<?php echo  lang('v_rpt_ur_retention')?></h3>
        <div class='submit_link'>
		<select id="selectchannel" name="selectchannel" onchange='changeversionorchannel(selectversion.value, selectchannel.value)'>
			
			<option value="all" selected><?php echo lang('v_rpt_el_allChannel'); ?></opton>			
			<?php if(isset($product_channels)) 
			{
				foreach ($product_channels as $row)
				{?>
				<option value="<?php echo $row -> channel_name; ?>"><?php echo $row -> channel_name; ?></option>	
			<?php }
                    }
                ?>
			
		</select>
		<select id="selectversion" name="selectversion" onchange='changeversionorchannel(selectversion.value, selectchannel.value)'>
			
			<option value="all" selected><?php echo lang('v_rpt_el_allVersion'); ?></opton>			
			<?php if(isset($productversion)) 
			{
				foreach ($productversion->result() as $row)
				{?>
				<option value="<?php echo $row -> version_name; ?>"><?php echo $row -> version_name; ?></option>	
			<?php }
                    }
                ?>
			
		</select>
        </div>

			<div class="submit_link">
			<ul class="tabs2" style="position:relative;top:-5px;">
				<li><a id="day" href="#tab0"><?php echo  lang('t_day')?></a></li>		
				<li><a id="week" href="#tab1"><?php echo  lang('t_week')?></a></li>
				<li><a id="month" href="#tab2"><?php echo  lang('t_month')?></a></li>
			</ul>			
			</div>	
		</header>
		<div id="contents">
			<div id="tab0" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th><?php echo  lang('v_rpt_ur_firstUseDay')?></th> 
    				<th><?php echo  lang('t_newUsers')?></th>
    				<th><?php echo  lang('v_rpt_ur_one_days')?></th> 
    				<th><?php echo  lang('v_rpt_ur_two_days')?></th> 
    				<th><?php echo  lang('v_rpt_ur_three_days')?></th> 
    				<th><?php echo  lang('v_rpt_ur_four_days')?></th> 
    				<th><?php echo  lang('v_rpt_ur_five_days')?></th> 
    				<th><?php echo  lang('v_rpt_ur_six_days')?></th> 
    				<th><?php echo  lang('v_rpt_ur_seven_days')?></th> 
    				<th><?php echo  lang('v_rpt_ur_eight_days')?></th> 
    				
				</tr> 
			</thead> 
			<tbody id='daydata'> 			
			</tbody> 
			</table>
			<footer>
			<div id="daypage" class="submit_link"></div>
			</footer>
			</div><!-- end of #tab1 -->			

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
    				<th><?php echo  lang('t_newUsers')?></th> 
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
    var dayuserdata;
    var weekuserdata;
    var monthuserdata;

    var color = ["", "#EDFFFF", "#FFFEED", "#EDEDED"];

    //When page loads...
    $(".tab_content").hide();
    //Hide all content
    $("ul.tabs2 li:first").addClass("active").show();
    //Activate first tab
    $(".tab_content:first").show();
    //Show first tab content

    //On Click Event
    $("ul.tabs2 li").click(function() {
        $(".tab_content").hide();
        $("ul.tabs2 li").removeClass("active");
        //Remove any "active" class
        $(this).addClass("active");
        //Add "active" class to selected tab
        var activeTab = $(this).find("a").attr("href");
        //Find the href attribute value to identify the active tab + content
        $(activeTab).show();
        //Fade in the active ID content
        return false;
    });
</script>

<script type="text/javascript">

    function pageselectdayCallback(page_index, jq){			
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";   
	var index = page_index*9;
	var pagenum = 9;	
    var daytr = "";
    if (index+pagenum >= dayuserdata.length)
    {
        pagenum = dayuserdata.length % pagenum;
    }

	for(i=pagenum-1;i>=0 && (index+i)<dayuserdata.length ;i--)
	{ 
		var start = dayuserdata[i+index].startdate;
		var end   = dayuserdata[i+index].enddate;
		var showtime = start;
		daytr = daytr+"<tr><td>";
		daytr = daytr + showtime;
		daytr = daytr + "</td><td>";
		daytr = daytr + dayuserdata[i+index].usercount;			
		daytr = daytr + "</td><td>";
		daytr = daytr + '<strong>' + dayuserdata[i+index].day1 + '</strong> (' + ((dayuserdata[i+index].day1/dayuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		daytr = daytr + "</td><td>";
		daytr = daytr + '<strong>' + dayuserdata[i+index].day2 + '</strong> (' + ((dayuserdata[i+index].day2/dayuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		daytr = daytr + "</td><td>";
		daytr = daytr + '<strong>' + dayuserdata[i+index].day3 + '</strong> (' + ((dayuserdata[i+index].day3/dayuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		daytr = daytr + "</td><td>";
		daytr = daytr + '<strong>' + dayuserdata[i+index].day4 + '</strong> (' + ((dayuserdata[i+index].day4/dayuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		daytr = daytr + "</td><td>";
		daytr = daytr + '<strong>' + dayuserdata[i+index].day5 + '</strong> (' + ((dayuserdata[i+index].day5/dayuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		daytr = daytr + "</td><td>";
		daytr = daytr + '<strong>' + dayuserdata[i+index].day6 + '</strong> (' + ((dayuserdata[i+index].day6/dayuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		daytr = daytr + "</td><td>";
		daytr = daytr + '<strong>' + dayuserdata[i+index].day7 + '</strong> (' + ((dayuserdata[i+index].day7/dayuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		daytr = daytr + "</td><td>";
		daytr = daytr + '<strong>' + dayuserdata[i+index].day8 + '</strong> (' + ((dayuserdata[i+index].day8/dayuserdata[i+index].usercount)*100).toFixed(1) +  '%)';					
		daytr = daytr + "</td></tr>";
	}
	$('#daydata').html(daytr);	
   //document.getElementById('content').innerHTML = msg;				
   return false;
 }

function pageselectweekCallback(page_index, jq){			
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";   
	var index = page_index*9;
	var pagenum = 9;	
	var weektr = "";	
    if (index+pagenum >= weekuserdata.length)
    {
        pagenum = weekuserdata.length % pagenum;
    }
	for(i=pagenum-1;i>=0 && (index+i)<weekuserdata.length ;i--)
	{ 
		var start = weekuserdata[i+index].startdate;
		var end   = weekuserdata[i+index].enddate;
		var showtime = start+"~"+end;
		weektr = weektr+"<tr><td>";
		weektr = weektr + showtime;
		weektr = weektr + "</td><td>";
		weektr = weektr + weekuserdata[i+index].usercount;
		weektr = weektr + "</td><td>";
		weektr = weektr + '<strong>' + weekuserdata[i+index].week1 + '</strong> (' + ((weekuserdata[i+index].week1/weekuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		weektr = weektr + "</td><td>";
		weektr = weektr + '<strong>' + weekuserdata[i+index].week2 + '</strong> (' + ((weekuserdata[i+index].week2/weekuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		weektr = weektr + "</td><td>";
		weektr = weektr + '<strong>' + weekuserdata[i+index].week3 + '</strong> (' + ((weekuserdata[i+index].week3/weekuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		weektr = weektr + "</td><td>";
		weektr = weektr + '<strong>' + weekuserdata[i+index].week4 + '</strong> (' + ((weekuserdata[i+index].week4/weekuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		weektr = weektr + "</td><td>";
		weektr = weektr + '<strong>' + weekuserdata[i+index].week5 + '</strong> (' + ((weekuserdata[i+index].week5/weekuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		weektr = weektr + "</td><td>";
		weektr = weektr + '<strong>' + weekuserdata[i+index].week6 + '</strong> (' + ((weekuserdata[i+index].week6/weekuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		weektr = weektr + "</td><td>";
		weektr = weektr + '<strong>' + weekuserdata[i+index].week7 + '</strong> (' + ((weekuserdata[i+index].week7/weekuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		weektr = weektr + "</td><td>";
		weektr = weektr + '<strong>' + weekuserdata[i+index].week8 + '</strong> (' + ((weekuserdata[i+index].week8/weekuserdata[i+index].usercount)*100).toFixed(1) +  '%)';
		weektr = weektr + "</td></tr>";
	}
	$('#weekdata').html(weektr);	
   //document.getElementById('content').innerHTML = msg;				
   return false;
 }



function pageselectmonthCallback(page_index, jq){			
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";   
	var index = page_index*9;
	var pagenum = 9;	
	var monthtr = "";
    if (index+pagenum >= monthuserdata.length)
    {
        pagenum = monthuserdata.length % pagenum;
    }
	for(j=pagenum-1;j>=0 && (index+j)<monthuserdata.length ;j--)
	{
		var start = monthuserdata[j+index].startdate;				
		var end   = monthuserdata[j+index].enddate;
		var showtime = start+"~"+end;
		monthtr = monthtr+"<tr><td>";
		monthtr = monthtr + showtime;
		monthtr = monthtr + "</td><td>";
		monthtr = monthtr + monthuserdata[j+index].usercount;
		monthtr = monthtr + "</td><td>";
		monthtr = monthtr + '<strong>' + monthuserdata[j+index].month1 + '</strong> (' + ((monthuserdata[j+index].month1/monthuserdata[j+index].usercount)*100).toFixed(1) +  '%)';
		monthtr = monthtr + "</td><td>";
		monthtr = monthtr + '<strong>' + monthuserdata[j+index].month2 + '</strong> (' + ((monthuserdata[j+index].month2/monthuserdata[j+index].usercount)*100).toFixed(1) +  '%)';
		monthtr = monthtr + "</td><td>";
		monthtr = monthtr + '<strong>' + monthuserdata[j+index].month3 + '</strong> (' + ((monthuserdata[j+index].month3/monthuserdata[j+index].usercount)*100).toFixed(1) +  '%)';
		monthtr = monthtr + "</td><td>";
		monthtr = monthtr + '<strong>' + monthuserdata[j+index].month4 + '</strong> (' + ((monthuserdata[j+index].month4/monthuserdata[j+index].usercount)*100).toFixed(1) +  '%)';
		monthtr = monthtr + "</td><td>";
		monthtr = monthtr + '<strong>' + monthuserdata[j+index].month5 + '</strong> (' + ((monthuserdata[j+index].month5/monthuserdata[j+index].usercount)*100).toFixed(1) +  '%)';
		monthtr = monthtr + "</td><td>";
		monthtr = monthtr + '<strong>' + monthuserdata[j+index].month6 + '</strong> (' + ((monthuserdata[j+index].month6/monthuserdata[j+index].usercount)*100).toFixed(1) +  '%)';
		monthtr = monthtr + "</td><td>";
		monthtr = monthtr + '<strong>' + monthuserdata[j+index].month7 + '</strong> (' + ((monthuserdata[j+index].month7/monthuserdata[j+index].usercount)*100).toFixed(1) +  '%)';
		monthtr = monthtr + "</td><td>";
		monthtr = monthtr + '<strong>' + monthuserdata[j+index].month8 + '</strong> (' + ((monthuserdata[j+index].month8/monthuserdata[j+index].usercount)*100).toFixed(1) +  '%)';
		monthtr = monthtr + "</td></tr>";
	}
	$('#monthdata').html(monthtr);
   //document.getElementById('content').innerHTML = msg;				
   return false;
 } 

/** 
* Callback function for the AJAX content loader.
 */
function dayinitPagination() {
  var num_entries = (dayuserdata.length)/9;
  // Create pagination element
  $("#daypage").pagination(num_entries, {
     num_edge_entries: 2,
     prev_text: '<?php echo  lang('g_previousPage')?>',
        next_text: '<?php echo  lang('g_nextPage')?>',
    num_display_entries: 4,
    callback: pageselectdayCallback,
    items_per_page:1
    });
    }

    /**
    * Callback function for the AJAX content loader.
    */
    function weekinitPagination() {
    var num_entries = (weekuserdata.length)/9;
    // Create pagination element
    $("#weekpage").pagination(num_entries, {
    num_edge_entries: 2,
    prev_text: '<?php echo  lang('g_previousPage')?>',
    next_text: '<?php echo  lang('g_nextPage')?>',
    num_display_entries: 4,
    callback: pageselectweekCallback,
    items_per_page:1
    });
    }

    function monthinitPagination() {
    var num_entries = (monthuserdata.length)/9;
    // Create pagination element
    $("#monthpage").pagination(num_entries, {
    num_edge_entries: 2,
    prev_text: '<?php echo  lang('g_previousPage')?>',
    next_text: '<?php echo  lang('g_nextPage')?>',
    num_display_entries: 4,
    callback: pageselectmonthCallback,
    items_per_page:1
    });
    }
</script>

<script type="text/javascript">
                var type=document.getElementById('selectversion').value;
$(document).ready(function() {	
	var userurl  = "<?php echo site_url(); ?>/report/userremain/getUserRemainweekMonthData/"+type;
	renderUserData(userurl);
});
var dayobj;
var weekobj;
var monthobj;
var productNames=[];
var d_timepart=[];
var w_timepart=[];
var m_timepart=[];
function renderUserData(myurl)
{	
	 var chart_canvas = $('#contents');
	 var loading_img = $("<img src='<?php echo base_url(); ?>/assets/images/loader.gif'/>");

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
        dayobj=data.userremainday;
        weekobj=data.userremainweek;
        monthobj=data.userremainmonth;
        var daytr="";
        var weektr="";
        var monthtr="";
        if(weekobj.length>1&&typeof(weekobj[0].name)!='undefined'){
        if(weekobj.length>1||monthobj.length>1){
        if(typeof weekobj[0].data!='undefined'){
        $.each(dayobj,function(index,item){
        productNames[index]=item.name;
        $.each(item.data,function(i,o){
        d_timepart[i]={};
        d_timepart[i].startdate=o.startdate;
        d_timepart[i].enddate=o.enddate;
        });
        });
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

        if(dayobj.length>1&&typeof(dayobj[0].data)!='undefined'){
        $('#tab2 th:eq(0)').after('<th><?php echo lang('v_app')?></th>');
    dayMaxlength=d_timepart.length;
    CompareDayData();
    //monthtr=CompareMonthData(m_timepart,productNames,monthtr,monthobj);
    initDayPagination();
    chart_canvas.unblock();
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
    dayuserdata=eval(dayobj);
    weekuserdata=eval(weekobj);
    monthuserdata=eval(monthobj);
    dayinitPagination();
    weekinitPagination();
    monthinitPagination();
    pageselectdayCallback(0,null);
    pageselectweekCallback(0,null);
    pageselectmonthCallback(0,null);
    chart_canvas.unblock();
    //document.getElementById('weekdata').innerHTML=weektr;
    //document.getElementById('monthdata').innerHTML = monthtr;

    }
    return false;
    });
    }
    function changeversionorchannel(version, channel)
    {
    var userurl  = "<?php echo site_url(); ?>/report/userremain/getUserRemainweekMonthData/" + version + "/" + channel;
	 renderUserData(userurl);
 }


 var dayMaxlength=0;
 var dayStart=0;
 var daypageindex=0;
 var pagesize=7;

 function CompareDayData(){
	 var daytr="";
	 dayStart=(daypageindex)*pagesize;
	 $.each(d_timepart,function(index,item){
			var sameCount=0;
			if(index>=dayStart&&index<dayStart+pagesize){
				daytr+='<tr><td rowspan='+(productNames.length)+'>'+(item.startdate+'~'+item.enddate)+'</td>';
				$.each(dayobj,function(i,o){										
					daytr+='<td style="background:'+color[i]+'">'+o.name+'</td>';
					if(o.data.length==0){
						daytr+="<td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>";
					}else{
					$.each(o.data,function(idx,day){
						if(item.startdate==day.startdate&&item.enddate==day.enddate){
							sameCount++;
							daytr = daytr + "<td style='background:"+color[i]+"'>";
							daytr = daytr + day.usercount;			
							daytr = daytr + "</td><td style='background:"+color[i]+"'>";
							daytr = daytr + day.day1;
							daytr = daytr + "</td><td style='background:"+color[i]+"'>";
							daytr = daytr + day.day2;
							daytr = daytr + "</td><td style='background:"+color[i]+"'>";
							daytr = daytr + day.day3;
							daytr = daytr + "</td><td style='background:"+color[i]+"'>";
							daytr = daytr + day.day4;
							daytr = daytr + "</td><td style='background:"+color[i]+"'>";
							daytr = daytr + day.day5;
							daytr = daytr + "</td><td style='background:"+color[i]+"'>";
							daytr = daytr + day.day6;
							daytr = daytr + "</td><td style='background:"+color[i]+"'>";
							daytr = daytr + day.day7;
							daytr = daytr + "</td><td style='background:"+color[i]+"'>";
							daytr = daytr + day.day8;					
							daytr = daytr + "</td>";
							}
						});
					if(sameCount==0){
						daytr+="<td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td><td>0</td>";
						}
					}
					daytr+='</tr>';
				});
				}
			
		});
	 	$('#daydata').html(daytr);
	 }


 var weekMaxlength=0;
 var weekStart=0;
 var weekpageindex=0;
 var pagesize=9;
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
	if(confirm( "<?php echo  lang('w_isaddreport')?>
        "))
        {
        var reportname="userremain";
        var reportcontroller="userremain";
        var data={
        reportname:reportname,
        controller:reportcontroller,
        height    :580,
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
    alert("<?php echo lang('w_overmaxnum'); ?>");
    }
    else
    {
    alert("<?php echo lang('w_addreportsuccess') ?>");
    }

    },
    error : function(XmlHttpRequest, textStatus, errorThrown) {
    alert(
<?php echo lang('t_error'); ?>);
							}
					});
		
	}
}

function deletereport()
{  
	if(confirm( "<?php echo  lang('v_deletreport')?>
        "))
        {
        window.parent.deletereport("userremain");
        }
        return false;

        }
        /**
        * Callback function for the AJAX content loader.
        */
        function initDayPagination() {
        var daynum_enteries = Math.ceil(dayMaxlength/pagesize);
        // Create pagination element
        $("#daypage").pagination(daynum_enteries, {
        num_edge_entries: 2,
        prev_text: '<?php echo lang('g_previousPage') ?>',
        next_text: '<?php echo lang('g_nextPage') ?>',
        num_display_entries: 4,
        callback: daypageselectCallback,
        items_per_page:1
        });
    }

    function initWeekPagination() {
    var weeknum_enteries = Math.ceil(weekMaxlength/pagesize);
    // Create pagination element
    $("#weekpage").pagination(weeknum_enteries, {
        num_edge_entries: 2,
        prev_text: '<?php echo lang('g_previousPage') ?>',       //prev page text
        next_text: '<?php echo lang('g_nextPage') ?>',       //next page text
        num_display_entries: 4,
        callback: weekpageselectCallback,
        items_per_page:1
    });
    }
    function initMonthPagination(){
    var monthnum_entries = Math.ceil(monthMaxlength/pagesize);
    $("#monthpage").pagination(monthnum_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo lang('g_previousPage') ?>',       //prev page text
        next_text: '<?php echo lang('g_nextPage') ?>',       //next page text
        num_display_entries: 4,
        callback: monthpageselectCallback,
        items_per_page:1
    });
    }
    function daypageselectCallback(page_index, jq) {
        weekpageindex = page_index;
        CompareDayData();
        return false;
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
