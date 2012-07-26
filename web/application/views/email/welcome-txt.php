<?php echo lang('allview_alertinfo') ?> <?php echo $site_name; ?>,

<?php echo lang('allview_thanksinfo') ?><?php echo $site_name; ?><?php echo lang('welcometxt_contentfinsh') ?>

<?php echo site_url('/auth/login/'); ?>

<?php if (strlen($username) > 0) { ?>

<?php echo lang('allview_contentusername') ?><?php echo $username; ?>
<?php } ?>

<?php echo lang('allview_contentemail') ?> <?php echo $email; ?>

<?php /* Your password: <?php echo $password; ?>

*/ ?>

<?php echo lang('allview_contenttrue') ?>
<?php echo $site_name; ?>&nbsp&nbsp<?php echo lang('allview_contentteam') ?>