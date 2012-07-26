<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<title>MSA移动用户分析系统</title>
	
	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery.select.css" type="text/css" media="screen" />
	<script src="<?php echo base_url();?>assets/js/jquery.select.js" type="text/javascript"></script>	
	
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
			<h1 class="site_title"><a href="<?php echo base_url();?>"><img src="<?php echo base_url(); ?>assets/images/demologo.png" /></a><span style="text-shadow: 0 -1px 0 #000;	margin-right: 20px;">MSA</span></h1>
			<h3 class="section_title"><?php if(isset($username)):?>
			<?php echo anchor('/', '<font color="#FFFFFF">我的应用</font>'); ?> | <?php echo anchor('/profile/modify/', '<font color="#FFFFFF">个人资料</font>'); ?>| <?php echo anchor('/auth/change_password/', '<font color="#FFFFFF">修改密码</font>'); ?> |<?php echo anchor('/auth/logout/', '<font color="#FFFFFF">退出</font>'); ?> 
			<?php else:?>
			<?php echo anchor('/auth/login/', '<font color="#FFFFFF">登录</font>'); ?> | <?php echo anchor('/auth/register/', '<font color="#FFFFFF">注册</font>'); ?>
			<?php endif;?></h3>
			
		</hgroup>
	</header> <!-- end of header bar -->
	
	<?php if(isset($login) && $login):?>
	<section id="secondary_bar">
		<div class="user">
			<p><?php if(isset($username)){ echo $username; }?> (<?php echo anchor('/profile/modify','个人资料')?>)</p>
			<!-- <a class="logout_user" href="#" title="Logout">Logout</a> -->
		</div>
		<div class="breadcrumbs_container">
			<article class="breadcrumbs">
			<a href="<?php echo base_url();?>">我的应用列表</a> 
			<?php if(isset($product)):?>
			<div class="breadcrumb_divider"></div> 
			<?php echo anchor('/report/productbasic/view/', $product->name);?>
			<?php endif;?>
			<div class="breadcrumb_divider"></div> 
			<a class="current"><?php echo $pageTitle;?>
			</a>
			
			</article>
			<div class="submit_link" style="margin-top:5px;"><?php echo anchor('/guide', '开发者指南',array('class'=>'sdkcolor'));?></div>
		</div>
		
	</section><!-- end of secondary bar -->	
	<aside id="sidebar" class="column">
		<?php if(!isset($product)):?>
		<h3>产品管理</h3>
		<ul class="toggle">
			<li class="icn_add_user"><?php echo anchor('/', '我的应用');?></li>
			<li class="icn_view_users"><?php echo anchor('/product/create', '添加应用');?></li>
			<li class="icn_view_users"><?php echo anchor('/channel/', '渠道管理');?></li>
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
		<h3>应用管理</h3>
		<ul class="toggle">
			<li class="icn_add_user"><?php echo anchor('/product/editproduct/'.$product->id, '编辑应用');?></li>
			<li class="icn_view_users"><?php echo anchor('/onlineconfig/', '发送策略');?></li></a></li>			
			<li class="icn_view_users"><?php echo anchor('/event/', '编辑自定义事件');?></li>
			<li class="icn_view_users"><?php echo anchor('/channel/appchannel/', '应用渠道');?></li>
		</ul>
		
		<h3>统计概况</h3>
		<ul class="toggle">
			<li class="icn_folder"><?php echo anchor('/report/productbasic/view/'.$product->id, '基本统计');?></li>
			<li class="icn_photo"><?php echo anchor("/report/market/viewMarket", '分发渠道');?></li>
			<li class="icn_audio"><?php echo anchor('/report/version/', '版本分布');?></li>
		</ul>
		<h3>用户分析</h3>
		<ul class="toggle">
			<li class="icn_settings"><?php echo anchor('/report/userbasic', '基本统计');?></li>
			<li class="icn_jump_back"><?php echo anchor('/usefrequency', '使用频率');?></li>
			<li class="icn_jump_back"><?php echo anchor('/usetime', '使用时长');?></li>
			<li class="icn_jump_back"><?php echo anchor('/report/pagevisit','访问页面');?></li>
			<li class="icn_security"><?php echo anchor('/report/region/', '地域分析');?></li>
			<li class="icn_security"><?php echo anchor('/report/userremain/', '用户留存');?></li>
		</ul>
		
		<h3>终端与网络</h3>
		<ul class="toggle">
			<li class="icn_settings"><?php echo anchor('/report/device/', '设备');?></li>
			<li class="icn_security"><?php echo anchor('/report/os/', '操作系统');?></li>
			<li class="icn_jump_back"><?php echo anchor('/report/resolution/', '分辨率');?></li>
			<li class="icn_jump_back"><?php echo anchor('/report/operator/', '运营商');?></li>
			<li class="icn_jump_back"><?php echo anchor('/report/network/', '联网方式');?></li>
		</ul>
		
		<h3>自定义事件</h3>
		<ul class="toggle">
			<li class="icn_settings"><?php echo anchor('/report/eventlist/', '事件列表');?></li>
		</ul>
		
		<h3>错误分析</h3>
		<ul class="toggle">
			<li class="icn_jump_back"><?php echo anchor('/report/errorlog/', '错误分析');?></li>	
		</ul>
		<?php endif;?>
		
		<?php if(isset($admin)):?>
		<h3>用户与权限</h3>
		<ul class="toggle">
			<li class="icn_new_article"><?php echo anchor('/user/', '用户管理');?></li>
			<li class="icn_edit_article"><?php echo anchor('/user/rolemanage/', '角色管理');?></li>
			<li class="icn_categories"><?php echo anchor('/user/resourcemanage/', '资源管理');?></li>
		</ul>
		<?php endif;?>
		<footer>
			<hr />
			<p><strong>Copyright &copy; 2012 UMS Solution</strong></p>
			<p>Powerd by <a href="http://www.wbkit.com">WBTECH ltd..</a></p>
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
		