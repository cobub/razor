<section id="main" class="column">
		
 		<h4 class="alert_info" id='msg'><?php echo  lang('allview_applicationtype')?></h4> 
		
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('allview_applicationtype')?></h3>
		<ul class="tabs">
   			<li><a href="#tab1"><?php echo  lang('type_applicationlist')?></a></li>
    		<li><a href="#tab2"><?php echo  lang('add_type_application')?></a></li>
		</ul>
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo  lang('type_applicationthead_id')?></th> 
    				<th><?php echo  lang('type_applicationthead_name')?></th>   				
    				<th><?php echo  lang('type_applicationthead_edit')?></th>
				</tr> 
			</thead> 
			<tbody> 
			 <?php if(isset($productcategorylist)):
			 	foreach($productcategorylist->result() as $row)
			 	{
			 ?>
				<tr> 
    				<td><?php echo $row->id;?></td>                
    				<td><?php echo $row->name;?></td>     				  				
    				<td><a  href="<?php echo site_url();?>/user/edittypeOfapplication/<?php echo $row->id; ?>/<?php echo urlencode($row->name); ?>">
    				<input type="image" src="<?php echo base_url();?>assets/images/icn_edit.png" title="Edit"></a>
    				<a href="javascript:if(confirm('<?php echo lang('type_application_delete') ?>'))location='<?php echo site_url();?>/user/deletetypeOfapplication/<?php echo $row->id; ?>'">
    				<input type="image" src="<?php echo base_url();?>assets/images/icn_trash.png" title="Trash"></a>
    				</td>  
				</tr> 
			<?php } endif;?>
			
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
			<div id="tab2" class="tab_content">
			<div class="module_content">
						<fieldset>
							<label><?php echo  lang('type_applicationthead_name')?></label>
							<input type="text" id='name'>
						</fieldset>
						<input type="button" value="<?php echo  lang('add_tyapplication')?>" class="alt_btn" onClick='addtypeOfapplica()'>
				</div>

			</div><!-- end of #tab2 -->
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		
		
		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
<script type="text/javascript">

function addtypeOfapplica() {	
	type_applicationName = document.getElementById('name').value;
	if(type_applicationName=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo  lang('type_application_jsnamemsg')?>';
		return;

	}
	var data = {
			type_applicationName : type_applicationName,
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/user/addtypeOfapplication",
					data : data,
					success : function(msg) {
						document.getElementById('msg').innerHTML = "<?php echo  lang('type_application_jquerysmsg')?>";						 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo  lang('type_application_jqueryerromsg')?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo  lang('type_application_jquerywaitmsg')?>';

					},
					complete : function() {
					}
				});
}
</script>
	
	
