<section id="main" class="column" style="height:2700px">
	<!-- Country -->
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_re_top10Nations') ?></h3>
			<ul class="tabs1">
				<li><a href="javascript:changeCountryReportType('activeuser')"><?php echo lang('t_activeUsers') ?></a></li>
				<li><a href="javascript:changeCountryReportType('newuser')"><?php echo lang('t_newUsers') ?></a></li>
			</ul>
		</header>
		<div id="country" style="height:450px">
		</div>
	</article>
	<!--Region -->
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_re_top10Provinces') ?></h3>
			<ul class="tabs2">
				<li><a href="javascript:changeRegionReportType('regionactiveuser')"><?php echo lang('t_activeUsers') ?></a></li>
				<li><a href="javascript:changeRegionReportType('regionnewuser')"><?php echo lang('t_newUsers') ?></a></li>
			</ul>
		</header>
		<div id="region" style="height:450px">
		</div>
	</article>
	<!-- Country -->
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_re_top10Nations') ?></h3>
			<ul class="tabs3">
				<li><a href="javascript:changeDetailCountryType('activeuser')"><?php echo  lang('t_activeUsers')?></a></li>
				<li><a href="javascript:changeDetailCountryType('newuser')"><?php echo  lang('t_newUsers')?></a></li>
			</ul>
			<span class="relative r"> <a
				href="<?php echo site_url().'/report/region/exportCSV/country'?>"
				class="bottun4 hover"><font><?php echo lang('g_exportToCSV') ?></font></a>
			</span>
		</header>
		<table id="countrytable" class="tablesorter" cellspacing="0">
		    <thead id="countrydetaildatatitle"></thead>
			<tbody id="countrydetaildata"></tbody>
		</table>
	</article>
	<!--Region -->
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_re_top10Provinces') ?></h3>
			<ul class="tabs4">
				<li><a href="javascript:changeDetailRegionType('regionactiveuser')"><?php echo  lang('t_activeUsers')?></a></li>
				<li><a href="javascript:changeDetailRegionType('regionnewuser')"><?php echo  lang('t_newUsers')?></a></li>
			</ul>
			<span class="relative r"> <a
				href="<?php echo site_url().'/report/region/exportCSV/region'?>"
				class="bottun4 hover"><font><?php echo lang('g_exportToCSV') ?></font></a>
			</span>
		</header>
		<table id="regiontable" class="tablesorter" cellspacing="0">
		    <thead id="regiondetaildatatitle"></thead>
			<tbody id="regiondetaildata"></tbody>
		</table>
	</article>
</section>

<script type="text/javascript">
$(".tab_content").hide();
$("ul.tabs1 li:first").addClass("active").show();
$("ul.tabs2 li:first").addClass("active").show();
$("ul.tabs3 li:first").addClass("active").show();
$("ul.tabs4 li:first").addClass("active").show();
$(".tab_content:first").show();

$("ul.tabs1 li").click(function() {
	$("ul.tabs1 li").removeClass("active");
	$(this).addClass("active");
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active");
	$(this).addClass("active");
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
	});
$("ul.tabs3 li").click(function() {
	$("ul.tabs3 li").removeClass("active");
	$(this).addClass("active");
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
	});
$("ul.tabs4 li").click(function() {
	$("ul.tabs4 li").removeClass("active");
	$(this).addClass("active");
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
	});

var chart;
var options;
var reportcountryType="activeuser";
var reportregionType="regionactiveuser";
var detailCountryData='';
var detailRegionData='';
var newUserData = new Array(new Array(),new Array());
var activeUserData = new Array(new Array(),new Array());
var regionNewUserData=new Array(new Array(),new Array());
var regionActiveUserData=new Array(new Array(),new Array());

$(document).ready(function() {
	options = {
	        chart: {
	            type:'pie'
		 	        },
	        title: {
	            text: ''
	        },
	        subtitle: {
	            text: ''
	        },
	        tooltip: {
	        	formatter: function () {  
                    return '<b>' + this.series.name+'<br>'+ this.point.name + '</b>: ' + Highcharts.numberFormat(this.percentage, 1) + ' %';  
                } 
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>:<br> '+ Highcharts.numberFormat(this.percentage,1) +' %';
                        }
                    },
                    showInLegend: true
                }
            },
            colors: [
    	 	           	'#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', '#DB843D', 
    	 	           	'#92A8CD', '#A47D7C', '#B5CA92','#058DC7', '#50B432','#ED561B', 
    		 	        '#DDDF00','#24CBE5','#64E572', '#FF9655', '#FFF263', '#6AF9C4',
    	  			 	'#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE','#DB843D', 
   	 	           	'#92A8CD', '#A47D7C','#B5CA92','#058DC7', '#50B432','#ED561B', 
   		 	        '#DDDF00','#24CBE5','#64E572', '#FF9655','#FFF263', '#6AF9C4',
   		 	        '#89A54E', '#80699B', '#3D96AE', '#DB843D', '#92A8CD','#A47D7C', 
   		 	        '#B5CA92','#058DC7', '#50B432','#ED561B', '#DDDF00','#24CBE5',
   		 	        '#64E572', '#FF9655','#FFF263', '#6AF9C4'	    
    		      ],
            labels:{
            	items:[],
            	style:{'font-size':'16px'}
            },
            credits: {
  	           enabled: false
  	        },
	        series: []
	    };
	var countryDataURL = "<?php echo site_url();?>/report/region/getCountryData";
	renderCountryCharts(countryDataURL);
	var regionDataURL = "<?php echo site_url();?>/report/region/getRegionData";
	renderRegionCharts(regionDataURL)
});
	function renderCountryCharts(myurl)
	{
		var chart_canvas = $('#country');
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
			baseZ:997});

		jQuery.getJSON(myurl, null, function(data) {
			detailCountryData=data;
	    	var productName='';
	    	var j=0;
			for (var key in data) {
				j++;
				if(key.length<11){
					break;
				}
				if(key.indexOf('a')==0){
					productName=key.substring(14);
					activeUserData[productName]=[];
				}
				if(key.indexOf('n')==0){
					productName=key.substring(11);
					newUserData[productName]=[];
				}
				var obj=data[key];
				for(i=0;i<obj.length;i++)
				{
					var pieObj = {};
					if(obj[i].country==''){
						pieObj.name="unknow";
					}else{
						pieObj.name=obj[i].country;}
					pieObj.sliced = false;
					pieObj.y = obj[i].percentage;
					pieObj.selected = false;
					if(typeof(newUserData[productName])=='undefined'){
						activeUserData[productName].push(pieObj);
					}else{
						newUserData[productName].push(pieObj);
					}
				}
			}
			if((j/2)>2){
				 document.getElementById("country").style.height='780px';
			}
			changeDetailCountryType("activeuser");
			changeCountryReportType(reportcountryType);
			chart_canvas.unblock();
		});
	}
	function renderRegionCharts(myurl)
	{
		var chart_canvas = $('#region');
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
			baseZ:997});
		jQuery.getJSON(myurl, null, function(data) {
			detailRegionData=data;
			 var productName='';
			 var j=0;
			 for (var key in data) {
				 j++;
				 if(key.length<17){
						break;
				 }
				 if(key.indexOf('regionActiveUserData')==0){
					 productName=key.substring(20);
					 regionActiveUserData[productName]=[];
				 }
				 if(key.indexOf('regionNewUserData')==0){
					 productName=key.substring(17);
					 regionNewUserData[productName]=[];
				 }
				 var obj=data[key];
				 for(i=0;i<obj.length;i++)
				 {
					 var pieObj = {};
					 if(obj[i].region==''){
						 pieObj.name="unknow";
					 }else{
						 pieObj.name=obj[i].region;}
					 pieObj.sliced = false;
					 pieObj.y = obj[i].percentage;
					 pieObj.selected = false;
					 if(typeof(regionNewUserData[productName])=='undefined'){
						 regionActiveUserData[productName].push(pieObj);
					 }else{
						 regionNewUserData[productName].push(pieObj);
					 }
				 }
			}
			if((j/2)>2){
				document.getElementById("region").style.height='780px';
			}
			changeDetailRegionType("regionactiveuser");
			changeRegionReportType(reportregionType);
			chart_canvas.unblock();
		});
	}
	function changeDetailCountryType(type){
		var change="country";
		var detailcontent='countrydetaildata';
		changeDetailType(type,detailCountryData,detailcontent,change);
	}
	function changeDetailRegionType(type){
		var change="region";
		var detailcontent='regiondetaildata';
		changeDetailType(type,detailRegionData,detailcontent,change);
	}
	function changeCountryReportType(type){
		reportcountryType=type;
		changeReportType(type,'country');
	}
	function changeRegionReportType(type){
		reportregionType=type;
		changeReportType(type,'region');
	}
	function changeDetailType(type,userData,detailid,changeType){
		var detailtitlecontent="<tr><th></th>";
		var j=0;
		var unitlength=Math.round(100/activeUserData.length);
		var productNames=[];
		for (var key in userData) {
			if(key.length<11){
				break;
			}
			if(key.indexOf('a')==0&&type=="activeuser"){
				productName=key.substring(14);
			}else if(key.indexOf('n')==0&&type=="newuser"){
				productName=key.substring(11);
			}else if(key.indexOf('regionActiveUserData')==0&&type=="regionactiveuser"){
				productName=key.substring(20);
			}else if(key.indexOf('regionNewUserData')==0&&type=="regionnewuser"){
				productName=key.substring(17);
			}else{
				continue;
			}
			productNames.push(key);
			detailtitlecontent=detailtitlecontent+"<th colspan='2'>"+productName+"</th>";
		}
		detailtitlecontent=detailtitlecontent+"</tr>";
		var detailcontent="";
		var maxlength=0;
		for(m=0;m<productNames.length;m++){
			if(userData[productNames[m]].length>maxlength){
				maxlength=userData[productNames[m]].length;
			}
		}
		for(i=0;i<maxlength;i++){
			detailcontent=detailcontent+"<tr><td>"+(i+1)+"</td>";
			for(j=0;j<productNames.length;j++){
				var obj=userData[productNames[j]];
				if(i>=obj.length){
					detailcontent=detailcontent+"<td></td>";
					detailcontent=detailcontent+"<td></td>";
				}else{
					if(obj[i][changeType]==''){
						detailcontent=detailcontent+"<td>"+"unknow"+"</td>";
					}else{
						detailcontent=detailcontent+"<td>"+obj[i][changeType]+"</td>";
					}
					detailcontent = detailcontent+"<td>"+obj[i].percentage+"%</td>";
				}
			}
			detailcontent=detailcontent+"</tr>";
		}
		$("#"+detailid+"title").html(detailtitlecontent);
		$("#"+detailid).html(detailcontent);
	}
	
	function changeReportType(type,render)
	{
		var reportType=type;
		var m=0;
		var chartwidth=parseInt($("#country").css("width"))/4;
		var chartcenter=[[chartwidth,310/2],[chartwidth*3,310/2],[chartwidth,310*1.6],[chartwidth*3,310*1.6]];
	    var charttitlecontent=[{left:chartwidth-40,top:'1px'},{left:chartwidth*3-40,top:'1px'},{left:chartwidth-40,top:'325'},{left:chartwidth*3-40,top:'325'}];
		if(reportType == "newuser"){
			userData=newUserData;
			options.title.text = '<?php echo $reportTitle['newUserReport'] ?>';
		}else if(reportType == "activeuser"){
			userData=activeUserData;
			options.title.text = '<?php echo $reportTitle['activeUserReport'] ?>';
		}else if(reportType == "regionactiveuser"){
			userData=regionActiveUserData;
			options.title.text = '<?php echo $reportTitle['regionActiveUserReport'] ?>';
		}else{
			userData=regionNewUserData;
			options.title.text = '<?php echo $reportTitle['regionNewUserReport'] ?>';
		}
		for(var key in userData){
			if(userData[key]==""){
				continue;
			}
			//options.chart.renderTo = "container"+m;
			options.chart.renderTo = render;
			options.series[m]={};
		//	options.title.text=key;
			options.series[m].name=key;
			options.series[m].size=chartwidth*3/4;
			options.series[m].center=chartcenter[m];
			options.series[m].data = userData[key];
			options.labels.items[m]={};
			options.labels.items[m].html=key;
			options.labels.items[m].style=charttitlecontent[m];
			options.subtitle.text = '<?php echo $reportTitle['timePhase'];?>';
			chart = new Highcharts.Chart(options);
			m++;
		}
	}
	window.onresize=function(){
		changeReportType(reportcountryType,"country");
		changeReportType(reportregionType,"region");
	}
</script>