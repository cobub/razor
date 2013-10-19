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
<h4 class="alert_warning" id="msg"><?php echo $msg;?></h4>
<?php endif;?>
	<!-- show user key&secret -->
	<?php if(!isset($flag)):?>
	<article class="module width_full">
		<header>
			<h3><?php echo  lang('m_ios_register')?></h3>
		</header>
		<?php echo form_open_multipart('plugin/iospush/iosactivate/activateApp'); ?>
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
	
					<fieldset>
						<label><?php echo "Bundle ID:" ?></label> <?php echo form_error('bundleid'); ?>
						<input
							type="text" id='bundleid' name='bundleid' value="<?php echo isset($flag)?$bundleid:"";?>">
					</fieldset>
					
					<fieldset>
					<?php echo form_label(lang('v_ios_certificate_file')); ?>
                    <?php echo form_upload('userfile');?>
					</fieldset>
					
					<fieldset>
						<label style="width:200px;"><?php echo lang('v_ios_certificate_pwd')  ?></label> <?php echo form_error('passwd'); ?>
						<input
							type="password" id="passwd" name="passwd" value="     ">
					</fieldset>

				</tbody>
			</table>
		</div>
		<footer>
			<div class="submit_link">
				<input type='submit' id='submit' class='alt_btn'
					name="iospush/iosactivate" value="<?php echo lang('m_register')?>">
			</div>
		</footer>
		</form>
				
	</article>
	<?php endif;?>	
	<!-- register  ends -->
	
	<!-- update bundleid begin -->
	<?php if(isset($flag)):?>
	<article class="module width_full">
		<header>
			<h3><?php echo  lang('m_ios_register')?></h3>
		</header>
		<?php echo form_open_multipart('plugin/iospush/iosactivate/activateApp'); ?>
		<div class="module_content">
			<table class="tablesorter" cellspacing="0">
				<tbody>
				
				    <fieldset>
						<p>&nbsp;&nbsp;<?php echo lang('m_ios_register_successed') ?></p> 
					</fieldset>
				
					<fieldset>
						<label><?php echo lang('v_appName') ?></label><?php echo form_error('appname'); ?>
						<input
							type="text" id='appname' name='appname'  readonly="readonly" value= "<?php echo $appname?>">
					
					</fieldset>
					
					<fieldset>
						<label><?php echo "Bundle ID:" ?></label><!-- <?php echo form_error('bundleid'); ?>  --> 
						<input
							type="text" id='bundleid' name='bundleid' value="<?php echo isset($flag)?$bundleid:"";?>">
					</fieldset>
					
					<fieldset>
						<label><?php echo "RegisterID" ?></label>
						<input
							type="text" id='registerid' name='registerid' readonly="readonly" value="<?php echo $register_id?>">
					</fieldset>
					
					<fieldset>
					<?php echo form_label(lang('v_ios_certificate_file')); ?>
                    <?php echo form_upload('userfile');?>
					</fieldset>
					
					<fieldset>
						<label ><?php echo lang('v_ios_certificate_pwd')  ?></label> <?php echo form_error('passwd'); ?>
						<input
							type="password" id="passwd" name="passwd" value="     ">
					</fieldset>

				</tbody>
			</table>
		</div>
		<footer>
			<?php if(isset($flag)):?>
				<div class="submit_link">
				<input type='submit' id='update' class='alt_btn'
					name="iospush/iosactivate/update" value="<?php echo lang('m_registered')?>">
				</div>
			<?php endif;?>
		</footer>
	</form>
				
	</article>
	<?php endif;?>	
	<!-- update bundleid ends -->

	