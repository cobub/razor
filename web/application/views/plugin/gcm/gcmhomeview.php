<?php
$language = $this->config->item('language');
?>

<section id="main" class="column">

<?php if(isset($msg)):?>
<h4 class="alert_warning" id="msg"><?php echo $msg;?></h4>
<?php endif;?>
		
		<article class="module width_full">
			<header><h3><?php echo  lang('v_basicIntroduce')?></h3>	</header>
			<?php echo form_open('plugin/gcm/gcmhome/saveAppkey'); ?>
			<div class="module_content" >
				<div id="container"  class="module_content" >
				<p><?php echo  lang('gcm_introduction')?></p>
				<p><?php echo  lang('gcm_cobub_introduction')?></p>
				<p><?php echo lang('gcm_apikey_descript')?><?php echo lang('gcm_get_apikey')?></p>
				<p><?php echo lang('gcm_sdk_integrate')?></p>
		       	</div>
			</div>		
		</article><!-- end of stats article -->	

		<article class="module width_full">
			<div class="module_content" >
				<div id="container"  class="module_content" >
				<lable style="font-weight: bold;">API key:</lable>&nbsp;&nbsp;
				<input type="text" id='appkey' name='appkey' style="width:400px;" value="<?php echo isset($appkey)?$appkey:"";?>">
				<input type='submit' id='submit' class='alt_btn' style="float:right" name="appkey/save" value=<?php echo lang('gcm_saveappkey') ?>>
				</div>
				
			</div>
		</article>
		
		<div class="clear"></div>
		
			<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('v_appList')?></h3>
		
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
					<th><?php echo  lang('v_app')?></th>
    				<th><?php echo  lang('v_push')?></th> 
				</tr> 
			</thead> 
			
			<tbody> 
			<?php 
			 	if(isset($applist)):
				for($i=0;$i<count($applist);$i++)
				{
			 		$row = $applist[$i];
			 	?>
				<tr>
    				<td><?php echo $row['androidlist'];?></td> 
    				<td><a href="<?php echo ($isAuth && $flag==1)?site_url().'/plugin/gcm/applist/pushInfo?appId='.$row['appId']:'javascript:userDisableAlert();';?>">
    				<?php echo lang('v_push')?>  </a></td> 			
    			</tr> 
			<?php } endif;?>
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			</div><!-- end of .tab_container -->
			<?php echo form_close(); ?>
		
		</article><!-- end of content manager article -->	
	</section>
	
	<script type="text/javascript">
		function userDisableAlert()
		{
			alert("<?php echo  lang('v_userStatus').': '?> <?php echo ($flag==1)? lang('v_userStatusActive'):lang('v_userStatusInactive'); ?>");
		}

	</script>
	
		


