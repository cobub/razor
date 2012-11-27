<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_re_funnelModifyfunnel');?></h3>
		</header>
		<div class="tab_container">
			<div class="tab_content" id="tab1">
				<form method="post" id="form_funnel" accept-charset="UTF-8">
					<input type="hidden" name="event_ids" /> <input type="hidden"
						name="step_names" /><input type="hidden" name="target_id" value="<?php if(isset($steplist)){echo $steplist->first_row()->tid;}?>" readonly="readonly"/>
					<div class="module_content">
					<style>
				fieldset{
	width:50%;
				}
fieldset input[type="text"] {
	margin: 0 5px;
}
		</style>
						<fieldset>
							<label><?php echo lang('v_rpt_re_funnelModifyfunnel');?></label> <input type="text" id="funnel_name"
								name="funnel_name"
								value="<?php if(isset($steplist)){echo $steplist->first_row()->targetname;}?>" />
							<label><?php echo lang('v_rpt_re_unitprice');?></label> <input
								type="text" id="unitprice" name="unitprice" value="<?php if(isset($steplist)){if(empty($steplist->first_row()->unitprice)){echo 0;}else{echo $steplist->first_row()->unitprice;}}?>">
						</fieldset>
						<?php if(isset($steplist)){foreach ($steplist->result() as $step){?>
						<fieldset param="<?php echo $step->sequence?>" var="setp"
							id="fieldset<?php  echo $step->sequence?>">
							<label>
							<a
								href="javascript:rmField(<?php echo $step->sequence?>,<?php echo $step->eventid?>,<?php echo $step->tid?>);"
								title="Remove"><img src="<?php echo base_url()?>assets/images/jian.png" style="border:0"/></a><?php echo lang('v_rpt_re_funnelStepadd');?><?php  echo $step->sequence?></label>
							 <input type="text" readonly="readonly" value="<?php echo $step->event_name?>" param="<?php echo $step->eventid?>" name="event_id"/>
							 <label><?php echo lang('v_rpt_re_funnelStepname');?></label> <input type="text" name="stepname"
								step="<?php echo $step->sequence?>"
								value="<?php echo $step->eventalias?>" />
						</fieldset>
						<?php }}?>
						<input type="button" onclick="modifyFunnel()"
							class="alt_btn" value="<?php echo lang('g_submit');?>">
							<fieldset style="display:none;" id="msg"></fieldset>
					</div>
				</form>
				<!-- end of post new article -->
			</div>
		</div>
	</article>
</section>

<script type="text/javascript">
	function rmField(index,eventid,targetid){
		if(!confirm('<?php echo lang('v_rpt_re_funnelmsgIsdelete') ?>')){return;}
		$.ajax({
			type:'post',
			url:'<?php echo site_url()?>/report/funnels/delteFunnelEvent',
			dataType:'json',
			data:{'event_id':eventid,'target_id':targetid},
			success:function(data,status){
				if('lt2'==data){
					alert('<?php echo lang('v_rpt_re_funnelmsgAtleasttwostep')?>');}
				if(data==1){	
				$('#fieldset'+index).remove();
					}
				}
			});
		}

	function modifyFunnel(){
		if(!checkContent()){return;}
		event_ids='';
		step_names='';
		$.each($('input[name=event_id]'),function(index,item){
			event_ids+=$(item).attr('param')+',';
			});
		$.each($('input[name=stepname]'),function(index,item){
			step_names +=item.value+',';
			});
		$('input[name=event_ids]').val(event_ids);
		$('input[name=step_names]').val(step_names);
		//loadAjax();
		$.post('<?php echo site_url()?>/report/funnels/modifyFunnel',$('#form_funnel').serialize(),function(data,status){
			$('#msg').html('<?php echo lang('v_rpt_re_funnelmsgModifysuccess')?>').show();
			setTimeout(function(){location.reload();},500);
			},'json');
		}
//	function loadAjax(){
//		$('.alt_btn:last').ajaxStart(function(){$(this).attr({'enable':'false'});});
//		}	
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
				return false;
				}
			});
		if(!con){return false;}
		return true;
		}
	function fillContent(t,index){
		text=$(t).find('option:selected').text();
		$('#fieldset'+index).find('input[name=stepname]').val(text);
		}
</script>