<section id="main" class="column">		
		<h4 class="alert_info" id="msg"><?php echo lang('allview_appchannel') ?></h4>
		<!-- 自定义渠道 --> 
<!-- 应用渠道 -->
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('allview_appchannel') ?></h3>
		<ul class="tabs">
   			<li><a href="#tab3"><?php echo lang('appchannel_systemchannel') ?></a></li>
    		<li><a href="#tab4"><?php echo lang('appchannel_definechannel') ?></a></li>
		</ul>
		</header>
		<div class="tab_container">
			<div id="tab3" class="tab_content">
			<header><h3 align="left"><?php echo lang('appchannel_closechannel') ?></h3></header>
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th width="10%"><?php echo lang('appchannel_channelidthead') ?></th> 
    				<th width="10%"><?php echo lang('appchannel_channelnamethead') ?></th>    				
    				<th width="10%"><?php echo lang('appchannel_openthead') ?></th>     				  
				</tr> 
			</thead> 
			<tbody> 
				  <?php 		 
		   if(isset($sychannel)):
			 	foreach($sychannel as $ret)
			 	{?>
				<tr> 
    				<td><?php echo $ret['channel_id'];?></td> 
    				<td><?php echo $ret['channel_name'];?></td>  				
    				<td> 		          
				  <a href="javascript:if(confirm('<?php echo lang('appchannel_tbodyinfo') ?>'))location='<?php echo site_url();?>/channel/openchannel/<?php echo $ret['channel_id']; ?>'">
				<input type="image" src="<?php echo base_url();?>assets/images/turnon.png" title="Edit"></a>			 
    				</td>    				   				
				</tr> 
			<?php } endif;?>				
			</tbody> 
			</table>
			<footer></footer>
			<header><h3 align="left"><?php echo lang('appchannel_openchannel') ?></h3></header>
			<table class="tablesorter" cellspacing="0">			 
			<thead> 
				<tr> 
    				<th width="10%"><?php echo lang('appchannel_opentheadid') ?></th> 
    				<th width="20%"><?php echo lang('appchannel_opentheadname') ?></th> 
    				<th width="40%"><?php echo lang('appchannel_opentheadapk') ?></th> 
    				<th width="15%"><?php echo lang('appchannel_opentheadstatus') ?></th> 
    				<th width="15%"><?php echo lang('appchannel_opentheadupdate') ?></th>   				
				</tr> 
			</thead> 
			<tbody> 
			  <?php 		 
		   if(isset($productkey)):
			 	foreach($productkey as $rel)
			 	{?>
				<tr> 
    				<td><?php echo $rel['channel_id'];?></td> 
    				<td><?php echo $rel['channel_name'];?></td>     				
    				<td>
		           <?php echo $rel['productkey'];?>	     
    				</td>
    				<td> 		          
				  <?php  echo lang('appchannel_opentbodyinfo') ?>				 
    				</td>    			
    				<td><a href="<?php echo site_url();?>/autoupdate/index/<?php echo $rel['cp_id']; ?>/<?php echo $rel['channel_id'];?>">
    				<input type="button" value="<?php echo lang('appchannel_openbtn') ?>" class="alt_btn" onClick=''></a></td>    				
				</tr> 
			<?php } endif;?>			
			</tbody> 
			</table>
			</div><!-- end of #tab3 -->
			
			<!-- 自定义渠道的应用 -->
			
			<div id="tab4" class="tab_content">
			<header><h3 align="left"><?php echo lang('appchannel_declosechannel') ?></h3></header>
				<table class="tablesorter" cellspacing="0">				 
			<thead> 
				<tr> 
   					<th width="10%"><?php echo lang('appchannel_detheandid') ?></th> 
    				<th width="10%"><?php echo lang('appchannel_detheandname') ?></th>    				
    				<th width="10%"><?php echo lang('appchannel_detheandopen') ?></th>     				  
				</tr> 
			</thead> 
			<tbody> 
				  <?php 		 
		   if(isset($channel)):
			 	foreach($channel as $rew)
			 	{?>
				<tr> 
    				<td><?php echo $rew['channel_id'];?></td> 
    				<td><?php echo $rew['channel_name'];?></td>  				
    				<td> 		          
				  <a href="javascript:if(confirm('<?php echo lang('appchannel_detbodyinfo') ?>'))location='<?php echo site_url();?>/channel/openchannel/<?php echo $rew['channel_id']; ?>'">
				<input type="image" src="<?php echo base_url();?>assets/images/turnon.png" title="Edit"></a>			 
    				</td>    				   				
				</tr> 
			<?php } endif;?>				
			</tbody> 
			</table>
			<footer></footer>
			<header><h3 align="left"><?php echo lang('appchannel_deopenchannel') ?></h3></header>
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr>    				 
    				<th width="10%"><?php echo lang('appchannel_deopenchanneid') ?></th> 
    				<th width="20%"><?php echo lang('appchannel_deopenchannelname') ?></th> 
    				<th width="40%"><?php echo lang('appchannel_deopenchannelapk') ?></th> 
    				<th width="15%"><?php echo lang('appchannel_deopencstatus') ?></th> 
    				<th width="15%"><?php echo lang('appchannel_deopenupdate') ?></th>   				
				</tr> 
			</thead> 
			<tbody> 
			 <?php 		 
		   if(isset($deproductkey)):
			 	foreach($deproductkey as $row)
			 	{
			 ?>
				<tr> 
    				<td><?php echo $row['channel_id'];?></td> 
    				<td><?php echo $row['channel_name'];?></td>     				
    				<td><?php echo $row['productkey'];?>	 </td>
    				<td>
		         <?php echo  lang('appchannel_deopentbodyinfo');?></td>    			
    			<td><a href="<?php echo site_url();?>/autoupdate/index/<?php echo $row['cp_id']; ?>/<?php echo $row['channel_id'];?>">
    				<input type="button" value="<?php echo lang('appchannel_deopenbtn') ?>" class="alt_btn" onClick=''></a></td>    	
				</tr> 
				<?php } endif;?>
			</tbody> 
			</table>

			</div><!-- end of #tab4 -->
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->	
		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>