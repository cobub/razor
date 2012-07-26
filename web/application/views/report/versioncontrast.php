<section id="main" class="column">
		<h4 class="alert_success" id='msg'><?php echo  lang('versioncontrast_alertinfo')?></h4>		
    
    <article class="module width_full">
			<header><h3 class="tabs_involved"><div id="day"><?php echo  lang('versioncontrast_headeinfo')?></div></h3>
			<span class="relative r">
                	<a href="#this" class="bottun4" onclick="sever('server1','server1c1');"><font>?</font></a>
                	<div class="server333" id="server1" style="width:620px;">
                       <div class="ser_title">
                           <b class="l"><?php echo  lang('versioncontrast_settitle')?></b>                          
                           <a class="r" href="#this" id="server1c1"><img src="<?php echo base_url(); ?>assets/images/server_close.gif" /></a>
                       </div>
                       
                                <style>
								.ser_txt font{
									width:110px
								}
								</style>
                       <div class="ser_txt">
                           <dl>
                               <dt>
                               	<font><?php echo  lang('versioncontrast_remindversionuser')?></font>
                               	<small><?php echo  lang('versioncontrast_remindtotaluser')?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo  lang('versioncontrast_remindnewuser')?></font>
                               	<small><?php echo  lang('versioncontrast_remindyesterday')?></small>
                                <div class="clear"></div>
                               </dd>
                               <dt>
                               	<font><?php echo  lang('versioncontrast_remindupgrade')?></font>
                               	<small><?php echo  lang('versioncontrast_remindyeupgrade')?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo  lang('versioncontrast_remindactuser')?></font>
                               	<small><?php echo  lang('versioncontrast_remindyeunique')?></small>
                                <div class="clear"></div>
                               </dd>
                               <dt>
                               	<font><?php echo  lang('versioncontrast_remindstartnum')?></font>
                               	<small><?php echo  lang('versioncontrast_remindsumnum')?></small>
                                <div class="clear"></div>
                               </dt>                               
                           </dl>
                       </div>
                	</div>
                </span>			
			</header>
			<table class="tablesorter" cellspacing="0">
			<thead> 
				<tr> 
				    <th><?php echo  lang('versioncontrast_appvresionth')?></th> 				    
    				<th><?php echo  lang('versioncontrast_totalth')?></th>     				     				
    				<th><?php echo  lang('versioncontrast_newth')?></th>
    				<th><?php echo  lang('versioncontrast_upgradeth')?></th> 				    
    				<th><?php echo  lang('versioncontrast_activeth')?></th>     				     				
    				<th><?php echo  lang('versioncontrast_startth')?></th>
				</tr> 
			</thead>
			<tbody> 
			<?php if(isset($versionList)&&count($versionList)>0):
			$allusers = 0;
			$activeusers = 0;
			   for ($i=0;$i<count($versionList);$i++)
			   {
			   	$row = $versionList[$i];
			   	$allusers+=$row['total'];
			   	$activeusers+=$row['active'];
			   	
			   }
			 	for($i=0;$i<count($versionList);$i++)
			 	{
			 		$row = $versionList[$i];
			 ?>
				<tr> 
    				<td><?php echo ($row['version']==null)?lang('versioncontrast_tbodyunknow'):$row['version'];?></td> 
    				<td><?php echo $row['total']."(".percent($row["total"], $allusers).")";?></td> 
    				<td><?php echo $row["new"];?></td> 
    				<td><?php echo $row["update"];?></td>
    				<td><?php echo $row['active']."(".percent($row["active"], $activeusers).")";?></td>
    				<td><?php echo $row["start"];?></td>
				</tr> 
			<?php } endif;?>											
			</tbody> 			
			</table>
	</article>
		
								
	<article class="module width_full">
	  <header><h3 class="tabs_involved"><?php echo  lang('versioncontrast_vertrend')?></h3>	  
					<div class="submit_link">
					<select onchange=selectChange(this.value) id='select'>
						<option value=过去一周 selected><?php echo  lang('allview_lastweek')?></option>
						<option value=过去一个月><?php echo  lang('allview_lastmonth')?></option>
						<option value=过去三个月><?php echo  lang('allview_last3month')?></option>
						<option value=全部><?php echo  lang('allview_alltime')?></option>
						<option value=任意时间段><?php echo  lang('allview_anytime')?></option>
					</select>
		<div id='selectTime'>
		<input type="text" id="dpFrom0"> 
		<input type="text" id="dpTo0"> 
		<input type="submit" id='btn' value="<?php echo  lang('allview_timebtn')?>" class="alt_btn" onclick="onAnyTimeButtonClicked()"></div>
		</div>
	  </header>

	<article class="width_full">
	     <div id="container"  class="module_content" style="height:300px">
		</div>
		</article>
		<div class="clear"></div>
		
		<footer>
			<ul class="tabs2">
				<li><a  href="javascript:changeChartType('new')"><?php echo  lang('versioncontrast_newcharttab')?></a></li>
				<li><a  href="javascript:changeChartType('active')"><?php echo  lang('versioncontrast_actchartab')?></a></li>
			</ul>
		</footer>
	</article>		

<article class="module width_full">
			<header><h3 class="tabs_involved"><?php echo  lang('versioncontrast_versiondistrtion')?></h3>
   			<div class="submit_link" >
					 <select onchange=selectStyletop(value) id='selectstyletop'>
						<option value = TOP5版本><?php echo  lang('versioncontrast_distop5ver')?></option>
						<option value=TOP10版本><?php echo  lang('versioncontrast_distop10ver')?></option>
						<option value=全部><?php echo  lang('versioncontrast_disallver')?></option>
					</select>
				</div></header>
			
		<div align="center" id='selectTime1'><?php echo  lang('versioncontrast_seletctime')?>
		            <input type="text" id="dpFrom1" > <?php echo  lang('versioncontrast_fromtime')?>
					<input type="text" id="dpTo1">  <?php echo  lang('versioncontrast_tiemvs')?>  
					<input type="text" id="dpFrom2"> <?php echo  lang('versioncontrast_totime')?>
					<input type="text" id="dpTo2">
		<input type="button" id='btn' value="<?php echo  lang('versioncontrast_selecttimebtn')?>" class="alt_btn" onclick="styleTimeButtonClicked()"></div>
						
	  <hr>
	  
			<table class="tablesorter" cellspacing="0" style="height:100px"> 
			<thead> 
				<tr> 
				    <th><?php echo  lang('versioncontrast_apptheadversion')?></th> 				    
    				<th><div id="userper"><?php echo  lang('versioncontrast_apptheadnew')?></div><span id="newuserfromto1"></span></th>     				     				
    				<th><div id="userper1"><?php echo  lang('versioncontrast_apptheadvsnew')?></div><span id="newuserfromto2"></span></th>
				</tr> 
			</thead>
			<tbody id="versinlist"> 
													
			</tbody> 						
			</table>
		<footer>		
		  <ul class="tabs3">					
			<li><a id="111" mt="newUser1" href="javascript:onContrastTabClicked('NewUser')" onclick='changenew()'><?php echo  lang('versioncontrast_appcharnew')?></a></li>
			<li><a id="222" mt="activeUser1" href="javascript:onContrastTabClicked('ActiveUser')" onclick='changeactive()'><?php echo  lang('versioncontrast_appchartact')?></a></li>
	      </ul>
	 </footer>	
</article>	
<div class="spacer"></div>
		
		
</section>
<script type="text/javascript">
function changenew()
{
	var type =document.getElementById('111').innerHTML;
	document.getElementById('userper').innerHTML=type+"<?php echo  lang('versioncontrast_jspercent')?>";
	document.getElementById('userper1').innerHTML=type+"<?php echo  lang('versioncontrast_jspercent')?>";
}
function changeactive()
{
	var type =document.getElementById('222').innerHTML;
	document.getElementById('userper').innerHTML=type+"<?php echo  lang('versioncontrast_jspercent')?>";
	document.getElementById('userper1').innerHTML=type+"<?php echo  lang('versioncontrast_jspercent')?>";
}
</script>

<script type="text/javascript">
function changeday()
{
	var type =document.getElementById('whichday').innerHTML;
	if(type=="<?php echo  lang('versioncontrast_viewtoday')?>")
	{
		document.getElementById('day').innerHTML="<?php echo  lang('versioncontrast_changetoday')?>";
		document.getElementById('whichday').innerHTML="<?php echo  lang('versioncontrast_yesterday')?>";
	}
	else
	{
		document.getElementById('day').innerHTML="<?php echo  lang('versioncontrast_changeyesterday')?>";
		document.getElementById('whichday').innerHTML="<?php echo  lang('versioncontrast_viewtoday')?>"	;	
	}
}
function changeactive()
{
	var type =document.getElementById('222').innerHTML;
	document.getElementById('userper').innerHTML=type+"<?php echo  lang('versioncontrast_jspercent')?>";
	document.getElementById('userper1').innerHTML=type+"<?php echo  lang('versioncontrast_jspercent')?>";
}
</script>
<script type="text/javascript">

var styleName = 'NewUser';
var version='5'
</script>

<script type="text/javascript">
var chartversion = 'default';
var chartName = 'new';
var time = '7day';
var fromTime='';
var toTime='';
var jsondata;
var timePhase = '7day';
var chart;
var chartdata;
var contrast_data;
var titlename='';

//When page loads...
dispalyOrHideTimeSelect();
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$("ul.tabs3 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

function dispalyOrHideTimeSelect()
{
	 var value = document.getElementById('select').value;
	 if(value=='任意时间段')
	 {
		 document.getElementById('selectTime').style.display="inline";
	 }
	 else
	 { 
		 document.getElementById('selectTime').style.display="none";
	 }
} 

function changeChartName(name)
{
	chartName = name;
	getChartData();
}
function selectChangetop(value)
{
    if(value=='TOP5版本')
    {
    	chartversion='5';
        getChartData();           
     }
    if(value=='TOP10版本')
    {
    	chartversion='10';
    	getChartData();
        
     }
    if(value=='全部')
    {
    	chartversion='1000';
    	getChartData();
     }          
}
function selectChange(value)
{
    if(value=='过去一周')
    {
    	timePhase='7day';
     }
    if(value=='过去一个月')
    {
    	timePhase='1month';
        
     }
    if(value=='过去三个月')
    {
    	timePhase='3month';
        
     }
    if(value=='全部')
    {
    	timePhase='all';
     }
    if(value=='任意时间段')
    {
    	timePhase='any';
    }
    if(timePhase!='any')
    {
        getChartData();
    }
    dispalyOrHideTimeSelect();           
}
function changeStyleName(name)
{
	styleName = name;
	getdata();
}
function selectStyletop(value)
{
    if(value=='TOP5版本')
    {
        version='5';
        getdata();           
     }
    if(value=='TOP10版本')
    {
    	version='10';
        getdata();
        
     }
    if(value=='全部')
    {
    	version='100';
        getdata();
     }          
}
//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("ct"); //Find the href attribute value to identify the active tab + content
	$('#'+activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
$("ul.tabs3 li").click(function() {
	$("ul.tabs3 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("mt"); //Find the href attribute value to identify the active tab + content
	$('#'+activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
</script>

<script type="text/javascript">
	$(function() {
		$("#dpFrom0" ).datepicker();
	});
	$( "#dpFrom0" ).datepicker({ dateFormat: "yy-mm-dd" });
	$(function() {
		$( "#dpTo0" ).datepicker();
	});
	$( "#dpTo0" ).datepicker({ dateFormat: "yy-mm-dd" });
	$(function() {
		$("#dpFrom1" ).datepicker();
	});
	$( "#dpFrom1" ).datepicker({ dateFormat: "yy-mm-dd" });
	$(function() {
		$( "#dpTo1" ).datepicker();
	});
	$( "#dpTo1" ).datepicker({ dateFormat: "yy-mm-dd" });
	$(function() {
		$("#dpFrom2" ).datepicker();
	});
	$( "#dpFrom2" ).datepicker({ dateFormat: "yy-mm-dd" });
	$(function() {
		$( "#dpTo2" ).datepicker();
	});
	$( "#dpTo2" ).datepicker({ dateFormat: "yy-mm-dd" });  
</script>

<script type="text/javascript">
function onAnyTimeButtonClicked()
{  
	fromTime = document.getElementById('dpFrom0').value;
	toTime = document.getElementById('dpTo0').value;
	getChartData();
}
function styleTimeButtonClicked()
{  
	fromTime1 = document.getElementById('dpFrom1').value;
	toTime1 = document.getElementById('dpTo1').value;
	document.getElementById('newuserfromto1').innerHTML = "("+fromTime1 + '-' + toTime1+")";
	fromTime2 = document.getElementById('dpFrom2').value;
	toTime2 = document.getElementById('dpTo2').value;
	document.getElementById('newuserfromto2').innerHTML = "("+fromTime2 + '-' + toTime2+")";
	getdata();
}

</script>
  
<script type="text/javascript">
var options;
$(document).ready(function() {
	options = {
            chart: {
                renderTo: 'container',
                type: 'spline'
            },
            title: {
                text: '   '
            },
            subtitle: {
                text: ' '
            },
            xAxis: {
                labels:{rotation:300,y:40,x:0}
            },
            yAxis: {
                title: {
                    text: ''
                },
                min:0,
                labels: {
                    formatter: function() {
                        return Highcharts.numberFormat(this.value, 0);
                    }
                }
            },
            tooltip: {
                crosshairs: true,
                shared: true
            },
            plotOptions: {
                spline: {
                    marker: {
                        radius: 1,
                        lineColor: '#666666',
                        lineWidth: 1
                    }
                }
            },
            legend:{
                labelFormatter: function() {
                	return this.name
                }
             },
            series: [
        
            ]
        };
    
	chartName = "new";
	getChartData();
});

    function renderCharts(myurl)
    {	
    	 var chart_canvas = $('#container');
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
    	//	alert(myurl);
    	jQuery.getJSON(myurl, null, function(data) {  
        	chartdata = data;	
        	var version_array = [];
        	for(var key in data.content)
        	{
            	version_array.push(key);

            }
    		for(var j=0;j<version_array.length;j++)
    		{
        		version = version_array[j];
        		var eachVersionData = data.content[version];
        		var categories = [];
    			var newUsers = [];
        		for(var i=0;i<eachVersionData.length;i++)
        		{
            		var eachVersionDataItem = eachVersionData[i];
            		if(chartName=="new")
            		    newUsers.push(parseInt(eachVersionDataItem.newusers,10));
            		if(chartName=="active")
            			newUsers.push(parseInt(eachVersionDataItem.startusers,10));
		    		categories.push(eachVersionDataItem.datevalue.substr(0,10));

            	}
        		options.series[j] = {};
        		if(version == "")
    		    {
    		    	options.series[j].name = "<?php echo  lang('versioncontrast_jsunknowinfo')?>";
    		    }
    		    else
    		    {
        		    options.series[j].name = version;
    		    }
    		    options.series[j].data = newUsers;
    			options.xAxis.labels.step = parseInt(categories.length/10);
    			options.xAxis.categories = categories; 
    			options.title.text = titlename;

        	}
    	    
    	    
    	    chart = new Highcharts.Chart(options);
    		chart_canvas.unblock();
    		});  
    }
  	    
</script>
<script type="text/javascript">
function changeChartType(type)
{
	changeChartTitleName(timePhase,type);
	chartName = type;
	var data = chartdata;
	var version_array = [];
	for(var key in data.content)
	{
    	version_array.push(key);

    }
	for(var j=0;j<version_array.length;j++)
	{
		
		version = version_array[j];
		var eachVersionData = data.content[version];
		var categories = [];
		var newUsers = [];
		for(var i=0;i<eachVersionData.length;i++)
		{
    		var eachVersionDataItem = eachVersionData[i];
    		if(type=="new")
    		{
    		    newUsers.push(parseInt(eachVersionDataItem.newusers,10));
    		}
    		if(type=="active")
    		{
   			  newUsers.push(parseInt(eachVersionDataItem.startusers,10));
        	}
        	
    		categories.push(eachVersionDataItem.datevalue.substr(0,10));

    	}
		options.series[j] = {};
		if(version == "")
	    {
	    	options.series[j].name = "<?php echo  lang('versioncontrast_jsunknowinfo')?>";
	    }
	    else
	    {
		    options.series[j].name = version;
	    }
	    
	    options.series[j].data = newUsers;
		options.xAxis.labels.step = parseInt(categories.length/10);
		options.xAxis.categories = categories; 
		options.title.text = titlename;

	}
    
    
    chart = new Highcharts.Chart(options);

}
</script>
<script type="text/javascript">

function getChartData()
{
	changeChartTitleName(timePhase,chartName);
	var myurl = "";
	if(timePhase=='any')
	{		
		myurl="<?php echo site_url()?>/report/version/getVersionData/"+chartName+"/"+timePhase+"/"+fromTime+"/"+toTime;
	}
	else
	{
		myurl="<?php echo site_url()?>/report/version/getVersionData/"+chartName+"/"+timePhase;
	}
	renderCharts(myurl);
}

function changeChartTitleName(timePhase,chartName){
	if (timePhase == "7day") {
		if(chartName=="active"){
			titlename = "<?php echo lang('versiontitle_actweek') ?>";
		}
		if(chartName=="new"){
			titlename ="<?php echo lang('versiontitle_lastweeknew')?>" ;
			
		}
	}
	if (timePhase == "1month") {
		if(chartName=="active"){
			titlename ="<?php echo lang('versiontitle_actmonth') ?>";
		}
		else
			titlename ="<?php echo lang('versiontitle_lastmonthnew') ?>";
	}	
	if (timePhase == "3month") {
		if(chartName=="active"){
			titlename ="<?php echo lang('versiontitle_act3month')?>";
		}
		else
			titlename ="<?php echo lang('versiontitle_last3monthnew') ?>";
	}
	if (timePhase == "all") {
		if(chartName=="active"){
			titlename ="<?php echo  lang('versiontitle_actall') ?>";
		}
		else
			titlename ="<?php echo lang('versiontitle_lastallnew') ?>";
	}  
	if (timePhase == "any") {
		if(chartName=="active"){
			titlename ="<?php echo  lang('versiontitle_actanytime') ?>";
		}
		else
			titlename ="<?php echo lang('versiontitle_lastanytimenew') ?>";
	}  
}
</script>
<script type="text/javascript">
function getdata()
{
	var myurl = "";
	if(styleName == 'NewUser')
	{	
		myurl="<?php echo site_url()?>/report/version/getVersionContrast/"+fromTime1+"/"+toTime1+"/"+fromTime2+"/"+toTime2;
	}
	else
	{		
	   myurl="<?php echo site_url()?>/report/version/getVersionContrast/"+fromTime1+"/"+toTime1+"/"+fromTime2+"/"+toTime2;
	}
	
	jQuery.ajax({
		type : "post",
		url : myurl,
		success : function(msg) {
			document.getElementById('msg').innerHTML = "<?php echo  lang('versioncontrast_jsloadpmsg')?>";			
			jsonData=eval("("+msg+")");	
			contrast_data = jsonData;
					
			if(document.getElementById("versinlist").value!="")							
			{
				clearSel(document.getElementById("versinlist")); 
			}	
					
			for(j = 0;j<jsonData[1].length;j++)
		    {
			    if(styleName == "NewUser")	 
			     document.getElementById('versinlist').innerHTML+='<tr><td>'+jsonData[0][j]['version_name']+'</td><td>'+jsonData[0][j]['newuserpercent']+'</td><td>'+jsonData[1][j]['newuserpercent']+'</td></tr>';
			    if(styleName == "ActiveUser")
				     document.getElementById('versinlist').innerHTML+='<tr><td>'+jsonData[0][j]['version_name']+'</td><td>'+jsonData[0][j]['startuserpercent']+'</td><td>'+jsonData[1][j]['startuserpercent']+'</td></tr>';
			    
		    } 
									
		},
		error : function(XmlHttpRequest, textStatus, errorThrown) {
			document.getElementById('msg').innerHTML = "<?php echo  lang('versioncontrast_jserrormsg')?>";
			
		},
		beforeSend : function() {
			document.getElementById('msg').innerHTML = '<?php echo  lang('versioncontrast_jswaitmsg')?>';

		},
		complete : function() {
		}
	});
}
</script>
<script type="text/javascript">

function onContrastTabClicked(styleName)
{
	var jsonData = contrast_data;
	
	if(document.getElementById("versinlist").value!="")							
	{
		clearSel(document.getElementById("versinlist")); 
	}	
			
	for(j = 0;j<jsonData[1].length;j++)
    {
	    if(styleName == "NewUser")	 
	     document.getElementById('versinlist').innerHTML+='<tr><td>'+jsonData[0][j]['version_name']+'</td><td>'+jsonData[0][j]['newuserpercent']+'</td><td>'+jsonData[1][j]['newuserpercent']+'</td></tr>';
	    if(styleName == "ActiveUser")
		     document.getElementById('versinlist').innerHTML+='<tr><td>'+jsonData[0][j]['version_name']+'</td><td>'+jsonData[0][j]['startuserpercent']+'</td><td>'+jsonData[1][j]['startuserpercent']+'</td></tr>';
	    
    } 
}
</script>
<script type="text/javascript">
function setTBodyInnerHTML(tbody, html) {
	  var temp = tbody.ownerDocument.createElement('div');
	  temp.innerHTML = '<table><tbody id=\"content\">' + html + '</tbody></table>';
	  tbody.parentNode.replaceChild(temp.firstChild.firstChild, tbody);
	}       
</script>
<script type="text/javascript">
function clearSel(selectname){
    
	  while(selectname.childNodes.length>0){
		  selectname.removeChild(selectname.childNodes[0]);
	  }
}  
</script>
<script type="text/javascript">
function xytt(txt, bg, colse) {
	var txt = txt;
	var bg = bg;
	$("#" + txt + " input").val("");
	var sHeight = document.body.clientHeight;
	var dheight = document.documentElement.clientHeight;
	var srctop = document.documentElement.scrollTop;
	if($.browser.safari) {
		srctop = window.pageYOffset;
	}
	$(".xy").css({
		"height" : dheight
	});
	dheight = (dheight - $("#" + txt).height()) / 2;
	$("#" + txt).show();
	$("#" + bg).show();
	$("#" + txt).css({
		"top" : (srctop + dheight) + "px"
	});
	$("#" + bg).css({
		"top" : (srctop ) + "px"
	});
	window.onscroll = function scall() {
		var srctop = document.documentElement.scrollTop;
		if($.browser.safari) {
			srctop = window.pageYOffset;
		}
		$("#" + txt).css({
			"top" : (srctop + dheight) + "px"
		});
		$("#" + bg).css({
			"top" : (srctop) + "px"
		});
		$("#fkicon").css({
			top : srctop + (innerHeights / 2)
		});
		window.onscroll = scall;
		window.onresize = scall;
		window.onload = scall;
	}
	$("." + colse).click(function() {
		$("#" + txt).hide();
		$("#" + bg).hide();
	})
}

function sever(txt, colse) {
	$("#" + txt).slideToggle("slow");
	$("#" + colse).click(function() {
		$("#" + txt).slideUp("slow");
	});
	
}

</script>

