<link rel="stylesheet" href="<?php echo base_url();?>assets/css/easydialog.css" type="text/css" media="screen"/>
<script
	src="<?php echo base_url();?>assets/js/easydialog/easydialog.min.js"
	type="text/javascript"></script>
<script src="<?php echo base_url();?>assets/js/highslide-full.min.js" type="text/javascript">
</script>
<script src="<?php echo base_url();?>assets/js/highslide.config.js" type="text/javascript">
</script>
<link href="<?php echo base_url();?>assets/css/highslide.css" type="text/css" rel="stylesheet"/>
<style type="text/css">
	.title,.marktime{
		width:150px;
		border:1px solid #cccccc;
		height:22px;
		padding:0 5px;	
	}
	.desc{
		width:150px;
		height:70px;
		border:1px solid #cccccc;
		padding:0 5px;
	}

* { margin:0;padding:0; }
	
</style>
<script type="text/javascript">
//
function prepare(dataArray,optiondetail,i){
	return dataArray.map(function(item,index){
		if(item==null)
			return [];
		var y=0;
		if(optiondetail.series.length>0){if(typeof(optiondetail.series[0].data)=='undefined'){y=0;}else{y=optiondetail.series[0].data.max();}}
		if(y<=0){
			optiondetail.yAxis.max=200;
			y=200;
			}
		y=y/20*(i+5);//#DB9D00,#F2AE00
		return {color:"#DB9D00",y:Math.round(y* 1000)/1000,time:item.marktime,title:item.title,description:item.description,date:item.marktime,username:item.username,rights:item.private};
	});
}
Array.prototype.max=function(){return Math.max.apply({},this);}
Array.prototype.content=function(number){
	var isexists=false;
	for(var i=0;i<this.length;i++){
		if(number==this[i]){
		isexists=true;
		}
	}
	return isexists;
}
//news report
function sendBack(e){
	showmainlist();
	$('input[name=markdate]').val(e.point.category);
}
//end news report
var btnFn = function( e ){
	$(e.target).ajaxStart(function(){$(this).attr({disabled:'disabled'});});
	var title=$('input[name=title]').val();
	var desc=$('textarea[name=description]').val();
	if(title==''){
			alert('<?php echo lang('m_tiptitle')?>');return false;
			}
	if(desc==''){
			alert('<?php echo lang('m_tipdesc')?>');return false;
			}
	$.ajax({
		url:'<?php echo site_url() ?>/manage/pointmark/addPointmark',
		type:'post',
		dataType:'json',
		data:$('#rebackForm').serialize(),
		success:function(data,status){
			if('ok'==data){alert('<?php echo lang('m_subsuccess')?>');window.location.href=window.location.href;}
			if('mdok'==data){alert('<?php echo lang('m_modifysuccess')?>');window.location.href=window.location.href;}
			if('exists'==data){if(confirm('<?php echo lang('m_changesubtomodify')?>')){$('input[name=type]').val('modify');btnFn(e);}}
			}
		});
	return false;
};

function showmainlist(){
	var div='';
		div+='<form id="rebackForm">';
		div+='<input type="hidden" name="type"/>';
		div+='<input type="button" value="<?php echo lang('m_modify')?>" name="modifyPoint" style="display:none;"/>';
		div+='<table>';
		div+='<tr>';
		div+='<td><?php echo lang('m_title')?>:</td>';
		div+='<td><input type="text" name="title" value="" class="title"/>&nbsp;*</td>';
		div+='</tr>';
		div+='<tr>';
		div+='<td><?php echo lang('m_description')?>:</td>';
		div+='<td><textarea name="description" class="desc"></textarea></td>';
		div+='</tr>';
		div+='<tr>';
		div+='<td><?php echo lang('m_marktime')?>:</td>';
		div+='<td><input type="text" name="markdate" value="" readonly="readonly" class="marktime"/></td>';
		div+='</tr>';
		div+='<tr>';
		div+='<td><?php echo lang('m_rights')?>:</td>';
		div+='<td><input type="radio" name="rights" value="1"/><?php echo lang('m_public')?>&nbsp;&nbsp;<input type="radio" name="rights" value="0" checked="checked"/><?php echo lang('m_private')?></td>';
		div+='</tr>';
		div+='</table>';
		div+='</form>';
	easyDialog.open({
		container : {
			header : '<?php echo lang('m_newsreport')?>',
			content : div,
			yesFn : btnFn,
			noFn : true
		},
		fixed : false
	});
};
</script>