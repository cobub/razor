<section id="main" class="column">
		<article class="module width_full">
			<header>
			<h3 class="tabs_involved"><?php echo lang('updateiphone_alertinfo') ?></h3>			
		</header>
			<div class="tab_container">			
			<form method="post" action="<?php echo site_url(); ?>/autoupdate/uploadapp/<?php if(isset($cp_id)) echo $cp_id;?>/<?php if(isset($upinfo)) echo $upinfo;?>" />		
				<div class="module_content">					
					<fieldset >				
							<label><?php echo lang('updateiphone_applabel') ?></label><?php echo form_error('appurl'); ?>											
							<input id="appurl" name="appurl" size="70"  type="text" value="<?php if(isset($updateinfo)) echo $updateinfo['updateurl'] ;?>">																	
						 <span style='color:#545454'><p align="left">&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo lang('updateiphone_appremind') ?></p></span>	 
						</fieldset>	
						<fieldset >				
							<label><?php echo lang('updateiphone_versionidlabl') ?></label><?php echo form_error('versionid'); ?><font color="red"><?php if(isset($errorversion)) echo $errorversion; ?></font>											
							<input id="versionid" name="versionid" size="15"  type="text" value="<?php if(isset($updateinfo)) echo $updateinfo['version'] ;?>">																			
						  <span style='color:#545454'><p align="left">&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo lang('updateiphone_versionremind') ?></p></span>	 
						</fieldset>				
						<fieldset>
							<label><?php echo lang('updateiphone_updatecommon') ?></label><?php echo form_error('description'); ?>
							<textarea name="description" rows="12"><?php if(isset($updateinfo)) echo $updateinfo['description'] ;?></textarea>
						</fieldset>						
						<p align="center"> <input type="submit" value="<?php echo lang('updateiphone_updatebtn') ?>" class="alt_btn" onClick="">
					</p>				
						<div class="clear"></div>
				</div><!-- end tab2 -->	
</form>
				</div><!-- end conta -->	
		</article><!-- end of styles article -->
		<div class="spacer"></div>
	</section>
	<script type="text/javascript">	
	
	</script>