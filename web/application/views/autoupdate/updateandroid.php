<section id="main" class="column">
		<article class="module width_full">
			<header>
			<h3 class="tabs_involved"><?php echo lang('v_man_au_info_autoUpdate') ?> </h3>		
		</header>
			<div class="tab_container">			
			<form method="post" action="<?php echo site_url(); ?>/manage/autoupdate/uploadapk/<?php if(isset($cp_id)) echo $cp_id;?>/<?php if(isset($upinfo)) echo $upinfo;?>" enctype="multipart/form-data" />		
			<div class="module_content">	    	
					<fieldset >				
							<label><?php echo lang('v_man_au_info_updateApk') ?></label>											
							<input id="userfile" name="userfile" size="70"  type="file"/><br>
							<span style='color:#545454'><p align="left">&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo lang('v_man_au_info_maxlimit') ?><?php if(isset($upload_mb)){echo $upload_mb;} ?>M</p></span>											
						<span style='color:red;'><?php if(isset($error)){echo $error;}?></span>	 
						</fieldset>	
						<fieldset >				
							<label><?php echo lang('v_man_au_info_version') ?></label><?php echo form_error('versionid'); ?><font color="red"><?php if(isset($errorversion)) echo $errorversion; ?></font>												
							<input id="versionid" name="versionid" size="15"  type="text" value="<?php  echo set_value('versionid',isset($updateinfo)? $updateinfo['version']:'') ;?>"/>																			
						  <span style='color:#545454'><p align="left">&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo lang('v_man_au_info_versionNote') ?></p></span>	 
						</fieldset>				
						<fieldset><label><?php echo lang('v_man_au_info_updateLog') ?></label><?php echo form_error('description'); ?>	
							<textarea name="description" rows="12" id="description" ><?php echo set_value('description',isset($updateinfo)? $updateinfo['description']:'') ; ?></textarea>
							
						</fieldset>							
						<p align="center"> <input type="submit" value="<?php echo lang('g_update') ?>" class="alt_btn" >
					</p>									
						<div class="clear"></div>
				</div><!-- end tab2 -->	
</form>
				</div><!-- end conta -->	
		</article><!-- end of styles article -->
		<div class="spacer"></div>
	</section>
	<script type="text/javascript">	
	  $('#userfile').bind('change', function() {
		var filesize= document.getElementById('userfile').files[0].size;
		var size=filesize/(1024*1024);
		var ajaxurl="<?php echo site_url().'/manage/autoupdate/verifysize' ;?>";		  					 				 					 			  
		   var data = {					
				 size : size
			};
			jQuery.post(ajaxurl, data, function(response) {			   
			});	
		
		});

	</script>