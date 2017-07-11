<?php

namespace Leedch\Unitest;

use Leedch\Unitest\Unitest;
use PHPUnit\Framework\TestCase;

class UnitestTest extends TestCase {

    protected $config = "vendor/leedch/unitest/examples/example.json";
    
    public function testRunFromPhp() 
    {
        $m = new Unitest();
        $m->runFromPhp($this->config);
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
        $m->runFromBash($arrConfig);
        $this->assertEquals("", "");
        //No assertion, just run to see that no exception is called
    }

    public function testGenerateJsonForFile() 
    {
        $m = new Unitest();
        $config = "vendor/leedch/unitest/examples/example.json";
        $m->generateJsonForFile($config);
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
    }


}
