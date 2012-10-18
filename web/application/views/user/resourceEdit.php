<section id="main" class="column">
		
		<h4 class="alert_info" id="msg" style="display:none;"></h4> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('v_user_appM_modifyResource')?></h3>
		
		</header>

		<div class="tab_container">
			
				<div class="module_content">
						<fieldset>
							<label><?php echo  lang('v_user_resm_resourceN')?></label>
							<input type="text" id='resource' value='<?php if(isset($resourceinfo)) echo $resourceinfo->name?>'>
						</fieldset>
						<fieldset>
							<label><?php echo  lang('v_user_resm_resourceD')?></label>
							<input type="text" id='description'  value='<?php if(isset($resourceinfo)) echo  $resourceinfo->description?>'>
						</fieldset>
						<input type="button" value="<?php echo  lang('g_update')?>" class="alt_btn" onClick='modifyResource(<?php if(isset($resourceinfo))  echo $resourceinfo->id?>)'>
				</div>
			
				
			
		<!-- end of post new article -->
			
		

			
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		
		
		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
	<script type="text/javascript">

function modifyResource(id) {	
	resource = trim(document.getElementById('resource').value);
	description =trim(document.getElementById('description').value);
	if(resource=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo  lang('v_user_resm_enterResource')?></font>';
		document.getElementById('msg').style.display="block";
		
		return;

	}
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
	for (var i = 0; i < resource.length; i++) {
		if(pattern.test(resource.substr(i, 1))){
			document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_user_resm_errorInput')?></font>';
			document.getElementById('msg').style.display="block";
			return;
			}
	}
	if(description=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo  lang('v_user_resm_addResourceD')?></font>';
		document.getElementById('msg').style.display="block";
		return;

	}
	var data = {
			id:id,
			name : resource,
			description : description
			
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo base_url()?>/index.php/user/modifyresource",
					data : data,
					success : function(msg) {
						if(!msg){
							document.getElementById('msg').innerHTML = "<font color=red><?php echo  lang('v_user_resm_existResources')?></font>";
							document.getElementById('msg').style.display="block";
						}else{
						document.getElementById('msg').innerHTML = "<?php echo  lang('v_user_resm_modifyResourceS')?>";	
						document.getElementById('msg').style.display="block";}				 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo  lang('t_error')?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo  lang('v_user_resm_waitMofify')?>';
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
	
