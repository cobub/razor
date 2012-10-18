<?php echo lang('l_welcome') ?><?php echo $site_name; ?>！,

<?php echo lang('e_thankRegistration') ?><?php echo $site_name; ?><?php echo lang('e_clickLink') ?>

<?php echo site_url('/auth/activate/'.$user_id.'/'.$new_email_key); ?>


<?php echo lang('e_checkEmail') ?><?php echo $activation_period; ?><?php echo lang('e_checkNote') ?>
<?php if (strlen($username) > 0) { ?>

<?php echo lang('e_yourUsername') ?> <?php echo $username; ?>
<?php } ?>

<?php echo lang('e_yourEmail') ?> <?php echo $email; ?>
<?php if (isset($password)) { /* ?>

Your password: <?php echo $password; ?>
<?php */ } ?>



<?php echo lang('e_yourSincerely') ?>
<?php echo $site_name; ?>&nbsp&nbsp<?php echo lang('e_managementTeam') ?>