<section class="section_maeginstyle" id="highchart"
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
if (! isset($delete)) {
        ?>
        style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;"
        <?php
}
    ?>>
    <article class="module width_full">
        <header>
            <div style="float: left; margin-left: 2%; margin-top: 7px;">
                <?php
                
if (isset($add)) {
                    ?>
                    <a href="#" onclick="addreport()"> <img
                    src="<?php echo base_url(); ?>assets/images/addreport.png"
                    title="<?php echo lang('s_suspend_title') ?>" style="border: 0" /></a>
                <?php
}
if (isset($delete)) {
                    ?>
                    <a href="#" onclick="deletereport()"> <img
                    src="<?php echo base_url(); ?>assets/images/delreport.png"
                    title="<?php echo lang('s_suspend_deltitle') ?>" style="border: 0" /></a>
                <?php
}
                ?>
            </div>
            <h3 class="h3_fontstyle">
                <?php echo lang('v_rpt_nw_top10') ?></h3>

            <ul class="tabs2">
                <li><a href="javascript:changeReportType('activeuser')"><?php echo lang('t_sessions') ?></a></li>
                <li><a href="javascript:changeReportType('newuser')"><?php echo lang('t_newUsers') ?></a></li>
            </ul>
        </header>
        <div id="container" class="module_content" style="height: 400px"></div>
    </article>
</section>


<script type="text/javascript">

//Init tab selector of report
$(".tab_content").hide();
$("ul.tabs2 li:first").addClass("active").show();
$(".tab_content:first").show();

//On Click Event
$("ul.tabs2 li").click(function () {
    $("ul.tabs2 li").removeClass("active");
    $(this).addClass("active");
    var activeTab = $(this).find("a").attr("id");
    $(activeTab).fadeIn();
    return true;
});


var chart;
var options;
var sessionData = [];
var sessionCategories = [];

var newUserData = [];
var newUserCategories = [];

var reportTitle = '<?php echo $reportTitle['activeUserReport'] ?>';

options = {
    chart: {
        renderTo: 'container',
        type: 'bar'
    },

    title: {
        text: '   '
    },
    subtitle: {
        text: '   '
    },
    xAxis: [
        {
            categories: [],
            title: {
                text: null
            },
            tickmarkPlacement: 'on'
        },
        {
            categories: [],
            title: {
                text: null
            },
            labels: {
                formatter: function () {
                    //return this.value +'%';
                }
            },
            opposite: true
        }
    ],
    yAxis: {
        min: 0,
        title: {
            text: '',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },

    tooltip: {
        formatter: function () {
            return '' +
                this.series.name + ': ' + this.y + ' ';
        }
    },

    plotOptions: {
        bar: {
            pointWidth: 20,
            dataLabels: {
                enabled: true,
                formatter: function () {
                    return '' + this.y;
                }
            }
        },
        scatter: {
            marker: {
                enabled: false
            }
        }
    },
    legend: {
        enabled: false
    },
    credits: {
        enabled: false
    },
    series: [
        {
            xAxis: 0,
            name: '<?php echo lang('v_rpt_re_count') ?>'
        },
        {
            type: 'scatter',
            xAxis: 1,
            name: ''
        }
    ]
};


$(document).ready(function () {
    var osDataURL = "<?php echo site_url();?>/report/network/getNetWorkData";
    renderCharts(osDataURL);
});

function renderCharts(myurl) {
    var chart_canvas = $('#container');
    var loading_img = $("<img src='<?php echo base_url();?>/assets/images/loader.gif'/>");

    chart_canvas.block({
        message: loading_img,
        css: {
            width: '32px',
            border: 'none',
            background: 'none'
        },
        overlayCSS: {
            backgroundColor: '#FFF',
            opacity: 0.8
        },
        baseZ: 997
    });

    jQuery.getJSON(myurl, null, function (data) {

        var obj = data.activeUserData;
        for (i = 0; i < obj.length; i++) {
            if (obj[i].networkname.length < 1)
                obj[i].networkname = 'unknown';
            sessionCategories.push(obj[i].networkname);
            sessionData.push(obj[i].sessions);
        }

        var objNewUserData = data.newUserData;
        for (i = 0; i < objNewUserData.length; i++) {
            if (objNewUserData[i].networkname.length < 1)
                objNewUserData[i].networkname = 'unknown';
            newUserCategories.push(objNewUserData[i].networkname);
            newUserData.push(objNewUserData[i].newusers);
        }

        changeReportType('activeuser');
        chart_canvas.unblock();
    });
}


function changeReportType(reportType) {
    if (reportType == "activeuser") {

        options.chart.renderTo = "container";
        options.series[0].data = sessionData;
        options.series[1].data = sessionData;
        options.xAxis[0].categories = sessionCategories;
        //options.xAxis[1].categories = sessionPercent ;

        options.title.text = '<?php echo $reportTitle['activeUserReport'] ?>';
        options.subtitle.text = '<?php echo $reportTitle['timePhase'] ?>';

        chart = new Highcharts.Chart(options);

    }

    if (reportType == "newuser") {
        options.chart.renderTo = "container";
        options.series[0].data = newUserData;
        options.series[1].data = newUserData;
        options.xAxis[0].categories = newUserCategories;
        //options.xAxis[1].categories = newUserPercent;

        options.title.text = '<?php echo $reportTitle['newUserReport'] ?>';
        options.subtitle.text = '<?php echo $reportTitle['timePhase'] ?>';
        chart = new Highcharts.Chart(options);
    }
}

function addreport() {
    if (confirm("<?php echo  lang('w_isaddreport')?>")) {
        var reportname = "network";
        var reportcontroller = "network";
        var data = {
            reportname: reportname,
            controller: reportcontroller,
            height: 480,
            type: 1,
            position: 0
        };
        jQuery.ajax({
            type: "post",
            url: "<?php echo site_url()?>/report/dashboard/addshowreport",
            data: data,
            success: function (msg) {
                if (msg == "") {
                    alert("<?php echo lang('w_addreportrepeat');?>");
                }
                else if (msg >= 8) {
                    alert("<?php echo  lang('w_overmaxnum');?>");
                }
                else {
                    alert("<?php echo lang('w_addreportsuccess');?>");
                }

            },
            error: function (XmlHttpRequest, textStatus, errorThrown) {
                alert(<?php echo lang('t_error');?>);
            }
        });

    }
}

function deletereport() {
    if (confirm("<?php echo  lang('v_deletreport')?>")) {
        window.parent.deletereport("network");
    }
    return false;

}
</script>