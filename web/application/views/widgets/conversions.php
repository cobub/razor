<?php
/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */
?>
<section class="section_maeginstyle" id="highchart"
<?php if (!isset($delete)) {?>
    style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;"<?php
}?>>
    <article class="module width_full">
        <header>
            <div style="float: left; margin-left: 2%; margin-top: 7px;">
    <?php

if (isset($add)) {
    ?>
    <a href="#" onclick="addreport()"> <img
                    src="<?php echo base_url();?>assets/images/addreport.png"
                    title="<?php echo lang('s_suspend_title')?>" style="border: 0" /></a>
    <?php
} if (isset($delete)) {
        ?>
    <a href="#" onclick="deletereport()"> <img
                    src="<?php echo base_url();?>assets/images/delreport.png"
                    title="<?php echo lang('s_suspend_deltitle')?>" style="border: 0" /></a>
    <?php
}?>
    </div>
            <h3 class="h3_fontstyle">
    <?php echo lang('v_rpt_re_funnelModel');?></h3>
            <ul class="tabs3" id="tabs3">
                <li class="active"><a href="javascript:change('count',0);"><?php echo lang('v_rpt_re_funneleventC');?></a></li>
                <li><a href="javascript:change('value',1);"><?php echo lang('v_rpt_re_unitprice');?></a></li>
                <li><a href="javascript:change('conversion',2);"><?php echo lang('v_rpt_re_funnelConversionrate');?></a></li>
            </ul>
        </header>
        <div id="container" class="module_content" style="height: 300px"></div>
    </article>
</section>

<script type="text/javascript">
var options ;
var show_thrend=1;
var show_markevent=1;
var markEventIndex=[];
var formaterdata;
var myurl='<?php echo site_url()?>/report/funnels/getChartData';
$(document).ready(function() {
    options = {
            chart: {
                renderTo: 'container',
                type:'line'
            },
            title: {
                text: ''
            },
            subtitle: {
                text: ' '
            },
            xAxis: {
                categories:'',
                labels: {
                    rotation: -45,
                    align: 'right',
                    style: {
                        fontSize: '11px',
                        fontFamily: ' sans-serif'
                    }
                }
            },
            yAxis: {
                    allowDecimals:false,
                    title: { text:'<?php echo lang('v_rpt_re_funneleventC');?>'},
                    min:0
            },
            plotOptions: {
                column: {
                    pointPadding: 0.3,
                    borderWidth: 0,
                    showInLegend:false
                },series:{
                    cursor:'pointer',
                    events:{
                        click:function(e){
                            if(show_markevent=='1'){
                                if(!markEventIndex.content(e.point.series.index)){
                                    sendBack(e);
                                    return;
                                    }
                                var rights=e.point.rights==1?'<?php echo lang('m_public')?>':'<?php echo lang('m_private')?>';
                                var content='<div><?php echo lang('m_marktime')?>:'+e.point.date+'</div>';
                                content+='<div><?php echo lang('m_user')?>:'+e.point.username+'</div>';
                                content+='<div><?php echo lang('m_title')?>:'+e.point.title+'</div>';
                                content+='<div><?php echo lang('m_description')?>:'+e.point.description+'</div>';
                                content+='<div><?php echo lang('m_rights')?>:'+rights+'</div>';
                                 hs.htmlExpand(null, {
                                     pageOrigin: {
                                         x: e.pageX,
                                         y: e.pageY
                                     },
                                     headingText: '<?php echo lang('m_eventsDetail')?>',
                                     maincontentText:content,
                                     width: 200
                                 });
                                }    
                            }
                        }
                    }
            },
            tooltip: {
                    crosshairs: true,
                     shared: true
                  ,
                formatter:function(){
                    var msg='';
                    if(show_markevent==1){//mean view product
                        if(typeof(this.points)!='undefined'&&this.points.length>0){
                            msg='<?php echo lang("g_date")?>:'+this.x+'<br/>';
                            var length=this.points.length;
                            for(i=0;i<length;i++){
                                var point=this.points[i];
                                var unitprice;
                                if(!markEventIndex.content(point.series.index)){
                                    for(var j=0;j<formaterdata.length;j++)
                                     {
                                         var targetname=formaterdata[j].targetname;                                         
                                         if(point.series.name==targetname)
                                         {
                                             var price=formaterdata[j].unitprice;
                                             unitprice=point.y*price;
                                             break;
                                        }
                                      }                                    
                                    msg+='<?php echo lang('v_rpt_re_funnelTarget');?>:'+point.series.name +','+'<?php echo lang("v_rpt_re_count");?>'+':'+point.y+","+"<?php echo lang('v_rpt_re_unitprice');?>:"+unitprice+'<br/>';
                                    }
                                }
                            }
                    }else{//mean compare
                        var length=this.points.length;
                        msg+='<?php echo lang("g_date")?>:'+this.x+'<br/>';
                        for(i=0;i<length;i++){
                            var point=this.points[i];
                            msg+='<?php echo lang('v_rpt_re_funnelTarget');?>:'+point.series.name+'<br/>';
                            var point=this.points[i];
                            if('count'==point.point.type){
                                msg+='<?php echo lang("v_rpt_re_count");?>:'+point.y+'<br/>';
                            }
                            if('conversion'==point.point.type){
                                msg+='<?php echo lang('v_rpt_re_funnelConversionrate');?>:'+point.y+'%<br/>';
                            }
                            if('value'==point.point.type){
                                msg+='<?php echo lang('v_rpt_re_unitprice');?>:'+point.y+'<br/>';
                            }
                        }    
                      }
                    
                    return msg;
                    }
            },
            credits: {
                enabled: false
            },
            series: [
                
            ]
        };
    renderCharts(myurl);
    
});

    function renderCharts(myurl)
    {        
         var chart_canvas = $('#container');
         var loading_img = $("<img src='<?php echo base_url();?>/assets/images/loader.gif'/>");
         chart_canvas.block({
                message: loading_img,
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
         
        jQuery.getJSON(myurl, null, function(data) { 
            if(typeof data.common!='undefined'){
                show_thrend=data.common.show_thrend;
                show_markevent=data.common.show_markevent;
                //means compare ways
                formaterdata=data.dataList;
                dataresult =data.result;
                change('count',0);
            }else{
                $('#tabs3').remove();
                formaterdata=data.dataList;
                contentConversion(data);
                chart = new Highcharts.Chart(options);
             }
            chart_canvas.unblock();
            });  
    }
//produc

var count=[];
var value=[];
var dataresult;
var conversion=[];
//tab 切换显示
function change(name,i){
    var time_array =[];
    options.series=[];
    $('#tabs3 li').each(function(dx,item){
        if(i==dx){
                $(this).addClass('active');
            }else{
                $(this).removeClass('active');
                }
        });
    $.each(dataresult,function(index,item){
        var con=[];
        var counts=[];
        var value=[];
        if(index==0){//push time_array
            for(var date in item.unitprice[0]){
                time_array.push(date);
                }
            }
            for(var date in item.date){
                for(var u in item.unitprice[0]){
                        if(date==u){
                            var conversion=0;
                            if(parseInt(item.unitprice[0][u])==0||parseInt(item.scount[u])==0){
                                conversion=0;
                                }else{
                                    conversion=item.scount[u]/item.date[u];
                                    }
                        value.push({y:item.unitprice[0][u],type:'value'});
                        counts.push({y:item.date[u],type:'count'});//({y:item.date[u],unitprice:item.unitprice[0][u],total:item.unitprice[0][u],conversion:Math.round(conversion*100)});
                        con.push({y:Math.round(conversion*100),type:'conversion'});
                        }
                 }
            }
            if('count'==name){
                var l=options.series.length;
                options.series[l] = {};
                 options.series[l].data = counts;
                 options.series[l].name = item.name;
                 options.yAxis.title.text='<?php echo lang('v_rpt_re_funneleventC');?>';
            }
            if('value'==name){
                var l=options.series.length;
                options.series[l] = {};
                 options.series[l].data = value;
                 options.series[l].name = item.name;
                    options.yAxis.title.text='<?php echo lang('v_rpt_re_unitprice');?>';
            }
            if('conversion'==name){
                //content coversion rate
                var len=options.series.length;
                 options.series[len]={};
                 //options.series[len].yAxis=1;
                 options.series[len].data=con;
                 options.series[len].name = item.name;
                 options.yAxis.title.text='<?php echo lang('v_rpt_re_funnelConversionrate');?>';
                 //options.series[len].showInLegend=false;
            }
             if(index==1){
                 options.xAxis.categories = time_array; 
                 options.xAxis.labels.step = parseInt(time_array.length/10);
             }
        });
    options.title.text = '<?php echo lang('v_rpt_re_funnelTargettrend');?>';
    options.subtitle.text = '<?php echo $reportTitle['timePhase'];?>';
    chart = new Highcharts.Chart(options);
}
//
function contentConversion(data){
    var dataList=data.dataList;
    var time_array =[];
           for(var j=0;j<dataList.length;j++)
         {
             options.series[j] = {};
             options.series[j].data = dataList[j].eventnum;
             options.series[j].name = dataList[j].targetname;
             var num_array = [];
             var temp_item = dataList[j];
             for(var k=0;k<temp_item.eventnum.length;k++)
             {
                 if(dataList[j].eventnum[k]!=null)
                 {                        
                  num_array.push(parseInt(dataList[j].eventnum[k]));
                 }
             }
             options.series[j].data = num_array;        
         }
            for(var i=0;i<data.defdate.length;i++){
                time_array.push(data.defdate[i]);
            }
            
    options.xAxis.categories = data.defdate; 
    options.xAxis.labels.step = parseInt(data.defdate.length/10); 
    options.title.text = '<?php echo lang('v_rpt_re_funnelTargettrend');?>';
    options.subtitle.text = '<?php echo $reportTitle['timePhase'];?>';
     //content markevent
    var marklist=data.marklist;
    var defdate=data.defdate;
    var markevents=data.markevents;
    if(marklist.length>=1){
        $.each(marklist,function(index,item){
            var seriesLength=options.series.length;
            markEventIndex[index]=seriesLength;
            options.series[seriesLength]={};
            options.series[seriesLength].type='column';
            options.series[seriesLength].name="<?php echo lang('m_dateevents');?>";
            //options.plotOptions.column.showInLengend=false;
            options.colors=[];
            options.colors[seriesLength]="#DB9D00";
            var contentdata=[];
            for(var j=0;j<defdate.length;j++){
                var markevent=null;
                $.each(markevents,function(i,o){
                    if(item.userid==o.userid){
                        if(defdate[j]==o.marktime){
                            markevent=o;
                        }    
                    }
                });
                if(markevent!=null){
                    contentdata.push(markevent);
                }else{
                    contentdata.push(null);
                    }    
            }
            options.series[seriesLength].data=prepare(contentdata,options,index);
            });
        }
//end content  
}
</script>
<script type="text/javascript">
function addreport()
{    
    if(confirm( "<?php echo  lang('w_isaddreport')?>"))
    {
        var reportname="conversions";
        var reportcontroller="funnels";
        var data={ 
                 reportname:reportname,
                     controller:reportcontroller,
                     height    :380,
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
                                alert(<?php echo lang('t_error'); ?>);
                            }
                    });
        
    }
}

function deletereport()
{ 
    if(confirm( "<?php echo lang('v_deletreport')?>"))
    {
        window.parent.deletereport("conversions");
              
    }
    return false;
    
}
</script>
<?php include 'application/views/manage/pointmark_base.php';?>