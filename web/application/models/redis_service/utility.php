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
 * Utility Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Utility extends CI_Model
{



    /** 
     * Utility load 
     * Utility function 
     * 
     * @return void 
     */
    function Utility()
    {
        parent::__construct();
        $this -> load -> database();
        $this -> load -> library('redis');
        $this -> load -> helper('array');
    }
    
    /** 
     * Check if the app key is exist 
     * IsKeyAvailale function 
     * 
     * @param string $key key 
     * 
     * @return bool 
     */
    function isKeyAvailale($key)
    {
    	////check
		$key = addslashes($key);
		
        $isKeyAvailable = $this -> redis -> hget("razor_appkeys_hash", $key);
        if ($isKeyAvailable != null && $isKeyAvailable != "") {
            log_message('debug', "key already in redis");
            return true;
        }

        $query = $this -> db -> query("select * from " . $this -> db -> dbprefix('channel_product') . " where productkey = '$key'");
        if ($query != null && $query -> num_rows() > 0) {
            $this -> redis -> hset('razor_appkeys_hash', array("$key" => $query -> first_row() -> product_id));
            return true;
        }
        return false;
    }
    
    /** 
     * Get product id by product key 
     * GetProductIdByKey function 
     * 
     * @param string $key key 
     * 
     * @return bool 
     */
    function getProductIdByKey($key)
    {
    	////check
		$key = addslashes($key);
    	
        $isKeyAvailable = $this -> redis -> hget("razor_appkeys_hash", $key);
        if ($isKeyAvailable != null && $isKeyAvailable != "") {
            log_message('debug', "key already in redis");
            return $isKeyAvailable;
        }

        $query = $this -> db -> query("select * from " . $this -> db -> dbprefix('channel_product') . " where productkey = '$key'");
        if ($query != null && $query -> num_rows() > 0) {
            $this -> redis -> hset('razor_appkeys_hash', array("$key" => $query -> first_row() -> product_id));
            return $query -> first_row() -> product_id;
        }
        return false;
    }
    
    /** 
     * Verify parameter 
     * IsPraramerValue function 
     * 
     * @param string $content content 
     * @param string $array   array 
     * 
     * @return array 
     */
    function isPraramerValue($content, $array)
    {
        for ($i = 0; $i < count($array); $i++) {
            if (!isset($content -> $array[$i])) {
                $ret = array('flag' => -2, 'msg' => 'Invalid parameter ' . $array[$i]);
                return $ret;
            }
        }
        $ret = array('flag' => 1, 'msg' => 'Pass check');
        return $ret;
    }
    
    /** 
     * Post 
     * Post2 function 
     * 
     * @param string $serverURL serverURL 
     * 
     * @return urldecode 
     */
    function Post2($serverURL)
    {
        $url = parse_url($serverURL);
        if (!$url)
            return "couldn’t parse url";
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
    
    /** 
     * Post 
     * Post function 
     * 
     * @param string $serverURL serverurl 
     * @param string $data      data 
     * 
     * @return urldecode 
     */
    function Post($serverURL, $data)
    {
        // $serverURL = "http://dev.myllec.cn/jsonservice/index.php/".$url;
        // $serverURL = "http://127.0.0.1/jsonservice/index.php/".$url;
        $url = parse_url($serverURL);
        if (!$url)
            return "couldn’t parse url";
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
        // echo urldecode(iconv('UTF-8', "GB2312",$results));
    }
    
    /** 
     * Get online ip 
     * GetOnlineIP function 
     * 
     * @param int $format format 
     * 
     * @return string 
     */
    function getOnlineIP($format = 0)
    {
        $result = '';
        $onlineip = 'unknown';
        // if (empty ( $_SGLOBAL ['onlineip'] )) {
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
        // }
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
    
    /** 
     * Get region info 
     * Getregioninfo function 
     * 
     * @param string $latitude  latitude 
     * @param string $longitude longitude 
     * @param string $id        id 
     * 
     * @return void 
     */
    function getregioninfo($latitude, $longitude, $id)
    {
        $apkey = "0097da75bc8864672f8dedca5c5c590bdb0d2653";
        $language = "zh-CN";
        $params = array('latitude' => $latitude, 'longitude' => $longitude, 'key' => $apkey, 'language' => $language);
        $arrVal["content"] = json_encode($params);
        $client = $this -> utility -> Post('http://lbs.cobub.com/index.php?jsonservice/GetAddressByCord/', $arrVal);
        // $arr =
        // $client->__soapCall('GetAddressByCord',array('parameters'=>$params));
        // echo $client;
        if (!isset($client)) {
            $ret = array('flag' => -8, 'msg' => 'Invalid regioninfo');
            echo json_encode($ret);
            return;
        } else {
            $arr = json_decode($client);
            $this -> clientdata -> addRegion($arr, $id);
        }
    }
    
    /** 
     * Have region info by ip 
     * Haveregioninfobyip function 
     * 
     * @param string $ip ip 
     * @param string $id id 
     * 
     * @return void 
     */
    function haveregioninfobyip($ip, $id)
    {
        $apkey = "0097da75bc8864672f8dedca5c5c590bdb0d2653";
        $language = "zh-CN";
        $params = array('ip' => $ip, 'key' => $apkey, 'language' => $language);
        $arrVal["content"] = json_encode($params);
        $client = $this -> utility -> Post('http://lbs.cobub.com/index.php?jsonservice/GetAddressByIP/', $arrVal);
        if (!isset($client)) {
            $ret = array('flag' => -8, 'msg' => 'Invalid regioninfo');
            echo json_encode($ret);
            return;
        } else {
            $arr = json_decode($client);
            $flag = $arr -> flag;
            if ($flag == 0) {
                $ret = array('flag' => -9, 'msg' => 'Invalid IP');
                return;
            } else {
                $this -> clientdata -> addRegion($arr, $id);
            }
        }
    }

}
?>
