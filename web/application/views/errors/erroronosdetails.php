<?php 
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */?>
<section id="main" class="column">
<article class="module width_full">
<header>
<h3 class="tabs_involved">

<a href ="<?php echo site_url().'/report/erroronos'?>">
<?php echo lang('v_rpt_err_errorList') ?></a>

<font color="#FF9224">
<label id='fix_status'>
<?php if($isfix==0){echo lang('v_rpt_err_unrepaired');}
      else{ echo lang('v_rpt_err_repairedE');} ?>
</label>
</font>

<a onclick="javascript:changefix();">
<label id='fix_label'>
<?php 
if($isfix==0){echo lang('v_rpt_err_markR');}
else { echo lang('v_rpt_err_markUR'); }
if(isset($errordetail))
$error_num = $errordetail->num_rows();
else 
$error_num = 0;
?>
</label>
</a>
</h3>
<ul class="tabs">
	<li><a id="stackTrace" href="#tab1"><?php echo lang('v_rpt_err_stackTrace') ?></a></li>
	<li><a id="errorDetail"
		href="#tab2"><?php echo lang('v_rpt_err_errorDetails') ?>(<?php if(isset($num)&&($num!="")){echo $num;}else{echo "0";} ?>)</a></li>
	<li><a id="deviDceistribution"
		href="#tab3"><?php echo lang('v_rpt_err_deviceDistribution') ?></a></li>
	<li><a id="OSDistribution"
		href="#tab4"><?php echo lang('v_rpt_err_versionDistribution') ?></a></li>
</ul>
</header>


<div id="tab1" class="tab_content">
  <div class="module_content" style="width:500px;margin-left: 10px;overflow-y:auto;">
      <p><?php if(isset($stacktrace)) echo $stacktrace;?></p>
  </div>
</div>


<div id="tab2" class="tab_content" >
    <div id="errorlistdetail">
    </div>
    <footer><div id="pagination" class="submit_link"></div></footer> 
</div>


<div id="tab3" class="tab_content">
 <div id="container"  class="module_content " style="height:500px;width: 73%">
</div>	
</div>

<div id="tab4" class="tab_content">
<div id="container1"  class="module_content" style="height:500px;width: 73%">
</div>	
</div>

</article>
</section>
<script type="text/javascript">
var titlesk='<?php echo isset($titlesk)?$titlesk:""?>';
var deviceos_sk='<?php echo isset($deviceos_sk)?$deviceos_sk:""?>';
var isfix='<?php echo isset($isfix)?$isfix:""?>';
var error_list = <?php echo json_encode($errordetail->result());?>;
</script>
<script type="text/javascript">
 function showdetail(index){
    var errordiv = document.getElementById('errordiv'+index);  
    var obj=document.getElementById('showdetail'+index);
    if (obj.innerHTML == "<?php echo lang('g_view') ?>") {
      obj.innerHTML = "<?php echo lang('m_hide') ?>";
    	  errordiv.style.display = "";    	
    } else {
      obj.innerHTML = "<?php echo lang('g_view') ?>";
    	  errordiv.style.display = "none";    	 
    }
  }
 var activeTab = $(this).find("a").attr("id");  
	$(activeTab).fadeIn();
</script>

<script type="text/javascript">
var options;
var options1;
$(document).ready(function() {
	options = { 			
            chart: {
            renderTo: 'container', type: 'pie'},            
            title: {text: '<?php echo lang('v_rpt_err_deviceDistributionComment') ?>' },  
            subtitle: {
                text: ' '
            },        
            tooltip:  {               
                	formatter: function() {    
                        return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,1) +'%';  } 
             } ,  
             legend: {
                
                 floating: false,
                 borderWidth: 1,
                 backgroundColor: '#FFFFFF'
             },                         
            plotOptions: {
          	  pie: {  //Pie chart
                  allowPointSelect: true,  
                  cursor: 'pointer',  
                  showInLegend:true,
                  dataLabels: {   
                      enabled: true, //false Does not display the indicator line
                      color: '#000000',  
                      connectorColor: '#000000',  
                      formatter: function() {  
                    	  return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,1) +'%';
                    	   } 
                  },
              showInLegend: true
            }
            },            
            series: []
        };   
	renderdeviceCharts();
	options1={		
                 chart: { renderTo: 'container1', type: 'pie'},
                 title: {"text":"<?php echo lang('v_rpt_err_appVersionD') ?>"},   
                 subtitle: {
                     text: ' '
                 },             
                 tooltip:  {formatter: function() {    
                     return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,1) +'%';  }
                 } ,                
                 plotOptions: {  pie: {  //Pie chart
                     allowPointSelect: true,  
                     cursor: 'pointer',  
                     showInLegend:true,
                     dataLabels: {   
                         enabled: true, //false Does not display the indicator lin
                         color: '#000000',  
                         connectorColor: '#000000',  
                         formatter: function() {  
                        	 return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,1) +'%';
                        	   } 
                     }
               }
                     },
                 series: [],
               
       };
	renderosCharts();		
	initPagination();	
	pageselectCallback(0,null);
});

    function renderdeviceCharts()
    {	
    	myurl="<?php echo site_url()?>/report/erroronos/getDeviceInfoOnOs/"+titlesk+"/"+deviceos_sk;	
    	 var chart_canvas = $('#container');
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
    		
    	jQuery.getJSON(myurl, null, function(data) { 
    		options.series[0] = {};
    		var devicenum = [];
   		 options.series[0].data = [];
    	options.subtitle.text = '<?php echo $reportTitle['timePhase'];?>';
   		 for(var i=0;i<data.length && i<15;i++)
  	    {  	
    	  	var obj = {};	    
  		    var deviceContent = data[i];
  		    obj.y = parseInt(deviceContent.count,10);
  		    obj.name = deviceContent.devicebrand_name;
    		  options.series[0].data.push(obj);		    	  			
  	    }   
          var chart = new Highcharts.Chart(options);    	   
  		chart_canvas.unblock();
  		});   	

   		    
    }
       
    function renderosCharts()
    {	
    	myurl="<?php echo site_url()?>/report/erroronos/getAppVersionOnOs/"+titlesk+"/"+deviceos_sk;	
    	 var chart_canvas = $('#container1');
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
    		
    	jQuery.getJSON(myurl, null, function(data) {   		
    		options1.series[0] = {};
       		 options1.series[0].data = [];
        		options1.subtitle.text = '<?php echo $reportTitle['timePhase'];?>';
   		 for(var i=0;i<data.length;i++)
  	    {  	
    	  	var obj = {};	    
  		    var osContent = data[i];
  		    obj.y = parseInt(osContent.count,10);
  		    obj.name = osContent.version_name;
    		options1.series[0].data.push(obj);		    	  			
  	    } 
          var chart = new Highcharts.Chart(options1);    	   
  		chart_canvas.unblock();
  		}); 
    	
    }   
</script>
<!-- Mark Repair-->
<script type="text/javascript">
function changefix()
{

	var chart_canvas = $('#main');
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
	 
		 var fix='';
		 if(isfix=='1')
			 fix='0';
		 else
			 fix='1';
		 
		 var data = {
					title_sk:titlesk,
					fix:fix
											
	                   };
					 jQuery.ajax({
							type : "post",
							url : "<?php
							echo site_url ()?>/report/errorlog/changeErrorStatus",
							data : data,
							success : function(msg) {								
								if(isfix=='1')
								{
									$("#fix_label").html('<?php echo lang("v_rpt_err_markR");?>');
									$("#fix_status").html('<?php echo lang("v_rpt_err_unrepaired");?>');
									isfix='0';
									
								}
								else
								{
									$("#fix_label").html('<?php echo lang("v_rpt_err_markUR");?>');
									$("#fix_status").html('<?php echo lang("v_rpt_err_repairedE");?>');
									isfix='1';

								}				 
							},
							error : function(XmlHttpRequest, textStatus, errorThrown) {
								
							},
							beforeSend : function() {							

							},
							complete : function() {
								chart_canvas.unblock();
							}
						});	
					
}
</script>
<script type="text/javascript">
function initPagination() {
	
	var num_entries;
    num_entries = <?php echo $error_num; ?>/<?php echo PAGE_NUMS;?>;
	
    $("#pagination").pagination(num_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo  lang('g_previousPage')?>',       //Previous button in the text
        next_text: '<?php echo  lang('g_nextPage')?>',       //Next button in the text           
        num_display_entries: 4,
        callback: pageselectCallback,
        items_per_page:1
    });
 }
 </script>
 <script type="text/javascript">
 function pageselectCallback(page_index, jq){
	
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";   
	var index = page_index*<?php echo PAGE_NUMS?>;
	var pagenum = <?php echo PAGE_NUMS?>;	
	var data= error_list;
	var msg = '<table class="tablesorter" cellspacing="0">';
	msg += '<tbody id="errorlistdetail">';
	msg += '<thead><tr><th width="20%"><?php echo lang("v_rpt_err_time") ?></th>'+
	'<th width="40%"><?php echo lang("v_rpt_ve_appVersion") ?></th>'+			
	'<th width="20%"><?php echo lang("v_rpt_err_device") ?></th>'+
	'<th width="20%"><?php echo lang("v_rpt_err_stackTrace") ?></th></tr></thead>';
	
	
	for(i=0;i<pagenum && (index+i)<data.length ;i++)
	{ 
		var err_index = index+i;
		msg = msg+"<tr><td>";
		msg += data[err_index].time;
		msg = msg + "</td><td>";
		msg = msg + data[err_index].version_name;
		msg = msg + "</td><td>";
		msg = msg + data[err_index].devicebrand_name;
		msg = msg + "</td><td>";
		msg = msg + '<a id="showdetail'+err_index+'" href="javascript:showdetail('+err_index+')"><?php echo lang('g_view') ?></a>';
		msg = msg + "</td></tr>";
		msg = msg+'<tr id="errordiv'+err_index+'" style="display: none;"><td>';
		msg = msg + '<div class="module_content" style="width:500px;margin-left: 10px;overflow-y:auto;"><p>'+data[i+index].stacktrace+'</p></div>';
		msg = msg + "</td></tr>";
	}

    msg+='</tbody></table>';
    $("#errorlistdetail").html(msg);		
    return false;
}
</script>

