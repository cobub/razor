<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = lang('forgetpwd_loginlabel');
} else {
	$login_label =lang('forgetpwd_loginlabel');
}
?>
<?php echo form_open($this->uri->uri_string()); ?>
<section id="main" class="column">
		<h4 class="alert_info"><?php echo lang('forgetpwd_inforemiind') ?></h4>
<article class="module width_full">
<header><h3><?php echo lang('forgetpwd_resetpwdlabel') ?></h3></header>
	<div class="module_content">
<table>
	<tr>
		<td><?php echo form_label($login_label, $login['id']); ?></td>
		<td><?php echo form_input($login); ?></td>
		<td style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></td>
	</tr>
</table>
<?php echo form_submit('reset',lang('forgetpwd_resetpwdbtn')); ?>
</div>
</article>
</section>
<?php echo form_close(); ?>