<?php
$appname = array(
	'name'	=> 'appname',
	'id'	=> 'appname',
	'value'	=> set_value('appname'),
	'maxlength'	=> 80,
	'size'	=> 30,
);

$description = array(
		'name'	=> 'description',
		'id'	=> 'description',
		'value'	=> set_value('description'),
		'rows'	=> 10,
);
?>
<?php echo form_open('product/saveedit/'.$product['id']); ?>
<section id="main" class="column">
		<h4 class="alert_info" id='msg'><?php echo lang('editproduct_alertinfo') ?></h4>
		
<article class="module width_full">
	<header><h3><?php echo lang('editproduct_headertitle') ?></h3></header>
	<div class="module_content">
		<fieldset>
			<label><?php echo lang('editproduct_appnamelbl') ?></label><?php echo form_error('appname'); ?>
			<input type="text" name="appname" value="<?php if(isset($product)) echo $product['name']  ?>">
		</fieldset>
		
		<fieldset style="width:48%; float:left; margin-right: 3%;">
			<label><?php echo lang('editproduct_apptypelbl') ?></label>
			<select name='category' id='category'  >			
			<?php if(isset($category)):?>
			<?php foreach($category->result() as $row) {?>
					<option value=<?php echo $row->id; ?> <?php if($product['category']== $row->id) echo "Selected"; ?>><?php echo $row->name;?></option>
			<?php } endif;?>
			</select>
		</fieldset>		
		<fieldset style="width:48%; float:left;">
			<label><?php echo lang('editproduct_appplatformlbl') ?></label>
			<input type="text" style="width:92%;" name="platform" value="<?php if(isset($product)) echo $product['platname'] ; ?>" ReadOnly=true>
		</fieldset>		
		<fieldset>
			<label><?php echo lang('editproduct_descriptionlbl') ?></label><?php echo form_error('description'); ?>
			
			<textarea name="description" rows="12" ><?php if(isset($product)) echo $product['description'] ; ?></textarea>			
		</fieldset>
		<div class="clear"></div>
	</div>
	<footer>
		<div class="submit_link">
		<input type='submit' id='submit' class='alt_btn' name="product/saveedit"  value="<?php echo lang('editproduct_appbtn') ?>">
		</div>
	</footer>
</article>
</section>
<?php echo form_close(); ?>
