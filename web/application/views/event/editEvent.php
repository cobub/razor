<section id="main" class="column">		
		<h4 class="alert_info" id="msg"><?php echo lang('editevent_alertinfo') ?></h4> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('editevent_headertitle') ?></h3>
		
		</header>

		<div class="tab_container">
				<div class="module_content">
						<fieldset>
							<label><?php echo lang('editevent_eventid') ?></label>
							<input type="text" id='eventId' value='<?php if(isset($eventlist)) echo $eventlist['event_identifier']?>'>
						</fieldset>
						<fieldset>
							<label><?php echo lang('editevent_eventname') ?></label>
							<input type="text" id='eventName'  value='<?php  if(isset($eventlist)) echo $eventlist['event_name']?>'>
						</fieldset>
						<input type="button" value="<?php echo lang('editevent_eventbtn') ?>" class="alt_btn" onClick='modifyevent(<?php  if(isset($eventlist)) echo $eventlist['event_id']?>)'>
				</div>
			
				
			
		<!-- end of post new article -->
	</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		
		
		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
	<script type="text/javascript">

function modifyevent(id) {	
	eventId = document.getElementById('eventId').value;
	eventName = document.getElementById('eventName').value;
	if(eventId=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo lang('editevent_jsnamemsg') ?>';
		return;

	}
	if(eventName=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo lang('editevent_jsdescrpmsg') ?>';
		return;

	}
	var data = {
			id:id,
			eventId : eventId,
			eventName : eventName
			
		};
		jQuery.ajax({
					type : "post",
					url : "<?php echo site_url()?>/event/modifyEvent",
					data : data,
					success : function(msg) {
						document.getElementById('msg').innerHTML = "<?php echo lang('editevent_jquerysmsg') ?>";						 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo lang('editevent_jqueryerromsg') ?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo lang('editevent_jquerywaitmsg') ?>';

					},
					complete : function() {
					}
				});
}
</script>
	
