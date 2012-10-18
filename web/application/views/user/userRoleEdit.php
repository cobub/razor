<section id="main" class="column">
		
		<h4 class="alert_info" id="msg"><?php echo  lang('v_user_rolem_editRole')?></h4> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('v_user_rolem_changeRole')?></h3>
		
		</header>

		<div class="tab_container">
				<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo  lang('l_username')?></th> 
    				<th><?php echo  lang('l_re_email')?></th> 
    				<th><?php echo  lang('v_user_userRole')?></th>     				
    				<th><?php echo  lang('v_user_rolem_changeRole')?></th>
				</tr> 
			</thead> 
			<tbody> 
			 
				<tr> 
    				<td><?php echo $userinfo->username;?></td> 
    				<td><?php echo $userinfo->email;?></td> 
    				<td><?php echo 'admin'?></td> 
    				<td></td> 
				</tr> 
			
			
			</tbody> 
			</table>
		
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
		document.getElementById('msg').innerHTML = '<?php echo  lang('v_user_resm_enterResource')?>';
		return;

	}
	if(description=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo  lang('v_user_resm_addResourceD')?>';
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
						document.getElementById('msg').innerHTML = "<?php echo  lang('v_user_resm_modifyResourceS')?>";						 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo  lang('t_error')?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo  lang('v_user_resm_waitMofify')?>';

					},
					complete : function() {
					}
				});
}
</script>
	
