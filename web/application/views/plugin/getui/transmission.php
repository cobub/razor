<section id="main" class="column" >
				<h4 class="alert_warning" style='display:none' id="msg"></h4>
			<article class="module width_full">
			<header><h3><?php echo  lang('getui_transmission');?></h3>	
				
			</header>

		<div class="module_content">
			<fieldset>
				<label ><span style="color: red;">*</span><?php echo  lang('getui_tagerapp');?></label>
								<label id="appname" ><?php echo $appname;?></label>
			</fieldset>
			<fieldset>
			<div id="setupAppTraCon">
							<div style="width:140px;float:left;margin-left:15px;">
								<span><?php echo lang('getui_transcontent');?></span>
							</div>
							<div style="height:135px;">
									<textarea  id='transmissionContentNotify' name="transmissionContentNotify" rows="5" cols="" style="height: 80px; resize: none; width: 95%;" maxlength="600"></textarea>
									
									<p style="float:right;margin-right:35px;" class="warring"><?php echo lang('getui_transcontent_noteii');?></p>
								</div>
						</div>
			</fieldset>


			<fieldset>
					<div style="padding-top:10px;padding-left:10px;">
					
					<div id="choosesetup" style="padding-left:0px;padding-top:10px;">
						
						<div style="height:50px;">
							<div style="width:120px;float:left;">
								<span><?php echo lang('getui_offline');?></span>
							</div>
							<div style="float:left;">
								<input type="radio" name="offlined" id="offline1" value="1" style="margin-left:20px;margin-right: 5px;" checked="checked"><?php  echo lang('getui_yes');?></input>
								<input type="radio" name="offlined" id="offline2" value="0" style="margin-left:20px;margin-right: 5px;"><?php echo lang('getui_no');?></input>
								<p id="offlinedTime" style="padding-top:10px;"><?php echo lang('getui_offlinetime');?><input type="text" style='height:18px;' oncontextmenu="return false;" id='offlineTime' name="offlineTime" value="2"/><?php echo lang('getui_hour');?>&nbsp;&nbsp;<span class="warring"><?php echo lang('getui_hour_note');?></span></p>
							</div>
						</div>
						
						
					</div>
				</div>
			
				
			</fieldset>

			<input type="hidden" name="appid" id="appid" value="<?php echo $appid;?>" />
			<input type="hidden" name="userKey" id="userKey" value="<?php echo $userKey;?>" />
			<input type="hidden" name="userSecret" id="userSecret" value="<?php echo $userSecret;?>" />
			<input type="hidden" name="appkey" id="appkey" value="<?php echo $appkey;?>" />
			<input type="hidden" name="productid" id="productid" value="<?php echo $productid;?>" />
			<input type="hidden" name="mastersecret" id="mastersecret" value="<?php echo $mastersecret;?>" />
			<input type="hidden" name="tagvalue" id="tagvalue" value='<?php echo $tagvalue;?>' />
			<input type="hidden" name="tagtype" id="tagtype" value="<?php echo $tagtype;?>" />

		</div>

		<footer>
			<div class="submit_link">
				<input type='submit' id='sendmsg' class='alt_btn'
					name="prod"
					value="<?php echo lang('getui_submit');?>">
			</div>
		</footer>





			
				
		


			   </article>	
		
	</section>
	<script type="text/javascript">
	// var netpic = document.getElementById("netpic");
	
	
	
	

	

	var sendmsg = document.getElementById("sendmsg");
	var is2all=document.getElementById('tagtype').value;

	sendmsg.onclick = function(){

		var tagvalue = document.getElementById('tagvalue').value;
		var productid = document.getElementById('productid').value;
		
		var mastersecret = document.getElementById('mastersecret').value;
		var appid = document.getElementById('appid').value;
		var userKey = document.getElementById('userKey').value;
		var userSecret = document.getElementById('userSecret').value;
		var appkey = document.getElementById("appkey").value;
		
		var offlineTime = document.getElementById('offlineTime').value;
		
		var pushUser =false;
		
		
		var offlined =true;
	
		if(document.getElementById('offline2').checked){
			offlined=false;
			offlineTime='';
		}

		var transmissionContentNotify = document.getElementById('transmissionContentNotify').value;
		if(transmissionContentNotify==''){
			document.getElementById('msg').style.display='';
			document.getElementById('msg').innerHTML="<?php echo '内容不可为空';?>"; 
			return;
		}
		
			var data = {
				appid:appid,
				userKey:userKey,
				pushUser:is2all,
				userSecret:userSecret,
				productid:productid,
				mastersecret:mastersecret,
				tagvalue:tagvalue,
				appkey:appkey,
				transmissionContentNotify:transmissionContentNotify,
				offlined:offlined,
				offlineTime:offlineTime
			};


			jQuery.ajax({
						type : "post",
						url : "<?php echo site_url()?>/plugin/getui/push/transmission",
						data : data,
						success : function(msg) {							
							
							var arr=eval('('+msg+')');
							  document.getElementById('msg').style.display='';
							  if(arr.flag!=1){
							  	//alert(arr.msg);
							  	document.getElementById('msg').style.display='';
							  	document.getElementById('msg').innerHTML="<?php echo lang('push_fail');?>"+'  '+arr.msg.result; 
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
							document.getElementById('msg').innerHTML="<?php echo '正在推送消息，请稍候...';?>"; 
						},
						complete : function() {
						}
					});

		
	}

$("input[name='offlined']").bind("click",function(){
			var e = $(this).val();
			if(e == 0){
				$("#offlinedTime").hide();
			}else{
				$("#offlinedTime").show();
			}
		});
	
	
	</script>