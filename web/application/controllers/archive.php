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
		$this->load->helper('date');
		$this->load->model('comparevalue/compare','compare');
		$this->load->model('alert/sendEmail','sendEmail');
	}
	
	/*
	 * Schedule hourly task to do the etl from production databse to data warehouse
	 */
	function archiveHourly()
	{
		$timezonestimestamp = gmt_to_local(local_to_gmt(), $this->config->item('timezones'));
		$timezonestime = date ( 'Y-m-d H:i:m', $timezonestimestamp );
		
		$fromTime = date('Y-m-d H:00:00',strtotime("-1 hour", strtotime($timezonestime)));
		$toTime = date('Y-m-d H:59:59',strtotime("-1 hour", strtotime($timezonestime)));
// 		echo $fromTime;
		$date = date('Y-m-d',strtotime("-1 hour", strtotime($timezonestime)));
		$dwdb = $this->load->database('dw',TRUE);
		
		//do etl to dimention table
		$logdate = date('Y-m-d H:i:s',$timezonestimestamp);
		log_message("debug","ETL RunDim at ".$logdate);
//		echo $logdate;
  		$dwdb->query("call rundim()");
		
		//run fact
		$logdate = date('Y-m-d H:i:s',$timezonestimestamp);
		log_message("debug","ETL runfact at $logdate and fromTime= $fromTime toTime= $toTime");
  		$dwdb->query("call runfact('$fromTime','$toTime')");

		//run sum
		$logdate = date('Y-m-d H:i:s',$timezonestimestamp);
  		log_message("debug","ETL runsum at $logdate and rundate = $date");
  		$dwdb->query("call runsum('$date')");
	}
	
	/*
	 * Schedule weekly task to do the statics
	 * Caculate from sunday to sataday.
	 * Must scheduled at sunday.
	 */
	function archiveWeekly()
	{
		$dwdb = $this->load->database('dw',TRUE);
		$timezonestimestamp = gmt_to_local(local_to_gmt(), $this->config->item('timezones'));
		$timezonestime = date ( 'Y-m-d', $timezonestimestamp );

		$lastSataday = date("Y-m-d", strtotime("-1 day", strtotime($timezonestime))) ;
		$lastSunday =  date("Y-m-d", strtotime("-7 day", strtotime($timezonestime))) ;
// 		echo $lastSunday. " " . $lastSataday;
		$logdate = date('Y-m-d H:i:s',$timezonestimestamp);
		log_message("debug","ETL runweekly at $logdate and fromDate = $lastSunday and toDate = $lastSataday");
 		$dwdb->query("call runweekly('$lastSunday','$lastSataday')");
	}
	
	/*
	 * Schedule monthly task to do the statics
	 */
	function archiveMonthly()
	{
		$dwdb = $this->load->database('dw',TRUE);
		
		$timezonestimestamp = gmt_to_local(local_to_gmt(), $this->config->item('timezones'));
		$timezonestime = date ( 'Y-m-d', $timezonestimestamp );
		
		$lastMonthStartDate = date("Y-m-1", strtotime("-1 month", strtotime($timezonestime))) ;
		$lastMonthEndDate = date("Y-m-t", strtotime("-1 month", strtotime($timezonestime))) ;
//		echo $lastMonthStartDate. " " .$lastMonthEndDate; 
		$logdate = date('Y-m-d H:i:s',$timezonestimestamp);
		log_message("debug","ETL runmonthly at $logdate and fromTime = $lastMonthStartDate and toTime = $lastMonthEndDate");
 	 	$dwdb->query("call runmonthly('$lastMonthStartDate','$lastMonthEndDate')");
	}
	
	/*
	 * Schedule task for later data, from last 7days to yestoday
	 */
	function archiveLaterData()
	{
		$timezonestimestamp = gmt_to_local(local_to_gmt(), $this->config->item('timezones'));
		$timezonestime = date ( 'Y-m-d H:i:m', $timezonestimestamp );
		$fromTime = date('Y-m-d',strtotime("-7 day", strtotime($timezonestime)));
		$toTime = date('Y-m-d',strtotime("-1 day", strtotime($timezonestime)));
// 		echo $fromTime.' '.$toTime;
		//run sum
		$dwdb = $this->load->database('dw',TRUE);
		$logdate = date('Y-m-d H:i:s',$timezonestimestamp);
		log_message("debug","ETL runArchiveLater at $logdate and fromDate = $fromTime and toDate = $toTime");
		for($i=1;$i<8;$i++)
		{
			$date = date('Y-m-d',strtotime("-$i day", strtotime($timezonestime)));
//			echo $date."<br>";
			log_message("debug","ETL runArchiveLater run sunm at $date");
  			$dwdb->query("call runsum('$date')");
		}
		
		
	}
	
	/*
	 * Schedule daily task for using log page views, yestoday
	*/
	function archiveUsingLog()
	{
		$timezonestimestamp = gmt_to_local(local_to_gmt(), $this->config->item('timezones'));
		$timezonestime = date ( 'Y-m-d H:i:m', $timezonestimestamp );
		$date = date('Y-m-d',strtotime("-1 day", strtotime($timezonestime)));
		//run archiveUsinglog
		$dwdb = $this->load->database('dw',TRUE);
		$logdate = date('Y-m-d H:i:s',$timezonestimestamp);
		log_message("debug","ETL archiveUsingLog at $logdate and date = $date");
		$dwdb->query("call rundaily('$date')");
		$this->archiveCompareValue($date);
	}
	
	function archiveCompareValue($date){
// 		$timezonestimestamp = gmt_to_local(local_to_gmt(), $this->config->item('timezones'));
// 		$timezonestime = date ( 'Y-m-d H:i:m', $timezonestimestamp );
// 		$date = date('Y-m-d',strtotime("-1 day", strtotime($timezonestime)));
		$this->sendEmail->comparevalue($date);
	}
	
	
}