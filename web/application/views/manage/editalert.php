<section id="main" class="column">		
		<h4 class="alert_info" id="msg" style="display:none;"></h4> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('v_man_ev_modifyAlertlab'); ?></h3>
		
		</header>

		<div class="tab_container">
				<div class="module_content">
						<fieldset>
							<label><?php echo lang('v_rpt_el_alertlab'); ?></label>
							<select id="myselect" >
							<?php 
								$id = $alertlist['label'];
								if($id=="t_newUser"){
									echo "<option value='t_newUser' selected>".  lang('t_newUser') ."</option>";
								}else{
									echo "<option value='t_newUser' >".lang('t_newUser') ."</option>";
								}
								if($id=="t_activeUser"){
									echo "<option value='t_activeUser' selected>". lang('t_activeUser') ."</option>";
								}else{
									echo "<option value='t_activeUser' >". lang('t_activeUser')."</option>";
								}
								if($id=="t_sessions"){
									echo "<option value='t_sessions' selected>". lang('t_sessions') ."</option>";
								}else{
									echo "<option value='t_sessions' >". lang('t_sessions') ."</option>";
								}
								if($id=="t_accumulatedUsers"){
									echo "<option value='t_accumulatedUsers' selected>". lang('t_accumulatedUsers') ."</option>";
								}else{
									echo "<option value='t_accumulatedUsers' >". lang('t_accumulatedUsers')."</option>";
								}
								if($id=="t_averageUsageDuration"){
									echo "<option value='t_averageUsageDuration' selected>". lang('t_averageUsageDuration') ."</option>";
								}else{
									echo "<option value='t_averageUsageDuration' >". lang('t_averageUsageDuration') ."</option>";
								}
							
							
							?>
							
							</select>
							
						<!--  	<input type="text" id='exceptionlab' value='<?php if(isset($alertlist)) echo $alertlist['label']?>'>-->
						</fieldset>
						<fieldset>
							<label style="width: 10%; "><?php echo lang('v_rpt_el_condition'); ?></label>
							<label style="width: 1%;margin-left: 0 px">+/-</label>
							<input style="width: 5%; margin-left: 5 px" type="text" id='condition'  value='<?php  if(isset($alertlist)) echo $alertlist['condition']?>'>
						    <label style="width: 1%; margin-left: 0 px">%</label>	</fieldset>
						    
						    <fieldset>
							<label style="width: 10%; "><?php echo lang('v_rpt_el_email') ?></label>
							<input style="width: 35%; margin-left: 5 px" type="text" id='emailstr'  value='<?php  if(isset($alertlist)) echo $alertlist['emails']?>'>
							<label><?php echo lang('v_rpt_el_note')?></label>
						</fieldset>
						    
						<input style="width: 10%; " type="button" value="<?php echo lang('v_rpt_el_set'); ?>" class="alt_btn" onClick='resetExceptionlab(<?php  if(isset($alertlist)) echo $alertlist['productid']?>,<?php if(isset($alertlist)) echo $alertlist['condition']?>)'>
				</div>
			
				
			
		<!-- end of post new article -->
	</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		
		
		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
	<script type="text/javascript">
function resetExceptionlab() {
	var select = document.getElementById("myselect");
	var index = select.selectedIndex;
	exceptionlab = select.options[index].value;
	condition = trim(document.getElementById('condition').value);
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
	if(exceptionlab=='')
	{
		document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_rpt_el_enterEventN') ?></font>";
		document.getElementById('msg').style.display="block";
		return;

	}
	for (var i = 0; i < exceptionlab.length; i++) {
		if(pattern.test(exceptionlab.substr(i, 1))){
			document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_man_ev_errorInputEI') ?></font>";
			document.getElementById('msg').style.display="block";
			return;
			}
	}
	if(condition=='')
	{
		document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_rpt_el_enterEventD') ?></font>";
		document.getElementById('msg').style.display="block";
		return;

	}
	
	var data = {
			
			exceptionlab : exceptionlab,
			condition : condition
			
		};
		jQuery.ajax({
					type : "post",
					url : "<?php echo site_url()?>/manage/alert/resetalertlab",
					data : data,
					success : function(msg) {
						document.getElementById('msg').innerHTML = "<?php echo lang('m_modifysuccess') ?>";
						document.getElementById('msg').style.display="block";
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						document.getElementById('msg').innerHTML = errorThrown;	
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
	
