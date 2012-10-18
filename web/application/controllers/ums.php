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
class Ums extends CI_Controller {
	function Ums() {
		parent::__construct ();
		
		$isRedisEnabled = $this->config->item('redis');
		if($isRedisEnabled)
		{
			$servicePrefix = "redis_service";
		}
		else
		{
			$servicePrefix = "service";
		}
		$this->load->model ( $servicePrefix.'/utility', 'utility' );
		$this->load->model ( $servicePrefix.'/event', 'event' );
		$this->load->model ( $servicePrefix.'/userlog', 'userlog' );
		$this->load->model ( $servicePrefix.'/update', 'update' );
		$this->load->model ( $servicePrefix.'/clientdata', 'clientdata' );
		$this->load->model ( $servicePrefix.'/activitylog', 'activitylog' );
		$this->load->model ( $servicePrefix.'/onlineconfig', 'onlineconfig' );
		$this->load->model ( $servicePrefix.'/uploadlog', 'uploadlog' );
	}
	
	/*
	 * Interface to accept event log by client
	 */
	function postEvent() {
		if (! isset ( $_POST ["content"] )) {
			
			$ret = array (
					'flag' => - 3,
					'msg' => 'Invalid content.' 
			);
			echo json_encode ( $ret );
			return;
		}
		
		$encoded_content = $_POST ["content"];
		log_message ( "debug", $encoded_content );
		$content = json_decode ( $encoded_content );
		$retParamsCheck = $this->utility->isPraramerValue ( $content, $array = array (
				'appkey',
				'event_identifier',
				'time',
				'activity' 
		) );
		
		if ($retParamsCheck ['flag'] <= 0) {
			$ret = array (
					'flag' => - 2,
					'msg' => $retParamsCheck ['msg'] 
			);
			echo json_encode ( $ret );
			return;
		}
		$key = $content->appkey;
		$isKeyAvailable = $this->utility->isKeyAvailale ( $key );
		if (! $isKeyAvailable) {
			$ret = array (
					'flag' => - 1,
					'msg' => 'NotAvailable appkey ' 
			);
			echo json_encode ( $ret );
			return;
		} 
		else
		{
			$isgetEventid = $this->event->addEvent ( $content );
			if (!$isgetEventid) {
				$ret = array (
						'flag' => - 5,
						'msg' => 'event_identifier not defined in product with provided appkey' 
				);
				echo json_encode ( $ret );
				return;
			} 
			else
			{
				$ret = array (
						'flag' => 1,
						'msg' => 'ok' 
					);
			}
			echo json_encode ( $ret );
		}
	}
	
	/*
	 * Interface to accept error log by client
	 */
	function postErrorLog() {
		if (! isset ( $_POST ["content"] )) {
			$ret = array (
					'flag' => - 3,
					'msg' => 'Invalid content.' 
			);
			echo json_encode ( $ret );
			return;
		}
		$encoded_content = $_POST ["content"];
		$content = json_decode ( $encoded_content );
		log_message ( 'debug', $encoded_content );
		$retParamsCheck = $this->utility->isPraramerValue ( $content, $array = array (
				"appkey",
				"stacktrace",
				"time",
				"activity",
				"os_version",
				"deviceid" 
		) );
		if ($retParamsCheck ["flag"] <= 0) {
			$ret = array (
					'flag' => - 2,
					'msg' => $retParamsCheck ['msg'] 
			);
			echo json_encode ( $ret );
			return;
		}
		$key = $content->appkey;
		$isKeyAvailable = $this->utility->isKeyAvailale ( $key );
		if (! $isKeyAvailable) {
			$ret = array (
					'flag' => - 1,
					'msg' => 'NotAvailable appkey  ' 
			);
			echo json_encode ( $ret );
			return;
		} else {
			try {
				$this->userlog->addUserlog ( $content );
				$ret = array (
						'flag' => 1,
						'msg' => 'ok' 
				);
			} catch ( Exception $ex ) {
				$ret = array (
						'flag' => - 4,
						'msg' => 'DB Error' 
				);
			}
		}
		echo json_encode ( $ret );
	}
	
	/*
	 * Interface to accept client data
	 */
	function postClientData() {
		if (! isset ( $_POST ["content"] )) {
			$ret = array (
					'flag' => - 3,
					'msg' => 'Invalid content.' 
			);
			echo json_encode ( $ret );
			return;
		}
		$encoded_content = $_POST ["content"];
		$content = json_decode ( $encoded_content );
		$retParamsCheck = $this->utility->isPraramerValue ( $content, $array = array (
				"appkey",
				"platform",
				"os_version",
				"language",
				"deviceid",
				"resolution" 
		) );
		if ($retParamsCheck ["flag"] <= 0) {
			$ret = array (
					'flag' => - 2,
					'msg' => $retParamsCheck ['msg'] 
			);
			echo json_encode ( $ret );
			return;
		}
		$key = $content->appkey;
		$isKeyAvailable = $this->utility->isKeyAvailale ( $key );
		if (! $isKeyAvailable) {
			$ret = array (
					'flag' => - 1,
					'msg' => 'Invalid app key' 
			);
			echo json_encode ( $ret );
			return;
		} else {
			try {
				$this->clientdata->addClientdata ( $content );
				$ret = array (
						'flag' => 1,
						'msg' => 'ok' 
				);
			} catch ( Exception $ex ) {
				$ret = array (
						'flag' => - 4,
						'msg' => 'DB Error' 
				);
			}
		}
		log_message('debug',json_encode($ret));
		echo json_encode ( $ret );
	}
	
	/*
	 * Interface to accept Activity Log
	 */
	function postActivityLog() {
		if (! isset ( $_POST ["content"] )) {
			$ret = array (
					'flag' => - 3,
					'msg' => 'Invalid content.' 
			);
			echo json_encode ( $ret );
			return;
		}
		$encoded_content = $_POST ["content"];
		log_message ( "debug", $encoded_content );
		$content = json_decode ( $encoded_content );
		$retParamsCheck = $this->utility->isPraramerValue ( $content, $array = array (
				"appkey",
				"session_id",
				"start_millis",
				"end_millis",
				"duration",
				"activities" 
		) );
		if ($retParamsCheck ["flag"] <= 0) {
			$ret = array (
					'flag' => - 2,
					'msg' => $retParamsCheck ['msg'] 
			);
			echo json_encode ( $ret );
			return;
		}
		$key = $content->appkey;
		$isKeyAvailable = $this->utility->isKeyAvailale ( $key );
		if (! $isKeyAvailable) {
			$ret = array (
					'flag' => - 1,
					'msg' => 'NotAvailable appkey ' 
			);
			echo json_encode ( $ret );
			return;
		} else {
			try {
				$this->activitylog->addActivitylog ( $content );
				$ret = array (
						'flag' => 1,
						'msg' => 'ok' 
				);
			} catch ( Exception $ex ) {
				$ret = array (
						'flag' => - 4,
						'msg' => 'DB Error' 
				);
			}
		}
		echo json_encode ( $ret );
	}
	
	/*
	 * Interface to accept total log
	 */
	function uploadLog() {
		if (! isset ( $_POST ["content"] )) {
			$ret = array (
					'flag' => - 3,
					'msg' => 'Invalid content.' 
			);
			echo json_encode ( $ret );
			return;
		}
		$encoded_content = $_POST ['content'];
		log_message ( "debug", $encoded_content );
		$content = json_decode ( $encoded_content );
		$key = $content->appkey;
		$isKeyAvailable = $this->utility->isKeyAvailale ( $key );
		if (! $isKeyAvailable) {
			$ret = array (
					'flag' => - 1,
					'msg' => 'NotAvailable appkey  ' 
			);
			echo json_encode ( $ret );
			return;
		} else {
			try {
				$this->uploadlog->addUploadlog ( $content );
				$ret = array (
						'flag' => 1,
						'msg' => 'ok' 
				);
			} catch ( Exception $ex ) {
				$ret = array (
						'flag' => - 4,
						'msg' => 'DB Error' 
				);
			}
		}
		echo json_encode ( $ret );
	}
	
	function Gzip() {
		$data = $_POST ['content'];
		$this->utility->gzdecode ( $data );
	}
	
	/*
	 * Get Application Update by version no
	 */
	function getApplicationUpdate() {
		header ( "Content-Type:application/json" );
		if (! isset ( $_POST ["content"] )) {
			
			$ret = array (
					'flag' => - 3,
					'msg' => 'Invalid content.' 
			);
			echo json_encode ( $ret );
			return;
		}
		$encoded_content = $_POST ["content"];
		log_message ( "debug", $encoded_content );
		$content = json_decode ( $encoded_content );
		$retParamsCheck = $this->utility->isPraramerValue ( $content, $array = array (
				"appkey",
				"version_code" 
		) );
		if ($retParamsCheck ["flag"] <= 0) {
			$ret = array (
					'flag' => - 2,
					'msg' => $retParamsCheck ['msg'] 
			);
			echo json_encode ( $ret );
			return;
		}
		$key = $content->appkey;
		$version_code = $content->version_code;
		$isKeyAvailable = $this->utility->isKeyAvailale ( $key );
		if (! $isKeyAvailable) {
			$ret = array (
					'flag' => - 1,
					'msg' => 'NotAvailable appkey ' 
			);
			echo json_encode ( $ret );
			return;
		} else {
			$haveNewversion = $this->update->haveNewversion ( $key, $version_code );
			if (! $haveNewversion) {
				$ret = array (
						'flag' => - 7,
						'msg' => 'no new version' 
				);
				echo json_encode ( $ret );
				return;
			} else {
				try {
					$product = $this->update->getProductUpdate ( $key );
					if ($product != null) {
						$ret = array (
								'flag' => 1,
								'msg' => 'ok',
								'fileurl' => $product->updateurl,
								'forceupdate' => $product->man,
								'description' => $product->description,
								'time' => $product->date,
								'version' => $product->version 
						);
					}
				} catch ( Exception $ex ) {
					$ret = array (
							'flag' => - 4,
							'msg' => 'DB Error' 
					);
				}
			}
			echo json_encode ( $ret );
		}
	}
	/*
	 * Used to get Online Configuration
	 */
	function getOnlineConfiguration() {
		$encoded_content = $_POST ['content'];
		log_message ( 'debug', $encoded_content );
		$content = json_decode ( $encoded_content );
		$key = $content->appkey;
		log_message ( 'debug', $key );
		if (! isset ( $key )) {
			$ret = array (
					'flag' => - 2,
					'msg' => 'Invalid key.' 
			)
			;
			echo json_encode ( $ret );
			return;
		} else {
			$isKeyAvailable = $this->utility->isKeyAvailale ( $key );
			if (! $isKeyAvailable) {
				$ret = array (
						'flag' => - 1,
						'msg' => 'NotAvailable appkey ' 
				);
				echo json_encode ( $ret );
				return;
			} else {
				try {
					$productid = $this->onlineconfig->getProductid ( $key );
					$configmessage = $this->onlineconfig->getConfigMessage ( $productid );
					if ($configmessage != null) {
						$ret = array (
								'flag' => 1,
								'msg' => 'ok',
								'autogetlocation' => $configmessage->autogetlocation,
								'updateonlywifi' => $configmessage->updateonlywifi,
								'sessionmillis' => $configmessage->sessionmillis,
								'reportpolicy' => $configmessage->reportpolicy 
						);
					}
				} catch ( Exception $ex ) {
					$ret = array (
							'flag' => - 4,
							'msg' => 'DB Error' 
					);
				}
			}
			echo json_encode ( $ret );
		}
	}
}
