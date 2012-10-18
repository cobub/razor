<?php echo lang('e_dear') ?><?php if (strlen($username) > 0) { ?> <?php echo $username; ?><?php } ?><?php echo lang('e_hello') ?>

<?php echo lang('e_preserveP') ?>
<?php if (strlen($username) > 0) { ?>

<?php echo lang('e_yourUsername') ?><?php echo $username; ?>
<?php } ?>

<?php echo lang('e_yourEmail') ?> <?php echo $email; ?>

<?php /* Your new password: <?php echo $new_password; ?>

*/ ?>

<?php echo lang('e_thankYou') ?>
<?php echo $site_name; ?>&nbsp&nbsp<?php echo lang('e_managementTeam') ?>