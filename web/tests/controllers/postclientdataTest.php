<?php
/*========================================
 umsTest
 Test case of ums controller
 ========================================*/

class postclientdataTest extends CIUnit_TestCase {
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
    public function testPostClientdata() {
        $this->CI->rawdata = dirname(__FILE__) . '/testdata_client/ok.json';
        ob_start();
        $this->CI->postClientdata();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":1,"msg":"ok"}', 
            $output
        );
    }

    public function testPostClientdata1() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/empty.json';
        ob_start();
        $this->CI->postClientdata();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-3,"msg":"Invalid content from php:\/\/input."}', 
            $output
        );
    }
 
    public function testPostClientdata2() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/partly.json';
        ob_start();
        $this->CI->postClientdata();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-4,"msg":"Parse json data failed."}', 
            $output
        );
    }
    
    public function testPostClientdata3() {
        $this->CI->rawdata = dirname(__FILE__) . '/testdata_client/noappkey.json';
        ob_start();
        $this->CI->postClientdata();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-5,"msg":"Appkey is not set in json."}', 
            $output
        );
    }
    
    public function testPostClientdata4() {
        $this->CI->rawdata = dirname(__FILE__) . '/testdata_client/errorappkey.json';
        ob_start();
        $this->CI->postClientdata();
        $output = ob_get_clean();
        $this -> assertEquals(
            '{"flag":-1,"msg":"Invalid appkey:invalid_appkey_00000"}', 
            $output
        );
    }

}
?>