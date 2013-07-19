<?php
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */
?>
<section id="main" class="column" style="height:1000px">
<div style="height:380px;">
  <iframe src="<?php echo site_url() ?>/report/funnels/addconversionsreport"  frameborder="0" scrolling="no"style="width:100%;height:100%;"></iframe>		
</div>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_re_funnelModel');?></h3>
			<?php if(isset($common)){?>
			<ul class="tabs3" id="tabs3">
				<li class="active"><a href="javascript:show('count',0);"><?php echo lang('v_rpt_re_funneleventC');?></a></li>
				<li><a href="javascript:show('value',1);"><?php echo lang('v_rpt_re_unitprice');?></a></li>
				<li><a href="javascript:show('conversion',2);"><?php echo lang('v_rpt_re_funnelConversionrate');?></a></li>
			</ul>
			<span class="relative r"> <a class="bottun4 hover" href="<?php echo site_url()?>/report/funnels/exportComparedata"><font>导出CSV</font></a>
			</span>
			<?php }?>
		</header>
		<div class="tab_container">
			<div class="tab_content" id="tab1" style="display: block;">
				<table cellspacing="0" class="tablesorter">
					<thead>
						<tr>
							<?php if(!isset($common)){?>
							<th width="16%" class="header"><?php echo lang('v_rpt_re_funnelName');?></th>
							<th width="16%" class="header"><?php echo lang('v_rpt_re_unitprice');?></th>
							<th width="21%" class="header"><?php echo lang('v_rpt_re_funnelStartevent');?></th>
							<th width="21%" class="header"><?php echo lang('v_rpt_re_funnelTargetevent')?></th>
							<th width="21%" class="header"><?php echo lang('v_rpt_re_funnelConversionrate');?></th>
							<th width="21%" class="header"><?php echo lang('t_details');?></th>
							<?php }else{?>
							<th width="16%" class="header"><?php echo lang('g_date');?></th>	
							<th class="header" colspan="<?php echo count($result)*3;?>"><?php echo lang('v_app');?></th>	
							<?php }?>
						</tr>
					</thead>
					<tbody id="tbcontent">
					<?php
					if (isset ( $result ) && ! empty ( $result )) {
						if(isset($common)&&'compare'==$common['type']){//load compare data
							?>
						<tr>
							<td width="16%" class="header" rowspan="2">&nbsp;</td>
							<?php for($i=0;$i<count($result);$i++){?>
							<td colspan="2"><?php echo $result[$i]['name'];?></td>
							<?php }?>
						</tr>
						<tr>
							<?php for($i=0;$i<count($result);$i++){?>
							<td><?php echo lang('v_rpt_re_funneleventC');?></td>
							<td><?php echo lang('v_rpt_re_unitprice');?></td>
							<?php }?>
						</tr>
						<?php foreach($result[0]['date'] as $key=>$value){?>
						<tr>
						<td><?php echo $key?></td>
						<?php
							for($i=0;$i<count($result);$i++){
								$r=$result[$i];
								$date=$r['date'];
								foreach ($date as $k=>$v){
									if($key==$k){
										?>
										<td><?php echo $date[$key]?></td>
										<td style="padding-left: 2%"><?php if(!isset($r['unitprice'])){echo 0;}else{echo number_format($r['unitprice'][0][$key],2);}?></td>
										<?php
									}
								}
							}
							?>
						</tr>
						<?php }?>
					<?php }else{for($i = 0; $i < count ( $result ['tid'] ); $i ++) {?>
						<tr>
							
								<td><?php echo $result ['targetname'] [$i]?></td>
								<td><?php if(empty($result ['unitprice'][$i])){echo 0;}else{echo $result ['unitprice'][$i];}?></td>
								<td><?php echo $result ['event1'] [$i]?></td>
								<td><?php echo $result ['event2'] [$i]?></td>
								<td><?php echo round((($result['event2_c'][$i])/($result['event1_c'][$i]))*100,1)?>%</td>
								<?php if(!isset($common)){
								?>
								<td><a
									href="<?php echo site_url()?>/report/funnels/viewDetail/<?php echo $result['tid'][$i]?>">
										<img style="border: 0px" title="View"
										src="<?php echo base_url()?>/assets/images/icn_search.png">
								</a></td>
								<?php }?>
							</tr>
						<?php }
							}
						}?>
					</tbody>
				</table>
			</div>
		</div>
	</article>
<script type="text/javascript">
$(function(){
	<?php if(isset($common)&&'compare'==$common['type']){?>
		show('count',0);
	<?php }?>
});
//type:count,value,conversion
var show=function(type,i){
	$.each($('#tabs3 li'),function(index,item){
		if(index==i){
			$(this).addClass('active');
		}else{
			$(this).removeClass('active');
		}
	});
	var iscompare='<?php if(isset($common)&&'compare'==$common['type']){echo true;}else{echo false;}?>';
	var tbc='';//count
	var tbu='';//value
	var tbf='';//conversion
	tbc+='<tr>';
	tbc+='<td width="16%" class="header" rowspan="2">&nbsp;</td>';
	<?php if(isset($common)&&'compare'==$common['type']){for($i=0;$i<count($result);$i++){?>
	tbc+='<td><?php echo $result[$i]['name'];?></td>';
	tbu=tbf=tbc;
	<?php }?>
	tbc+='</tr><tr>';
	tbu+='</tr><tr>';
	tbf+='</tr><tr>';
	<?php for($i=0;$i<count($result);$i++){?>
	tbc+='<td><?php echo lang('v_rpt_re_funneleventC');?></td>';
	tbu+='<td><?php echo lang('v_rpt_re_unitprice');?></td>';
	tbf+='<td><?php echo lang('v_rpt_re_funnelConversionrate');?></td>';
	<?php }?>
	tbc+='</tr>';
	tbu+='</tr>';
	tbf+='</tr>';
	//
	<?php foreach($result[0]['date'] as $key=>$value){?>
	tbc+='<tr><td><?php echo $key?></td>';
	tbu+='<tr><td><?php echo $key?></td>';
	tbf+='<tr><td><?php echo $key?></td>';
	<?php
		for($i=0;$i<count($result);$i++){
			$r=$result[$i];
			$date=$r['date'];
			$seventdate=$r['scount'];
			foreach ($date as $k=>$v){
				if($key==$k){?>
					tbc+='<td><?php echo $date[$key]?></td>';
					tbu+='<td style="padding-left: 2%"><?php if(!isset($r['unitprice'])){echo 0;}else{echo number_format($r['unitprice'][0][$key],2);}?></td>';
					tbf+='<td><?php 
						if(empty($seventdate[$key])||empty($date[$key])||$date[$key]==0||$seventdate[$key]==0){
							echo 0;
						}else{
							echo number_format(($seventdate[$key]/$date[$key])*100);
						}?>%</td>';
					<?php
				}
			}
		}
	}
}
		?>
	tbc+='</tr>';
	tbu+='</tr>';
	tbf+='</tr>';
	if('count'==type){
		$('#tbcontent').html(tbc);
	}
	if('value'==type){
		$('#tbcontent').html(tbu);
	}
	if('conversion'==type){
		$('#tbcontent').html(tbf);
	}
}
</script>
</section>