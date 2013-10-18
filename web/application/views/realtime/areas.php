<script src="<?php echo base_url();?>assets/js/jquery.easyui.min.js"></script>
<script src="<?php echo base_url();?>assets/js/datagrid-detailview.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/css/jquery.easyui.css"></link>

<section id="main" class="column"  style="height:1800px">
<article  class="module width_full">
<table id="dg" class="width_full" style="height:500px"
            url="<?php echo site_url();?>/realtime/areas/getAreaDataForGrid/<?php echo $productId?>"
            pagination="false" sortName="countryName" sortOrder="desc"
            title="<?php echo lang('v_rpt_realtime_areas_title');?>"
            singleSelect="true" fitColumns="true" border="false">
        <thead>
            <tr>
                <th field="countryName" width="100%"><?php echo lang('v_rpt_re_nation');?></th>
                <th field="countrySize" width="100%"><?php echo lang('v_rpt_realtime_onlineuser_size');?></th>
            </tr>
        </thead>
    </table>
    <script type="text/javascript">
        $(function(){
            $('#dg').datagrid({
                view: detailview,
                detailFormatter:function(index,row){
                    return '<div id="ddv-' + index + '" style="padding:5px 0"></div>';
                },
                onExpandRow: function(index,row){
                    $('#ddv-'+index).panel({                        
                        border:true,
                        cache:false,
                        href:'<?php echo site_url();?>/realtime/areas/getDetailRegionsInfo/<?php echo $productId?>/'+row.countryName,
                        onLoad:function(){
                            $('#dg').datagrid('fixDetailRowHeight',index);
                        }
                    });
                    $('#dg').datagrid('fixDetailRowHeight',index);
                }
            });
        });
    </script>
</article>
</section>

<script type="text/javascript">
function flashTable(){ 
    $("#dg").datagrid("reload"); 
} 

$(document).ready(function() {
    window.setInterval(flashTable,30000);
} );
</script>