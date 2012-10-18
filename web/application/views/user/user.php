<section id="main" class="column">
		
<h4 class="alert_info" id='msg' style="display: none;"></h4>
		
	
		
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('v_user_userList')?></h3>
		
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo  lang('l_username')?></th> 
    				<th><?php echo  lang('l_re_email')?></th> 
    				<th><?php echo  lang('v_user_userRole')?></th>    				
    				<th><?php echo  lang('v_user_modifyUserRole')?></th>
				</tr> 
			</thead> 
			<tbody> 
			 <?php if(isset($userlist)):
			 	foreach($userlist->result() as $row)
			 	{
			 ?>
				<tr> 
    				<td><?php echo $row->username;?></td> 
    				<td><?php echo $row->email;?></td> 
    				<td><label id='label_<?php echo $row->id?>'><?php echo $row->name?></label></td> 
    				<td>
							
							<select style="width:92%;" <?php if(isset($currentuserid)&&($currentuserid==$row->id)){ echo "disabled=true";} ?> 
							onchange="changeForm(this.value,<?php echo $row->id?>)" id='select_'<?php echo $row->id?> >
							<?php foreach ($roleslist->result() as $row2)
							{
								?>
								<option <?php 
								    if($row2->name==$row->name)
								    echo 'selected'
								?>><?php echo $row2->name?></option>
								<?php 
							}
							
							?>
							
						</fieldset>
				</tr> 
			<?php } endif;?>
			
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
			
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		
		
		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	<script  type="text/javascript">
	function changeForm(rolename,id)
	{
		
		var data = {
				id:id,
				rolename : rolename
				
			};
			jQuery
					.ajax({
						type : "post",
						url : "<?php echo site_url()?>/user/modifyUserRole",
						data : data,
						success : function(msg) {
							document.getElementById('msg').innerHTML = "<?php echo  lang('v_user_rolem_modifyRoleS')?>";	
							document.getElementById('msg').style.display="block";
							document.getElementById('label_'+id).innerHTML = rolename;					 
						},
						error : function(XmlHttpRequest, textStatus, errorThrown) {
							alert("<?php echo  lang('t_error')?>");
						},
						beforeSend : function() {
							document.getElementById('msg').innerHTML = '<?php echo  lang('v_user_rolem_waitModifyR')?>';
							document.getElementById('msg').style.display="block";
						},
						complete : function() {
						}
					});

		}
	</script>
