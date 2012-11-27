<section class="column" id="main" style="height:1000px">	
	   <div style="height:330px;">
		<iframe src="<?php echo site_url() ?>/report/productbasic/adduserbehavorviewreport?type=compare"  frameborder="0" scrolling="no"style="width:100%;height:100%;"></iframe>		
		</div>
		
	<article class="module width_full">
		<header>		
		<h3 class="h3_fontstyle">        
		<?php echo  lang('v_rpt_pb_overviewOfUserBehavior')?></h3>
		<ul class="tabs2">
				<li><a href="javascript:changeChartName('newusers')"><?php echo  lang('t_newUsers')?></a></li>
				<li><a href="javascript:changeChartName('allusers')"><?php echo  lang('t_accumulatedUsers')?></a></li>
				<li><a href="javascript:changeChartName('startusers')"><?php echo  lang('t_activeUsers')?></a></li>
				<li><a href="javascript:changeChartName('sessions')"><?php echo  lang('t_sessions')?></a></li>
				<li><a href="javascript:changeChartName('usingtime')"><?php echo  lang('t_averageUsageDuration')?></a></li>
	    </ul>
		<span class="relative r"> <a class="bottun4 hover" href="<?php echo site_url()?>/report/productbasic/exportComparedata"><font><?php echo lang('g_exportToCSV') ?></font></a>
		</span>	
		</header>
		   <table class="tablesorter" cellspacing="0"> 
		       <thead id='contenttitle'></thead> 
		       <tbody id='content'></tbody>    
			</table>
			<footer>
			<div id="userbehavorpage" class="submit_link"></div>
			</footer>
	</article>
</section>
<script>
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show();
$(".tab_content:first").show(); //Show first tab content

$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});

var detaildata;
var maxlength;
var chartname='';
var pageindex=0;

$(function(){
	$.ajax({
		url:'<?php echo site_url()?>/report/productbasic/getUsersDataByTime?date='+new Date().getTime(),
		type:'get',
		dateType:'json',
		success:function(data){
			detaildata=eval('{'+data+'}');
			var detailtitlecontent="<tr><th><?php echo lang('g_date')?></th>";
			for(var i=0;i<detaildata.length;i++){
				detailtitlecontent=detailtitlecontent+"<th>"+detaildata[i].name+"</th>";
				maxlength=detaildata[i].content.length;
			}
			detailtitlecontent=detailtitlecontent+"</tr>";
			$("#contenttitle").html(detailtitlecontent);
			initPagination();
			changeChartName("newusers");
			}
		});
	
});
function changeChartName(name){
	chartname=name;
	contentTable();
}
var contentTable=function(){
	var pre=pageindex*10;
	var next=(pageindex+1)*10;
	if(maxlength-next<0){
		next=maxlength;
	}
	var tr='';
	for(var i=pre;i<next;i++){
		for(var j=0;j<detaildata.length;j++){
			var obj=detaildata[j];
			if(j==0){
				tr=tr+'<tr><td>'+obj.content[i].datevalue.substr(0,10)+'</td>';
			}
			var currentdata=obj.content[i][chartname];
			if(chartname=='usingtime'){
				if(obj.content[i]['sessions']!=0){
					currentdata=(currentdata/obj.content[i]['sessions'])/1000;
					currentdata=parseFloat(currentdata,10).toFixed(2);
				}
				currentdata=currentdata+"<?php echo lang('g_s')?>";
			}
			tr=tr+'<td>'+currentdata+'</td>';
		}
		tr=tr+'</tr>';
	}
	$('#content').html(tr);
}

/** 
 * Callback function for the AJAX content loader.
 */
function initPagination() {
    var num_entries = maxlength/<?php echo PAGE_NUMS;?>;
    // Create pagination element
    $("#userbehavorpage").pagination(num_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo lang('g_previousPage') ?>',       //上一页按钮里text 
        next_text: '<?php echo lang('g_nextPage') ?>',       //下一页按钮里text            
        num_display_entries: 4,
        callback: pageselectCallback,
        items_per_page:1
    });
 }
function pageselectCallback(page_index, jq){
	pageindex=page_index;
	contentTable();
	return false;
}
</script>