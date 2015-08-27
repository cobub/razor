<section id="main" class="column" style='height: 1500px;'>
<?php if(isset($msg)):?>
<h4 class="alert_error" id="msg"><?php echo $msg;?></h4>
<?php endif;?>
<?php if(isset($successmsg)):?>
<h4 class="alert_success" id="successmsg"><?php echo $successmsg;?></h4>
<?php endif;?>
    <!-- show user key&secret -->
    <article class="module width_full">
        <header>
            <style type="text/css">
a:hover {
    text-decoration: underline
}
</style>
            <h3 class="tabs_involved"><?php echo lang('v_plugins_account') ?></h3>
        </header>
        <?php echo form_open('manage/accountauth/saveUserKeys'); ?>
        <div class="module_content">
            <p style="font-size: 13px;"> <?php echo lang('v_plugins_introduce')?>
            <br /> <b> <a
                    href="<?php if($language=='zh_CN') echo 'http://www.cobub.com/users/index.php?/help/userkey';else echo 'http://www.cobub.com/users/en/index.php?/help/userkey';?>"
                    target="_blank"><?php echo lang('plg_getkey')?></a>
                </b>
            </p>
            <table class="tablesorter" cellspacing="0">
                <tbody>
                    <fieldset>
                        <label style="font-weight: bold;"><?php echo lang('plg_userkey') ?></label></label><?php echo form_error('userkey'); ?>
                        <input type="text" id='userkey' name='userkey'
                            value="<?php echo isset($puserkey)?$puserkey:"";?>">
                    </fieldset>

                    <fieldset>
                        <label style="font-weight: bold;"><?php echo lang('plg_usersecret') ?></label></label><?php echo form_error('usersecret'); ?>
                        <input type="text" id='usersecret'
                            name='usersecret'
                            value="<?php echo isset($pusersecret)?$pusersecret:"";?>">
                    </fieldset>
                </tbody>
            </table>
        </div>
        <footer>
            <div class="submit_link">
                <input
                    <?php if(isset($guest_roleid) && $guest_roleid==2):echo 'disabled="disabled"'; endif;?>
                    type='submit' id='submit' class='alt_btn' name="userkey/save"
                    value=<?php echo lang('plg_save') ?>>
            </div>
        </footer>
        <?php echo form_close(); ?>
        
    </article>
    <!-- end of show user key&secret-->