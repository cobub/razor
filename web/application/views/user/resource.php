<section id="main" class="column">
		
 		<h4 class="alert_info" id='msg' style="display:none;"></h4> 
		
	
		
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo  lang('m_resourceManagement')?></h3>
		<ul class="tabs">
   			<li><a href="#tab1"><?php echo  lang('v_user_resm_resourceL')?></a></li>
    		<li><a href="#tab2"><?php echo  lang('v_user_resm_addResource')?></a></li>
		</ul>
		</header>

		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo  lang('v_user_resm_resourceN')?></th> 
    				<th><?php echo  lang('v_user_resm_resourceD')?></th>   				
    				<th><?php echo  lang('v_user_resm_editResource')?></th>
				</tr> 
			</thead> 
			<tbody> 
			 <?php if(isset($resourcelist)):
			 	foreach($resourcelist->result() as $row)
			 	{
			 ?>
				<tr> 
    				<td><?php echo $row->name;?></td> 
    				<td><?php echo $row->description;?></td>     				  				
    				<td>
    				<a href="<?php echo site_url().'/user/editResource/'.$row->id?>"><?php echo  lang('g_edit')?></a>
    				</td> 
				</tr> 
			<?php } endif;?>
			
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			
			<div id="tab2" class="tab_content">
			<div class="module_content">
						<fieldset>
							<label><?php echo  lang('v_user_resm_resourceN')?></label>
							<input type="text" id='name'>
						</fieldset>
						<fieldset>
							<label><?php echo  lang('v_user_resm_resourceD')?></label>
							<input type="text" id='description'>
						</fieldset>
						<input type="button" value="<?php echo  lang('v_user_resm_addResource')?>" class="alt_btn" onClick='addResource()'>
				</div>

			</div><!-- end of #tab2 -->
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		
		
		
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
	<script type="text/javascript">

function addResource() {	
	resourceName = trim(document.getElementById('name').value);
	description = trim(document.getElementById('description').value);
	if(resourceName=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_user_resm_enterResource')?></font>';
		document.getElementById('msg').style.display="block";
		return;

	}
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
	for (var i = 0; i < resourceName.length; i++) {
		if(pattern.test(resourceName.substr(i, 1))){
			document.getElementById('msg').innerHTML = '<font color=red><?php echo lang('v_user_resm_errorInput')?></font>';
			document.getElementById('msg').style.display="block";
			return;
			}
	}
	if(description=='')
	{
		document.getElementById('msg').innerHTML = '<font color=red><?php echo  lang('v_user_resm_addResourceD')?></font>';
		document.getElementById('msg').style.display="block";
		return;

	}
	var data = {
			resourceName : resourceName,
			description : description
			
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/user/addResource",
					data : data,
					success : function(msg) {	
						if(!msg){
							document.getElementById('msg').innerHTML = "<font color=red><?php echo  lang('v_user_resm_existResources')?></font>";
							document.getElementById('msg').style.display="block";
						}else{
						document.getElementById('msg').innerHTML = "<?php echo  lang('v_user_resm_addResourceS')?>";
						document.getElementById('msg').style.display="block";}						 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo  lang('t_error')?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo  lang('v_user_resm_waitAdd')?>';
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
	
	
