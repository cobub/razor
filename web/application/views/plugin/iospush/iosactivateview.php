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