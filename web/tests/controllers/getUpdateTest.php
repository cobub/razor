<?php
/*========================================
 umsTest
 Test case of ums controller
 ========================================*/

class getUpdateTest extends CIUnit_TestCase
{
    public function __construct($name = NULL, array $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    public function setUp()
    {
        parent::setUp();
        $this->CI = set_controller('ums');
        $this->dbfixt('razor_channel_product');
        $this->dbfixt('razor_event_defination');
    }

    public function tearDown()
    {
        parent::tearDown();
        $tables = array(
            'razor_channel_product' => 'razor_channel_product',
            'razor_event_defination' => 'razor_event_defination'
        );

        $this->dbfixt_unload($tables);
    }

    public function testUpdate()
    {
        $this->CI->rawdata = dirname(__FILE__) . '/testdata_update/ok.json';
        ob_start();
        $this->CI->appupdate();
        $output = ob_get_clean();
        $this->assertEquals(
        '{"reply":{"flag":1,"msg":"ok","fileUrl":"http:\/\/localhost","forceupdate":"0","description":"android","time":"2016-05-01 21:45:22","versionName":"3","returnCode":{"domain":"","type":"S","code":"AAAAAA"}}}',
        //'{"flag":1,"msg":"ok","fileurl":"http:\/\/localhost","forceupdate":"0","description":"android","time":"2015-08-01 00:00:00","version":"2"}', 
        $output);
    }

    public function testUpdate_noupdate()
    {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/update_noupdate.json';
        ob_start();
        $this->CI->appupdate();
        $output = ob_get_clean();
        $this->assertEquals(//'{"reply":{"flag":-5,"msg":"Appkey is not in json.","returnCode":{"domain":"","type":"S","code":"AAAAAA"}}}', 
        '{"reply":{"flag":-1,"msg":"Invalid appkey:49d0b54ea086922f0dd5459269d338ce","returnCode":{"domain":"","type":"S","code":"AAAAAA"}}}',
        $output);
    }

    public function testUpdate1()
    {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/empty.json';
        ob_start();
        $this->CI->appupdate();
        $output = ob_get_clean();
        $this->assertEquals(//'{"reply":{"flag":-5,"msg":"Appkey is not in json.","returnCode":{"domain":"","type":"S","code":"AAAAAA"}}}', 
        '{"reply":{"flag":-3,"msg":"Invalid content from php:\/\/input.","returnCode":{"domain":"","type":"S","code":"AAAAAA"}}}',
        $output);
    }

    public function testUpdate2()
    {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/partly.json';
        ob_start();
        $this->CI->appupdate();
        $output = ob_get_clean();
        $this->assertEquals('{"reply":{"flag":-5,"msg":"Appkey is not in json.","returnCode":{"domain":"","type":"S","code":"AAAAAA"}}}', 
        $output);
    }

    public function testUpdate3()
    {
        $this->CI->rawdata = dirname(__FILE__) . '/testdata_update/noappkey.json';
        ob_start();
        $this->CI->appupdate();
        $output = ob_get_clean();
        $this->assertEquals('{"reply":{"flag":-5,"msg":"Appkey is not in json.","returnCode":{"domain":"","type":"S","code":"AAAAAA"}}}',
         $output);
    }

    public function testUpdate4()
    {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/invalidappkey.json';
        ob_start();
        $this->CI->appupdate();
        $output = ob_get_clean();
        $this->assertEquals('{"reply":{"flag":-5,"msg":"Appkey is not in json.","returnCode":{"domain":"","type":"S","code":"AAAAAA"}}}', 
        $output);
    }
/*
    public function testUpdate5()
    {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/onlyappkey.json';
        ob_start();
        $this->CI->getApplicationUpdate();
        $output = ob_get_clean();
        $this->assertEquals('{"flag":-7,"msg":"no new version"}', $output);
    }
*/
}
?>