<section class="column"  id="main" style='height:1500px;'>

	<article class="module width_full" >
	<header>
	<h3 class="h3_fontstyle">		
	<?php   echo '发送数和接收数报表' ?></h3>
		</header>
		<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php  echo '发送数';?></th> 
    				<th><?php  echo "接收数";?></th> 
    				</tr> 
			</thead> 
			<tbody id=''>
				<!--
				<?php 

			 	if(isset($pushrecords)):
				for($i=0;$i<count($pushrecords);$i++)
				{
			 		$row = $pushrecords[$i];

			 	?>
				<tr>
					
    				<tr><td><?php echo $row->push_title;?></td>
								<td><?php echo $row->push_content;?></td>
							<td><?php echo $row->push_time;?></td>
						<td><?php if ($row->push_type==1)echo "普通推送";else echo "透传推送";?></td>
					<td><a href ="<?php echo site_url()?>/plugin/getui/report/gettaskdata?taskid=<?php echo $row->taskid?>&appid=<?php echo $appid?>">详细</a></td>

    				
    			</tr> 
			<?php } endif;?>

			-->

						
			</tbody>
			</table>
	</article>


	
</section>

