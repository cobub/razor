<section id="main" class="column">
<h4 class="alert_info" id='msg'><?php echo lang('channeledit_alertinfo') ?></h4> 

<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo lang('channeledit_headertitle') ?></h3>
</header>
<div class="tab_container">
<div class="module_content">
<fieldset>
	<label><?php echo lang('channeledit_channelname') ?></label>
	<input type="text" id='channel_name' value=<?php if(isset($edit)) echo $edit['channel_name'] ;?>>
</fieldset>
<fieldset>
	<label><?php echo lang('channeledit_platform') ?></label>
  <select  id="platform" >  	
	<?php if(isset($platform))
	foreach ($platform as $row)
	{
	?>
	<option value="<?php echo $row['id'];?>"
				<?php if($row['name']==$edit['name']) echo 'selected';?>>
			<?php echo $row['name'];?>
	</option>		
  <?php 
	}
							
	?>	
						  
  </select>
</fieldset>
<input type="button" value="<?php echo lang('channeledit_editbtn') ?>" class="alt_btn" onClick="editchannel('<?php if(isset($edit)) echo $edit['channel_id'] ; ?>')">
</div>
<!-- end of #tab1 -->
<!-- end of .tab_container -->

</article>
<!-- end of content manager article -->



<div class="clear"></div>
<div class="spacer"></div>
</section>

<script type="text/javascript">

function editchannel(channel_id)
{		
	var channel_name = document.getElementById('channel_name').value;
	var platform = document.getElementById('platform').value;
	if(channel_name=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo lang('channeledit_jsnamemsg') ?>';
		return;

	}
	if(platform=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo lang('channeledit_jsdescrpmsg') ?>';
		return;

	}
	var data = {
			 channel_id : channel_id,
			channel_name :channel_name,
			platform : platform
			
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/channel/modifychannel",
					data : data,
					success : function(msg) {
						document.getElementById('msg').innerHTML = "<?php echo lang('channeledit_jquerysmsg') ?>";						 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo lang('channeledit_jqueryerromsg') ?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo lang('channeledit_jquerywaitmsg') ?>';

					},
					complete : function() {
					}
				});
}
</script>

