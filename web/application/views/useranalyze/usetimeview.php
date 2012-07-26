<section id="main" class="column">
<h4 class="alert_success" id='msg'><?php echo  lang('usetimeview_alertinfo')?></h4>

<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo  lang('usetimeview_headeinfo')?></h3>
<ul class="tabs" style="position:absolute; right:75px; padding:0;">
   			<li><a href="#tab1"><?php echo  lang('usetimeview_singletimetab')?></a></li>
<!--    		<li><a href="#tab2">日使用时长</a></li>-->
<!--    		<li><a href="#tab3">周使用时长</a></li>-->
</ul>
<span class="relative r">                	
      <a href="#this" class="bottun4" onclick="sever('server1','server1c1');"><font>?</font></a>
           <div class="server333" id="server1" style="width:620px;">
               <div class="ser_title">
                     <b class="l"><?php echo  lang('usetimeview_remindinfo')?></b>                          
                      <a class="r" href="#this" id="server1c1"><img src="<?php echo base_url(); ?>assets/images/server_close.gif" /></a>
                   </div>
                       
                                <style>
								.ser_txt font{
									width:70px
								}
								</style>
                       <div class="ser_txt">
                           <dl>
                               <dt><?php echo  lang('usetimeview_reminddetailinfo')?>
                               </dt>
                           </dl>
                       </div>
                	</div>
                </span>		

</header>

<div id="tab1" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo  lang('usetimeview_singleth')?></th>
			<th><?php echo  lang('usetimeview_percentth')?></th>
			<th></th>
			
		</tr>
	</thead>
	<tbody>
	    <?php 
	        foreach ($data->result() as $row)
	        {
	    ?>
	    <tr>			
			<td><?php echo $row->segment_name?></td>			
			<td><div style="background-color:<?php echo BLOCK_COLOR?>; height: <?php echo BLOCK_HEIGHT?>; width: <?php echo BLOCK_MAX_LENGTH*$row->percentage.'px'?>;"></div></td>
		    <td><?php echo 100*$row->percentage."%"?></td>			
		</tr>
		<?php 
	        }
		?>
		

	</tbody>
</table>
</div>

<div id="tab2" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th>日使用时长</th>
			<th>比例</th>
			<th></th>
			
		</tr>
	</thead>
	<tbody>
		<tr>			
			<td>1-3秒</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 193px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>3-10秒</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>10-30秒</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 93px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>30-60</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>1-3分钟</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>3-10分钟</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>10-30分钟</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>30分钟以上</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>

	</tbody>
</table>
</div>

<div id="tab3" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th>周使用时长</th>
			<th>比例</th>
			<th></th>
			
		</tr>
	</thead>
	<tbody>
		<tr>			
			<td>1-3秒</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 193px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>3-10秒</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>10-30秒</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 93px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>30-60</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>1-3分钟</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>3-10分钟</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 53px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>10-30分钟</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>30-60分钟</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>60-90分钟</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>120分钟以上</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>

	</tbody>
</table>
</div>
</article>


</section>

<script type="text/javascript">

function sever(txt, colse) {
	$("#" + txt).slideToggle("slow");
	$("#" + colse).click(function() {
		$("#" + txt).slideUp("slow");
	});
	
}

</script>



