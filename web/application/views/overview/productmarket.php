<section id="main" class="column">
<div style="height:420px;">
		<iframe src="<?php echo site_url() ?>/report/market/addchannelmarketreport"  frameborder="0" scrolling="no"style="width:100%;height:100%;"></iframe>		
	</div>
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_mk_channelList') ?></h3>		
		</header>
		<table class="tablesorter" cellspacing="0">
			<thead>
				<tr>
					<th><?php echo lang('v_man_au_channelName') ?></th>
					<th><?php echo lang('v_rpt_mk_newToday') ?></th>
					<th><?php echo lang('v_rpt_mk_newYesterday') ?></th>
					<th><?php echo lang('v_rpt_mk_activeToday') ?></th>
					<th><?php echo lang('v_rpt_mk_activeYesterday') ?></th>
					<th><?php echo lang('t_accumulatedUsers') ?></th>
					<th><?php echo lang('t_activeRateWeekly') ?></th>
					<th><?php echo lang('t_activeRateMonthly') ?></th>
					<!--  th>时段内新增（%）</th>-->

				</tr>
			</thead>
			<tbody>
	<?php 
	$todayDataArray = $todayData->result_array();
	$yestaodayDataArray = $yestodayData->result_array();
	$sevenDayActive = $sevendayactive->result_array();	
	$thirtyDayActive = $thirty_day_active->result_array();	
	for ($i=0;$i<$count;$i++)
	{?>
		<tr>
					<td><?php echo $todayDataArray[$i]['channel_name']?></td>
					<td><?php echo $todayDataArray[$i]['newusers']
	?></td>
					<td><?php echo $yestaodayDataArray[$i]['newusers']?></td>
					<td><?php echo $todayDataArray[$i]['startusers']?></td>
					<td><?php echo $yestaodayDataArray[$i]['startusers']?></td>
					<td><?php echo $todayDataArray[$i]['allusers']?></td>
					<td><?php if(empty($sevenDayActive[$i]['percent'])){echo '0.0%';}
					else{ echo round($sevenDayActive[$i]['percent']*100,1).'%';}?></td>
					<td><?php if(empty($thirtyDayActive[$i]['percent'])){echo '0.0%';}
					else{ echo round($thirtyDayActive[$i]['percent']*100,1).'%';}?></td>
					<!--  td><?php // echo ($new_user_time_phase[$i]*100)."%" ; ?></td>-->
				</tr>
		<?php }?>

	</tbody>
		</table>
	</article>	
</section>