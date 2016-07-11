<?php
/*========================================
 umsTest
 Test case of ums controller
 ========================================*/

class postusinglogTest extends CIUnit_TestCase {
    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
    }

    public function setUp() {
        parent::setUp();
        $this -> CI = set_controller('ums');
        $this -> dbfixt('razor_channel_product');
    }
    
    public function tearDown() {
        parent::tearDown();
        $tables = array(
            'razor_channel_product'=>'razor_channel_product'
        );

        //$this->dbfixt_unload($tables);
    }
    public function testPostUsinglog() {
        $this->CI->rawdata = dirname(__FILE__) . '/testdata_usinglog/ok.json';
        ob_start();
        $this->CI->usinglog();
        $output = ob_get_clean();
        $this -> assertEquals('{"flag":1,"msg":"ok"}', $output);
    }

    public function testPostUsinglog1() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/empty.json';
        ob_start();
        $this->CI->usinglog();
        $output = ob_get_clean();
        $this -> assertEquals('{"flag":-3,"msg":"Invalid content from php:\/\/input."}', $output);
    }
    
    public function testPostUsinglog2() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/partly.json';
        ob_start();
        $this->CI->usinglog();
        $output = ob_get_clean();
        $this -> assertEquals('{"flag":-4,"msg":"Parse json data failed."}', $output);
    }
    
    public function testPostUsinglog3() {
        $this->CI->rawdata = dirname(__FILE__) . '/testdata_usinglog/noappkey.json';
        ob_start();
        $this->CI->usinglog();
        $output = ob_get_clean();
        $this -> assertEquals('{"flag":-5,"msg":"Appkey is not set in json."}', $output);
    }
    
    public function testPostUsinglog4() {
        $this->CI->rawdata = dirname(__FILE__) . '/testdata_usinglog/errorappkey.json';
        ob_start();
        $this->CI->usinglog();
        $output = ob_get_clean();
        $this -> assertEquals('{"flag":-1,"msg":"Invalid appkey:invalid_appkey_00000"}', $output);
    }

}
?>