<?php

namespace Leedch\Unitest;

use Exception;
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
    
    protected function setUseOldPhpunit($bool) {
        $this->useOldPhpunit = $bool;
    }
    
    /**
     * Makes a test class 
     * @param string $className the full (namespace) class name
     */
    public function generateTestClassFromClassName($className) {
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
        
        foreach ($arrClassMethods as $methodName) {
            if ($methodName == "__construct") {
                continue;
            }
            $attributes = [
                "className" => $className,
                "classNameNoNamespace" => $classNameNoNamespace,
                "originalMethodName" => $methodName,
                "methodName" => "test" . ucfirst($methodName),
            ];
            $file->addAttributes($attributes);
            $output .= $file->generateCode();
        }
        return $output;
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
