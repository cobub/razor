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
 * Plugin Model
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class PluginM extends CI_Model
{
    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct ()
    {
        parent::__construct();
        $this->load->helper('file');
        $this->load->helper('path');
    }

    /**
     * traverse function
     * traverse
     *
     * @param string $path      path
     * @param string $returnarr return arr
     *
     * @return query returnarr
     */
    function traverse ($path, $returnarr)
    {
        $entity = array();
        $classname = array();
        
        $current_dir = opendir($path); // opendir()返回一个目录句柄,失败返回false
        while (($file = readdir($current_dir)) !== false) { // readdir()返回打开目录句柄中的一个条目
            
            $sub_dir = $path . $file; // 构建子目录路径
            
            if ($file == '.' || $file == '..' || $file == '.svn') {
                continue;
            } else {
                if (is_dir($sub_dir)) {
                    $returnarr = $this->traverse($sub_dir . "/", $returnarr);
                } else {
                    $index = strrpos($file, ".");
                    $filename = substr($file, 0, $index);
                    
                    $entity = array(
                            'classpath' => $path . $file,
                            'classname' => $filename
                    );
                    array_push($returnarr, $entity);
                }
            }
        }
        return $returnarr;
    }

    /**
     * run function
     * run
     *
     * @param string $functionname function name
     * @param string $par          par
     *
     * @return query pluginsArray
     */
    function run ($functionname, $par)
    {
        $dir = dirname(dirname(__FILE__)) . "/plugin/";
        $returnarr = array();
        $arr = $this->traverse($dir, $returnarr);
        $pluginsArray = array();
        for ($i = 0; $i < count($arr); $i ++) {
            $classpat = $arr[$i]['classpath'];
            require_once ($classpat);
            $classname = $arr[$i]['classname'];
            if ($classname == "pluginInterface") {
                continue;
            }
            $reflectionClass = new ReflectionClass($classname);
            if ($reflectionClass->implementsInterface('pluginInterface')) {
                try {
                    $cla = $reflectionClass->newInstance();
                } catch (Exception $e) {
                    continue;
                }
                
                if (method_exists($cla, $functionname)) {
                    $function = $reflectionClass->getmethod($functionname);
                    // $this->runfunction($classname,$classpat, "getName",
                    // $par);
                    $str = $function->invoke($cla, $par);
                    array_push($pluginsArray, $str);
                }
            }
        }
        return $pluginsArray;
    }
    
    // function runfunction($classname,$classpat,$functionname,$par){
    
    // require_once ($classpat);
    // $reflectionClass = new ReflectionClass($classname);
    // try {
    // $cla= $reflectionClass->newInstance();
    // } catch (Exception $e) {
    // continue;
    // }
    
    // if(method_exists($cla, $functionname)){
    // $function= $reflectionClass->getmethod($functionname);
    // $str=$function->invoke($cla,$par);
    // echo $str."<br>";
    // }
    
    // }
    
    // function get_ini_file($file_name = "demo.ini"){
    // $str=file_get_contents($file_name);
    // $ini_list = explode("\r\n",$str);
    // $ini_items = array();
    // foreach($ini_list as $item){
    // $one_item = explode("=",$item);
    // if(isset($one_item[0])&&isset($one_item[1]))
    // $ini_items[trim($one_item[0])] = trim($one_item[1]); //���key=>value����ʽ.
    // }
    // return $ini_items;
    // }
    
    // function get_ini_item($ini_items = null,$item_name = ''){
    // if(empty($ini_items)) return "";
    // else return $ini_items[$item_name];
    // }
}

?>
