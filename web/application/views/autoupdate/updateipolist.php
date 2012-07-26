<section id="main" class="column">
		
		<h4 class="alert_info" id="msg"><?php echo lang('updateipolist_alertinfo') ?></h4>
		<!-- 自定义渠道 --> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('updateipolist_headertitle') ?></h3>		
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead>	
				<tr> 				   		 
    				<th><?php echo lang('updateipolist_channelname') ?></th> 
    				<th><?php echo lang('updateipolist_latesttime') ?></th> 
    				<th><?php echo lang('updateipolist_versionid') ?></th>
    				<th><?php echo lang('updateipolist_action') ?></th>    				   				
				</tr> 
			</thead> 		
			<tbody> 
			 <?php 		 
		  if(isset($appinfo)):
			 	foreach($appinfo as $rel)
			 	{
			 ?>
				<tr>				  
    				<td><?php echo $rel['channel_name'];?></td> 
    				<td><?php echo substr($rel['date'], 0, 10)?></td> 
    				<td><?php echo $rel['version'] ;?></td>
    				<td>
    				<a href="<?php echo site_url(); ?>/autoupdate/updatenewinfo/<?php echo $rel['channel_id']; ?>/<?php echo $rel['cp_id'];?>"><?php echo lang('updateipolist_tbodyupdatebtn') ?></a>
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



