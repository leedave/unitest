<?php

namespace Leedch\Unitest;

use Exception;
use ReflectionClass;
use ReflectionParameter;
use Leedch\Unitest\Unitest;
use Leedch\Codemonkey\Core\File;
use Leedch\Codemonkey\Core\Project;

/**
 * Generates a test class from a PHP Class
 *
 * @author leed
 */
class Phpclass {
    
    protected $useOldPhpunit = false;
    protected $constructParams = [];
    protected $constructorParamsCall = [];
    protected $constructorParamsDeclare = [];
    
    protected function setUseOldPhpunit($bool) {
        $this->useOldPhpunit = $bool;
    }
    
    /**
     * Makes a test class 
     * @param string $className the full (namespace) class name
     */
    public function generateTestClassFromClassName($className) {
        $this->getOriginalClassConstructorParams($className);
        $arrTemplateAttributes = [
            "namespace" => $this->getNamespaceForTestClass($className),
            "originalClassName" => $className,
            "testClassName" => $this->getClassNameForTestClass($className),
            "testClassAttributes" => "",
            "testClassMethods" => $this->generateTestMethods($className),
        ];
        
        $arrTemplates = [
            __DIR__."/../templates/phpclass.php.txt",
        ];
        
        if ($this->useOldPhpunit) {
            $arrTemplates = [
                __DIR__."/../templates/phpclass_old.php.txt",
            ];
        }
        
        $filename = $this->getFileNameForTestClass($className);
        $path = $this->getFilePathForTestClass($className);
        $json = $this->generateJsonForFile($path.$filename, $arrTemplates, $arrTemplateAttributes);
        $project = new Project();
        $project->loadConfigJson($json);
        $project->createFiles();
    }
    
    /**
     * Fetch Constructor params from original class and put into 
     * this->constructParams array $paramName => ["type" => "hintName", "name" => "classname"]
     * 
     * @param string $className
     */
    protected function getOriginalClassConstructorParams($className) {
        $class = new ReflectionClass($className);
        $constructor = $class->getConstructor();
        $constparams = $constructor->getParameters();
        foreach ($constparams as $paramName) {
            $param = new ReflectionParameter([$className, '__construct'], $paramName->name);
            $class = $param->getClass();
            if ($class) {
                $this->constructParams[$paramName->name] = ["type" => "class", "name" => $class->name];
            } else if ($param->getType()) {
                $this->constructParams[$paramName->name] = ["type" => $param->getType()->__toString()];
            } else {
                $this->constructParams[$paramName->name] = ["type" => "string"];
            }
        }
    }
    
    /**
     * Create Test Methods to put in Class
     * @param string $className
     * @return string Methods for in Class
     */
    protected function generateTestMethods($className) {
        $arrClassName = explode("\\", $className);
        $classNameNoNamespace = array_pop($arrClassName);
        $arrClassMethods = get_class_methods($className);
        $file = new File('');
        $file->addTemplate(__DIR__."/../templates/method.php.txt");
        
        $output = "";
        
        //Prepare code for instantiation of original class in test method
        $this->generateCodeForInstantiation();
        
        foreach ($arrClassMethods as $methodName) {
            if ($methodName == "__construct") {
                continue;
            }
            $attributes = [
                "className" => $className,
                "classNameNoNamespace" => $classNameNoNamespace,
                "originalMethodName" => $methodName,
                "methodName" => "test" . ucfirst($methodName),
                "constructorParamsDeclare" => implode("\n        ", $this->constructorParamsDeclare),
                "constructorParamsCall" => implode(", ", $this->constructorParamsCall)
            ];
            $file->addAttributes($attributes);
            $output .= $file->generateCode();
        }
        return $output;
    }
    
    protected function generateCodeForInstantiation() {
        if (count($this->constructParams) < 1) {
            //No constructor params, return empty
            return "";
        }
        
        foreach ($this->constructParams as $name => $arrDetails) {
            $this->constructorParamsCall[] = "$".$name;
            if (!isset($arrDetails['type'])) {
                continue;
            }
            if (isset($arrDetails['name']) && $arrDetails['type'] == "class") {
                $this->constructorParamsDeclare[] = "$".$name.' = $this->createMock(\''.$arrDetails['name'].'\');';
            } elseif ($arrDetails['type'] == 'string') {
                $this->constructorParamsDeclare[] = "$".$name.' = "";';
            } else {
                $this->constructorParamsDeclare[] = "$".$name.' = ('.$arrDetails['type'].') "";';
            }
        }
    }
    
    /**
     * Gets the namespace from class name
     * 
     * @param type $className
     * @return string
     */
    protected function getNamespaceForTestClass($className) {
        $arrNamespace = explode("\\", $className);
        array_pop($arrNamespace);
        if (count($arrNamespace) < 1) {
            return "";
        }
        
        return "namespace ".implode("\\", $arrNamespace).";";
    }
    
    /**
     * Returns File name (no path) for Test Class
     * 
     * @param string $className
     * @return string
     */
    protected function getFileNameForTestClass($className) {
        $arrClassName = explode("\\", $className);
        return array_pop($arrClassName)."Test.php";
    }
    
    /**
     * Generates a file path for a test class
     * @param string $className full class name with namespace
     * @return string   path for file
     */
    protected function getFilePathForTestClass($className) {
        $arrClassName = explode("\\", $className);
        array_pop($arrClassName);
        return "tests"
                . DIRECTORY_SEPARATOR
                . implode(DIRECTORY_SEPARATOR, $arrClassName)
                . DIRECTORY_SEPARATOR;
    }
    
    /**
     * Returns Class Name for Test Class (no namespace)
     * 
     * @param string $className
     * @return string
     */
    protected function getClassNameForTestClass($className) {
        $arrClassName = explode("\\", $className);
        return array_pop($arrClassName)."Test";
    }

    /**
     * Simple Shortcut for Unitest::generateJsonForFile
     * @param string $filename  filePath
     * @param array $templates  Array of Templates
     * @param array $attributes Array of Attributes
     */
    protected function generateJsonForFile($filename, $templates = [], $attributes = []) {
        $unitest = new Unitest();
        return $unitest->generateJsonForFile($filename, $templates, $attributes);
    }
}
