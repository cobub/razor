<section id="main" class="column" style="height:1000px;">
		<h4 class="alert_success" id='msg' style="display:none;"></h4>		
    
    <article class="module width_full">
			<header><h3 class="tabs_involved"><div id="day"><?php echo  lang('v_rpt_ve_tillYesterday')?></div></h3>				
			</header>
			<table class="tablesorter" cellspacing="0">
			<thead> 
				<tr> 
				    <th><?php echo  lang('v_rpt_ve_appVersion')?></th> 				    
    				<th><?php echo  lang('t_accumulatedUsersP')?></th>     				     				
    				<th><?php echo  lang('t_newUsers')?></th>
    				<th><?php echo  lang('t_upgradeUsers')?></th> 				    
    				<th><?php echo  lang('t_activeUserP')?></th>     				     				
    				<th><?php echo  lang('t_sessions')?></th>
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
    				<td><?php echo ($row['version']==null)?lang('t_unknow'):$row['version'];?></td> 
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
	  <header><h3 class="tabs_involved"><?php echo  lang('v_rpt_ve_changingTrends')?></h3>	  
		<ul class="tabs2">
				<li><a  href="javascript:changeChartType('<?php echo  lang('t_newUsers')?>')"><?php echo  lang('t_newUsers')?></a></li>
				<li><a  href="javascript:changeChartType('<?php echo  lang('t_activeUsers')?>')"><?php echo  lang('t_activeUsers')?></a></li>
		</ul>	
	  </header>

	<article class="width_full">
	     <div id="container"  class="module_content" style="height:300px">
		</div>
		</article>
		<div class="clear"></div>	
	</article>		

<article class="module width_full">
			<header><h3 class="tabs_involved"><?php echo  lang('v_rpt_ve_comparison')?></h3>
   			<div class="submit_link" >
					 <select onchange=selectStyletop(value) id='selectstyletop'>
						<option value = TOP5><?php echo  lang('v_rpt_ve_top5')?></option>
						<option value=TOP10><?php echo  lang('v_rpt_ve_top10')?></option>
						<option value=all><?php echo  lang('g_all')?></option>
					</select>
				</div></header>
			
		<div align="center" id='selectTime1'><?php echo  lang('v_rpt_ve_selectTime')?>
		            <input type="text" id="dpFrom1" > <?php echo  lang('v_rpt_ve_to')?>
					<input type="text" id="dpTo1">  <?php echo  lang('v_rpt_ve_vs')?>  
					<input type="text" id="dpFrom2"> <?php echo  lang('v_rpt_ve_to')?>
					<input type="text" id="dpTo2">
		<input type="button" id='btn' value="<?php echo  lang('g_update')?>" class="alt_btn" onclick="styleTimeButtonClicked()"></div>
						
	  <hr>
	  
			<table class="tablesorter" cellspacing="0" style="height:100px"> 
			<thead> 
				<tr> 
				    <th><?php echo  lang('v_rpt_ve_appVersion')?></th> 				    
    				<th><div id="userper"><?php echo  lang('t_newUsersP')?></div><span id="newuserfromto1"></span></th>     				     				
    				<th><div id="userper1"><?php echo  lang('t_newUsersP')?></div><span id="newuserfromto2"></span></th>
				</tr> 
			</thead>
			<tbody id="versinlist"> 
													
			</tbody> 						
			</table>
		<footer>		
		  <ul class="tabs3">					
			<li><a id="111" mt="newUser1" href="javascript:onContrastTabClicked('NewUser')" onclick='changenew()'><?php echo  lang('t_newUsers')?></a></li>
			<li><a id="222" mt="activeUser1" href="javascript:onContrastTabClicked('ActiveUser')" onclick='changeactive()'><?php echo  lang('t_activeUsers')?></a></li>
	      </ul>
	 </footer>	
</article>	
<div class="spacer"></div>
		
		
</section>
<script type="text/javascript">
function changenew()
{
	var type =document.getElementById('111').innerHTML;
	document.getElementById('userper').innerHTML=type+"<?php echo  lang('g_percent')?>";
	document.getElementById('userper1').innerHTML=type+"<?php echo  lang('g_percent')?>";
}
function changeactive()
{
	var type =document.getElementById('222').innerHTML;
	document.getElementById('userper').innerHTML=type+"<?php echo  lang('g_percent')?>";
	document.getElementById('userper1').innerHTML=type+"<?php echo  lang('g_percent')?>";
}
</script>

<script type="text/javascript">
function changeday()
{
	var type =document.getElementById('whichday').innerHTML;
	if(type=="<?php echo  lang('v_rpt_ve_viewToday')?>")
	{
		document.getElementById('day').innerHTML="<?php echo  lang('v_rpt_ve_versionST')?>";
		document.getElementById('whichday').innerHTML="<?php echo  lang('v_rpt_ve_viewYesterday')?>";
	}
	else
	{
		document.getElementById('day').innerHTML="<?php echo  lang('v_rpt_ve_ersionSY')?>";
		document.getElementById('whichday').innerHTML="<?php echo  lang('v_rpt_ve_viewToday')?>"	;	
	}
}
function changeactive()
{
	var type =document.getElementById('222').innerHTML;
	document.getElementById('userper').innerHTML=type+"<?php echo  lang('g_percent')?>";
	document.getElementById('userper1').innerHTML=type+"<?php echo  lang('g_percent')?>";
}
</script>
<script type="text/javascript">
var styleName = 'NewUser';
var version='5'
</script>

<script type="text/javascript">
var chartversion = 'default';
var chartName = '<?php echo  lang('t_newUsers')?>';
var time = '7day';
var fromTime='';
var toTime='';
var jsondata;
var chart;
var contrast_data;
var titlename='';

//When page loads...
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$("ul.tabs3 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content
function changeStyleName(name)
{
	styleName = name;
	getdata();
}
function selectStyletop(value)
{
    if(value=='TOP5')
    {
        version='5';
        getdata();           
     }
    if(value=='TOP10')
    {
    	version='10';
        getdata();
        
     }
    if(value=='all')
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
function styleTimeButtonClicked()
{  
	fromTime1 = document.getElementById('dpFrom1').value;
	toTime1 = document.getElementById('dpTo1').value;
	document.getElementById('newuserfromto1').innerHTML = "("+fromTime1 + '-' + toTime1+")";
	fromTime2 = document.getElementById('dpFrom2').value;
	toTime2 = document.getElementById('dpTo2').value;
	document.getElementById('newuserfromto2').innerHTML = "("+fromTime2 + '-' + toTime2+")";
	ft1=new Date(fromTime1);
	tot1=new Date(toTime1);
	ft2=new Date(fromTime2);
	tot2=new Date(toTime2);
	if(ft1>tot1||ft2>tot2){
	alert('<?php echo lang("g_timeError")?>');return;}
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
                text: '<?php echo $reportTitle['timePase']; ?>'
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
	var myurl="<?php echo site_url()?>/report/version/getVersionData";
	renderCharts(myurl);
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
	jQuery.getJSON(myurl, null, function(data) {     	
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
			var reportTitle;
    		for(var i=0;i<eachVersionData.length;i++)
    		{
        		var eachVersionDataItem = eachVersionData[i];           		
        		if(chartName=="<?php echo  lang('t_newUsers')?>")
        		{
       			 newUsers.push(parseInt(eachVersionDataItem.newusers,10));
      		     reportTitle="<?php echo $reportTitle['newUser'] ?>";
            	}            		   
        		if(chartName=="<?php echo  lang('t_activeUsers')?>")
        		{
        			newUsers.push(parseInt(eachVersionDataItem.startusers,10));
            		reportTitle="<?php echo $reportTitle['activeUser'] ?>";
            	}            		
	    		categories.push(eachVersionDataItem.datevalue.substr(0,10));
        	}
    		options.series[j] = {};
    		if(version == "")
		    {
		    	options.series[j].name = "<?php echo  lang('t_unknow')?>";
		    }
		    else
		    {
    		    options.series[j].name = version;
		    }
		    options.series[j].data = newUsers;
			options.xAxis.labels.step = parseInt(categories.length/10);
			options.xAxis.categories = categories; 
			options.title.text = reportTitle;
    	}    	    
	    chart = new Highcharts.Chart(options);
		chart_canvas.unblock();
		});  
    }
  	    
</script>
<script type="text/javascript">
function changeChartType(type)
{	
	chartName = type;
	var myurl="<?php echo site_url()?>/report/version/getVersionData";
	renderCharts(myurl);
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
			document.getElementById('msg').innerHTML = "<?php echo  lang('v_rpt_ve_competeLoad')?>";	
			document.getElementById('msg').style.display="block";		
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
			document.getElementById('msg').innerHTML = "<?php echo  lang('t_error')?>";
			document.getElementById('msg').style.display="block";
			
		},
		beforeSend : function() {
			document.getElementById('msg').innerHTML = '<?php echo  lang('v_rpt_ve_waitLoad')?>';
			document.getElementById('msg').style.display="block";

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
