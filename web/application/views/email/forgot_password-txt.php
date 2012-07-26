<?php echo lang('allview_contentdear') ?><?php if (strlen($username) > 0) { ?> <?php echo $username; ?><?php } ?><?php echo lang('allview_contenthello') ?>

<?php echo lang('forgotpwdtxt_forgotpwdinfo') ?>

<?php echo site_url('/auth/reset_password/'.$user_id.'/'.$new_pass_key); ?>


<?php echo lang('forgotpwdtxt_emailviainfo') ?><a href="<?php echo site_url(''); ?>" style="color: #3366cc;"><?php echo $site_name; ?></a><?php echo lang('forgotpwdtxt_warninfo') ?>


<?php echo lang('allview_contenttrue') ?>
<?php echo $site_name; ?>&nbsp&nbsp<?php echo lang('allview_contentteam') ?>