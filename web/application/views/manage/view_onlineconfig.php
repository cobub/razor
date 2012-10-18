<?php
$autogetlocation = array(
	'name'	=> '$autogetlocation',
	'id'	=> '$autogetlocation',
	'value'	=> set_value('$autogetlocation'),
);
$updateonlywifi = array(
	'name'	=> 'updateonlywifi',
	'id'	=> 'updateonlywifi',
	'value'	=> set_value('updateonlywifi'),
);
$reportpolicy = array(
	'name'	=> 'reportpolicy',
	'id'	=> 'reportpolicy',
	'value'	=> set_value('reportpolicy'),
);

$sessionmillis = array(
	'name'	=> 'sessionmillis',
	'id'	=> 'sessionmillis',
	'value'	=> set_value('sessionmillis'),
	'maxlength'	=> 80,
	'size'	=> 30
);
 if(!empty($onlineconfigList))
{
	$autogetlocation['value'] = $onlineconfigList->autogetlocation;
	$updateonlywifi['value'] = $onlineconfigList->updateonlywifi;
	$reportpolicy['value'] = $onlineconfigList->reportpolicy;
	$sessionmillis['value'] = $onlineconfigList->sessionmillis;
} 
?>
<?php echo form_open(site_url().'/manage/onlineconfig/modifyonlineconfig/'); ?>
<section id="main" class="column">
<article class="module width_full">
<header><h3><?php echo lang('v_man_oc_sendPC') ?></h3></header>
	<div class="module_content">
		<table class="tablesorter" cellspacing="0">
		<tbody> 
				<tr> 
   					<td><input name="autogetlocation" type="checkbox" value="1"  id="autogetlocation"
   					<?php if ($autogetlocation['value']==1)   echo 'checked';?> /><?php echo lang('v_man_oc_setAutoPos') ?></td>	
				</tr> 
				<tr> 
   					<td><input name="updateonlywifi" type="checkbox" value="1" id="updateonlywifi"
   					<?php if ($updateonlywifi['value']==1) echo 'checked';?>  /><?php echo lang('v_man_oc_remindUser') ?></td>
				</tr> 				

				<tr> 
    				<td><?php echo form_label(lang('m_rpt_sendPolicy'), $reportpolicy['id']); ?></td> 
 
				</tr> 
				
				<tr> 
    				<td><input type="radio" name="reportPolicy" value="0" 
    				<?php if ($reportpolicy['value']==0) { echo 'checked';}?> ><?php echo lang('v_man_oc_sendDataOnStarted') ?></td>
				</tr>
				
				<tr> 
				    
    				<td><input type="radio" name="reportPolicy" value="1"
    				<?php if ($reportpolicy['value']==1) {echo 'checked value=1';}?>  ><?php echo lang('v_man_oc_sendDataInRealTime') ?></td>
				</tr> 
				

				
				<tr> 
    				<td><?php echo form_label(lang('v_man_oc_setIntervalTime'), $sessionmillis['id']); ?></td> 
				</tr> 
				
				<tr>
					<td><?php echo form_input($sessionmillis)?></td>
				
				</tr>
				<tr>
				<td>
					<?php echo form_error($sessionmillis['name']); ?>
    				<?php echo isset($errors[$sessionmillis['name']])?$sessionmillis[$sessionmillis['name']]:''; ?> 				
				</td>
				</tr>
				
			</tbody> 
			</table>
	</div>
	<footer>
		<div class="submit_link">
		<td><?php echo form_submit('submit', lang('g_save')); ?></td> 
		</div>
	</footer>
</article>
</section>
<?php echo form_close(); ?>
	
	

