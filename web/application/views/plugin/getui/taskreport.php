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

				<tr><td><?php if(isset($sendnum)) echo $sendnum;?></td><td><?php if(isset($receivenum)) echo $receivenum;?></td></tr>
				
				
						
			</tbody>
			</table>
	</article>


	
</section>

