<section id="main" class="column">

    <?php if(isset($msg)): ?>
<h4 class="alert_warning" id="msg">
		<style type="text/css">
    a:hover {
        text-decoration: underline
    } 
 </style>
        <?php echo lang('plg_get_keysecret_home')?>
	</h4>
    <?php endif; ?>
				
			<article class="module width_full">
			<header><h3><?php echo  lang('v_console')?></h3>	
			</header>
				<div class="module_content">
				<article>
				<div id="container"  class="module_content" style="height:330px">
					
					<table style="padding-top:1.5cm" width="100%" border="0" cellspacing="0" cellpadding="0">
					<tr >
					    <td align="center"><a href="<?php echo site_url(); ?>/report/console">
						<img src="<?php echo base_url(); ?>assets/images/applications.png" /></a></td>
					    <td align="center"><a href="<?php echo site_url(); ?>/manage/pluginlist">
						<img src="<?php echo base_url(); ?>assets/images/tools.png" /></a></td>
                        <!-- <?php if(isset($key) && isset($secret)): ?>
					    <td align="center"><a href=<?php echo SERVER_BASE_URL.lang('cobub_login_ucenter')."/cobubtologin/$key/$secret" ?> target="_blank" >
						<img src="<?php echo base_url(); ?>assets/images/usercenter.png" /></a></td>
                        <?php endif; ?>
					    <?php if(!isset($key) && !isset($secret)):?>
					    <td align="center"><a href=<?php echo SERVER_BASE_URL.lang('cobub_login_ucenter')."/login" ?> target="_blank" >
						<img src="<?php echo base_url(); ?>assets/images/usercenter.png" /></a></td>
                        <?php endif; ?> -->
					</tr>
					<tr>
					    <td align="center"><a href="<?php echo site_url(); ?>/report/console"  style="font-size:20px"><?php echo lang('m_myapps')?></a></td>
					    <td align="center"><a href="<?php echo site_url(); ?>/manage/pluginlist"  style="font-size:20px"><?php echo lang('m_plugin')?></a></td>
					    <!-- <?php if(isset($key) && isset($secret)):?>
					    <td align="center"><a href= <?php echo SERVER_BASE_URL.lang('cobub_login_ucenter')."/cobubtologin/$key/$secret" ?> target="_blank" style="font-size:20px"><?php echo lang('v_user_center'); ?></a></td>
					    <?php endif; ?>
					    <?php if( !isset($key) && !isset($secret) ):?>
					    <td align="center"><a href= <?php echo SERVER_BASE_URL.lang('cobub_login_ucenter')."/login" ?> target="_blank" style="font-size:20px"><?php echo lang('v_user_center'); ?></a></td>
					    <?php endif; ?> -->
					    
					</tr>
					</table>
		        </div>
                </article>
		
				<div class="clear"></div>
			</div>
		</article>
		<!-- end of stats article -->			
		
		<!-- <article class="module module width_full">
		<header><h3><?php echo lang('v_CR_news'); ?></h3></header>
 
        <iframe id="code_result" class="frame_result" name="code_result" width="100%" height="270px" frameborder="0" scrolling="no" >
         </iframe>
		
		<script type="text/javascript">
                    var posturl = "http://news.cobub.com/index.php?/news/getnews/"+"<?php echo $language; ?>";
            jQuery.ajax({
            type : "post",
            url : posturl,
            success : function(msg) {
            window.frames["code_result"].document.open();
            window.frames["code_result"].document.write(msg);
            window.frames["code_result"].document.close();
            },
            error : function(XmlHttpRequest, textStatus, errorThrown) {

            },
            beforeSend : function() {

            },
            complete : function() {
            }
            });

		</script>
		
		<iframe src="<?php echo site_url()."/news/postnews" ?>"  
		 frameborder="0" scrolling="no"  style="display:none;"></iframe>		
		</article>	 -->
		
		<div class="clear"></div>
		<div class="spacer"></div>
		
	</section>

