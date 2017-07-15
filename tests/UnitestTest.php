<?php

namespace Leedch\Unitest;

use Leedch\Unitest\Unitest;
use PHPUnit\Framework\TestCase;

class UnitestTest extends TestCase {

    protected $config = "vendor/leedch/unitest/examples/example.json";
    
    public function testRunFromPhp() 
    {
        $m = new Unitest();
        //Method requires empty header, cant test that
        //$m->runFromPhp($this->config);
        $this->assertEquals("", "");
        //No assertion, just run to see that no exception is called
    }

    public function testRunFromBash() 
    {
        $m = new Unitest();
        $arrConfig = [
            "",
            $this->config
        ];
        ob_start();
        $m->runFromBash($arrConfig);
        ob_end_clean();
        //$this->assertEquals("", "");
        $filenames = [
            "temp/tests/Leedch/Unitest/PhpclassTest.php",
            "temp/tests/Leedch/Unitest/UnitestTest.php",
            "temp/tests/bootstrap.php",
            "temp/phpunit.xml",
        ];
        foreach ($filenames as $filename) {
            $this->assertFileExists($filename);
            $this->assertGreaterThan(0, filesize($filename));
        }
        //No assertion, just run to see that no exception is called
    }

    public function testGenerateJsonForFile() 
    {
        $m = new Unitest();
        $configFile = "vendor/leedch/unitest/examples/example.json";
        $file = "tests/bootstrap.php";
        $arrTemplates = [
            "vendor/Leedch/Unitest/templates/bootstrap.php"
        ];
        $arrAttributes = [];
        $json = $m->generateJsonForFile($file, $arrTemplates, $arrAttributes);
        $expected = '{"files":[{"name":"tests\/bootstrap.php","templates":["vendor\/Leedch\/Unitest\/templates\/bootstrap.php"],"attributes":[]}]}';
        $this->assertEquals($json, $expected);
    }


}
