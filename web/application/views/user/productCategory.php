<section id="main" class="column">
		
 		<h4 class="alert_info" id='msg' style="display:none"></h4> 
		
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('m_appType')?></h3>
		<ul class="tabs">
   			<li><a href="#tab1"><?php echo  lang('v_user_appM_appTypeList')?></a></li>
    		<li><a href="#tab2"><?php echo  lang('v_user_appM_addAppType')?></a></li>
		</ul>
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo  lang('v_user_appM_typeId')?></th> 
    				<th><?php echo  lang('v_user_appM_typeName')?></th>   				
    				<th><?php echo  lang('v_user_appM_editType')?></th>
				</tr> 
			</thead> 
			<tbody> 
			 <?php if(isset($productcategorylist)):
			 	foreach($productcategorylist->result() as $row)
			 	{
			 ?>
				<tr> 
    				<td><?php echo $row->id;?></td>                
    				<td><?php echo $row->name;?></td>     				  				
    				<td><a  href="<?php echo site_url();?>/user/edittypeOfapplication/<?php echo $row->id; ?>">
    				<img src="<?php echo base_url();?>assets/images/icn_edit.png" title="Edit" style="border:0px;"/></a>
    				<a href="javascript:if(confirm('<?php echo lang('v_user_appM_deleteType') ?>'))location='<?php echo site_url();?>/user/deletetypeOfapplication/<?php echo $row->id; ?>'">
    				<img src="<?php echo base_url();?>assets/images/icn_trash.png" title="Trash" style="border:0px;"/></a>
    				</td>  
				</tr> 
			<?php } endif;?>
			
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
			<div id="tab2" class="tab_content">
			<div class="module_content">
						<fieldset>
							<label><?php echo  lang('v_user_appM_typeName')?></label>
							<input type="text" id='name'>
						</fieldset>
						<input id="addAppBtn" type="button" value="<?php echo  lang('v_user_appM_addType')?>" class="alt_btn" onClick='addtypeOfapplica()'>
				</div>

			</div><!-- end of #tab2 -->
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		
		
		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
<script type="text/javascript">

function addtypeOfapplica() {	
	type_applicationName = trim(document.getElementById('name').value);
	if(type_applicationName=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo  lang('v_user_appM_addTypeFail')?></font>';
		document.getElementById('msg').style.display="block";
		return;

	}
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
	for (var i = 0; i < type_applicationName.length; i++) {
		if(pattern.test(type_applicationName.substr(i, 1))){
			document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_user_appM_errorInput') ?></font>';
			document.getElementById('msg').style.display="block";
			return;
			}
	}
	document.getElementById('addAppBtn').disabled=true;
	var data = {
			type_applicationName : type_applicationName,
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/user/addtypeOfapplication",
					data : data,
					success : function(msg) {
						if(!msg){
							document.getElementById('msg').innerHTML = "<font color=red><?php echo  lang('v_user_appM_duplicateApp')?></font>";		
							document.getElementById('msg').style.display="block";
							document.getElementById('addAppBtn').disabled=false;
						}else{
						document.getElementById('msg').innerHTML = "<?php echo  lang('v_user_appM_addTypeSuccess')?>";		
						document.getElementById('msg').style.display="block";
						window.location="<?php echo site_url()?>/user/applicationManagement";}			 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo  lang('t_error')?>");
						document.getElementById('addAppBtn').disabled=false;
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo  lang('v_user_appM_waitAddType')?>';
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
	
	
