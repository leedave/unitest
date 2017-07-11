<?php

namespace Leedch\Unitest;

use Leedch\Unitest\Unitest;
use PHPUnit\Framework\TestCase;

class UnitestTest extends TestCase {

    public function testRunFromPhp() 
    {
        $m = new Unitest();
        $response = "";//$m->runFromPhp();
        $expected = "";
        $this->assertEquals($expected, $response);
    }

    public function testRunFromBash() 
    {
        $m = new Unitest();
        $response = "";//$m->runFromBash();
        $expected = "";
        $this->assertEquals($expected, $response);
    }

    public function testGenerateJsonForFile() 
    {
        $m = new Unitest();
        $response = "";//$m->generateJsonForFile();
        $expected = "";
        $this->assertEquals($expected, $response);
    }


}
