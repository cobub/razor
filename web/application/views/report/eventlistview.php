<script type="text/javascript">
var version = '<?php echo $current_version?>';
</script>
<section id="main" class="column">

<h4 class="alert_success" id='msg'><?php echo  lang('eventlistview_alertinfo')?>

</h4>
 <article class="module width_full"> <header>
<h3 class="tabs_involved"><?php echo  lang('eventlistview_headeinfo')?></h3>

<div class="submit_link" style="position:absolute; right:45px;  padding: 5px 10px 0;"><select onchange=selectChange(this.value)
	id='select'>
	<?php 
	if (isset ( $versions )) {
		
		foreach ( $versions->result() as $row ) {
			$r_version =$row->version_name; 
			$r_version_value = $row->version_name;
			if ($r_version_value=="")
			{
				$r_version = lang('eventlistview_unkownversion');
			    $r_version_value = "unknown";
			}
			
	?>
	<option value=<?php  echo $r_version_value?>><?php echo $r_version?></option>
	<?php }}?>
	<option value='all' selected><?php echo  lang('eventlistview_allversion')?></option>
</select>
</div>
<span class="relative r">
			<a href="#this" class="bottun4" onclick="sever('server','server1c');"><font>?</font></a>
                	<div class="server333" id="server" style="width:620px;">
                       <div class="ser_title">
                           <b class="l"><?php echo  lang('eventlistview_divsetttitle')?></b>                          
                           <a class="r" href="#this" id="server1c"><img src="<?php echo base_url(); ?>assets/images/server_close.gif" /></a>
                       </div>
                       
                                <style>
								.ser_txt font{
									width:135px
								}
								</style>
                       <div class="ser_txt">
                           <dl>
                               <dt>
                               	<font><?php echo  lang('eventlistview_remindereventid')?></font><small><?php echo  lang('eventlistview_remindersame')?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo  lang('eventlistview_remindername')?></font><small><?php echo  lang('eventlistview_remindermanage')?></small>
                                <div class="clear"></div>
                               </dd>
                               <dt>
                               	<font><?php echo  lang('eventlistview_remindermessagenum')?></font><small><?php echo  lang('eventlistview_reminderoccurnum')?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo  lang('eventlistview_remindereventsta')?></font><small><?php echo  lang('eventlistview_remindereventtouch')?></small>
                                <div class="clear"></div>
                               </dd>
                           </dl>
                       </div>
                	</div>
                </span>	

</header>

<div id="tab1" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo  lang('eventlistview_eventidthead')?></th>
			<th><?php echo  lang('eventlistview_eventnamethead')?></th>
			<th><?php echo  lang('eventlistview_messagenumthead')?></th>
			<th><?php echo  lang('eventlistview_detailthead')?></th>
			<th></th>
		</tr>
	</thead>
	<tbody>
	    <?php
	    if (isset($event)) 
	    {
	        foreach ($event->result() as $row)
	        {
	    ?>
		<tr>
			<td><?php echo $row->eventidentifier?></td>
			<td><?php echo $row->eventname?></td>
			<td><?php echo isset($row->count)?$row->count:'0' ?></td>
			<td><a href='<?php echo site_url()?>/report/eventlist/getEventDeatil/<?php echo $row->event_sk?>/<?php echo $current_version?>/<?php echo $row->eventidentifier?>'><?php echo  lang('eventlistview_statitbody')?></a></td>
		</tr>
		<?php 
	        } }?>
	</tbody>
</table>
</div>
</article> </section> 

<script type="text/javascript">
//这里必须最先加载
   document.getElementById('select').value=version;
    
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
<script type="text/javascript">

function sever(txt, colse) {
	$("#" + txt).slideToggle("slow");
	$("#" + colse).click(function() {
		$("#" + txt).slideUp("slow");
	});
	
}

</script>
