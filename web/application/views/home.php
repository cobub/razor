<section id="main" class="column">

<?php if(isset($msg)):?>
<h4 class="alert_warning" id="msg"> <style type="text/css">
				 a:hover {text-decoration: underline }
		    </style><?php echo lang('plg_get_keysecret_home1')?>&nbsp<a  href="http://dev.cobub.com/users" target="_blank"><?php echo lang('plg_get_keysecret_home2')?></a>&nbsp
		    <?php echo lang('plg_get_keysecret_home3')?><?php echo lang('plg_get_keysecret_home4')?><?php echo lang('plg_get_keysecret_home5')?>
		    <a  href="http://dev.cobub.com/users" tartget="_blank"><?php echo lang('plg_get_keysecret_home6')?></a>
		   </h4>
<?php endif;?>
				
			<article class="module width_full">
			<header><h3><?php echo  lang('v_console')?></h3>	
				
			</header>
				<div class="module_content">
				<article>
				<div id="container"  class="module_content" style="height:430px">
					
					<table style="padding-top:3cm" width="100%" border="0" cellspacing="0" cellpadding="0">
					  <tr >
					    <td align="center"><a href="<?php echo site_url() ;?>/report/console"><img src="<?php echo base_url();?>assets/images/applications.png" /></a></td>
					    <td align="center"><a href="<?php echo site_url() ;?>/manage/pluginlist"><img src="<?php echo base_url();?>assets/images/tools.png" /></a></td>
					   
					  </tr>
					  <tr>
					    <td align="center"><a href="<?php echo site_url() ;?>/report/console"  style="font-size:20px"><?php echo lang('m_myapps')?></a></td>
					    <td align="center"><a href="<?php echo site_url() ;?>/manage/pluginlist"  style="font-size:20px"><?php echo lang('m_plugin')?></a></td>
					    
					  </tr>
					</table>
		       </div>
			   </article>
		
				<div class="clear"></div>
			</div>		
		</article><!-- end of stats article -->			
		
			<div class="clear"></div>
		<div class="spacer"></div>	
		
	</section>
	



	
