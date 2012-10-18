<script src="<?php echo base_url();?>/assets/js/flow/d3.v2.min.js" type="text/javascript"></script>
<style>
.chart {
  display: block;
  margin: auto;
  font-size: 11px;
}

rect {
  stroke: #eee;
  fill-opacity: .8;
}

rect.parent {
  cursor: pointer;
}

text {
  pointer-events: none;
}
</style>
<section id="main" class="column" style="height:1150px">
	<article class="module width_full" style="height:500px;background:lightgrey;" >
		<header>
			<h3 class="tabs_involved"><?php echo lang('v_rpt_pv_visitpath') ?></h3>
		</header>
		<!--  
		<iframe width="100%"  scrolling="no" height="90%" frameborder="0" src="<?php echo site_url();?>/report/pagevisit/getFlowChart"></iframe>
		-->
		<div id="chart"></div>
	</article>
	
<script type="text/javascript">

$(document).ready(function(){ 
	var chart_canvas = $("#chart");
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
	var w = $("#chart").width();
	var h = 450,
    x = d3.scale.linear().range([0, w]),
    y = d3.scale.linear().range([0, h]);

var color = d3.scale.category20c();

var vis = d3.select("#chart").append("div")
    .attr("class", "chart")
    .style("width", w + "px")
    .style("height", h + "px")
  .append("svg:svg")
    .attr("width", w)
    .attr("height", h);
    
function comparator(a, b) {
	  return b.value - a.value;
	}
	
var partition = d3.layout.partition()
    .value(function(d) { return d.percentage; });
    
d3.json("<?php echo site_url();?>/report/pagevisit/getFlowChart", function(root) {
  var g = vis.selectAll("g")
      .data(partition.nodes(root))
    .enter().append("svg:g")
      .attr("transform", function(d) { return "translate(" + x(d.y) + "," + y(d.x) + ")"; })
      .on("click", click);

  var kx = w / root.dx,
      ky = h / 1;

  g.append("svg:rect")
      .attr("width", root.dy * kx)
      .attr("height", function(d) { return d.dx * ky; })
      .attr("fill", function(d) 
    	      { 
	      		if(d.name.indexOf("Exit")>-1)
	      		{	      		
		      		return "#FF0000";
	      		}
	      		return color((d.children ? d : d.parent).name);
	      	  }
  	  );
      //      .attr("class", function(d) { return d.children ? "parent" : "child"; });

  g.append("svg:text")
      .attr("transform", transform)
      .attr("dy", ".35em")
      .style("opacity", function(d) { return d.dx * ky > 12 ? 1 : 0; })
      .text(function(d) { return d.name; })

  d3.select(window)
      .on("click", function() { click(root); })

  function click(d) {
    if (!d.children) return;

    kx = (d.y ? w - 40 : w) / (1 - d.y);
    ky = h / d.dx;
    x.domain([d.y, 1]).range([d.y ? 40 : 0, w]);
    y.domain([d.x, d.x + d.dx]);

    var t = g.transition()
        .duration(d3.event.altKey ? 7500 : 750)
        .attr("transform", function(d) { return "translate(" + x(d.y) + "," + y(d.x) + ")"; });

    t.select("rect")
        .attr("width", d.dy * kx)
        .attr("height", function(d) { return d.dx * ky; });

    t.select("text")
        .attr("transform", transform)
        .style("opacity", function(d) { return d.dx * ky > 12 ? 1 : 0; });

    d3.event.stopPropagation();
  }

  function transform(d) {
    return "translate(8," + d.dx * ky / 2 + ")";
  }
});
chart_canvas.unblock();
}); 


    </script>
			
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
