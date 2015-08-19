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

/**
 * Archive Controller
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Archive extends CI_Controller
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->helper('date');
        $this->load->model('alert/sendemail', 'sendemail');
    }

    /**
     * archiveHourly function
     * Schedule hourly task to do the etl from production databse to data warehouse
     *
     * @return void
     */
    function archiveHourly()
    {
        $timezonestimestamp = time();
        $timezonestime = date('Y-m-d H:i:m', $timezonestimestamp);
        $fromTime = date('Y-m-d H:00:00', strtotime("-1 hour", strtotime($timezonestime)));
        $toTime = date('Y-m-d H:59:59', strtotime("-1 hour", strtotime($timezonestime)));
        //echo $fromTime;
        $date = date('Y-m-d', strtotime("-1 hour", strtotime($timezonestime)));
        $dwdb = $this->load->database('dw', true);
        
        //do etl to dimention table
        $logdate = date('Y-m-d H:i:s', $timezonestimestamp);
        log_message("debug", "ETL RunDim at ".$logdate);
        //echo $logdate;
        $dwdb->query("call rundim()");
        
        //run fact
        $logdate = date('Y-m-d H:i:s', $timezonestimestamp);
        log_message("debug", "ETL runfact at $logdate and fromTime= $fromTime toTime= $toTime");
        $dwdb->query("call runfact('$fromTime','$toTime')");

        //run sum
        $logdate = date('Y-m-d H:i:s', $timezonestimestamp);
        log_message("debug", "ETL runsum at $logdate and rundate = $date");
        $dwdb->query("call runsum('$date')");
    }

    /**
     * archiveWeekly function
     * Schedule weekly task to do the statics,Caculate from sunday to sataday,Must scheduled at sunday.
     *
     * @return void
     */
    function archiveWeekly()
    {
        $dwdb = $this->load->database('dw', true);
        $timezonestimestamp = time();
        $timezonestime = date('Y-m-d', $timezonestimestamp);

        $lastSataday = date("Y-m-d", strtotime("-1 day", strtotime($timezonestime)));
        $lastSunday =  date("Y-m-d", strtotime("-7 day", strtotime($timezonestime)));
        //echo $lastSunday. " " . $lastSataday;
        $logdate = date('Y-m-d H:i:s', $timezonestimestamp);
        log_message("debug", "ETL runweekly at $logdate and fromDate = $lastSunday and toDate = $lastSataday");
        $dwdb->query("call runweekly('$lastSunday','$lastSataday')");
    }

    /**
     * archiveMonthly function
     * Schedule monthly task to do the statics
     *
     * @return void
     */
    function archiveMonthly()
    {
        $dwdb = $this->load->database('dw', true);
        
        $timezonestimestamp = time();
        $timezonestime = date('Y-m-d', $timezonestimestamp);
        
        $lastMonthStartDate = date("Y-m-1", strtotime("-1 month", strtotime($timezonestime)));
        $lastMonthEndDate = date("Y-m-t", strtotime("-1 month", strtotime($timezonestime)));
        //echo $lastMonthStartDate. " " .$lastMonthEndDate; 
        $logdate = date('Y-m-d H:i:s', $timezonestimestamp);
        log_message("debug", "ETL runmonthly at $logdate and fromTime = $lastMonthStartDate and toTime = $lastMonthEndDate");
        $dwdb->query("call runmonthly('$lastMonthStartDate','$lastMonthEndDate')");
    }

    /**
     * archiveLaterData function
     * Schedule task for later data, from last 7days to yestoday
     *
     * @return void
     */
    function archiveLaterData()
    {
        $timezonestimestamp = time();
        $timezonestime = date('Y-m-d H:i:m', $timezonestimestamp);
        $fromTime = date('Y-m-d', strtotime("-7 day", strtotime($timezonestime)));
        $toTime = date('Y-m-d', strtotime("-1 day", strtotime($timezonestime)));
        //echo $fromTime.' '.$toTime;
        //run sum
        $dwdb = $this->load->database('dw', true);
        $logdate = date('Y-m-d H:i:s', $timezonestimestamp);
        log_message("debug", "ETL runArchiveLater at $logdate and fromDate = $fromTime and toDate = $toTime");
        for ($i=1;$i<8;$i++) {
            $date = date('Y-m-d', strtotime("-$i day", strtotime($timezonestime)));
            //echo $date."<br>";
            log_message("debug", "ETL runArchiveLater run sunm at $date");
            $dwdb->query("call runsum('$date')");
        }
    }

    /**
     * archiveUsingLog function
     * Schedule daily task for using log page views, yestoday
     *
     * @return void
     */
    function archiveUsingLog()
    {
        $timezonestimestamp = time();
        $timezonestime = date('Y-m-d H:i:m', $timezonestimestamp);
        $date = date('Y-m-d', strtotime("-1 day", strtotime($timezonestime)));
        $date2 = date('Y-m-d', strtotime($timezonestime));
        //run archiveUsinglog
        $dwdb = $this->load->database('dw', true);
        $logdate = date('Y-m-d H:i:s', $timezonestimestamp);
        log_message("debug", "ETL archiveUsingLog at $logdate and date = $date");
        $dwdb->query("call rundaily('$date')");
        $this->archiveCompareValue($date);
    }

    /**
     * archiveCompareValue function
     * Schedule compare value
     *
     * @param int $date date
     *
     * @return void
     */
    function archiveCompareValue($date)
    {
        //$timezonestimestamp = time();
        //$timezonestime = date ( 'Y-m-d H:i:m', $timezonestimestamp );
        //$date = date('Y-m-d',strtotime("-1 day", strtotime($timezonestime)));
        $this->sendemail->comparevalue($date);
    }
}
