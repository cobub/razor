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
	<script src="<?php echo $newurl ?>/assets/js/jquery-1.5.2.min.js" type="text/javascript"></script>
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

		var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab 

+ content
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
			<header><h3><?php echo lang('installview_checkheader') ;?></h3></header>
	<div>			
    <span id="imgtitle"><a href="http://www.cobub.com/products/cobub-razor/" target="_blank"><img src="<?php if(isset($language)):
    if($language=="chinese"){ echo $newurl.'/assets/images/cobub-razor-logo.png';}
    else{echo $newurl.'/assets/images/ecobubrazorlogo.png';} endif;?>"/></a></span>
     </div>
        <hr style="color:#9FA0A2;" />
     	
			<div>
			<div  style="height:50px;"></div> 		
			<h3><font color="#990000"><?php echo lang('installview_checkstep') ;?></font></h3>			
			<h3><font color="#9d9d9d"><?php echo lang('installview_databasestep') ;?></font></h3>			
			<h3><font color="#9d9d9d"><?php echo lang('installview_websitestep') ;?></font></h3>
			<h3><font color="#9d9d9d"><?php echo lang('installview_finshstep') ;?></font></h3>		
			</div>
			<div style="height:350px;position:absolute;top:230px;left:420px;">
			<font color="red">
		    <p><?php if(isset($versionerror)) echo $versionerror;?></p>
            <p><?php if(isset($mysqlierror)) echo $mysqlierror;?></p>
            <p><?php if(isset($writeerror))  echo $writeerror;?></p></font>
			</div>
			<div style="position:absolute;top:300px;left:420px;"   >
			 <p align="left" class="STYLE5"><?php echo lang('installview_check') ;?></p>
             <table class="tablesorter" cellspacing="0">
             	<tr><td><?php echo lang('installview_checkversion') ;?><?php echo PHP_VERSION; ?></td>
             	<td>
             	<!-- 对版本的判断   版本大于4.2-->
             	<?php if(isset($phpversion)&&$phpversion=="true") 
             	      {
             			echo '<img src="'.$newurl.'/assets/images/icn_alert_success.png"/>';
             	      }
             		else
             		 {
             		  echo '<img src="'.$newurl.'/assets/images/icn_alert_error.png"/>';
             		 }
             	?>
             	</td></tr>
                <tr><td><?php echo lang('installview_checkexpand') ;?></td><td>                
             	<?php if(isset($mysqli)&&$mysqli=="true") 
             	{echo  '<img src="'.$newurl.'/assets/images/icn_alert_success.png"/>';}
             	else { echo '<img src="'.$newurl.'/assets/images/icn_alert_error.png"/>';}
             	?>
                </td></tr>
                <tr><td><?php echo lang('installview_checkpermission') ;?></td>
                <?php if(isset($configpath)):
                if($configwrite)
                { 	  	
			  	  echo "<tr><td><img src='".$newurl."/assets/images/icn_alert_success.png'/>".$configpath."</td></tr>"; 
			    }
			  else
			  {
			  	echo  "<tr><td><img src='".$newurl."/assets/images/icn_alert_error.png'/>".$configpath."</tr></td>"; 
			  } endif;?>
              <?php if(isset($captchapath)):
                if($captchwrite)
                { ?>                
		       <?php  
			  
			  	
			  	echo "<tr><td><img src='".$newurl."/assets/images/icn_alert_success.png'/>".$captchapath."</tr></td>"; 
			  }
			  else
			  {
			  	echo  "<tr><td><img src='".$newurl."/assets/images/icn_alert_error.png'/>".$captchapath."</tr></td>"; 
			  } endif;?> 
		       <?php if(isset($assetspath)):
               foreach ($assetspath as $row)
                {		  
				  if($row['readable']==1&&$row['writable']==1)
				  {  if($row['name']=="android"||$row['name']=="sql"||$row['name']=="storedprocedure")
				     {
				  	    echo "<tr><td><img src='".$newurl."/assets/images/icn_alert_success.png'/>".$row['server_path']."</td></tr>"; 
				     }
				  }
				  else
				  {
				  	if($row['name']=="android"||$row['name']=="sql"||$row['name']=="storedprocedure")
				     {
				  	   echo  "<tr><td><img src='".$newurl."/assets/images/icn_alert_error.png'/>".$row['server_path']."</td></tr>";
				     } 
				  }		    
                } endif;?> 	               
             </table>
			</div>
			<div class="clear"></div>	
		<div  style="height:466px;"></div>
		<footer>
		<div style="position:absolute;top:874px;right:60px;">		
		<a href='<?php if($phpversion=="true"&&$mysqli=="true"&&$writetrue=="true") {echo $newurl.'/index.php?/main/databaseinfo';}
		else{  echo $newurl.'/index.php?/main/systemcheck';  } ?>'>
		<input type="submit" value="<?php echo lang('installview_nextstep') ;?>"></a>
		</div>
		
		</footer>
			</article>
		<div class="clear"></div>	
		<div class="spacer"></div>
	</section>

<div style="position:absolute;top:920px; left:500px">
<p align="center">Copyright &copy; 2012 Cobub Razor Solution Verion:0.1 <a href="http://dev.cobub.com/" target="_blank"><?php echo lang('installview_companyname') ;?></a></p></div>

</body>

</html>