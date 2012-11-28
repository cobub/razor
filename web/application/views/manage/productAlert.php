<section id="main" class="column">
		
		<h4 class="alert_info" id="msg"  style="display: none"></h4> 
		<article class="module width_full">
		<header><h3 class="tabs_involved"><?php echo lang('v_man_ev_productalter') ?></h3>
		<ul class="tabs">
   			<li><a href="#tab1" onclick="refreshEvent()"><?php echo lang('v_man_ev_productAlertList') ?></a></li>
    		  <li><a href="#tab2"><?php echo lang('v_man_ev_addAlertlab') ?></a></li>
		</ul>
		</header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
				    <th><?php echo lang('v_rpt_el_alertlab') ?></th> 				    
    				<th><?php echo lang('v_rpt_el_condition') ?></th>     				     				
    			
    				<th><?php echo lang('v_man_ev_editalertlab') ?></th>
    				<th></th>
    				
				</tr> 
			</thead> 
			<tbody> 
			
			 <?php if(isset($alertList)):
			 	foreach($alertList->result() as $row)
			 	{
			 ?>
				<tr> 
				    <td><?php echo lang($row->label);?></td> 
    				<td>+/-<?php echo $row->condition;?>%</td> 
    								    					
    					<td>
    					<?php echo anchor('/manage/alert/editAlert/'.$row->label.'/'.$row->condition, lang('g_edit'));?>
    					<?php if ($row->active==1) 
    					{ 
    						echo anchor('/manage/alert/delAlert/'.$row->label.'/'.$row->condition,lang('m_delete'));
    					}
    					?>
    				</td> 
    					</td> 				 
				</tr> 
			<?php } endif;?>									
			
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->		  	
			<div id="tab2" class="tab_content">								
				<div class="module_content">
						<fieldset>
							<label><?php echo lang('v_rpt_el_alertlab') ?></label>
							<select id="myselect" >
								<option value="t_newUser"><?php echo lang('t_newUser') ?></option>
								<option value="t_activeUser"><?php echo lang('t_activeUser') ?></option>
								<option value="t_sessions"><?php echo lang('t_sessions') ?></option>
								<option value="t_accumulatedUsers"><?php echo lang('t_accumulatedUsers') ?></option>
								<option value="t_averageUsageDuration"><?php echo lang('t_averageUsageDuration') ?></option>
							</select>
							
						<!--  	<input type="text" id='exceptionlab' value='<?php if(isset($alertlist)) echo $alertlist['label']?>'>-->
						</fieldset>
						<fieldset>
							<label style="width: 10%; "><?php echo lang('v_rpt_el_condition') ?></label>
							<label style="width: 1%;margin-left: 0 px">+/-</label>
							<input style="width: 5%; margin-left: 5 px" type="text" id='condition'  value='<?php  if(isset($alertlist)) echo $alertlist['condition']?>'>
						    <label style="width: 1%; margin-left: 0 px">%</label>
						</fieldset>
						
						<fieldset>
							<label style="width: 10%; "><?php echo lang('v_rpt_el_email') ?></label>
							<input style="width: 35%; margin-left: 5 px" type="text" id='emailstr'  value='<?php  if(isset($alertlist)) echo $alertlist['emails']?>'>
							<label style="width: 35%; margin-left: 5 px"><?php echo lang('v_rpt_el_note')?></label>
						</fieldset>
						
						<input id="addButton" type="button" value="<?php echo lang('v_rpt_el_add') ?>" class="alt_btn" onClick='addEvent()'>
				</div>
			
				
			
		<!-- end of post new article -->
			
		

			</div><!-- end of #tab2 -->
			
		</div><!-- end of .tab_container -->
		
		</article><!-- end of content manager article -->
		<div class="clear"></div>
		<div class="spacer"></div>
	</section>
	
	<script type="text/javascript">
var isAddEvent="false";

function refreshEvent(){
	//window.location.reload();
	if(isAddEvent){
	}	
}

function addEvent() {var select = document.getElementById("myselect");
var index = select.selectedIndex;
exceptionlab = select.options[index].value;
condition = trim(document.getElementById('condition').value);
emailstr = trim(document.getElementById('emailstr').value);
var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）——|{}【】‘；：”“'。，、？]");
if(exceptionlab=='')
{
	document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_rpt_el_enterEventN') ?></font>";
	document.getElementById('msg').style.display="block";
	return;

}
for (var i = 0; i < exceptionlab.length; i++) {
	if(pattern.test(exceptionlab.substr(i, 1))){
		document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_man_ev_errorInputEI') ?></font>";
		document.getElementById('msg').style.display="block";
		return;
		}
}
if(condition=='')
{
	document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_rpt_el_noteofcondition') ?></font>";
	document.getElementById('msg').style.display="block";
	return;

}
if(emailstr=='')
{
	document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_rpt_el_noteofemail') ?></font>";
	document.getElementById('msg').style.display="block";
	return;

}
for (var i = 0; i < condition.length; i++) {
	if(pattern.test(condition.substr(i, 1))){
		document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('v_man_ev_errorInputEN') ?></font>";
		document.getElementById('msg').style.display="block";
		return;
		}
}
var data = {
		
		exceptionlab : exceptionlab,
		condition : condition,
		emailstr : emailstr
		
	};
	jQuery.ajax({
				type : "post",
				url : "<?php echo site_url()?>/manage/alert/addalertlab",
				data : data,
				success : function(msg) {
					if(!msg){
						document.getElementById('msg').innerHTML = "<font color=red><?php echo lang('t_error') ?></font>";
						document.getElementById('msg').style.display="block";
					}else{
					document.getElementById('msg').innerHTML = "<?php echo lang('v_rpt_addok') ?>";	
					document.getElementById('msg').style.display="block";
					window.location.href="<?php echo site_url()?>/manage/alert/";
					}			 
				},
				error : function(XmlHttpRequest, textStatus, errorThrown) {
					alert("<?php echo lang('t_error') ?>");
				},
				beforeSend : function() {
					document.getElementById('msg').innerHTML = "<?php echo lang('v_rpt_addlab') ?>";
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
	
