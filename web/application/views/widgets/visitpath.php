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
<section class="section_maeginstyle" id="highchart"
<?php if(!isset($delete)) {?>
style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;"<?php }?>>
	<article class="module width_full" style="height:500px;background:lightgrey;" >
		<header>
  <div style="float:left;margin-left:2%;margin-top: 5px;">
	<?php   if(isset($add))
  {?>
  <a href="#" onclick="addreport()">
	<img src="<?php echo base_url();?>assets/images/addreport.png" title="<?php echo lang('s_suspend_title')?>" style="border:0"/></a>
<?php }if(isset($delete)){?>
 <a href="#" onclick="deletereport()">
	<img src="<?php echo base_url();?>assets/images/delreport.png" title="<?php echo lang('s_suspend_deltitle')?>" style="border:0"/></a>
	<?php }?>
  </div>
<h3  class="h3_fontstyle">
   <?php  echo lang('v_rpt_pv_visitpath') ?></h3>
		</header>		
		<div id="chart"></div>
	</article>
</section>	
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
<script type="text/javascript">
function addreport()
{	
	if(confirm( "<?php echo  lang('w_isaddreport')?>"))
	{
		var reportname="visitpath";
	    var reportcontroller="pagevisit";
	    var data={ 
	    		 reportname:reportname,
  		  	     controller:reportcontroller,
	  		  	 height    :520,
	  		  	 type      :1,
	  		  	 position  :0
		  	     };
		jQuery.ajax({
						type :  "post",
						url  :  "<?php echo site_url()?>/report/dashboard/addshowreport",	
						data :  data,			
						success : function(msg) {
							if(msg=="")
							{
								alert("<?php echo lang('w_addreportrepeat') ?>");
							}
							else if(msg>=8)
							{
								alert("<?php echo  lang('w_overmaxnum');?>");
							}
							else
							{
								 alert("<?php echo lang('w_addreportsuccess') ?>");									 
							}
									 
							},
							error : function(XmlHttpRequest, textStatus, errorThrown) {
								alert(<?php echo lang('t_error') 	; ?>);
							}
					});
		
	}
}

function deletereport()
{ 
	if(confirm( "<?php echo  lang('v_deletreport')?>"))
	{
		window.parent.deletereport("visitpath");	 	 	  
	}
	return false;
	
}
</script>