<section id="main" class="column">
		
		<h4 class="alert_info" id="msg"  style="display: none"></h4> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('v_man_ev_productCustomEvent') ?></h3>
		<ul class="tabs">
   			<li><a href="#tab1" onclick="refreshEvent()"><?php echo lang('v_man_ev_productEventList') ?></a></li>
    		  <li><a href="#tab2"><?php echo lang('v_man_ev_addCustomEvent') ?></a></li>
		</ul>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
				    <th><?php echo lang('v_rpt_el_eventID') ?></th> 				    
    				<th><?php echo lang('v_rpt_el_eventName') ?></th>     				     				
    			
    				<th><?php echo lang('v_man_ev_editCustomEvent') ?></th>
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
    								    					
    					<td>
    					<?php echo anchor('/manage/event/editEvent/'.$row->eventid, lang('g_edit'));?>
    					<?php if ($row->active==1) 
    					{ 
    						echo anchor('/manage/event/stopEvent/'.$row->eventid,lang('g_stop'));
    					}
    					else 
			 	        { 
    						echo anchor('/manage/event/startEvent/'.$row->eventid,lang('g_start'));
    					}?>
    					<a href="javascript:if(confirm('<?php echo lang('v_man_ev_resetEventPrompt') ?>'))location='<?php echo site_url();?>/manage/event/resetEvent/<?php echo $row->eventid?>'"><?php echo lang('g_reset') ?></a>
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
							<label><?php echo lang('v_rpt_el_eventID') ?></label>
							<input type="text" id='eventid'>
						</fieldset>
						<fieldset>
							<label><?php echo lang('v_rpt_el_eventName') ?></label>
							<input type="text" id='eventname'>
						</fieldset>
						<input id="addButton" type="button" value="<?php echo lang('v_man_ev_addEvent') ?>" class="alt_btn" onClick='addEvent()'>
				</div>
			
				
			
		<!-- end of post new article -->
			
		

			</div><!-- end of #tab2 -->
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
	<script type="text/javascript">
var isAddEvent="false";

function refreshEvent(){
	//window.location.reload();
	if(isAddEvent){
	}	
}

function addEvent() {	
	eventid = trim(document.getElementById('eventid').value);
	eventname = trim(document.getElementById('eventname').value);
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
	if(eventid=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_rpt_el_entryEventID') ?></font>';
		document.getElementById('msg').style.display="block";
		return;

	}
	for (var i = 0; i < eventid.length; i++) {
		if(pattern.test(eventid.substr(i, 1))){
			document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_man_ev_errorInputEI') ?></font>';
			document.getElementById('msg').style.display="block";
			return;
			}
	}
	if(eventname=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_rpt_el_entryEventName') ?></font>';
		document.getElementById('msg').style.display="block";
		return;

	}
	for (var i = 0; i < eventname.length; i++) {
		if(pattern.test(eventname.substr(i, 1))){
			document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_man_ev_errorInputEN') ?></font>';
			document.getElementById('msg').style.display="block";
			return;
			}
	}
	document.getElementById('addButton').disabled=true;
	var data = {
			eventid : eventid,
			eventname : eventname
			
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/manage/event/addEvent",
					data : data,
					success : function(msg) {
					    if(!msg){
					    	document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_rpt_el_eventIDExists') ?></font>";
							document.getElementById('msg').style.display="block";
							document.getElementById('addButton').disabled=false;
						}
					    else{
					    	document.getElementById('msg').innerHTML = "<?php echo lang('v_rpt_el_addEventS') ?>";
					    	document.getElementById('msg').style.display="block";
					    	window.location="<?php echo site_url()?>/manage/event";	
						}		 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo lang('t_error') ?>");
						document.getElementById('addButton').disabled=false;
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo lang('v_rpt_el_waitAdd') ?>';
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
	
