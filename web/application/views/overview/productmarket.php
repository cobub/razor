<section id="main" class="column">
<div style="height:420px;">
		<iframe src="<?php echo site_url() ?>/report/market/addchannelmarketreport"  frameborder="0" scrolling="no"style="width:100%;height:100%;"></iframe>		
	</div>			
	<!-- page visit detail -->
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_mk_channelList') ?></h3>
			<div class="submit_link">
				<label><?php echo lang('v_man_ch_name') ?></label>&nbsp;
				<select id='selectversion'
					onchange="onSelectVersionChanged(this.options[this.selectedIndex].value)">
					<option selected value='all'><?php echo lang("v_rpt_el_allChannel")?></option>
				  	<?php
						if (isset ( $channel )) :
							foreach ( $channel->result () as $row ) {
								if ($row->channel_name != 'all') {
									?>
				    <option value=<?php echo $row->channel_name; ?>><?php echo $row->channel_name;?></option>
				    <?php } } endif;?>
	  			</select>
	  			
	  			&nbsp;&nbsp;
				<input type="button" id='exportpagebtn' value="<?php echo  lang('g_exportToCSV')?>" class="alt_btn" onclick="exportPage()"> 
	
			</div>			
		</header>
		<div id="pageinfo" class="tab_content">
			<table class="tablesorter" cellspacing="0">
				<thead>
					<tr>
						<th><?php echo lang('v_man_au_channelName') ?></th>
						<th><?php echo lang('g_date') ?></th>
						<th><?php echo lang('t_newUsers') ?></th>
						<th><?php echo lang('t_activeUsers') ?></th>
						<th><?php echo lang('t_sessions') ?></th>
						<th><?php echo lang('t_averageUsageDuration') ?></th>
						<th><?php echo lang('t_accumulatedUsers') ?></th>
					</tr>
				</thead>
				<tbody id="content">

				</tbody>
			</table>
		</div>
		<footer>
			<div id="pagination" class="submit_link"></div>
		</footer>
	</article>
	<div class="clear"> </div>
</section>


<script type="text/javascript">
//Here must first load
var version="all";
var weburl = '';
var data;
var version_array=[]; 
var verDetaildata;
var flag=1;
var currentVersionData;
var totalaccesscount = 1;
var totalexitcount = 1;
function onSelectVersionChanged(value)
{
	version = value;
	if(version == '<?php echo lang('t_unknow') ?>')
	{
		version = "NULL";
	}
	onConditionChanged();
}
</script>

<script type="text/javascript">

		function onConditionChanged()
		{
            var myurl = "<?php echo site_url()?>/report/market/getchanneldata/" + version ;
			pageselectCallback(myurl);
		}
		
		function pageselectCallback(url){
			/** Load splats */
			var chart_canvas = $("#pageinfo");
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
    	    
		////	var myurl=" echo site_url()/report/pagevisit/getPageInfo/"+version+"/"+page_index;
 			var data = {
            		channel: version
        		};
        		
				myurl = url;
				//alert(myurl);
				jQuery.ajax({
					type : "post",
					data : data,
					url : myurl,
					success : function(msg) {   
						if( eval( "(" + msg + ")" )==""){
							flag="";
							initPagination(0);
							var container = document.getElementById("content");
							setTBodyInnerHTML(container,'');     
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

		     // Clear the list box 
	        function clearList() {
	        	var id = document.getElementById("selectversion");
		        if(version_array.length>0)
		        {
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

			//Parsing json data                   
            function getJSONData(data,version){
            			verDetaildata = data;
                  		currentVersionData=data.content;                 		
                 		initPagination(currentVersionData.length);
                  		pageselect(0, 0);           
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
        			htmlText = htmlText+"<td>"+eachVersionDataItem.channel_name+"</td>";
        			htmlText = htmlText+"<td>"+eachVersionDataItem.datevalue+"</td>";
        			htmlText = htmlText+"<td>"+eachVersionDataItem.newusers+"</td>";
        			htmlText = htmlText+"<td>"+eachVersionDataItem.startusers+"</td>";
        			htmlText = htmlText+"<td>"+eachVersionDataItem.sessions+"</td>";
        			if(eachVersionDataItem.sessions>0){
        				htmlText = htmlText+"<td>"+((parseFloat(eachVersionDataItem.usingtime))/1000/eachVersionDataItem.sessions).toFixed(2)+"<?php echo lang('g_s');?></td>";
        			} else{
        				htmlText = htmlText+"<td>"+0+"</td>";
        			}
        			 
        			htmlText = htmlText+"<td>"+eachVersionDataItem.allusers+"</td>";
        			htmlText = htmlText+"</tr>";
                }
            	var container = document.getElementById("content");
				setTBodyInnerHTML(container,htmlText);		
				return false;		
            }
           
            /** 
             * Callback function for the AJAX content loader.
             */
            function initPagination(count) {
                var num_entries = count/<?php echo PAGE_NUMS;?>;
                // Create pagination element
                $("#pagination").pagination(num_entries, {
                    num_edge_entries: 2,
                    prev_text: '<?php echo lang('g_previousPage') ?>',       //Previous button in the text
                    next_text: '<?php echo lang('g_nextPage') ?>',       //Next button in the text         
                    num_display_entries: 4,
                    callback: pageselect,
                    items_per_page:1
                });
             }
     
            $(document).ready(function(){ 
            	document.getElementById('selectversion').value='all';
            	var myurl = "<?php echo site_url()?>/report/market/getchanneldata/" + version ;
            	pageselectCallback(myurl); 
            });    


            function setTBodyInnerHTML(tbody, html) {
          	  var temp = tbody.ownerDocument.createElement('div');
          	  temp.innerHTML = '<table><tbody id=\"content\">' + html + '</tbody></table>';
          	  tbody.parentNode.replaceChild(temp.firstChild.firstChild, tbody);
          	}

			function exportPage()
			{
    			value = document.getElementById('selectversion').value;
    			window.location.href = "<?php echo site_url().'/report/market/exportPage/'?>"+value;
			}
			
			function trim(str) {
        		return  (str.replace(/(^\s*)|(\s*$)/g, ''));
			}       
</script>




