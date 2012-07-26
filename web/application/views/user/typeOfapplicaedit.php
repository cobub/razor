<section id="main" class="column">
<h4 class="alert_info" id='msg'><?php echo lang('type_applicaedit_alertinfo') ?></h4> 

<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo lang('type_applicaedit_alertinfo') ?></h3>
</header>
<div class="tab_container">
<div class="module_content">
<fieldset>
	<label><?php echo lang('type_applicationthead_name') ?></label>
	<input type="text" id='type_applicationthead_name' value="<?php if(isset($catagory)) echo $catagory->name;?>">
</fieldset>
<input type="button" value="<?php echo lang('type_applica_editbtn') ?>" class="alt_btn" onClick="edittype_applica('<?php if(isset($catagory)) echo $catagory->id; ?>')">
</div>
<!-- end of #tab1 -->
<!-- end of .tab_container -->

</article>
<!-- end of content manager article -->



<div class="clear"></div>
<div class="spacer"></div>
</section>

<script type="text/javascript">

function edittype_applica($id)
{
	var type_applica_name = document.getElementById('type_applicationthead_name').value;
	if(type_applica_name=='')
	{
		document.getElementById('msg').innerHTML = '<?php echo lang('type_application_jsnamemsg') ?>';
		return;

	}
	var data = {
			type_applicathead_id:$id,
			type_applicathead_name : type_applica_name                          	
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/user/modifytypeOfapplica",
					data : data,
					success : function(msg) {
						document.getElementById('msg').innerHTML = "<?php echo lang('type_applicaedit_jquerysmsg') ?>";						 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo lang('type_applicaedit_jqueryerromsg') ?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo lang('type_applicaedit_jquerywaitmsg') ?>';

					},
					complete : function() {
					}
				});
}
</script>

