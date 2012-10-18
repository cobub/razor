<?php
$old_password = array(
	'name'	=> 'old_password',
	'id'	=> 'old_password',
	'value' => set_value('old_password'),
	'size' 	=> 30,
);
$new_password = array(
	'name'	=> 'new_password',
	'id'	=> 'new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_new_password = array(
	'name'	=> 'confirm_new_password',
	'id'	=> 'confirm_new_password',
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size' 	=> 30,
);
?>
<?php echo form_open($this->uri->uri_string()); ?>
<section id="main" class="column">
<article class="module width_full">
<header><h3><?php echo lang('m_changePassword') ?></h3></header>
	<div class="module_content">
	<fieldset>
		<?php echo form_label(lang('m_cp_currentPassword'), $old_password['id']); ?>
		<?php echo form_password($old_password); ?>
		<font style="color: red;"><?php echo form_error($old_password['name']); ?><?php echo isset($errors[$old_password['name']])?$errors[$old_password['name']]:''; ?></font>
	</fieldset>
	<fieldset>
		<?php echo form_label(lang('m_cp_newPassword'), $new_password['id']); ?>
		<?php echo form_password($new_password); ?>
		<font style="color: red;"><?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?></font>
	</fieldset>
	<fieldset>
		<?php echo form_label(lang('l_re_confirmPassword'), $confirm_new_password['id']); ?>
		<?php echo form_password($confirm_new_password); ?>
		<font style="color: red;"><?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?></font>
		</fieldset>
	</div>
	<footer>
		<div class="submit_link">
		<?php echo form_submit('change', lang('m_cp_saveChanges')); ?> 
		</div>
	</footer>
</article>
</section>
<?php echo form_close(); ?>