<script type="text/javascript">
var titlesk=<?php echo isset($titlesk)?'"'.$titlesk.'"':'"'?>;
var productsk=<?php echo isset($productsk)?'"'.$productsk.'"':'""'?>;
var isfix=<?php echo isset($isfix)?'"'.$isfix.'"':'""'?>;
</script>
<section id="main" class="column">
<h4 class="alert_success" id='msg'><?php echo lang('errordetails_alertinfo') ?></h4>
<article class="module width_full">
<header>
<h3 class="tabs_involved"><a href="<?php echo site_url(); ?>/report/errorlog"> <?php echo lang('errordetails_headertitle') ?></a><font color="#FF9224"><?php if($isfix==0){echo lang('errordetails_errrorfix');}else{ echo lang('errordetails_errrornofix');} ?></font></h3>
<ul class="tabs">
	<li><a id="stackTrace" href="#tab1"><?php echo lang('errordetails_errortabstack') ?></a></li>
	<li><a id="errorDetail"
		href="#tab2"><?php echo lang('errordetails_errortabdetail') ?>(<?php if(isset($num)&&($num!="")){echo $num;}else{echo "0";} ?>)</a></li>
	<li><a id="deviDceistribution"
		href="#tab3"><?php echo lang('errordetails_errortabdevice') ?></a></li>
	<li><a id="OSDistribution"
		href="#tab4"><?php echo lang('errordetails_errortabos') ?></a></li>
</ul>
</header>
<div id="tab1" class="tab_content">
<div class="module_content">
<p><?php if(isset($stacktrace)) echo $stacktrace->stacktrace;?></p></div>
</div>
<!-- Stack Trace -->
<div id="tab2" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th width="20%"><?php echo lang('errordetails_errordetailthtime') ?></th>
			<th width="40%"><?php echo lang('errordetails_errordetailthversion') ?></th>			
			<th width="20%"><?php echo lang('errordetails_errordetailthdevice') ?></th>
			<th width="20%"><?php echo lang('errordetails_errordetailthstack') ?></th>
			
		</tr>
		
	</thead>
	<tbody>	
	<?php
			if (isset ( $errordetail )) :
			$i=0;
				foreach ( $errordetail->result_array() as $row ) {
					?>
		<tr>
			<td width="20%"><?php echo $row['time']; ?></td>
			<td  width="40%"><?php echo $row['deviceos_name']; ?></td>
			<td width="20%"><?php echo $row['devicebrand_name']; ?></td>
       <td width="20%"><a id="showdetail<?php echo $i;?>" href="javascript:showdetail(<?php echo $i;?>)"><?php echo lang('errordetails_errordetailtview') ?></a> </td></tr>
       <tr id="errordiv<?php echo $i;?>" style="display: none;">
         <td>  
      <div class="module_content" style="width:500px;height:350px;margin-left: 100px;overflow-y:auto;">
      <p><?php echo $row['stacktrace']; ?></p></div>
		</td></tr>
		<?php
			$i=$i+1;	}
			 endif;
			?>	
	</tbody>
</table>
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
 function showdetail(index){
    var errordiv = document.getElementById('errordiv'+index);  
    var obj=document.getElementById('showdetail'+index);
    if (obj.innerHTML == "<?php echo lang('errordetails_jserrorshow') ?>") {
      obj.innerHTML = "<?php echo lang('errordetails_jserrorhide') ?>";
    	  errordiv.style.display = "";    	
    } else {
      obj.innerHTML = "<?php echo lang('errordetails_jserrorshow') ?>";
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
            title: {text: '<?php echo lang('errordetails_charttitleinfo') ?>' },          
            tooltip:  {               
                	formatter: function() {    
                        return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,1) +'% ('+Highcharts.numberFormat(this.y, 0, ',') +' <?php echo lang('errordetails_chartdevnuminfo') ?>)';  } 
             } ,                           
            plotOptions: {
          	  pie: {  //饼图  
                  allowPointSelect: true,  
                  cursor: 'pointer',  
                  showInLegend:true,
                  dataLabels: {   
                      enabled: true, //false 不显示指示线  
                      color: '#000000',  
                      connectorColor: '#000000',  
                      formatter: function() {  
                    	  if (this.y > 5) return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,1) +'% ('+Highcharts.numberFormat(this.y, 0, ',') +' <?php echo lang('errordetails_chartdevnuminfo') ?>)';  } 
                  }
            }
            },            
            series: []
        };   
	renderdeviceCharts();
	options1={		
                 chart: { renderTo: 'container1', type: 'pie'},
                 title: {"text":"<?php echo lang('errordetails_chartinfostitle') ?>"},                
                 tooltip:  {formatter: function() {    
                     return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,1) +'% ('+Highcharts.numberFormat(this.y, 0, ',') +' <?php echo lang('errordetails_chartosnuminfo') ?>)';  }
                 } ,                
                 plotOptions: {  pie: {  //饼图  
                     allowPointSelect: true,  
                     cursor: 'pointer',  
                     showInLegend:true,
                     dataLabels: {   
                         enabled: true, //false 不显示指示线  
                         color: '#000000',  
                         connectorColor: '#000000',  
                         formatter: function() {  
                        	 if (this.y > 5)  return '<b>'+ this.point.name +'</b>: '+ Highcharts.numberFormat(this.percentage,1) +'% ('+Highcharts.numberFormat(this.y, 0, ',') +' <?php echo lang('errordetails_chartosnuminfo') ?>)';  } 
                     }
               }
                     },
                 series: [],
               
       };
	renderosCharts();			
});

    function renderdeviceCharts()
    {	
    	myurl="<?php echo site_url()?>/report/errorlog/deviceinfo/"+titlesk+"/"+productsk+"/"+isfix;	
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
   		 for(var i=0;i<data.length;i++)
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
    	myurl="<?php echo site_url()?>/report/errorlog/operationinfo/"+titlesk+"/"+productsk+"/"+isfix;	
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
   		 for(var i=0;i<data.length;i++)
  	    {  	
    	  	var obj = {};	    
  		    var osContent = data[i];
  		    obj.y = parseInt(osContent.count,10);
  		    obj.name = osContent.deviceos_name;
    		options1.series[0].data.push(obj);		    	  			
  	    } 
          var chart = new Highcharts.Chart(options1);    	   
  		chart_canvas.unblock();
  		}); 
    	
    }   
</script>
