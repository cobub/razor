<section id="main" class="column">			
		<h4 class="alert_info" id="msg"><?php echo lang('androidhis_alertinfo') ?></h4>
		<!-- 自定义渠道 --> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('androidhis_headertitle') ?></h3></header>		
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead>	
				<tr> 				   		 
    				<th><?php echo lang('androidhis_namethead') ?></th> 
    				<th><?php echo lang('androidhis_timethead') ?></th> 
    				<th><?php echo lang('androidhis_apkthead') ?></th> 
    				<th><?php echo lang('androidhis_actionthead') ?></th>    				    				
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
    				<td><a href="<?php echo $rel['updateurl'];?>"><?php echo substr($rel['updateurl'],strrpos($rel['updateurl'],"/")+1)  ;?>(<?php echo lang('androidhis_versionidtbody')?>：<?php echo $rel['version']; ?>)</a></td>			 
    				<td>
    				<a href="<?php echo site_url(); ?>/autoupdate/updatenewinfo/<?php echo $rel['channel_id']; ?>/<?php echo $rel['cp_id'];?>"><?php echo lang('androidhis_updatetbodytdbtn')?></a>|
    				<a href="<?php echo site_url(); ?>/autoupdate/upgradeinfo/<?php echo $rel['channel_id']; ?>/<?php echo $rel['cp_id'];?>"><?php echo lang('androidhis_upgradetbodytdbtn')?></a>
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
		<header><h3 class="tabs_involved"><?php echo lang('androidhis_headerhistit') ?></h3>		
        </header>
		<div class="tab_container">		
			<table class="tablesorter" cellspacing="0"> 
				<thead>
				<tr> 								   		 
    				<th><?php echo lang('androidhis_histheadname') ?></th> 
    				<th><?php echo lang('androidhis_histheadtime') ?></th> 
    				<th><?php echo lang('androidhis_histheadapk') ?></th>
    				<th><?php echo lang('androidhis_histheadaction') ?></th>    				  				    				
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
    				<td><a id="updateurl" href="<?php echo $rel['updateurl'];?>"><?php echo substr($rel['updateurl'],strrpos($rel['updateurl'],"/")+1)  ;?>(<?php echo lang('androidhis_histbodytdversionid') ?>：<?php echo $rel['version']; ?>)</a>
    				</td>
    				
    				<td>   				
    				<a href="javascript:if(confirm('<?php echo lang('androidhis_histdeleteinfo') ?>'))location='<?php echo site_url();?>/autoupdate/deleteupdate/<?php echo $rel['channel_id']; ?>/<?php echo $rel['product_channel_id'];?>/<?php echo  $rel['id'] ; ?>'"><?php echo lang('androidhis_histbodytddelete') ?></a></td>    								 
				</tr> 
			<?php } endif;?>			
			</tbody> 
			</table>				
		</div><!-- end of .tab_container -->		
		</article><!-- end of content manager article -->
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	