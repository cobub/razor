<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title><?php echo lang('chanemailhtml_titleinfo') ?><?php echo $site_name; ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;"><?php echo lang('chanemailhtml_contentyou') ?> <?php echo $site_name; ?><?php echo lang('chanemailhtml_contentnewemail') ?></h2>
<?php echo lang('chanemailhtml_contentupdate') ?><?php echo $site_name; ?><?php echo lang('chanemailhtml_contentemailaddress') ?><br />
<?php echo lang('chanemailhtml_contentlink') ?><br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;"><?php echo lang('chanemailhtml_confirmemail') ?></a></b></big><br />
<br />
<?php echo lang('chanemailhtml_contenthand') ?><br />
<nobr><a href="<?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;"><?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?></a></nobr><br />
<br />
<br />
<?php echo lang('allview_contentemail') ?><?php echo $new_email; ?><br />
<br />
<br /><!--
You received this email, because it was requested by a <a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a> user. If you have received this by mistake, please DO NOT click the confirmation link, and simply delete this email. After a short time, the request will be removed from the system.<br />

-->
<?php echo lang('chanemailhtml_contentguade') ?><a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a><?php echo lang('chanemailhtml_contentwarninfo') ?>
<br />
<br />
<?php echo lang('allview_contentthanks') ?><br />
<?php echo $site_name; ?>&nbsp&nbsp<?php echo lang('allview_contentteam') ?>
</td>
</tr>
</table>
</div>
</body>
</html>