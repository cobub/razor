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
			<section id="main" class="column" style="width:100%" >
		<article class="module width_full">
			<header><h3><?php echo lang('installview_welcomestep') ;?></h3></header>
	<div>			
    <span id="imgtitle"><a href="http://www.cobub.com/products/cobub-razor/" target="_blank"><img src="<?php if(isset($language)):
    if($language=="zh_CN"){ echo $newurl.'/assets/images/cobub-razor-logo.png';}
    else{echo $newurl.'/assets/images/ecobubrazorlogo.png';} endif;?>"/></a></span>
     </div> 
     <hr style="color:#9FA0A2;" />
     	
			<div>
			<div  style="height:30px;"></div>
			<h3><font color="#9d9d9d"><?php echo lang('installview_checkstep') ;?></font></h3>			
			<h3><font color="#9d9d9d"><?php echo lang('installview_databasestep') ;?></font></h3>			
			<h3><font color="#9d9d9d"><?php echo lang('installview_websitestep') ;?></font></h3>
			<h3><font color="#9d9d9d"><?php echo lang('installview_finshstep') ;?></font></h3>			
			</div>
		 <div id="informinfo"  style="position:absolute;top:270px;right:100px;">
			 <a href="<?php if(isset($language)):
    if($language=="zh_CN"){ echo 'http://dev.cobub.com/zh/docs/cobub-razor/installation-guide/';}
    else{echo 'http://dev.cobub.com/docs/cobub-razor/installation-guide/';} endif;?>" target="_blank">
    <?php if(isset($language)):
    if($language=="zh_CN"){ echo '<p style="font-size: 16px;font-weight: bold;">帮助</p>';}
    else{echo '<p style="font-size: 16px;font-weight: bold;">Help</p>';} endif;?></a>
			 </div>
			<div style="position:absolute;top:270px;left:420px;">			
            <p align="left" class="STYLE5"><?php echo lang('installview_welcome') ;?></p>
            <p align="left"><strong>Cobub Razor</strong><?php echo lang('installview_welcomeintro') ;?></p>
            <p align="left"><?php echo lang('installview_welcomedemand') ;?></p>                   
			</div>
			<div id="acceptcontent" style="position:absolute;top:370px;left:420px;">
			 <p><b><?php echo lang('installview_licensetitle') ;?></b></p> 
             <p><?php echo lang('installview_licensecontent1') ;?></p>
              <p><a href="<?php if(isset($language)):if($language=="zh_CN"){echo "http://dev.cobub.com/zh/docs/cobub-razor/license/";} else{echo "http://dev.cobub.com/docs/cobub-razor/license/";} endif; ?>" target="_blank"><?php echo lang('installview_licenselink') ;?></a></p>
              <input id="acceptcheck" type="checkbox" onClick="isaccpet()"  /> <?php echo lang('installview_acceptcontent') ;?>  
             <?php echo lang('installview_licensecontent3') ;?></p>
            
            </div>

				<div class="clear"></div>
		<div  style="height:150px;"></div>			
		<footer>
		<ul  class="tabs">
		    <div id="installbutton" style="display:none">
			<a href="<?php echo $newurl; ?>/index.php?/install/install/systemcheck/<?php echo $language ?>"><input type="submit" value="<?php echo lang('installview_installstep') ;?>"></a>
			</div>
		</ul>
		</footer>	
			</article>
			<div class="clear"></div>	
		<div  class="spacer"></div>
	</section>
<div style="position:absolute;top:580px; left:500px">
<p align="center"> &copy; Copyright 2012 Cobub Razor Solution Verion:0.3 <a href="http://dev.cobub.com/" target="_blank"><?php echo lang('installview_companyname') ;?></a></p></div>
</body>
</html>
<script type="text/javascript">
function isaccpet()
{
	 var cb=document.getElementById('acceptcheck');	
	 var accbtn=document.getElementById('installbutton');	
	 if(cb.checked==true)
	 {		
		 accbtn.style.display = "";  
	 }
	 else
	 {		  
		 accbtn.style.display = "none";  
	 }
     
}
</script>