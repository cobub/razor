<section id="main" class="column" style="height:800px">
  <div style="height:470px;">
		<iframe src="<?php echo site_url() ?>/report/usefrequency/addsessiondistributionreport"  frameborder="0" scrolling="no"style="width:100%;height:100%;"></iframe>		
  </div>
  <?php  if(isset($comparecontent)){?>
  <article class="module width_full">
		<header>
			<h3 class="h3_fontstyle">
			<?php echo  lang('v_rpt_uf_distribution')?></h3>
			<span class="relative r"> <a
				href="<?php echo site_url()?>/report/usefrequency/exportCompareUsefrequency" class="bottun4 hover"><font><?php echo  lang('g_exportToCSV')?></font></a>
			</span>	
			</header>
		<div id="container" class="tab_content">
		<table class="tablesorter" cellspacing="0"> 
			<thead><?php  if(isset($comparetitlecontent)){?>
			    <tr>
			      <th width="10%"></th>
			      <?php foreach ($comparetitlecontent as $row){?>
			      <th width="15%"><?php echo $row['segment_name']?></th><?php } ?>
			    </tr> <?php } ?>
			</thead>
			<tbody><?php echo $comparecontent?></tbody> 
			</table>
		</div>
	</article>
	<?php }?>
	
</section>