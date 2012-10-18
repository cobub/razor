<section id="main" class="column">
		
		<h4 class="alert_info" id="msg" style="display:none;"></h4>
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('v_man_ch_cusChannel') ?></h3>
		<ul class="tabs">
   			<li><a href="#tab1"><?php echo lang('v_rpt_mk_channelList') ?></a></li>
    		  <li><a href="#tab2"><?php echo lang('v_man_pr_addChannel') ?></a></li>
		</ul>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead>
			<?php 
		if ($num == 0) {?>
	
		<h4 class="alert_warning"><?php echo lang('v_man_pr_noCustomC') ?></h4> <div class="clear"></div> <div class="spacer"></div>
		<?php   }		else {?>
		 
				<tr> 
				    <th  width="16%"><?php echo lang('v_man_ch_channelID') ?></th> 			 
    				<th  width="28%"><?php echo lang('v_rpt_mk_channelName') ?></th> 
    				<th  width="28%"><?php echo lang('v_platform') ?></th>    				
    				<th  width="28%"><?php echo lang('g_actions') ?></th>     				
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
    				<td><a  href="<?php echo site_url();?>/manage/channel/editchannel/<?php echo $rel['channel_id']; ?>">
    				<img src="<?php echo base_url();?>assets/images/icn_edit.png" title="Edit" style="border:0px"/></a>
    				<a href="javascript:if(confirm('<?php echo lang('v_man_pr_deleteNote') ?>'))location='<?php echo site_url();?>/manage/channel/deletechannel/<?php echo $rel['channel_id']; ?>'">
    				<img src="<?php echo base_url();?>assets/images/icn_trash.png" title="Trash" style="border:0px"/></a>
    				</td>    				 
				</tr> 
			<?php } endif;?>			
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
		  	
			<div id="tab2" class="tab_content">			
				<div class="module_content">
						<fieldset>
							<label><?php echo lang('v_man_au_channelName') ?></label>
							<input type="text" id='channel_name'>
						</fieldset>
						<fieldset>
							<label><?php echo lang('v_platform') ?></label>
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
						<input id="addchannelButton" type="button" value="<?php echo lang('v_man_pr_addChannel') ?>" class="alt_btn" onClick='addchannel()'>
				</div>			
		<!-- end of post new article -->
			</div><!-- end of #tab2 -->			
		</div><!-- end of .tab_container -->		
		</article><!-- end of content manager article -->
		
		<!-- Custom system channels--> 
		<?php 
		if ($isAdmin==true ) {?>	
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('v_man_ch_sysChannel') ?></h3>
		<ul class="tabs2">
   			<li><a href="#tab3"><?php echo lang('v_rpt_mk_channelList') ?></a></li>
    		  <li><a href="#tab4"><?php echo lang('v_man_pr_addChannel') ?></a></li>
		</ul>
		</header>  
		<div class="tab_container">
			<div id="tab3" class="tab_content1">
			<table class="tablesorter" cellspacing="0"> 
			<thead>	 
				<tr> 
				    <th  width="16%"><?php echo lang('v_man_ch_channelID') ?></th> 			 
    				<th  width="28%"><?php echo lang('v_rpt_mk_channelName') ?></th> 
    				<th  width="28%"><?php echo lang('v_platform') ?></th>    				
    				<th  width="28%"><?php echo lang('g_actions') ?></th>     			
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
    				<td><a  href="<?php echo site_url();?>/manage/channel/editchannel/<?php echo $rel['channel_id']; ?>">
    				<img src="<?php echo base_url();?>assets/images/icn_edit.png" title="Edit" style="border:0px;"/></a>
    				<a href="javascript:if(confirm('<?php echo lang('v_man_pr_deleteNote') ?>'))location='<?php echo site_url();?>/manage/channel/deletechannel/<?php echo $rel['channel_id']; ?>'">
    				<img src="<?php echo base_url();?>assets/images/icn_trash.png" title="Trash" style="border:0px"/></a>
    				</td>    				 
				</tr> 
			<?php } endif;?>			
			</tbody> 
			</table>
			</div><!-- end of #tab3 -->
		  	
			<div id="tab4" class="tab_content1">			
				<div class="module_content">
						<fieldset>
							<label><?php echo lang('v_rpt_mk_channelName') ?></label>
							<input type="text" id='sychannel_name'>
						</fieldset>
						<fieldset>
							<label><?php echo lang('v_platform') ?></label>
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
						<input id="addSyschannelBtn" type="button" value="<?php echo lang('v_man_pr_addChannel') ?>" class="alt_btn" onClick='addsychannel()'>
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
//add custom channel
function addchannel() {	
	channel_name = trim(document.getElementById('channel_name').value);
	platform = trim(document.getElementById('platform').value);
	if(channel_name=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_man_pr_enterChannelN') ?></font>';
		document.getElementById('msg').style.display="block";
		return;

	}
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
	for (var i = 0; i < channel_name.length; i++) {
		if(pattern.test(channel_name.substr(i, 1))){
			document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_rpt_mk_channelNameE') ?></font>';
			document.getElementById('msg').style.display="block";
			return;
			}
	}
	
	if(platform=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_man_pr_selectPlateform') ?></font>';
		document.getElementById('msg').style.display="block";
		return;

	}
	document.getElementById('addchannelButton').disabled=true;
	var data = {
			channel_name :channel_name,
			platform : platform
			
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/manage/channel/addchannel",
					data : data,
					success : function(msg) {
						if(!msg){
							document.getElementById('addchannelButton').disabled=false;
							document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_man_pr_existChannelS') ?></font>";	
							document.getElementById('msg').style.display="block";
						}else{
						document.getElementById('msg').innerHTML = "<?php echo lang('v_man_pr_modifyChannelS') ?>";										 
						document.getElementById('msg').style.display="block";
						window.location="<?php echo site_url()?>/manage/channel";	}
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo lang('t_error') ?>");
						document.getElementById('addchannelButton').disabled=false;
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo lang('v_man_pr_modifyChannel') ?>';
						document.getElementById('msg').style.display="block";
					},
					complete : function() {
					}
				});
}

function addsychannel() {	
	channel_name = trim(document.getElementById('sychannel_name').value);
	platform = trim(document.getElementById('syplatform').value);
	if(channel_name=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_man_pr_enterChannelN') ?></font>';
		document.getElementById('msg').style.display="block";
		return;

	}
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
	for (var i = 0; i < channel_name.length; i++) {
		if(pattern.test(channel_name.substr(i, 1))){
			document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_rpt_mk_channelNameE') ?></font>';
			document.getElementById('msg').style.display="block";
			return;
			}
	}
	if(platform=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_man_pr_selectPlateform') ?></font>';
		document.getElementById('msg').style.display="block";
		return;

	}
	document.getElementById('addSyschannelBtn').disabled=true;
	var data = {
			channel_name :channel_name,
			platform : platform
			
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/manage/channel/addsychannel",
					data : data,
					success : function(msg) {
						if(!msg){
							document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_man_pr_existChannelS') ?></font>";	
							document.getElementById('msg').style.display="block";
							document.getElementById('addSyschannelBtn').disabled=false;
						}else{
						document.getElementById('msg').innerHTML = "<?php echo lang('v_man_pr_modifyChannelS') ?>";	
						document.getElementById('msg').style.display="block";
						window.location="<?php echo site_url()?>/manage/channel";}									 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo lang('t_error') ?>");
						document.getElementById('addSyschannelBtn').disabled=false;
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




 