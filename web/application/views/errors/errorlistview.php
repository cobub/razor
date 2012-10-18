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
 <article class="module width_full">
  <header>
     <h3 class="tabs_involved">
     
<?php echo lang('v_rpt_err_errorListK') ?>

<?php
    $fixed_num = isset($fixed_error)?count($fixed_error):'0';
    $unfixed_num = isset($unfixed_error)?count($unfixed_error):'0';
    echo $unfixed_num."&nbsp&nbsp".lang('v_rpt_err_urep').", ".$fixed_num."&nbsp&nbsp".lang('v_rpt_err_rep');

?>&nbsp&nbsp<?php echo  lang('v_rpt_err_error')?></h3>
  <div >
   <ul class="tabs3">
	<li><a id="error_fixed" href="javascript:disErrorListByIsFix('0')"><?php echo  lang('v_rpt_err_unfixed')?></a></li>
	<li><a id="error_unfixed" href="javascript:disErrorListByIsFix('1')"><?php echo  lang('v_rpt_err_fixed')?></a></li>
  </ul>
  </div>

</header>
<div id="error_table">
<table class="tablenosorter" cellspacing="0">
	<thead>
		<tr>
			<!-- <th width="5%"><input name="selectall" type="checkbox" id="allsss" value="all"
				onClick="checkall(this)" /></th>-->
			<th width="55%"><?php echo  lang('v_rpt_err_errorSummary')?></th>
			<th width="15%"><?php echo  lang('v_rpt_ve_appVersion')?></th>
			<th width="15%"><?php echo  lang('v_rpt_err_time')?></th>
			<th width="10%"><?php echo  lang('v_rpt_err_num')?></th>
		</tr>
	</thead>
	<tbody id="errorlistdetail">
    </tbody>
</table>
</div>
    <footer>
    <div id="pagination" class="submit_link"></div>		
    </footer> 
</article>
    

</section>
<script type="text/javascript">

var type = 'unfixed';
var unfixed_error = <?php echo json_encode($unfixed_error);?>;
var fixed_error = <?php echo json_encode($fixed_error);?>;
$('.list_box').hide();
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
<!--Update the list of errors in the data according to the selected type-->
<script type="text/javascript">
function disErrorListByIsFix(isfix)
{
	    if(isfix == '0')
		    type = 'unfixed';
	    else
		    type = 'fixed';
	    initPagination();
	    pageselectCallback(0,null);
	    
}
</script>


<!-- get error info by pagnum -->
<script type="text/javascript">

function pageselectCallback(page_index, jq){
	
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";   
	var index = page_index*<?php echo PAGE_NUMS?>;
	var pagenum = <?php echo PAGE_NUMS?>;	
	var msg = '';
	var data;
	if (type=="fixed")
		data = fixed_error;
	else
		data = unfixed_error;
	for(i=0;i<pagenum && (index+i)<data.length ;i++)
	{ 
		msg += '<tr><td>';
		//msg = msg+'<input name="select" type="checkbox" value='+data[i+index].title_sk +'/></td><td>';
		msg += '<a href=<?php echo site_url();?>/report/errorlog/detailstacktrace/'+data[i+index].title_sk+'/'+data[i+index].version_name+'>';
		msg = msg + data[i+index].title;
		msg += '</a>';
		msg = msg + "</td><td>";
		msg = msg + data[i+index].version_name;
		msg = msg + "</td><td>";
		msg = msg + data[i+index].time;
		msg = msg + "</td><td>";
		msg = msg + data[i+index].errorcount;
		msg = msg + "</td></tr>";
	}
   $("#errorlistdetail").html(msg);			
   return false;
}
</script>
<script type="text/javascript">
function initPagination() {
	
	var num_entries;
	if(type=='fixed')
	{
      num_entries = <?php if(isset($fixed_num)){echo $fixed_num;} else{echo 90;} ?>/<?php echo PAGE_NUMS;?>;

	} else
	{
	  num_entries = <?php if(isset($unfixed_num)){echo $unfixed_num;} else{echo 90;} ?>/<?php echo PAGE_NUMS;?>;
    }

	//alert(num_entries);
	
    // Create pagination element
    $("#pagination").pagination(num_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo  lang('g_previousPage')?>',       //Previous button in the text
        next_text: '<?php echo  lang('g_nextPage')?>',       //Next button in the text           
        num_display_entries: 4,
        callback: pageselectCallback,
        items_per_page:1
    });
 }


// When the HTML has loaded, call initPagination to paginate the elements        
$(document).ready(function(){      
	initPagination();
	
	pageselectCallback(0,null);
});
</script>
<!-- Select or Unselect -->
<script type="text/javascript">
function checkall(cb)
{
	 var cba=document.getElementsByTagName("input");
	 for(var i = 0 ; i < cba.length ; i++)
	{
	
	
		if (cb.checked == true)
	    {
			if(cba[i].type == "checkbox")	
		    {	
				
			    cba[i].checked = cb.checked;
			}
			
	    }
	    else
	    {
	    	if(cba[i].type == "checkbox")
			{
				cba[i].checked = cb.checked;
			}
	    }
		
	
	}

}
</script>