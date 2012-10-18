<?php
$appname = array (
		'name' => 'appname',
		'id' => 'appname',
		'value' => set_value ( 'appname' ),
		'maxlength' => 80,
		'size' => 30 
);

$description = array (
		'name' => 'description',
		'id' => 'description',
		'value' => set_value ( 'description' ),
		'rows' => 10 
);
?>
<?php echo form_open('manage/product/saveedit/'.$product['id']); ?>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3><?php echo lang('m_rpt_editApp') ?></h3>
		</header>
		<div class="module_content">
			<fieldset>
				<label><?php echo lang('v_man_pr_name') ?></label><?php echo form_error('appname'); ?>
			<input type="text" name="appname"
					value="<?php echo set_value('appname',isset($product)?$product['name']:"");?>">
			</fieldset>

			<fieldset style="width: 48%; float: left; margin-right: 3%;">
				<label><?php echo lang('v_man_pr_appType') ?></label> <select
					name='category' id='category'>			
			<?php if(isset($category)):?>
			<?php foreach($category->result() as $row) {?>
					<option value="<?php echo $row->id; ?>"
						<?php
					
if (isset ( $selectcategory )) {
						if ($selectcategory == $row->id) {
							echo "Selected";
						}
					} else {
						if (($product ['category'] == $row->id)) {
							echo "Selected";
						}
					}
					?>><?php echo $row->name;?></option>
			<?php } endif;?>
			</select>
			</fieldset>
			<fieldset style="width: 48%; float: left;">
				<label><?php echo lang('v_man_pr_platform') ?></label> <input
					type="text" style="width: 92%;" name="platform"
					value="<?php if(isset($product)) echo $product['platname'] ; ?>"
					ReadOnly=true>
			</fieldset>
			<div class="clear"></div>
			<fieldset>
				<label><?php echo lang('v_man_pr_description') ?></label><?php echo form_error('description'); ?>
			
			<textarea name="description" rows="10" cols="40"><?php echo set_value('description',isset($product)?$product['description']:"") ; ?></textarea>
			</fieldset>
		</div>
		<footer>
			<div class="submit_link">
				<input type='submit' id='submit' class='alt_btn'
					name="product/saveedit" value="<?php echo lang('g_update') ?>">
			</div>
		</footer>
	</article>
</section>
<?php echo form_close(); ?>
