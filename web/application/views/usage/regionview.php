<section id="main" class="column">
    <!-- Country -->
    <div style="height:480px;">
        <iframe src="<?php echo site_url() ?>/report/region/addregioncountryreport" frameborder="0" scrolling="no"
                style="width:100%;height:100%;"></iframe>
    </div>

    <!--Region -->
    <div style="height:480px;">
        <iframe src="<?php echo site_url() ?>/report/region/addregionprovincereport" frameborder="0" scrolling="no"
                style="width:100%;height:100%;"></iframe>
    </div>
    <!--City-->
    <div style="height:480px;">
        <iframe src="<?php echo site_url() ?>/report/region/addregioncityreport" frameborder="0" scrolling="no"
                style="width:100%;height:100%;"></iframe>
    </div>

    <article class="module width_full">
        <header>
            <h3 class="tabs_involved"><?php echo lang('v_rpt_re_detailsOfNation') ?></h3>
            <span class="relative r"> <a
                    href="<?php echo site_url() . '/report/region/exportcountry/' . $from . '/' . $to ?>"
                    class="bottun4 hover"><font><?php echo lang('g_exportToCSV') ?></font></a>
            </span>
        </header>
        <table id="countrytable" class="tablesorter" cellspacing="0">
            <thead>
            <tr>
                <th><?php echo lang('v_rpt_re_nation') ?></th>
                <th><?php echo lang('t_sessions') ?></th>
                <th><?php echo lang('t_sessionsP') ?></th>
                <th><?php echo lang('t_newUsers') ?></th>
                <th><?php echo lang('t_percentOfNewUsers') ?></th>
            </tr>
            </thead>
            <tbody id="countrydetail">
            <?php
            if (isset($activepagecoun)):
                foreach ($activepagecoun->result() as $rel) {
                    ?>
                    <tr>
                        <td><?php
                            if ($rel->country_name == 'unknown')
                                $rel->country_name = lang('t_unknow');
                            echo $rel->country_name;
                            ?></td>
                        <td><?php echo $rel->sessions; ?></td>
                        <td><?php echo ($country_session > 0) ? round(100 * $rel->sessions / $country_session, 1) . '%' : '0%'; ?></td>
                        <td><?php echo $rel->newusers; ?></td>
                        <td><?php echo ($country_newuser > 0) ? round(100 * $rel->newusers / $country_newuser, 1) . '%' : '0%'; ?></td>

                    </tr>
                <?php } endif; ?>
            </tbody>
        </table>
        <footer>
            <div id="pagination" class="submit_link"></div>
        </footer>
    </article>
    <article class="module width_full">
        <header>
            <h3 class="tabs_involved"><?php echo lang('v_rpt_re_detailsOfProvince') ?></h3>
            <!--<div class="submit_link"> <a  href="<?php echo site_url() . '/report/region/exportpro/' . $from . '/' . $to; ?>">-->
            <!--<img  src="<?php echo base_url(); ?>assets/images/export.png"/></a></div>-->
            <span class="relative r"> <a
                    href="<?php echo site_url() . '/report/region/exportpro/' . $from . '/' . $to; ?>"
                    class="bottun4 hover"><font><?php echo lang('g_exportToCSV') ?></font></a>
            </span>
        </header>
        <table id="regionTable" class="tablesorter" cellspacing="0">
            <thead>
            <tr>
                <th><?php echo lang('v_rpt_re_province') ?></th>
                <th><?php echo lang('t_sessions') ?></th>
                <th><?php echo lang('t_sessionsP') ?></th>
                <th><?php echo lang('t_newUsers') ?></th>
                <th><?php echo lang('t_percentOfNewUsers') ?></th>
            </tr>
            </thead>
            <tbody id="prodetail">
            <?php
            if (isset($activepagepro)):
                foreach ($activepagepro->result() as $ret) {
                    ?>
                    <tr>
                        <td><?php echo $ret->region_name ?></td>
                        <td><?php echo $ret->sessions; ?></td>
                        <td><?php echo ($pro_session > 0) ? round(100 * $ret->sessions / $pro_session, 1) . '%' : '0%'; ?></td>
                        <td><?php echo $ret->newusers; ?></td>
                        <td><?php echo ($pro_newuser > 0) ? round(100 * $ret->newusers / $pro_newuser, 1) . '%' : '0%'; ?></td>

                    </tr>
                <?php } endif; ?>
            </tbody>
        </table>
        <footer>
            <div id="paginationpro" class="submit_link"></div>
        </footer>
    </article>
    <!--city-->
    <article class="module width_full">
        <header>
            <h3 class="tabs_involved"><?php echo lang('v_rpt_re_detailsOfCity') ?></h3>
            <!--<div class="submit_link"> <a  href="<?php echo site_url() . '/report/region/exportpro/' . $from . '/' . $to; ?>">-->
            <!--<img  src="<?php echo base_url(); ?>assets/images/export.png"/></a></div>-->
            <span class="relative r"> <a
                    href="<?php echo site_url() . '/report/region/exportcity/' . $from . '/' . $to; ?>"
                    class="bottun4 hover"><font><?php echo lang('g_exportToCSV') ?></font></a>
            </span>
        </header>
        <table id="cityTable" class="tablesorter" cellspacing="0">
            <thead>
            <tr>
                <th><?php echo lang('v_rpt_re_city') ?></th>
                <th><?php echo lang('t_sessions') ?></th>
                <th><?php echo lang('t_sessionsP') ?></th>
                <th><?php echo lang('t_newUsers') ?></th>
                <th><?php echo lang('t_percentOfNewUsers') ?></th>
            </tr>
            </thead>
            <tbody id="citydetail">
            <?php
            if (isset($activepagecity)):
                foreach ($activepagecity->result() as $ret) {
                    ?>
                    <tr>
                        <td><?php echo $ret->city_name ?></td>
                        <td><?php echo $ret->sessions ?></td>
                        <td><?php echo ($city_session > 0) ? round(100 * $ret->sessions / $city_session, 1) . '%' : '0%'; ?></td>
                        <td><?php echo $ret->newusers ?></td>
                        <td><?php echo ($city_newuser > 0) ? round(100 * $ret->newusers / $city_newuser, 1) . '%' : '0%'; ?></td>
                    </tr>
                <?php } endif; ?>
            </tbody>
        </table>
        <footer>
            <div id="paginationcity" class="submit_link"></div>
        </footer>
    </article>
    <div class="clear"></div>
    <div class="spacer"></div>
</section>

<!--Country active users detail -->
<script type="text/javascript">
    function pageselectCallback(page_index, jq) {

        var chart_canvas = $('#countryTable');
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

        page_index = arguments[0] ? arguments[0] : "0";
        jq = arguments[1] ? arguments[1] : "0";
        var myurl = "<?php echo site_url()?>/report/region/activecountrypage/" + page_index;
        jQuery.ajax({
            type: "post",
            url: myurl,
            success: function (msg) {
                document.getElementById('countrydetail').innerHTML = msg;
                chart_canvas.unblock();
            },
            error: function (XmlHttpRequest, textStatus, errorThrown) {
                alert("<?php echo lang('t_error') ?>");
                chart_canvas.unblock();
            }
        });
        return false;
    }

    /**
     * Callback function for the AJAX content loader.
     */
    function initPagination() {
        var num_entries =
        <?php if(isset($counum)) echo $counum; ?>/<?php echo PAGE_NUMS;?>;
        // Create pagination element
        $("#pagination").pagination(num_entries, {
            num_edge_entries: 2,
            prev_text: '<?php echo lang('g_previousPage') ?>',       //上一页按钮里text
            next_text: '<?php echo lang('g_nextPage') ?>',       //下一页按钮里text
            num_display_entries: 4,
            callback: pageselectCallback,
            items_per_page: 1
        });
    }

    // Load HTML snippet with AJAX and insert it into the Hiddenresult element
    // When the HTML has loaded, call initPagination to paginate the elements
    $(document).ready(function () {
        initPagination();
        initproPagination();
        initcityPagination();
    });
</script>
<!--Region detail users  -->
<script type="text/javascript">
    function pageproCallback(page_index, jq) {
        var chart_canvas = $('#regionTable');
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
        page_index = arguments[0] ? arguments[0] : "0";
        jq = arguments[1] ? arguments[1] : "0";
        var myurl = "<?php echo site_url()?>/report/region/activepropage/" + page_index;
        jQuery.ajax({
            type: "post",
            url: myurl,
            success: function (msg) {
                document.getElementById('prodetail').innerHTML = msg;
                chart_canvas.unblock();
            },
            error: function (XmlHttpRequest, textStatus, errorThrown) {
                alert("<?php echo lang('t_error') ?>");
                chart_canvas.unblock();
            }
        });
        return false;
    }

    /**
     * Callback function for the AJAX content loader.
     */
    function initproPagination() {

        var num_entries =
        <?php if(isset($pronum)) echo $pronum; ?>/<?php echo PAGE_NUMS;?>;
        // Create paginationpro element
        $("#paginationpro").pagination(num_entries, {
            num_edge_entries: 2,
            prev_text: '<?php echo lang('g_previousPage') ?>',
            next_text: '<?php echo lang('g_nextPage') ?>',
            num_display_entries: 4,
            callback: pageproCallback,
            items_per_page: 1
        });
    }
</script>

<!--City detail users  -->
<script type="text/javascript">
    function pagecityCallback(page_index, jq) {
        var chart_canvas = $('#cityTable');
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
        page_index = arguments[0] ? arguments[0] : "0";
        jq = arguments[1] ? arguments[1] : "0";
        var myurl = "<?php echo site_url()?>/report/region/activecitypage/" + page_index;
        jQuery.ajax({
            type: "post",
            url: myurl,
            success: function (msg) {
                document.getElementById('citydetail').innerHTML = msg;
                chart_canvas.unblock();
            },
            error: function (XmlHttpRequest, textStatus, errorThrown) {
                alert("<?php echo lang('t_error') ?>");
                chart_canvas.unblock();
            }
        });
        return false;
    }

    /**
     * Callback function for the AJAX content loader.
     */
    function initcityPagination() 
    {
        var num_entries =
        <?php if(isset($citynum)) echo $citynum; ?>/<?php echo PAGE_NUMS;?>;
        // Create paginationpro element
        $("#paginationcity").pagination(num_entries, {
            num_edge_entries: 2,
            prev_text: '<?php echo lang('g_previousPage') ?>',
            next_text: '<?php echo lang('g_nextPage') ?>',
            num_display_entries: 4,
            callback: pagecityCallback,
            items_per_page: 1
        });
    }
</script>
