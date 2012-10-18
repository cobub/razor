<?php echo lang('e_dear') ?><?php if (strlen($username) > 0) { ?> <?php echo $username; ?><?php } ?><?php echo lang('e_hello') ?>

<?php echo lang('e_clickLinkB') ?>

<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>


<?php echo lang('e_sendEmailA') ?><a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a><?php echo lang('e_sendEmailP') ?>


<?php echo lang('e_yourSincerely') ?>
<?php echo $site_name; ?>&nbsp&nbsp<?php echo lang('e_managementTeam') ?>