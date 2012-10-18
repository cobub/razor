<section id="main" class="column" style="height: 1100px">
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('v_rpt_op_top10')?></h3>
			<ul class="tabs2">
				<li><a ct="activeuser"
					href="javascript:changeReportType('activeuser')"><?php echo  lang('t_activeUsers')?></a></li>
				<li><a ct="newuser" href="javascript:changeReportType('newuser')"><?php echo  lang('t_newUsers')?></a></li>
			</ul>
		</header>
		<div id="container" class="module_content" style="height: 400px"></div>
	</article>

	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('v_rpt_op_details')?></h3>
			<span class="relative r"> <a
				href="<?php echo site_url()?>/report/operator/export"
				class="bottun4 hover"><font><?php echo  lang('g_exportToCSV')?></font></a>
			</span>
		</header>
		<table class="tablesorter" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo  lang('v_rpt_op_carrier')?></th>
					<th><?php echo  lang('g_percent')?></th>
				</tr>
			</thead>
			<tbody id="detailInfo">
				<div id='out'>
		    			<?php
									$num = count ( $details->result () );
									$array = $details->result ();
									if (count ( $array ) < PAGE_NUMS) {
										$nums = count ( $array );
									} else {
										$nums = PAGE_NUMS;
									}
									?>
		    	</div>
			</tbody>

		</table>
		<footer>
			<div id="pagination" class="submit_link"></div>
		</footer>
	</article>
</section>

<script type="text/javascript">
var chart;
var options;
var newUserData = [];
var activeUserData = [];
var reportTitle = '<?php echo $reportTitle['activeUserReport'] ?>';

$(document).ready(function() {
	options = {
	        chart: {
	            renderTo: 'container'
		 	        },
	        title: {
	            text: '   '
	        },
	        subtitle:{text:''},
	        tooltip: {
	        	formatter: function () {  
                    return '<b>' + this.point.name + '</b>: ' + Highcharts.numberFormat(this.percentage, 1) + ' %';  
                } 
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: 0,
                y: 40,
                floating: false,
                borderWidth: 1,
                backgroundColor: '#FFFFFF'
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
                            return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,1) +' %';
                        }
                    },
            showInLegend: true
                }
            },
	        series: [{	            
	          	type:'pie'
	        }]
	    };
    
	var osDataURL  = "<?php echo site_url();?>/report/operator/getOperatorData";
	renderCharts(osDataURL);
	initPagination();
	pageselectCallback(0,null);
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

		var obj = data.activeUserData;
		for(i=0;i<obj.length;i++)
		{
			var pieObj = {};
			pieObj.name = obj[i].devicesupplier_name;
			pieObj.sliced = false;
			pieObj.y = obj[i].percentage;
			pieObj.selected = false;
			activeUserData.push(pieObj);
		}

		var objNewUserData = data.newUserData;
		for(i=0;i<objNewUserData.length;i++)
		{
			var pieObj = {};
			pieObj.name = objNewUserData[i].devicesupplier_name;
			pieObj.sliced = false;
			pieObj.y = objNewUserData[i].percentage;
			pieObj.selected = false;
			newUserData.push(pieObj);
		}
		
		options.series[0].data = activeUserData;
		options.title.text = reportTitle;
		options.subtitle.text = '<?php echo $reportTitle['timePhase'];?>';
		chart = new Highcharts.Chart(options);
		
		chart_canvas.unblock();
		});  
}


function changeReportType(reportType)
{
	if(reportType == "activeuser")
	{
		options.series[0].data = activeUserData;
		options.title.text = '<?php echo $reportTitle['activeUserReport'] ?>';
		chart = new Highcharts.Chart(options);
	}

	if(reportType == "newuser")
	{
		options.series[0].data = newUserData;
		options.title.text = '<?php echo $reportTitle['newUserReport'] ?>';
		chart = new Highcharts.Chart(options);
	}
}

$(".tab_content").hide();
$("ul.tabs2 li:first").addClass("active").show(); 
$(".tab_content:first").show();

$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active");
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});

var detailObj = eval(<?php echo "'".json_encode($details->result())."'"?>);

function pageselectCallback(page_index, jq){
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";   
	var index = page_index*<?php echo PAGE_NUMS?>;
	var pagenum = <?php echo PAGE_NUMS?>;	
	var msg = "";
	
	for(i=0;i<pagenum && (index+i)<detailObj.length ;i++)
	{ 
		msg = msg+"<tr><td>";
		msg = msg + detailObj[i+index].devicesupplier_name;
		msg = msg + "</td><td>";
		msg = msg + (detailObj[i+index].percentage*100).toFixed(1)+"%";
		msg = msg + "</td></tr>";
	}
	
   document.getElementById('detailInfo').innerHTML = msg;				
   return false;
}

/** 
 * Callback function for the AJAX content loader.
 */
function initPagination() {
   var num_entries = <?php if(isset($num)) echo $num; ?>/<?php echo PAGE_NUMS;?>;
    // Create pagination element
    $("#pagination").pagination(num_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo  lang('g_previousPage')?>',
        next_text: '<?php echo  lang('g_nextPage')?>',           
        num_display_entries: 4,
        callback: pageselectCallback,
        items_per_page:1
    });
 }
</script>

