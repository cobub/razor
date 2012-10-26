<script type="text/javascript">
var version = '<?php echo $current_version?>';
</script>
<section id="main" class="column">

 <article class="module width_full"> <header>
<h3 class="tabs_involved"><?php echo  lang('m_rpt_eventlist')?></h3>

<div class="submit_link">
<select onchange=selectChange(this.value)	id='select'>
	<?php 
	if (isset ( $versions )) {
		
		foreach ( $versions->result() as $row ) {
			$r_version =$row->version_name; 
			$r_version_value = $row->version_name;
			if ($r_version_value=="")
			{
				$r_version = lang('t_unknow');
			    $r_version_value = "unknown";
			}
			
	?>
	<option value=<?php  echo $r_version_value?>><?php echo $r_version?></option>
	<?php }}?>
	<option value='all' selected><?php echo  lang('v_rpt_el_allVersion')?></option>
</select>
</div>
</header>

<div id="tab1" class="tab_content">
<div id="eventlistdata">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo  lang('v_rpt_el_eventID')?></th>
			<th><?php echo  lang('v_rpt_el_eventName')?></th>
			<th><?php echo  lang('v_rpt_el_messages')?></th>
			<th><?php echo  lang('t_details')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody id="eventlistpageinfo">
	   <div id='out'>
	    	<?php
	    	  $num = count($event->result());	
	    	  $array = $event->result ();
	    	  if (count ( $array ) < PAGE_NUMS) {
	    	  	$nums = count ( $array );
	    	  } else {
	    	  	$nums = PAGE_NUMS;
	    	  }
	    	  ?>
	   </div>
	</tbody>
	
</table>
</div>	
</div>
<footer>
 <div id="pagination" class="submit_link"></div></footer>
</article> </section> 

<script type="text/javascript">
var chart_canvas;
//Here must first load
   document.getElementById('select').value=version;
   $(document).ready(function() {

	    chart_canvas = $('#eventlistdata');
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

		 initPagination();
		 pageselectCallback(0,null);
	});

   document.onreadystatechange=eventListDataInit;
   function eventListDataInit()
   {
      if (document.readyState=="complete")
      {
    	  chart_canvas.unblock();
      }
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
           num_display_entries: 8,
           callback: pageselectCallback,
           items_per_page:1
       });
    }
   var eventdata = eval(<?php echo "'".json_encode($event->result())."'"?>);
   
   function pageselectCallback(page_index, jq){
   	page_index = arguments[0] ? arguments[0] : "0";
   	jq = arguments[1] ? arguments[1] : "0";   
   	var index = page_index*<?php echo PAGE_NUMS?>;
   	var pagenum = <?php echo PAGE_NUMS?>;	
   	var msg = "";
   	
   	for(i=0;i<pagenum && (index+i)<eventdata.length;i++)
   	{ 
   		msg = msg+"<tr><td>";
		msg = msg + eventdata[i+index].eventidentifier;
		msg = msg + "</td><td>";
		msg = msg + eventdata[i+index].eventname;
		msg = msg + "</td><td>";
		msg = msg + (eventdata[i+index].count?eventdata[i+index].count:'0');
		msg = msg + "</td><td>";
		msg = msg + "<a href='<?php echo site_url()?>/report/eventlist/getEventDeatil/"
		+eventdata[i+index].event_sk+"/<?php echo $current_version?>/"+
		eventdata[i+index].eventidentifier+"'><?php echo  lang('v_rpt_el_eventStatistics')?></a>";
		msg = msg + "</td></tr>";
   	}
   	
      //document.getElementById('eventlistpageinfo').innerHTML = msg;
    $('#eventlistpageinfo').html(msg);				
      return false;
   }
    
</script> <script type="text/javascript">

//When page loads...
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$("ul.tabs3 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
$("ul.tabs3 li").click(function() {
	$("ul.tabs3 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});


function selectChange(value)
{
    version = value;
    getdata();
           
}


function getdata()
{	 
	window.location = "<?php echo site_url().'/report/eventlist/getEventListData/'?>"+version;
	
}
</script>