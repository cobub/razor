<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_re_funnelModel');?></h3>
			<ul class="tabs">
				<li class="active"><a href="#tab1"><?php echo lang('v_rpt_re_funnelList');?></a></li>
				<li><a href="#tab2"><?php echo lang('v_rpt_re_funnelAdd');?></a></li>
			</ul>
		</header>
		<div class="tab_container">
			<div class="tab_content" id="tab1" style="display: block;">
				<table cellspacing="0" class="tablesorter">
					<thead>

						<tr>
							<th width="16%" class="header"><?php echo lang('v_rpt_re_funnelName');?></th>
							<th width="10%" class="header"><?php echo lang('v_rpt_re_unitprice');?></th>
							<th width="21%" class="header"><?php echo lang('v_rpt_re_funnelStartevent');?></th>
							<th width="21%" class="header"><?php echo lang('v_rpt_re_funnelTargetevent')?></th>
							<th width="21%" class="header"><?php echo lang('v_rpt_re_funnelConversionrate');?></th>
							<th width="21%" class="header"><?php echo lang('t_details');?></th>
						</tr>
					</thead>
					<tbody>
					<?php
					
					if (isset ( $result ) && ! empty ( $result )) {
						for($i = 0; $i < count ( $result ['tid'] ); $i ++) {
							?>
						<tr>
							<td><?php echo $result ['targetname'] [$i]?></td>
							<td><?php if(empty($result['unitprice'][$i])){echo 0;}else{echo $result['unitprice'][$i];}?></td>
							<td><?php echo $result ['event1'] [$i]?></td>
							<td><?php echo $result ['event2'] [$i]?></td>
							<td><?php if(empty($result['event2_c'])||empty($result['event1_c'])){
								echo 0;}else{
										$e1_c=$result['event1_c'][$i];
										$e2_c=$result['event2_c'][$i];
									if(($e1_c==0)||($e2_c==0)){
										echo 0;
									}else{
										echo round(($e2_c/$e1_c)*100,2);
									}
								}?>%</td>
							<td><a
								href="<?php echo site_url()?>/report/funnels/editFunnel/<?php echo $result['tid'][$i]?>">
									<img style="border: 0px" title="Edit"
									src="<?php echo base_url()?>/assets/images/icn_edit.png">
							</a> <a
								href="javascript:if(confirm('<?php echo lang('v_rpt_re_funnelmsgIsdelete');?>')){location.href='<?php echo site_url()?>/manage/funnels/deleteFunnel/<?php echo $result['tid'][$i]?>'}">
									<img style="border: 0px" title="Trash"
									src="<?php echo base_url()?>/assets/images/icn_trash.png" style="border:0;">
							</a></td>
						</tr>
						<?php
						}
					}
					?>
					
					</tbody>
				</table>
			</div>
			<style type="text/css">
.fieldset {
	width: 50%;
}

.fieldset input[type="text"] {
	margin: 0 5px;
}
</style>
			<div class="tab_content" id="tab2" style="display: none;">
				<form method="post" id="form_funnel" accept-charset="UTF-8">
					<input type="hidden" name="event_ids" /> <input type="hidden"
						name="step_names" /><input type="hidden" name="rand"
						value="<?php echo rand(time())?>" />
					<div class="module_content">
						<fieldset class="fieldset">
							<label><?php echo lang('v_rpt_re_funnelName');?></label> <input
								type="text" id="funnel_name" name="funnel_name">
							<label><?php echo lang('v_rpt_re_unitprice');?></label> <input
								type="text" id="unitprice" name="unitprice">
						</fieldset>
						<fieldset param="1" var="setp" id="fieldset1" class="fieldset">
							<label><a href="javascript:rmField(1);"><img src="<?php echo base_url()?>assets/images/jian.png" style="border:0;"/></a><?php echo lang('v_rpt_re_funnelStep');?>1</label>
							<select id="platform1" onchange="fillContent(this,1);">
							<?php if(isset($eventlist)){foreach ($eventlist->result() as $event){?>
							<option value="<?php echo $event->eventid?>"><?php echo $event->eventName?></option>
							<?php }}?>
						</select> <label><?php echo lang('v_rpt_re_funnelStepname');?></label> <input
								type="text" name="stepname" step="1" />
						</fieldset>
						<input type="button" onclick="addstep()" class="alt_btn"
							id="mark_id" value="<?php echo lang('v_rpt_re_funnelStepadd');?>" /> <input
							type="button" onclick="addfunnel()" class="alt_btn"
							value="<?php echo lang('g_submit');?>">
							<fieldset class="fieldset" id="msg" style="display: none;">
						</fieldset>
					</div>
				</form>
				<!-- end of post new article -->
			</div>
		</div>
	</article>
</section>

<script type="text/javascript">
$(function(){
	text=$('#platform1 option:selected').text();
	$('#fieldset1').find('input[name=stepname]').val(text);
});
	function addstep(){
		var field_length=$('#tab2 fieldset[var=setp]').length;
		if(field_length>=5){
			alert('<?php echo lang('v_rpt_re_funnelmsgMaxadd');?>');return;}
		var param=parseInt($('#tab2 fieldset[var=setp]:last').attr('param'));
		if(isNaN(param)){
			param=0}
		var index=param+1;
		var fieldset='';
		fieldset+='<fieldset param="'+index+'" var="setp" id="fieldset'+index+'" class="fieldset">';
		fieldset+='<label><a href="javascript:rmField('+index+');"><img src="<?php echo base_url()?>assets/images/jian.png" style="border:0"/></a><?php echo lang('v_rpt_re_funnelStep');?>'+index+'</label> <select id="platform'+index+'" onchange=\'fillContent(this,'+index+');\'>';
		<?php if(isset($eventlist)){foreach ($eventlist->result() as $event){?>
		fieldset+='<option value="<?php echo $event->eventid?>"><?php echo $event->eventName?></option>';
		<?php }}?>
		fieldset+='</select>';
		fieldset+='<label><?php echo lang('v_rpt_re_funnelStepname');?></label>';
		fieldset+='<input type="text" name="stepname" step="'+index+'"/>';
		fieldset+='</fieldset>';
		$('#mark_id').before($(fieldset));
		}
	function rmField(index){
		$('#fieldset'+index).remove();
		}

	function addfunnel(){
		if(!checkContent()){return;}
		event_ids='';
		step_names='';
		$.each($('.module_content select'),function(index,item){
			event_ids+=item.value+',';
			});
		$.each($('input[name=stepname]'),function(index,item){
			step_names +=item.value+',';
			});
		$('input[name=event_ids]').val(event_ids);
		$('input[name=step_names]').val(step_names);
		//loadAjax();
		$.post('<?php echo site_url()?>/report/funnels/addFunnel/'+new Date().getTime(),$('#form_funnel').serialize(),function(data,status){
			if('existsname'==data){
				$('#msg').html('<?php echo lang('v_rpt_re_funnelmsgExistsfunnelname');?>').show();
				setTimeout(function(){location.reload();},200);
				return false;
				}
			if('success'==data){
				$('#msg').html('<?php echo lang('v_rpt_re_funnelmsgAddsuccess');?>').show();
				setTimeout(function(){location.reload();},200);
			return;
				}
			if('error'==data){
				$('#msg').html('<?php echo lang('t_error');?>').show();setTimeout(function(){location.reload();},200);return;}
			if('max'==data){
				$('#msg').html('<?php echo lang('v_rpt_re_funnelmsgOutofmaxadd');?>').show();setTimeout(function(){location.reload();},200);return;}
			},'text');
		}
	function checkContent(){
		if($('#funnel_name').val()==''){
			alert('<?php echo lang('v_rpt_re_funnelmsgInputfunnelname');?>');return false;}
		if($('input[name=\'stepname\']').length<2){
			alert('<?php echo lang('v_rpt_re_funnelmsgAtleasttwostep');?>');return false;}
		var validateMoney=/^\d{0,10}(\.)?\d{0,3}$/;
		if(!validateMoney.test($('input[name=\'unitprice\']').val())){alert('<?php echo lang('v_rpt_re_unitprice_alt');?>');return false;}
		con=true;
		$.each($('input[name=\'stepname\']'),function(index,item){
			if(item.value==''){
				alert('<?php echo lang('v_rpt_re_funnelStep');?>'+$(item).attr('step')+'<?php echo lang('v_rpt_re_funnelmsgNotnull');?>');
				con=false;
				}
			});
		if(!con){return false;}
			var exist_events_hash = {};
			 $.each($('fieldset[var=setp] select option:selected'), function(i,e){
				    var event = $(e).text();
				    if( !exist_events_hash.hasOwnProperty(event) ){
				      exist_events_hash[event] = true;
				    }else{
				      alert('<?php echo lang('v_rpt_re_funnelmsgNotrepeatevent');?>: '+ event);
					con=false;
				    }});
		if(!con){return false;}
		return true;
		}
	function fillContent(t,index){
		text=$(t).find('option:selected').text();
		$('#fieldset'+index).find('input[name=stepname]').val(text);
		}
</script>