<style>
/*this is the style of helpview*/
#main {
	margin: 20px 40px 450px;
	padding: 0;
}

#main h3 {
	margin: 8px 0;
}

#main ul {
	padding: 6px;
}

#main li {
	margin-bottom: 9px;
}

.content {
	background-color: #FFFFFF;
	border: 1px solid #B3B4BD;
	font-family: font-family : Lucida Grande, Verdana, Geneva, Sans-serif;
	margin-top: 10px;
	padding: 6px;
	font-size:13px;
}

.rmborder {
	border-left: 0px;
	border-right: 0px;
}
.left {
	width: 150px;
	text-align: left;
	padding-left: 5px;
}

.right {
	text-align: left;
	line-height: 24px;
	margin-left:10px;	
}
</style>
<div id="main">
	<!-- <div class="title">
		<h3><?php echo lang('t_helpItem')?></h3>
	</div> -->
	<!-- <div class="content rmborder" style="background: none;">
		<ul>
			<li><a href="#OverviewRecently"><?php echo lang('v_rpt_pb_overviewRecently');?></a></li>
			<li><a href="#GeneralSituation"><?php echo lang('v_rpt_pb_generalSituation');?></a></li>
			<li><a href="#PageViewDetails"><?php echo lang('v_rpt_pv_details');?></a></li>
			<li><a href="#Retention"><?php echo lang('v_rpt_ur_retention');?></a></li>
		</ul>
	</div> -->
<div id="OverviewRecently"></div>
<h3><?php echo lang('t_newUsers') ?></h3>
<div class="content"><?php echo lang('t_newUser_def') ?></div>
<h3><?php echo lang('t_activeUsers') ?></h3>
<div class="content"><?php echo lang('t_activeUser_def') ?></div>
<h3><?php echo lang('t_newUserPer') ?></h3>
<div class="content"><?php echo lang('t_percentOfNewUsers_def') ?></div>
<h3><?php echo lang('t_sessions') ?></h3>
<div class="content"><?php echo lang('t_sessions_def') ?></div>
<h3><?php echo lang('t_upgradeUsers') ?></h3>
<div class="content"><?php echo lang('t_upgradeUsers_def') ?></div>
<h3><?php echo lang('t_averageUsageDuration') ?></h3>
<div class="content"><?php echo lang('t_averageUsageDuration_def') ?></div>
<div id="GeneralSituation"></div>
<h3><?php echo lang('t_accumulatedUsers') ?></h3>
<div class="content"><?php echo lang('t_accumulatedUsers_def') ?></div>
<h3><?php echo lang('t_accumulatedSessions') ?></h3>
<div class="content"><?php echo lang('t_accumulatedStarts_def') ?></div>
<h3><?php echo lang('t_activeUsersWeekly') ?></h3>
<div class="content"><?php echo lang('t_activeUsersWeekly_def') ?></div>
<h3><?php echo  lang('t_bounceRateP')?></h3>
<div class="content"><?php echo  lang('t_bounceRate_def')?></div>
<h3><?php echo lang('t_activeUsersMonthly') ?></h3>
<div class="content"><?php echo lang('t_activeUsersMonthly_def') ?></div>
<h3><?php echo lang('t_activePercentageW') ?></h3>
<div class="content"><?php echo lang('t_activeRateWeekly_def') ?></div>
<h3><?php echo lang('t_activeRateM') ?></h3>
<div class="content"><?php echo lang('t_activeRateMonthly_def') ?></div>
<div id="PageViewDetails"></div>
<h3><?php echo  lang('t_pageView')?></h3>
<div class="content"><?php echo  lang('t_numberOfPageViews_def')?></div>
<h3><?php echo  lang('t_accumulatedSessions')?></h3>
<div class="content"><?php echo  lang('t_accumulatedStarts_def')?></div>
<h3><?php echo  lang('t_averageDuration')?></h3>
<div class="content"><?php echo  lang('t_averageDuration_def')?></div>
<h3><?php echo  lang('t_bounceRateP')?></h3>
<div class="content"><?php echo  lang('t_bounceRate_def')?></div>
<div id="Retention"></div>
<h3><?php echo lang('m_rpt_userRetention') ?></h3>
<div class="content"><?php echo lang('t_userRetention_def') ?></div>
