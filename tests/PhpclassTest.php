<?php

namespace Leedch\Unitest;

use Leedch\Unitest\Phpclass;
use PHPUnit\Framework\TestCase;

class PhpclassTest extends TestCase {

    public function testGenerateTestsFromConfig() 
    {
        $m = new Phpclass();
        $response = "";//$m->generateTestsFromConfig();
        $expected = "";
        $this->assertEquals($expected, $response);
    }

    public function testGenerateTestClassFromClassName() 
    {
        $m = new Phpclass();
        $response = "";//$m->generateTestClassFromClassName();
        $expected = "";
        $this->assertEquals($expected, $response);
    }

    public function testGenerateTestClass() 
    {
        $m = new Phpclass();
        $response = "";//$m->generateTestClass();
        $expected = "";
        $this->assertEquals($expected, $response);
    }

    public function testGenerateTestMethods() 
    {
        $m = new Phpclass();
        $response = "";//$m->generateTestMethods();
        $expected = "";
        $this->assertEquals($expected, $response);
    }

    public function testGetNamespaceForTestClass() 
    {
        $m = new Phpclass();
        $response = "";//$m->getNamespaceForTestClass();
        $expected = "";
        $this->assertEquals($expected, $response);
    }

    public function testGetFileNameForTestClass() 
    {
        $m = new Phpclass();
        $response = "";//$m->getFileNameForTestClass();
        $expected = "";
        $this->assertEquals($expected, $response);
    }

    public function testGetFilePathForTestClass() 
    {
        $m = new Phpclass();
        $response = "";//$m->getFilePathForTestClass();
        $expected = "";
        $this->assertEquals($expected, $response);
    }

    public function testGetClassNameForTestClass() 
    {
        $m = new Phpclass();
        $response = "";//$m->getClassNameForTestClass();
        $expected = "";
        $this->assertEquals($expected, $response);
    }

    public function testGenerateJsonForFile() 
    {
        $m = new Phpclass();
        $response = "";//$m->generateJsonForFile();
        $expected = "";
        $this->assertEquals($expected, $response);
    }


}
