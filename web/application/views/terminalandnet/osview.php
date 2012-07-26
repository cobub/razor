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
<h4 class="alert_success" id='msg'><?php echo  lang('osview_alertinfo')?>
<div class="submit_link" style="margin-top:-8px">
<select onchange=selectChange(this.value)
	id='select'>
	<option value='7day'><?php echo  lang('allview_lastweek')?></option>
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
<h3 class="tabs_involved"><?php echo  lang('osview_headertilte')?></h3>
<ul class="tabs2">
   	<!--  	<li><a href="#tab1"><?php echo  lang('osview_activeuser')?></a></li> 
    		<li><a href="#tab2"><?php echo  lang('osview_newuser')?></a></li> -->
    		<li><a ct="activeuser" href="javascript:changefirstchartName('activeuser')"><?php echo  lang('osview_activeuser')?></a></li>
			<li><a ct="newuser" href="javascript:changefirstchartName('newuser')"><?php echo  lang('osview_newuser')?></a></li>	
</ul>

</header>

<div id="tab1" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>             
		<tr>
			<th><?php echo  lang('osview_versionthead')?></th>
			<th><?php echo  lang('osview_percentthead')?></th>
			<th></th>
			
		</tr>
	</thead>
	<tbody   id="contentss">
	    <?php if(isset($activeuser)){
	        foreach ($activeuser->result() as $row)
	        {
	    ?>
		<tr>			
			<td><?php echo $row->deviceos_name?></td>			
			<td><div style="background-color: <?php echo BLOCK_COLOR?>; height: <?php echo BLOCK_HEIGHT?>; width: <?php echo BLOCK_MAX_LENGTH*$row->percentage?>px;"></div></td>
		    <td><?php echo 100*$row->percentage."%"?></td>			
		</tr> 
		<?php 
	        }}?>
	</tbody>
</table>
</div>

<!--  <div id="tab2" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo  lang('osview_tabthversion')?></th>
			<th><?php echo  lang('osview_tabthpercent')?></th>
			<th></th>
			
		</tr>
	</thead>
	<tbody>
		<?php 
	        if(isset($newuser)){
	        	foreach ($newuser->result() as $row)
	        {
	    ?>
		<tr>			
			<td><?php echo $row->deviceos_name?></td>			
			<td><div style="background-color: <?php echo BLOCK_COLOR?>; height: <?php echo BLOCK_HEIGHT?>; width: <?php echo BLOCK_MAX_LENGTH*$row->percentage?>px;"></div></td>
		    <td><?php echo 100*$row->percentage."%"?></td>			
		</tr>
		<?php 
	        }}?>
	</tbody>
</table>
</div>-->


</article>

<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo  lang('osview_detailos')?>  </h3>
<!--<div class="submit_link"><a href="<?php echo site_url()?>/report/os/export/<?php echo $from.'/'.$to?>">-->
<!--<img  src="<?php echo base_url(); ?>assets/images/export.png"/></a></div>-->
<span class="relative r">                	
   <a href="<?php echo site_url()?>/report/os/export/<?php echo $from.'/'.$to?>" class="bottun4 hover" ><font><?php echo  lang('osview_exportbtn')?></font></a>
</span>	
</header>


<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo  lang('osview_detailosvers')?></th>
			<th><?php echo  lang('osview_detailpercent')?></th>
		</tr>
	</thead>
	<tbody id="totalOsconten">
		<?php 
		if(isset($operator)){
	        foreach ($operator->result() as $row)
	        {
	    ?>
		<tr>			
			<td><?php echo $row->deviceos_name?></td>			
		    <td><?php echo round(100*$row->percentage,2)."%"?></td>			
		</tr>
		<?php 
	        }}?>
	</tbody>
</table>
<footer>
<div id="pagination"  class="submit_link"></div>
</footer>
</article>
</section>
<!--<script type="text/javascript" src="http://www.tenddata.com/js/index.js"></script>-->
<!--	<link rel="stylesheet" href="http://www.tenddata.com/css/css.css" type="text/css" media="all" />-->

<script type="text/javascript">
var charname='activeuser';
var TotalOSdata='';
var alldata;
var isfirst='false';
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
function changefirstchartName(changename)
{	
	charname = changename;
	curPaView(alldata);
//	getdata();
//	alert(changename);
	//var data = chardata;
//	var newUsers = [];
	//var obj = data.content[changename];
//    for(var i=0;i<obj.length;i++)
  //  {
	//	    newUsers.push(parseInt(obj[i].startusers,10));
    //}
    
       
	//getfirstchartdata(); 
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

function pageselect(page_index, jq){
    var page_num=(page_index+1)*10;
    if((TotalOSdata.length-page_num)<0){
        page_num=page_index*10+TotalOSdata.length-page_index*10;
    }
    var htmlText='';
	for(var i=page_index*10;i<page_num;i++){
		var eachOSdataItem = TotalOSdata[i];
		htmlText = htmlText+"<tr>";
		htmlText = htmlText+"<td>"+eachOSdataItem.deviceos_name+"</td>";
		htmlText = htmlText+"<td>"+(eachOSdataItem.percentage*100).toFixed(2)+"%</td>";
		htmlText = htmlText+"</tr>";
    }
	document.getElementById("totalOsconten").innerHTML=htmlText;
}

function initPagination(count) {
    var num_entries = count/<?php echo PAGE_NUMS;?>;
    // Create pagination element
    $("#pagination").pagination(num_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo lang('allview_jsbeforepage') ?>',       //上一页按钮里text 
        next_text: '<?php echo lang('allview_jsnextpage') ?>',       //下一页按钮里text            
        num_display_entries: 8,
        callback: pageselect,
        items_per_page:1
    });
 }
$(document).ready(function(){ 
	//document.getElementById("");  
	isfirst='true';
	getdata(); 
});   

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
    var myurl="";
    if(time=='any')
    	myurl = "<?php echo site_url().'/report/os/getOsData/'?>"+time+"/"+isfirst+"/"+fromTime+"/"+toTime;
	else
		myurl = "<?php echo site_url().'/report/os/getOsData/'?>"+time+"/"+isfirst;
	//if(time=='any')
	//    window.location = "<?php echo site_url().'/report/os/getOsData/'?>"+time+"/"+fromTime+"/"+toTime;
	//else
	//	window.location = "<?php echo site_url().'/report/os/getOsData/'?>"+time;
	jQuery.ajax({
		type : "post",
		url : myurl,
		success : function(msg) {
		//	alert(msg);
			isfirst='false';    
			// alert(eval( "(" + msg + ")" ));
			 curPaView(eval( "("+msg+ ")" ));
			 
			                 
		},
		error : function(XmlHttpRequest, textStatus, errorThrown) {
			
		},
		beforeSend : function() {
			
		},
		complete : function() {
			chart_canvas.unblock();
		},
		
	});
}
//解析json数据                       
function curPaView(data){
    alldata=data;
	if(charname=="activeuser"){
		var obj=data.datas;
	}
	if(charname=="newuser"){
		var obj=data.datan;
	}
	if(TotalOSdata==''){
		TotalOSdata=data.totaldata;
		if(TotalOSdata==null)
			TotalOSdata="";
		initPagination(TotalOSdata.length);
		pageselect(0, 0);
	}
	var str='';
    for(var i=0;i<obj.length;i++)
    {
        str=str+"<tr><td>"+obj[i].deviceos_name+"</td><td><div style='background-color: rgb(116, 119, 213); height: 15px; width:"+ <?php  echo BLOCK_MAX_LENGTH; ?> * obj[i].percentage +"px;'></div>"
        +"</td><td>"+Math.floor(10000*obj[i].percentage)/100+"%</td></tr>";
    }
    document.getElementById('contentss').innerHTML=str;
}
	
</script>
 

