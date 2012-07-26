<section id="main" class="column">
		<article class="module width_full">
			<header>
			<h3 class="tabs_involved"><?php echo lang('updateandroid_alertinfo') ?> </h3>		
		</header>
			<div class="tab_container">			
			<form method="post" action="<?php echo site_url(); ?>/autoupdate/uploadapk/<?php if(isset($cp_id)) echo $cp_id;?>/<?php if(isset($upinfo)) echo $upinfo;?>" enctype="multipart/form-data" />		
			<div class="module_content">					
					<fieldset >				
							<label><?php echo lang('updateandroid_uploadlabel') ?></label>											
							<input id="userfile" name="userfile" size="70"  type="file">												
						<span style='color:red'></span>	 
						</fieldset>	
						<fieldset >				
							<label><?php echo lang('updateandroid_versionidlabl') ?></label><?php echo form_error('versionid'); ?><font color="red"><?php if(isset($errorversion)) echo $errorversion; ?></font>												
							<input id="versionid" name="versionid" size="15"  type="text" value="<?php if(isset($updateinfo)) echo $updateinfo['version'] ;?>">																			
						  <span style='color:#545454'><p align="left">&nbsp&nbsp&nbsp&nbsp&nbsp<?php echo lang('updateandroid_versionremind') ?></p></span>	 
						</fieldset>				
						<fieldset><label><?php echo lang('updateandroid_updatecommon') ?></label><?php echo form_error('description'); ?>	
							<textarea name="description" rows="12" id="description"><?php if(isset($updateinfo)) echo $updateinfo['description'] ;?></textarea>
						</fieldset>							
						<p align="center"> <input type="submit" value="<?php echo lang('updateandroid_updatebtn') ?>" class="alt_btn" onClick="uploadapk()">
					</p>				
						<div class="clear"></div>
				</div><!-- end tab2 -->	
</form>
				</div><!-- end conta -->	
		</article><!-- end of styles article -->
		<div class="spacer"></div>
	</section>
	<script type="text/javascript">	
	function uploadapk()
       {	      
	     var file=document.getElementById('userfile').value;

          if(file=="")
          {
              alert("<?php echo lang('updateandroid_jsapkvalue'); ?>");
              return;
          }	      
	     var pos = file.lastIndexOf(".");
	     var lastname =file.substring(pos,file.length)  //此处文件后缀名也可用数组方式获得file.split(".") 
	     if (lastname.toLowerCase()!=".apk")
	     {
	         alert("<?php echo lang('updateandroid_jsjudgebefore'); ?>"+lastname+"<?php echo lang('updateandroid_jsjudgeafter'); ?>");	        
	         return ;
	     }
	    
	   }
	</script>