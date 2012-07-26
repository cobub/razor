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
<h4 class="alert_success" id='msg'><?php echo  lang('networkview_alertinfo')?>
<div class="submit_link" style="margin-top:-8px">
<select onchange=selectChange(this.value)
	id='select'>
	<option value='7day'><?php echo  lang('allview_lastweek')?></option>
	<option value='1month'><?php echo  lang('allview_lastmonth')?></option>
	<option value='3month'><?php echo  lang('allview_last3month')?></option>
	<option value='all'><?php echo  lang('allview_alltime')?></option>
	<option value='any'><?php echo  lang('allview_anytime')?></option>
</select>
<div id='selectTime'>
    <input type="text" id="dpFrom"> 
    <input type="text"	id="dpTo"> 
	<input type="submit" id='btn' value="<?php echo  lang('allview_timebtn')?>" class="alt_btn"	onclick="onBtn()">
</div>
</div>
</h4>


<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo  lang('networkview_headertilte')?></h3>

<ul class="tabs">
   			<li><a href="#tab1"><?php echo  lang('operatorview_activetab')?></a></li>
    		<li><a href="#tab2"><?php echo  lang('operatorview_newtab')?></a></li>
    		
</ul>
</header>

<div id="tab1" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo  lang('networkview_networkthead')?></th>
			<th><?php echo  lang('networkview_percentthead')?></th>
			<th></th>
			
		</tr>
	</thead>
	<tbody>
	    <?php 
	        foreach ($activeUsernetworktype->result() as $row)
	        {
	    ?>
		<tr>			
			<td><?php echo $row->networkname?></td>			
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
			<th><?php echo  lang('networkview_networkthead')?></th>
			<th><?php echo  lang('networkview_percentthead')?></th>
			<th></th>
			
		</tr>
	</thead>
	<tbody>
	    <?php 
	        foreach ($newUsernetworktype->result() as $row)
	        {
	    ?>
		<tr>			
			<td><?php echo $row->networkname?></td>			
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
<h3 class="tabs_involved"><?php echo  lang('networkview_networkdetailinfo')?></h3>
<!--<div class="submit_link"><a href="<?php echo site_url()?>/report/operator/export/<?php echo $from.'/'.$to?>">-->
<!--<img  src="<?php echo base_url(); ?>assets/images/export.png"/></a></div>-->
<span class="relative r">                	
   <a href="<?php echo site_url()?>/report/operator/export/<?php echo $from.'/'.$to?>>" class="bottun4 hover" ><font><?php echo  lang('operatorview_exportbtn')?></font></a>
</span>	
</header>

<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo  lang('networkview_networkthead')?></th>
			<th><?php echo  lang('networkview_percentthead')?></th>
			
			
		</tr>
	</thead>
	<tbody>
		<?php 
	        foreach ($totalnetworktype->result() as $row)
	        {
	    ?>
		<tr>			
			<td><?php echo $row->networkname?></td>			
		    <td><?php echo 100*$row->percentage."%"?></td>			
		</tr>
		<?php 
	        }?>
	</tbody>
	
</table>
<footer>
		<div id="pagination"  class="submit_link">
		</div>
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
	    window.location = "<?php echo site_url().'/report/network/getNetworkData/'?>"+time+"/"+fromTime+"/"+toTime;
	else
		window.location = "<?php echo site_url().'/report/network/getNetworkData/'?>"+time;
}

</script>




