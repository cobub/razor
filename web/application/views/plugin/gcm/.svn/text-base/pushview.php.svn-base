<section id="main" class="column" style='height: 1000px;'>
	<h4 class="alert_warning" style='display: none' id="msg"></h4>
	<article class="module width_full">
		<header>
			<h3><?php echo  lang('gcm_push');?></h3>

		</header>

		<div class="module_content">
			<fieldset>
				<div>
					<label><?php echo  lang('getui_tagerapp');?></label>
					<p style="margin-left: 200px;"><?php echo $appname;?></p>
					<br />


					<p></p>
					<br /> <label><?php echo  lang('getui_note_content');?></label>
					<textarea id="appcontent" rows="5" cols=""
						style="width: 65%; height: 80px;"></textarea>
					<p></p>
					<br />
				</div>


			</fieldset>
			<input type="hidden" name="productid" id="productid"
				value="<?php echo $productid;?>" /> <input type="hidden"
				name="tagvalue" id="tagvalue" value='<?php echo $tagvalue;?>' /> <input
				type="hidden" name="userKey" id="userKey"
				value="<?php echo $userKey;?>" /> <input type="hidden"
				name="userSecret" id="userSecret" value="<?php echo $userSecret;?>" />
			<input type="hidden" name="appname" id="appname"
				value="<?php echo $appname;?>" />


		</div>

		<footer>
			<div class="submit_link">
				<input type='submit' id='sendmsg' class='alt_btn' name="prod"
					value="<?php echo lang('getui_submit');?>">
			</div>
		</footer>

	</article>
	<div style='height: 50px;'></div>

</section>

<script type="text/javascript">
	// var netpic = document.getElementById("netpic");
	
	var sendmsg = document.getElementById("sendmsg");

	sendmsg.onclick = function(){
		var tagvalue = document.getElementById('tagvalue').value;
		var userKey = document.getElementById('userKey').value;
		var userSecret = document.getElementById('userSecret').value;
		
		var appcontent = document.getElementById('appcontent').value;
		var productid = document.getElementById('productid').value;
		var appname = document.getElementById('appname').value;
		if(appcontent==''){
			document.getElementById('msg').style.display='';
			document.getElementById('msg').innerHTML="<?php echo lang('gcm_content_notempty');?>"; 
			return;
		}
			var data = {
				userKey:userKey,
				appname:appname,
				productid:productid,	
				appcontent:appcontent,
				userSecret:userSecret,
				tagvalue:tagvalue
			};

			jQuery.ajax({
						type : "post",
						url : "<?php echo site_url()?>/plugin/gcm/push/pushtoUcenter",
						data : data,
						success : function(msg) {							
							// alert(msg);
							var arr=eval('('+msg+')');
							  document.getElementById('msg').style.display='';
							  if(arr.flag!=1){
							  	//alert(arr.msg);
							  	document.getElementById('msg').style.display='';
							  	document.getElementById('msg').innerHTML="<?php echo lang('push_fail');?>"+'  '+arr.msg; 
							  }else{
							  	document.getElementById('msg').style.display='';
							  	document.getElementById('msg').innerHTML='<?php echo lang("push_success");?>'; 
							  }
																 
						},
						error : function(XmlHttpRequest, textStatus, errorThrown) {
							
							document.getElementById('msg').style.display='';
							document.getElementById('msg').innerHTML="<?php echo lang('push_fail');?>"; 
							//alert("<?php echo lang('t_error') ?>");
						},
						beforeSend : function() {							
							document.getElementById('msg').style.display='';
							document.getElementById('msg').innerHTML="<?php echo lang('gcm_pushing_wait');?>"; 
						},
						complete : function() {
						}
					});

	}


	</script>