<!doctype html>
<html lang="en">

<head>
<meta charset="utf-8"/>
<title>Cobub Razor Setup Wizard</title>
	
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
			<h1 class="site_title"  ><a href="http://dev.cobub.com" target="_blank">
			<img  class="logo" style="border:0" src="<?php echo $newurl.'/assets/images/razorlogo.png'?>"/>
			<span style="vertical-align: top;">Cobub Razor Setup Wizard</span></a></h1>			
		</hgroup>
		
		
	</header>
	<form method="post" action="<?php echo $newurl; ?>/index.php?/install/installation/selectlanguage"> 	
			<section id="main" class="column" style="width:100%" >
		<article class="module width_full">
			<header><h3>Select language</h3></header>
	<div>			
    <span id="imgtitle"><a href="http://dev.cobub.com" target="_blank"><img src="<?php echo $newurl.'/assets/images/ecobubrazorlogo.png'?>"/></a></span>
     </div> 
     <hr style="color:#9FA0A2;" />
     	
			<div style="position:absolute;left:400px">
			<p align="left" class="STYLE5">Please select the language you want to install</p>
			 <table cellspacing="0">
			               
                <tr><td><label><b>Language</b></label></td>
                <td width="50px"></td>
                <td><select name="weblanguage" id="weblanguage" style="width:160px">                
                	<?php if(isset($languageinfo)):
							foreach ($languageinfo as $row)
							{
								?>							
							<option value="<?php echo $row ;?>" <?php if($row=="en_US") {echo "selected";} ?>>
									<?php  
									if($row=="de_DE"){echo "Deutsch(".$row.")";};
									if($row=="en_US"){echo "English(".$row.")";};
									if($row=="zh_CN"){echo "简体中文(".$row.")";};
									if($row=="ja_JP"){echo "日本語(".$row.")";};?></option>
								<?php 
							}
							
							endif;?>
                </select></td></tr>
                </table> 
            </div>
				<div class="clear"></div>	
		<div  style="height:150px;"></div>			
		<footer>
		<ul  class="tabs">
		<div id="installbutton">
		<input type="submit" value="Start Installation">
		</div>
		</ul>
		</footer>
			</article>
			<div class="clear"></div>	
		<div  class="spacer"></div>
	</section>
	</form>
<div style="position:absolute;top:480px; left:450px">
<p align="center"> &copy; Copyright 2012-2015 Cobub Razor  Version:<?php  echo $this->config->item('version')?>


<a href="http://dev.cobub.com/docs/cobub-razor/release-note/" target="_blank"><?php echo 'Release Note'; ?></a><br/>
<a href ="http://dev.cobub.com/" target ="_blank" title="Mobile Analytics" alt="Cobub Razor - Open Source Mobile Analytics                   Solution"><?php echo 'Mobile Analytics of Open Source'; ?></a>

</p>








</body>
</html>
