<?php

require_once("../spyc.php");

class DumpTest extends PHPUnit_Framework_TestCase
{

    private $files_to_test = array();

    public function setUp()
    {
        $this->files_to_test = array('../spyc.yaml', 'failing1.yaml', 'indent_1.yaml', 'quotes.yaml');
    }

    public function testDump()
    {
        foreach ($this->files_to_test as $file) {
            $yaml = spyc_load(file_get_contents($file));
            $dump = Spyc::YAMLDump($yaml);
            $yaml_after_dump = Spyc::YAMLLoad($dump);
            $this->assertEquals($yaml, $yaml_after_dump);
        }
    }

    public function testDumpWithQuotes()
    {
        $Spyc = new Spyc();
        $Spyc->setting_dump_force_quotes = true;
        foreach ($this->files_to_test as $file) {
            $yaml = $Spyc->load(file_get_contents($file));
            $dump = $Spyc->dump($yaml);
            $yaml_after_dump = Spyc::YAMLLoad($dump);
            $this->assertEquals($yaml, $yaml_after_dump);
        }
    }

    public function testDumpArrays()
    {
        $dump = Spyc::YAMLDump(array('item1', 'item2', 'item3'));
        $awaiting = "---\n- item1\n- item2\n- item3\n";
        $this->assertEquals($awaiting, $dump);
    }

    public function testDumpNumerics()
    {
        $dump = Spyc::YAMLDump(array('404', '405', '500'));
        $awaiting = "---\n- 404\n- 405\n- 500\n";
        $this->assertEquals($awaiting, $dump);
    }

    public function testDumpAsterisks()
    {
        $dump = Spyc::YAMLDump(array('*'));
        $awaiting = "---\n- '*'\n";
        $this->assertEquals($awaiting, $dump);
    }


    public function testEmpty()
    {
        $dump = Spyc::YAMLDump(array("foo" => array()));
        $awaiting = "---\nfoo: [ ]\n";
        $this->assertEquals($awaiting, $dump);
    }

}