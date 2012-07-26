<?php 
if (!isset($from))
{
    $to = date('Y-m-d',time());
    $from = date('Y-m-d',strtotime("-7 day"));
}
?>
<script type="text/javascript">
var time=<?php echo isset($timetype)?'"'.$timetype.'"':'"'."7day".'"'?>;
var fromTime=<?php echo isset($from)?'"'.$from.'"':'""'?>;
var toTime=<?php echo isset($to)?'"'.$to.'"':'""'?>;
</script>


<section id="main" class="column">
<h4 class="alert_success" id='msg'><?php echo  lang('resolutionview_alertinfo')?>
<div class="submit_link" style="margin-top:-8px">
<select onchange=selectChange(this.value)
	id='select'>
	<option value='7day' ><?php echo  lang('allview_lastweek')?></option>
	<option value='1month'><?php echo  lang('allview_lastmonth')?></option>
	<option value='3month'><?php echo  lang('allview_last3month')?></option>
	<option value='all'><?php echo  lang('allview_alltime')?></option>
	<option value='any'><?php echo  lang('allview_anytime')?></option>
</select>
<div id='selectTime'><input type="text" id="dpFrom"> 
    <input type="text"	id="dpTo"> 
	<input type="submit" id='btn' value="<?php echo  lang('allview_timebtn')?>" class="alt_btn"	onclick="onBtn()">
</div>
</div>
</h4>


<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo  lang('resolutionview_headertilte')?></h3>
<ul class="tabs">
   			<li><a href="#tab1"><?php echo  lang('resolutionview_actuser')?></a></li>
    		<li><a href="#tab2"><?php echo  lang('resolutionview_newuser')?></a></li>
    		
</ul>

</header>

<div id="tab1" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo  lang('resolutionview_resolutionthead')?></th>
			<th><?php echo  lang('resolutionview_userperthead')?></th>
			<th></th>
			
		</tr>
	</thead>
	<tbody>
	    <?php 
	        foreach ($activeuser->result() as $row)
	        {
	    ?>
		<tr>			
			<td><?php echo $row->deviceresolution_name 	?></td>			
			<td><div style="background-color: <?php echo BLOCK_COLOR?>; height: <?php echo BLOCK_HEIGHT?>; width: <?php echo BLOCK_MAX_LENGTH*$row->percentage?>px;"></div></td>
		    <td><?php echo 100*$row->percentage."%"?></td>			
		</tr>
		<?php 
	        }?>
	</tbody>
</table>
</div>

<div id="tab2" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo  lang('resolutionview_theadresolution')?></th>
			<th><?php echo  lang('resolutionview_theaduserper')?></th>
			<th></th>
			
		</tr>
	</thead>
	<tbody>
		<?php 
	        foreach ($newuser->result() as $row)
	        {
	    ?>
		<tr>			
			<td><?php echo $row->deviceresolution_name 	?></td>			
			<td><div style="background-color: <?php echo BLOCK_COLOR?>; height: <?php echo BLOCK_HEIGHT?>; width: <?php echo BLOCK_MAX_LENGTH*$row->percentage?>px;"></div></td>
		    <td><?php echo 100*$row->percentage."%"?></td>			
		</tr>
		<?php 
	        }?>
	</tbody>
</table>
</div>

</article>

<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo  lang('resolutionview_detailheader')?>  </h3>
<!--<div class="submit_link"><a href="<?php echo site_url()?>/report/resolution/export/<?php echo $from.'/'.$to?>">-->
<!--<img  src="<?php echo base_url(); ?>assets/images/export.png"/></a></div>-->
<span class="relative r">                	
   <a href="<?php echo site_url()?>/report/resolution/export/<?php echo $from.'/'.$to?>>" class="bottun4 hover" ><font><?php echo  lang('resolutionview_exportbtn')?></font></a>
</span>	
</header>

<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo  lang('resolutionview_detailresolution')?></th>
			<th><?php echo  lang('resolutionview_detailuserper')?></th>			
		</tr>
	</thead>
	<tbody id="resolutiondetail">
		<?php 
	        foreach ($operator->result() as $row)
	        {
	    ?>
		<tr>			
			<td><?php echo $row->deviceresolution_name?></td>			
		    <td><?php echo 100*$row->percentage."%"?></td>			
		</tr>
		<?php 
	        }?>
	</tbody>
</table>
<footer>
<div id="pagination" class="submit_link"></div>
</footer>
</article>
</section>
<script type="text/javascript">
//这里必须最先加载
    document.getElementById('select').value= time;
    if(time=='any')
    {
    	document.getElementById('dpFrom').value = fromTime;
    	document.getElementById('dpTo').value = toTime;

    }
</script>
<script>
function pageselectCallback(page_index, jq){
	page_index = arguments[0] ? arguments[0] : "0";  
	jq = arguments[1] ? arguments[1] : "0";   
   var myurl="<?php echo site_url().'/report/resolution/genresolution/'?>"+fromTime+"/"+toTime+"/"+page_index;
	jQuery.ajax({
		type : "post",
		url : myurl,
		success : function(msg) {
			document.getElementById('resolutiondetail').innerHTML = msg;				
		},
		error : function(XmlHttpRequest, textStatus, errorThrown) {
			alert("<?php echo lang('regionview_counjsremind') ?>");
			
		}
	});
  return false;
	}
function initPagination() {
    var num_entries = <?php if(isset($num)) echo $num; ?>/<?php echo PAGE_NUMS;?>;
    // Create pagination element
    $("#pagination").pagination(num_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo lang('allview_jsbeforepage') ?>',       //上一页按钮里text 
        next_text: '<?php echo lang('allview_jsnextpage') ?>',       //下一页按钮里text            
        num_display_entries: 8,
        callback: pageselectCallback,
        items_per_page:1
    });
 }  
$(document).ready(function(){  
	initPagination();
	
});
</script>
<script type="text/javascript">
dispalyOrHideTimeSelect();
$(function() {
	$("#dpFrom" ).datepicker();
});
$( "#dpFrom" ).datepicker({ dateFormat: "yy-mm-dd" });
$(function() {
	$( "#dpTo" ).datepicker();
});
$( "#dpTo" ).datepicker({ dateFormat: "yy-mm-dd" });
//When page loads...
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$("ul.tabs3 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
$("ul.tabs3 li").click(function() {
	$("ul.tabs3 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
function onBtn()
{  
	time='any';
	fromTime = document.getElementById('dpFrom').value;
	toTime = document.getElementById('dpTo').value;
	getdata();
}
function dispalyOrHideTimeSelect()
{
	 var value = document.getElementById('select').value;
	 if(value=='any')
	 {
		 document.getElementById('selectTime').style.display="inline";
	 }
	 else
	 {			 
		 document.getElementById('selectTime').style.display="none";
	 } 
}

function selectChange(value)
{
    if(value=='any')
    {
        time='any';
    }	
    else
    {
        time=value;
        getdata();
    }
    dispalyOrHideTimeSelect();           
}


function getdata()
{
	// 显示 加载图标
	var chart_canvas = $("#tab1");
    var loading_img = $("<img src='<?php echo base_url();?>/assets/images/loader.gif'/>");

    chart_canvas.block({
        message: loading_img
        ,
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

	
	if(time=='any')
	    window.location = "<?php echo site_url().'/report/resolution/getResolutionData/'?>"+time+"/"+fromTime+"/"+toTime;
	else
		window.location = "<?php echo site_url().'/report/resolution/getResolutionData/'?>"+time;
}
</script>







