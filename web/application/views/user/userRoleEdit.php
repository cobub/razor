<section id="main" class="column">
		
		<h4 class="alert_info" id="msg"><?php echo  lang('userroleedit_alertinfo')?></h4> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('userroleedit_headerinfo')?></h3>
		
		</header>

		<div class="tab_container">
				<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo  lang('userroleedit_nameth')?></th> 
    				<th><?php echo  lang('userroleedit_emailth')?></th> 
    				<th><?php echo  lang('userroleedit_roleth')?></th>     				
    				<th><?php echo  lang('userroleedit_changeroleth')?></th>
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
		document.getElementById('msg').innerHTML = '<?php echo  lang('userroleedit_jsnamemsg')?>';
		return;

	}
	if(description=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo  lang('userroleedit_jsdescrpmsg')?>';
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
						document.getElementById('msg').innerHTML = "<?php echo  lang('userroleedit_jquerysmsg')?>";						 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo  lang('userroleedit_jqueryerromsg')?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo  lang('userroleedit_jquerywaitmsg')?>';

					},
					complete : function() {
					}
				});
}
</script>
	
