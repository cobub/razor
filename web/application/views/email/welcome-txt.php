<?php echo lang('l_welcome') ?> <?php echo $site_name; ?>,

<?php echo lang('e_thankRegistration') ?><?php echo $site_name; ?><?php echo lang('e_clickLink') ?>

<?php echo site_url('/auth/login/'); ?>

<?php if (strlen($username) > 0) { ?>

<?php echo lang('e_yourUsername') ?><?php echo $username; ?>
<?php } ?>

<?php echo lang('e_yourEmail') ?> <?php echo $email; ?>

<?php /* Your password: <?php echo $password; ?>

*/ ?>

<?php echo lang('e_yourSincerely') ?>
<?php echo $site_name; ?>&nbsp&nbsp<?php echo lang('e_managementTeam') ?>