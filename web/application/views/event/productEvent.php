<section id="main" class="column">
		
		<h4 class="alert_info" id="msg"><?php echo lang('productevent_alertinfo') ?></h4> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('productevent_headertitle') ?></h3>
		<ul class="tabs">
   			<li><a href="#tab1"><?php echo lang('productevent_eventlist') ?></a></li>
    		  <li><a href="#tab2"><?php echo lang('productevent_defineevent') ?></a></li>
		</ul>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
				    <th><?php echo lang('productevent_theadeventid') ?></th> 				    
    				<th><?php echo lang('productevent_theadeventname') ?></th>     				     				
    				<th><?php echo lang('productevent_theadmessagenum') ?></th>
    				<th><?php echo lang('productevent_theadeditdefine') ?></th>
    				<th></th>
    				
				</tr> 
			</thead> 
			<tbody> 
			
			 <?php if(isset($eventList)):
			 	foreach($eventList->result() as $row)
			 	{
			 ?>
				<tr> 
				    <td><?php echo $row->event_identifier;?></td> 
    				<td><?php echo $row->eventName;?></td> 
    				<td><?php if (isset($row->eventnum)) {
    					echo $row->eventnum;
    				}
    				else {echo '0';}?></td>     				    					
    					<td>
    					<?php echo anchor('/event/editEvent/'.$row->eventid, lang('productevent_tbodyedit'));?>
    					<?php if ($row->active==1) 
    					{ 
    						echo anchor('/event/stopEvent/'.$row->eventid,lang('productevent_tbodystop'));
    					}
    					else 
			 	        { 
    						echo anchor('/event/startEvent/'.$row->eventid,lang('productevent_tbodystart'));
    					}?>
    					<a href="javascript:if(confirm('<?php echo lang('productevent_resetjavainfo') ?>'))location='<?php echo site_url();?>/event/resetEvent/<?php echo $row->eventid?>'"><?php echo lang('productevent_tbodyreset') ?></a>
    				</td> 
    					</td> 				 
				</tr> 
			<?php } endif;?>									
			
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->		  	
			<div id="tab2" class="tab_content">								
				<div class="module_content">
						<fieldset>
							<label><?php echo lang('productevent_eventidlabel') ?></label>
							<input type="text" id='eventid'>
						</fieldset>
						<fieldset>
							<label><?php echo lang('productevent_eventname') ?></label>
							<input type="text" id='eventname'>
						</fieldset>
						<input type="button" value="<?php echo lang('productevent_eventbtn') ?>" class="alt_btn" onClick='addEvent()'>
				</div>
			
				
			
		<!-- end of post new article -->
			
		

			</div><!-- end of #tab2 -->
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
	<script type="text/javascript">

function addEvent() {	
	eventid = document.getElementById('eventid').value;
	eventname = document.getElementById('eventname').value;
	if(eventid=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo lang('productevent_jseventid') ?>';
		return;

	}
	if(eventname=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo lang('productevent_jseventname') ?>';
		return;

	}
	var data = {
			eventid : eventid,
			eventname : eventname
			
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/event/addEvent",
					data : data,
					success : function(msg) {
						document.getElementById('msg').innerHTML = "<?php echo lang('productevent_jquerysuccessmsg') ?>";
						window.location="<?php echo site_url()?>/event";				 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo lang('productevent_jqueryerrormsg') ?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo lang('productevent_jquerywaitmsg') ?>';

					},
					complete : function() {
					}
				});
}
</script>
	
