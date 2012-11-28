<?php 
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */?>
<section class="column" id="main" style="height:1100px">
<article class="module width_full">
  <header>
  <h3 class="h3_fontstyle"><?php echo  lang('m_rpt_errors')?></h3>
  <ul class="tabs2">
	<li><a id="errornum11" href="javascript:changetypename('errorNumber')"><?php echo  lang('v_rpt_err_errorNums')?></a></li>
	<li><a id="errorstartnum11" href="javascript:changetypename('errorAndStart')"><?php echo  lang('v_rpt_err_errorNumsInSessions')?></a></li>
  </ul>
  </header>
  
 <article class="">
	     <div id="container"  class="module_content" style="height:300px"></div>
 </article>
</article>

	<article class="module width_full">
		<header>		
		<h3 class="h3_fontstyle"><?php echo  lang('m_rpt_errors')?></h3>
		<span class="relative r"> <a class="bottun4 hover" href="<?php echo site_url()?>/report/errorlog/exportComparedata"><font>导出CSV</font></a>
 		</span>
		</header>
		<table class="tablesorter" cellspacing="0">
		   <thead id="detailtitle"></thead>
		   <tbody id='content'> </tbody>    
		</table>
		<footer>
			<div id="errorpage" class="submit_link"></div>
		</footer>
	</article>
</section>
<script type="text/javascript">
//Init tab selector of report
$(".tab_content").hide(); 
$("ul.tabs2 li:first").addClass("active").show(); 
$(".tab_content:first").show();

//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); 
	$(this).addClass("active");
	var activeTab = $(this).find("a").attr("id");
	$(activeTab).fadeIn(); 
	return true;
});

var title='';
var errorCountData = [];
var errorCountPerSessionData = [];
var category;
var detaildata;
var maxlength=0;
var myurl = "<?php echo site_url()?>/report/errorlog/compareErrorDetail/";
</script>
<!-- report -->
<script type="text/javascript">
var options;
$(document).ready(function() {
	options = {
            chart: {
                renderTo: 'container',
                type:'line'
            },
            title: {
                text: '   '
            },
            subtitle: {
                text: ' '
            },
            xAxis: {
            	categories:[],
            	labels: {
                    rotation: -45,
                    align: 'right',
                    style: {
                        fontSize: '11px',
                        fontFamily: ' sans-serif'
                    }
                }
            },
            yAxis:{
				title:' ',
				min:0
                },
            tooltip: {
            	crosshairs: true,
                shared: true
            },
            legend: {
                enabled:true
            },
            credits: {
    	        enabled: false
    	    },
            series: [
            ]
        };
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
    		category=[];
    		detaildata=data;
    		for (var key in detaildata) {
    			maxlength=detaildata[key].content.length;
    			break;
    		}
    		initPagination();
    		changeDetailtype(0);
    		changetypename("errorNumber");
    		chart_canvas.unblock();
    		});  
    } 
</script>
<script type="text/javascript">
function changeDetailtype(pageindex){
	var pre=pageindex*10;
	var next=(pageindex+1)*10;
	if(maxlength-next<0){
		next=maxlength;
	}
	var detailtitlecontent="<tr><th></th>";
	var detailcontent="<tr><td><?php echo  lang('g_date')?></td>";
	var productNames=[];
	for (var key in detaildata) {
		productNames.push(key);
		detailtitlecontent=detailtitlecontent+"<th colspan='2'>"+key+"</th>";
		detailcontent=detailcontent+"<td><?php echo  lang('v_rpt_err_errorNums')?></td>";
		detailcontent=detailcontent+"<td><?php echo  lang('v_rpt_err_errorNumsInSessions')?></td>";
	}
	detailtitlecontent=detailtitlecontent+"</tr>";
	detailcontent=detailcontent+"</tr>";
	for(i=pre;i<next;i++){
		for(j=0;j<productNames.length;j++){
			var obj=detaildata[productNames[j]].content;
			if(j==0){
				detailcontent=detailcontent+"<tr><td>"+obj[i].date+"</td>";
			}
			detailcontent=detailcontent+"<td>"+obj[i].count+"</td>";
			detailcontent = detailcontent+"<td>"+obj[i].percentage+"</td>";
		}
		detailcontent=detailcontent+"</tr>";
	}
	$('#detailtitle').html(detailtitlecontent);
	$('#content').html(detailcontent);
  }
function changetypename(name)
{
	if(name == "errorNumber")
	{
		changeChartData("errorNumber");
		options.yAxis.allowDecimals=false; 
		options.title.text = '<?php echo $reportTitle['errorCount'] ?>';
	}
	if(name == "errorAndStart")
	{
		changeChartData("errorAndStart");
		options.yAxis.allowDecimals=true; 
		options.title.text = '<?php echo $reportTitle['errorCountPerSession'] ?>';
	}
	options.subtitle.text = '<?php echo $reportTitle['timePhase'];?>';
	chart = new Highcharts.Chart(options);
}

function changeChartData($flag){
	var j=0;
	for (var key in detaildata) {
		var obj=detaildata[key].content;
		var categories=[];
		var chartData=[];
		for(i=0;i<obj.length;i++)
		{
			categories.push(obj[i].date);
			if($flag=="errorNumber"){
				chartData.push(parseInt(obj[i].count));
			}else{
				chartData.push(parseFloat(obj[i].percentage));
			}
		}
		options.series[j]={};
		options.series[j].data = chartData;
		options.series[j].name=key;
		options.xAxis.categories = categories;
		options.xAxis.labels.step = parseInt(categories.length/10);
		j++;
	}
}
/** 
 * Callback function for the AJAX content loader.
 */
function initPagination() {
    var num_entries = maxlength/<?php echo PAGE_NUMS;?>;
    // Create pagination element
    $("#errorpage").pagination(num_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo lang('g_previousPage') ?>',       //上一页按钮里text 
        next_text: '<?php echo lang('g_nextPage') ?>',       //下一页按钮里text            
        num_display_entries: 4,
        callback: pageselectCallback,
        items_per_page:1
    });
 }
 function pageselectCallback(page_index, jq){
	 changeDetailtype(page_index);
	 return false;
 }
</script>

