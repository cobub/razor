<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title><?php echo lang('l_welcome') ?> <?php echo $site_name; ?>！</title></head>
<body>
<div style="max-width: 800px; margin: 0; padding: 30px 0;">
<table width="80%" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="5%"></td>
<td align="left" width="95%" style="font: 13px/18px Arial, Helvetica, sans-serif;">
<h2 style="font: normal 20px/23px Arial, Helvetica, sans-serif; margin: 0; padding: 0 0 18px; color: black;"><?php echo lang('l_welcome') ?> <?php echo $site_name; ?>！</h2>
<!--Thanks for joining <?php echo $site_name; ?>. We listed your sign in details below, make sure you keep them safe.<br />
To open your <?php echo $site_name; ?> homepage, please follow this link:<br />-->
<?php echo lang('e_thankRegistration') ?><?php echo $site_name; ?><?php echo lang('e_clickLink') ?><br />
<br />
<big style="font: 16px/18px Arial, Helvetica, sans-serif;"><b><a href="<?php echo site_url('/auth/activate/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;"><?php echo lang('e_completeR') ?></a></b></big><br />
<br />
<?php echo lang('e_linkNotC') ?><br />
<nobr><a href="<?php echo site_url('/auth/activate/'.$user_id.'/'.$new_email_key); ?>" style="color: #3366cc;"><?php echo site_url('/auth/activate/'.$user_id.'/'.$new_email_key); ?></a></nobr><br />
<br />
<?php echo lang('e_checkEmail') ?><?php echo $activation_period; ?><?php echo lang('e_checkNote') ?><br />
<br />
<br />
<?php if (strlen($username) > 0) { ?><?php echo lang('e_yourUsername') ?><?php echo $username; ?><br /><?php } ?>
<?php echo lang('e_yourEmail') ?><?php echo $email; ?><br />
<?php if (isset($password)) { /* ?>Your password: <?php echo $password; ?><br /><?php */ } ?>
<br />
<br />
<?php echo lang('e_yourSincerely') ?><br />
<?php echo $site_name; ?>&nbsp&nbsp<?php echo lang('e_managementTeam') ?>
</td>
</tr>
</table>
</div>
</body>
</html>