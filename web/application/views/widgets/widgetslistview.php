<div  id="addwidget">	        
			<input type='Checkbox' id="errorversion" name="reportname" value="errorlog/errorversion/380" onclick="checkboxnum()"
			<?php if(isset($addreport)) 
				foreach ($addreport->result() as $report)
			{ $cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
			$value=$cvalue."/".$rvalue;if($value=="errorlog/errorversion"){echo "checked='true'";break;}}?>/>
			 <?php echo lang('w_errorlog'); ?><br/>
			<input type='Checkbox' id="erroros" name="reportname" value="erroronos/erroros/380" onclick="checkboxnum()"
			<?php if(isset($addreport)) 
				foreach ($addreport->result() as $report)
				{ $cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="erroronos/erroros"){echo "checked='true'";break;}}?>/>
				<?php echo lang('w_errorlogonos'); ?><br/>
			<input type='Checkbox' id="errordevice" name="reportname" value="errorondevice/errordevice/380" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
					foreach ($addreport->result() as $report)
				{ $cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="errorondevice/errordevice"){echo "checked='true'";break;}}?>/>
				<?php echo lang('w_errorlogondevice');  ?><br/>
			<input type='Checkbox' id="conversions" name="reportname" value="funnels/conversions/380" onclick="checkboxnum()"
			<?php if(isset($addreport)) 
					foreach ($addreport->result() as $report)
				{ $cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="funnels/conversions"){echo "checked='true'";break;}}?>/>
			<?php echo lang('w_conversions');  ?><br/>
			<input type='Checkbox' id="resolutioninfo" name="reportname" value="resolution/resolutioninfo/480" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
					foreach ($addreport->result() as $report)
				{$cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="resolution/resolutioninfo"){echo "checked='true'";break;}}?>	/>
				<?php echo lang('w_resolutioninfo');  ?><br/>
			<input type='Checkbox' id="osversion" name="reportname" value="os/osversion/480" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
					foreach ($addreport->result() as $report)
				{$cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="os/osversion"){echo "checked='true'";break;}}?>	/>
				<?php echo lang('w_osversion');  ?><br/>
			<input type='Checkbox' id="carrier" name="reportname" value="operator/carrier/480" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
					foreach ($addreport->result() as $report)
				{ $cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="operator/carrier"){echo "checked='true'";break;}}?>/>
			<?php echo lang('w_carrier');  ?><br/>
			<input type='Checkbox' id="network" name="reportname" value="network/network/480" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
					foreach ($addreport->result() as $report)
				{ $cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="network/network"){echo "checked='true'";break;}}?>/>
			<?php echo lang('w_network');  ?><br/>
			<input type='Checkbox' id="devicetype" name="reportname" value="device/devicetype/480" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
					foreach ($addreport->result() as $report)
				{$cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="device/devicetype"){echo "checked='true'";break;}}?>/>
			<?php echo lang('w_devicetype'); ?><br/>
			<input type='Checkbox' id="visitpath" name="reportname" value="pagevisit/visitpath/520" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
					foreach ($addreport->result() as $report)
				{ $cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="pagevisit/visitpath"){echo "checked='true'";break;}}?>/>
			<?php echo lang('w_visitpath'); ?><br/>
			<input type='Checkbox' id="regioncountry" name="reportname" value="region/regioncountry/480" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
					foreach ($addreport->result() as $report)
				{ $cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="region/regioncountry"){echo "checked='true'";break;}}?>/>
			<?php echo lang('w_regioncountry');  ?><br/>
			<input type='Checkbox' id="regionprovince" name="reportname" value="region/regionprovince/480" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
					foreach ($addreport->result() as $report)
				{$cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="region/regionprovince"){echo "checked='true'";break;}}?>/>
			<?php echo lang('w_regionprovince');  ?><br/>
			<input type='Checkbox' id="sessiondistribution" name="reportname" value="usefrequency/sessiondistribution/500" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
					foreach ($addreport->result() as $report)
				{ $cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="usefrequency/sessiondistribution"){echo "checked='true'";break;}}?>/>
			<?php echo lang('w_sessiondistribution');  ?><br/>
			<input type='Checkbox' id="userremain" name="reportname" value="userremain/userremain/480" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
					foreach ($addreport->result() as $report)
				{ $cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="userremain/userremain"){echo "checked='true'";break;}}?>/>
			<?php echo lang('w_userremain'); ?><br/>
			<input type='Checkbox' id="usadgeduration" name="reportname" value="usetime/usadgeduration/500" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
					foreach ($addreport->result() as $report)
				{ $cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="usetime/usadgeduration"){echo "checked='true'";break;}}?>/>
			<?php echo lang('w_usadgeduration'); ?><br/>	
			<input type='Checkbox' id="channelmarket" name="reportname" value="market/channelmarket/420" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
					foreach ($addreport->result() as $report)
				{ $cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="market/channelmarket"){echo "checked='true'";break;}}?>/>
			<?php echo lang('w_channelmarket'); ?><br/>
			<input type='checkbox' id="versionview" name="reportname" value="version/versionview/400" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
				foreach ($addreport->result() as $report)
				{ $cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="version/versionview"){echo "checked='true'";break;}}?>/>
			<?php echo lang('w_versionview'); ?><br/>
				<input type='checkbox' id="phaseusetime" name="reportname" value="productbasic/phaseusetime/390" onclick="checkboxnum()"
				<?php if(isset($addreport)) 
				foreach ($addreport->result() as $report)
				{ $cvalue=$report->controller;if($report->type==2){ $report=$report->reportname;$rvalue=substr($report,4);}else{$rvalue=$report->reportname;}
				$value=$cvalue."/".$rvalue;if($value=="productbasic/phaseusetime"){echo "checked='true'";break;}}?>/>
			<?php echo lang('w_phaseusetime'); ?><br/>
		 
			<div style="display:none" id="overnum"></div>			
</div>
<script type="text/javascript">
function checkboxnum()
{	
	var num=<?php if(isset($num)) echo $num;?>;
	//alert(num);
	var item = document.getElementsByName("reportname");  
	var addcheck=0; 	
    for (var i = 0; i < item.length; i++)  
    {     	
    	if(item[i].checked==true) 
    	{   		
    		var reportinfo=eval(<?php if(isset($addreport)) {echo "'".json_encode($addreport->result())."'";}?>);
    		if(reportinfo!=eval())
    		{
    			for(j=0;j<reportinfo.length ;j++)
    			{
        			if(item[i].id==reportinfo[j].reportname)
        			{
        				num=num-1;      				
            		}
        		}  
    		}    		
    		addcheck=addcheck+1;
   		  		
        }   	
           	
    }         
    if(addcheck>8-num)
    {       
    	alert("<?php echo lang('w_overmaxnum') ?>");
    	document.getElementById('overnum').innerHTML="max";
    }
    else
    {       
    	document.getElementById('overnum').innerHTML="";
    }
        
   
}
</script>
