<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8"/>
	<title><?php echo lang('installview_installheader') ;?></title>
	
	<link rel="stylesheet" href="<?php echo $newurl ?>/assets/css/layout.css" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
    <script src="<?php echo base_url();?>assets/js/tag/jquery-1.9.1.js"
	type="text/javascript"></script>	<script src="<?php echo $newurl ?>/assets/js/hideshow.js" type="text/javascript"></script>
	<script src="<?php echo $newurl ?>/assets/js/jquery.tablesorter.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo $newurl ?>/assets/js/jquery.equalHeight.js"></script>
	<script type="text/javascript">
	$(document).ready(function() 
    	{ 
      	  $(".tablesorter").tablesorter(); 
   	 } 
	);
	$(document).ready(function() {

	//When page loads...
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content

	//On Click Event
	$("ul.tabs li").click(function() {

		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab+ content
		$(activeTab).fadeIn(); //Fade in the active ID content
		return false;
	});

});
    </script>
    <script type="text/javascript">
    $(function(){
        $('.column').equalHeight();
    });
</script>

</head>


<body>
	<header id="header">
		<hgroup>
	<h1 class="site_title" style="width:70%"><a href="http://dev.cobub.com" target="_blank"><img   class="logo" src="<?php echo $newurl.'/assets/images/razorlogo.png'?>"/><span style="vertical-align: top;"><?php echo lang('installview_installheader') ;?></span></a></h1>			
		</hgroup>
	</header> 	
			<section id="main" class="column" style="width:100%">
		<article class="module width_full">
			<header><h3><?php echo lang('installview_websiteheader') ;?></h3></header>
	<div>			
    <span id="imgtitle"><a href="http://dev.cobub.com" target="_blank"><img src="<?php if(isset($language)):
    if($language=="zh_CN"){ echo $newurl.'/assets/images/ecobubrazorlogo.png';}
    else{echo $newurl.'/assets/images/ecobubrazorlogo.png';} endif;?>"/></a></span>
     </div>
     <hr style="color:#9FA0A2;" />
     	
			<div>
			<div  style="height:30px;"></div>			
			<h3><font color="#123901"><?php echo lang('installview_checkstep') ;?></font></h3>			
			<h3><font color="#123901"><?php echo lang('installview_databasestep') ;?></font></h3>			
			<h3><font color="#990000"><?php echo lang('installview_websitestep') ;?></font></h3>
			<h3><font color="#9d9d9d"><?php echo lang('installview_finshstep') ;?></font></h3>		
			</div>
		 
			<div style="position:absolute;top:280px;left:420px;">
			<P align="left" class="STYLE5"><?php echo lang('installview_websiteheader') ;?></P>	
			<form action="<?php echo $newurl; ?>/index.php?/install/installation/createuserinfo" method="post">
             <table class="tablesorter" cellspacing="0">
                 <tr><td><?php echo lang('installview_userurl') ;?></td>
                <td><input name='siteurl' type="text" value="<?php echo set_value('siteurl');?>"></input></td><td><label><?php echo lang('installview_userurlreminder'); ?></label></td>               
                <td><?php echo form_error('siteurl'); ?></td></tr>  
                
                 <tr><td><?php echo lang('installview_timezones') ;?></td>
                <td><select name="webtimezones" id="webtimezones" style="width:160px">
               <option value="UM12" <?php if ($webtimezones == 'UM12') echo 'selected="selected"'; ?> >UTC -12:00</option>
                <option value="UM11" <?php if ($webtimezones == 'UM11') echo 'selected="selected"'; ?>>UTC -11:00</option>
                 <option value="UM10" <?php if ($webtimezones == 'UM10') echo 'selected="selected"'; ?> >UTC -10:00</option>
                <option value="UM95" <?php if ($webtimezones == 'UM95') echo 'selected="selected"'; ?>>UTC -9:30</option>
                <option value="UM9" <?php if ($webtimezones == 'UM9') echo 'selected="selected"'; ?> >UTC -9:00</option>
                <option value="UM8" <?php if ($webtimezones == 'UM8') echo 'selected="selected"'; ?>>UTC -8:00</option>
                 <option value="UM7" <?php if ($webtimezones == 'UM7') echo 'selected="selected"'; ?> >UTC -7:00</option>
                <option value="UM6" <?php if ($webtimezones == 'UM6') echo 'selected="selected"'; ?>>UTC -6:00</option>
                <option value="UM5" <?php if ($webtimezones == 'UM5') echo 'selected="selected"'; ?> >UTC -5:00</option>
                <option value="UM45" <?php if ($webtimezones == 'UM45') echo 'selected="selected"'; ?>>UTC -4:30</option>
                 <option value="UM4" <?php if ($webtimezones == 'UM4') echo 'selected="selected"'; ?> >UTC -4:00</option>
                <option value="UM35" <?php if ($webtimezones == 'UM35') echo 'selected="selected"'; ?>>UTC -3:30</option>
                <option value="UM3" <?php if ($webtimezones == 'UM3') echo 'selected="selected"'; ?> >UTC -3:00</option>
                <option value="UM2" <?php if ($webtimezones == 'UM2') echo 'selected="selected"'; ?>>UTC -2:00</option>
                 <option value="UM1" <?php if ($webtimezones == 'UM1') echo 'selected="selected"'; ?> >UTC -1:00</option>
                 <option value="UTC" <?php if ($webtimezones == 'UTC') echo 'selected="selected"'; ?>>UTC</option>
                <option value="UP1" <?php if ($webtimezones == 'UP1') echo 'selected="selected"'; ?> >UTC +1:00</option>
                <option value="UP2" <?php if ($webtimezones == 'UP2') echo 'selected="selected"'; ?>>UTC +2:00</option>
                 <option value="UP3" <?php if ($webtimezones == 'UP3') echo 'selected="selected"'; ?> >UTC +3:00</option>
                <option value="UP35" <?php if ($webtimezones == 'UP35') echo 'selected="selected"'; ?>>UTC +3:30</option>
                <option value="UP4" <?php if ($webtimezones == 'UP4') echo 'selected="selected"'; ?> >UTC +4:00</option>
                <option value="UP45" <?php if ($webtimezones == 'UP45') echo 'selected="selected"'; ?>>UTC +4:30</option>
                 <option value="UP5" <?php if ($webtimezones == 'UP5') echo 'selected="selected"'; ?> >UTC +5:00</option>
                <option value="UP55" <?php if ($webtimezones == 'UP55') echo 'selected="selected"'; ?>>UTC +5:30</option>
                <option value="UP575" <?php if ($webtimezones == 'UP575') echo 'selected="selected"'; ?> >UTC +5:45</option>
                <option value="UP6" <?php if ($webtimezones == 'UP6') echo 'selected="selected"'; ?>>UTC +6:00</option>
                 <option value="UP65" <?php if ($webtimezones == 'UP65') echo 'selected="selected"'; ?> >UTC +6:30</option>
                <option value="UP7" <?php if ($webtimezones == 'UP7') echo 'selected="selected"'; ?>>UTC +7:00</option>
                <option value="UP8" <?php if ($webtimezones == 'UP8') echo 'selected="selected"'; ?>>UTC +8:00</option>
                <option value="UP875" <?php if ($webtimezones == 'UP875') echo 'selected="selected"'; ?> >UTC +8:45</option>
                <option value="UP9" <?php if ($webtimezones == 'UP9') echo 'selected="selected"'; ?>>UTC +9:00</option>
                <option value="UP95" <?php if ($webtimezones == 'UP95') echo 'selected="selected"'; ?>>UTC +9:30</option>
                 <option value="UP10" <?php if ($webtimezones == 'UP10') echo 'selected="selected"'; ?> >UTC +10:00</option>
                <option value="UP105" <?php if ($webtimezones == 'UP105') echo 'selected="selected"'; ?>>UTC +10:30</option>
                <option value="UP11" <?php if ($webtimezones == 'UP11') echo 'selected="selected"'; ?>>UTC +11:00</option>
                <option value="UP115" <?php if ($webtimezones == 'UP115') echo 'selected="selected"'; ?>>UTC +11:30</option>
                <option value="UP12" <?php if ($webtimezones == 'UP12') echo 'selected="selected"'; ?> >UTC +12:00</option>
                </select></td><td></td></tr>
                             
             	<tr><td><?php echo lang('installview_usersupperaccount') ;?></td><td><input name='superuser' value="<?php echo set_value('superuser'); ?>"></input></td>
             	<td><?php echo form_error('superuser'); ?></td></tr>
                <tr><td><?php echo lang('installview_userpwd') ;?></td><td><input name='pwd' type="password" value="<?php echo set_value('pwd'); ?>"></input></td>
                <td><?php echo form_error('pwd'); ?></td></tr>
                <tr><td><?php echo lang('installview_userconfirmpwd') ;?></td><td><input name='verifypassword' type="password" value="<?php echo set_value('verifypassword'); ?>"></input></td>
                <td><?php echo form_error('verifypassword'); ?></td></tr>
                <tr><td><?php echo lang('installview_useremail') ;?></td><td><input name='email' value="<?php echo set_value('email'); ?>"></input></td>
                <td><?php echo form_error('email'); ?></td></tr>                
             </table>
			</div>
			<div id="informinfo"  style="position:absolute;top:270px;right:100px;">
			 <a href="<?php if(isset($language)):
    if($language=="zh_CN"){ echo 'http://dev.cobub.com/zh/docs/cobub-razor/installation-guide/';}
    else{echo 'http://dev.cobub.com/docs/cobub-razor/installation-guide/';} endif;?>" target="_blank">
    <?php if(isset($language)):
    if($language=="zh_CN"){ echo '<p style="font-size: 16px;font-weight: bold;">安装帮助</p>';}
    else{echo '<p style="font-size: 16px;font-weight: bold;">Help On Installation</p>';} endif;?></a>
			 </div>
			<div class="clear"></div>	
			<div style="height:330px"></div>
		<footer>
		<ul  class="tabs">
		<div >
		<input type="submit" value="<?php echo lang('installview_nextstep') ;?>">
		 </form>	
		</div>
		</ul>
		</footer>
			</article>
		<div class="clear"></div>	
		<div class="spacer"></div>
	</section>
<div style="position:absolute;top:770px; left:500px">
<p align="center"> &copy; Copyright 2012-2015 Cobub Razor  Version:<?php  echo $this->config->item('version')?>

 <a href=" <?php if($language=="zh_CN")
                       { echo 'http://dev.cobub.com/zh/docs/cobub-razor/release-note/';}
               else{ echo 'http://dev.cobub.com/docs/cobub-razor/release-note/'; } ?>" target="_blank"><?php
                            if ($language == "zh_CN") {
                                echo '发布说明';}
                            else {
                                echo 'Release Note';} ?></a><br/>
                                           <a href ="
 <?php if($language=="zh_CN")
                        { echo 'http://dev.cobub.com/zh/';}
                                         else{ echo 'http://dev.cobub.com/'; } ?>
                                             " target ="_blank" title="<?php if ($language == 'zh_CN') {echo '移动应用分析';}
                else {echo 'Mobile Analytic';}?>" 
                    alt="Cobub Razor - Open Source Mobile Analytics                         Solution"><?php if ($language == "zh_CN") {
                            echo '开源移动应用统计分析平台';}
                        else {
                            echo 'Mobile Analytics of Open Source';}?></a>
</div>

</html>
