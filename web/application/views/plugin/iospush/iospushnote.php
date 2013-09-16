<section id="main" class="column" style='height:1000px;' >
				<h4 class="alert_warning" style='display:none' id="msg"></h4>
			<article class="module width_full" >
			<header><h3><?php echo  lang('m_ios_push');?></h3>	
				
			</header>

		<div class="module_content">
			<fieldset>
				<div>
				<label><?php echo  lang('m_app_name');?></label><p style="margin-left:220px;"><?php echo $appname;?></p><br/>
				<label><?php echo  lang('m_push_content');?></label>
				<textarea id="appcontent" rows="5"  cols="" style="width:65%;height:30px;"></textarea>
				<p></p><br/><br/><br/>
				<label><?php echo  lang('m_update_sign');?></label><input id="updatesign" style="height:18px;width:20%;margin-left:10px;" />
				<span style="color:red;margin-left:20px;"><?php echo lang('m_tips');?></span>
				<p></p><br/>
				<label><?php echo  lang('m_parameter_name');?></label><input id="paraname" style="height:18px;width:20%;margin-left:10px;" />
				<p></p><br/>
				<label><?php echo  lang('m_parameter_value');?></label><input id="paravalue" style="height:18px;width:20%;margin-left:10px;" />
				<p></p><br/>
				</div>
				<div style="width: 100%;padding-top:80px;">
						<label ><?php echo  lang('m_push_type');?></label>
							<select id="select" name="pushType"  size="1"  style="width:160px;" >
								<option name="startapp" value="1" ><?php echo  lang('m_notice');?></option>
							
							</select>

		       </div>
		       <br/>
		       <p style="color:red;margin-left:20px;"><?php echo lang('m_alltips');?></p>


				
			</fieldset>

			<input type="hidden" name="registerid" id="registerid" value="<?php echo $registerid;?>" />
			<input type="hidden" name="userKey" id="userKey" value="<?php echo $userKey;?>" />
			<input type="hidden" name="userSecret" id="userSecret" value="<?php echo $userSecret;?>" />
			<input type="hidden" name="bundleid" id="bundleid" value="<?php echo $bundleid;?>" />
			<input type="hidden" name="productid" id="productid" value="<?php echo $productid;?>" />
			<!--<?php echo $tagvalue;?> -->
			<input type="hidden" name="tagvalue" id="tagvalue" value='<?php echo $tagvalue;?>' />
			<input type="hidden" name="tagtype" id="tagtype" value="<?php echo $tagtype;?>" />

		</div>

		<footer>
			<div class="submit_link">
				<input type='submit' id='sendmsg' class='alt_btn'
					name="prod"
					value="<?php echo lang('m_sendMsg');?>">
			</div>
		</footer>	
		</article>	
	</section>
	<script type="text/javascript">
	// var netpic = document.getElementById("netpic");
	
	sendmsg.onclick = function(){
		var tagvalue = document.getElementById('tagvalue').value;
		var tagtype = document.getElementById('tagtype').value;
		var registerid = document.getElementById('registerid').value;
		var bundleid = document.getElementById('bundleid').value;
		var userKey = document.getElementById('userKey').value;
		var userSecret = document.getElementById('userSecret').value;
		var updatesign = document.getElementById('updatesign').value;
		var appcontent = document.getElementById('appcontent').value;
		var productid = document.getElementById('productid').value;
		var paraname = document.getElementById('paraname').value;
		var paravalue = document.getElementById('paravalue').value;
		if(isNaN(updatesign)||updatesign<0||updatesign>900){
			document.getElementById('msg').style.display='';
			document.getElementById('msg').innerHTML="<?php echo '更新标志超出填写范围';?>"; 
			return;
		}
		if(appcontent==''){
			document.getElementById('msg').style.display='';
			document.getElementById('msg').innerHTML="<?php echo '推送内容不可为空';?>"; 
			return;
		}
		var allSize = appcontent.length+updatesign.length+paraname.length+paravalue.length;
		if(allSize>255){
			document.getElementById('msg').style.display='';
			document.getElementById('msg').innerHTML="<?php echo '总长度超出255个字节';?>"; 
			return;
		}
		var data = {
			registerid:registerid,
			bundleid:bundleid,
			userKey:userKey,
			updatesign:updatesign,
			productid:productid,
			paraname:paraname,
			paravalue:paravalue,
			appcontent:appcontent,
			userSecret:userSecret,
			bundleid:bundleid,
			tagvalue:tagvalue,
			tagtype:tagtype		
			};
		jQuery.ajax({
				type : "post",
				url : "<?php echo site_url()?>/plugin/iospush/push",
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

</script>