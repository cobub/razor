<?php 
if (!isset($from))
{
    $to = date('Y-m-d',time());
    $from = date('Y-m-d',strtotime("-7 day"));
}?>
<script type="text/javascript">
var time=<?php echo isset($timetype)?'"'.$timetype.'"':'"'."7day".'"'?>;
var fromTime=<?php echo isset($from)?'"'.$from.'"':'""'?>;
var toTime=<?php echo isset($to)?'"'.$to.'"':'""'?>;
</script>
<section id="main" class="column">
<h4 class="alert_success" id='msg'><?php echo lang('regionview_alertinfo') ?>
	<div class="submit_link" style="margin-top:-8px">
<select onchange=selectChange(this.value)
	id='select'>
	<option selected value='7day'><?php echo lang('allview_lastweek') ?></option>
	<option value='1month'><?php echo lang('allview_lastmonth') ?></option>
	<option value='3month'><?php echo lang('allview_last3month') ?></option>
	<option value='all'><?php echo lang('allview_alltime') ?></option>
	<option value='any'><?php echo lang('allview_anytime') ?></option>
</select>
<div id='selectTime'><input type="text" id="dpFrom"> 
    <input type="text"	id="dpTo"> 
	<input type="submit" id='btn' value="<?php echo lang('allview_timebtn') ?>" class="alt_btn"	onclick="onBtn()">
</div>
</div>
</h4>
<!-- 国家分布 -->
<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo lang('regionview_countrytitle') ?></h3>
<ul class="tabs">
	<li><a href="#tab1"><?php echo lang('regionview_countabnew') ?></a></li>
	<li><a href="#tab2"><?php echo lang('regionview_countabact') ?></a></li>
</ul>
</header>
<!-- 新增用户 -->
<div id="tab1" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th width="20%"><?php echo lang('regionview_countrythead') ?></th>
			<th width="70%"><?php echo lang('regionview_newuserpercent') ?></th>
			<th width="10%"></th>

		</tr>
	</thead>
	<tbody>
	 <?php 		 
		  if(isset($newcountry)):		       
			 	foreach($newcountry->result() as $row)
			 	{  
			 ?>
		<tr>
			<td><?php echo $row->country; ?></td>
			<td>
			<div
				style="background-color: rgb(116, 119, 213); height: 15px; width: <?php  echo BLOCK_MAX_LENGTH*$row->percentage; ?>px;"></div>
			</td>
			<td><?php echo 100*$row->percentage.'%'; ?></td>
		</tr>
		<?php   } endif;?>
	</tbody>
</table>
</div>
<!-- 活跃用户 -->
<div id="tab2" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th width="20%"><?php echo lang('regionview_theadcountry') ?></th>
			<th width="70%"><?php echo lang('regionview_theadpercent') ?></th>
			<th width="10%"></th>        
		</tr>
	</thead>
	<tbody>
	 <?php 		 
		  if(isset($activecountry)):		   
			 	foreach($activecountry->result() as $rel)
			 	{ 
			 ?>
		<tr>
			<td><?php echo $rel->country; ?></td>
			<td>
			<div
				style="background-color: rgb(116, 119, 213); height: 15px; width: <?php  echo BLOCK_MAX_LENGTH*$rel->percentage; ?>px;"></div>
			</td>
			<td><?php echo 100*$rel->percentage.'%'; ?></td>
			
		</tr>
		<?php } endif;?>
	</tbody>
</table>
</div>
</article>
<!--省市分布 -->
<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo lang('regionview_provintilte') ?></h3>
<ul class="tabs2">
	<li><a href="#tab3"><?php echo lang('regionview_pronewuser') ?></a></li>
	<li><a href="#tab4"><?php echo lang('regionview_proactuser') ?></a></li>
</ul>
</header>
<!-- 新增用户 -->
<div id="tab3" class="tab_content1">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th width="20%"><?php echo lang('regionview_provincethead') ?></th>
			<th width="70%"><?php echo lang('regionview_propercentthead') ?></th>
			<th width="10%"></th>

		</tr>
	</thead>
	<tbody>
	 <?php 		 
		  if(isset($newpro)):		      
			 	foreach($newpro->result() as $rew)
			 	{  			 ?>
		<tr>
			<td><?php echo $rew->region?></td>
			<td>
			<div
				style="background-color: rgb(116, 119, 213); height: 15px; width: <?php  echo BLOCK_MAX_LENGTH*$rew->percentage; ?>px;"></div>
			</td>
			<td><?php  echo 100*$rew->percentage.'%'; ?></td>
		</tr>
		<?php   } endif;?>
	</tbody>
</table>
</div>
<!-- 活跃用户 -->
<div id="tab4" class="tab_content1">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th width="20%"><?php echo lang('regionview_protheadprovince') ?></th>
			<th width="70%"><?php echo lang('regionview_protheadper') ?></th>
			<th width="10%"></th>

		</tr>
	</thead>
	<tbody>
		 <?php 		 
		  if(isset($activepro)):		      
			 	foreach($activepro->result() as $ret)
			 	{  			 ?>
		<tr>
			<td><?php echo $ret->region?></td>
			<td>
			<div
				style="background-color: rgb(116, 119, 213); height: 15px; width: <?php  echo BLOCK_MAX_LENGTH*$ret->percentage; ?>px;"></div>
			</td>
			<td><?php  echo 100*$ret->percentage.'%'; ?></td>
		</tr>
		<?php   } endif;?>
	</tbody>
</table>
</div>
</article>
<div class="clear"></div>
<div class="spacer"></div>

<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo lang('regionview_coundetailtitle') ?></h3>
<!--<div class="submit_link"><a href="<?php echo site_url().'/report/region/exportcountry/'.$from.'/'.$to?>">-->
<!--<img  src="<?php echo base_url(); ?>assets/images/export.png"/></a></div>-->
<span class="relative r">                	
   <a href="<?php echo site_url().'/report/region/exportcountry/'.$from.'/'.$to?>" class="bottun4 hover" ><font><?php echo lang('regionview_exportbtn') ?></font></a>
</span>	
</header>
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th width="30%"><?php echo lang('regionview_coundetailthcontry') ?></th>
			<th width="70%"><?php echo lang('regionview_coundetailthper') ?></th>
			
		</tr>
	</thead>
	<tbody id="countrydetail">	
		<?php 		 
		  if(isset($activepagecoun)):		  
			 	foreach($activepagecoun->result() as $rel)
			 	{ 
			 ?>
		<tr>
			<td><?php echo $rel->country; ?></td>			
			<td><?php echo 100*$rel->percentage.'%'; ?></td>
			
		</tr>
		<?php } endif;?>
	</tbody>
</table>
<footer>
<div id="pagination"  class="submit_link"></div>			
</footer>
</article>
<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo lang('regionview_prodetailtitle') ?></h3>
<!--<div class="submit_link"> <a  href="<?php echo site_url().'/report/region/exportpro/'.$from.'/'.$to; ?>">-->
<!--<img  src="<?php echo base_url(); ?>assets/images/export.png"/></a></div>-->
<span class="relative r">                	
   <a href="<?php echo site_url().'/report/region/exportpro/'.$from.'/'.$to; ?>" class="bottun4 hover" ><font><?php echo lang('regionview_proexportbtn') ?></font></a>
</span>	
</header>
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th width="30%"><?php echo lang('regionview_prodetailpro') ?></th>
			<th width="70%"><?php echo lang('regionview_prodetailper') ?></th>			
		</tr>
	</thead>
	<tbody id="prodetail">
		 <?php 		 
		  if(isset($activepagepro)):		    
			 	foreach($activepagepro->result() as $ret)
			 	{ 
			 ?>
		<tr>
			<td><?php echo $ret->region?></td>			
			<td><?php  echo 100*$ret->percentage.'%'; ?></td>
		</tr>
		<?php   } endif;?>
	</tbody>
</table>
<footer>
<div id="paginationpro"  class="submit_link"></div>			
</footer>
</article>
<div class="clear"></div>
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
<!--设置活动的tab -->
<script>
$(".tab_content1").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content1:first").show(); //Show first tab content
</script>
<!--设置时间下拉框 -->
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


$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(".tab_content1").hide(); //Hide all tab content
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
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

</script>

<!--获得数据 -->
<script type="text/javascript">
function getdata()
	{
	    if(time=='any')
		{		
	    	window.location = "<?php echo site_url().'/report/region/regioninfo/'?>"+time+"/"+fromTime+"/"+toTime;
		}
		else
		{
			window.location = "<?php echo site_url().'/report/region/regioninfo/'?>"+time;
		}	
}

</script>
<!--设置活跃国家明细页数 -->
<script type="text/javascript">
function pageselectCallback(page_index, jq){
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";   
   var myurl="<?php echo site_url()?>/report/region/activecountrypage/<?php echo isset($from)?$from:date ( "Y-m-d", strtotime ( "-7 day" ) );?>/<?php echo isset($to)?$to:date ("Y-m-d");?>/"+page_index;
	jQuery.ajax({
		type : "post",
		url : myurl,
		success : function(msg) {
			document.getElementById('countrydetail').innerHTML = msg;				
		},
		error : function(XmlHttpRequest, textStatus, errorThrown) {
			alert("<?php echo lang('regionview_counjsremind') ?>");
			
		}
	});
  return false;
	}

/** 
 * Callback function for the AJAX content loader.
 */
function initPagination() {
    var num_entries = <?php if(isset($counum)) echo $counum; ?>/<?php echo PAGE_NUMS;?>;
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
        
// Load HTML snippet with AJAX and insert it into the Hiddenresult element
// When the HTML has loaded, call initPagination to paginate the elements        
$(document).ready(function(){  
	initPagination();
	initproPagination();
});    
</script>
<!--设置活跃省市明细页数 -->
<script type="text/javascript">
function pageproCallback(page_index, jq){
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";	 
   var myurl="<?php echo site_url()?>/report/region/activepropage/<?php echo isset($from)?$from:date ( "Y-m-d", strtotime ( "-7 day" ) );?>/<?php echo isset($to)?$to:date ("Y-m-d");?>/"+page_index;
	jQuery.ajax({
		type : "post",
		url : myurl,
		success : function(msg) {
			document.getElementById('prodetail').innerHTML = msg;				
		},
		error : function(XmlHttpRequest, textStatus, errorThrown) {
			document.getElementById('msg').innerHTML = "<?php echo lang('regionview_jserror') ?>";
		}
	});
  return false;
	}

/** 
 * Callback function for the AJAX content loader.
 */
function initproPagination() {
	
    var num_entries = <?php if(isset($pronum)) echo $pronum; ?>/<?php echo PAGE_NUMS;?>;
    // Create paginationpro element
    $("#paginationpro").pagination(num_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo lang('allview_jsbeforepage') ?>',       //上一页按钮里text 
        next_text: '<?php echo lang('allview_jsnextpage') ?>',       //下一页按钮里text            
        num_display_entries: 8,
        callback: pageproCallback,
        items_per_page:1
    });
 }
</script>
