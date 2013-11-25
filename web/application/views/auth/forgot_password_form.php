<?php
$login = array(
	'name'	=> 'login',
	'id'	=> 'login',
	'value' => set_value('login'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
if ($this->config->item('use_username', 'tank_auth')) {
	$login_label = lang('l_fp_enterEmail');
} else {
	$login_label =lang('l_fp_enterEmail');
}
?>
<?php echo form_open($this->uri->uri_string()); ?>
<section id="main_full" class="column">
	
<article class="module width_full">
<header><h3><?php echo lang('l_fp_forgetPassword') ?></h3></header>
	<div class="module_content">
<table class="tablesorter" cellspacing="0">
	<tr><td colspan="3"><?php echo lang('l_fp_details') ?></td></tr>
	<tr>
		<td><?php echo form_label($login_label, $login['id']); ?></td>
		<td><?php echo form_input($login); ?></td>
		<td style="color: red;"><?php echo form_error($login['name']); ?><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?></td>
	</tr>
	<tr><td><?php echo form_submit('reset',lang('l_fp_send')); ?></td><td> </td><td></td></tr>
</table>

<br/>

<p align="center">
  
                                             &copy; Copyright 2012-2015 Cobub Razor  Version:<?php  echo $this->config->item('version')?> 
 <a href=" <?php if($this->config->item('language')=="zh_CN")
                       { echo 'http://dev.cobub.com/zh/docs/cobub-razor/release-note/';}
                 else{ echo 'http://dev.cobub.com/docs/cobub-razor/release-note/'; } ?>" target="_blank">
				  <?php if($this->config->item('language')=="zh_CN")
                       { echo '发布说明';}
                 else{ echo 'Release Notes'; } ?>
				 </a><br/>
  <p align="center">  <a href ="
 <?php if($this->config->item('language')=="zh_CN")
                        { echo 'http://dev.cobub.com/zh/';}
                  else{ echo 'http://dev.cobub.com/'; } ?>
 " target ="_blank" title="Mobile Analytics" alt="Cobub Razor - Open Source Mobile Analytics                   Solution">
 <?php if($this->config->item('language')=="zh_CN")
                        {  echo '开源移动应用统计分析平台';}
                  else{ echo 'Mobile Analytics of       Open          Source'; } ?>
 </a>
                          </p>    </p><br/>

</div>
</article>
</section>
<?php echo form_close(); ?>
