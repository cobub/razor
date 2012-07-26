<section id="main" class="column">
		
<h4 class="alert_info" id='msg'><?php echo  lang('user_alertinfo')?></h4>
		
	
		
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('user_headertilte')?></h3>
		
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo  lang('user_namethead')?></th> 
    				<th><?php echo  lang('user_emailthead')?></th> 
    				<th><?php echo  lang('user_rolethead')?></th>    				
    				<th><?php echo  lang('user_changerolethead')?></th>
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
							
							<select style="width:92%;" onchange="changeForm(this.value,<?php echo $row->id?>)" id='select_'<?php echo $row->id?> >
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
							document.getElementById('msg').innerHTML = "<?php echo  lang('user_jquerysmsg')?>";	
							document.getElementById('label_'+id).innerHTML = rolename;					 
						},
						error : function(XmlHttpRequest, textStatus, errorThrown) {
							alert("<?php echo  lang('user_jqueryerromsg')?>");
						},
						beforeSend : function() {
							document.getElementById('msg').innerHTML = '<?php echo  lang('user_jquerywaitmsg')?>';

						},
						complete : function() {
						}
					});

		}
	</script>
