<?php

namespace Leedch\Unitest;

use Leedch\Unitest\Phpclass;
use PHPUnit\Framework\TestCase;

class PhpclassTest extends TestCase {

    public function testSetUseOldPhpunit() {
        $m = new Phpclass();
        $m->setUseOldPhpunit(true);
        $this->assertAttributeEquals(true, 'useOldPhpunit', $m);
        $m->setUseOldPhpunit(false);
        $this->assertAttributeEquals(false, 'useOldPhpunit', $m);
        $m->setUseOldPhpunit(1);
        $this->assertAttributeEquals(true, 'useOldPhpunit', $m);
        $m->setUseOldPhpunit(0);
        $this->assertAttributeEquals(false, 'useOldPhpunit', $m);
    }
    
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
