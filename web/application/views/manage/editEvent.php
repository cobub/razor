<section id="main" class="column">
	<h4 class="alert_info" id="msg" style="display: none;"></h4>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php 
                                        /**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */
                                        echo lang('v_man_ev_modifyEvent') ?></h3>

		</header>

		<div class="tab_container">
			<div class="module_content">
				<fieldset>
					<label><?php echo lang('v_rpt_el_eventID') ?></label> <input
						type="text" id='eventId'
						value='<?php if(isset($eventlist)) echo $eventlist['event_identifier']?>'>
				</fieldset>
				<fieldset>
					<label><?php echo lang('v_rpt_el_eventName') ?></label> <input
						type="text" id='eventName'
						value='<?php  if(isset($eventlist)) echo $eventlist['event_name']?>'>
				</fieldset>
				<input type="button" value="<?php echo lang('g_update') ?>"
					class="alt_btn"
					onClick='modifyevent(<?php  if(isset($eventlist)) echo $eventlist['event_id']?>)'>
			</div>



			<!-- end of post new article -->
		</div>
		<!-- end of .tab_container -->

	</article>
	<!-- end of content manager article -->



	<div class="clear"></div>
	<div class="spacer"></div>
</section>

<script type="text/javascript">

function modifyevent(id) {	
	eventId = trim(document.getElementById('eventId').value);
	eventName = trim(document.getElementById('eventName').value);
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
	if(eventId=='')
	{
		document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_rpt_el_entryEventID') ?></font>";
		document.getElementById('msg').style.display="block";
		return;

	}
	for (var i = 0; i < eventId.length; i++) {
		if(pattern.test(eventId.substr(i, 1))){
			document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_man_ev_errorInputEI') ?></font>";
			document.getElementById('msg').style.display="block";
			return;
			}
	}
	if(eventName=='')
	{
		document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_rpt_el_entryEventName') ?></font>";
		document.getElementById('msg').style.display="block";
		return;

	}
	for (var i = 0; i < eventName.length; i++) {
		if(pattern.test(eventName.substr(i, 1))){
			document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_man_ev_errorInputEN') ?></font>";
			document.getElementById('msg').style.display="block";
			return;
			}
	}
	var data = {
			id:id,
			eventId : eventId,
			eventName : eventName
			
		};
		jQuery.ajax({
					type : "post",
					url : "<?php echo site_url()?>/manage/event/modifyEvent",
					data : data,
					success : function(msg) {
						if(!msg){
							document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_rpt_el_eventIDNameExists') ?></font>";
							document.getElementById('msg').style.display="block";
						}else{
						document.getElementById('msg').innerHTML = "<?php echo lang('v_rpt_el_modifyEventS') ?>";	
						document.getElementById('msg').style.display="block";}			 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
					    document.getElementById('msg').innerHTML = " ";
                        document.getElementById("msg").style.display="none";
						alert("<?php echo lang('t_error') ?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = "<?php echo lang('v_rpt_el_waitModifyE') ?>";
						document.getElementById('msg').style.display="block";

					},
					complete : function() {
					}
				});
}
function trim(str){
    return  (str.replace(/(^\s*)|(\s*$)/g,''));
 }
</script>

