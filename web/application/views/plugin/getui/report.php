<section class="column"  id="main" style='height:1000px;'>
	<h4 class="alert_warning"  id="msg"> <?php echo lang('current_user_num')?>：<?php echo $onlineuser;?></h4>
	<?php if(isset($timeerror)):?>
	<h4   class="alert_warning"  id="timemsg"><?php echo $timeerror;?></h4>
	<?php endif;?>
	
	<article class="module width_full">
	<header>
	<h3 class="h3_fontstyle">		
	<?php  echo lang('getui_data'); ?></h3>
			<ul class="tabs2">
				<li><a id='newuser'
					href="javascript:chooseType('user')"><?php echo lang('getui_newuser');?></a></li>
				<li><a id='activeuser'
					href="javascript:chooseType('online')"><?php echo lang('getui_online');?></a></li>
				<li><a id='session'
					href="javascript:chooseType('push')"><?php echo lang('getui_push');?></a></li>
				<li><a id='avgusage'
					href="javascript:chooseType('receive')"><?php echo lang('getui_recive');?></a></li>
				<li><a id='weekrate'
					href="javascript:chooseType('click')"><?php echo lang('getui_click');?></a></li>
				
		</header>
		<div class="module_content">
			<div id="container" class="module_content" style="height: 300px"></div>
		</div>
		<input type="hidden" id='appid' name="appid" value="<?php echo $appid?>" />
	</article>

	<article class="module width_full" >
	<header>
	<h3 class="h3_fontstyle">		
	<?php   echo lang('getui_data'); ?></h3>
		</header>
		<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php  echo lang('g_date');?></th> 
    				<th><?php  echo lang('getui_newuser');?></th> 
    				<th><?php  echo lang('getui_online');?></th> 
    				<th><?php  echo lang('getui_push');?></th> 
    				<th><?php  echo lang('getui_recive');?></th> 
    				<th><?php echo lang('getui_click')?></th> 
				</tr> 
			</thead> 
			<tbody id='tablebody'>	
						
			</tbody>
			</table>
	</article>
</section>
<script type="text/javascript">
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("ct"); //Find the href attribute value to identify the active tab + content
	$('#'+activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
</script>

<script type="text/javascript">
var chart;
var options;
var type="user";
var optionsLength=0;
var markEventIndex=[];//save all markevent series index
var  allusers= new Array();
var chart_canvas ;
var category=[];
var tooltipmarkevent=[];
var tooltipdata=new Array(new Array(),new Array());
var tooltipname=[];
var colors=['#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', '#DB843D', '#92A8CD', 
            '#A47D7C', '#B5CA92', '#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', 
            '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92'];
$(document).ready(function() {	
	

	options = {
		            chart: {
		                renderTo: 'container',
		                type: 'spline'
		            },
		            title: {
		                text: ''
		            },
		            subtitle: {
		                text: ''
		            },
		            xAxis: {
		                labels:{rotation:300,y:40,x:0}
		            },
		            yAxis: {
		                title: {
		                    text: ''
		                },
		                min:0,
		                labels: {
		                    formatter: function() {
		                        return Highcharts.numberFormat(this.value, 0);
		                    }
		                }
		            },
			        credits:{
						enabled:false
				        },
				    tooltip: {
				        crosshairs: true,
				        shared:false,
				        
				    },
		            plotOptions: {
			            column: {
	                    showInLegend:false
	                	},
		                spline: {
		                    marker: {
		                        radius: 1,
		                        lineColor: '#666666',
		                        lineWidth: 1
		                    }
		                },series:{
		                	cursor:'pointer'
			                }
		            },
		            legend:{
		                labelFormatter: function() {
		                	return this.name
		                }
		             },
		            series: [
		        
		            ]
		        };
	 var appid=document.getElementById('appid').value;
  chart_canvas  = $('#container');
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
	

	var myurl="<?php echo site_url();?>/plugin/getui/report/getdata/?type=online&appid="+appid+"&time="+new Date();	
	jQuery.getJSON(myurl, null, function(data) {
	onlinedata=data; 
	flag1=false;

	var myurl="<?php echo site_url();?>/plugin/getui/report/getdata/?type=push&appid="+appid+"&time="+new Date();
	jQuery.getJSON(myurl, null, function(data) {
	pushdata=data;
	flag2=false;

		var myurl="<?php echo site_url();?>/plugin/getui/report/getdata/?type=receive&appid="+appid+"&time="+new Date();	
		jQuery.getJSON(myurl, null, function(data) {
		receivedata=data;
		flag3=false;

			var myurl="<?php echo site_url();?>/plugin/getui/report/getdata/?type=click&appid="+appid+"&time="+new Date();
			jQuery.getJSON(myurl, null, function(data) {
			clickdata=data; 
			flag4=false;

				var myurl="<?php echo site_url();?>/plugin/getui/report/getdata/?type="+type+"&appid="+appid+"&time="+new Date();	

				jQuery.getJSON(myurl, null, function(newdata) {
				userdata=newdata;
				renderCharts(myurl);
				var tbody = document.getElementById('tablebody');
				var str="";
				// alert('sdf');
				// do{

				// 	}while(flag4||flag3||flag2||flag1)

				if(newdata.status=="Succ"&&onlinedata.status=="Succ"&&pushdata.status=="Succ"&&receivedata.status=="Succ"&&clickdata.status=="Succ"){
					for(var i=newdata.headList.length-1;i<newdata.headList.length;i++){
						for(var j=newdata.dataList.length-1;j>=0;j--){

							

							str = str+"<tr><td>"+newdata.dataList[j].date+"</td><td>"+userdata.dataList[j].datas[i]+"</td><td>"+onlinedata.dataList[j].datas[i]+"</td><td>"+pushdata.dataList[j].datas[i]+"</td><td>"+receivedata.dataList[j].datas[i]+"</td><td>"+clickdata.dataList[j].datas[i]+"</td></tr>";
						}
						tbody.innerHTML=str;
					}
				}else{
					tbody.innerHTML=str;
				}
				


				 }); 
			});  

		 });  
	 });  
	});  

});
</script>
<script type="text/javascript">     
    function renderCharts(myurl)
    {

    	
      		 if(type=='user'){
	    	data = userdata;
	    }
	     if(type=='online'){
	    	data = onlinedata;
	    }
	     if(type=='push'){
	    	data = pushdata;
	    }
	     if(type=='click'){
	    	data = clickdata;
	    }
	     if(type=='receive'){
	    	data = receivedata;
	    }
      	    

      	 	if(data.status=='Succ'){
      	 	 // alert(data.dataList[0].date);
      	 	 // alert(data);
      	 	
          	 	    var d =data.headList.length;
          	 	    for(var i=0;i<data.headList.length-1;i++){
          	 	    	var appdata =[];
          	 	    	var categories=[];

          	 	    	options.series[i] = {};
          	 	    	options.series[i].name =data.headList[i];
          	 	    	for(var j=data.dataList.length-1;j>=0;j--){
          	 	    		categories.push(data.dataList[j].date);
          	 	    		
          	 	    			appdata.push(parseInt( data.dataList[j].datas[i],10));
          	 	    		
          	 	    	}
          	 	    options.series[i].data = appdata;
          	 	  
					options.xAxis.labels.step = parseInt(categories.length/10);
					options.xAxis.categories = categories; 
					<!--options.title.text = <?php echo lang('getui_data');?>;-->
          	 	    }
          	 	    chart = new Highcharts.Chart(options);
          			chart_canvas.unblock();
          		};  
    }
  	    
</script>
<script type="text/javascript">

    function chooseType(typename)
    {     
        type=typename;    	
        var appid=document.getElementById('appid').value;
    	myurl="<?php echo site_url();?>/plugin/getui/report/getdata/?type="+type+"&appid="+appid;	
    	renderCharts(myurl);
    	 
    }
</script>

