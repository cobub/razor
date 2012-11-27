<meta charset="utf-8">


<h1>Coffee Flavour Wheel</h1>
<script src="<?php echo base_url();?>/assets/js/flow/d3.v2.min.js" type="text/javascript"></script>

<style>
path {
  stroke: #000;
  stroke-width: 1.5;
  cursor: pointer;
}

text {
  font: 11px sans-serif;
  cursor: pointer;
}


</style>

  <body>
    <div id="chart"></div>
    <script type="text/javascript">
    // Coffee Flavour Wheel by Jason Davies,
    // http://www.jasondavies.com/coffee-wheel/
    // License: http://www.jasondavies.com/coffee-wheel/LICENSE.txt
    var width = 840,
        height = width,
        radius = width / 2,
        x = d3.scale.linear().range([0, 2 * Math.PI]),
        y = d3.scale.pow().exponent(1.3).domain([0, 1]).range([0, radius]),
        padding = 5,
        duration = 1000;

    var div = d3.select("#chart");
    var color = d3.scale.category20c();
    var vis = div.append("svg")
        .attr("width", width + padding * 2)
        .attr("height", height + padding * 2)
      	.append("g")
        .attr("transform", "translate(" + [radius + padding, radius + padding] + ")");

    var partition = d3.layout.partition()
        .sort(null)
        .value(function(d) { return 5.8 - d.depth; });

    var arc = d3.svg.arc()
        .startAngle(function(d) { return Math.max(0, Math.min(2 * Math.PI, x(d.x))); })
        .endAngle(function(d) { return Math.max(0, Math.min(2 * Math.PI, x(d.x + d.dx))); })
        .innerRadius(function(d) { return Math.max(0, d.y ? y(d.y) : d.y); })
        .outerRadius(function(d) { return Math.max(0, y(d.y + d.dy)); });

    d3.json("<?php echo site_url();?>/report/pagevisit/getFlowChart", function(json) {
      var nodes = partition.nodes({children: json});

      var path = vis.data([json]).selectAll("path").data(partition.nodes);
      path.enter().append("path")
          .attr("id", function(d, i) { return "path-" + i; })
          .attr("d", arc)
          .style("fill", function(d) { return color((d.children ? d : d.parent).name); })
          .on("click", click);

      var text = vis.data([json]).selectAll("text").data(partition.nodes);
      var textEnter = text.enter().append("text")
          .style("fill-opacity",  function(d) {
			  var startAngle = Math.max(0, Math.min(2 * Math.PI, x(d.x)));
			  var endAngle = Math.max(0, Math.min(2 * Math.PI, x(d.x + d.dx)));
			  var innerRadius = Math.max(0, d.y ? y(d.y) : d.y);
			  var outerRadius = Math.max(0, y(d.y + d.dy));
			  var anchorDistance = Math.abs(endAngle-startAngle) *outerRadius;
			 return anchorDistance>50?1:0;
					 })
          .style("fill", function(d) {
            return brightness(d3.rgb(colour(d))) < 125 ? "#eee" : "#000";
          })
          .attr("text-anchor", function(d) {
            return x(d.x + d.dx / 2) > Math.PI ? "end" : "start";
          })
          .attr("dy", ".2em")
          .attr("transform", function(d) {
            var multiline = (d.name || "").split("(").length > 1,
                angle = x(d.x + d.dx / 2) * 180 / Math.PI - 90,
                rotate = angle + (multiline ? -.5 : 0);
            return "rotate(" + rotate + ")translate(" + (y(d.y) + padding) + ")rotate(" + (angle > 90 ? -180 : 0) + ")";
          })
          .on("click", click);
      textEnter.append("tspan")
          .attr("x", 0)
          .text(function(d) { return d.depth ? d.name.split("(")[0] : ""; });
      textEnter.append("tspan")
          .attr("x", 0)
          .attr("dy", "1em")
          .text(function(d) { return d.depth ? "( "+d.name.split("(")[1] || "" : ""; });

      function click(d) {
        path.transition()
          .duration(duration)
          .attrTween("d", arcTween(d));

        // Somewhat of a hack as we rely on arcTween updating the scales.
        text.style("visibility", function(e) {
            return isParentOf(d, e) ? null : d3.select(this).style("visibility");
          })
        .transition()
          .duration(duration)
          .attrTween("text-anchor", function(d) {
            return function() {
              return x(d.x + d.dx / 2) > Math.PI ? "end" : "start";
            };
          })
          .attrTween("transform", function(d) {
            var multiline = (d.name || "").split(" ").length > 1;
            return function() {
              var angle = x(d.x + d.dx / 2) * 180 / Math.PI - 90,
                  rotate = angle + (multiline ? -.5 : 0);
              return "rotate(" + rotate + ")translate(" + (y(d.y) + padding) + ")rotate(" + (angle > 90 ? -180 : 0) + ")";
            };
          })
          .style("fill-opacity", function(e) {
          	var startAngle = Math.max(0, Math.min(2 * Math.PI, x(e.x)));
  			  var endAngle = Math.max(0, Math.min(2 * Math.PI, x(e.x + e.dx)));
  			  var innerRadius = Math.max(0, e.y ? y(e.y) : e.y);
  			  var outerRadius = Math.max(0, y(e.y + e.dy));
  			  var anchorDistance = Math.abs(endAngle-startAngle) *outerRadius;
  	
  			 return anchorDistance>50 && isParentOf(d, e)?1:0;
  	    		})
          .each("end", function(e) {
            d3.select(this).style("visibility", isParentOf(d, e) ? null : "hidden");
          });
        setTimeout(function() {refresh(d);},1000);
    }

      function refresh(d) {
          path.transition()
            .duration(duration)
            .attrTween("d", arcTween(d));

          // Somewhat of a hack as we rely on arcTween updating the scales.
          text.style("visibility", function(e) {
              return isParentOf(d, e) ? null : d3.select(this).style("visibility");
            })
          .transition()
            .duration(duration)
            .attrTween("text-anchor", function(d) {
              return function() {
                return x(d.x + d.dx / 2) > Math.PI ? "end" : "start";
              };
            })
            .attrTween("transform", function(d) {
              var multiline = (d.name || "").split(" ").length > 1;
              return function() {
                var angle = x(d.x + d.dx / 2) * 180 / Math.PI - 90,
                    rotate = angle + (multiline ? -.5 : 0);
                return "rotate(" + rotate + ")translate(" + (y(d.y) + padding) + ")rotate(" + (angle > 90 ? -180 : 0) + ")";
              };
            })
            .style("fill-opacity", function(e) {
            	var startAngle = Math.max(0, Math.min(2 * Math.PI, x(e.x)));
    			  var endAngle = Math.max(0, Math.min(2 * Math.PI, x(e.x + e.dx)));
    			  var innerRadius = Math.max(0, e.y ? y(e.y) : e.y);
    			  var outerRadius = Math.max(0, y(e.y + e.dy));
    			  var anchorDistance = Math.abs(endAngle-startAngle) *outerRadius;
    	
    			 return anchorDistance>50 && isParentOf(d, e)?1:0;
    	    		})
            .each("end", function(e) {
              d3.select(this).style("visibility", isParentOf(d, e) ? null : "hidden");
            });
      }

        


        
    });

    function isParentOf(p, c) {
      if (p === c) return true;
      if (p.children) {
        return p.children.some(function(d) {
          return isParentOf(d, c);
        });
      }
      return false;
    }
    
    function colour(d) {
        return "#fff";
      }
    

    // Interpolate the scales!
    function arcTween(d) {
      var my = maxY(d),
          xd = d3.interpolate(x.domain(), [d.x, d.x + d.dx]),
          yd = d3.interpolate(y.domain(), [d.y, my]),
          yr = d3.interpolate(y.range(), [d.y ? 20 : 0, radius]);
      return function(d) {
        return function(t) { x.domain(xd(t)); y.domain(yd(t)).range(yr(t)); return arc(d); };
      };
    }

    function maxY(d) {
      return d.children ? Math.max.apply(Math, d.children.map(maxY)) : d.y + d.dy;
    }

    // http://www.w3.org/WAI/ER/WD-AERT/#color-contrast
    function brightness(rgb) {
      return rgb.r * .299 + rgb.g * .587 + rgb.b * .114;
    }
    </script>
