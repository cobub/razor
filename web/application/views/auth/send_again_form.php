<?php
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
?>
<?php echo form_open($this->uri->uri_string()); ?>
<section id="main" class="column">
		<h4 class="alert_info"><?php echo lang('l_sendagain'); ?><?php echo anchor('/auth/logout/', lang('l_return')); ?></h4>
		
<article class="module width_full">
<header><h3><?php echo lang('l_resend'); ?></h3></header>
	<div class="module_content">
<table>
	<tr>
		<td><?php echo form_label(lang('l_emailAddress'), $email['id']); ?></td>
		<td><?php echo form_input($email); ?></td>
		<td style="color: red;"><?php echo form_error($email['name']); ?><?php echo isset($errors[$email['name']])?$errors[$email['name']]:''; ?></td>
	</tr>
</table>
<?php echo form_submit('send', lang('l_fp_send')); ?>
</div>
</article>
</section>
<?php echo form_close(); ?>