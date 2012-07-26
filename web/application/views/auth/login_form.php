<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
	$login_label = lang('login_usernameemaillbl');
} else if ($login_by_username) {
	$login_label = lang('login_usernamelbl');
} else {
	$login_label = lang('login_emaillnl');
}
$password = array(
	'name'	=> 'password',
	'id'	=> 'password',
	'size'	=> 30,
);
$remember = array(
	'name'	=> 'remember',
	'id'	=> 'remember',
	'value'	=> 1,
	'checked'	=> set_value('remember'),
	'style' => 'margin:0;padding:0',
);
$captcha = array(
	'name'	=> 'captcha',
	'id'	=> 'captcha',
	'maxlength'	=> 8,
);
?>
<?php echo form_open($this->uri->uri_string()); ?>

<section id="main" class="column">
<?php if(isset($message)):?>
<h4 class="alert_success"><?php echo $message; ?>
<?php else:?>
		<h4 class="alert_info"><?php echo lang('login_welcomeinfo') ?></h4>
<?php endif;?>
		
<article class="module width_full">
<header><h3><?php echo lang('login_userlogin') ?></h3></header>
	<div class="module_content">
		<table class="tablesorter" cellspacing="0"> 
			<tbody> 
				<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_label($login_label, $login['id']); ?>:</td> 
    				<td><?php echo form_input($login); ?> </td> 
    				<td><?php echo form_error($login['name']); ?>
					<span style='color:red'><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></span></td> 
				</tr> 
				
				<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_label(lang('login_password'), $password['id']); ?>:</td> 
    				<td><?php echo form_password($password); ?> </td> 
    				<td><?php echo form_error($password['name']); ?>
					<span style='color:red'><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?></span></td> 
				</tr> 
				
				<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_submit('submit', lang('login_loginton')); ?></td> 
    				<td><?php echo anchor('/auth/forgot_password/', lang('login_forgetlabel')); ?>&nbsp&nbsp&nbsp&nbsp&nbsp<?php if ($this->config->item('allow_registration', 'tank_auth')) echo anchor('/auth/register/',lang('login_registerlabel')); ?></td> 
    				<td></td>
				</tr> 
			</tbody> 
			</table>
			<br/><br/>
			<p align="center"><?php echo lang('allview_statement') ;?></p>
	</div>
	<footer></footer>
</article>

</section>
<?php echo form_close(); ?>
