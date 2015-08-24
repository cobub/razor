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
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/update_ok.json';
        ob_start();
        $this->CI->getApplicationUpdate();
        $output = ob_get_clean();
        $this->assertEquals('{"flag":1,"msg":"ok","fileurl":"http:\/\/localhost","forceupdate":"0","description":"android","time":"2015-08-01 00:00:00","version":"2"}', $output);
    }

    public function testUpdate_noupdate()
    {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/update_noupdate.json';
        ob_start();
        $this->CI->getApplicationUpdate();
        $output = ob_get_clean();
        $this->assertEquals('{"flag":-7,"msg":"no new version"}', $output);
    }

    public function testUpdate1()
    {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/empty.json';
        ob_start();
        $this->CI->getApplicationUpdate();
        $output = ob_get_clean();
        $this->assertEquals('{"flag":-3,"msg":"Invalid content from php:\/\/input."}', $output);
    }

    public function testUpdate2()
    {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/partly.json';
        ob_start();
        $this->CI->getApplicationUpdate();
        $output = ob_get_clean();
        $this->assertEquals('{"flag":-4,"msg":"Parse jsondata failed. Error No. is 4"}', $output);
    }

    public function testUpdate3()
    {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/noappkey.json';
        ob_start();
        $this->CI->getApplicationUpdate();
        $output = ob_get_clean();
        $this->assertEquals('{"flag":-5,"msg":"Appkey is not set in json."}', $output);
    }

    public function testUpdate4()
    {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/invalidappkey.json';
        ob_start();
        $this->CI->getApplicationUpdate();
        $output = ob_get_clean();
        $this->assertEquals('{"flag":-1,"msg":"Invalid app key:invalid_appkey_00000"}', $output);
    }

    public function testUpdate5()
    {
        $this->CI->rawdata = dirname(__FILE__) . '/testjson/onlyappkey.json';
        ob_start();
        $this->CI->getApplicationUpdate();
        $output = ob_get_clean();
        $this->assertEquals('{"flag":-7,"msg":"no new version"}', $output);
    }

}
?>