<?php
class Pageviewmodel extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->helper('date');
		$this->load->library('redis');
	}
	function getActivities($productId) {
		$timezonestimestamp = gmt_to_local(local_to_gmt(), $this->config->item('timezones'));
		$timezonestime = date('Y-m-d H:i:m', $timezonestimestamp);
		$allKeys = $this->redis->keys("razor_r_ac_p_" . $productId . "*");
		$ret = array();
		$allActivitiesArray = array();

		if ($allKeys && count($allKeys) > 0) {
			for ($i = 0; $i < count($allKeys); $i++) {
				$key = $allKeys[$i];
				$activities = $this->redis->hkeys($key);
				if ($activities && count($activities) > 0) {
					for ($j = 0; $j < count($activities); $j++) {
						$activityName = $activities[$j];
						if (isset($allActivitiesArray["$activityName"])) {
							$allActivitiesArray["$activityName"] = (int) $allActivitiesArray["$activityName"] + 1;
						} else {
							$allActivitiesArray["$activityName"] = 1;
						}
					}
				}
			}
		}

		$allChildrens = array();
		if ($allActivitiesArray && count($allActivitiesArray) > 0) {
			foreach ($allActivitiesArray as $key => $value) {
				$ac = array('name' => $key, 'size' => $value);
				array_push($allChildrens, $ac);
			}
		}
		$ret["total"] = count($allActivitiesArray);
		$ret["rows"] = $allChildrens;
		echo json_encode($ret);
	}

	function getActivityByMinutes($productId) {
		$timezonestimestamp = gmt_to_local(local_to_gmt(), $this->config->item('timezones'));
		$timezonestime = date('Y-m-d H:i:m', $timezonestimestamp);
		$all_ret = array();
		$version = "all";

		for ($i = 29; $i >= 0; $i--) {
			$all_size = 0;
			$dataStr = date('Y-m-d-H-i', strtotime("-$i minutes", strtotime($timezonestime)));
			$allAcs = $this->redis->keys("razor_r_ac_p_" . $productId . "_" . $dataStr . "*");
			if ($allAcs && count($allAcs) > 0) {
				for ($k = 0; $k < count($allAcs); $k++) {
					$key = $allAcs[$k];
					$len = $this->redis->hlen($key);
					$all_size += $len;
				}
			}

			if ($i == 0) {
				$onlinedata = array('minutes' => lang("v_rpt_realtime_now"), 'size' => $all_size);
			} else {
				$onlinedata = array('minutes' => "- " . $i . lang("v_rpt_realtime_minutes"), 'size' => $all_size);
			}
			array_push($all_ret, $onlinedata);
		}
		return json_encode($all_ret);
	}

}

?>