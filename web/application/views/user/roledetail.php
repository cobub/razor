<?php
//$roleid = $_GET['id'];
//$rolename = $_GET['name'];
?>


<section id="main" class="column">

<h4 class="alert_info" id='msg' style="display: none;"></h4> 

<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo  lang('v_user_rolem_role')?><?php echo $rolename?></h3>

</header>

<div class="tab_container">
<div id="tab1" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo  lang('v_user_resm_resourceN')?></th>
			<th><?php echo  lang('v_user_resm_resourceD')?></th>
			<th><?php echo  lang('v_user_rolem_accessP')?></th>
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
			<td><input type="checkbox" name='check_<?php echo $row->id?>'
				id='check_<?php echo $row->id?>'
				onClick='check(<?php echo $roleid?>,<?php echo $row->id?> )'
				<?php if ($row->read) {
    					echo 'checked';
    				}?>></td>
		</tr> 
			<?php } endif;?>
			
			</tbody>
</table>
</div>
<!-- end of #tab1 -->

<!--<div id="tab2" class="tab_content">-->
<!--<table class="tablesorter" cellspacing="0">-->
<!--	<thead>-->
<!---->
<!--		<tr>-->
<!--			<th>资源名称</th>-->
<!--			<th>资源描述</th>-->
<!--			<th>添加</th>-->
<!---->
<!--		</tr>-->
<!--	</thead>-->
<!--	<tbody>-->
<!--		<tr>-->
<!--			<td><input type="text" name='resourcename'></input></td>-->
<!--			<td><input type="text" name='resourcedescription'></input></td>-->
<!--			<td><input type="button" value='add'></td>-->
<!--		</tr>-->
<!--	</tbody>-->
<!--</table>-->

</div>
<!-- end of #tab2 --></div>
<!-- end of .tab_container -->

</article>
<!-- end of content manager article -->



<div class="clear"></div>
<div class="spacer"></div>
</section>

<script type="text/javascript">

function check(role, resource) {	
	var capability;	
	if (document.getElementById('check_'+resource).checked == true) {
		capability = 1;
		
	} else {
		capability = 0;		
	}	
	var data = {
			role : role,
			resource : resource,
			capability : capability
		};
		jQuery
				.ajax({
					type : "post",
					url : "<?php echo site_url()?>/user/modifyRoleCapability",
					data : data,
					success : function(msg) {
						document.getElementById('msg').innerHTML = "<?php echo  lang('v_user_rolem_mofifyS')?>";	
						document.getElementById('msg').style.display="block";					 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo  lang('t_error')?>");
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo  lang('v_user_rolem_waitmodify')?>';
						document.getElementById('msg').style.display="block";
					},
					complete : function() {
					}
				});
}
</script>


