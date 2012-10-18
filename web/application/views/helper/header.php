<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title><?php echo lang('l_welcomeCR') ?></title>
<link rel="shortcut icon" href="<?php base_url()?>assets/images/fav.ico" >
<link rel="stylesheet"
	href="<?php echo base_url();?>assets/css/jquery.select.css"
	type="text/css" media="screen" />
<script src="<?php echo base_url();?>assets/js/jquery.select.js"
	type="text/javascript"></script>

<!--	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/english_layout.css" type="text/css" media="screen" />-->
<link rel="stylesheet"
	href="<?php echo base_url();?>assets/css/<?php $style=get_cookie('style'); if($style==""){echo "layout";}else{echo get_cookie('style');}?>.css"
	type="text/css" media="screen" />
<link rel="stylesheet"
	href="<?php echo base_url();?>/assets/css/helplayout.css"
	type="text/css" media="screen" />
<!--[if lt IE 9]>
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
<link rel="stylesheet"
	href="<?php echo base_url();?>assets/css/jquery-ui.css" type="text/css"
	media="screen" />
<link rel="stylesheet"
	href="<?php echo base_url();?>assets/css/pagination.css"
	type="text/css" media="screen" />
<script src="<?php echo base_url();?>assets/js/json/json2.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery-1.7.1.min.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui-1.8.min.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/hideshow.js"
	type="text/javascript"></script>
<script
	src="<?php echo base_url();?>assets/js/jquery.tablesorter.min.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery.pagination.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery.blockUI.js"
	type="text/javascript"></script>
<script type="text/javascript"
	src="<?php echo base_url();?>assets/js/jquery.equalHeight.js"></script>

<script src="<?php echo base_url();?>assets/js/charts/highcharts.js"
	type="text/javascript"></script>
<script
	src="<?php echo base_url();?>assets/js/charts/modules/exporting.js"
	type="text/javascript"></script>
	<?php $style=get_cookie('style'); if($style!=""&&$style!="layout"):?>
	<script
	src="<?php echo base_url();?>/assets/js/charts/themes/<?php $style=get_cookie('style'); if($style==""){echo "layout";}else{echo get_cookie('style');}?>.js"
	type="text/javascript"></script>
	<?php endif;?>
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



<body id="body">
	<header id="header">
		<hgroup>
			<h1 class="site_title">
				<a href="<?php echo base_url();?>"><img class="logo"
					src="<?php echo base_url();?>assets/images/razorlogo.png" /><span
					style=""><?php echo lang('g_cobubRazor') ?></span></a>
			</h1>
			<h3 class="section_title"><?php if(isset($username)):?>				
			<?php echo anchor('/',lang('m_myapps'));?> | <?php echo anchor('/profile/modify/',lang('m_profile'));?> | <?php echo anchor('/auth/change_password/',lang('m_changePassword'));?> | <?php echo anchor('/auth/logout/',lang('m_logout'));?> 
			<?php else:?>
			<?php echo anchor('/auth/login/',lang('l_login'));?> | <?php echo anchor('/auth/register/',lang('l_signup'));?>
			<?php endif;?></h3>
		</hgroup>
	</header>
	<!-- end of header bar -->
	<script type="text/javascript">	
	$(document).ready(function(){  
		//init time segment selector
		initTimeSelect();		
	});  	

	$(function() {
		$("#dpMainFrom" ).datepicker({dateFormat: "yy-mm-dd","setDate":new Date()});
	});

	$(function() {
		$( "#dpMainTo" ).datepicker({ dateFormat: "yy-mm-dd" ,"setDate":new Date()});
	});

	function blockUI()
	{
		var chart_canvas = $('#body');
	    var loading_img = $("<img src='<?php echo base_url();?>/assets/images/loader.gif'/>");
	    		    
	    chart_canvas.block({
	    	 message: loading_img,
	    	        css:{
	    	            width:'32px',
	    	            border:'none',
	    	            background: 'none'
	    	        },
	    	        overlayCSS:{
	    	            backgroundColor: '#FFF',
	    	            opacity: 0.8
	    	        },
	    	        baseZ:997
	    	    });
	}

	function timePhaseChanged(phase)
	{		
		blockUI();
		var url = "<?php echo site_url()?>/report/console/changeTimePhase/"+phase;
		jQuery.getJSON(url, null, function(data) {  	
			window.location.reload();
	    });
	    setCookie("timephase",phase);	
	}

	function onAnyTimePhaseUpdate()
	{
		blockUI();
		 var fromTime = document.getElementById('dpMainFrom').value;
		 var toTime = document.getElementById('dpMainTo').value;
		 var url = "<?php echo site_url()?>/report/console/changeTimePhase/any/"+fromTime+"/"+toTime;
			jQuery.getJSON(url, null, function(data) {  	
				window.location.reload();
		    });  
	}

	//Change selected product to another
	function changeProduct(pid)
	{   	    
		blockUI();
		var url = "<?php echo site_url()?>/manage/product/changeProduct/"+pid;
		jQuery.getJSON(url, null, function(data) {  	
			window.location.reload();
	    });  
	}

	function setcssstyle(cssstyle)
	{
		setCookie("style",cssstyle);
		window.location.reload();
	}

	function setCookie(name,value)
	{
	  var Days = 365; //cookie will remain one year
	  var exp  = new Date();    //new Date("December 31, 9998");
	  exp.setTime(exp.getTime() + Days*24*60*60*1000);
	  document.cookie = name + "="+ escape(value) +";expires="+ exp.toGMTString();
	}
			
	</script>