<?php
$appname = array(
	'name'	=> 'appname',
	'id'	=> 'appname',
	'value'	=> set_value('appname'),
	'maxlength'	=> 80,
	'size'	=> 30,
);

$description = array(
		'name'	=> 'description',
		'id'	=> 'description',
		'value'	=> set_value('description'),
		'rows'	=> 10,
);
?>
<?php   $this->load->Model('common');
		$this->load->model('channelmodel','channel');  ?>

<?php echo form_open('product/saveApp'); ?>
<section id="main" class="column">
		<h4 class="alert_info" id='msg'><?php echo lang('createproduct_alertinfo') ?></h4>
		
<article class="module width_full">
	<header><h3><?php echo lang('createproduct_headertitle') ?></h3></header>
	<div class="module_content">
		<fieldset>
			<label><?php echo lang('createproduct_appnamelbl') ?></label><?php echo form_error('appname'); ?>
			<?php echo form_input($appname); ?> 
		</fieldset>
		
		<fieldset >
			<label><?php echo lang('createproduct_apptypelbl') ?></label><?php echo form_error('category'); ?>
			<select name='category' id='category'>
			<option value="" selected="selected"><?php echo lang('createproduct_selectplat') ?></option>
			<?php if(isset($category)):?>
			<?php foreach($category->result() as $row) {?>
					<option value=<?php echo $row->id; ?>><?php echo $row->name;?></option>
			<?php } endif;?>
			</select>
		</fieldset>		
		<fieldset style="width:48%; float:left; margin-right: 3%;">
			<label><?php echo lang('createproduct_appplatformlbl') ?></label><?php echo form_error('platform'); ?>
			<select name='platform' id='platform' onchange="changeplatform(this.value)">
			<option value="" selected="selected"><?php echo lang('createproduct_selectplat') ?></option>
		<?php if(isset($platform)):?>
			<?php foreach($platform as $rel) {?>
					<option value=<?php echo $rel['id']; ?>><?php echo $rel['name'];?></option>
			<?php } endif;?>		
			</select>
		</fieldset>	
		<fieldset style="width:48%; float:left;">
			<label><?php echo lang('createproduct_appchannellbl') ?></label><?php echo form_error('channel'); ?>
			<select name='channel' id='channel'>
			<option value="" selected="selected"><?php echo lang('createproduct_selectplat') ?></option>									
			</select>
		</fieldset>
		<fieldset>
			<label><?php echo lang('createproduct_descriptionlbl') ?></label><?php echo form_error('description'); ?>
			<?php echo form_textarea($description); ?> 
		</fieldset>
		<div class="clear"></div>
	</div>
	<footer>
		<div class="submit_link">
		<input type='submit' id='submit' class='alt_btn' name="product/saveApp"  value="<?php echo lang('createproduct_appbtn') ?>">
		</div>
	</footer>
</article>
</section>
<?php echo form_close(); ?>
<script  type="text/javascript">
    function clearSel(selectname){
    
	  while(selectname.childNodes.length>0){
		  selectname.removeChild(selectname.childNodes[0]);
	  }
    }    
	function changeplatform(platform)
	{
		if(platform=="")
		{
			var chann=document.getElementById("channel").value;		
			if(chann!="")
			{ 
				clearSel(document.getElementById("channel")); 
				 var value = "";								   							  　
				 var text ="<?php echo lang('createproduct_selectplat') ?>";								    
				 var channel = new Option(text,value);
				 document.getElementById('channel').options.add(channel);
				  return;	
			}
			document.getElementById('msg').innerHTML = '<?php echo lang('createproduct_jscallbackplatform') ?>';
			return;
		}		
		var data = {
				platform:platform
								
			};
			jQuery
					.ajax({
						type : "post",
						url : "<?php echo site_url()?>/product/uploadchannel",
						data : data,
						success : function(msg) {
							document.getElementById('msg').innerHTML = "<?php echo lang('createproduct_jscallbackchannel') ?>";														
							jsonData=eval("("+msg+")");
							if(document.getElementById("channel").value!="")							
							{
								clearSel(document.getElementById("channel")); 
							}							
							 for(i = 0;i<jsonData.length;i++)
						 {	 
								   var value = jsonData[i]['channel_id'];								   							  　
								    var text = jsonData[i]['channel_name'];								    
								    var channel = new Option(text,value);
								    document.getElementById('channel').options.add(channel);							 
							         　
						 }                    
		                   					
																 
						},
						error : function(XmlHttpRequest, textStatus, errorThrown) {
							alert("<?php echo lang('createproduct_jscallbackerror') ?>");
						},
						beforeSend : function() {
							document.getElementById('msg').innerHTML = '<?php echo lang('createproduct_jscallbackwait') ?>';

						},
						complete : function() {
						}
					});
	}
	</script>
