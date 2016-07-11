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
        $this->CI->rawdata = dirname(__FILE__) . '/testdata_config/ok.json';
        ob_start();
        $this->CI->pushpolicyquery();
        $output = ob_get_clean();
        $this -> assertEquals(
        '{"reply":{"fileSize":1,"flag":1,"msg":"ok","autoGetLocation":"1","updateOnlyWifi":"1","sessionMillis":"3000","intervalTime":5,"reportPolicy":"1","returnCode":{"domain":"","type":"S","code":"AAAAAA"}}}',
            //'{"reply": {"returnCode": {"domain": null,"type": "S","code": "AAAAAA"},"flag": "1","description": "android sdk test","versionName": "version5","fileUrl": "http://c.cobub.com/uploadify/test.apk"}}', 
            $output
        );
    }

    public function testGetConfig1() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/empty.json';
        ob_start();
        $this->CI->pushpolicyquery();
        $output = ob_get_clean();
        $this -> assertEquals(
        '{"reply":{"fileSize":1,"flag":-3,"msg":"Invalid content from php:\/\/input.","returnCode":{"domain":"","type":"S","code":"AAAAAA"}}}',
            //'{"reply": {"returnCode": {"domain": null,"type": "S","code": "AAAAAA"},"flag": "-3","description": "android sdk test","versionName": "version5","fileUrl": "http://c.cobub.com/uploadify/test.apk"}}', 
            $output
        );
    }
    
    public function testGetConfig2() {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/partly.json';
        ob_start();
        $this->CI->pushpolicyquery();
        $output = ob_get_clean();
        $this -> assertEquals(
        '{"reply":{"fileSize":1,"flag":-5,"msg":"Appkey is not in json.","returnCode":{"domain":"","type":"S","code":"AAAAAA"}}}',
            //'{"reply": {"returnCode": {"domain": null,"type": "S","code": "AAAAAA"},"flag": "-4","description": "android sdk test","versionName": "version5","fileUrl": "http://c.cobub.com/uploadify/test.apk"}}', 
            $output
        );
    }
    
    public function testGetConfig3() {
        $this->CI->rawdata = dirname(__FILE__) . '/testdata_config/noappkey.json';
        ob_start();
        $this->CI->pushpolicyquery();
        $output = ob_get_clean();
        $this -> assertEquals(
        '{"reply":{"fileSize":1,"flag":-5,"msg":"Appkey is not in json.","returnCode":{"domain":"","type":"S","code":"AAAAAA"}}}', 
            $output
        );
    }
    
    public function testGetConfig4() {
        $this->CI->rawdata = dirname(__FILE__) . '/testdata_config/errorappkey.json';
        ob_start();
        $this->CI->pushpolicyquery();
        $output = ob_get_clean();
        $this -> assertEquals(
        '{"reply":{"fileSize":1,"flag":-5,"msg":"Appkey is not in json.","returnCode":{"domain":"","type":"S","code":"AAAAAA"}}}',
            //'{"reply": {"returnCode": {"domain": null,"type": "S","code": "AAAAAA"},"flag": "-1","description": "android sdk test","versionName": "version5","fileUrl": "http://c.cobub.com/uploadify/test.apk"}}', 
            $output
        );
    }
	 
}
?>