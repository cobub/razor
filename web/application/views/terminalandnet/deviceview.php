<section id="main" class="column" style="height:1100px">
<div style="height:480px;">
		<iframe src="<?php echo site_url() ?>/report/device/adddevicetypereport"  frameborder="0" scrolling="no"style="width:100%;height:100%;"></iframe>		
</div>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo  lang('v_rpt_de_details')?></h3>
			<span class="relative r"> <a
				href="<?php echo site_url()?>/report/device/export"
				class="bottun4 hover"><font><?php echo  lang('g_exportToCSV')?></font></a>
			</span>
		</header>

		<table class="tablesorter" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo  lang('v_rpt_de_type')?></th>
					<th><?php echo  lang('g_percent')?></th>
				</tr>
			</thead>
			<tbody id="devicepageinfo">
			<?php $num = count($deviceDetails->result());?>
	    	
		</tbody>
	</table>

		<footer>
			<div id="pagination" class="submit_link"></div>
		</footer>	
	</article>
</section>

<script type="text/javascript">
$(document).ready(function() {
	initPagination();
	pageselectCallback(0,null);
});
var device = eval(<?php echo "'".json_encode($deviceDetails->result())."'"?>);

function pageselectCallback(page_index, jq){
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";   
	var index = page_index*<?php echo PAGE_NUMS?>;
	var pagenum = <?php echo PAGE_NUMS?>;	
	var msg = "";
	
	for(i=0;i<pagenum && (index+i)<device.length ;i++)
	{ 
		msg = msg+"<tr><td>";
		msg = msg + device[i+index].devicebrand_name;
		msg = msg + "</td><td>";
		msg = msg + (device[i+index].percentage*100).toFixed(1)+"%";
		msg = msg + "</td></tr>";
	}
	
   //document.getElementById('devicepageinfo').innerHTML = msg;
	   $('#devicepageinfo').html(msg);				
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