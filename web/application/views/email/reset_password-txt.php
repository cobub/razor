<?php echo lang('allview_contentdear') ?><?php if (strlen($username) > 0) { ?> <?php echo $username; ?><?php } ?><?php echo lang('allview_contenthello') ?>

<?php echo lang('resetpwdtxt_changedinfo') ?>
<?php if (strlen($username) > 0) { ?>

<?php echo lang('allview_contentusername') ?><?php echo $username; ?>
<?php } ?>

<?php echo lang('allview_contentemail') ?> <?php echo $email; ?>

<?php /* Your new password: <?php echo $new_password; ?>

*/ ?>

<?php echo lang('allview_contentthanks') ?>
<?php echo $site_name; ?>&nbsp&nbsp<?php echo lang('allview_contentteam') ?>