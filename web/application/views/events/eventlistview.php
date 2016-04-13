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

<label><?php echo lang('v_rpt_el_eventIDName') ?></label>
<input type="text" id='eventname' >
<input type="button" id='searchbtn' value="<?php echo  lang('g_search')?>" class="alt_btn" onclick="searchEvent()">
<input type="button" id='exportbtn' value="<?php echo  lang('g_exportToCSV')?>" class="alt_btn" onclick="exportEvent()">
 
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
var eventdata;
var version="all";
//Here must first load
   document.getElementById('select').value=version;
   $(document).ready(function() {
		    var myurl="<?php echo site_url().'/report/eventlist/getEventListData/'?>"+version;
		    rederUserData(myurl);
		
	});
   function rederUserData(myurl)
   { 

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
	    jQuery.getJSON(myurl, null, function(data) { 
		    
	    	   var event=data.event;
			   eventdata=eval(event); 
		       initPagination();
			   pageselectCallback(0,null); 

			});    
	  
   }

   /** 
    * Callback function for the AJAX content loader.
    */
   function initPagination() {	   
      var num_entries = (eventdata.length)/<?php echo PAGE_NUMS;?>;
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
		+eventdata[i+index].event_sk+"/"+version+"/"+eventdata[i+index].eventname+"/"+
		eventdata[i+index].eventidentifier+"'><?php echo  lang('v_rpt_el_eventStatistics')?></a>";
		msg = msg + "</td></tr>";
   	}
   	
      //document.getElementById('eventlistpageinfo').innerHTML = msg;
    $('#eventlistpageinfo').html(msg);	
    chart_canvas.unblock();			
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
    document.getElementById('eventname').value = '';
    myurl= "<?php echo site_url().'/report/eventlist/getEventListData/'?>"+version;
    rederUserData(myurl);
           
}
function searchEvent()
{
    eventname = trim(document.getElementById('eventname').value);
    value = document.getElementById('select').value;
    myurl= "<?php echo site_url().'/report/eventlist/getSearchEventData/'?>"+value+'/'+eventname;
    rederUserData(myurl);
           
}

function exportEvent()
{
    eventname = trim(document.getElementById('eventname').value);
    value = document.getElementById('select').value;
    window.location.href = "<?php echo site_url().'/report/eventlist/exportEvent/'?>"+value+'/'+eventname;;
}


function trim(str) {
        return  (str.replace(/(^\s*)|(\s*$)/g, ''));
}
</script>