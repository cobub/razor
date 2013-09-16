<section id="main" class="column">
<?php if(isset($msg)):?>
<h4 class="alert_error" id="msg"><?php echo $msg;?></h4>
<?php endif;?>
	<!-- show user key&secret -->

	<article class="module width_full">
		<header>
			<h3><?php echo lang ('m_checkApp')?></h3>
		</header>
		<?php echo form_open('plugin/radar/checkradarinfo/activateApp'); ?>
		<div class="module_content">
			<?php if(isset($verification)): else:?>
		 	<p> <b><?php echo lang ('m_tipInfo1') ?></b></p>
		 	<?php endif;?>
			<table class="tablesorter" cellspacing="0">
				<tbody>
					<fieldset>
						<label><?php echo lang('m_appNames') ?></label><?php echo form_error('appname'); ?>
						<input
							type="text" id='appname' name='appname'   disabled="disabled" value= "<?php echo $appName?>">
					
					</fieldset>

					<fieldset>
						<label><?php echo "APPID" ?></label> <label><a href="dev.cobub.com/users/index.php?/help/radar" target="_blank"><?php echo lang ('m_tipInfo2')?></a></label><?php echo form_error('appid'); ?>
						
						<input
							type="text" id='appid' name='appid' value="<?php echo isset($verification)?$appid:"";?>">
					</fieldset>
				</tbody>
			</table>
		</div>
		<footer>
			<footer>
			<div class="submit_link">
				<input type='submit' id='submit' class='alt_btn'
					name="radar/activate" value= "<?php echo lang('m_check')?>">
			</div>
			</footer>
		</footer>
		<?php echo form_close(); ?>
	</article>

	<!-- end of show user key&secret-->
	<?php if(isset($verification)): ?>

	<article class="module width_full" >
	<header>
	<h3 class="h3_fontstyle">		
	<?php  echo lang('m_appRanks'); ?></h3>
				
		</header>
		<div class="module_content">
			<div id="container" class="module_content" style="height: 300px"></div>
		</div>
		<input type="hidden" id='appid' name="appid" value="<?php echo $appid?>" />
	</article>

	<article class="module width_full">
	<header><h3 class="tabs_involved"><?php echo lang('m_tables')?></h3></header>
		<div class="tab_container">
			<div id="tab1" class="tab_content">
			<table class="tablesorter" cellspacing="0"> 
			
			<tbody> 
				<tr>
					
    				<td><?php echo lang('m_appNames')?></td> 
    				<td><?php echo $name ?> </td>
    				
    			</tr>
    		    <tr>
				
					<td><?php echo lang('m_appSizes')?></td> 
    				<td><?php echo $size?> </td>
    			</tr> 
				<tr>
					
    				<td><?php echo lang('m_classes')?></td> 
    				<td><?php echo $c ?> </td>
    				
    			</tr>
    		    <tr>
				
					<td><?php echo lang('m_payType')?></td> 
    				<td><?php echo $py?> </td>
    			</tr> 
    			<tr>
					
    				<td><?php echo lang('m_platform')?></td> 
    				<td><?php echo $pl?></td>
    			</tr> 
    			<tr>
					
    				<td><?php echo lang('m_country')?></td> 
    				<td> <?php echo $ctr?></td>
    			</tr> 
    			<tr>
					
    				<td><?php echo lang('m_url')?></td> 
    				<td><a href="<?php echo $url ?>" target="_blank"><?php echo $url ?></a></td>
    			</tr> 
					
    				<td><?php echo lang('m_hasdata')?></td> 
    				<td><?php echo ($hasdata>0)?lang('m_yes'):lang('m_no')?> </td>
    			</tr> 
    			<tr>
					
    				<td><?php echo lang('m_verification')?></td> 
    				<td><?php echo ($verification>0)?lang('m_yes'):lang('m_no') ?></td>
 				</tr>

 				
		
			</tbody> 
			</table>
			</div><!-- end of #tab1 -->
			</div><!-- end of .tab_container -->
		</article> <br/><br/>
	<?php  endif;?>

<script type="text/javascript">
var chart;
var options;
var type="user";
var optionsLength=0;
var markEventIndex=[];//save all markevent series index
var allusers= new Array();
var category=[];
var tooltipmarkevent=[];
var tooltipdata=new Array(new Array(),new Array());
var tooltipname=[];
var colors=['#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', '#DB843D', '#92A8CD', 
             '#A47D7C', '#B5CA92','#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', 
             '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92'];
$(document).ready(function() {	
	options = {
		            chart: {
		                renderTo: 'container',
		                type: 'spline'
		            },
		            title: {
		                text: "<?php echo $name?>"+"<?php echo lang('m_titleRanks')?>"
		            },
		            subtitle: {
		                text: "<?php echo lang ('m_subtitle')?>"
		            },
		            xAxis: {
		                categories: ['0', '1', '2', '3', '4', '5', '6', '7', '8'],       

						labels: {    
							step: 1   
						} 
		            },
		            yAxis: {
		                title: {
		                    text: '<?php echo lang('m_ranksInfo')?>'
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
				        formatter: function() {
					        var content=this.x+'<br>'+'<?php echo lang('m_currentRank')?>:'+this.y;
					        var m=0;					       
					        for(var i=0;i<category.length;i++){
						        if(category[i]==this.x){
							        m=i;
							        break;
						        }
						    }
					        if(this.series.name=='<?php echo lang('m_dateevents');?>'){
		                           content=tooltipmarkevent[m];
		                    }else{
			                    for(var j=0;j<tooltipname.length;j++){
				                    content=content+'<span style="color:'+colors[j]+'">'+tooltipname[j]+'</span>:'+tooltipdata[j][m]+'<br>';
				                }
		                    }
					        return content;
				        }
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
		                	cursor:'pointer',
		                	events:{
		        				click:function(e){
		        					if(!markEventIndex.content(e.point.series.index)){
		        						sendBack(e);
		        						return;
		        						}
		    						var rights=e.point.rights==1?'<?php echo lang('m_public')?>':'<?php echo lang('m_private')?>';
		        					var content='<div><?php echo lang('m_marktime')?>:'+e.point.date+'</div>';
		        					content+='<div><?php echo lang('m_user')?>:'+e.point.username+'</div>';
		        					content+='<div><?php echo lang('m_title')?>:'+e.point.title+'</div>';
		        					content+='<div><?php echo lang('m_description')?>:'+e.point.description+'</div>';
		        					content+='<div><?php echo lang('m_rights')?>:'+rights+'</div>';
		        					 hs.htmlExpand(null, {
		                                 pageOrigin: {
		                                     x: e.pageX,
		                                     y: e.pageY
		                                 },
		                                 headingText: '<?php echo lang('m_eventsDetail')?>',
		                                 maincontentText:content,
		                                 width: 200
		                             });	
		        					}
		                        }
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
	 
 	//var appleid=document.getElementById('appleid').value;
	var myurl="<?php echo site_url();?>/plugin/radar/checkradarinfo/getRanks?appleid=<?php echo $appid ?>";
	renderCharts(myurl);	
});
</script>

<script type="text/javascript">     
    function renderCharts(myurl)
    {
      	    var chart_canvas = $('#container');
      	    var loading_img = $("<img src='<?php echo base_url();?>assets/images/loader.gif'/>");
         
      	 	jQuery.getJSON(myurl, null, function(data) {
      	 	 // alert(data.dataList[0].date);
      	 		//alert(data);
					var time = new Array();
					var ranks = new Array();
					options.series[0] = {};
					time = data[0];
					ranks = data[1];
					//alert(time);

					options.series[0].data = ranks;
					options.series[0].name = '<?php echo $name?>';
					options.xAxis.labels.step = parseInt(time.length/10);
					options.xAxis.categories = time; 

					chart = new Highcharts.Chart(options);
          		});  
    }
  	    
</script>


