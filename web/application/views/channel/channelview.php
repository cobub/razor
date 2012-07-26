<section id="main" class="column">
		
		<h4 class="alert_info" id="msg"><?php echo lang('channel_alertinfo') ?></h4>
		<!-- 自定义渠道 --> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('channel_headertitle') ?></h3>
		<ul class="tabs">
   			<li><a href="#tab1"><?php echo lang('channel_channellist') ?></a></li>
    		  <li><a href="#tab2"><?php echo lang('channel_addchannel') ?></a></li>
		</ul>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead>
			<?php 
		if ($num == 0) {?>
	
		<h4 class="alert_warning"><?php echo lang('channel_contentalert') ?></h4> <div class="clear"></div> <div class="spacer"></div>
		<?php   }		else {?>
		 
				<tr> 
				    <th  width="16%"><?php echo lang('channel_channelidthead') ?></th> 			 
    				<th  width="28%"><?php echo lang('channel_channelnamethead') ?></th> 
    				<th  width="28%"><?php echo lang('channel_platformthead') ?></th>    				
    				<th  width="28%"><?php echo lang('channel_actionthead') ?></th>     				
				</tr> 
			</thead> 
			<?php } ?>
			<tbody> 
			 <?php 		 
		  if(isset($channel)):
			 	foreach($channel as $rel)
			 	{
			 ?>
				<tr>				  
    				<td><?php echo $rel['channel_id'];?></td> 
    				<td><?php echo $rel['channel_name'];?></td> 
    				<td><?php echo $rel['name'];?></td>    				 
    				<td><a  href="<?php echo site_url();?>/channel/editchannel/<?php echo $rel['channel_id']; ?>">
    				<input type="image" src="<?php echo base_url();?>assets/images/icn_edit.png" title="Edit"></a>
    				<a href="javascript:if(confirm('<?php echo lang('channel_tobodydelete') ?>'))location='<?php echo site_url();?>/channel/deletechannel/<?php echo $rel['channel_id']; ?>'">
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
							<label><?php echo lang('channel_addchannelname') ?></label>
							<input type="text" id='channel_name'>
						</fieldset>
						<fieldset>
							<label><?php echo lang('channel_addplatform') ?></label>
						  <select  id="platform">
							<?php foreach ($platform as $row)
							{
								?>
								<option value="<?php echo $row['id'];?>"><?php echo $row['name']?></option>
								<?php 
							}
							
							?>						  
						  </select>
						</fieldset>
						<input type="button" value="<?php echo lang('channel_addbtn') ?>" class="alt_btn" onClick='addchannel()'>
				</div>			
		<!-- end of post new article -->
			</div><!-- end of #tab2 -->			
		</div><!-- end of .tab_container -->		
		</article><!-- end of content manager article -->
		
		<!-- 自定义系统渠道 --> 
		<?php 
		if ($isAdmin==true ) {?>	
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('channel_headertitlesys') ?></h3>
		<ul class="tabs2">
   			<li><a href="#tab3"><?php echo lang('channel_channellist') ?></a></li>
    		  <li><a href="#tab4"><?php echo lang('channel_addchannel') ?></a></li>
		</ul>
		</header>  
		<div class="tab_container">
			<div id="tab3" class="tab_content1">
			<table class="tablesorter" cellspacing="0"> 
			<thead>	 
				<tr> 
				    <th  width="16%"><?php echo lang('channel_channelidthead') ?></th> 			 
    				<th  width="28%"><?php echo lang('channel_channelnamethead') ?></th> 
    				<th  width="28%"><?php echo lang('channel_platformthead') ?></th>    				
    				<th  width="28%"><?php echo lang('channel_actionthead') ?></th>     			
				</tr> 
			</thead> 
			<tbody> 
			 <?php 		 
		  if(isset($allsychannel)):
			 	foreach($allsychannel as $rel)
			 	{
			 ?>
				<tr>				  
    				<td><?php echo $rel['channel_id'];?></td> 
    				<td><?php echo $rel['channel_name'];?></td> 
    				<td><?php echo $rel['name'];?></td>    				 
    				<td><a  href="<?php echo site_url();?>/channel/editchannel/<?php echo $rel['channel_id']; ?>">
    				<input type="image" src="<?php echo base_url();?>assets/images/icn_edit.png" title="Edit"></a>
    				<a href="javascript:if(confirm('<?php echo lang('channel_tobodydelete') ?>'))location='<?php echo site_url();?>/channel/deletechannel/<?php echo $rel['channel_id']; ?>'">
    				<input type="image"   src="<?php echo base_url();?>assets/images/icn_trash.png" title="Trash"></a>
    				</td>    				 
				</tr> 
			<?php } endif;?>			
			</tbody> 
			</table>
			</div><!-- end of #tab3 -->
		  	
			<div id="tab4" class="tab_content1">			
				<div class="module_content">
						<fieldset>
							<label><?php echo lang('channel_addchannelname') ?></label>
							<input type="text" id='sychannel_name'>
						</fieldset>
						<fieldset>
							<label><?php echo lang('channel_addplatform') ?></label>
						  <select  id="syplatform">
							<?php foreach ($platform as $row)
							{
								?>
								<option value="<?php echo $row['id'];?>"><?php echo $row['name']?></option>
								<?php 
							}
							?>
						  </select>
						</fieldset>
						<input type="button" value="<?php echo lang('channel_addbtn') ?>" class="alt_btn" onClick='addsychannel()'>
				</div>			
		<!-- end of post new article -->
			</div><!-- end of #tab4 -->			
		</div><!-- end of .tab_container -->
		</article>  
		<?php } ?>                                                                
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
<script>
$(".tab_content1").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content1:first").show(); //Show first tab content

$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(".tab_content1").hide(); //Hide all tab content
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
</script>

<script type="text/javascript">
//添加自定义渠道
function addchannel() {	
	channel_name = document.getElementById('channel_name').value;
	platform = document.getElementById('platform').value;
	if(channel_name=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo lang('channel_jsnamemsg') ?>';
		return;

	}
	if(platform=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo lang('channel_jsdescrpmsg') ?>';
		return;

	}
	var data = {
			channel_name :channel_name,
			platform : platform
			
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/channel/addchannel",
					data : data,
					success : function(msg) {
						document.getElementById('msg').innerHTML = "<?php echo lang('channel_jquerysmsg') ?>";										 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo lang('channel_jqueryerromsg') ?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo lang('channel_jquerywaitmsg') ?>';

					},
					complete : function() {
					}
				});
}
function addsychannel() {	
	channel_name = document.getElementById('sychannel_name').value;
	platform = document.getElementById('syplatform').value;
	if(channel_name=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo lang('channel_jsnamemsg') ?>';
		return;

	}
	if(platform=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo lang('channel_jsdescrpmsg') ?>';
		return;

	}
	var data = {
			channel_name :channel_name,
			platform : platform
			
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/channel/addsychannel",
					data : data,
					success : function(msg) {
						document.getElementById('msg').innerHTML = "<?php echo lang('channel_jquerysmsg') ?>";										 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo lang('channel_jqueryerromsg') ?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo lang('channel_jquerywaitmsg') ?>';

					},
					complete : function() {
					}
				});
}
</script>




 