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
class Compare extends CI_Model {
	function __construct() {
		$this->load->Model ( 'common' );
		$this->load->database ();
	}
	function getAllAlertlab(){
		$userId = $this->common->getUserId();
		$product = $this->common->getCurrentProduct();
// 		$sql = "select label,`condition`  from ".$this->db->dbprefix('alert')." where userid='".$userId."' and productid=".$product->id."";
		$sql = "select *  from razor_alert where userid='15' and productid=1";
// 		echo $sql;
		$result = $this->db->query($sql);
// 		print_r($result->result_array());
		  return $result->result_array();  
	}
	function addAlertEmail($alertlabel,$factdata,$forecastdata,$time,$states){
		
		$states=1;
		$sql="INSERT INTO ".$this->db->dbprefix('alertdetail')." (  `states` ,  `time` ,  `forecastdata` ,  `factdata` ,  `alertlabel` ) 
VALUES ( $states,  '$time', $forecastdata, $factdata,  '$alertlabel' );";
		$this->db->query($sql);
	}
	
}