<?php
$email = array(
	'name'	=> 'email',
	'id'	=> 'email',
	'value'	=> set_value('email'),
	'maxlength'	=> 80,
	'size'	=> 30,
	'readonly'=>true	
);
$username = array(
	'name'	=> 'username',
	'id'	=> 'username',
	'value' => set_value('username'),
	'maxlength'	=> 80,
	'size'	=> 30,
	'readonly'=>true
);
$companyname = array(
	'name'	=> 'companyname',
	'id'	=> 'companyname',
	'value' => set_value('companyname'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$contact = array(
	'name'	=> 'contact',
	'id'	=> 'contact',
	'value' => set_value('contact'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$telephone = array(
	'name'	=> 'telephone',
	'id'	=> 'telephone',
	'value' => set_value('telephone'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$QQ = array(
	'name'	=> 'QQ',
	'id'	=> 'QQ',
	'value' => set_value('QQ'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$MSN = array(
	'name'	=> 'MSN',
	'id'	=> 'MSN',
	'value' => set_value('MSN'),
	'maxlength'	=> 80,
	'size'	=> 30,
);
$Gtalk = array(
	'name'	=> 'Gtalk',
	'id'	=> 'Gtalk',
	'value' => set_value('Gtalk'),
	'maxlength'	=> 80,
	'size'	=> 30,
);

if(isset($profile))
{
	$email['value'] = $profile->useremail;
	$username['value'] = $profile->username;
	$companyname['value'] = $profile->companyname;
	$contact['value'] = $profile->contact;
	$telephone['value'] = $profile->telephone;
	$QQ['value'] = $profile->QQ;
	$MSN['value'] = $profile->MSN;
	$Gtalk['value'] = $profile->Gtalk;
}

?>
<?php echo form_open(site_url().'/profile/saveprofile/'); ?>
<section id="main" class="column">
<h4 class="alert_info" id="msg"><?php echo  lang('userprofile_alertinfo')?></h4> 
<article class="module width_full">
<header><h3><?php echo  lang('userprofile_headertilte')?></h3></header>
	<div class="module_content">
		<table class="tablesorter" cellspacing="0">
		
			<tbody> 
				<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_label(lang('userprofile_emaillabe'), $email['id']); ?></td> 
					<td><?php echo form_input($email);?> 
    				</td> 
    				<td></td>
				</tr> 

				<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_label(lang('userprofile_usernamelal'), $username['id']); ?></td> 
					<td><?php echo form_input($username	)?></td> 
                    <td></td>
    				</td> 
				</tr> 
				
					<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_label(lang('userprofile_companylb'), $companyname['id']); ?></td>   				    
    				<td><?php echo form_input($companyname); ?></td> 
    				<td></td>
				</tr>
				 
				<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_label(lang('userprofile_contractlabe'), $contact['id']); ?></td>  
    				<td><?php echo form_input($contact); ?></td> 
    				<td></td>
				</tr> 
				
				<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_label(lang('userprofile_telephonelal'), $telephone['id']); ?></td>
    				<td><?php echo form_input($telephone); ?></td> 
    				<td><?php echo form_error($telephone['name']); ?>
    				<?php echo isset($errors[$telephone['name']])?$errors[$telephone['name']]:''; ?></td> 
				</tr> 
				
				<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_label('QQ', $QQ['id']); ?></td>
    				<td><?php echo form_input($QQ); ?></td>	
    				<td></td>			 
				</tr> 
				
				<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_label('MSN', $MSN['id']); ?></td>
    				<td><?php echo form_input($MSN); ?></td>
    				<td><?php echo form_error($MSN['name']); ?>
    				<?php echo isset($errors[$MSN['name']])?$errors[$MSN['name']]:''; ?></td>  
				</tr>
				 
				<tr> 
   					<td></td> 
   					<td></td>
    				<td><?php echo form_label('Gtalk', $Gtalk['id']); ?></td>
    				<td><?php echo form_input($Gtalk); ?></td> 
    				<td></td>
				</tr> 	

			</tbody> 
			</table>
	</div>
	<footer>
		<div class="submit_link">
		<td><?php echo form_submit('submit', lang('userprofile_savebtn')); ?></td> 
		</div>
	</footer>
</article>
</section>
<?php echo form_close(); ?>
	