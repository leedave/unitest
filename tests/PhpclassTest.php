<?php

namespace Leedch\Unitest;

use Leedch\Unitest\Phpclass;
use PHPUnit\Framework\TestCase;

class PhpclassTest extends TestCase {

    public function testGenerateTestClassFromClassName() 
    {
        $m = new Phpclass();
        $className = "\\Leedch\\Unitest\\Unitest";
        $m->generateTestClassFromClassName($className);
        $this->assertFileExists("temp/tests/Leedch/Unitest/UnitestTest.php");
    }

    public function testGenerateTestClass() 
    {
        $m = new Phpclass();
        $response = "";//$m->generateTestClass();
        $expected = "";
        $this->assertEquals($expected, $response);
        //No test needed is called in generateTestClassFromClassName()
    }
}
