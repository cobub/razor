<?php
if ($use_username) {
	$username = array(
		'name'	=> 'username',
		'id'	=> 'username',
		'value' => set_value('username'),
		'maxlength'	=> $this->config->item('username_max_length', 'tank_auth'),
		'size'	=> 30,
	);
}
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'value' => set_value('password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$confirm_password = array(
	'name'	=> 'confirm_password',
	'id'	=> 'confirm_password',
	'value' => set_value('confirm_password'),
	'maxlength'	=> $this->config->item('password_max_length', 'tank_auth'),
	'size'	=> 30,
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>
<?php echo form_open($this->uri->uri_string()); ?>
<section id="main" class="column">
		<h4 class="alert_info"><?php echo lang('register_alertinfo') ?></h4>
		
<article class="module width_full">
<header><h3><?php echo lang('register_header') ?></h3></header>
	<div class="module_content">
		<table class="tablesorter" cellspacing="0"> 
			<tbody> 
			<?php if ($use_username) { ?>
				<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_label(lang('register_userlabel'), $username['id']); ?></td> 
    				<td><?php echo form_input($username); ?> </td> 
    				<td><?php echo form_error($username['name']); ?>
    				<?php echo isset($errors[$username['name']])?$errors[$username['name']]:''; ?>
    				</td> 
				</tr> 
			<?php } ?>
				<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_label(lang('register_emaillabel'), $email['id']); ?></td> 
    				<td><?php echo form_input($email); ?> </td> 
    				<td><?php echo form_error($email['name']); ?>
    				<?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></td> 
				</tr> 
				
					<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_label(lang('register_passwordlab'), $password['id']); ?></td> 
    				<td><?php echo form_password($password); ?></td> 
    				<td><?php echo form_error($password['name']); ?></td> 
				</tr> 
				
				<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_label(lang('register_confirmpwdlab'), $confirm_password['id']); ?></td> 
    				<td><?php echo form_password($confirm_password); ?></td> 
    				<td><?php echo form_error($confirm_password['name']); ?></td> 
				</tr>
					<?php if ($captcha_registration) {?>
					
					<tr>
						<td></td>
						<td></td>
						<td><?php echo form_label(lang('register_verifylabel'), $captcha['id']); ?></td>
						<td><?php echo form_input($captcha); ?></td>
						<td><?php echo form_error($captcha['name']); ?></td>
					</tr>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td><?php echo $captcha_html; ?></td>
						<td></td>
						<td></td>
					</tr>
					<?php } ?>
				<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_submit('register', lang('register_registerbtn')); ?></td> 
    				<td><?php echo lang('register_signupedinfo'); ?>,<?php echo lang('register_nowinfo'); ?>&nbsp&nbsp<?php echo anchor('/auth/login/', lang('register_logininfo')); ?></td> 
    				<td></td>
				</tr> 
			</tbody> 
			</table>
			
	</div>
</article>
</section>
<?php echo form_close(); ?>

