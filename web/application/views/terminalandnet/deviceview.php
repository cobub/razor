<?php 
if(isset($_GET['page'])){
              $page = intval($_GET['page']);
          }
          else {
              $page=1;
          }
if (!isset($from))
{
    $to = date('Y-m-d',time());
    $from = date('Y-m-d',strtotime("-7 day"));
}
$num = count($operator->result());


?>
<script type="text/javascript">
var time=<?php echo isset($timetype)?'"'.$timetype.'"':'"'."7day".'"'?>;
var fromTime=<?php echo isset($from)?'"'.$from.'"':'""'?>;
var toTime=<?php echo isset($to)?'"'.$to.'"':'""'?>;
var operator = eval(<?php echo "'".json_encode($operator->result())."'"?>);
</script>

<?php 
//将数据放到数组中，每个元素是一个页面的内容
//$records = $operator->num_rows();
//$PageCount = ceil($records/PAGE_NUMS);
//$all_content = array();
//$page_content='';
//for($i = 0; $i < $PageCount; $i ++) {
//	for ( $j=0;$j<PAGE_NUMS;$j++ ) {
//		$row = $operator->row($j);
//		$page_content = '';
//		$page_content .= '<tr><td>' . $row->devicebrand_name . '</td>';
//		$page_content .= '<td>' . $row->percentage . '</td><tr>';
//	}
//	array_push ( $all_content, $page_content );
//}
?>

<section id="main" class="column">


<h4 class="alert_success" id='msg'><?php echo  lang('deviceview_alertinfo')?>
<div class="submit_link" style="margin-top:-8px">
<select onchange=selectChange(this.value)
	id='select'>
	<option value='7day'><?php echo  lang('allview_lastweek')?></option>
	<option value='1month'><?php echo  lang('allview_lastmonth')?></option>
	<option value='3month'><?php echo  lang('allview_last3month')?></option>
	<option value='all'><?php echo  lang('allview_alltime')?></option>
	<option value='any'><?php echo  lang('allview_anytime')?></option>
</select>
<div id='selectTime'><input type="text" id="dpFrom"> <input type="text"
	id="dpTo"> <input type="submit" id='btn' value="<?php echo  lang('allview_timebtn')?>" class="alt_btn"
	onclick="onBtn()"></div>
</div>
</h4>


<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo  lang('deviceview_deviceinfo')?></h3>
<ul class="tabs">
	<li><a href="#tab1"><?php echo  lang('deviceview_deviceactive')?></a></li>
	<li><a href="#tab2"><?php echo  lang('deviceview_devicenew')?></a></li>

</ul>

</header>

<div id="tab1" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo  lang('deviceview_typeidthead')?></th>
			<th><?php echo  lang('deviceview_percentthead')?></th>
			<th></th>

		</tr>
	</thead>
	<tbody>
	    <?php 
	        foreach ($activeuser->result() as $row)
	        {
	    ?>
		<tr>
			<td><?php echo $row->devicebrand_name==""?lang('deviceview_unknowtbody'):$row->devicebrand_name?></td>
			<td>
			<div style="background-color: <?php echo BLOCK_COLOR?>; height: <?php echo BLOCK_HEIGHT?>; width: <?php echo BLOCK_MAX_LENGTH*$row->percentage?>px;"></div>
			</td>
			<td><?php echo 100*$row->percentage."%"?></td>
		</tr>
		<?php 
	        }?>
	</tbody>
</table>
</div>

<div id="tab2" class="tab_content">
<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo  lang('deviceview_tabtheadtypeid')?></th>
			<th><?php echo  lang('deviceview_tabtheadpercent')?></th>
			<th></th>

		</tr>
	</thead>
	<tbody>
		<?php 
	        foreach ($newuser->result() as $row)
	        {
	    ?>
		<tr>
			<td><?php echo $row->devicebrand_name==""?lang('deviceview_tabtbodyunknow'):$row->devicebrand_name?></td>
			<td>
			<div style="background-color: <?php echo BLOCK_COLOR?>; height: <?php echo BLOCK_HEIGHT?>; width: <?php echo BLOCK_MAX_LENGTH*$row->percentage?>px;"></div>
			</td>
			<td><?php echo 100*$row->percentage."%"?></td>
		</tr>
		<?php 
	        }?>
	</tbody>
</table>

</div>


</article>

<article class="module width_full">
<header>
<h3 class="tabs_involved"><?php echo  lang('deviceview_detailheader')?></h3>
<!--<div class="submit_link"><a-->
<!--	href="<?php echo site_url()?>/report/device/export/<?php echo $from.'/'.$to?>">-->
<!--	<img  src="<?php echo base_url(); ?>assets/images/export.png"/></a></div>-->
<span class="relative r">                	
   <a href="<?php echo site_url()?>/report/device/export/<?php echo $from.'/'.$to?>" class="bottun4 hover" ><font><?php echo  lang('deviceview_exportinfo')?></font></a>
</span>	
</header>

<table class="tablesorter" cellspacing="0">
	<thead>
		<tr>
			<th><?php echo  lang('deviceview_detailtheadtypeid')?></th>
			<th><?php echo  lang('deviceview_detailtheadpercent')?></th>


		</tr>
	</thead>
	<tbody id="devicepageinfo">
		<div id='out'>
		
	    <?php 
	        $array = $operator->result();
	        if(count($array)<PAGE_NUMS){
	        	$nums=count($array);
	        }else{
	        	$nums = PAGE_NUMS;
	        }
	        for ($i=0;$i<$nums;$i++)
	        {
	        	?>
	        	<tr><td><?php echo $array[$i]->devicebrand_name?></td>
			    <td><?php echo round(100*$array[$i]->percentage,2)."%"?></td>
			    </tr>
	        	<?php 
	        }
	    ?>  
	    </div> 
	
	</tbody>
</table>

<footer>
<div class="submit_link">
<?php 
 
 /*计算总页数
         if($records){
             if($records<PAGE_NUMS) { //如果总数量小于每页的记录数量$PageSize，那么只有一页.
                 $PageCount = 1;
             }
             if($records%PAGE_NUMS) { //总数量除以每页的记录数量取于
                 $PageCount =(int)($records/PAGE_NUMS)+1;//如果有于，则页数等于总数量除每页的记录数加1
             }
             else{
                 $PageCount =$records/PAGE_NUMS;//没有，则结果是页数
             }
         }
         else{
             $PageCount = 0;
         }
*/
         
         //计算总页数
         
         //页数链接显示
//         $PageOut = '';
//         if($page==1){//如果页数只有一页
//             $PageOut .= '第一页|上一页';
//         }
//         else{
//             $PageOut .= '<a href="javascript:test()">第一页& lt;/a>|<a href="index.php?page='.($page-1).'">上一页</a>|';
//         }
//         if($page==$PageCount||$PageCount==0){//如果当前页等于总也数
//             $PageOut .= '下一页|尾页';
//         }
//         else{
//             $PageOut .=  '<a href="javascript:gotopage('.$page.')">下一页</a>|<a href="index.php?page='.$PageCount.'">尾页</a>';
//         }
//         echo $PageOut;
        // for ($i=1;$i<=$PageCount;$i++)
         //{         	
//         	 echo '<a href="?page='.$i.'">'.' '.$i.' '.'</a>';
        // }
        

?></div>
<div id="pagination"  class="submit_link"></div>
</footer>
</article>
</section>
<script type="text/javascript">
//这里必须最先加载
    document.getElementById('select').value= time;
    if(time=='any')
    {
    	document.getElementById('dpFrom').value = fromTime;
    	document.getElementById('dpTo').value = toTime;

    }
</script>
<script type="text/javascript">
dispalyOrHideTimeSelect();
$(function() {
	$("#dpFrom" ).datepicker();
});
$( "#dpFrom" ).datepicker({ dateFormat: "yy-mm-dd" });
$(function() {
	$( "#dpTo" ).datepicker();
});
$( "#dpTo" ).datepicker({ dateFormat: "yy-mm-dd" });
//When page loads...
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$("ul.tabs3 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

//On Click Event
$("ul.tabs2 li").click(function() {
	$("ul.tabs2 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});
$("ul.tabs3 li").click(function() {
	$("ul.tabs3 li").removeClass("active"); //Remove any "active" class
	$(this).addClass("active"); //Add "active" class to selected tab
	var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
	$(activeTab).fadeIn(); //Fade in the active ID content
	return true;
});


function onBtn()
{  
	time='any';
	fromTime = document.getElementById('dpFrom').value;
	toTime = document.getElementById('dpTo').value;
	getdata();
}
function dispalyOrHideTimeSelect()
{
	 var value = document.getElementById('select').value;
	 if(value=='any')
	 {
		 document.getElementById('selectTime').style.display="inline";
	 }
	 else
	 {			 
		 document.getElementById('selectTime').style.display="none";
	 } 
}

function selectChange(value)
{
    if(value=='any')
    {
        time='any';
    }	
    else
    {
        time=value;
        getdata();
    }
    dispalyOrHideTimeSelect();           
}


function getdata()
{
	if(time=='any')
	    window.location = "<?php echo site_url().'/report/device/getDeviceData/'?>"+time+"/"+fromTime+"/"+toTime;
	else
		window.location = "<?php echo site_url().'/report/device/getDeviceData/'?>"+time;
}
</script>

<script type="text/javascript">

  var http_request=false;
  function send_request(url){//初始化，指定处理函数，发送请求的函数
    http_request=false;
    //开始初始化XMLHttpRequest对象
    if(window.XMLHttpRequest){//Mozilla浏览器
     http_request=new XMLHttpRequest();
     if(http_request.overrideMimeType){//设置MIME类别
       http_request.overrideMimeType("text/xml");
     }
    }
    else if(window.ActiveXObject){//IE浏览器
     try{
      http_request=new ActiveXObject("Msxml2.XMLHttp");
     }catch(e){
      try{
      http_request=new ActiveXobject("Microsoft.XMLHttp");
      }catch(e){}
     }
    }
    if(!http_request){//异常，创建对象实例失败
     window.alert("<?php echo  lang('deviceview_jsalertinfo')?>");
     return false;
    }
    http_request.onreadystatechange=processrequest;
    //确定发送请求方式，URL，及是否同步执行下段代码
    http_request.open("GET",url,true);
    http_request.send(null);
  }
  //处理返回信息的函数
  function processrequest(){
   if(http_request.readyState==4){//判断对象状态
     if(http_request.status==200){//信息已成功返回，开始处理信息
      document.getElementById(reobj).innerHTML=http_request.responseText;
     }
     else{//页面不正常
      alert("<?php echo  lang('deviceview_jsalertseinfo')?>");
     }
   }
  }
  function dopage(obj,url){
   document.getElementById(out).innerHTML="<?php echo  lang('deviceview_jscallbackreadinfo')?>";
   reobj = obj;
   send_request(url);
   }
</script>
<!-- 设置分页 -->
<script type="text/javascript">
function pageselectCallback(page_index, jq){
	page_index = arguments[0] ? arguments[0] : "0";
	jq = arguments[1] ? arguments[1] : "0";   
	var index = page_index*<?php echo PAGE_NUMS?>;
	var pagenum = <?php echo PAGE_NUMS?>;	
	var msg = "";
	
	for(i=0;i<pagenum && (index+i)<operator.length ;i++)
	{ 
		msg = msg+"<tr><td>";
		msg = msg + operator[i+index].devicebrand_name;
		msg = msg + "</td><td>";
		msg = msg + (operator[i+index].percentage*100).toFixed(2)+"%";
		msg = msg + "</td></tr>";
		
	}
	
   document.getElementById('devicepageinfo').innerHTML = msg;				
}

/** 
 * Callback function for the AJAX content loader.
 */
function initPagination() {
   var num_entries = <?php if(isset($num)) echo $num; ?>/<?php echo PAGE_NUMS;?>;
    // Create pagination element
    $("#pagination").pagination(num_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo  lang('allview_jsbeforepage')?>',       //上一页按钮里text 
        next_text: '<?php echo  lang('allview_jsnextpage')?>',       //下一页按钮里text            
        num_display_entries: 8,
        callback: pageselectCallback,
        items_per_page:1
    });
 }
        
// Load HTML snippet with AJAX and insert it into the Hiddenresult element
// When the HTML has loaded, call initPagination to paginate the elements        
$(document).ready(function(){  
	initPagination();
	
});    
</script>