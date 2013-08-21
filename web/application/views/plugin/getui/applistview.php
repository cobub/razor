<?php
$language = $this->config->item('language');
?>

<section id="main" class="column">
		<?php if(isset($msgw)):?>
		<h4 class="alert_warning" id="msg"> 
		 <?php echo lang('plg_get_keysecret1')?><a href="http://dev.cobub.com/users" ><?php echo lang('plg_get_keysecret2')?></a><?php echo lang('plg_get_keysecret3')?>
			 <a href="http://dev.cobub.com/users"><?php echo lang('plg_get_keysecret4')?></a><?php echo lang('plg_get_keysecret5')?><a href="http://dev.cobub.com/users"></a>
			 <a href="http://192.168.1.4/ucenter/index.php?/help/userkey"><?php echo lang('plg_get_keysecret8')?></a></h4>
		<?php endif;?>
		<?php if(isset($msg)):?>
		<h4 class="alert_error" id="msg"><?php echo $msg;?></h4>
		<?php endif;?>	
		
		<article class="module width_full">
			<header><h3><?php echo  lang('v_basicIntroduce')?></h3>	</header>
			<div class="module_content" >
				<div id="container"  class="module_content" >
				<p><?php echo  lang('v_getuiPluginsContent')?></p>
			

				<h4><?php echo  lang('v_userStatus').': '?> <?php echo ($flag==1)? lang('v_userStatusActive'):lang('v_userStatusInactive'); ?></h4>	
				<p><?php echo lang('v_userStatusExplain')?><a href="http://dev.cobub.com/users"  target="_blank" ><?php echo lang('plg_get_keysecret_home2')?></a><?php echo lang('v_userStatusExplain1')?><a href="http://192.168.1.4/ucenter/index.php?/help/getui#n2"  target="_blank"><?php echo lang('v_integrationGuide')?></a></p>

					<p><small><?php echo  lang('v_tap')?><a href="http://dev.cobub.com/users"  target="_blank" ><?php echo lang('plg_get_keysecret_home2')?></a><?php echo  lang('v_tap1')?></small></p>
		       	</div>
			</div>		
		</article><!-- end of stats article -->	

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
    				<th><?php echo  lang('v_appActive')?></th>
    				<th><?php echo  lang('v_push')?></th> 
    				<th><?php echo  lang('v_cleanPush')?></th> 
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
    				<?php  if($row['isActive']){ ?>
    				<td><a href="<?php echo site_url()?>/plugin/getui/activate/checkInfo?appName=<?php echo $row['androidlist']?>" ><?php echo  lang('v_checkKeys')?></a></td>
    				<?php }else{ 
    				?>
    				<td><a href="<?php echo site_url()?>/plugin/getui/activate/index?appName=<?php echo $row['androidlist']?>" ><?php echo lang('v_active')?></a> </td> 
    				<?php }?>
    				<td><a href="<?php echo ($isAuth==1&&$flag==1)?site_url().'/plugin/getui/applist/pushInfo?type=0&appName='.$row['androidlist']:'javascript:userDisableAlert();';?>">
    				<?php echo lang('v_push')?>  </a></td> 
    				<td><a href="<?php echo ($isAuth==1&&$flag==1)?site_url().'/plugin/getui/applist/pushInfo?type=1&appName='.$row['androidlist']:'javascript:userDisableAlert();';?>">
    				<?php echo lang('v_cleanPush')?></a> </td> 
    				
    			</tr> 
			<?php } endif;?>
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->	
	</section>
		
	<script type="text/javascript">
		function userDisableAlert()
		{
			alert("<?php echo  lang('v_userStatus').': '?> <?php echo ($flag==1)? lang('v_userStatusActive'):lang('v_userStatusInactive'); ?>");
		}
	</script>