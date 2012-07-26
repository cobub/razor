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
class Clientdata extends CI_Model
{
	function Clientdata()
	{
		parent::__construct();
		$this->load->model('utility');
		$this->load->database();
		$this->load->helper("date");
	}	
	
	function addClientdata($clientdata)
	{  
		$ip=$this->utility->getOnlineIP();
//		date_default_timezone_set("Asia/Shanghai");
//        $showtime=date("Y-m-d H:i:s");
        $i=isset($clientdata->mccmnc)?$clientdata->mccmnc:''; 
        $query = $this->db->query("select name from ".$this->db->dbprefix('mccmnc')." where value = '$i'"); 
	    if($query!=null&& $query->num_rows()>0)
		{
			$service_supplier= $query->first_row()->name;			
		}
//        if ($i==46000||$i==46002)
//         {
//         		$service_supplier="中国移动";
//         }
//         elseif ($i==46001)
//         {
//         		$service_supplier="中国联通";
//         }
//         elseif ($i==46003) 
//         {
//            	$service_supplier="中国电信";
//         }     	
         else 
         {
         	$service_supplier="其他";
         }  
//         $networktype=isset($clientdata->network)?$clientdata->network:'';
//         $query = $this->db->query("select id from networktype where type = '$networktype'");
//         if($query!=null&& $query->num_rows()>0)
// 		{
// 			$networkid=$query->first_row()->id;			
// 		}
// 		else 
// 		{
// 			$networkid=18;
// 		}		                      	 
		$data = array(
			'productkey' => $clientdata->appkey,
			'platform'=> $clientdata->platform,
			'osversion'=> $clientdata->os_version,
			'language' => $clientdata->language,
			'deviceid' => $clientdata->deviceid,
			'resolution'=>$clientdata->resolution,
			'ismobiledevice' => isset($clientdata->ismobiledevice)?$clientdata->ismobiledevice:'',
			'devicename'=> isset($clientdata->devicename)?$clientdata->devicename:'',
			'defaultbrowser'=> isset($clientdata->defaultbrowser)?$clientdata->defaultbrowser:'',
			'javasupport' => isset($clientdata->javasupport)?$clientdata->javasupport:'',
			'flashversion' => isset($clientdata->flashversion)?$clientdata->flashversion:'',
			'modulename'=>isset($clientdata->modulename)?$clientdata->modulename:'',
			'imei' => isset($clientdata->imei)?$clientdata->imei:'',
			'imsi'=> isset($clientdata->imsi)?$clientdata->imsi:'',
			'havegps' => isset($clientdata->havegps)?$clientdata->havegps:'',
			'havebt' => isset($clientdata->havebt)?$clientdata->havebt:'',
			'havewifi'=>isset($clientdata->havewifi)?$clientdata->havewifi:'',
			'havegravity'=>isset($clientdata->havegravity)?$clientdata->havegravity:'',
			'wifimac'=>isset($clientdata->wifimac)?$clientdata->wifimac:'',
			'version'=>isset($clientdata->version)?$clientdata->version:'',
			'network'=>isset($clientdata->network)?$clientdata->network:'',
//			'cellid'=>$clientdata->CellID,
//			'mccmnc'=>$clientdata->MCCMNC,
//			'lac'=>$clientdata->LAC,
			'latitude' =>isset($clientdata ->latitude)?$clientdata ->latitude:'',
			'longitude'=>isset($clientdata->longitude)?$clientdata->longitude:'',
		    'isjailbroken'=>isset($clientdata->isjailbroken)?$clientdata->isjailbroken:'',
//			'wifi_towers'=>$clientdata->wifi_towers
//            'date'=>$showtime,
			'date'=>isset($clientdata->time)?$clientdata->time:'',
		    'service_supplier'=>$service_supplier,
		    'clientip'=>$ip
		);
		$this->db->insert('clientdata',$data);
		$clientdataid = $this->db->insert_id();
		if(!empty($clientdataid)){
			return $clientdataid;
		}else{
			return false;
		}
			
	}
	function addCell_towers($content,$id){
		 $i=isset($content->mccmnc)?$content->mccmnc:'';
         $mcccode=substr($i, 0, 3);// returns mcc
         $mnccode=substr($i, 3, 2);// returns mcc        
		$data = array(
			'clientdataid' => $id,
			'cellid'=>isset($content->cellid)?$content->cellid:'',		    
			'mcc'=>$mcccode,
		    'mnc'=>$mnccode,
			'lac'=>isset($content->lac)?$content->lac:''
			);			
			$this->db->insert('cell_towers',$data);
		}
	function addWifi_towers($content,$id)
	{
		$data = array(
			'clientdataid' => $id,
			'mac_address' => isset($content->mac_address)?$content->mac_address:'',
			'signal_strength' => isset($content->signal_strength)?$content->signal_strength:'',
			'age' => isset($content->age)?$content->age:''
		);
			$this->db->insert('wifi_towers',$data);
	}

	function addRegion($content,$id){
		$data = array(
			'id'=>$id,
			'country'=>$content->country,
			'region'=>$content->region,
			'city'=>$content->city,
			'street'=>$content->street,
			'streetno'=>$content->street_number,
			'postcode'=>$content->postal_code
		);	
			$this->db->where('id',$id);
			$this->db->update('clientdata',$data);
	}
	
}
?>