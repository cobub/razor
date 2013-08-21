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

<section id="main" class="column">

	<!--   article class="module width_3_quarter" -->
	<article class="module width_full" >
		<header>
			<h3><?php echo  '选择标签组'?></h3>
			<select id="select_saved_tags" onchange='chooseTagsGroup()'
				style="margin-right: 3%; margin-top: 6px">
				<option>所有用户</option>
			
			<?php
			foreach ( $tagsgroup->result () as $row ) {
				?>
			    <option><?php echo $row->name?></option>
			    
			    <?php }?>
			</select>

		</header>
		<div class="module_content">
			<article>
				<div id="container" class="module_content">

					<!--  tag start-->
					<div class="module_content">
						<h3>选择版本</h3>
						<input id="tag_version">
						<!--  <button id="btn_version">清空</button>-->
					</div>
					

					<div class="module_content">
						<h3>选择渠道</h3>
						<input id="tag_channel">
						<!--<button id="btn_channel">清空</button>-->
					</div>

					<div class="module_content">
						<h3>选择地区(省份)</h3>
						<input id="tag_region">
						<!--<button id="btn_region">清空</button>-->
					</div>
					<!--  tag end-->
			    </div>
			</article>

			<div class="clear"></div>
			<br>
			<div class="module_content">
				<input type="checkbox" id="checkbox_save" onclick="showInputText()">  保存以便下次使用 
				<input
					type="text" id="input_tag_name" style="width: 30%; display:none">
					<font color='red'><label id='err_msg'></label></font>
			</div>
		</div>

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
	</article>
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

$(document).ready(function() {
	
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
	    	document.getElementById("err_msg").innerHTML="该名称已存在，请重新输入新的名称";
		    
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
	
    var content = "<p>你选择的标签：<p class='overview_day'><div id='tags_sel' class='tagsout'>";
    
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
    if(name != "所有用户")
    {
        tag_type = "custom";
    }
    if (name == "所有用户")
    {
        var v_l = $("#tag_version").val().split(","); 
        $.each(version_tags,function(k,v){

            if(jQuery.inArray(v,v_l)==-1)
            {
            	alert(k+":"+v);
            	tag_type = "custom";
        	    return;
            }
            });

        if(tag_type!="custom")
        {

        var c_l = $("#tag_channel").val().split(","); 
        $.each(channel_tags,function(k,v){

            if(jQuery.inArray(v,c_l)==-1)
            {
            	alert(k+":"+v);
            	tag_type = "custom";
        	return;}
            });
        }
        if(tag_type!="custom")
        {

        var r_l = $("#tag_region").val().split(","); 
        
        $.each(region_tags,function(k,v){

            if(jQuery.inArray(v,r_l)==-1)
            {
                alert(k+":"+v);
            	tag_type = "custom";
                return;
            }
            });

        }
       
    }
        
    document.getElementById("tag_type").value = tag_type;
	
	
	if(document.getElementById('checkbox_save').checked)
	  {
	    if($("#input_tag_name").val()=="")
	    {
		   
		    document.getElementById("err_msg").innerHTML="请输入要保存的标签组名称";
		    return;
	    }
	    else if(jQuery.inArray($("#input_tag_name").val(),tagsgroup)!=-1)//-1 notexist else retrun index
	    {
	    	document.getElementById("err_msg").innerHTML="该名称已存在，请重新输入新的名称";
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
		url : "<?php echo site_url()?>/Tag/tags/addTagsGroup",
		data : data,
		success : function(msg) {
			tagsgroup.push(name);
			var select = document.getElementById("select_saved_tags");
			select.options[select.options.length] = new Option(name, name);
			all_tags_data_select[name] = postData;
			$.unblockUI();
			submitData();
			
			//alert("ok");		 
		},
		error : function(XmlHttpRequest, textStatus, errorThrown) {
			$.unblockUI();
			alert("error");
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
    if(name == "所有用户")
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