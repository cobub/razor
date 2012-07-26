<section id="main" class="column">
		
		<h4 class="alert_info" id="msg"><?php echo  lang('resourceeditedit_alertinfo')?></h4> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('resourceeditedit_headertilte')?></h3>
		
		</header>

		<div class="tab_container">
			
		  	
			
			
				
			
				<div class="module_content">
						<fieldset>
							<label><?php echo  lang('resourceeditedit_namelabe')?></label>
							<input type="text" id='resource' value='<?php if(isset($resourceinfo)) echo $resourceinfo->name?>'>
						</fieldset>
						<fieldset>
							<label><?php echo  lang('resourceeditedit_descriplal')?></label>
							<input type="text" id='description'  value='<?php if(isset($resourceinfo)) echo  $resourceinfo->description?>'>
						</fieldset>
						<input type="button" value="<?php echo  lang('resourceeditedit_addbtn')?>" class="alt_btn" onClick='modifyResource(<?php if(isset($resourceinfo))  echo $resourceinfo->id?>)'>
				</div>
			
				
			
		<!-- end of post new article -->
			
		

			
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		
		
		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
	<script type="text/javascript">

function modifyResource(id) {	
	resource = document.getElementById('resource').value;
	description = document.getElementById('description').value;
	if(resource=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo  lang('resourceeditedit_jsnamemsg')?>';
		return;

	}
	if(description=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo  lang('resourceeditedit_jsdescrpmsg')?>';
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
						document.getElementById('msg').innerHTML = "<?php echo  lang('esourceeditedit_jquerysmsg')?>";						 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo  lang('resourceeditedit_jqueryerromsg')?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo  lang('resourceeditedit_jquerywaitmsg')?>';

					},
					complete : function() {
					}
				});
}
</script>
	
