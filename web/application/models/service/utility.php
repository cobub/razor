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
class Utility extends CI_Model {
	function Utility() {
		parent::__construct();
		$this -> load -> database();
		$this -> load -> helper('array');
	}

	function isKeyAvailale($key) {
		$query = $this -> db -> query("select * from " . $this -> db -> dbprefix('channel_product') . " where productkey = '$key'");
		if ($query != null && $query -> num_rows() > 0) {
			return true;
		}
		return false;
	}

	function isPraramerValue($content, $array) {
		for ($i = 0; $i < count($array); $i++) {
			if (!isset($content -> $array[$i])) {
				$ret = array('flag' => -2, 'msg' => 'Invalid parameter ' . $array[$i]);
				return $ret;
			}
		}
		$ret = array('flag' => 1, 'msg' => 'Pass check');
		return $ret;
	}

	function Post2($serverURL) {
		$url = parse_url($serverURL);
		if (!$url)
			return "couldn't parse url";
		if (!isset($url['port'])) {
			$url['port'] = "";
		}
		if (!isset($url['query'])) {
			$url['query'] = "";
		}

		$errorno = "";
		$errorstr = "";
		$fp = fsockopen($url['host'], $url['port'] ? $url['port'] : 80, $errorno, $errorstr, 5);
		if (!$fp)
			return "Failed to open socket to $url[host]";

		fputs($fp, sprintf("POST %s%s%s HTTP/1.0\n", $url['path'], $url['query'] ? "?" : "", $url['query']));
		fputs($fp, "Host: $url[host]\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
		fputs($fp, "Accept-Language:zh-cn\n");
		fputs($fp, "Content-length: " . 0 . "\n");
		fputs($fp, "Connection: close\n\n");

		$line = fgets($fp, 1024);

		if (!preg_match("/^HTTP\/1\\.. 200/", $line)) {
			return;
		}

		$results = "";
		$inheader = 1;
		while (!feof($fp)) {
			$line = fgets($fp, 1024);
			if ($inheader && ($line == "\n" || $line == "\r\n")) {
				$inheader = 0;
			} elseif (!$inheader) {
				$results .= $line;
			}
		}
		fclose($fp);
		return urldecode($results);
	}

	function Post($serverURL, $data) {
		$url = parse_url($serverURL);
		if (!$url)
			return "couldn't parse url";
		if (!isset($url['port'])) {
			$url['port'] = "";
		}
		if (!isset($url['query'])) {
			$url['query'] = "";
		}

		$encoded = "";

		while (list($k, $v) = each($data)) {
			$encoded .= ($encoded ? "&" : "");
			$encoded .= rawurlencode($k) . "=" . rawurlencode($v);
		}
		$errorno = "";
		$errorstr = "";
		$fp = fsockopen($url['host'], $url['port'] ? $url['port'] : 80, $errorno, $errorstr, 5);
		if (!$fp)
			return "Failed to open socket to $url[host]";

		fputs($fp, sprintf("POST %s%s%s HTTP/1.0\n", $url['path'], $url['query'] ? "?" : "", $url['query']));
		fputs($fp, "Host: $url[host]\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
		fputs($fp, "Content-length: " . strlen($encoded) . "\n");
		fputs($fp, "Connection: close\n\n");

		fputs($fp, "$encoded\n");

		$line = fgets($fp, 1024);

		if (!preg_match("/^HTTP\/1\\.. 200/", $line))
			return;

		$results = "";
		$inheader = 1;
		while (!feof($fp)) {
			$line = fgets($fp, 1024);
			if ($inheader && ($line == "\n" || $line == "\r\n")) {
				$inheader = 0;
			} elseif (!$inheader) {
				$results .= $line;
			}
		}
		fclose($fp);
		return urldecode($results);
		//echo urldecode(iconv('UTF-8', "GB2312",$results));
	}

	function getOnlineIP($format = 0) {
		$result = '';

		//if (empty ( $_SGLOBAL ['onlineip'] )) {
		if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
			$onlineip = getenv('HTTP_CLIENT_IP');
		} elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
			$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
			$onlineip = getenv('REMOTE_ADDR');
		} elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
			$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		preg_match('/[\d\.]{7,15}/', $onlineip, $onlineipmatches);
		$result = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
		//}
		if ($format) {
			$ips = explode('.', $result);
			for ($i = 0; $i < 3; $i++) {
				$ips[$i] = intval($ips[$i]);
			}
			return sprintf('%03d%03d%03d', $ips[0], $ips[1], $ips[2]);
		} else {
			return $result;
		}
	}
}
?>