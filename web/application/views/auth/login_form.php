<?php
$login = array(
  'name'	=> 'login',
  'id'	=> 'login',
  'value' => set_value('login'),
  'maxlength'	=> 80,
  'size'	=> 30,
);
if ($login_by_username AND $login_by_email) {
 $login_label = lang('l_emailOrUsername');
} else if ($login_by_username) {
 $login_label = lang('l_username');
} else {
 $login_label = lang('l_re_email');
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

<section id="main" class="column" style="width: 100%">
	<?php if(isset($message)):?>
	<h4 class="alert_success">
		<?php echo $message; ?>
		<?php endif;?>

		<article class="module width_full">
			<header>
				<h3>
					<?php echo lang('l_userlogin') ?>
				</h3>
			</header>
			<div class="module_content">
				<table class="tablesorter" cellspacing="0">
					<tbody>
						<tr>
							<td></td>
							<td></td>
							<td><?php echo form_label($login_label, $login['id']); ?>:</td>
							<td><?php echo form_input($login); ?>
							</td>
							<td><?php echo form_error($login['name']); ?> <span
								style='color: red'><?php echo isset($errors[$login['name']])?$errors[$login['name']]:''; ?>
							</span></td>
						</tr>

						<tr>
							<td></td>
							<td></td>
							<td><?php echo form_label(lang('l_password'), $password['id']); ?>:</td>
							<td><?php echo form_password($password); ?>
							</td>
							<td><?php echo form_error($password['name']); ?> <span
								style='color: red'><?php echo isset($errors[$password['name']])?$errors[$password['name']]:''; ?>
							</span></td>
						</tr>

						<tr>
							<td></td>
							<td></td>
							<td><?php echo form_submit('submit', lang('l_login')); ?></td>
                            
							</td>
							<td></td>
						</tr>

					</tbody>
				</table>

				<br />
				<?php if(isset($version) && $version) {?>
				<div style="text-align: center">
					<span style="background: yellow"> <b><?php echo lang('l_versioninform')
            .$versionvalue.lang('l_vinformtogo');?> <a
							href="<?php echo $version; ?>" target="_blank"><?php echo $version; ?>
						</a> <?php echo lang('l_vinformupdate') ;?> </b>
					</span>
				</div>
				<?php }?>

				<br />
			                                <p align="center">

                                            &copy; <?php echo lang('m_copyright_version')?><?php  echo $this->config->item('version')?> 
<a href=" <?php if($this->config->item('language')=="zh_CN")
                      { echo 'http://dev.cobub.com/zh/docs/cobub-razor/release-note/';}
                else{ echo 'http://dev.cobub.com/docs/cobub-razor/release-note/'; } ?>" target="_blank"><?php echo lang('m_release_note')?></a><br/>
 <p align="center">  <a href ="
<?php if($this->config->item('language')=="zh_CN")
                       { echo 'http://dev.cobub.com/zh/';}
                 else{ echo 'http://dev.cobub.com/'; } ?>
" target ="_blank" title="Mobile Analytics" 
alt="Cobub Razor - Open Source Mobile Analytics                   Solution">
 <?php if($this->config->item('language')=="zh_CN")
                        {  echo '开源移动应用统计分析平台';}
                  else{ echo 'Mobile Analytics of       Open          Source'; } ?></a>
                         </p>    </p><br/>



				<p align="center">
					<?php echo lang('f_detail') ;?>
				</p>
			</div>

		</article>

</section>
<?php echo form_close(); ?>
