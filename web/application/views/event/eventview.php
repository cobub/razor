<section id="main" class="column">
		<?php if(isset($message)):?>
		<h4 class="alert_success"><?php echo $message;?></h4>
		<?php else:?>
			<h4 id='msg' class="alert_success"><?php echo lang('eventview_alertinfo') ?></h4>
		<?php endif;?>		
      <div class="submit_link"><?php echo lang('allview_choosetime') ?>
					<input type="text" id="dpFrom">
					<input type="text" id="dpTo">
					<input type="submit" value="<?php echo lang('allview_timebtn') ?>" class="alt_btn">					
					<select>
						<option><?php echo lang('allview_lastweek') ?></option>
						<option><?php echo lang('allview_lastmonth') ?></option>
						<option><?php echo lang('allview_last3month') ?></option>
						<option><?php echo lang('allview_alltime') ?></option>
					</select>
				</div>
     <header><h3 id='msg' class="alert_success"><?php echo lang('eventview_headertitle') ?></h3>	
		<article class="module width_full">
			
			<ul class="tabs2">
   			<li><a href="#message"><?php echo lang('eventview_messagenum') ?></a></li>
    		<li><a href="#startuser"><?php echo lang('eventview_eventstartuser') ?></a></li>
    		<li><a href="#startnumber"><?php echo lang('eventview_eventstartnum') ?></a></li>  	
			</ul>
			</header>
			<div class="tab_container">
				<div id="message" class="tab_content">
					<div class="module_content">
						<article class="width_full">
							<?php if(isset($chartmessage)):?>
							<?php echo $chartmessage;?>
							<?php endif;?>
						</article>
				
						<div class="clear"></div>
					</div>
				</div>
				
				<div id="startuser" class="tab_content">
					<div class="module_content">
						<article class="width_full">
							<?php if(isset($chartstartuser)):?>
							<?php echo $chartstartuser;?>
							<?php endif;?>
						</article>
				
						<div class="clear"></div>
					</div>
				</div>
				
				<div id="startnumber" class="tab_content">
					<div class="module_content">
						<article class="width_full">
							<?php if(isset($chartstartnumber)):?>
							<?php echo $chartstartnumber;?>
							<?php endif;?>
						</article>
				
						<div class="clear"></div>
					</div>
				</div>
		</article>
		<div class="clear"></div>
		<div class="spacer"></div>
		
		<article class="module width_full">
		</article>
		<div class="clear"></div>		
		
</section>
	
<script>
//When page loads...
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(".tab_content").hide(); //Hide all tab content
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});


</script>

<script>
	$(function() {
		$("#dpFrom" ).datepicker();
	});
	$( "#dpFrom" ).datepicker({ dateFormat: "yy-mm-dd" });
	$(function() {
		$( "#dpTo" ).datepicker();
	});
	$( "#dpTo" ).datepicker({ dateFormat: "yy-mm-dd" });
	</script>
	
<script type="text/javascript">
function switchTimePhase(timePhase)
{
		jQuery.ajax({
					type : "post",
					url : "<?php echo site_url()?>/product/getTypeAnalyzeData/"+timePhase,
					success : function(msg) {
						document.getElementById('msg').innerHTML = "<?php echo lang('eventview_scriptloadmsg') ?>";	
						loaddata(msg);								
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						document.getElementById('msg').innerHTML = "<?php echo lang('eventview_scripterrormsg') ?>";
						
					},
					beforeSend : function() {
						document.getElementById('msg').innerHTML = '<?php echo lang('eventview_scriptwaitmsg') ?>';

					},
					complete : function() {
					}
				});
}

</script>


<script type="text/javascript">

function loaddata(data)
{
	var swf = findSWF("chartStartUser");
	swf.load(data);
}
function findSWF(movieName)
{
  if (navigator.appName.indexOf("Microsoft")!= -1) {
    return window[movieName];
  } else {
    return document[movieName];
  }
}
</script>



