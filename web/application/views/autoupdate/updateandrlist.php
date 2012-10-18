<section id="main" class="column">
		<!-- Custom Channels --> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('v_man_au_info_autoUpdate') ?></h3>		
		</header>
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
    				<td><a href="<?php echo $rel['updateurl'];?>"><?php echo substr($rel['updateurl'],strrpos($rel['updateurl'],"/")+1)  ;?>(<?php echo lang('v_man_au_versionID') ?>：<?php echo $rel['version']; ?>)</a></td>			 
    				<td>
    				<a href="<?php echo site_url(); ?>/manage/autoupdate/updatenewinfo/<?php echo $rel['channel_id']; ?>/<?php echo $rel['cp_id'];?>"><?php echo lang('g_update') ?></a>
    				</td>  				 
				</tr> 
			<?php } endif;?>			
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->				
		</div><!-- end of .tab_container -->		
		</article><!-- end of content manager article -->
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>


