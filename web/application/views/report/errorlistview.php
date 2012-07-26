<script type="text/javascript">
var isfix=<?php echo isset($isfix)?'"'.$isfix.'"':'""'?>;
</script>
<div id="errorview">
<style type="text/css">
.list_box {
	position: relative;
	width: 250px;
    height:200px;
	margin-left: 80px;
	background: #f3f3f3;
	border: 1px solid #CCC;
   overflow-y:auto;
    overflow-x:auto;
   
}

.keywords_list {
	margin: 0;
	padding: 0;
	list-style: none;
}

.hover {
	background: #33CCFF;
	color: #333333;
}
</style>
<!-- 错误列表 --> <article class="module width_full"> <header>
<h3 class="tabs_involved"><?php echo lang('errorlistview_headerinfo') ?><?php
if($isfix==0)
{
if(isset($nonum)&&$nonum!="")
{echo $nonum."&nbsp&nbsp".lang('errorlistview_headerunnum');}
else {echo lang('errorlistview_headerun0num');}
}
else 
{
if(isset($nonum)&&$nonum!="")
{echo $nonum."&nbsp&nbsp".lang('errorlistview_headernumed');}
else {echo lang('errorlistview_header0numed');}
}
?>&nbsp&nbsp<?php echo  lang('errorlistview_headerfixerror')?></h3>
<div style="float: right; margin-right: 10%; padding: 5px 0;"><select 
	name='selecterrorlist' id='isfixlist'>
	<option selected value="0"><?php echo  lang('errorlistview_selectnofix')?></option>
	<option value="1"><?php echo  lang('errorlistview_selectfix')?></option>
</select> <input type="text" id='devicetype' name='devicetype' value='<?php echo  lang('errorlistview_devicetype')?>'
	onClick="soClick(this)" onBlur="soBlur(this)" onkeyup="getinforminfo(this.value)"
	">
<div class="list_box" style="display: none">
<div class="keywords_list"></div>
</div>
</div>
<div style="position:absolute;right:4%; padding: 5px 0;">
<input type="button" id='update' value="<?php echo  lang('errorlistview_devicebtn')?>" class="alt_btn"
	onclick="updateerrorlist() " style="position: relative">
</div>
</header>
<table class="tablenosorter" cellspacing="0">
	<thead>
		<tr>
			<th width="5%"><input name="selectall" type="checkbox" id="allsss" value="all"
				onClick="checkall(this)" /></th>
			<th width="55%"><?php echo  lang('errorlistview_abstractthead')?></th>
			<th width="15%"><?php echo  lang('errorlistview_appversionthead')?></th>
			<th width="15%"><input id="up" onClick="showdown()" type="image"
				src="<?php
				echo base_url ();
				?>assets/images/up.gif" /> <input
				type="image" style="display: none" id="down" onClick="showup()"
				src="<?php
				echo base_url ();
				?>assets/images/down.gif" /><?php echo  lang('errorlistview_occurtimethead')?></th>
			<th width="10%"><?php echo  lang('errorlistview_occurfrenthead')?></th>
		</tr>
	</thead>
	<tbody id="errorlistdetail"> 
			<?php
			if (isset ( $errorlistnofix )) :
				foreach ( $errorlistnofix->result_array () as $row ) {
					?>
				<tr>
			<td width="5%"><input name="select" type="checkbox"
				value="<?php echo $row['title_sk']."|</br>|".$row['product_sk']."|</br>|".$row['title']."|</br>|".$row['version_name']?>" id="profile" /></td>
			<td width="55%">
			<font color="black"><a href="<?php echo site_url()."/report/errorlog/detailstacktrace/".$row['title_sk']."/".$row['product_sk']."/".$isfix ?>">
			<?php if($row ['title']==""){echo "null";}
				else{	echo $row ['title'];}
					?></a></font></td>
			<td width="10%"><?php if($row ['version_name']==""){echo lang('errorlistview_tbodyunknow');}
				else{echo $row ['version_name'];} ?></td>
			<td width="15%"><?php
					echo $row ['time'];
					?></td>
			<td width="15%"><?php
					echo $row ['errorcount'];
					?></td>
		</tr> 
			<?php
				}
			 endif;
			?>											
			</tbody>
</table>

<footer>

<p>&nbsp&nbsp&nbsp&nbsp&nbsp<a href="#" onClick="changefix()"><?php echo  lang('errorlistview_linkfix')?></a>
&nbsp&nbsp&nbsp&nbsp&nbsp<a href="#" onClick="changenofix()"><?php echo  lang('errorlistview_linknofix')?></a></p>
<div id="pagination" style="position:absolute;right:4%;top:1150px;">
</div>		
</footer> </article></div>
<div class="clear"></div>
<div class="spacer"></div>
</section>
<script type="text/javascript">
$('.list_box').hide();
</script>
<!-- get error info by pagnum -->
<script>
function pageselecterrorinfo(page_index,jq)
{   page_index = arguments[0] ? arguments[0] : "0";  
    jq = arguments[1] ? arguments[1] : "0"; 	
	var myurl="<?php echo site_url().'/report/errorlog/geterrorlistpageinfo/'?>"+page_index+"/"+isfix;
	jQuery.ajax({
		type : "post",
		url : myurl,
		success : function(msg) {
			document.getElementById('errorlistdetail').innerHTML = msg;
		},
		error : function(XmlHttpRequest, textStatus, errorThrown) {
			alert("<?php echo  lang('errorlistview_alertinfo')?>");		
		},
		beforeSend : function() {
			

		},
		complete : function() {
			
		}
})
}
function initPagination() {
    var num_entries = <?php if(isset($nonum)){echo $nonum;} else{echo 90;} ?>/<?php echo PAGE_NUMS;?>;
    // Create pagination element
    $("#pagination").pagination(num_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo  lang('allview_jsbeforepage')?>',       //上一页按钮里text 
        next_text: '<?php echo  lang('allview_jsnextpage')?>',       //下一页按钮里text            
        num_display_entries: 8,
        callback: pageselecterrorinfo,
        items_per_page:1
    });
 }
// When the HTML has loaded, call initPagination to paginate the elements        
$(document).ready(function(){      
	initPagination();
	pageselecterrorinfo(0);
});
</script>
<!-- 按照时间对数据进行排序 -->
<script type="text/javascript">
function showdown()
{ 	
  document.getElementById('down').style.display="";
  document.getElementById('up').style.display="none" ;  
  $(".tablenosorter").tablesorter(); 
	  
} 
</script>
<script type="text/javascript">
 function showup()
 {	 
	 document.getElementById('up').style.display="";
	 document.getElementById('down').style.display="none" ;
	 $(".tablenosorter").tablesorter();
 }
</script>
<!-- 设置文本框中的值为设备类型或"" -->
<script type="text/javascript">
function soClick(obj){
	
    if(obj.value=="<?php echo  lang('errorlistview_jsdevicetype')?>")
    {
      obj.value =""; 
    }
}

function soBlur(obj){
	
    if(obj.value=="")
    {
      obj.value ="<?php echo  lang('errorlistview_jsdevicetype')?>"; 
    }   
}

</script>
<!-- 获得设备类型信息提示 -->
<script type="text/javascript">
function getinforminfo(name)
{	
	var data = {device:name};	
	$.ajax({
		type:"POST",
		url:"<?php echo site_url ()?>/report/errorlog/getdevicename",
		data:data,
		success:function(html) {
			$('.list_box').show();
			$('.keywords_list').html(html);
			$('li').hover(function(){
				$(this).addClass('hover');
			},function(){
				$(this).removeClass('hover');
			});
			$('li').click(function(){				
				$('#devicetype').val($(this).text());				
				$('.list_box').hide();
			});
		}
	});
	return false;

	
	
}
</script>
<!--根据所选类型更新错误列表中的数据 -->
<script type="text/javascript">
function updateerrorlist()
{
	var fix =document.getElementById('isfixlist').value;
	var type=document.getElementById('devicetype').value;	
	if(type==""||type=="<?php echo  lang('errorlistview_jsdevicetype')?>")
	{  
		type="";		
		var data = {
			isfix:fix	,
			devicename:type					
		};
		
	}
	else
	{
		var data = {
				isfix:fix	,
				devicename:type
										
		};
	}
	
		jQuery
				.ajax({
					type : "post",
					url : "<?php
					echo site_url ()?>/report/errorlog/updaterrorlist",
					data : data,
					success : function(msg) {						
						document.getElementById('errorview').innerHTML = msg;						 
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						alert("<?php echo  lang('errorlistview_alertinfo')?>");
					},
					beforeSend : function() {						

					},
					complete : function() {
					}
				});
	
}
</script>
<!-- 全选或全不选 -->
<script type="text/javascript">
function checkall(cb)
{
	 var cba=document.getElementsByTagName("input");
	 for(var i = 0 ; i < cba.length ; i++)
	{
	
	
		if (cb.checked == true)
	    {
			if(cba[i].type == "checkbox")	
		    {	
				
			    cba[i].checked = cb.checked;
			}
			
	    }
	    else
	    {
	    	if(cba[i].type == "checkbox")
			{
				cba[i].checked = cb.checked;
			}
	    }
		
	
	}

}
</script>




<!-- 标记   已修复-->
<script type="text/javascript">
function changefix()
{
	 var cba=document.getElementsByTagName("input");
	 var checked = false; 
	 for(var i = 0 ; i < cba.length ; i++)
	{
	
			if(cba[i].type == "checkbox")	
		    {					
			   if(cba[i].checked==true)
			   {	
				   checked = true; 	
				   var version=cba[i].value;				 			 
				if(version=="all")
				{
					 var fix=1;
					 var data = {
								version:version	,
								fix:fix
														
				};
					 jQuery.ajax({
							type : "post",
							url : "<?php
							echo site_url ()?>/report/errorlog/fixerrorinfo",
							data : data,
							success : function(msg) {								
								document.getElementById('errorview').innerHTML = msg;						 
							},
							error : function(XmlHttpRequest, textStatus, errorThrown) {
								alert("<?php echo  lang('errorlistview_alertinfo')?>");
							},
							beforeSend : function() {							

							},
							complete : function() {
							}
						});	
					break;
					
				}
				  var fix=1;
				 var fixdata= version.split("|</br>|");				 
					 var titlesk=fixdata[0];					
					 var product_sk=fixdata[1];				
					 var titles=fixdata[2];									
					 var product_version=fixdata[3];
				  var data = {
						    titlesk:titlesk	,				
							product_sk:	product_sk,			
							titles:	titles,											
							product_version:product_version,							
							fix:fix													
					};
				 jQuery.ajax({
						type : "post",
						url : "<?php
						echo site_url ()?>/report/errorlog/fixerrorinfo",
						data : data,
						success : function(msg) {							
							document.getElementById('errorview').innerHTML = msg;						 
						},
						error : function(XmlHttpRequest, textStatus, errorThrown) {
							alert("<?php echo  lang('errorlistview_alertinfo')?>");
						},
						beforeSend : function() {						

						},
						complete : function() {
						}
					});	
				 		
			   }
			}
			
	}	
	 if (!checked) 
     {   
         alert("<?php echo  lang('errorlistview_alertcheck')?>"); 
         return; 
     } 
	   
}
</script>
<!-- 将错误信息标记为  未修复 -->
<script type="text/javascript">
function changenofix()
{
	 var cba=document.getElementsByTagName("input");
	 var checked = false; 
	 for(var i = 0 ; i < cba.length ; i++)
	{
	
			if(cba[i].type == "checkbox")	
		    {					
			   if(cba[i].checked==true)
			   {				  
				   checked = true; 
				 var version=cba[i].value;
					if(version=="all")
					{
						 var fix=0;
						 var data = {
									version:version	,
									fix:fix
															
							};
						 jQuery.ajax({
								type : "post",
								url : "<?php
								echo site_url ()?>/report/errorlog/fixerrorinfo",
								data : data,
								success : function(msg) {									
									document.getElementById('errorview').innerHTML = msg;						 
								},
								error : function(XmlHttpRequest, textStatus, errorThrown) {
									alert("<?php echo  lang('errorlistview_alertinfo')?>");
								},
								beforeSend : function() {								

								},
								complete : function() {
								}
							});	
						break;
						
					}
				 var fix=0;
				 var fixdata= version.split("|</br>|");				 
				 var titlesk=fixdata[0];					
				 var product_sk=fixdata[1];				
				 var titles=fixdata[2];									
				 var product_version=fixdata[3];
			  var data = {
					    titlesk:titlesk	,				
						product_sk:	product_sk,			
						titles:	titles,												
						product_version:product_version,							
						fix:fix						
					};
				 jQuery.ajax({
						type : "post",
						url : "<?php
						echo site_url ()?>/report/errorlog/fixerrorinfo",
						data : data,
						success : function(msg) {							
							document.getElementById('errorview').innerHTML = msg;						 
						},
						error : function(XmlHttpRequest, textStatus, errorThrown) {
							alert("<?php echo  lang('errorlistview_alertinfo')?>");
						},
						beforeSend : function() {						

						},
						complete : function() {
						}
					});				
			   }
			}
			
	}	
	 if (!checked) 
     {   
         alert("<?php echo  lang('errorlistview_alertcheck')?>"); 
         return; 
     } 
}
</script>



