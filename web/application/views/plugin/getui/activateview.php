<section id="main" class="column">
<?php if(isset($msg)):?>
<h4 class="alert_error" id="msg"><?php echo $msg;?></h4>
<?php endif;?>
	<!-- show user key&secret -->
	<article class="module width_full">
		<header>
			<h3><?php echo  lang('v_activateApp')?></h3>
		</header>
		<?php echo form_open('plugin/getui/activate/activateApp'); ?>
		<div class="module_content">
			<?php if(isset($flag)): else:?>
		   <p> <b><?php echo lang('v_tipPackageName') ?></b></p>
		  	<?php endif;?>
			<table class="tablesorter" cellspacing="0">
				<tbody>
					<fieldset>
						<label><?php echo lang('v_appName') ?></label><?php echo form_error('appname'); ?>
						<input
							type="text" id='appname' name='appname'   readonly="readonly" value= "<?php echo $appName?>">
					
					</fieldset>

					<fieldset>
						<label><?php echo "PackageName" ?></label> <label><a href="http://dev.cobub.com/users/index.php?/help/getui#n2" target="_blank"><?php echo lang('v_tipGetPackageName') ?></a></label><?php echo form_error('packagename'); ?>
						
						<input
							type="text" id='packagename' name='packagename' <?php echo isset($flag)?'disabled':'';?> value="<?php echo isset($flag)?$app_identifier:"";?>">
					</fieldset>
				</tbody>
			</table>
		</div>
		<footer>
			<footer>
			<?php if(isset($flag)):?>
				<div class="submit_link">
				<input type='submit' id='submit' class='alt_btn'
					name="getui/activated" value="<?php echo lang('v_actived')?>" disabled="disabled">
				</div>
			<?php else:?>
			<div class="submit_link">
				<input type='submit' id='submit' class='alt_btn'
					name="getui/activate" value="<?php echo lang('v_active')?>">
			</div>
			<?php endif;?>
			</footer>
		</footer>
		<?php echo form_close(); ?>
		
	</article>
	<!-- end of show user key&secret-->
	<?php if(isset($flag)): ?>

	
	<article class="module width_full">
	<header><h3 class="tabs_involved"><?php echo  lang('v_responseInfo')?></h3></header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			
			<tbody> 
				<tr>
					
    				<td>client_Appid</td> 
    				<td><?php echo $appId ?> </td>
    				
    			</tr>
    		    <tr>
				
					<td>client_appKey</td> 
    				<td><?php echo $appKey?> </td>
    			</tr> 
    			<tr>
					
    				<td>client_appSecret</td> 
    				<td><?php echo $appSecret?></td>
    			</tr> 
    			<tr>
					
    				<td>client_masterSecret</td> 
    				<td> <?php echo $masterSecret?></td>
    			</tr> 
    			<tr>
					
    				<td>activate_date</td> 
    				<td><?php echo $activateDate ?> </td>
    			</tr> 
		
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			</div><!-- end of .tab_container -->
		</article>

	<?php endif;?>