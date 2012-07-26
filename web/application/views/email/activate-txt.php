<?php echo lang('allview_alertinfo') ?><?php echo $site_name; ?>！,

<?php echo lang('allview_thanksinfo') ?><?php echo $site_name; ?><?php echo lang('activatetxt_registerinfo') ?>

<?php echo site_url('/auth/activate/'.$user_id.'/'.$new_email_key); ?>


<?php echo lang('activatetxt_pleaseinfo') ?><?php echo $activation_period; ?><?php echo lang('activatetxt_checkinfo') ?>
<?php if (strlen($username) > 0) { ?>

<?php echo lang('allview_contentusername') ?> <?php echo $username; ?>
<?php } ?>

<?php echo lang('allview_contentemail') ?> <?php echo $email; ?>
<?php if (isset($password)) { /* ?>

Your password: <?php echo $password; ?>
<?php */ } ?>



<?php echo lang('allview_contenttrue') ?>
<?php echo $site_name; ?>&nbsp&nbsp<?php echo lang('allview_contentteam') ?>