<?php echo lang('e_dear') ?><?php if (strlen($username) > 0) { ?> <?php echo $username; ?><?php } ?><?php echo lang('e_hello') ?>

<!--You have changed your email address for <?php echo $site_name; ?>.
Follow this link to confirm your new email address:

<?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?>


Your new email: <?php echo $new_email; ?>


You received this email, because it was requested by a <?php echo $site_name; ?> user. If you have received this by mistake, please DO NOT click the confirmation link, and simply delete this email. After a short time, the request will be removed from the system.


Thank you,
The <?php echo $site_name; ?> Team-->
<?php echo lang('e_changeEmail') ?><?php echo $site_name; ?><?php echo lang('chanemailtxt_contentregister') ?>
<?php echo lang('e_confirmNewA') ?>

<?php echo site_url('/auth/reset_email/'.$user_id.'/'.$new_email_key); ?>


<?php echo lang('e_newEamilA') ?> <?php echo $new_email; ?>



<?php echo lang('e_notClick') ?><?php echo $site_name; ?><?php echo lang('e_deleteEmail') ?>

<?php echo lang('e_thankYou') ?>
<?php echo $site_name; ?> &nbsp&nbsp<?php echo lang('e_managementTeam') ?>