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
 */
?>
<section id="main" class="column" style="height: 1250px;">
	<article class="module width_full">
		<header>
			<h3><?php echo  lang('v_rpt_re_customEventF')?></h3>
			<div class="submit_link">
				<select onchange=selectChange(this.value) id='select'>
				<?php	
				if (isset ( $versions )) {
					foreach ( $versions->result () as $row ) {
						$r_version = $row->version_name;
						$r_version_value = $row->version_name;
						if ($r_version_value == "") {
							$r_version = lang ( 't_unknow' );
							$r_version_value = "unknown";
						}
						?>
						<option value=<?php  echo $r_version_value?>><?php echo $r_version?></option>
						<?php }}?>
						<option value='all' selected><?php echo  lang('v_rpt_el_allVersion')?></option>
				</select>
			</div>
		</header>

		<article class="width_full">
			<div id="container" class="module_content" style="height: 300px"></div>
		</article>
	</article>
	<article class="module width_full">
		<header>
			<h3><?php echo  lang('v_rpt_re_eventOverview')?></h3>
		</header>
		<table class="tablesorter" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo lang('v_rpt_el_eventID') ?></th>
					<th><?php echo lang('v_rpt_re_eventNumber') ?></th>
					<th><?php echo lang('v_rpt_re_conversionRate') ?></th>
				</tr>
			</thead>
			<tbody id="eventlistrateinfo">
			</tbody>
		</table>
	</article>
</section>

<script type="text/javascript">
var version='all';
var title='';
var options;
var basicdata;
var lastdata=0;
var averageData=0;
var completionCountData = [];
var myurl = "<?php echo site_url()?>/report/funnels/getViewDetail/<?php echo $targetid ?>";
</script>
<!-- report -->
<script type="text/javascript">
$(document).ready(function() {
	options = {
            chart: {
                renderTo: 'container',
                inverted: true
            },
            title: {
                text: '   '
            },
            subtitle: {
                text: ' '
            },
            xAxis: {
            	categories:''
            	//,title:{text:"version"}
            },
            yAxis: {
                title:{text:''},
                allowDecimals:false,
                min:0
            },
            plotOptions: {
                columnrange: {
                    dataLabels: {
                        enabled: false
                        }
                },
                series: {
                    showInLegend: false
                },
                scatter: {
                    marker: {radius:0},
                    dataLabels: {
                        enabled: true,
                        color:'white',
                        align:'right',
                        y:10,
                        formatter: function() {
                            if((this.y-averageData)>0){
                                return (this.y-averageData)*2;
                                }
                        }
                    }
                },
                arearange: {
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            if((this.y-averageData)>0){
                                if(basicdata>0){
                                    return Highcharts.numberFormat((this.y-averageData)*200/basicdata,1)+"%";
                                }else{
                                    return "--";
                                }
                            }
                        }
                   }
               }
            },
            tooltip: {
        	   formatter: function() {
            	var unit = {
                    'arearange': false,
                    'scatter': false,
                    'columnrange': true
                 }[this.series.type];
                 if(!unit){
                     this.tooltip.enabled=false;
                     return ;
                 }   
               	 datavalue=(this.y-averageData)*2;
           		 if(datavalue<0){
                     datavalue=0-datavalue;}
                 return this.x+":"+datavalue;
             }
            },
           credits: {
                enabled: false
            },
            colors: ['#CCCCCC','#D1D1D1','#4572A7'] ,
            series: [
            {
              type:'arearange',
              data:'',
              dashStyle: 'longdash'
            },
            {
                type:'scatter',
                data:'',
                dashStyle: 'longdash'
            },
            {
               type:'columnrange',
               data: ''
            }
            ]
        };
	var url=myurl+'/'+version;
	renderCharts(url);
	
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
    			var rangeOfData=[];
    			var maxData=[];
    			var maxY=0;
            	if(data==null)
            	{	
            		chart_canvas.unblock();
            		return;
                }
           
            	var detaildata = data.content;
        		if(detaildata==null)
        		{
        			options.series=[];
            		options.title.text = "";
           		    chart = new Highcharts.Chart(options);
        			var msg='';
            		$('#eventlistrateinfo').html(msg);	
            		chart_canvas.unblock();
            		return;
                }
        		var categories = [];
        		var msg='';

        		basicdata=parseInt(detaildata[0].num);
        	    for(var j=0;j<detaildata.length;j++)
        	    {
            	    msg = msg+"<tr><td>";
            	    msg = msg + detaildata[j].eventalias;
            	    msg = msg + "</td><td>";
            	    msg = msg + parseInt(detaildata[j].num);
            	    msg = msg + "</td><td>";
            	    if(basicdata!=0){
                	    msg = msg + Math.round(10*(100*parseInt(detaildata[j].num)/basicdata))/10+"%";}
            	    else{
                	    msg=msg+"--";
            	    }
            	    msg = msg + "</td><tr>";
            	    var nowY=parseInt(detaildata[j].num);
            	    if(maxY<nowY){
                	    maxY=nowY;
            	    }
    		    	categories.push(detaildata[j].eventalias);
        	    }
        	    for(var j=0;j<detaildata.length;j++)
        	    {
        	    	var eventCountData = [];
        	    	var nowY=parseInt(detaildata[j].num);
        	    	var currentMin=(maxY-nowY)/2;
        	    	maxData.push(currentMin+nowY);
        	    	eventCountData.push(currentMin,currentMin+nowY);
        	        rangeOfData.push(eventCountData);
        	    }
        	    averageData=maxY/2;
        	    $('#eventlistrateinfo').html(msg);
          		options.series[0].data = rangeOfData;
          		options.series[1].data =  maxData;
          		options.series[2].data =  rangeOfData;
    			options.xAxis.categories = categories; 
    			options.title.text = '<?php echo $reportTitle['eventCount'] ?>';
    			options.subtitle.text = '<?php echo $reportTitle['timePhase'];?>';
        	    chart = new Highcharts.Chart(options);
        		chart_canvas.unblock();
        		
        		});  
    		
    } 
</script>
<script type="text/javascript">

function selectChange(value)
{
	version = value;
	var url=myurl+'/'+version;
	renderCharts(url);
}

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
</script>