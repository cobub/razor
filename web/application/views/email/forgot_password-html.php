<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title><?php echo lang('forgotpwdhtml_contentcreate') ?><?php echo $site_name; ?><?php echo lang('forgotpwdhtml_contentnewpwd') ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;"><?php echo lang('forgotpwdhtml_contentcreatenewpwd') ?></h2>
<?php echo lang('forgotpwdhtml_contentforgetpwd') ?><br />
<?php echo lang('forgotpwdhtml_contentnewpwdlink') ?><br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;"><?php echo lang('forgotpwdhtml_contentcreatepwd') ?></a></b></big><br />
<br />
<?php echo lang('forgotpwdhtml_contentlinknouse') ?><br />
<nobr><a href="<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;"><?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?></a></nobr><br />
<br />
<br />
<?php echo lang('forgotpwdhtml_contenteamilvia') ?><a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a><?php echo lang('forgotpwdhtml_contentwarninfo') ?><br />
<br />
<?php echo lang('allview_contenttrue') ?><br />
<?php echo $site_name; ?>&nbsp&nbsp<?php echo lang('allview_contentteam') ?>
</td>
</tr>
</table>
</div>
</body>
</html>