<?php
/*========================================
 umsTest
 Test case of ums controller
 ========================================*/

class umsTest extends CIUnit_TestCase {
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
    public function testPostClientdata() {
        $this->CI->input = dirname(__FILE__) . '/testdata_clientdata/ok.json';
        ob_start();
        $this->CI->postClientdata();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":1,"msg":"ok"}', 
            $output
        );
    }

    public function testPostClientdata1() {
        $this->CI->input = dirname(__FILE__) . '/empty.json';
        ob_start();
        $this->CI->postClientdata();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-3,"msg":"Invalid content from php:\/\/input."}', 
            $output
        );
    }
    
    public function testPostClientdata2() {
        $this->CI->input = dirname(__FILE__) . '/partly.json';
        ob_start();
        $this->CI->postClientdata();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-4,"msg":"Parse jsondata failed. Error No. is 4"}', 
            $output
        );
    }
    
    public function testPostClientdata3() {
        $this->CI->input = dirname(__FILE__) . '/noappkey.json';
        ob_start();
        $this->CI->postClientdata();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-5,"msg":"Appkey is not set in json."}', 
            $output
        );
    }
    
    public function testPostClientdata4() {
        $this->CI->input = dirname(__FILE__) . '/invalidappkey.json';
        ob_start();
        $this->CI->postClientdata();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-1,"msg":"Invalid app key:invalid_appkey_00000"}', 
            $output
        );
    }

}
?>