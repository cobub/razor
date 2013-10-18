<?php
$crt_passwd = array(
	'name'	=> 'crt_passwd',
	'id'	=> 'crt_passwd',
	'value' => set_value('crt_passwd'),
	'size'	=> 30,
	'class' => "span6 typeahead required",
	'style' => "height:24px;"
);
?>
<section id="main" class="column">
<?php if(isset($msg)):?>
<h4 class="alert_error" id="msg"><?php echo $msg;?></h4>
<?php endif;?>
	<!-- show user key&secret -->
	<article class="module width_full">
		<header>
			<h3><?php echo  lang('m_ios_register')?></h3>
		</header>
		<?php echo form_open('plugin/iospush/iosactivate/activateApp'); ?>
		<div class="module_content">
			<table class="tablesorter" cellspacing="0">
				<tbody>
				
				    <fieldset>
						<p>&nbsp;&nbsp;<?php echo lang('m_ios_register_successed') ?></p> 
					</fieldset>
				
					<fieldset>
						<label><?php echo lang('v_appName') ?></label><?php echo form_error('appname'); ?>
						<input
							type="text" id='appname' name='appname'   readonly="readonly" value= "<?php echo $appname?>">
					
					</fieldset>
					
					<?php if(!isset($flag)):?>
					<fieldset>
					
						<label><?php echo "Bundle ID:" ?></label> <?php echo form_error('bundleid'); ?>
						<input
							type="text" id='bundleid' name='bundleid' value="<?php echo isset($flag)?$bundleid:"";?>">
					</fieldset>
					<?php endif;?>
					

					<?php if(isset($flag)):?>
					<fieldset>
						<label><?php echo "Bundle ID:" ?></label> <?php echo form_error('bundleid'); ?>
						<input
							type="text" id='bundleid' name='bundleid' readonly="readonly" value="<?php echo isset($flag)?$bundleid:"";?>">
					</fieldset>
					
					<fieldset>
						<label><?php echo "RegisterID" ?></label>
						<input
							type="text" id='registerid' name='registerid' readonly="readonly" value="<?php echo $register_id?>">
					</fieldset>
					<?php endif;?>

				</tbody>
			</table>
		</div>
		<footer>
			<footer>
			<?php if(isset($flag)):?>
				<div class="submit_link">
				<input type='submit' id='submit' class='alt_btn'
					name="iospush/iosactivate" value="<?php echo lang('m_registered')?>" disabled="disabled">
				</div>
			<?php else:?>
			<div class="submit_link">
				<input type='submit' id='submit' class='alt_btn'
					name="iospush/iosactivate" value="<?php echo lang('m_register')?>">
			</div>
			<?php endif;?>
			</footer>
		</footer>
		<?php echo form_close(); ?>
				
	</article>
	
	<!-- upload certificate Begin -->
<article class="module width_full">
	<header>
		<h3><?php echo lang('v_ios_upload_certificate') ?></h3>
	</header>
			
<div id="content" class="span10">
	<!-- content starts -->
	<div class="row-fluid sortable ui-sortable">
		<div class="box span12">

			<div class="box-content">
				<?php echo form_open_multipart('plugin/iospush/iosactivate/upload/'.$register_id.'/'.$appname);?>
					<div class="module_content">
						<table class="table table-striped table-bordered bootstrap-datatable">
							<tbody>
								<tr>
									<td><?php echo form_label(lang('v_ios_certificate_pwd')); ?>&nbsp;&nbsp;</td>
                                    <td>
                                        <?php 
											echo form_password($crt_passwd);
                                        ?>
										<span class="help-inline"><font color='error'>
                                   		<?php echo form_error($crt_passwd['name']); ?>
                                    	<?php echo isset($errors[$crt_passwd['name']]) ? $errors[$crt_passwd['name']] : '';?>
                                    </font></span>
    				                </td>
								</tr>
								<tr></tr>
								<tr></tr>
								<tr>
									<td><?php echo form_label(lang('v_ios_certificate_file')); ?>&nbsp;&nbsp;</td>
                                    <td>
                                        <?php echo form_upload('userfile');?>
    				                </td>
								</tr>
							</tbody>
						</table>
						<br />
						<div class="form-actions">
							<button class="btn btn-primary" type="submit" id="uploadsubmit" 
							style="width:70px;height:25px;">
							<?php echo lang('v_ios_upload')?></button>						
						</div>
						
					    </div> 	    
				</form>
			</div>
		</div>
	</div>
</div> <!-- content ends -->
</article> <!-- upload certificate Begin -->
	


	