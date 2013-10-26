<?php
$language = $this->config->item('language');
?>

<section id="main" class="column">
		<?php if(isset($msgw)):?>
		<h4 class="alert_warning" id="msg"> 
		 <?php echo lang('plg_get_keysecret')?>
			 </h4>
		<?php endif;?>
		<?php if(isset($msg)):?>
		<h4 class="alert_error" id="msg"><?php echo $msg;?></h4>
		<?php endif;?>	
		
		<article class="module width_full">
			<header><h3><?php echo  lang('v_basicIntroduce')?></h3>	</header>
			<div class="module_content" >
				<div id="container"  class="module_content" >
				<p><?php echo  lang('v_IOSPluginsContent')?></p>
			

				<h4><?php echo  lang('v_userStatus').': '?> <?php echo ($flag==1)? lang('v_userStatusActive'):lang('v_userStatusInactive'); ?></h4>	
				<p><?php echo lang('v_userStatusExplain')?><br /><?php echo lang('v_userStatusExplain1')?></p>

					<p><small><?php echo  lang('v_tap')?><?php echo lang('v_cobub_user_center')?><?php echo  lang('v_tap1')?></small></p>
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
					<th><?php echo  lang('v_ios_app_name')?></th>
    				<th><?php echo  lang('m_ios_register')?></th>
    				<th><?php echo  lang('v_ios_app_push')?></th> 
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
    				<td><a href="<?php echo site_url()?>/plugin/iospush/iosactivate/checkInfo?appname=<?php echo $row['androidlist']?>" ><?php echo  lang('m_iosinfo')?></a></td>
    				<?php }else{ 
    				?>
    				<td><a href="<?php echo site_url()?>/plugin/iospush/iosactivate/index?appname=<?php echo $row['androidlist']?>" ><?php echo lang('m_register')?></a> </td> 
    				<?php }?>
    				<td><a href="<?php echo ($flag==1)?site_url().'/plugin/iospush/iosapplist/pushInfo?appname='.$row['androidlist']:'javascript:userDisableAlert();';?>">
    				<?php echo lang('v_ios_pushed')?>  </a></td> 
    			
    				
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