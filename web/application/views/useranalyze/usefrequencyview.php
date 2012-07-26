

<section id="main" class="column">
<h4 class="alert_success" id='msg'><?php echo  lang('allview_usefrequency')?></h4>

<!--<article class="module width_full">-->
<!--<header>-->
<!---->
<!--<h3 class="tabs_involved">使用频率</h4>-->
<!--</header>-->
<!--<div class="module_content">-->
<!---->
<!---->
<!---->
<!--<ul>-->
<!--	<li>使用频率会帮助您了解用户使用应用的频繁程度，您能清楚的掌握每日、每周、每月的用户使用次数分布情况。</li>-->
<!--</ul>-->
<!--</div>-->
<!--</article>-->


<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo  lang('userfrequency_headeinfo')?></h3>
<ul class="tabs" style="position:absolute; right:75px; padding:0;">
   			<li><a href="#tab1"><?php echo  lang('userfrequency_daynumtab')?></a></li>
    		<!--<li><a href="#tab2"><?php echo  lang('userfrequency_weeknumtab')?></a></li>
    		<li><a href="#tab3"><?php echo  lang('userfrequency_monthnumtab')?></a></li>
--></ul>
			<span class="relative r">                	
                	<a href="#this" class="bottun4" onclick="sever('server1','server1c1');"><font>?</font></a>
                	<div class="server333" id="server1" style="width:620px;">
                       <div class="ser_title">
                           <b class="l"><?php echo  lang('userfrequency_settitle')?></b>                          
                           <a class="r" href="#this" id="server1c1"><img src="<?php echo base_url(); ?>assets/images/server_close.gif" /></a>
                       </div>
                       
                                <style>
								.ser_txt font{
									width:70px
								}
								</style>
                       <div class="ser_txt">
                           <dl>
                               <dt><?php echo  lang('userfrequency_reminderinfo')?>
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
			<th><?php echo  lang('userfrequency_daystartthead')?></th>
			<th><?php echo  lang('userfrequency_percentthead')?></th>
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
			<th><?php echo  lang('userfrequency_weekstartthead')?></th>
			<th><?php echo  lang('userfrequency_weekperthead')?></th>
			<th></th>
			
		</tr>
	</thead>
	<tbody>
		<tr>			
			<td>1-2</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>3-5</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>6-9</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>10-19</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>20-49</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>50-99</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>100+</td>			
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
			<th><?php echo  lang('userfrequency_monthdaythead')?></th>
			<th><?php echo  lang('userfrequency_monthperthead')?></th>
			<th></th>
			
		</tr>
	</thead>
	<tbody>
		<tr>			
			<td>1-2</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>3-5</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>6-9</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>10-19</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>20-49</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>50-99</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>100-199</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>200-299</td>			
			<td><div style="background-color: rgb(116, 119, 213); height: 15px; width: 393px;"></div></td>
		    <td>20%</td>			
		</tr>
		<tr>			
			<td>300+</td>			
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


