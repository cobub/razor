<?php
/*========================================
 umsTest
 Test case of ums controller
 ========================================*/

class posteventTest extends CIUnit_TestCase {
    public function __construct($name = NULL, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);
    }

    public function setUp() {
        parent::setUp();
        $this -> CI = set_controller('ums');
        $this -> dbfixt('razor_channel_product');
        $this -> dbfixt('razor_event_defination');
    }
    
    public function tearDown() {
        parent::tearDown();
        $tables = array(
            'razor_channel_product'=>'razor_channel_product',
            'razor_event_defination'=>'razor_event_defination'
        );

        $this->dbfixt_unload($tables);
    }
    public function testPostEvent() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/event_ok.json';
        ob_start();
        $this->CI->postEvent();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":1,"msg":"ok"}', 
            $output
        );
    }

    public function testPostEvent1() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/empty.json';
        ob_start();
        $this->CI->postEvent();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-3,"msg":"Invalid content from php:\/\/input."}', 
            $output
        );
    }
    
    public function testPostEvent2() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/partly.json';
        ob_start();
        $this->CI->postEvent();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-4,"msg":"Parse jsondata failed. Error No. is 4"}', 
            $output
        );
    }
    
    public function testPostEvent3() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/noappkey.json';
        ob_start();
        $this->CI->postEvent();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-5,"msg":"Appkey is not set in json."}', 
            $output
        );
    }
    
    public function testPostEvent4() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/invalidappkey.json';
        ob_start();
        $this->CI->postEvent();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-1,"msg":"Invalid app key:invalid_appkey_00000"}', 
            $output
        );
    }

}
?>