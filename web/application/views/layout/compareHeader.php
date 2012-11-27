<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title><?php echo lang('l_cobubRazor') ?></title>
<style>
* { margin:0;padding:0; }
</style>
<link rel="icon" href="<?php echo base_url()?>favicon.ico" type="image/x-icon"/>
<link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico" type="image/x-icon"/>
<link rel="Bookmark" href="<?php echo base_url()?>favicon.ico"/>
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
	href="<?php echo base_url();?>assets/css/<?php $style=get_cookie('style'); if($style==""){echo "layout";}else{echo get_cookie('style');}?>pagination.css"
	type="text/css" media="screen" />
<script src="<?php echo base_url();?>assets/js/json/json2.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery-1.7.1.min.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/jquery-ui-1.8.min.js"
	type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/jquery-ui-1.8.16.custom.min.js"
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
<script src="<?php echo base_url();?>assets/js/estimate.js"
	type="text/javascript"></script>

<script src="<?php echo base_url();?>assets/js/charts/highcharts.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/charts/highcharts-more.js"
	type="text/javascript"></script>
<script
	src="<?php echo base_url();?>assets/js/charts/modules/exporting.js"
	type="text/javascript"></script>
		
<!-- easydialog -->
<link rel="stylesheet"
	href="<?php echo base_url();?>assets/css/easydialog.css" type="text/css"
	media="screen" />
<script	src="<?php echo base_url();?>assets/js/easydialog/easydialog.js"
	type="text/javascript"></script>
<script	src="<?php echo base_url();?>assets/js/easydialog/easydialog.min.js"
	type="text/javascript"></script>
<!-- easydialog -->
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
					src="<?php echo base_url();?>assets/images/razorlogo.png" style="border:0"/><span
					style=""><?php echo lang('g_cobubRazor') ?></span></a>
			</h1>
			<h3 class="section_title"><?php if(isset($username)):?>				
			<?php echo anchor('/',lang('v_console'));?> | <?php echo anchor('/profile/modify/',lang('m_profile'));?> | <?php echo anchor('/auth/change_password/',lang('m_changePassword'));?> | <?php echo anchor('/auth/logout/',lang('m_logout'));?> 
			<?php else:?>
			<?php echo anchor('/auth/login/',lang('l_login'));?> | <?php echo anchor('/auth/register/',lang('l_signup'));?>
			<?php endif;?></h3>
		</hgroup>
	</header>
	<!-- end of header bar -->
	
	<?php if(isset($login) && $login):?>
	<section id="secondary_bar">
		<div class="user">
			<p><?php if(isset($username)){ echo $username; }?> (<?php echo anchor('/profile/modify',lang('m_profile'))?>)</p>
			<!-- <a class="logout_user" href="#" title="Logout">Logout</a> -->
		</div>
		<div class="breadcrumbs_container">
			<article class="breadcrumbs">
				<a href="<?php echo base_url();?>"><?php echo lang('v_console') ?></a> 
			<div class="breadcrumb_divider"></div>
				<a class="current"><?php echo lang('c_compare_product');?>
			</a>
			
			<?php if(isset($viewname)&& $viewname!="") {?>
			<div class="breadcrumb_divider"></div>
				<a class="current"><?php echo $viewname;?>
			</a>
			<?php }?>
			</article>
		</div>	
		<!-- Section for user date section selector -->
		<?php 
			$fromTime =  $this->session->userdata("fromTime");
			if(isset($fromTime) && $fromTime!=null && $fromTime!="")
			{
			}
			else
			{
				$fromTime = date("Y-m-d",strtotime("-6 day"));
			}
			
			$toTime =  $this->session->userdata("toTime");
			if(isset($toTime) && $toTime!=null && $toTime!="")
			{
				
			}
			else
			{
				$toTime = date("Y-m-d",time());
			}
		?>
		<?php if(isset($showDate)&&$showDate==true):?>
			<div class="select_option fr"
			style="z-index:5555;position: absolute; right: 30px; margin-top: 3px">
			<div class="select_arrow fr"></div>
			<div id="selected_value" style="font-size: 12px;"
				class="selected_value fr"><?php echo $fromTime;?>~<?php echo $toTime;?></div>
			<div class="clear"></div>
			<div id="select_list_body" style="display: none;"
				class="select_list_body">
				<ul>
					<li><a class="" id=""
						href="javascript:timePhaseChanged('7day')"> <?php echo  lang('g_lastweek')?></a>
					</li>
					<li><a class="" id=""
						href="javascript:timePhaseChanged('1month');"> <?php echo  lang('g_lastmonth')?></a>
					</li>
					<li><a class=""
						href="javascript:timePhaseChanged('3month');"> <?php echo  lang('g_last3months')?></a>
					</li>
					<li><a class=""
						href="javascript:timePhaseChanged('all');">  <?php echo  lang('g_alltime')?></a>
					</li>
					<li class="date_picker noClick"><a style=""><?php echo  lang('g_anytime')?></a>
					</li>
					<li style="padding: 0; display: none;"
						class="date_picker_box noClick">
						<div style="width: 100%; padding-left: 20px;" class="selbox">
							<span><?php echo  lang('g_from')?></span> <input
								type="text" name="dpMainFrom" id="dpMainFrom" value=""
								class="datainp first_date date"><br> <span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  lang('v_rpt_ve_to')?></span>
							<input type="text" name="dpMainTo" id="dpMainTo" value=""
								class="datainp last_date date">
						</div>
						<div class="" style="">
							<input id="any" type="button" onclick="onAnyTimePhaseUpdate()"
								value="&nbsp;<?php echo  lang('g_search')?>&nbsp;"
								class="any" style="margin: 5px 60px 0 50px;">
						</div>
					</li>
				</ul>
			</div>
		</div>
	<?php endif;?>
	</section>
	<!-- end of secondary bar -->
	<aside id="sidebar" class="column">
	<div id="project"><?php if(isset($products)){
				for($i=0;$i<count($products);$i++){
				?>
				<div style="font-size:14px;height:20px;color:#666666;width=100%;">
				<a href='<?php echo site_url()?>/report/productbasic/view/<?php echo $products[$i]->id ?>'><?php echo $products[$i]->name?></a>
				</div>
			<?php } }?></div>
		<hr />
		<?php //if(isset($product)):?>
		<h3><?php echo lang('m_rpt_statisticsOverview') ?></h3>
		<ul class="toggle">
			<li class="icn_basic_statis"><?php echo anchor('/report/productbasic/view?type=compare', lang('m_rpt_dashboard'));?></li>
		</ul>
		<h3><?php echo lang('m_rpt_users') ?></h3>
		<ul class="toggle">
			<li class="icn_use_frequency"><?php echo anchor('/report/usefrequency?type=compare', lang('m_rpt_frequencyOfUse') );?></li>
			<li class="icn_use_time"><?php echo anchor('/report/usetime?type=compare',  lang('m_rpt_usageDuration'));?></li>
			<li class="icn_phaseusetime"><?php echo anchor('/report/productbasic/phaseusetime?type=compare', lang('m_rpt_timeTrendOfUsers') );?></li>
			<li class="icn_analy_region"><?php echo anchor('/report/region?type=compare',  lang('m_rpt_geography'));?></li>
			<li class="icn_remainuser"><?php echo anchor('/report/userremain?type=compare',  lang('m_rpt_userRetention'));?></li>
		</ul>
		<h3><?php echo lang('m_rpt_terminalsOrNetwork') ?></h3>
		<ul class="toggle">
			<li class="icn_equipment"><?php echo anchor('/report/device?type=compare', lang('m_rpt_devices'));?></li>
			<li class="icn_system"><?php echo anchor('/report/os?type=compare', lang('m_rpt_os'));?></li>
			<li class="icn_resolution"><?php echo anchor('/report/resolution?type=compare', lang('m_rpt_resolution'));?></li>
			<li class="icn_operator"><?php echo anchor('/report/operator?type=compare', lang('m_rpt_carriers'));?></li>
			<li class="icn_network"><?php echo anchor('/report/network?type=compare', lang('m_rpt_networking'));?></li>
		</ul>

		
		<h3><?php echo lang('m_rpt_events') ?></h3>
		<ul class="toggle">
			<li class="icn_funnel_list"><?php echo anchor('/report/funnels?type=compare', lang('v_rpt_re_funnelModel'));?></li>
		</ul>

		<h3><?php echo lang('m_rpt_errors') ?></h3>
		<ul class="toggle">
			<li class="icn_error_analys"><?php echo anchor('/report/errorlog/compareErrorlog?type=compare', lang('m_rpt_errors') );?></li>
		</ul>
		<?php //endif;?>
		<footer>
			<hr />
			<!-- 
			<p>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a id="greencss"
					href="javascript:setcssstyle('greenlayout')"><img
					src="<?php echo base_url();?>assets/images/greenbtn.png" style="border:0"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a id="layoutcss" href="javascript:setcssstyle('layout')"><img
					src="<?php echo base_url();?>assets/images/graybtn.png" style="border:0"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<a id="bluecss" href="javascript:setcssstyle('bluelayout')"><img
					src="<?php echo base_url();?>assets/images/bluebtn.png" style="border:0"/></a>

			</p>
			 -->
			<p>
				<strong>&copy; Copyright 2012 Cobub Solution </strong>
			</p>
			<p>
				Verion:0.3 <a href="http://dev.cobub.com/" target="_blank"><?php echo lang('g_devCobubC');?></a>
			</p>
		</footer>

	</aside>
	<?php endif;?>
	
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
			window.location.href="<?php echo site_url()?>/report/productbasic/view/"+pid;  	
			//window.location.reload();
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