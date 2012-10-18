<section id="main" class="column">		
		<!-- Custom Channels--> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('v_man_au_currentVersion') ?></h3></header>		
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead>	
				<tr> 				   		 
    				<th><?php echo lang('v_man_au_channelName') ?></th> 
    				<th><?php echo lang('v_man_au_latestUpdateTime') ?></th> 
    				<th><?php echo lang('v_man_au_latestApk') ?></th> 
    				<th><?php echo lang('g_actions') ?></th>    				    				
				</tr> 
			</thead> 		
			<tbody> 
			 <?php 		 
		  if(isset($apkinfo)):
			 	foreach($apkinfo as $rel)
			 	{
			 ?>
				<tr>				  
    				<td><?php echo $rel['channel_name'];?></td> 
    				<td><?php echo substr($rel['date'], 0, 10)?></td> 
    				<td><a href="<?php echo $rel['updateurl'];?>"><?php echo substr($rel['updateurl'],strrpos($rel['updateurl'],"/")+1)  ;?>(<?php echo lang('v_man_au_version')?>：<?php echo $rel['version']; ?>)</a></td>			 
    				<td>
    				<a href="<?php echo site_url(); ?>/manage/autoupdate/updatenewinfo/<?php echo $rel['channel_id']; ?>/<?php echo $rel['cp_id'];?>"><?php echo lang('g_update')?></a>|
    				<a href="<?php echo site_url(); ?>/manage/autoupdate/upgradeinfo/<?php echo $rel['channel_id']; ?>/<?php echo $rel['cp_id'];?>"><?php echo lang('g_upgrade')?></a>
    				</td>  				 
				</tr> 
			<?php } endif;?>			
			</tbody> 
			</table>
			</div> 			
		</div>	
		</article>
		<div class="clear"></div>
		<div class="spacer"></div>
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('v_man_au_historyVersion') ?></h3>		
        </header>
		<div class="tab_container">		
			<table class="tablesorter" cellspacing="0"> 
				<thead>
				<tr> 								   		 
    				<th><?php echo lang('v_man_au_channelName') ?></th> 
    				<th><?php echo lang('v_man_au_updateTime') ?></th> 
    				<th><?php echo lang('v_man_au_apk') ?></th>
    				<th><?php echo lang('g_actions') ?></th>    				  				    				
				</tr> 
			</thead> 		
			<tbody> 
			 <?php 		 
		  if(isset($androidinfo)):
			 	foreach($androidinfo as $rel)
			 	{
			 ?>
				<tr>				  				  
    				<td><?php echo $rel['channel_name'];?></td> 
    				<td><?php echo substr($rel['updatetime'], 0, 10)?></td> 
    				<td><a id="updateurl" href="<?php echo $rel['updateurl'];?>"><?php echo substr($rel['updateurl'],strrpos($rel['updateurl'],"/")+1)  ;?>(<?php echo lang('v_man_au_version') ?>：<?php echo $rel['version']; ?>)</a>
    				</td>
    				
    				<td>   				
    				<a href="javascript:if(confirm('<?php echo lang('v_man_au_info_deletePrompt') ?>'))location='<?php echo site_url();?>/manage/autoupdate/deleteupdate/<?php echo $rel['channel_id']; ?>/<?php echo $rel['product_channel_id'];?>/<?php echo  $rel['id'] ; ?>'"><?php echo lang('g_delete') ?></a></td>    								 
				</tr> 
			<?php } endif;?>			
			</tbody> 
			</table>				
		</div><!-- end of .tab_container -->		
		</article><!-- end of content manager article -->
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	