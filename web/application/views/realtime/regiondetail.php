<style type="text/css">
	.dv-table td{
		border:0;
	}
	.dv-label{
		font-weight:bold;
		color:#15428B;
		width:100px;
	}
</style>

<table class="dv-table datagrid" border="0" style="width:100%;">
	 <thead>
            <tr  class="datagrid-row">
                <th  class = "datagrid-cell" field="regionName" width="50%"><?php echo lang('v_rpt_realtime_areas_region');?></th>
                <th  class = "datagrid-cell" field="regionSize" width="50%"><?php echo lang('v_rpt_realtime_onlineuser_size');?></th>
            </tr>
        </thead>
        <tbody>
        	<?php if($regions && count($regions)>0):?>
        	<?php for($i=0;$i<count($regions);$i++) { $region = $regions[$i];?>
			<tr class="datagrid-row">
				<td class = "datagrid-cell"><?php echo $region['regionName']; ?></td>
				<td class="datagrid-cell"><?php echo $region['regionSize']; ?></td>
			</tr>
			<?php } ?>
			<?php endif;?>
		</tbody>
</table>
