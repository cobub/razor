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

class Processor extends CI_Model {
	
	function __construct() {
		$this->load->database ();
		$this->load->library('redis');
	}
	
	/*
	 * Process items in redis queue
	 */
	function process() 
	{
		$timestamp = $this->redis->get('razor_timestamp');
		if($timestamp!=null && $timestamp!="")
		{
			$now = time();
			$previousTimeStamp = strtotime($timestamp);
			$timeDiffer = $now - $previousTimeStamp;
			log_message('debug',"TimeStamp differ = ".$timeDiffer);
			$timeInterval = $this->config->item('redis_interval');
			if($timeDiffer>$timeInterval)
			{
				$this->processItems("razor_events","eventdata","productkey");
				$this->processItems("razor_errors","errorlog","appkey");
				$this->processItems("razor_clientdata","clientdata","productkey");
				$this->processItems("razor_clientusinglogs","clientusinglog","appkey");
			}
		}
		else
		{
			$timestamp = date ( 'Y-m-d H:i:s', time () );
			log_message('debug','No timestamp, so add one to redis'.$timestamp);
			$this->redis->set('razor_timestamp',$timestamp);
		}
	}
	
	/*
	 * Insert items to database from Redis Server
	 * $redisItemName, key value stored in Redis
	 * $tableName, table name of item
	 * $keyName, appkey column name. productkey or appkey
	 */
	function processItems($redisItemName,$tableName,$keyName) {
		log_message ( "debug", "Batch Process $tableName" );
		try {
			$size = $this->redis->llen ($redisItemName);
			log_message("debug","$tableName Size = $size");
			$itemsArray = array ();
			for($i = 0; $i < $size; $i ++) {
				$popdata = $this->redis->lpop ($redisItemName);
				if (isset ( $popdata )) {
					$postArray = unserialize ( $popdata );
					if (is_array ( $postArray ) && isset ( $postArray [$keyName] ) && $postArray [$keyName] != null && $postArray [$keyName] != '') {
						array_push ( $itemsArray, $postArray );
					}
				}
			}
			if ($itemsArray != null && count ( $itemsArray ) > 0) {
				log_message ( "debug", "Processs " . count ( $itemsArray ) . " $tableName" );

				$this->db->insert_batch ($tableName, $itemsArray );
			}
		} catch ( Exception $ex ) {
			log_message ( "error", "Batch Insert $tableName error === " . $ex );
		}
	}
}