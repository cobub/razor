<section id="main" class="column">
		
		<h4 class="alert_info" id="msg"><?php echo  lang('allview_managerole')?></h4> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('allview_managerole')?></h3>
		<ul class="tabs">
   			<li><a href="#tab1"><?php echo  lang('roles_list')?></a></li>
    		  <li><a href="#tab2"><?php echo  lang('roles_addrole')?></a></li>
		</ul>
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
				    <th><?php echo  lang('roles_id')?></th> 
    				<th><?php echo  lang('roles_name')?></th> 
    				<th><?php echo  lang('roles_description')?></th>     				
    				<th><?php echo  lang('roles_right')?></th>
    				
				</tr> 
			</thead> 
			<tbody> 
			 <?php if(isset($rolelist)):
			 	foreach($rolelist->result() as $row)
			 	{
			 ?>
				<tr> 
				    <td><?php echo $row->id;?></td> 
    				<td><?php echo $row->name;?></td> 
    				<td><?php echo $row->description;?></td> 
    				<td><?php echo anchor('/user/roleManageDetail/'.$row->id.'/'.$row->name, lang('roles_tbodyright'));?>
    				
    				</td>
    				
    				 
				</tr> 
			<?php } endif;?>
			
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
		  	
			<div id="tab2" class="tab_content">
			
				
			
				<div class="module_content">
						<fieldset>
							<label><?php echo  lang('roles_namelabe')?></label>
							<input type="text" id='role'>
						</fieldset>
						<fieldset>
							<label><?php echo  lang('roles_descriplal')?></label>
							<input type="text" id='description'>
						</fieldset>
						<input type="button" value="<?php echo  lang('roles_addbtn')?>" class="alt_btn" onClick='addRole()'>
				</div>
			
				
			
		<!-- end of post new article -->
			
		

			</div><!-- end of #tab2 -->
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		
		
		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
	<script type="text/javascript">

function addRole() {	
	role = document.getElementById('role').value;
	description = document.getElementById('description').value;
	if(role=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo  lang('roles_jsnamemsg')?>';
		return;

	}
	if(description=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo  lang('roles_jsdescrpmsg')?>';
		return;

	}
	var data = {
			role : role,
			description : description
			
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo base_url()?>/index.php/user/addRole",
					data : data,
					success : function(msg) {
						document.getElementById('msg').innerHTML = "<?php echo  lang('roles_jquerysmsg')?>";						 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo  lang('roles_jqueryerromsg')?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo  lang('roles_jquerywaitmsg')?>';

					},
					complete : function() {
					}
				});
}
</script>
	
