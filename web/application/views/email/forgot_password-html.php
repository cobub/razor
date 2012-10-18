<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title><?php echo lang('g_create') ?><?php echo $site_name; ?><?php echo lang('e_newPassword') ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;"><?php echo lang('e_createNewP') ?></h2>
<?php echo lang('e_forgetPassword') ?><br />
<?php echo lang('e_clicklink') ?><br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;"><?php echo lang('e_createNewP') ?></a></b></big><br />
<br />
<?php echo lang('e_linkNotC') ?><br />
<nobr><a href="<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>" style="color: #3366cc;"><?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?></a></nobr><br />
<br />
<br />
<?php echo lang('e_sendEmailA') ?><a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a><?php echo lang('e_sendEmailP') ?><br />
<br />
<?php echo lang('e_yourSincerely') ?><br />
<?php echo $site_name; ?>&nbsp&nbsp<?php echo lang('e_managementTeam') ?>
</td>
</tr>
</table>
</div>
</body>
</html>