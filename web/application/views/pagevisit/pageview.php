<?php 
if (!isset($from))
{
    $to = date('Y-m-d',time());
    $from = date('Y-m-d',strtotime("-7 day"));
}?>
<script type="text/javascript">
var time=<?php echo isset($timetype)?'"'.$timetype.'"':'"'."7day".'"'?>;
var fromTime=<?php echo isset($from)?'"'.$from.'"':'""'?>;
var toTime=<?php echo isset($to)?'"'.$to.'"':'""'?>;
</script>
<section id="main" class="column">
<h4 class="alert_success" id='msg'><?php echo lang('allview_pagevisit') ?></h4>
<!-- 访问页面明细 -->
<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo lang('pageview_headertitle') ?></h3>
<div  style="position:absolute; right:70px; top:172px;padding: 5px 10px 0;">

<select id='selectversion'  onchange="onSelectVersionChanged(this.options[this.selectedIndex].value)" >
	<option  selected  value='all'><?php echo lang("eventlistview_allversion")?></option>
	  <?php if(isset($version)):
	  foreach($version->result() as $row) {
	  if($row->version_name!='all'){?>
	    <option value=<?php echo $row->version_name; ?>><?php echo $row->version_name;?></option>
	    <?php }} endif;?>
	  </select>
	    </div>   
	<div  style="position:absolute; right:200px; top:178px;" >
  <!--  <div class="select_arrow fr"></div>
    <div id="selected_value" style="font-size:12px;" class="selected_value fr"><?php echo lang('allview_lastweek') ?></div>
    <div class="clear"></div>
    <div id="select_list_body" style="display: none;" class="select_list_body">
         <ul>
           <li><a class="" id="" href="javascript:timePhaseChanged('7day','<?php echo lang('allview_lastweek') ?>')"><?php echo lang('allview_lastweek') ?></a>
           </li>
           <li><a class="" id="" href="javascript:timePhaseChanged('1month','<?php echo lang('allview_lastmonth') ?>');"><?php echo lang('allview_lastmonth') ?></a>
           </li>
           <li><a class="" href="javascript:timePhaseChanged('3month','<?php echo lang('allview_last3month') ?>');"><?php echo lang('allview_last3month') ?></a>
           </li>
           <li><a class="" href="javascript:timePhaseChanged('all','<?php echo lang('allview_alltime') ?>');"><?php echo lang('allview_alltime') ?></a>
           </li>
      <!--      <li class="date_picker noClick"> 
              <a style=""><?php echo lang('allview_choosetime') ?></a>
            </li>
           <li style="padding:0;display:none;" class="date_picker_box noClick">
	           	<div style="width:100%;padding-left:20px;" class="selbox">
	               <span><?php echo lang('allview_datefrom') ?></span>
	              <input type="text" name="dpFrom" id="dpFrom" value="" class="datainp first_date date"><br>
	               <span><?php echo lang('allview_dateto') ?></span>
	              <input type="text" name="dpTo"  id="dpTo" value="" class="datainp last_date date">
	            </div>
              	  <div class="" style="">
              	  	<input id="any" type="button" onclick="onBtn()" value="&nbsp;<?php echo lang('allview_timebtn') ?>&nbsp;" class="any" style="margin: 5px 60px 0 50px;">
              	  </div>
           </li> 
           </ul>
		</div>-->
		<select onchange="timePhaseChanged(this.options[this.selectedIndex].value)" id='selected_value'>
				<option value=7day selected ><?php echo  lang('allview_lastweek')?></option>
				<option value=1month><?php echo  lang('allview_lastmonth')?></option>
				<option value=3month><?php echo  lang('allview_last3month')?></option>
				<option value=all><?php echo  lang('allview_alltime')?></option>
				<option value=any><?php echo  lang('allview_anytime')?></option>
	   </select>
	   <div id='selectTime'><input type="text"
				id="dpFrom"> <input type="text" id="dpTo"> <input type="submit"
				id='btn' value="<?php echo  lang('allview_timebtn')?>" class="alt_btn" onclick="onBtn()"></div>
      </div>    
    
  <span class="relative r">
			<a href="#this" class="bottun4" onclick="sever('server','server1c');"><font>?</font></a>
                	<div class="server333" id="server" style="width:620px;">
                       <div class="ser_title">
                           <b class="l"><?php echo lang('pageview_divsetttitle') ?></b>                          
                           <a class="r" href="#this" id="server1c"><img src="<?php echo base_url(); ?>assets/images/server_close.gif" /></a>
                       </div>
                       
                                <style>
								.ser_txt font{
									width:135px
								}
								</style>
                       <div class="ser_txt">
                           <dl>
                               <dt>
                               	<font><?php echo lang('pageview_remindvisitnum') ?></font>
                               	<small><?php echo lang('pageview_remindsumnum') ?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo lang('pageview_remindavgtime') ?></font>
                               	<small><?php echo lang('pageview_remindavgvisittime') ?></small>
                                <div class="clear"></div>
                               </dd>
                               <dt>
                               	<font><?php echo lang('pageview_remindexit') ?></font>
                               	<small><?php echo lang('pageview_remindexitpercent') ?></small>
                                <div class="clear"></div>
                               </dt>
                               <dd>
                               	<font><?php echo lang('pageview_remindtrend') ?></font>
                               	<small><?php echo lang('pageview_remindtrendinfo') ?></small>
                                <div class="clear"></div>
                               </dd>
                           </dl>
                       </div>
                	</div>
                </span> 

</header>
<div id="pageinfo" class="tab_content"   style="height:515px;">
<table class="tablesorter"  cellspacing="0">
	<thead>
		<tr>
			<th><?php echo lang('pageview_pagethead') ?></th>
			<th><?php echo lang('pageview_visitnumthead') ?></th>
			<th><?php echo lang('pageview_avgtimethead') ?></th>
			<th><?php echo lang('pageview_exitthead') ?></th>
		</tr>
	</thead>
	<tbody  id="content">
		
	</tbody>
</table>
</div>
<footer>
<div id="pagination"  class="submit_link">
</div>
</footer>
</article>
<div class="clear"></div>
</section>
<script type="text/javascript">
//这里必须最先加载
var version="all";
var timePhase = '7day';
var fromTime;
var toTime;
var data;
var version_array=[]; 
var verDetaildata;
var flag=1;
var currentVersionData;
//When page loads...
dispalyOrHideTimeSelect();

function timePhaseChanged(value)
{
	if(timePhase!=value){
		timePhase=value;
		dispalyOrHideTimeSelect();
		if(value=='any'){
		}
		else{
			pageselectCallback(0,0);
		}
	//	document.getElementById('selected_value').innerHTML = text;
	}
}

function dispalyOrHideTimeSelect()
{
	 var value = document.getElementById('selected_value').value;
	 if(value=='any')
	 {
		 document.getElementById('selectTime').style.display="inline";
	 }
	 else
	 { 
		 document.getElementById('selectTime').style.display="none";
	 }
} 

function onSelectVersionChanged(value)
{
	version = value;
	if(version == '<?php echo lang('pageview_jsunknow') ?>')
	{
		version = "NULL";
	}
	onConditionChanged();
	//pageselectCallback(0,0);
}
</script>

<script type="text/javascript">
	$(function() {
		$("#dpFrom" ).datepicker({dateFormat: "yy-mm-dd","setDate":new Date()});
	});

	$(function() {
		$( "#dpTo" ).datepicker({ dateFormat: "yy-mm-dd" ,"setDate":new Date()});
	});
</script>

<!--设置时间下拉框 -->
<script type="text/javascript">
function onBtn()
{  
		fromTime = document.getElementById('dpFrom').value;
		toTime = document.getElementById('dpTo').value;
	//document.getElementById('selected_value').innerHTML = "<?php echo lang('pageview_jsanytime') ?>";
		pageselectCallback(0,0);
}
</script>

<script type="text/javascript">       
$(document).ready(function(){  
	initTimeSelect();
});    

</script>
<script type="text/javascript">

function sever(txt, colse) {
	$("#" + txt).slideToggle("slow");
	$("#" + colse).click(function() {
		$("#" + txt).slideUp("slow");
	});
	
}

</script>

<script type="text/javascript">

			function onConditionChanged()
			{
			//	var getPageCountURL = "<?php echo site_url()?>/report/pagevisit/getPageCount/"+timePhase+"/"+version+"/";
			//	if(timePhase == 'any')
			//	{
			//		getPageCountURL="<?php echo site_url()?>/report/pagevisit/getPageCount/"+timePhase+"/"+version+"/"+fromTime+"/"+toTime;
			//	}
			//	jQuery.ajax({
			//		type : "post",
			//		url : getPageCountURL,
			//		success : function(msg) {
			//			initPagination(msg);
			//		},
			//		error : function(XmlHttpRequest, textStatus, errorThrown) {
						
			//		},
			//		beforeSend : function() {

			//		},
			//		complete : function() {

			//		}
			//	});
				/************************new js**************************/
				if(verDetaildata!=''&&flag==1){
					 for(var j=0;j<version_array.length;j++)
	          		 {
	             		if(version_array[j]==version){
	                  		currentVersionData=verDetaildata.content[version];
	                 		initPagination(currentVersionData.length);
	                  		pageselect(0, 0);
	                  		break;}
				     }
				}
				
			}
			
			function pageselectCallback(page_index, jq){
				/** 加载提示 图标 */
				var chart_canvas = $("#pageinfo");
	    	    var loading_img = $("<img src='<?php echo base_url();?>/assets/images/loader.gif'/>");
	    	
	    	    chart_canvas.block({
	    	        message: loading_img
	    	        ,
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
	    	    
				var myurl="<?php echo site_url()?>/report/pagevisit/getPageInfo/"+timePhase+"/"+version+"/"+page_index;
				if(timePhase == 'any')
				{
					myurl="<?php echo site_url()?>/report/pagevisit/getPageInfo/"+timePhase+"/"+version+"/"+page_index+"/"+fromTime+"/"+toTime;
				}
				jQuery.ajax({
					type : "post",
					url : myurl,
					success : function(msg) {    
						if( eval( "(" + msg + ")" )==""){
							flag="";
							initPagination(0);
							var container = document.getElementById("content");
							setTBodyInnerHTML(container,'');    
						//	clearList();   
						}
						else{
						flag=1;
						getJSONData(eval( "(" + msg + ")" ),version); }                       
					},
					error : function(XmlHttpRequest, textStatus, errorThrown) {
						
					},
					beforeSend : function() {
						
					},
					complete : function() {
						chart_canvas.unblock();
					},
					
				});
				
                return false;
            } 

		     // 清除列表框  
	        function clearList() {
	        	var id = document.getElementById("selectversion");
		        if(version_array.length>0){
	        	for(var i=0;i<version_array.length;i++)
                {
                	if ( version_array[i] == 'all')
                	{             	
                	}
                	else
                	{
                    	id.remove(1);
                    }
                }
	        	
                }
	        }

			//解析json数据                       
            function getJSONData(data,version){
            	verDetaildata = data;
            	for(var key in data.content){
                	version_array.push(key); 
                	 }
           //   	if(flag==1){
            //      	flag=0;
                 // 	alert('sfgddd');
                //   	for(var i=0;i<version_array.length;i++)
                 //   {
                 //   	if ( version_array[i] == 'all')
                //    	{             	
                 //   	}
                //    	else
               //     	{
               //     		var o = new Option(version_array[i], version_array[i]);         		
               //     		$(o).html(version_array[i]); 
               //    		$("#selectversion").append(o);
                    		            
               //         }
              //	 }
             // 	}
              	 for(var j=0;j<version_array.length;j++)
          		 {
             		if(version_array[j]==version){
                  		//var eachVersionData = data.content[version];
                  		currentVersionData=data.content[version];
                  		//var versionData = [];
                 		initPagination(currentVersionData.length);
                  		pageselect(0, 0);
                  		//var htmlText='';
                  		//for(var i=0;i<10;i++){
                      	///	var eachVersionDataItem = currentVersionData[i];
                      	//	versionData.push(eachVersionDataItem.activity_name);
                  		//	versionData.push(eachVersionDataItem.accesscount);
                  		//	versionData.push(eachVersionDataItem.avertime);
                  		//	versionData.push(eachVersionDataItem.exitcount);
                  		//	htmlText = htmlText+"<tr>";
                		//	htmlText = htmlText+"<td>"+eachVersionDataItem.activity_name+"</td>";
                		//	htmlText = htmlText+"<td>"+eachVersionDataItem.accesscount+"</td>";
                		//	htmlText = htmlText+"<td>"+eachVersionDataItem.avertime+"</td>";
                		//	htmlText = htmlText+"<td>"+eachVersionDataItem.exitcount+"</td>";
                		//	htmlText = htmlText+"</tr>";
                  		//	}
                  		//alert(versionData);
                  		//var container = document.getElementById("content");
						//setTBodyInnerHTML(container,htmlText);
              			break;
                  		}	   
          		}
             }

            function pageselect(page_index, jq){
                var page_num=(page_index+1)*10;
                if((currentVersionData.length-page_num)<0){
                    page_num=page_index*10+currentVersionData.length-page_index*10;
                }
                var htmlText='';
            	for(var i=page_index*10;i<page_num;i++){
            		var eachVersionDataItem = currentVersionData[i];
            		htmlText = htmlText+"<tr>";
        			htmlText = htmlText+"<td>"+eachVersionDataItem.activity_name+"</td>";
        			htmlText = htmlText+"<td>"+eachVersionDataItem.accesscount+"</td>";
        			htmlText = htmlText+"<td>"+eachVersionDataItem.avertime+"</td>";
        			htmlText = htmlText+"<td>"+eachVersionDataItem.exitcount+"</td>";
        			htmlText = htmlText+"</tr>";
                }
            	var container = document.getElementById("content");
				setTBodyInnerHTML(container,htmlText);
            }
           
            /** 
             * Callback function for the AJAX content loader.
             */
            function initPagination(count) {
                var num_entries = count/<?php echo PAGE_NUMS;?>;
                // Create pagination element
                $("#pagination").pagination(num_entries, {
                    num_edge_entries: 2,
                    prev_text: '<?php echo lang('allview_jsbeforepage') ?>',       //上一页按钮里text 
                    next_text: '<?php echo lang('allview_jsnextpage') ?>',       //下一页按钮里text            
                    num_display_entries: 8,
                    callback: pageselect,
                    items_per_page:1
                });
             }

                    
            // Load HTML snippet with AJAX and insert it into the Hiddenresult element
            // When the HTML has loaded, call initPagination to paginate the elements        
            $(document).ready(function(){ 
            	//document.getElementById("");  
            	pageselectCallback(0,0); 
            });    


            function setTBodyInnerHTML(tbody, html) {
          	  var temp = tbody.ownerDocument.createElement('div');
          	  temp.innerHTML = '<table><tbody id=\"content\">' + html + '</tbody></table>';
          	  tbody.parentNode.replaceChild(temp.firstChild.firstChild, tbody);
          	}       
</script>

