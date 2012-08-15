<?php

/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */

class Archive extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	/*
	 * Schedule hourly task to do the etl from production databse to data warehouse
	 */
	function archiveHourly()
	{
		$today = date('Y-m-d',time());
		$fromTime = date('Y-m-d H:00:00',strtotime("-1 hour"));
		$toTime = date('Y-m-d H:59:59',strtotime("-1 hour"));
// 		echo $fromTime;
		$date = date('Y-m-d',strtotime("-1 hour"));
		$dwdb = $this->load->database('dw',TRUE);
		
		//do etl to dimention table
		$logdate = date('Y-m-d H:i:s',time());
		log_message("error","ETL RunDim at ".$logdate);
		echo $logdate;
  		$dwdb->query("call rundim()");
		
		//run fact
		$logdate = date('Y-m-d H:i:s',time());
		log_message("error","ETL runfact at $logdate and fromTime= $fromTime toTime= $toTime");
  		$dwdb->query("call runfact($fromTime,$toTime)");

		//run sum
		$logdate = date('Y-m-d H:i:s',time());
  		log_message("error","ETL runsum at $logdate and rundate = $date");
  		$dwdb->query("call runsum($date)");
	}
	
	/*
	 * Schedule weekly task to do the statics
	 * Caculate from sunday to sataday.
	 * Must scheduled at sunday.
	 */
	function archiveWeekly()
	{
		$dwdb = $this->load->database('dw',TRUE);
		
		$d = new DateTime();
		$weekday = $d->format('w');
		$diff = ($weekday == 0 ?7 : $weekday); // Monday=0, Sunday=6
		$d->modify("-$diff day");
		$lastSunday = $d->format('Y-m-d');
		$d->modify('+6 day');
		$lastSataday = $d->format('Y-m-d');

// 		echo $lastSunday. " " . $lastSataday;
		$logdate = date('Y-m-d H:i:s',time());
		log_message("error","ETL runweekly at $logdate and fromDate = $lastSunday and toDate = $lastSataday");
 		$dwdb->query("call runweekly($lastSunday,$lastSataday)");
	}
	
	/*
	 * Schedule monthly task to do the statics
	 */
	function archiveMonthly()
	{
		$dwdb = $this->load->database('dw',TRUE);
		
		$lastMonthStartDate = date("Y-m-1", strtotime("-1 month") ) ;
		$lastMonthEndDate = date("Y-m-t", strtotime("-1 month") ) ;
		$logdate = date('Y-m-d H:i:s',time());
		log_message("error","ETL runmonthly at $logdate and fromTime = $lastMonthStartDate and toTime = $lastMonthEndDate");
 	 	$dwdb->query("call runmonthly($lastMonthStartDate,$lastMonthEndDate)");
	}
	
	/*
	 * Schedule task for later data, from last 7days to yestoday
	 */
	function archiveLaterData()
	{
		$today = date('Y-m-d',time());
		$fromTime = date('Y-m-d',strtotime("-7 day"));
		$toTime = date('Y-m-d',strtotime("-1 day"));
// 		echo $fromTime.' '.$toTime;
		//run sum
		$dwdb = $this->load->database('dw',TRUE);
		$logdate = date('Y-m-d H:i:s',time());
		log_message("error","ETL runArchiveLater at $logdate and fromDate = $fromTime and toDate = $toTime");
		for($i=1;$i<8;$i++)
		{
			$date = date('Y-m-d',strtotime("-$i day"));
// 			echo $date."<br>";
			log_message("error","ETL runArchiveLater run sunm at $date");
  			$dwdb->query("call runsum($date)");
		}
	}
}