<section id="main" class="column">

		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('m_rpt_appChannel') ?></h3>
		<ul class="tabs">
   			<li><a href="#tab3"><?php echo lang('v_man_ch_sysChannel') ?></a></li>
    		<li><a href="#tab4"><?php echo lang('v_man_ch_cusChannel') ?></a></li>
		</ul>
		</header>
		<div class="tab_container">
			<div id="tab3" class="tab_content">
			<header><h3 align="left"><?php echo lang('v_man_ch_unOpenedChannel') ?></h3></header>
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
   					<th width="10%"><?php echo lang('v_man_ch_channelID') ?></th> 
    				<th width="10%"><?php echo lang('v_rpt_mk_channelName') ?></th>    				
    				<th width="10%"><?php echo lang('v_man_ch_openChannel') ?></th>     				  
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
				  <a href="javascript:if(confirm('<?php echo lang('v_man_pr_openApp') ?>'))location='<?php echo site_url();?>/manage/channel/openchannel/<?php echo $ret['channel_id']; ?>'">
				<img src="<?php echo base_url();?>assets/images/turnon.png" title="Edit"  style="border:0px;"></a>			 
    				</td>    				   				
				</tr> 
			<?php } endif;?>				
			</tbody> 
			</table>
			<footer></footer>
			<header><h3 align="left"><?php echo lang('v_man_ch_openedChannel') ?></h3></header>
			<table class="tablesorter" cellspacing="0">			 
			<thead> 
				<tr> 
    				<th width="10%"><?php echo lang('v_man_ch_channelID') ?></th> 
    				<th width="20%"><?php echo lang('v_rpt_mk_channelName') ?></th> 
    				<th width="40%"><?php echo lang('v_man_ch_appKey') ?></th> 
    				<th width="15%"><?php echo lang('v_man_ch_channelStatus') ?></th> 
    				<th width="15%"><?php echo lang('v_man_ch_autoUpdate') ?></th>   				
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
				  <?php  echo lang('v_man_ch_opened') ?>				 
    				</td>    			
    				<td><a href="<?php echo site_url();?>/manage/autoupdate/index/<?php echo $rel['cp_id']; ?>/<?php echo $rel['channel_id'];?>">
    				<input type="button" value="<?php echo lang('v_man_ch_autoUpdate') ?>" class="alt_btn" onClick=''></a></td>    				
				</tr> 
			<?php } endif;?>			
			</tbody> 
			</table>
			</div><!-- end of #tab3 -->
			
			
			<div id="tab4" class="tab_content">
			<header><h3 align="left"><?php echo lang('v_man_ch_unOpenedChannel') ?></h3></header>
				<table class="tablesorter" cellspacing="0">				 
			<thead> 
				<tr> 
   					<th width="10%"><?php echo lang('v_man_ch_channelID') ?></th> 
    				<th width="10%"><?php echo lang('v_man_au_channelName') ?></th>    				
    				<th width="10%"><?php echo lang('v_man_ch_openChannel') ?></th>     				  
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
				  <a href="javascript:if(confirm('<?php echo lang('v_man_pr_openApp') ?>'))location='<?php echo site_url();?>/manage/channel/openchannel/<?php echo $rew['channel_id']; ?>'">
				<img src="<?php echo base_url();?>assets/images/turnon.png" title="Edit"  style="border:0px;"/></a>			 
    				</td>    				   				
				</tr> 
			<?php } endif;?>				
			</tbody> 
			</table>
			<footer></footer>
			<header><h3 align="left"><?php echo lang('v_man_ch_openedChannel') ?></h3></header>
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr>    				 
    				<th width="10%"><?php echo lang('v_man_ch_channelID') ?></th> 
    				<th width="20%"><?php echo lang('v_man_au_channelName') ?></th> 
    				<th width="40%"><?php echo lang('v_man_ch_appKey') ?></th> 
    				<th width="15%"><?php echo lang('v_man_ch_channelStatus') ?></th> 
    				<th width="15%"><?php echo lang('v_man_ch_autoUpdate') ?></th>   				
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
		         <?php echo  lang('v_man_ch_opened');?></td>    			
    			<td><a href="<?php echo site_url();?>/manage/autoupdate/index/<?php echo $row['cp_id']; ?>/<?php echo $row['channel_id'];?>">
    				<input type="button" value="<?php echo lang('v_man_ch_autoUpdate') ?>" class="alt_btn" onClick=''></a></td>    	
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