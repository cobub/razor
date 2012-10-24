<?php
$appname = array (
		'name' => 'appname',
		'id' => 'appname',
		'value' => set_value ( 'appname' ),
		'maxlength' => 80,
		'size' => 30 
);

$description = array (
		'name' => 'description',
		'id' => 'description',
		'value' => set_value ( 'description', isset ( $description ) ? $description : '' ),
		'rows' => 10,
		'cols' => 40 
);
?>
<?php echo form_open('manage/product/saveApp'); ?>
<section id="main" class="column">
	<h4 class="alert_info" id='msg' style="display: none"></h4>

	<article class="module width_full">
		<header>
			<h3><?php echo lang('v_man_pr_createApp') ?></h3>
		</header>
		<div class="module_content">
			<fieldset>
				<label><?php echo lang('v_man_pr_name') ?></label><?php echo form_error('appname'); ?>
			<?php echo form_input($appname); ?> 
		</fieldset>

			<fieldset>
				<label><?php echo lang('v_man_pr_appType') ?></label><?php echo form_error('category'); ?>
			<select name='category' id='category'>
					<option value="" Selected><?php echo lang('v_man_pr_pleaseSelect') ?></option>
			<?php if(isset($category)):?>
			<?php foreach($category->result() as $row) {?>
					<option value="<?php echo $row->id; ?>"
						<?php if(isset($selectcategory)&&$selectcategory== $row->id){echo "Selected";} ?>><?php echo $row->name;?></option>
			<?php } endif;?>
			</select>
			</fieldset>
			<fieldset style="width: 48%; float: left; margin-right: 3%;">
				<label><?php echo lang('v_platform') ?></label><?php echo form_error('platform'); ?>
			<select name='platform' id='platform'
					onchange="changeplatform(this.value)">
					<option value="" Selected><?php echo lang('v_man_pr_pleaseSelect') ?></option>
		<?php if(isset($platform)):?>
			<?php foreach($platform as $rel) {?>
					<option value="<?php echo $rel['id']; ?>"
						<?php if(isset($selectplatform)&&$selectplatform== $rel['id']){echo "Selected";}?>><?php echo $rel['name'];?></option>
			<?php } endif;?>		
			</select>
			</fieldset>
			<fieldset style="width: 48%; float: left;">
				<label><?php echo lang('v_man_pr_channelType') ?></label><?php echo form_error('channel'); ?>
			<select name='channel' id='channel'>
					<option value="" Selected><?php echo lang('v_man_pr_pleaseSelect') ?></option>
				</select>
			</fieldset>
			<div class="clear"></div>
			<fieldset>
				<label><?php echo lang('v_man_pr_description') ?></label><?php echo form_error('description'); ?>
			<?php echo form_textarea($description); ?> 
		</fieldset>
		</div>
		<footer>
			<div class="submit_link">
				<input type='submit' id='submit' class='alt_btn'
					name="product/saveApp"
					value="<?php echo lang('v_man_pr_submit') ?>">
			</div>
		</footer>
	</article>
</section>
<?php echo form_close(); ?>
<script type="text/javascript">
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
			var selectTag = document.getElementById("channel"); 
			var colls = selectTag.options;						
			if((chann!="") || (colls.length>0))
			{ 
				clearSel(document.getElementById("channel")); 
				 var value = "";								   							  　
				 var text ="<?php echo lang('v_man_pr_pleaseSelect') ?>";								    
				 var channel = new Option(text,value);
				 document.getElementById('channel').options.add(channel);
				  return;	
			}			
			document.getElementById('msg').innerHTML = '<?php echo lang('v_man_pr_selectPlatform') ?>';
			document.getElementById('msg').style.display="block";
			return;
		}		
		var data = {
				platform:platform
								
			};
			jQuery
					.ajax({
						type : "post",
						url : "<?php echo site_url()?>/manage/product/uploadchannel",
						data : data,
						success : function(msg) {							
							document.getElementById('msg').innerHTML = "<?php echo lang('v_man_pr_selectChannel') ?>";														
							document.getElementById('msg').style.display="block";
							jsonData=eval("("+msg+")");
							var selectTag = document.getElementById("channel"); 
							var colls = selectTag.options;															
							if((colls.length>0) || (document.getElementById("channel").value!=""))							
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
							alert("<?php echo lang('t_error') ?>");
						},
						beforeSend : function() {							
							document.getElementById('msg').innerHTML = '<?php echo lang('v_rpt_ve_waitLoad') ?>';
							document.getElementById('msg').style.display="block";
						},
						complete : function() {
						}
					});
	}
var platformvalue="<?php if(isset($selectplatform)&&$selectchannel!="") {echo $selectplatform; } else{echo "";} ?>";
var channelvalue="<?php if(isset($selectchannel)&&$selectchannel!="") {echo $selectchannel; } else{echo "";} ?>";
if(platformvalue!="")
{
	if(channelvalue!="")
	{
		var data = {
				platform:platformvalue
								
			};
			jQuery
					.ajax({
						type : "post",
						url : "<?php echo site_url()?>/manage/product/uploadchannel",
						data : data,
						success : function(msg) {						
							jsonData=eval("("+msg+")");
							var selectTag = document.getElementById("channel"); 
							var colls = selectTag.options;	
							if((document.getElementById("channel").value!="") || (colls.length>0))							
							{
								clearSel(document.getElementById("channel")); 
							}							
							 for(i = 0;i<jsonData.length;i++)
						    {								  
								   var value = jsonData[i]['channel_id'];								   							  　
								   var text = jsonData[i]['channel_name'];								   
								    if(channelvalue==value)
								    {								    	
								    	var selected=true;	
								        var channel = new Option(text,value,selected);							    	
									}
								    else
									{
								    	var channel = new Option(text,value);
									}								    
								    document.getElementById('channel').options.add(channel);							 
							         　
						 }  									 
						}
					});
	}
}

  
	</script>
