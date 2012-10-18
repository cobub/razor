<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title><?php echo lang('e_newEmailin') ?><?php echo $site_name; ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;"><?php echo lang('e_newEmailLn') ?> <?php echo $site_name; ?><?php echo lang('e_newEamil') ?></h2>
<?php echo lang('e_changeEmail') ?><?php echo $site_name; ?><?php echo lang('chanemailhtml_contentemailaddress') ?><br />
<?php echo lang('e_clickLinkC') ?><br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;"><?php echo lang('e_confirmNewE') ?></a></b></big><br />
<br />
<?php echo lang('e_linkNotC') ?><br />
<nobr><a href="<?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;"><?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?></a></nobr><br />
<br />
<br />
<?php echo lang('e_yourEmail') ?><?php echo $new_email; ?><br />
<br />
<br /><!--
You received this email, because it was requested by a <a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a> user. If you have received this by mistake, please DO NOT click the confirmation link, and simply delete this email. After a short time, the request will be removed from the system.<br />

-->
<?php echo lang('e_sendEmailA') ?><a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a><?php echo lang('e_sendEmailP') ?>
<br />
<br />
<?php echo lang('e_thankYou') ?><br />
<?php echo $site_name; ?>&nbsp&nbsp<?php echo lang('e_managementTeam') ?>
</td>
</tr>
</table>
</div>
</body>
</html>