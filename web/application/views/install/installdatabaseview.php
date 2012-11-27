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

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
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
			<header><h3><?php echo lang('installview_databaseheader') ;?></h3></header>
	<div>			
    <span id="imgtitle"><a href="http://www.cobub.com/products/cobub-razor/" target="_blank"><img src="<?php if(isset($language)):
    if($language=="zh_CN"){ echo $newurl.'/assets/images/cobub-razor-logo.png';}
    else{echo $newurl.'/assets/images/ecobubrazorlogo.png';} endif;?>"/></a></span>
     </div> 
     <hr style="color:#9FA0A2;" />
     	
			<div>
			<div  style="height:50px;"></div> 
			<h3><font color="#123901"><?php echo lang('installview_checkstep') ;?></font></h3>			
			<h3><font color="#990000"><?php echo lang('installview_databasestep') ;?></font></h3>			
			<h3><font color="#9d9d9d"><?php echo lang('installview_websitestep') ;?></font></h3>
			<h3><font color="#9d9d9d"><?php echo lang('installview_finshstep') ;?></font></h3>			
			</div>			
			<form method="post" action="<?php echo $newurl; ?>/index.php?/install/install/createdatabase">
			<div id="showerror"  style="position:absolute;top:245px;left:420px;">	
			<div id='errorinfo' ><font color="red" size="4px"><?php if(isset($error)) echo $error; ?></font></div>
			 <div id='errorinfo' ><font color="red" size="4px"><?php if(isset($errord)) echo $errord; ?></font></div>	
			 <div id='errorinfo' ><font color="red" size="4px"><?php if(isset($inerror)) echo $inerror; ?></font></div>
			 <div id='errorinfo' ><font color="red" size="4px"><?php if(isset($inerrordw)) echo $inerrordw; ?></font></div>	
			 </div>	
			 <div id="informinfo"  style="position:absolute;top:296px;right:100px;">
			 <a href="<?php if(isset($language)):
    if($language=="zh_CN"){ echo 'http://dev.cobub.com/zh/docs/cobub-razor/installation-guide/';}
    else{echo 'http://dev.cobub.com/docs/cobub-razor/installation-guide/';} endif;?>" target="_blank">
    <?php if(isset($language)):
    if($language=="zh_CN"){ echo '<p style="font-size: 16px;font-weight: bold;">帮助</p>';}
    else{echo '<p style="font-size: 16px;font-weight: bold;">Help</p>';} endif;?></a>
			 </div>
			<div id="database"  style="position:absolute;top:296px;left:420px;">
			<p align="left" class="STYLE5"><?php echo lang('installview_databaseheader') ;?></p>
			<p><?php echo lang('installview_datawarn') ;?></p>
			  <p><?php echo lang('installview_datawarninfo') ;?></p>			 		 
			 </div>
			 <div id="database"  style="position:absolute;top:420px;left:420px;">
			<p align="left" class="STYLE5"><?php echo lang('installview_dataset') ;?></p>			
             <table class="tablesorter" cellspacing="0">
             	<tr><td><?php echo lang('installview_dataserve') ;?></td>
             	<td><input name='ip' value='<?php if(isset($ip)){echo $ip;} else{echo set_value('ip');} ?>'></input></td>
               <td><?php echo form_error('ip'); ?></td></tr>
                <tr><td><?php echo lang('installview_dataaccount') ;?></td><td><input name='username' type="text" value="<?php echo set_value('username'); ?>"></input></td><td><?php echo form_error('username'); ?></td></tr>
                <tr><td><?php echo lang('installview_datapassword') ;?></td><td><input name='password' type="text" value="<?php echo set_value('password'); ?>"></input></td><td><?php echo form_error('password'); ?></td></tr>
                <tr><td><?php echo lang('installview_dataname') ;?></td><td><input name='dbname' type="text" value="<?php echo set_value('dbname'); ?>"></input></td><td><?php echo form_error('dbname'); ?></td></tr>
                <tr><td><?php echo lang('installview_datatablehead') ;?></td><td>
                <input name='tablehead' type="text" value="<?php if((set_value('tablehead')=="razor_")||(set_value('tablehead')=="")) {echo "razor_";} else {echo set_value('tablehead'); }?>"></input></td><td><?php echo form_error('tablehead'); ?></td></tr>                
             </table>            
			</div >
			<div id="datadepot" style="position:absolute;top:750px;left:415px;">
			<p align="left" class="STYLE5"><?php echo lang('installview_datadepotset') ;?></p>			
             <table class="tablesorter" cellspacing="0">
             	<tr><td><?php echo lang('installview_datadepotserve') ;?></td>
             	<td><input name='depotip' value='<?php if(isset($ip)){echo $ip;} else{ echo set_value('depotip');} ?>'></input></td>
               <td><?php echo form_error('depotip'); ?></td></tr>
                <tr><td><?php echo lang('installview_datadepotaccount') ;?></td><td><input name='depotusername' type="text" value="<?php echo set_value('depotusername'); ?>"></input></td><td><?php echo form_error('depotusername'); ?></td></tr>
                <tr><td><?php echo lang('installview_datadepotpwd') ;?></td><td><input name='depotpassword' type="text" value="<?php echo set_value('depotpassword'); ?>"></input></td><td><?php echo form_error('depotpassword'); ?></td></tr>
                <tr><td><?php echo lang('installview_datadepotname') ;?></td><td><input name='depotdbname' type="text" value="<?php echo set_value('depotdbname'); ?>"></input></td><td><?php echo form_error('depotdbname'); ?></td></tr>
                <tr><td><?php echo lang('installview_datadepottablehead') ;?></td><td><input name='depottablehead' type="text" value="<?php if((set_value('tablehead')=="razor_")||(set_value('tablehead')=="")) {echo "razor_";} else {echo set_value('depottablehead'); }?>"></input></td>
                <td><?php echo form_error('depottablehead'); ?></td></tr>
                
             </table>
            
			</div>
			<div class="clear"></div>	
		<div  style="height:740px;"></div>
		<footer>
		<ul  class="tabs">
		<div >
		<input type="submit" value="<?php echo lang('installview_nextstep') ;?>" align="right">
		 </form>
		</div>
		</ul>
		</footer>		
			</article>		
	</section>
	<div style="position:absolute;top:1180px; left:500px">
<p align="center"> &copy; Copyright 2012 Cobub Razor Solution Verion:0.3 <a href="http://dev.cobub.com/" target="_blank"><?php echo lang('installview_companyname') ;?></a></p></div>
</body>
</html>
