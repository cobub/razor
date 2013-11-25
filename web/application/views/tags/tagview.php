<?php
$language = $this->config->item ( 'language' );

?> 
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<title><?php
echo lang ( 'l_cobubRazor' )?></title>
</head>

<link rel="stylesheet"
	href="<?php echo base_url();?>assets/css/tag/jquery.tagsinput.css"
	type="text/css" media="screen" />
	
	<script src="<?php echo base_url();?>assets/js/tag/jquery.blockUI.js"
	type="text/javascript"></script>

<script src="<?php echo base_url();?>assets/js/tag/jquery.tagsinput.js"
	type="text/javascript"></script>


<section id="main" class="column"  style='height:1500px;'>
    <article class="module width_full" >
    <header>
			<h3 class="tabs_involved">
			<input type="radio" name="user_radio" id="radio_all" value="1" checked="checked">
			<?php echo  lang('tag_chose_all_user');?>
			</h3>
			
    </header>
    <div class="module_content">
    <?php echo lang('tag_push_all_user');?>
    </div>
    
    </article>

	<!--   article class="module width_3_quarter" -->
	<article class="module width_full" >
		<header>
			<h3 class="tabs_involved">
			<input type="radio" name="user_radio" id="radio_tag" value="2">
			<?php echo  lang('tag_chose_taggroup_user')?></h3>
			<span id="select_span" display>
			<select id="select_saved_tags" onchange='chooseTagsGroup()'
				style="margin-right: 3%; margin-top: 6px">
				<option><?php echo lang('tag_all_user')?></option>
			
			<?php
			foreach ( $tagsgroup->result () as $row ) {
				?>
			    <option><?php echo $row->name?></option>
			    
			    <?php }?>
			</select>
			</span>

		</header>
		<div class="tab_container" id="tag_content">
			<article>
				<div id="tab1" class="tab_content">

					<!--  tag start-->
					<div class="module_content">
						<h3><?php echo lang('tag_chose_version');?></h3>
						<input id="tag_version">
						<!--  <button id="btn_version">清空</button>-->
					</div>
					

					<div class="module_content">
						<h3><?php echo lang('tag_chose_channel')?></h3>
						<input id="tag_channel">
						<!--<button id="btn_channel">清空</button>-->
					</div>

					<div class="module_content">
						<h3><?php echo lang('tag_chost_pri');?></h3>
						<input id="tag_region">
						<!--<button id="btn_region">清空</button>-->
					</div>
					<!--  tag end-->
					
					<br>
			        <div class="module_content">
				        <input type="checkbox" id="checkbox_save" onclick="showInputText()">  <?php echo lang('tag_save_user');?> 
				        <input
					     type="text" id="input_tag_name" style="width: 30%; display:none">
					    <font color='red'><label id='err_msg'></label></font>
			        </div>
			    </div>
			    <br>
			    
			</article>

			<div class="clear"></div>
			
		</div>

		
	</article>
	<footer>
			<div class="submit_link">
			<form id="tag_form" action="<?php echo $url?>" method="post">
			    <input type="hidden" id="data_for_submit" name="tag_data">
			    <input type="hidden" id="product_id" name="product_id">
			    <input type="hidden" id="tag_type" name="tag_type">
				<input type='button' id='btn_submit' class='alt_btn_new'
					 value="下一步">
			</form>		
			</div>
		</footer>
	<!-- end of stats article -->
	<!--  
	<article class="module width_quarter">
			<header><h3><?php echo  lang('v_overview')?></h3></header>
			<article class="stats_overview width_full">
			        <div><Br></div>
					<p class="overview_day"><?php echo  '已选择的用户数'?></p>
					
					<div class="overview_day">
					    <div><Br></div>
						<p class="overview_count"></p>
						
						<p class="overview_count">1000</p>
						<div><Br></div>
					</div>
					
			</article>
		</article>

-->
	<!--  
		<article class="module width_quarter">
			<header><h3><?php echo  '你可以保存当前选择TAG列表'?></h3></header>
			<article class="stats_overview width_full">
			    <div class="module_content">
			    
			    <fieldset >
				<label><?php echo '请输入TAG列表名称:' ?></label>
			    <input type="text" id="input_tag_name" style="width: 80%; float: left; margin-right: 3%;">
			    <div class="submit_link">
				<input type='submit' id='btn_save' class='alt_btn'
					value="保存">
			    </div>
			    </fieldset>
				</div>
					
			<footer>
			
		    </footer>
			</article>
		-->
	</article>
	<div class="clear"></div>
	<div class="spacer"></div>
</section>

<script type="text/javascript">
var product_id = <?php echo $productId?>;
var all_tag_list = new Array();
var tagsgroup = new Array();
console.log(tagsgroup);

var version_tags = <?php echo $version;?>;
var channel_tags = <?php echo $channel;?>;
var region_tags = <?php echo $region;?>;


var version_list ;
var channel_list;
var region_list;

var all_tags_data_select = <?php echo $tagsgroupjson;?>;
var current_tags_data_select = "";

function isShowTags()
{
	var val=$('input:radio[name="user_radio"]:checked').val();
	if(val=="1")
	{
		$("#tab1").hide();
	}
	else
		$("#tab1").slideDown(1000);
	}

$(document).ready(function() {
	isShowTags();
	$("#radio_all,#radio_tag").bind("click",isShowTags);
	
	//$("#input_tag_name").value ="";
	$("#select_saved_tags option").each(function()
			{
			    // add $(this).val() to your list
	           tagsgroup.push($(this).val());
			});
	showInputText();
	$("#input_tag_name").keyup(function(){
	   // alert(this.value);
		//document.getElementById("err_msg").innerHTML="";
		document.getElementById("err_msg").innerHTML="";
	    if(jQuery.inArray(this.value,tagsgroup)!=-1)//-1 notexist else retrun index
	    {
	    	document.getElementById("err_msg").innerHTML="<?php echo lang('tag_name_exited');?>";
		    
	    }
	  });
	
	document.getElementById("input_tag_name").value="";
	$('#tag_version').tagsInput({
		width: 'auto',
		'defaultText':'',
		onChange:showTagList,

		autocomplete_url:<?php echo $version?>,
		usefulTags:version_tags
	});
	
	$('#tag_channel').tagsInput({
		width: 'auto',
		'defaultText':'',
		onChange:showTagList,

		autocomplete_url:<?php echo $channel?> ,
		usefulTags:channel_tags
	});
	
	$('#tag_region').tagsInput({
		width: 'auto',
		'defaultText':'',
		onChange:showTagList,
	    
		autocomplete_url:<?php echo $region?> ,
		usefulTags:region_tags
	});
	

	//$('#tag_version').tagsInput.allUsefulTags(version_list);

	chooseTagsGroup();

});


function showTagList()
{
	if($("#tag_version").val()!="")
	    version_list = $("#tag_version").val().split(",");
	else
		version_list = "";

	if($("#tag_channel").val()!="")
	    channel_list = $("#tag_channel").val().split(",");
	else
		channel_list = "";

	if($("#tag_region").val()!="")
        region_list = $("#tag_region").val().split(",");
	else
		region_list = "";
	
    var content = "<p><?php echo lang('tag_chosed');?><p class='overview_day'><div id='tags_sel' class='tagsout'>";
    
    for(var i in version_list)
    {
    	content+="<span class='tag'>";
       content+="<span>"+version_list[i]+"</span>";
       content+="</span>";
    }
    //content+="";
    //content+="你选择的渠道:<br>";
    for(var i in channel_list)
    {
    	content+="<span class='tag'>";
        content+="<span>"+channel_list[i]+"</span>";
        content+="</span>";
    }
     //content+="<br>";
    // content+="你选择的地区:<br>";
     for(var i in region_list)
     {
    	 content+="<span class='tag'>";
         content+="<span>"+region_list[i]+"</span>";
         content+="</span>";
     }
     content+="</div>";

    
   //document.getElementById('content_list').innerHTML=content;

}
function initAllTag()
{
	
	clearAllTags();
	for(var i in version_tags)
	{
		if(version_tags!='[]')
		$('#tag_version').addTag(version_tags[i]);
	}
	for(var i in channel_tags)
	{
		if(channel_tags!='[]')
		$('#tag_channel').addTag(channel_tags[i]);
	}
	for(var i in region_tags)
	{
		if(region_tags!='[]')
		$('#tag_region').addTag(region_tags[i]);
	}

}
//initAllTag();
showTagList();

$("#btn_version").click(function()

		{
	    $('#tag_version').importTags('');
	    }
		);
		
$("#btn_channel").click(function()

		{
	    $('#tag_channel').importTags('');
	    }
		);

$("#btn_region").click(function()

		{
	    $('#tag_region').importTags('');
	    }
		);


$("#btn_submit").click(function()

{	
	getCurrentTagList();
	document.getElementById("data_for_submit").value = JSON.stringify(all_tag_list);
	document.getElementById("product_id").value = product_id;
	

	var tag_type="all";
	var name=document.getElementById("select_saved_tags").value;
	
	
	if($('input[name="user_radio"]:checked').val()=="1")
	{
		//return;
	}
	
	
    if(name != "<?php echo lang('tag_all_user');?>")
    {
        tag_type = "custom";
        
    }
    if (name == "<?php echo lang('tag_all_user');?>")
    {
        var v_l = $("#tag_version").val().split(","); 
        $.each(version_tags,function(k,v){

            if(jQuery.inArray(v,v_l)==-1)
            {
            	//alert(k+":"+v);

            	tag_type = "custom";
        	    //return;
            }
            });

        if(tag_type!="custom")
        {

        var c_l = $("#tag_channel").val().split(","); 
        $.each(channel_tags,function(k,v){

            if(jQuery.inArray(v,c_l)==-1)
            {
            	//alert(k+":"+v);
            	tag_type = "custom";
        	//return;
        	}
            });
        }
        if(tag_type!="custom")
        {

        var r_l = $("#tag_region").val().split(","); 
        
        $.each(region_tags,function(k,v){

            if(jQuery.inArray(v,r_l)==-1)
            {
                //alert(k+":"+v);
            	tag_type = "custom"; 
               // return;
            }
            });

        }
       // alert(tag_type);
       
    }
    
     //var radio=document.getElementById("radio_all");
    // if(radio.checked){
     //	tag_type='all';
    // }
        
    document.getElementById("tag_type").value = tag_type;
    // alert(tag_type);
	
	if(document.getElementById('checkbox_save').checked)
	  {
	    if($("#input_tag_name").val()=="")
	    {
		   
		    document.getElementById("err_msg").innerHTML="<?php echo lang('tag_input_groupname');?>";
		    return;
	    }
	    else if(jQuery.inArray($("#input_tag_name").val(),tagsgroup)!=-1)//-1 notexist else retrun index
	    {
	    	document.getElementById("err_msg").innerHTML="<?php echo lang('tag_name_exited');?>";
		    return;
	    }
	    
	    else
	    {
	    	document.getElementById("err_msg").innerHTML="";
		    
	    	$.blockUI({ css: { 
	            border: 'none', 
	            padding: '15px', 
	            backgroundColor: '#000', 
	            '-webkit-border-radius': '10px', 
	            '-moz-border-radius': '10px', 
	            opacity: .5, 
	            color: '#fff' 
	        } }); 
	    	
	    	addTagsGroup();  
	    } 
	    
	  }
	else
	{
		submitData();
	}
	  
}
);

function submitData()
{
    $("#tag_form").submit();
}

function showInputText()
{
	if(document.getElementById('checkbox_save').checked)
	{
		$("#input_tag_name").show();
		$("#input_tag_name").val("");

		}
	else
	{
		$("#input_tag_name").hide();
		$("#err_msg").html("");
	}
}




function addTagsGroup()
{
	var name = $("#input_tag_name").val();
	var postData = JSON.stringify(all_tag_list);
	var data = {
			product_id:product_id,
			tags : postData,
			name:name
	};
	
	jQuery.ajax({
		type : "post",
		url : "<?php echo site_url()?>/tag/tags/addTagsGroup",
		data : data,
		success : function(msg) {
			tagsgroup.push(name);
			var select = document.getElementById("select_saved_tags");
			select.options[select.options.length] = new Option(name, name);
			all_tags_data_select[name] = postData;
			$.unblockUI();
			submitData();
		},
		error : function(XmlHttpRequest, textStatus, errorThrown) {
			$.unblockUI();
			alert("<?php echo lang('tag_save_group_fail');?>");
		},
		beforeSend : function() {
			

		},
		complete : function() {
		}
	});

}
function clearAllTags()
{
	$('#tag_version').importTags('');
	$('#tag_channel').importTags('');
	$('#tag_region').importTags('');

}

function getCurrentTagList()
{
	version_tag_obj = new Object();
    version_tag_obj.type = "version";
    version_tag_obj.value= version_list;

    channel_tag_obj = new Object();
    channel_tag_obj.type = "channel";
    channel_tag_obj.value= channel_list;

    region_tag_obj = new Object();
    region_tag_obj.type = "region";
    region_tag_obj.value= region_list;


    all_tag_list[0] = channel_tag_obj;
    all_tag_list[1] = version_tag_obj;
    all_tag_list[2] = region_tag_obj;

	}

function chooseTagsGroup()
{
    var name=$("#select_saved_tags").val();
    if(name == "<?php echo lang('tag_all_user');?>")
    {
    	initAllTag();
    	return;
    }

  
    for(var key in all_tags_data_select)
    {
        if(name == key)
        {
        	current_tags_data_select = eval(all_tags_data_select[key]);
            
            break;
        }
         
    }
    resetAllTagsBySelected(current_tags_data_select);
    
}



function resetAllTagsBySelected(obj)
{
	clearAllTags();
	for(var i in obj)
	{
        if(obj[i].type=="version")
        {            
            for(var j in obj[i].value)
            {
              $('#tag_version').addTag(obj[i].value[j]);
            }

       }

        if(obj[i].type=="channel")
        {
        	for(var j in obj[i].value)
            {
              $('#tag_channel').addTag(obj[i].value[j]);
            }
            }
        if(obj[i].type=="region")
        {
        	for(var j in obj[i].value)
            {
              $('#tag_region').addTag(obj[i].value[j]);
            }
            }
	}

}


</script>