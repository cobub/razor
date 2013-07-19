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
		$this->load->model ( 'common' );
		$this->load->database ();
	}
	function getAllAlertlab(){
		$userId = $this->common->getUserId();
		$product = $this->common->getCurrentProduct();
	    $sql = "select * from ".$this->db->dbprefix('alert')."";
		$result = $this->db->query($sql);
		return $result->result_array();  
	}
	function addAlertEmail($alertlabel,$factdata,$forecastdata,$time,$states){
		
		$states=1;
		$sql="INSERT INTO ".$this->db->dbprefix('alertdetail')." (  `states` ,  `time` ,  `forecastdata` ,  `factdata` ,  `alertlabel` ) 
        VALUES ( $states,  '$time', $forecastdata, $factdata,  '$alertlabel' );";
		$this->db->query($sql);
	}
	
}