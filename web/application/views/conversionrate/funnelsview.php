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
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_re_funnelModel');?></h3>
		</header>
		<div id="container" class="module_content" style="height: 300px"></div>
	</article>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_re_funnelModel');?></h3>
		</header>
		<div class="tab_container">
			<div class="tab_content" id="tab1" style="display: block;">
				<table cellspacing="0" class="tablesorter">
					<thead>
						<tr>
							<th width="16%" class="header"><?php echo lang('v_rpt_re_funnelName');?></th>
							<th width="21%" class="header"><?php echo lang('v_rpt_re_funnelStartevent');?></th>
							<th width="21%" class="header"><?php echo lang('v_rpt_re_funnelTargetevent')?></th>
							<th width="21%" class="header"><?php echo lang('v_rpt_re_funnelConversionrate');?></th>
							<th width="21%" class="header"><?php echo lang('t_details');?></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (isset ( $result ) && ! empty ( $result )) {
						for($i = 0; $i < count ( $result ['tid'] ); $i ++) {
							?>
						<tr>
							<td><?php echo $result ['targetname'] [$i]?></td>
							<td><?php echo $result ['event1'] [$i]?></td>
							<td><?php echo $result ['event2'] [$i]?></td>
							<td><?php echo round((($result['event2_c'][$i])/($result['event1_c'][$i]))*100,2)?>%</td>
							<td><a
								href="<?php echo site_url()?>/conversionrate/funnels/viewDetail/<?php echo $result['tid'][$i]?>">
									<img style="border: 0px" title="View"
									src="<?php echo base_url()?>/assets/images/icn_search.png">
							</a></td>
						</tr>
						<?php
						}
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</article>
</section>

<script type="text/javascript">
var options ;
var myurl='<?php echo site_url()?>/conversionrate/funnels/getChartData';
$(document).ready(function() {
	options = {
            chart: {
                renderTo: 'container',
                type:'line'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ' '
            },
            xAxis: {
            	categories:'',
            	labels: {
                    rotation: -45,
                    align: 'right',
                    style: {
                        fontSize: '11px',
                        fontFamily: ' sans-serif'
                    }
                }
            	//,title:{text:"version"}
            },
            yAxis: {
                title: { text:'<?php echo lang('v_rpt_re_funneleventC');?>'},
                min:0
            },
            plotOptions: {
                column: {
                    pointPadding: 0.3,
                    borderWidth: 0
                }
            },
            tooltip: {
                formatter: function() {
                    return '<?php echo lang('v_rpt_re_funnelTarget');?>:'+this.series.name +'<br>'+'<?php echo lang("v_rpt_re_funneleventC");?>'+':'+this.y;
                }
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
        	
        	
    	    for(var j=0;j<data.length;j++)
    	    {
    	    	options.series[j] = {};
        	    options.series[j].data = data[j].eventnum;
        	    options.series[j].name = data[j].targetname;

        	    var num_array = [];
        	    var time_array =[];

        	    var temp_item = data[j];
        	    
        	    for(var k=0;k<temp_item.eventnum.length;k++)
        	    {
            	    if(data[j].eventnum[k]!=null)
            	    {
        	    	num_array.push(parseInt(data[j].eventnum[k],10));
        	    	time_array.push(data[j].eventtime[k]);
            	    }
            	    
            	}
            	options.series[j].data = num_array;
    	    }
    	    if(time_array!=null)
			{
				options.xAxis.categories = time_array; 
			    options.xAxis.labels.step = parseInt(time_array.length/10);
			    
			}
			options.title.text = '<?php echo lang('v_rpt_re_funnelTargettrend');?>';
			options.subtitle.text = '<?php echo $reportTitle['timePhase'];?>';
    	    chart = new Highcharts.Chart(options);
    		chart_canvas.unblock();
    		});  
    } 
</script>