<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title><?php echo lang('e_yours') ?> <?php echo $site_name; ?><?php echo lang('e_newPassword') ?></title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;"><?php echo lang('e_yourPassword') ?><?php echo $site_name; ?><?php echo lang('resetpwdhtml_newpwdcontent') ?></h2>
<?php echo lang('e_changeP') ?><br />
<?php echo lang('e_recordAndP') ?><br />
<br />
<?php if (strlen($username) > 0) { ?><?php echo lang('e_yourUsername') ?> <?php echo $username; ?><br /><?php } ?>
<?php echo lang('e_yourEmail') ?> <?php echo $email; ?><br />
<?php /* Your new password: <?php echo $new_password; ?><br /> */ ?>
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