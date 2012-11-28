<section id="main" class="column" style="height:1600px">
	<article class="module width_full">
		<header>
			<h3 class="h3_fontstyle"><?php echo  lang('v_rpt_de_top10')?></h3>
			<ul class="tabs2">
				<li><a href="javascript:changeReportType('activeuser')"><?php echo  lang('t_activeUsers')?></a></li>
				<li><a href="javascript:changeReportType('newuser')"><?php echo  lang('t_newUsers')?></a></li>
			</ul>

		</header>
		<div id="devicetype" style="height:500px">
		</div>
	</article>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_de_top10') ?></h3>
			<ul class="tabs3">
				<li><a href="javascript:changeDetailType('activeuser')"><?php echo  lang('t_activeUsers')?></a></li>
				<li><a href="javascript:changeDetailType('newuser')"><?php echo  lang('t_newUsers')?></a></li>
			</ul>
			<span class="relative r"> <a
				href="<?php echo site_url().'/report/device/exportCSV'?>"
				class="bottun4 hover"><font><?php echo lang('g_exportToCSV') ?></font></a>
			</span>
		</header>
		<table id="devicetable" class="tablesorter" cellspacing="0">
		    <thead id="devicetitle"></thead>
			<tbody id="devicedetaildata"></tbody>
		</table>
	</article>
</section>

<script type="text/javascript">

//Init tab selector of report
$(".tab_content").hide(); 
$("ul.tabs2 li:first").addClass("active").show(); 
$("ul.tabs3 li:first").addClass("active").show(); 
$(".tab_content:first").show();

//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); 
	$(this).addClass("active");
	var activeTab = $(this).find("a").attr("id");
	$(activeTab).fadeIn(); 
	return true;
});
$("ul.tabs3 li").click(function() {
	$("ul.tabs3 li").removeClass("active"); 
	$(this).addClass("active");
	var activeTab = $(this).find("a").attr("id");
	$(activeTab).fadeIn(); 
	return true;
});


var chart;
var options;
var reportType="activeuser";
var detailData='';
var newUserData = new Array(new Array(),new Array());
var activeUserData = new Array(new Array(),new Array());

$(document).ready(function() {
	options = {
	        chart: {
	        	type:'pie'
		 	        },
	        title: {
	            text: ''
		    },
	        subtitle:{
	            text: ''
	        },
	        tooltip: {
	        	formatter: function () {  
                    return '<b>' + this.series.name+'<br>'+this.point.name + '</b>: ' + Highcharts.numberFormat(this.percentage, 1) + ' %';  
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
                            return '<b>'+this.point.name +'</b>:<br> '+ Highcharts.numberFormat(this.percentage,1) +' %';
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
    
	var deviceURL  = "<?php echo site_url();?>/report/device/getDeviceReportData";
	renderCharts(deviceURL);	
});

function renderCharts(myurl)
{	
	 var chart_canvas = $('#devicetype');
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
		    detailData=data;
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
					if(obj[i].devicebrand_name==''){
						pieObj.name="unknow";
					}else{
						pieObj.name=obj[i].devicebrand_name;}
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
				 document.getElementById("devicetype").style.height='850px';
			}
			changeDetailType("activeuser");
			changeReportType(reportType);
			chart_canvas.unblock();
		});  
}

function changeDetailType(type){
	var detailtitlecontent="<tr><th></th>";
	var j=0;
	var unitlength=Math.round(100/activeUserData.length);
	var productNames=[];
	for (var key in detailData) {
		if(key.length<11){
			break;
		}
		if(key.indexOf('a')==0&&type=="activeuser"){
			productName=key.substring(14);
		}else if(key.indexOf('n')==0&&type=="newuser"){
			productName=key.substring(11);
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
		if(detailData[productNames[m]].length>maxlength){
			maxlength=detailData[productNames[m]].length;
		}
	}
	for(i=0;i<maxlength;i++){
		detailcontent=detailcontent+"<tr><td>"+(i+1)+"</td>";
		for(j=0;j<productNames.length;j++){
			var obj=detailData[productNames[j]];
			if(i>=obj.length){
				detailcontent=detailcontent+"<td></td>";
				detailcontent=detailcontent+"<td></td>";
			}else{
				if(obj[i].devicebrand_name==''){
					detailcontent=detailcontent+"<td>"+"unknow"+"</td>";
				}else{
					detailcontent=detailcontent+"<td>"+obj[i].devicebrand_name+"</td>";
				}
				detailcontent = detailcontent+"<td>"+obj[i].percentage+"%</td>";
			}
		}
		detailcontent=detailcontent+"</tr>";
	}
	$("#devicetitle").html(detailtitlecontent);
	$("#devicedetaildata").html(detailcontent);
}

function changeReportType(type)
{
	reportType=type;
	var m=0;
	var chartwidth=parseInt($("#devicetype").css("width"))/4;
	var chartcenter=[[chartwidth,365/2],[chartwidth*3,365/2],[chartwidth,365*1.5],[chartwidth*3,365*1.5]];
    var charttitlecontent=[{left:chartwidth-40,top:'15px'},{left:chartwidth*3-40,top:'15px'},{left:chartwidth-40,top:'370'},{left:chartwidth*3-40,top:'370'}];
	if(reportType == "newuser"){
		userData=newUserData;
		options.title.text = '<?php echo $reportTitle['newUserReport'] ?>';
	}else{
		userData=activeUserData;
		options.title.text = '<?php echo $reportTitle['activeUserReport'] ?>';
	}
	for(var key in userData){
		if(userData[key]==""){
			continue;
		}
		//options.chart.renderTo = "container"+m;
		options.chart.renderTo = "devicetype";
		options.series[m]={};
	//	options.title.text=key;
		options.series[m].name=key;
		options.series[m].size=chartwidth*5/6;
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
	changeReportType(reportType);
}
</script>