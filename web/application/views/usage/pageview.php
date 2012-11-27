<section id="main" class="column" style="height:1150px">	
	 <div style="height:520px;">
		<iframe src="<?php echo site_url() ?>/report/pagevisit/addvisitpathreport"  frameborder="0" scrolling="no"style="width:100%;height:100%;"></iframe>		
  </div>			
	<!-- page visit detail -->
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_pv_details') ?></h3>
			<div class="submit_link">
				<select id='selectversion'
					onchange="onSelectVersionChanged(this.options[this.selectedIndex].value)">
					<option selected value='all'><?php echo lang("v_rpt_el_allVersion")?></option>
				  	<?php
						if (isset ( $version )) :
							foreach ( $version->result () as $row ) {
								if ($row->version_name != 'all') {
									?>
				    <option value=<?php echo $row->version_name; ?>><?php echo $row->version_name;?></option>
				    <?php }} endif;?>
	  			</select>
			</div>			
		</header>
		<div id="pageinfo" class="tab_content">
			<table class="tablesorter" cellspacing="0">
				<thead>
					<tr>
						<th><?php echo lang('v_rpt_pv_page') ?></th>
						<th><?php echo lang('t_numberOfPageViews') ?></th>
						<th><?php echo lang('t_averageRetentionTime') ?></th>
						<th><?php echo lang('t_bounceRate') ?></th>
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
	<div class="clear"></div>
</section>
<script type="text/javascript">
//Here must first load
var version="all";
var data;
var version_array=[]; 
var verDetaildata;
var flag=1;
var currentVersionData;

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
			/** Load splats */
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
    	    
			var myurl="<?php echo site_url()?>/report/pagevisit/getPageInfo/"+version+"/"+page_index;
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

		     // Clear the list box 
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

			//Parsing json data                   
            function getJSONData(data,version){
            	verDetaildata = data;
            	for(var key in data.content){
                	version_array.push(key); 
                	 }
         
              	 for(var j=0;j<version_array.length;j++)
          		 {
             		if(version_array[j]==version){
                  	
                  		currentVersionData=data.content[version];                  		
                 		initPagination(currentVersionData.length);
                  		pageselect(0, 0);           
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
        			htmlText = htmlText+"<td>"+((parseFloat(eachVersionDataItem.avertime))/1000).toFixed(2)+"<?php echo lang('g_s');?></td>";
        			htmlText = htmlText+"<td>"+eachVersionDataItem.exitcount+"</td>";
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
            	pageselectCallback(0,0); 
            });    


            function setTBodyInnerHTML(tbody, html) {
          	  var temp = tbody.ownerDocument.createElement('div');
          	  temp.innerHTML = '<table><tbody id=\"content\">' + html + '</tbody></table>';
          	  tbody.parentNode.replaceChild(temp.firstChild.firstChild, tbody);
          	}       
</script>
