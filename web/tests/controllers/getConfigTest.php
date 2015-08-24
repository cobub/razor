<?php
/*========================================
 umsTest
 Test case of ums controller
 ========================================*/

class getConfigTest extends CIUnit_TestCase {
    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
    }

    public function setUp() {
        parent::setUp();
        $this -> CI = set_controller('ums');
        $this -> dbfixt('razor_channel_product');
        $this -> dbfixt('razor_config');
    }
    
    public function tearDown() {
        parent::tearDown();
        $tables = array(
            'razor_channel_product'=>'razor_channel_product',
            'razor_config'=>'razor_config'
        );

        $this->dbfixt_unload($tables);
    }
    public function testGetConfig() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/config_ok.json';
        ob_start();
        $this->CI->getOnlineConfiguration();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":1,"msg":"ok","autogetlocation":"1","updateonlywifi":"1","sessionmillis":"3000","reportpolicy":"1"}', 
            $output
        );
    }

    public function testGetConfig1() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/empty.json';
        ob_start();
        $this->CI->getOnlineConfiguration();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-3,"msg":"Invalid content from php:\/\/input."}', 
            $output
        );
    }
    
    public function testGetConfig2() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/partly.json';
        ob_start();
        $this->CI->getOnlineConfiguration();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-4,"msg":"Parse jsondata failed. Error No. is 4"}', 
            $output
        );
    }
    
    public function testGetConfig3() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/noappkey.json';
        ob_start();
        $this->CI->getOnlineConfiguration();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-5,"msg":"Appkey is not set in json."}', 
            $output
        );
    }
    
    public function testGetConfig4() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/invalidappkey.json';
        ob_start();
        $this->CI->getOnlineConfiguration();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-1,"msg":"Invalid app key:invalid_appkey_00000"}', 
            $output
        );
    }
}
?>