<section id="main" class="column">
	<!-- Country -->
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_re_top10Nations') ?></h3>
			<ul class="tabs2">
				<li><a ct="activeuser"
					href="javascript:changeReportType('activeuser')"><?php echo lang('t_activeUsers') ?></a></li>
				<li><a ct="newuser" href="javascript:changeReportType('newuser')"><?php echo lang('t_newUsers') ?></a></li>
			</ul>
		</header>
		<div id="container" class="module_content" style="height: 400px"></div>
	</article>

	<!--Region -->
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_re_top10Provinces') ?></h3>
			<ul class="tabs3">
				<li><a ct="activeuser"
					href="javascript:changeReportType('regionactiveuser')"><?php echo lang('t_activeUsers') ?></a></li>
				<li><a ct="newuser" href="javascript:changeReportType('regionnewuser')"><?php echo lang('t_newUsers') ?></a></li>
			</ul>
		</header>
		<div id="containerRegion" class="module_content" style="height: 400px"></div>
	</article>
	<div class="clear"></div>
	<div class="spacer"></div>

	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_re_detailsOfNation') ?></h3>
			<!--<div class="submit_link"><a href="<?php echo site_url().'/report/region/exportcountry/'.$from.'/'.$to?>">-->
			<!--<img  src="<?php echo base_url(); ?>assets/images/export.png"/></a></div>-->
			<span class="relative r"> <a
				href="<?php echo site_url().'/report/region/exportcountry/'.$from.'/'.$to?>"
				class="bottun4 hover"><font><?php echo lang('g_exportToCSV') ?></font></a>
			</span>
		</header>
		<table id="countrytable" class="tablesorter" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo lang('v_rpt_re_nation') ?></th>
					<th><?php echo lang('v_rpt_re_count') ?></th>
					<th><?php echo lang('g_percent') ?></th>
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
					<td><?php echo $rel->access; ?></td>
					<td><?php echo 100*$rel->percentage.'%'; ?></td>

				</tr>
		<?php } endif;?>
	</tbody>
		</table>
		<footer>
			<div id="pagination" class="submit_link"></div>
		</footer>
	</article>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_re_detailsOfProvince') ?></h3>
			<!--<div class="submit_link"> <a  href="<?php echo site_url().'/report/region/exportpro/'.$from.'/'.$to; ?>">-->
			<!--<img  src="<?php echo base_url(); ?>assets/images/export.png"/></a></div>-->
			<span class="relative r"> <a
				href="<?php echo site_url().'/report/region/exportpro/'.$from.'/'.$to; ?>"
				class="bottun4 hover"><font><?php echo lang('g_exportToCSV') ?></font></a>
			</span>
		</header>
		<table id="regionTable" class="tablesorter" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo lang('v_rpt_re_province') ?></th>
					<th><?php echo lang('v_rpt_re_count') ?></th>
					<th><?php echo lang('g_percent') ?></th>
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
					<td><?php echo $ret->access?></td>
					<td><?php  echo 100*$ret->percentage.'%'; ?></td>
				</tr>
		<?php   } endif;?>
	</tbody>
		</table>
		<footer>
			<div id="paginationpro" class="submit_link"></div>
		</footer>
	</article>
	<div class="clear"></div>
	<div class="spacer"></div>
</section>

<script type="text/javascript">
var chart;
var chartRegion;
var options;

var countryActiveUserData =[];
var countryActiveUserCategories=[];
var countryActiveUserPercentCategories=[]; 

var countryNewUserData=[];
var countryNewUserCategories=[];
var countryNewUserPercentCategories=[]; 



var regionActiveUserData =[];
var regionActiveUserCategories=[];
var regionActiveUserPercentCategories=[]; 

var regionNewUserData=[];
var regionNewUserCategories=[];
var regionNewUserPercentCategories=[]; 

options = {
		 chart: {
              renderTo: 'container',
              type: 'bar'
          },
          title: {
              text: ''
          },
          subtitle: {
              text: ''
          },
          xAxis: [
          	{
	                categories: [],
	                title: {
	                    text: null
	                },
	                tickmarkPlacement: 'on'
          	},
          	{
      			categories : [],
      			title : {
      				text : null
      			},
      			labels: {
      				formatter: function() {
      					return this.value +'%';
      				}
      			},
      			opposite: true
      		}
          ],
          yAxis: {
              min: 0,
              title: {
                  text: '',
                  align: 'high'
              },
              labels: {
                  overflow: 'justify'
              }
          },
          tooltip: {
              formatter: function() {
                  return ''+
                      this.series.name +': '+ this.y +' ';
              }
          },
          plotOptions: {
          	bar : {
  				pointWidth:20,
  				dataLabels : {
  					enabled : true,
  					formatter : function() {
  						return '' + this.y;
  					}
  				}
  			},
  			scatter : {
  				marker : {
  					enabled : false
  				}
  			}
          },
          legend: {
          	enabled : false
          },
          credits: {
              enabled: false
          },
	        series: [{	 
		        		xAxis:0,           
	          			name:'<?php echo lang('v_rpt_re_count') ?>'
	        		},
			        {
	        			type: 'scatter',
						xAxis:1,
						name:''
					}
	        ]
  };

  
$(document).ready(function() {
	var countryDataURL  = "<?php echo site_url();?>/report/region/getCountryData";
	renderCountryCharts(countryDataURL);
	var regionDataURL  = "<?php echo site_url();?>/report/region/getRegionData";
	renderRegionCharts(regionDataURL)
});

function renderCountryCharts(myurl)
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
				countryActiveUserCategories.push(obj[i].country);
				countryActiveUserData.push(parseInt(obj[i].access));
				countryActiveUserPercentCategories.push(Highcharts.numberFormat(obj[i].percentage*100,1) );
			}
	
			var objNewUserData = data.newUserData;
			for(i=0;i<objNewUserData.length;i++)
			{
				countryNewUserCategories.push(objNewUserData[i].country);
				countryNewUserData.push(parseInt(objNewUserData[i].access));
				countryNewUserPercentCategories.push(Highcharts.numberFormat(objNewUserData[i].percentage*100,1));
			}
			
			changeReportType('activeuser');
			chart_canvas.unblock();
		});  
}


function renderRegionCharts(myurl)
{	
	 var chart_canvas = $('#containerRegion');
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
		      
			var obj = data.regionActiveUserData;
			for(i=0;i<obj.length;i++)
			{
				regionActiveUserCategories.push(obj[i].region);
				regionActiveUserData.push(parseInt(obj[i].access));
				regionActiveUserPercentCategories.push(Highcharts.numberFormat(obj[i].percentage*100,1));
			}
	
			var objNewUserData = data.regionNewUserData;
			for(i=0;i<objNewUserData.length;i++)
			{
				regionNewUserCategories.push(objNewUserData[i].region);
				regionNewUserData.push(parseInt(objNewUserData[i].access));
				regionNewUserPercentCategories.push(Highcharts.numberFormat(objNewUserData[i].percentage*100,1));
			}
			
			changeReportType('regionactiveuser');
			chart_canvas.unblock();
		});  
}

function changeReportType(reportType)
{
	if(reportType == "activeuser")
	{
		options.chart.renderTo = "container";
		options.series[0].data = countryActiveUserData;
		options.series[1].data = countryActiveUserData;
		options.xAxis[0].categories = countryActiveUserCategories;
		options.xAxis[1].categories = countryActiveUserPercentCategories;
		options.title.text = '<?php echo $reportTitle['activeUserReport'] ?>';
		options.subtitle.text = '<?php echo $reportTitle['timePhase'] ?>';
		chart = new Highcharts.Chart(options);
	}

	if(reportType == "newuser")
	{ 
		options.chart.renderTo = "container";
		options.series[0].data = countryNewUserData;
		options.series[1].data = countryNewUserData;
		options.xAxis[0].categories = countryNewUserCategories;
		options.xAxis[1].categories = countryNewUserPercentCategories;
		options.title.text = '<?php echo $reportTitle['newUserReport'] ?>';
		options.subtitle.text = '<?php echo $reportTitle['timePhase'] ?>';
		chart = new Highcharts.Chart(options);
	}

	if(reportType == "regionactiveuser")
	{
		options.chart.renderTo = "containerRegion";
		options.series[0].data = regionActiveUserData;
		options.series[1].data = regionActiveUserData;
		options.xAxis[0].categories = regionActiveUserCategories;
		options.xAxis[1].categories = regionActiveUserPercentCategories;
		options.title.text = '<?php echo $reportTitle['regionActiveUserReport'] ?>';
		options.subtitle.text = '<?php echo $reportTitle['timePhase'] ?>';
		chart = new Highcharts.Chart(options);
	}

	if(reportType == "regionnewuser")
	{ 
		options.chart.renderTo = "containerRegion";
		options.series[0].data = regionNewUserData;
		options.series[1].data = regionNewUserData;
		options.xAxis[0].categories = regionNewUserCategories;
		options.xAxis[1].categories = regionNewUserPercentCategories;
		options.title.text = '<?php echo $reportTitle['regionNewUserReport'] ?>';
		options.subtitle.text = '<?php echo $reportTitle['timePhase'] ?>';
		chart = new Highcharts.Chart(options);
	}
}

$(".tab_content").hide(); 
$("ul.tabs2 li:first").addClass("active").show(); 
$("ul.tabs3 li:first").addClass("active").show(); 
$(".tab_content:first").show(); 

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

</script>
<!--Country active users detail -->
<script type="text/javascript">
function pageselectCallback(page_index, jq){

	var chart_canvas = $('#countryTable');
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
	    
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";   
   var myurl="<?php echo site_url()?>/report/region/activecountrypage/"+page_index;
	jQuery.ajax({
		type : "post",
		url : myurl,
		success : function(msg) {
			document.getElementById('countrydetail').innerHTML = msg;
			chart_canvas.unblock();				
		},
		error : function(XmlHttpRequest, textStatus, errorThrown) {
			alert("<?php echo lang('t_error') ?>");
			chart_canvas.unblock();	
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
        prev_text: '<?php echo lang('g_previousPage') ?>',       //上一页按钮里text 
        next_text: '<?php echo lang('g_nextPage') ?>',       //下一页按钮里text            
        num_display_entries: 4,
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
<!--Region detail users  -->
<script type="text/javascript">
function pageproCallback(page_index, jq){
	var chart_canvas = $('#regionTable');
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
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";	 
   var myurl="<?php echo site_url()?>/report/region/activepropage/"+page_index;
	jQuery.ajax({
		type : "post",
		url : myurl,
		success : function(msg) {
			document.getElementById('prodetail').innerHTML = msg;
			chart_canvas.unblock();				
		},
		error : function(XmlHttpRequest, textStatus, errorThrown) {
			alert("<?php echo lang('t_error') ?>");
			chart_canvas.unblock();
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
        prev_text: '<?php echo lang('g_previousPage') ?>', 
        next_text: '<?php echo lang('g_nextPage') ?>',               
        num_display_entries: 4,
        callback: pageproCallback,
        items_per_page:1
    });
 }
</script>
