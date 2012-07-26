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
<header><h3><?php echo lang('changepwd_changetitle') ?></h3></header>
	<div class="module_content">
		<table class="tablesorter" cellspacing="0">
<tbody> 
	<tr>
		<td><?php echo form_label(lang('changepwd_oldpwd'), $old_password['id']); ?></td>
		<td><?php echo form_password($old_password); ?></td>
		<td style="color: red;"><?php echo form_error($old_password['name']); ?><?php echo isset($errors[$old_password['name']])?$errors[$old_password['name']]:''; ?></td>
	</tr>
	<tr>
		<td><?php echo form_label(lang('changepwd_newpwd'), $new_password['id']); ?></td>
		<td><?php echo form_password($new_password); ?></td>
		<td style="color: red;"><?php echo form_error($new_password['name']); ?><?php echo isset($errors[$new_password['name']])?$errors[$new_password['name']]:''; ?></td>
	</tr>
	<tr>
		<td><?php echo form_label(lang('changepwd_confirmpwd'), $confirm_new_password['id']); ?></td>
		<td><?php echo form_password($confirm_new_password); ?></td>
		<td style="color: red;"><?php echo form_error($confirm_new_password['name']); ?><?php echo isset($errors[$confirm_new_password['name']])?$errors[$confirm_new_password['name']]:''; ?></td>
	</tr>
</tbody> 
			</table>			
	</div>
	<footer>
		<div class="submit_link">
		<td><?php echo form_submit('change', lang('changepwd_button')); ?></td> 
		</div>
	</footer>
</article>
</section>
<?php echo form_close(); ?>