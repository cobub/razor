<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title><?php echo lang('header_title') ?></title>
	
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.select.css" type="text/css" media="screen" />
	<script src="<?php echo base_url();?>assets/js/jquery.select.js" type="text/javascript"></script>	
	
<!--	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/english_layout.css" type="text/css" media="screen" />-->
<link rel="stylesheet" href="<?php echo base_url();?>assets/css/layout.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>/assets/css/helplayout.css" type="text/css" media="screen" />
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/ie.css" type="text/css" media="screen" />
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/pagination.css" type="text/css" media="screen" />
	<script src="<?php echo base_url();?>assets/js/json/json2.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/jquery-1.7.1.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/jquery-ui-1.8.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/hideshow.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/jquery.tablesorter.min.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/jquery.pagination.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/jquery.blockUI.js" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo base_url();?>assets/js/jquery.equalHeight.js"></script>
	
	<script src="<?php echo base_url();?>assets/js/charts/highcharts.js" type="text/javascript"></script>
	<script src="<?php echo base_url();?>assets/js/charts/modules/exporting.js" type="text/javascript"></script>

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
			<h1 class="site_title"><a href="<?php echo base_url();?>"><img class="logo" src="<?php echo base_url();?>assets/images/razorlogo.png"/><span style="text_title"><?php echo lang('header_topsitetitle') ?></span></a></h1>
			<h3 class="section_title"><?php if(isset($username)):?>
			<?php echo anchor('/',lang('allview_myapplication'));?> | <?php echo anchor('/profile/modify/',lang('header_toptitleprofile'));?> | <?php echo anchor('/auth/change_password/',lang('header_toptitlechangepwd'));?> | <?php echo anchor('/auth/logout/',lang('header_toptitleexit'));?> 
			<?php else:?>
			<?php echo anchor('/auth/login/',lang('header_toptitlelogin'));?>|<?php echo anchor('/auth/register/',lang('header_toptitleregister'));?>
			<?php endif;?></h3>
			
		</hgroup>
	</header> <!-- end of header bar -->
	
	<?php if(isset($login) && $login):?>
	<section id="secondary_bar">
		<div class="user">
			<p><?php if(isset($username)){ echo $username; }?> (<?php echo anchor('/profile/modify',lang('header_userprofile'))?>)</p>
			<!-- <a class="logout_user" href="#" title="Logout">Logout</a> -->
		</div>
		<div class="breadcrumbs_container">
			<article class="breadcrumbs">
			<a href="<?php echo base_url();?>"><?php echo lang('allview_myapplication') ?></a> 
			<?php if(isset($product)):?>
			<div class="breadcrumb_divider"></div> 
			<?php echo anchor('/report/productbasic/view/', $product->name);?>
			<?php endif;?>
			<div class="breadcrumb_divider"></div> 
			<a class="current"><?php echo $pageTitle;?>
			</a>
			
			</article>
			<div class="submit_link" style="margin-top:5px;"><?php echo anchor('/guide', lang('header_guide'),array('class'=>'sdkcolor'));?></div>
		</div>
		
	</section><!-- end of secondary bar -->	
	<aside id="sidebar" class="column">
		<?php if(!isset($product)):?>
		<h3><?php echo lang('header_asideproduct_promanage') ?></h3>
		<ul class="toggle">
			<li class="icn_my_application"><?php echo anchor('/',  lang('allview_myapplication'));?></li>
			<li class="icn_add_apps"><?php echo anchor('/product/create', lang('header_asideproduct_addapp'));?></li>
			<li class="icn_app_channel"><?php echo anchor('/channel/', lang('header_asideproduct_channelmana'));?></li>
		</ul>
		<?php endif;?>		
		<?php if(isset($product)):?>
		<form class="quick_search">
			<?php if(isset($productList)):?>
							<select style="width:90%;" id='select_head' onchange='changeProduct(value)'>
							<?php foreach($productList->result() as $row){?>
								<option <?php if($product->id == $row->id) echo 'selected';?> value="<?php echo $row->id;?>"><?php echo $row->name;?></option>
							<?php }?>
							</select>
							<?php endif;?>
		</form>
		<hr/>
		<h3><?php echo lang('header_asideapp_appmanage') ?></h3>
		<ul class="toggle">
			<li class="icn_edit_application"><?php echo anchor('/product/editproduct/'.$product->id, lang('header_asideapp_editapp'));?></li>
			<li class="icn_sendpolicy"><?php echo anchor('/onlineconfig/', lang('allview_sendlabel'));?></li>			
			<li class="icn_custom_event"><?php echo anchor('/event/', lang('header_asideapp_editevent'));?></li>
			<li class="icn_app_channel"><?php echo anchor('/channel/appchannel/', lang('allview_appchannel'));?></li>
		</ul>
		
		<h3><?php echo lang('header_asidestatis_statoverview') ?></h3>
			<ul class="toggle">
			<li class="icn_basic_statis"><?php echo anchor('/report/productbasic/view/'.$product->id, lang('header_asidestatis_basicstatics'));?></li>
			<!--<li class="icn_settings"><?php // echo anchor('/report/userbasic', lang('header_asideanaly_basicstatistics')) ;?></li>-->
			<li class="icn_dis_channel"><?php echo anchor("/report/market/viewMarket", lang('header_asidestatis_dischannel'));?></li>
			<li class="icn_version"><?php echo anchor('/report/version/', lang('header_asidestatis_disversion'));?></li>
			<li class="icn_use_frequency"><?php echo anchor('/usefrequency', lang('allview_usefrequency') );?></li>
			<li class="icn_use_time"><?php echo anchor('/usetime',  lang('header_asideanaly_usagetime'));?></li>
			<li class="icn_pagevisit"><?php echo anchor('/report/pagevisit', lang('allview_pagevisit'));?></li>
			<li class="icn_analy_region"><?php echo anchor('/report/region/',  lang('header_asideanaly_region'));?></li>
			<li class="icn_remainuser"><?php echo anchor('/report/userremain/',  lang('header_asideanaly_userretain'));?></li>
		</ul>
	
		<h3><?php echo lang('header_asidedevice_terminal') ?></h3>
		<ul class="toggle">
			<li class="icn_equipment"><?php echo anchor('/report/device/', lang('header_asidedevice_device'));?></li>
			<li class="icn_system"><?php echo anchor('/report/os/', lang('header_asidedevice_os'));?></li>
			<li class="icn_resolution"><?php echo anchor('/report/resolution/', lang('header_asidedevice_resolution'));?></li>
			<li class="icn_operator"><?php echo anchor('/report/operator/', lang('header_asidedevice_operators'));?></li>
			<li class="icn_network"><?php echo anchor('/report/network/', lang('header_asidedevice_network'));?></li>
		</ul>
		
		<h3><?php echo lang('header_asidedefine_defineevent') ?></h3>
		<ul class="toggle">
			<li class="icn_event_list"><?php echo anchor('/report/eventlist/', lang('header_asidedefine_eventlist'));?></li>
		</ul>
		
		<h3><?php echo lang('header_asideerro_errortitle') ?></h3>
		<ul class="toggle">
			<li class="icn_error_analys"><?php echo anchor('/report/errorlog/', lang('header_asideerro_erroranaly') );?></li>	
		</ul>
		<?php endif;?>
		
		<?php if(isset($admin)):?>
		<h3><?php echo lang('header_asiduser_right_userright') ?></h3>
		<ul class="toggle">
			<li class="icn_mangageuser"><?php echo anchor('/user/', lang('header_asiduseright_usermanage'));?></li>
			<li class="icn_managerole"><?php echo anchor('/user/rolemanage/', lang('allview_managerole'));?></li>
			<li class="icn_manaresource"><?php echo anchor('/user/resourcemanage/', lang('allview_manageresource'));?></li>
			<li class="icn_manacategory"><?php echo anchor('/user/applicationManagement/', lang('allview_applicationtype'));?></li>
		</ul>
		<?php endif;?>
		<footer>
			<hr />
			<p><strong>Copyright &copy; 2012 Cobub Razor Solution </strong></p>
			<p>Verion:0.1 <a href="http://dev.cobub.com/" target="_blank"><?php echo lang('allview_companyname');?></a></p>
		</footer>
	</aside>
	<?php endif;?>
	
	<script type="text/javascript">
		function changeProduct(pid)
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
	    	    
			var url = "<?php echo site_url()?>/product/changeProduct/"+pid;
			jQuery.getJSON(url, null, function(data) {  	
				window.location.reload();
	    		});  
		}
	</script>
	
		