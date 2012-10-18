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
class Uploadlog extends CI_Model
{
	function Activitylog()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('service/event','event');
		$this->load->model('service/userlog','userlog');
		$this->load->model('service/clientdata','clientdata');
		$this->load->model('service/activitylog','activitylog');
		$this->load->model('service/utility','utility');	
	}	
	
	function addUploadlog($content)
	{
//		$eventInfo = $content->eventInfo;
		$eventInfo=isset($content->eventInfo)?$content->eventInfo:"";
		if(isset($eventInfo))
		{
			if(is_array($eventInfo))
			{
				foreach ($eventInfo as $event)
				{
					$this->event->addEvent($event);
				}
			}
		}
		$errorInfo =isset($content->errorInfo)?$content->errorInfo:"";
		if(isset($errorInfo))
		{
			if(is_array($errorInfo))
			{
				foreach($errorInfo as $errorlog)
				{
					$this->userlog->addUserlog($errorlog);
				}
			}
		}
		$clientData = isset($content->clientData)?$content->clientData:"";
		if(isset($clientData))
		{
			if(is_array($clientData))
			{
				foreach($clientData as $clientdataInfo)
				{
					$this->clientdata->addClientdata($clientdataInfo);
				}
			}
		}
		$activityInfo = isset($content->activityInfo)?$content->activityInfo:"";
		if(isset($activityInfo))
		{
			if(is_array($activityInfo))
			{
				foreach ($activityInfo as $erroractivity) {
					$this->activitylog->addActivitylog($erroractivity);
				}
			}
		}
		
	}	
	
}
?>