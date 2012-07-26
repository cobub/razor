<section id="main" class="column">		
		<h4 class="alert_info" id="msg"><?php echo lang('iphonehis_alertinfo') ?></h4>
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('iphonehis_headertitle') ?></h3>		
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead>	
				<tr> 				   		 
    				<th><?php echo lang('iphonehis_chnnelnamethead') ?></th> 
    				<th><?php echo lang('iphonehis_newtimethead') ?></th> 
    				<th><?php echo lang('iphonehis_versionidthead') ?></th>
    				<th><?php echo lang('iphonehis_actionthead') ?></th>    				   				
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
    				<a href="<?php echo site_url(); ?>/autoupdate/updatenewinfo/<?php echo $rel['channel_id']; ?>/<?php echo $rel['cp_id'];?>"><?php echo lang('iphonehis_tbodyupdate') ?></a>|
    				<a href="<?php echo site_url(); ?>/autoupdate/upgradeinfo/<?php echo $rel['channel_id']; ?>/<?php echo $rel['cp_id'];?>"><?php echo lang('androidhis_upgradetbodytdbtn')?></a>
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
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('iphonehis_headerhistit') ?></h3>		
		</header>
		<div class="tab_container">			
			<table class="tablesorter" cellspacing="0"> 
			<thead>	
				<tr> 				   		 
    				<th><?php echo lang('iphonehis_histheadname') ?></th> 
    				<th><?php echo lang('iphonehis_histheadtime') ?></th> 
    				<th><?php echo lang('iphonehis_histheadversionid') ?></th> 
    				<th><?php echo lang('iphonehis_histheadaction') ?></th>   				    				   				
				</tr> 
			</thead> 		
			<tbody> 
			 <?php 		 
		  if(isset($iphoneinfo)):
			 	foreach($iphoneinfo as $rel)
			 	{
			 ?>
				<tr>				  
    				<td><?php echo $rel['channel_name'];?></td> 
    				<td><?php echo substr($rel['updatetime'], 0, 10)?></td> 
    				<td><?php echo $rel['version'] ;?></td> 
    				<td>  				
    				<a href="javascript:if(confirm('<?php echo lang('iphonehis_histdeleteinfo') ?>'))location='<?php echo site_url();?>/autoupdate/deleteupdate/<?php echo $rel['channel_id']; ?>/<?php echo $rel['product_channel_id'];?>/<?php echo  $rel['id'] ; ?>'"><?php echo lang('iphonehis_histbodydelete') ?></a></td>   				 				 				 
				</tr> 
			<?php } endif;?>			
			</tbody> 
			</table>					
		</div><!-- end of .tab_container -->		
		</article><!-- end of content manager article -->		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>