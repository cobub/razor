<section id="main" class="column" style='height:1000px;' >
				<h4 class="alert_warning" style='display:none' id="msg"></h4>
			<article class="module width_full" >
			<header><h3><?php echo  lang('getui');?></h3>	
				
			</header>

		<div class="module_content">
			<fieldset>
				<div>
				<label><?php echo  lang('getui_tagerapp');?></label><p style="margin-left:200px;"><?php echo $appname;?></p><br/>
				
				<label><?php echo  lang('getui_note_title');?></label><input id="appntitle" style="height:18px;width:65%;margin-left:10px;" />
				<p></p><br/>
				<label><?php echo  lang('getui_note_content');?></label>
				<textarea id="appcontent" rows="5"  cols="" style="width:65%;height:80px;"></textarea>
				<p></p><br/>
				</div>
				<div style="width: 100%;padding-top:80px;">
						<label ><?php echo  lang('getui_after_clicknote');?></label>
							<select id="select" name="pushType"  size="1"  style="width:160px;" >
								<option name="startapp" value="1" ><?php echo  lang('getui_startapp');?></option>
								<option name="opennet" value="2" ><?php echo  lang('getui_opennet');?></option>
								<option name="downapk" value="3" ><?php echo  "下载应用";?></option>
							
							</select>

		       </div>
		        <div id="opennet" name="show1" style="height:130px; display:none;">
							<div style="height:40px;margin-top:20px;margin-left:20px;">
								<input id="opencheck" name="opencheck" type="checkbox" lang="1" checked="checked" style="padding-left:20px;">打开网页前需要用户确认</input>
							</div>
							<div style="height:70px;line-height:30px;">
								<label ><span style="color: red;">*</span><?php echo  lang('getui_neturl');?></label>
									
									<span class="errStar urlLink_err" style="display:none;"></span>
								<br/>
								<input type="text" id="urladdress" name="urlLink" value="http://" style="width:250px;height:25px;resize:none;line-height:25px;margin-left:50px;"></input>
								<span style="margin-left:20px;" class="warring"><?php echo  lang('getui_neturl_note');?></span>
							</div>
				</div>


				<div id="downapp" name="downapp" style="height:300px;margin-top:20px;">
							
						
							<table>
								<tbody>

									<tr><td><label >弹框标题</label></td><td ><input id="showtitle" name="popTitle"  maxlength="20"  type="text" style='width:230px;' ></input></td></tr>
									<br><tr><td><label>弹框图片（可选）</label></td><td><input type="file" name="filename" id="popPicture" value="选择文件"></input>
										<input type="hidden" name="popPicture"></input>
											<span >图标大小要求：25K以内</span></td></tr>
									<br><tr><td><label>弹框内容*</label></td><td><textarea id="showmessage" name="popWords"  maxlength="50" rows="5" cols="" style="width:690px;height:80px;"></textarea>
							</td></tr>

							<br><tr><td><label>按钮名称*</label></td><td>
								<span>左边按钮名称：</span><input style='width:80px;'  name="popFirstButton" id="lbname" value="下载" maxlength="20"/>
								<br/><br/><span>右边按钮名称：</span><input  style='width:80px;' name="popSecondButton" id="rbname" value="取消" maxlength="20"/>
								
								<tr><td><label >应用下载地址*
								</label></td><td><input type="text" style='width:230px;' id="urladdress" name="loadUrl" value="http://"  maxlength="100"></input>
							</td></tr>
								

							<br><tr><td><label >应用名称*
							</label></td><td><input type="text" style='width:230px;' name="loadTitle" value=""  maxlength="40"></input>
							</td></tr>
							</tbody>
						</table>

					
				</div>

				
			</fieldset>

			
			<fieldset>
					<div style="padding-top:10px;padding-left:10px;">
					<div id="setup" onclick="showchoosesetup();" >
						
						<a href="javascript:void(0)" style="font-size:14px;"><?php echo lang('getui_set'); ?></a>
					</div>
					<div id="choosesetup" style="padding-left:0px;padding-top:10px;display:none">
						<div id="setupAppTraCon">
							<div style="width:140px;float:left;">
								<span><?php echo lang('getui_transcontent');?></span>
							</div>
							<div style="height:135px;">
									<textarea  id='transmissionContentNotify' name="transmissionContentNotify" rows="5" cols="" style="height: 80px; resize: none; width: 95%;" maxlength="600"></textarea>
									<p style="float:left;margin-left:140px;" class="warring"><?php echo lang('getui_transcontent_note');?></p>
									<p style="float:right;margin-right:35px;" class="warring"><?php echo lang('getui_transcontent_noteii');?></p>
								</div>
						</div>
						<div style="height:50px;">
							<div style="width:120px;float:left;">
								<span><?php echo lang('getui_clear');?></span>
							</div>
							<div style="float:left;">
							<input type="radio" name="notyCleared" id="clear1" value="1" style="margin-left:20px;margin-right: 5px;" checked="checked"><?php echo lang('getui_yes');?></input>
							<input type="radio" name="notyCleared" id="clear2" value="0" style="margin-left:20px;margin-right: 5px;"><?php echo lang('getui_no');?></input>
							<span style="color:red;margin-left:20px;"><?php echo lang('getui_clearnote');?></span>
							</div>
						</div>
						<div style="height:50px;">
							<div style="width:120px;float:left;">
								<span><?php echo lang('getui_ring');?></span>
							</div>
							<div style="float:left;">
							<input type="radio" name="notyBelled" id="ring1" value="1" style="margin-left:20px;margin-right: 5px;" checked="checked"><?php echo lang('getui_yes');?></input>
							<input type="radio" name="notyBelled" id="ring2" value="0" style="margin-left:20px;margin-right: 5px;"><?php  echo lang('getui_no');?></input>
							</div>
						</div>
						<div style="height:50px;">
							<div style="width:120px;float:left;">
								<span><?php echo lang('getui_v');?></span>
							</div>
							<div style="float:left;">
							<input type="radio" name="notyVibrationed" id="vibrate1" value="1" style="margin-left:20px;margin-right: 5px;" checked="checked"><?php echo lang('getui_yes');?></input>
							<input type="radio" name="notyVibrationed" id="vibrate2" value="0" style="margin-left:20px;margin-right: 5px;"><?php echo lang('getui_no');?></input>
							</div>
						</div>
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
						<div style="height:70px;display:inline-block;">
							<div style="width: 120px; float: left; margin-top: 22px;"><span><?php echo lang('getui_note_logo');?></span></div>
							
							<br>
							<div class="app_icon_local" style="width: 480px; margin-left: 109px; margin-top: -6px;">
								<input type="hidden" id='logo_url' name="logo_url" value="" />
								<input type="hidden" name="app_icon_type" value="1" />
								<img src="<?php echo base_url();?>assets/images/launcher.png" id="logo_url_img" alt="" style="margin-bottom: -6px; margin-right: 20px; width: 42px;heigth:42px;"/>
								<input type="file" id="logo_upload" style="margin-left:20px"></input> <p style='margin-left:80px;'><?php echo lang('getui_pic_note');?></p>
								
							</div>
							
						</div>
						<div id="fileQueue" style="width:80%;height:60px;margin-left:100px;" ></div>
					</div>
				</div>
			
				
			</fieldset>

			<input type="hidden" name="appid" id="appid" value="<?php echo $appid;?>" />
			<input type="hidden" name="userKey" id="userKey" value="<?php echo $userKey;?>" />
			<input type="hidden" name="userSecret" id="userSecret" value="<?php echo $userSecret;?>" />
			<input type="hidden" name="appkey" id="appkey" value="<?php echo $appkey;?>" />
			<input type="hidden" name="productid" id="productid" value="<?php echo $productid;?>" />
			<input type="hidden" name="mastersecret" id="mastersecret" value="<?php echo $mastersecret;?>" />
			<!--<?php echo $tagvalue;?> -->
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

			   </article>	<div style='height:50px;'></div>
		
	</section>
	<script type="text/javascript">
	// var netpic = document.getElementById("netpic");
	
	
	

	var tduser = document.getElementById("tduser");
	var ctime = document.getElementById("ctime");
	var sendmsg = document.getElementById("sendmsg");

$("#logo_upload").uploadify({
		'uploader'       : '<?php echo base_url();?>assets/swf/uploadify.swf',
		'script'         : '<?php echo base_url();?>assets/swf/uploadify.php',
		'cancelImg'      : '<?php echo base_url();?>assets/images/cancel.png',
		'folder'         : 'uploads',
		'queueID'        : 'fileQueue',
		'auto'           : true,
		'multi'          : true,
		'fileExt'   	 : '*.png;*.jpg',
		'fileDesc' 		 : '只支持 (.png,.jpg)文件,文件大小不超过5K',
		'sizeLimit'      : 5 * 1024 ,
		'onComplete': function(event, ID, fileObj, response, data) {
								var filename = response.substring(response.lastIndexOf("/")+1);
								var baseurl="<?php echo base_url();?>uploads/";
								document.getElementById('logo_url').value = "<?php echo base_url();?>uploads/"+filename;
								$('#logo_url_img').attr("src","<?php echo base_url();?>uploads/"+filename);
								
							},
	 'onError'   : function(event, ID, fileObj){
		       		if(fileObj.size > 5 * 1024){
		       			alert("文件："+fileObj.name+"大小超出5 KB，请重新上传。");
		       		}else{
		       			alert("文件:" + fileObj.name + " 上传失败");
		       		}}


	});
	sendmsg.onclick = function(){
		var tagvalue = document.getElementById('tagvalue').value;
		
		var mastersecret = document.getElementById('mastersecret').value;
		var appid = document.getElementById('appid').value;
		var userKey = document.getElementById('userKey').value;
		var userSecret = document.getElementById('userSecret').value;
		var appkey = document.getElementById("appkey").value;
		var appntitle = document.getElementById('appntitle').value;
		var appcontent = document.getElementById('appcontent').value;
		if(appntitle==''){
			document.getElementById('msg').style.display='';
			document.getElementById('msg').innerHTML="<?php echo '标题不可为空';?>"; 
			return;
		}
		if(appcontent==''){
			document.getElementById('msg').style.display='';
			document.getElementById('msg').innerHTML="<?php echo '内容不可为空';?>"; 
			return;
		}
		
		var selectvalue = document.getElementById('select').value;
		var startapp=false;
		var opennet=false;
		var downapp=false;
		var opencheck = document.getElementById('opencheck').checked;
		var urladdress = document.getElementById('urladdress').value;
		var productid = document.getElementById('productid').value;
		// var showtitle = document.getElementById('showtitle').value;
		// var shownetpic = document.getElementById('shownetpic').value;
		// var showmessage = document.getElementById('showmessage').value;
		// var appurladdress = document.getElementById('appurladdress').value;
		// var loadappname = document.getElementById('loadappname').value;
		
		var offlineTime = document.getElementById('offlineTime').value;
		var is2all=document.getElementById('tagtype').value;

		
		
		var notyCleared = true;
		var notyBelled =true;
		var notyVibrationed =true;
		var offlined =true;
		var logo_url = document.getElementById('logo_url').value;
	
		if(document.getElementById('offline2').checked){
			offlined=false;
			offlineTime='';
		}

		if(document.getElementById('vibrate2').checked){
			notyVibrationed = false;
		}

		if(document.getElementById('ring2').checked){
			notyBelled=false;
		}

		if(document.getElementById('clear2').checked){
			notyCleared=false;
		}

		var transmissionContentNotify = document.getElementById('transmissionContentNotify').value;
		


		
		
		// alert(selectvalue);
		
			var data = {
				appid:appid,
				userKey:userKey,
				pushUser:is2all,
				productid:productid,
				appntitle:appntitle,
				appcontent:appcontent,
				userSecret:userSecret,
				mastersecret:mastersecret,
				tagvalue:tagvalue,
				appkey:appkey,
				pushType:selectvalue,
				opencheck:opencheck,
				urladdress:urladdress,
				transmissionContentNotify:transmissionContentNotify,
				notyCleared:notyCleared,
				notyBelled:notyBelled,
				notyVibrationed:notyVibrationed,
				offlined:offlined,
				offlineTime:offlineTime,
				logo_url:logo_url
			};

			jQuery.ajax({
						type : "post",
						url : "<?php echo site_url()?>/plugin/getui/push",
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


function showchoosesetup(){
		if(choosesetup.style.display == ""){
			choosesetup.style.display = "none";
		}else{
			choosesetup.style.display = ""
		}
	}

	$("#select").change(function(){
			var val = Number($(this).val());
			switch(val){
				case 1:	//启动应用
					$("#downapp").hide();
					$("#opennet").hide();
					$("#setupAppTraCon").show();
					break;
				case 2:	//打开网页
					$("#downapp").hide();
					$("#opennet").show();			
					$("#setupAppTraCon").hide();
					break;
				case 3:	//下载应用
					$("#downapp").show();
					$("#opennet").hide();
					$("#setupAppTraCon").hide();
					break;
			}
		});
	var send_time_num = 0;
	</script>