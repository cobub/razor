<section id="main" class="column">
<h4 class="alert_info" id='msg' style="display:none;"></h4> 

<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo lang('v_user_appM_editAppT') ?></h3>
</header>
<div class="tab_container">
<div class="module_content">
<fieldset>
	<label><?php echo lang('v_user_appM_typeName') ?></label>
	<input type="text" id='type_applicationthead_name' value="<?php if(isset($catagory)) echo $catagory->name;?>">
</fieldset>
<input type="button" value="<?php echo lang('v_user_rolem_modifyTypeN') ?>" class="alt_btn" onClick="edittype_applica('<?php if(isset($catagory)) echo $catagory->id; ?>')">
</div>
<!-- end of #tab1 -->
<!-- end of .tab_container -->

</article>
<!-- end of content manager article -->



<div class="clear"></div>
<div class="spacer"></div>
</section>

<script type="text/javascript">

function edittype_applica(id)
{	
	var type_applica_name = trim(document.getElementById('type_applicationthead_name').value);
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
	if(type_applica_name=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_user_appM_addTypeFail') ?></font>';
		document.getElementById('msg').style.display="block";
		return;

	}
	for (var i = 0; i < type_applica_name.length; i++) {
		if(pattern.test(type_applica_name.substr(i, 1))){
			document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_user_appM_errorInput') ?></font>';
			document.getElementById('msg').style.display="block";
			return;
			}
	}
	var data = {
			type_applicathead_id:id,
			type_applicathead_name : type_applica_name                          	
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/user/modifytypeOfapplica",
					data : data,
					success : function(msg) {
						if(!msg){
							document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_user_appM_duplicateApp') ?></font>";	
							document.getElementById('msg').style.display="block";
						}else{
						document.getElementById('msg').innerHTML = "<?php echo lang('v_user_rolem_saveAppT') ?>";	
						document.getElementById('msg').style.display="block";}				 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo lang('t_error') ?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo lang('v_user_appM_modifyAppT') ?>';
						document.getElementById('msg').style.display="block";

					},
					complete : function() {
					}
				});
}
function trim(str){
    return  (str.replace(/(^\s*)|(\s*$)/g,''));
 }
</script>

