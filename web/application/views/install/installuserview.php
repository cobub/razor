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
	<script src="<?php echo $newurl ?>/assets/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	<script src="<?php echo $newurl ?>/assets/js/hideshow.js" type="text/javascript"></script>
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
			<h1 class="site_title" style="width:70%"><a href="http://www.cobub.com/products/cobub-razor/" target="_blank"><?php echo lang('installview_installheader') ;?></a></h1>			
		</hgroup>
	</header> 	
			<section id="main" class="column" style="width:100%">
		<article class="module width_full">
			<header><h3><?php echo lang('installview_websiteheader') ;?></h3></header>
	<div>			
    <span id="imgtitle"><a href="http://www.cobub.com/products/cobub-razor/" target="_blank"><img src="<?php if(isset($language)):
    if($language=="zh_CN"){ echo $newurl.'/assets/images/cobub-razor-logo.png';}
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
		 <div id="informinfo"  style="position:absolute;top:280px;right:100px;">
			 <a href="<?php if(isset($language)):
    if($language=="zh_CN"){ echo 'http://dev.cobub.com/zh/docs/cobub-razor/installation-guide/';}
    else{echo 'http://dev.cobub.com/docs/cobub-razor/installation-guide/';} endif;?>" target="_blank">
    <?php if(isset($language)):
    if($language=="zh_CN"){ echo '<p style="font-size: 16px;font-weight: bold;">帮助</p>';}
    else{echo '<p style="font-size: 16px;font-weight: bold;">Help</p>';} endif;?></a>
			 </div>
			<div style="position:absolute;top:280px;left:420px;">
			<P align="left" class="STYLE5"><?php echo lang('installview_websiteheader') ;?></P>	
			<form action="<?php echo $newurl; ?>/index.php?/install/install/createuserinfo" method="post">
             <table class="tablesorter" cellspacing="0">
                 <tr><td><?php echo lang('installview_userurl') ;?></td>
                <td><input name='siteurl' type="text" value="<?php echo set_value('siteurl');?>"></input></td><td><label><?php echo lang('installview_userurlreminder'); ?></label></td>               
                <td><?php echo form_error('siteurl'); ?></td></tr>  
                
                 <tr><td><?php echo lang('installview_timezones') ;?></td>
                <td><select name="webtimezones" id="webtimezones" style="width:160px">
               <option value="UM12"  >UTC -12:00</option>
                <option value="UM11" >UTC -11:00</option>
                 <option value="UM10"  >UTC -10:00</option>
                <option value="UM95" >UTC -9:30</option>
                <option value="UM9"  >UTC -9:00</option>
                <option value="UM8" >UTC -8:00</option>
                 <option value="UM7"  >UTC -7:00</option>
                <option value="UM6" >UTC -6:00</option>
                <option value="UM5"  >UTC -5:00</option>
                <option value="UM45" >UTC -4:30</option>
                 <option value="UM4"  >UTC -4:00</option>
                <option value="UM35" >UTC -3:30</option>
                <option value="UM3"  >UTC -3:00</option>
                <option value="UM2" >UTC -2:00</option>
                 <option value="UM1"  >UTC -1:00</option>
                <option value="UTC" selected="selected">UTC</option>
                <option value="UP1"  >UTC +1:00</option>
                <option value="UP2" >UTC +2:00</option>
                 <option value="UP3"  >UTC +3:00</option>
                <option value="UP35" >UTC +3:30</option>
                <option value="UP4"  >UTC +4:00</option>
                <option value="UP45" >UTC +4:30</option>
                 <option value="UP5"  >UTC +5:00</option>
                <option value="UP55" >UTC +5:30</option>
                <option value="UP575"  >UTC +5:30</option>
                <option value="UP6" >UTC +6:00</option>
                 <option value="UP65"  >UTC +6:30'</option>
                <option value="UP7" >UTC +7:00</option>
                <option value="UP8" >UTC +8:00</option>
                <option value="UP875"  >UTC +8:45</option>
                <option value="UP9" >UTC +9:00</option>
                <option value="UP95" >UTC +9:30</option>
                 <option value="UP10"  >UTC +10:00</option>
                <option value="UP105" >UTC +10:30</option>
                <option value="UP11" >UTC +11:00</option>
                <option value="UP115" >UTC +11:30</option>
                <option value="UP12"  >UTC +12:00</option>
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
<p align="center"> &copy; Copyright 2012 Cobub Razor Solution Verion:0.3<a href="http://dev.cobub.com/" target="_blank"><?php echo lang('installview_companyname') ;?></a></p></div>

</body>

</html>
