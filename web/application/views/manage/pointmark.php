<link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-webox.css" type="text/css" media="screen"/>
<script
	src="<?php echo base_url();?>assets/js/jquery-webox.js"
	type="text/javascript"></script>
<section id="main" class="column">
	<article class="module width_full">
		<header>
			<h3 class="tabs_involved"><?php echo lang('m_markeventlist')?></h3>
				<span class="relative r">
				<a href="<?php echo site_url(); ?>/report/productbasic/exportdetaildata" class="bottun4 hover" >
				<font><?php echo  lang('g_exportToCSV');?></font></a>
			</span>					
		</header>
		
		<table class="tablesorter" cellspacing="0"> 
			<thead> 
				<tr> 
    				<th><?php echo lang('m_title')?></th> 
    				<th><?php echo lang('m_description')?></th> 
    				<th><?php echo lang('m_marktime')?></th> 
    				<th><?php echo lang('m_rights')?></th> 
    				<th><?php echo lang('m_operate')?></th>
				</tr> 
			</thead> 
			<tbody id="content">		     
	    <?php $num = count($ponitevents);?>	    	
			</tbody>
		</table> 
		
		<footer>
		<div id="pagination"  class="submit_link">
		</div>
		</footer>
	</article>
<style type="text/css">
.mainlist{
	width:400px;
	height:200px;
	margin:20px 100px;
}
.tbcontent{
width:400px;height:200px;border:1px solid #cccccc;}
 .mainlist td{
	/*border:1px solid #cccccc;padding-left:5px;*/
 }
.title{width:180px;border:1px solid #cccccc;height:24px;line-height:24px;vertical-align:middle;}
 .des{width:180px;border:1px solid #cccccc;}
 .mktime{width:180px;border:1px solid #cccccc;height:24px;line-height:24px;vertical-align:middle;}
</style>
		<div id="container" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
		<div id="box" style="display:none;">
	<div class="mainlist">
		<table class="tbcontent">
			<tr>
				<td><?php echo lang('m_title')?>:</td>
				<td><input type="text" name="title" value="" class="title"/></td>
			</tr>
			<tr>
				<td><?php echo lang('m_description')?>:</td>
				<td><textarea name="description" class="des"></textarea></td>
			</tr>
			<tr>
				<td><?php echo lang('m_marktime')?>:</td>
				<td><input type="text" name="markdate" value="" readonly="readonly" class="mktime"/></td>
			</tr>
			<tr>
				<td><?php echo lang('m_rights')?>:</td>
				<td><input type="radio" name="rights" value="1"/><?php echo lang('m_public')?>&nbsp;&nbsp;<input type="radio" name="rights" value="0" checked="checked"/><?php echo lang('m_private')?></td>
			</tr>
			<tr>
				<td colspan="2"><input type="button" value="<?php echo lang('m_submit')?>" name="subPoint"/><input type="button" value="<?php echo lang('m_modify')?>" name="modifyPoint" style="display:none;"/></td>
			</tr>
		</table>
	</div>
</div>
<!--end news report-->	
</section>
<script type="text/javascript">
$(function(){
   initPagination();
	pageselectCallback(0,null);	
});
function pageselectCallback(page_index, jq){			
	var ponitevents = eval(<?php echo json_encode($ponitevents)?>);
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";   
	var index = page_index*<?php echo PAGE_NUMS?>;
	var pagenum = <?php echo PAGE_NUMS?>;	
	var msg = "";
	$.each(ponitevents,function(i,item){
		if(i<pagenum&&(index+i)<ponitevents.length){
		var rights=item.private==1?'<?php echo lang('m_public')?>':'<?php echo lang('m_private')?>';
		msg+='<tr id='+item.id+'>';
		msg+='<td>'+item.title+'</td>';
		msg+='<td>'+item.description+'</td>';
		msg+='<td>'+item.marktime+'</td>';
		msg+='<td>'+rights+'<input type="hidden" name="right" value="'+item.private+'"/></td>';
		msg+='<td><a href="javascript:void 0;" onclick="loadDiv(event);"><?php echo lang('m_modify')?></a> <a href="javascript:void 0;" onclick="delMarkpoint(event);"><?php echo lang('m_delete')?></a><span></span></td>';
		msg+='</tr>';
		}
		});
	$('#content').html(msg);
   return false;
 }
/** 
 * Callback function for the AJAX content loader.
 */
function initPagination() {
	var num_entries = <?php if(isset($num)) echo $num; ?>/<?php echo PAGE_NUMS;?>;
    // Create pagination element
    $("#pagination").pagination(num_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo  lang('g_previousPage')?>',
        next_text: '<?php echo  lang('g_nextPage')?>',           
        num_display_entries: 4,
        callback: pageselectCallback,
        items_per_page:1               
    });
 }           

 function delMarkpoint(e,id){
	 if(!confirm('<?php echo lang('m_isdel')?>')){return;}
	 var $target=$(e.target);
	 var tr;
		if($target.is('a')){
			tr=$target.parent().parent();
			id=tr.attr('id');
			date=tr.children().eq(2).html();
			$target.next('span').ajaxStart(function(){
				$(this).html('<?php echo lang('m_waittingdel')?>');
				});
			if(typeof id !='undefined'){
				$.ajax({
					url:'<?php echo site_url()?>/manage/pointmark/removePointmark',
					type:'post',
					dataType:'json',
					data:{'id':id,'date':date},
					success:function(data,status){
						if('delok'==data){
							$target.next('span').html('<?php echo lang('m_delsuccess')?>');
							setTimeout(function(){
								tr.remove();
								},100);
						}
						if('othererror'==data){
							$target.next('span').html('<?php echo lang('m_errordeltryagain')?>');
							}
						if('noexists'==data){
							$target.next('span').html('<?php echo lang('m_faildeloninfo')?>');
							window.location.href=location.href;
						}
						}
					});
				}
		}	
	 } 
 function loadDiv(e){
		$.webox({
				height:280,
				width:600,
				bgvisibel:true,
				title:'<?php echo lang('m_newsreport')?>',
				html:$("#box").html()
			});
		var $target=$(e.target);
		if($target.is('a')){
			tr=$target.parent().parent();
			pointid=tr.attr('id');
			title=tr.children().eq(0).html();
			description=tr.children().eq(1).html();
			marktime=tr.children().eq(2).html();
			right=tr.children().eq(3).children('input[name=right]').val();
			$('input[name=title]').eq(1).val(title);
			$('textarea[name=description]').eq(1).val(description);
			$('input[name=markdate]').val(marktime).attr({readonly:'readonly'});
			$('input[name=rights]').each(function(index,item){
				if(item.value==right){
					$('input[name=rights]').eq(index).attr({checked:"checked"});
					}
				});	
			}
	}

 $(function(){
		// add or modify the mark
		$('input[name=subPoint]').live('click',function(){
				$(this).ajaxStart(function(){$(this).attr({disabled:'disabled'});});
				var title=$('input[name=title]').eq(1).val();
				var desc=$('textarea[name=description]').eq(1).val();
				var markdate=$('input[name=markdate]').eq(1).val();
				var rights=$('input[name=rights]:checked').val();
				if(title==''){alert('<?php echo lang('m_tiptitle')?>');return;}
				if(desc==''){alert('<?php echo lang('m_tipdesc')?>');return;}
				$.ajax({
					url:'<?php echo site_url() ?>/manage/pointmark/addPointmark',
					type:'post',
					dataType:'json',
					data:{'title':title,'description':desc,'markdate':markdate,'rights':rights,'type':'modify','date':new Date().getTime()},
					success:function(data,status){
						if('ok'==data){alert('<?php echo lang('m_subsuccess')?>');window.location.href=window.location.href;}
						if('mdok'==data){alert('<?php echo lang('m_modifysuccess')?>');window.location.href=window.location.href;}
						if('nochange'==data){alert('<?php echo lang('m_modifysuccess')?>');window.location.href=window.location.href;}
						}
					});
				});
	});    
</script>