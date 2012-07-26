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
 
class Ums extends CI_Controller
{
	function Ums()
	{
		parent::__construct();
		$this->load->model('service/utility','utility');
		$this->load->model('service/event','event');
		$this->load->model('service/userlog','userlog');
		$this->load->model('service/update','update');
		$this->load->model('service/clientdata','clientdata');
		$this->load->model('service/activitylog','activitylog');
		$this->load->model('service/onlineconfig','onlineconfig');
		$this->load->model('service/uploadlog','uploadlog');
	}
	
  	function postEvent()
  	{ 
  		if (!isset($_POST["content"]))
        {
        	
        	$ret = array(
				'flag'=>-3,
				'msg'=>'Invalid content.'				
			);
			echo json_encode($ret);
			return;
        }       
        $encoded_content = $_POST["content"];
        log_message("debug",$encoded_content);
  		$content = json_decode($encoded_content);  	  	
        $retParamsCheck = $this->utility->isPraramerValue($content,$array=array('appkey','event_identifier','time','activity'));      
       if($retParamsCheck['flag']<=0)
       {
      	$ret = array(
      		'flag'=>-2,
      		'msg'=> $retParamsCheck['msg']
      	);
      	echo json_encode($ret);
      	return ;     	
       }	
       $key=$content->appkey;
	   $isKeyAvailable = $this->utility->isKeyAvailale($key);
	   if(!$isKeyAvailable)
	   {
					$ret = array(
					'flag'=>-1,
					'msg'=>'NotAvailable appkey '
				);
				echo  json_encode($ret);
				return;
		}		
		else
		{
//		$product_id=$this->event->getProductid($key);
//		$event_identifier=$content->event_identifier;
//        $getEventid = $this->event->isEventidAvailale($product_id,$event_identifier);
        $isgetEventid=$this->event->addEvent($content);
		 if ($isgetEventid==NULL )
		  {
               $ret = array(
					'flag'=>-5,
					'msg'=>'event_identifier not defined in product with provided appkey'
				);
				echo json_encode($ret);
				return;
		  }	   
	      else
			   {
				  try 
				  {
//					$this->event->addEvent($content);
					$ret = array(
						'flag'=>1,
						'msg'=>'ok'
					);
				  }
				  catch (Exception $ex)
				  {
						$ret = array(
						'flag'=>-4,
						'msg'=>'DB Error'
					);
				  }
			  }
  	  echo json_encode($ret);
// 	}
  }
}
	function postErrorLog()
	{	        	
		if (!isset($_POST["content"]))
        {        	
        	 $ret = array(
				  'flag'=>-3,
				  'msg'=>'Invalid content.'				
			);
			 echo json_encode($ret);
			 return;
           }   
		$encoded_content = $_POST["content"];
	        //$encoded_content = "{'time':'2','os_version':'2.3.4'}";
	  		$content = json_decode($encoded_content);
	  		log_message('debug',$encoded_content);    	
      $retParamsCheck = $this->utility->isPraramerValue($content,$array=array("appkey","stacktrace","time","activity","os_version","deviceid"));
      if($retParamsCheck["flag"]<=0)
      {
      	$ret = array(
      		'flag'=>-2,
      		'msg'=> $retParamsCheck['msg']
      	);
      	echo json_encode($ret);
      	return ;
      }	
       $key=$content->appkey;
	   $isKeyAvailable = $this->utility->isKeyAvailale($key);
				if(!$isKeyAvailable)
				{
						$ret = array(
						'flag'=>-1,
						'msg'=>'NotAvailable appkey  '
					);
					echo json_encode($ret);
					return;
				}
				else
				{
					try 
					{
						$this->userlog->addUserlog($content);
						$ret = array(
							'flag'=>1,
							'msg'=>'ok'
						);
					}
					catch (Exception $ex)
					{
							$ret = array(
							'flag'=>-4,
							'msg'=>'DB Error'
						);
					}
				}
	  		echo json_encode($ret);
	  	}
	  	
    function postClientData()
	{	        
		    if (!isset($_POST["content"]))
            {        	
        	  $ret = array(
				  'flag'=>-3,
				  'msg'=>'Invalid content.'				
			);
			 echo json_encode($ret);
			 return;
           }
           $encoded_content = $_POST["content"];
	  		$content = json_decode($encoded_content);
           $retParamsCheck = $this->utility->isPraramerValue($content,$array=array("appkey","platform","os_version","language","deviceid","resolution"));
           if($retParamsCheck["flag"]<=0)
           {
      	     $ret = array(
      		     'flag'=>-2,
      		     'msg'=> $retParamsCheck['msg']
      	    );
         	echo json_encode($ret);
      	    return ;
      		}	
            $key=$content->appkey;
            $isKeyAvailable = $this->utility->isKeyAvailale($key);
				if(!$isKeyAvailable)
				{
						$ret = array(
						'flag'=>-1,
						'msg'=>'NotAvailable appkey '
					);
					echo json_encode($ret);
					return;
				}
				else
				{
				try 
					{
						$ip=$this->utility->getOnlineIP();
//						$ip="121.229.189.28";
						$id= $this->clientdata->addClientdata($content);
												
						$this->clientdata->addCell_towers($content,$id);
						$this->clientdata->addWifi_towers($content,$id);
//						if (!isset($content->latitude)||!isset($content->longitude))
                        $latitude =isset($content ->latitude)?$content ->latitude:'';
                        if ($latitude!='')
						{
							$latitude=$content->latitude;
							$longitude=$content->longitude;
						    $this->utility->getregioninfo(
							    $latitude,
							    $longitude,
							    $id
							);
						}
						else 
						{
							$this->utility->haveregioninfobyip($ip,$id);
						}	
						$ret = array(
							'flag'=>1,
							'msg'=>'ok'
						);
					}
					catch (Exception $ex)
					{
							$ret = array(
							'flag'=>-4,
							'msg'=>'DB Error'
						);
					}
				
				}
	  		echo json_encode($ret);
	  	}
	function postActivityLog()
	{
		    if (!isset($_POST["content"]))
            {        	
        	  $ret = array(
				  'flag'=>-3,
				  'msg'=>'Invalid content.'				
			);
			 echo json_encode($ret);
			 return;
           }		
	        $encoded_content = $_POST["content"];
	        log_message("debug",$encoded_content);
	  		$content = json_decode($encoded_content);
           $retParamsCheck = $this->utility->isPraramerValue($content,$array=array("appkey","session_id","start_millis","end_millis","duration","activities"));
           if($retParamsCheck["flag"]<=0)
           {
      	     $ret = array(
      		     'flag'=>-2,
      		     'msg'=> $retParamsCheck['msg']
      	    );
         	echo json_encode($ret);
      	    return ;
      		}	
            $key=$content->appkey;
			$isKeyAvailable = $this->utility->isKeyAvailale($key);
				if(!$isKeyAvailable)
				{
						$ret = array(
						'flag'=>-1,
						'msg'=>'NotAvailable appkey '
					);
					echo json_encode($ret);
					return;
				}
				else
				{
					try 
					{
						$this->activitylog->addActivitylog($content);
						$ret = array(
							'flag'=>1,
							'msg'=>'ok'
						);
					}
					catch (Exception $ex)
					{
							$ret = array(
							'flag'=>-4,
							'msg'=>'DB Error'
						);
					}
				}
	  		echo json_encode($ret);
	  	}
	function uploadLog()
	{
	        if (!isset($_POST["content"]))
            {        	
        	  $ret = array(
				  'flag'=>-3,
				  'msg'=>'Invalid content.'				
			);
			 echo json_encode($ret);
			 return;
           }
	        $encoded_content = $_POST['content'];
	        log_message("debug",$encoded_content);
	  		$content = json_decode($encoded_content);
			$key = $content->appkey;
			$isKeyAvailable = $this->utility->isKeyAvailale($key);
				if(!$isKeyAvailable)
				{
						$ret = array(
						'flag'=>-1,
						'msg'=>'NotAvailable appkey  '
					);
					echo json_encode($ret);
					return;
				}
				else
				{
					try 
					{
						$this->uploadlog->addUploadlog($content);
						$ret = array(
							'flag'=>1,
							'msg'=>'ok'
						);
					}
					catch (Exception $ex)
					{
							$ret = array(
							'flag'=>-4,
							'msg'=>'DB Error'
						);
					}
				}
	  		echo json_encode($ret);
	  	}

   function Gzip(){
	  	$data = $_POST['content'];
	  	$this->utility->gzdecode($data);
	  	}
  	
  	function getApplicationUpdate()
  	{
  		header("Content-Type:application/json");
  		if (!isset($_POST["content"]))
        {
        	
        	$ret = array(
				'flag'=>-3,
				'msg'=>'Invalid content.'				
			);
			echo json_encode($ret);
			return;
        } 
        $encoded_content = $_POST["content"];
        log_message("debug",$encoded_content);
  		$content = json_decode($encoded_content);
  		$retParamsCheck = $this->utility->isPraramerValue($content,$array=array("appkey","version_code"));
           if($retParamsCheck["flag"]<=0)
           {
      	     $ret = array(
      		     'flag'=>-2,
      		     'msg'=> $retParamsCheck['msg']
      	    );
         	echo json_encode($ret);
      	    return ;
      		}	
            $key=$content->appkey;
		    $version_code=$content->version_code;
			$isKeyAvailable = $this->utility->isKeyAvailale($key);
			if(!$isKeyAvailable)
			{
					$ret = array(
					'flag'=>-1,
					'msg'=>'NotAvailable appkey '
				);
				echo json_encode($ret);
				return;
			}
			else
			{
				$haveNewversion = $this->update->haveNewversion($key,$version_code);
				if (!$haveNewversion)
			    {
					$ret = array(
					'flag'=>-7,
					'msg'=>'no new version'
				);
				echo json_encode($ret);
				return;
			   }
			   else 
			   {
				try 
				{
					$product = $this->update->getProductUpdate($key);			
					if($product!=null)
					{
						$ret = array(
						'flag'=>1,
						'msg'=>'ok',
						'fileurl'=>$product->updateurl,
					    'forceupdate'=>$product->man,
					    'description'=>$product->description,
					    'time'=>$product->date,
					    'version'=> $product->version					
						);
					}					
					
				}
				catch (Exception $ex)
				{
						$ret = array(
						'flag'=>-4,
						'msg'=>'DB Error'
					);
				}
			}	
  		echo json_encode($ret);
  	}
  }
  	
    function getOnlineConfiguration()
    {
        $encoded_content = $_POST['content'];       
        log_message('debug',$encoded_content);
  		$content = json_decode($encoded_content);
		$key = $content->appkey;
		log_message('debug',$key);
		if(!isset($key))
		{
			$ret = array(
				'flag'=>-2,
				'msg'=>'Invalid key.'
				
			);
			echo json_encode($ret);
			return;
		}
		else
		{
			$isKeyAvailable = $this->utility->isKeyAvailale($key);
			if(!$isKeyAvailable)
			{
					$ret = array(
					'flag'=>-1,
					'msg'=>'NotAvailable appkey '
				);
				echo json_encode($ret);
				return;
			}
			else
			{
				try 
				{
					$productid = $this->onlineconfig->getProductid($key);
					$configmessage = $this->onlineconfig->getConfigMessage($productid);
					if ($configmessage!=null)
					{
					    $ret = array(
						'flag'=>1,
						'msg'=>'ok',
						'autogetlocation'=>$configmessage->autogetlocation,
					    'updateonlywifi'=>$configmessage->updateonlywifi,
					    'sessionmillis'=>$configmessage->sessionmillis,
					    'reportpolicy'=>$configmessage->reportpolicy
					);					
				  }	
				}			
			catch (Exception $ex)
				{
						$ret = array(
						'flag'=>-4,
						'msg'=>'DB Error'
					);
				}
			}
  		echo json_encode($ret);
  	  }
  } 	

}
