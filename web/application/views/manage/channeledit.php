<section id="main" class="column">
<h4 class="alert_info" id='msg' style="display: none"></h4> 

<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo lang('v_man_pr_editChannel') ?></h3>
</header>
<div class="tab_container">
<div class="module_content">
<fieldset>
	<label><?php echo lang('v_man_au_channelName') ?></label>
	<input type="text" id='channel_name' value="<?php if(isset($edit)) echo $edit['channel_name'] ;?>">
</fieldset>
<fieldset>
	<label><?php echo lang('v_platform') ?></label>
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
<input type="button" value="<?php echo lang('v_man_pr_changeChannel') ?>" class="alt_btn" onClick="editchannel('<?php if(isset($edit)) echo $edit['channel_id'] ; ?>')">
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
	var channel_name = trim(document.getElementById('channel_name').value);
	var platform = trim(document.getElementById('platform').value);
	if(channel_name=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_man_pr_enterChannelN') ?></font>';
		document.getElementById('msg').style.display="block";
		return;
	}
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
	for (var i = 0; i < channel_name.length; i++) {
		if(pattern.test(channel_name.substr(i, 1))){
			document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('t_error') ?></font>';
			document.getElementById('msg').style.display="block";
			return;
			}
	}
	if(platform=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_man_pr_selectPlatform') ?></font>';
		document.getElementById('msg').style.display="block";
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
					url : "<?php echo site_url()?>/manage/channel/modifychannel",
					data : data,
					success : function(msg) {
						if(!msg){
							document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_man_pr_existChannelS') ?></font>";	
							document.getElementById('msg').style.display="block";
						}else{
						document.getElementById('msg').innerHTML = "<?php echo lang('v_man_pr_modifyChannelS') ?>";	
						document.getElementById('msg').style.display="block";}				 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo lang('t_error') ?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo lang('v_man_pr_modifyChannel') ?>';
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

