<section id="main" class="column">
		
 		<h4 class="alert_info" id='msg'><?php echo  lang('allview_manageresource')?></h4> 
		
	
		
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('resource_headertilte')?></h3>
		<ul class="tabs">
   			<li><a href="#tab1"><?php echo  lang('resource_resourcelist')?></a></li>
    		<li><a href="#tab2"><?php echo  lang('resource_addresource')?></a></li>
		</ul>
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo  lang('resource_namethead')?></th> 
    				<th><?php echo  lang('resource_descriptionthead')?></th>   				
    				<th><?php echo  lang('resource_editthead')?></th>
				</tr> 
			</thead> 
			<tbody> 
			 <?php if(isset($resourcelist)):
			 	foreach($resourcelist->result() as $row)
			 	{
			 ?>
				<tr> 
    				<td><?php echo $row->name;?></td> 
    				<td><?php echo $row->description;?></td>     				  				
    				<td>
    				<a href="<?php echo site_url().'/user/editResource/'.$row->id?>"><?php echo  lang('resource_tbodyedit')?></a>
    				</td> 
				</tr> 
			<?php } endif;?>
			
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
			<div id="tab2" class="tab_content">
			<div class="module_content">
						<fieldset>
							<label><?php echo  lang('resource_namelabe')?></label>
							<input type="text" id='name'>
						</fieldset>
						<fieldset>
							<label><?php echo  lang('resource_descriplal')?></label>
							<input type="text" id='description'>
						</fieldset>
						<input type="button" value="<?php echo  lang('resource_addbtn')?>" class="alt_btn" onClick='addResource()'>
				</div>

			</div><!-- end of #tab2 -->
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		
		
		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
	<script type="text/javascript">

function addResource() {	
	resourceName = document.getElementById('name').value;
	description = document.getElementById('description').value;
	if(resourceName=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo  lang('resource_jsnamemsg')?>';
		return;

	}
	if(description=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo  lang('resource_jsdescrpmsg')?>';
		return;

	}
	var data = {
			resourceName : resourceName,
			description : description
			
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/user/addResource",
					data : data,
					success : function(msg) {
						document.getElementById('msg').innerHTML = "<?php echo  lang('resource_jquerysmsg')?>";						 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo  lang('resource_jqueryerromsg')?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo  lang('resource_jquerywaitmsg')?>';

					},
					complete : function() {
					}
				});
}
</script>
	
	
