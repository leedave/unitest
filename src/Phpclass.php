<?php

namespace Leedch\Unitest;

use Exception;
use Leedch\Codemonkey\Core\File;
use Leedch\Codemonkey\Core\Project;

/**
 * Generates a test class from a PHP Class
 *
 * @author leed
 */
class Phpclass {
    
    public function generateTestsFromConfig($pathConfig) {
        if (!file_exists($pathConfig)) {
            throw new Exception('Cannot find config file '.$pathConfig);
        }
        
        $json = file_get_contents($pathConfig);
        $arrFiles = json_decode($json, true);
        foreach ($arrFiles as $className) {
            $this->generateTestClassFromClassName($className);
        }
        $this->generatePhpUnitXml();
        $this->generateBootstrap();
        
        $project = new Project();
        $arrConfig = [
            "projectname" => "Testclasses",
        ];
        $json = json_encode($arrConfig, JSON_UNESCAPED_UNICODE);
        $project->loadConfigJson($json);
        $project->returnZipFile();
    }
    
    protected function generatePhpUnitXml() {
        $filename = "phpunit.xml";
        $arrTemplates = [
            "Unitest/phpunit.xml.txt",
        ];
        $arrTemplateAttributes = [];
        $json = $this->generateJsonForFile($filename, $arrTemplates, $arrTemplateAttributes);
        
        $project = new Project();
        $project->loadConfigJson($json);
        $project->createFiles();
    }
    
    protected function generateBootstrap() {
        $filename = "tests/bootstrap.php";
        $arrTemplates = [
            "Unitest/bootstrap.php.txt",
        ];
        $arrTemplateAttributes = [];
        $json = $this->generateJsonForFile($filename, $arrTemplates, $arrTemplateAttributes);
        
        $project = new Project();
        $project->loadConfigJson($json);
        $project->createFiles();
    }


    protected function generateTestClassFromClassName($className) {
        $class = new $className();
        $this->generateTestClass($class);
    }
    
    public function generateTestClass($originalClass) {
        $className = get_class($originalClass);
        $classMethods = get_class_methods($className);
        $classAttributes = get_class_vars($className);
        
        $arrTemplateAttributes = [
            "namespace" => $this->getNamespaceForTestClass($className),
            "originalClassName" => $className,
            "testClassName" => $this->getClassNameForTestClass($className),
            "testClassAttributes" => "",
            "testClassMethods" => $this->generateTestMethods($className),
        ];
        
        $arrTemplates = [
            "Unitest/phpclass.php.txt",
        ];
        
        $filename = $this->getFileNameForTestClass($className);
        $path = $this->getFilePathForTestClass($className);
        $json = $this->generateJsonForFile($path.$filename, $arrTemplates, $arrTemplateAttributes);
        $project = new Project();
        $project->loadConfigJson($json);
        $project->createFiles();
        
        echo "Name: ".print_r($className, true)."<br />"
                . "Methods: ".print_r($classMethods, true)."<br />"
                . "Attributes: ".print_r($classAttributes, true)."<br />";
    }
    
    protected function generateTestMethods($className) {
        $arrClassMethods = get_class_methods($className);
        $file = new File('');
        $file->addTemplate(codemonkey_pathTemplateDir . "/Unitest/method.php.txt");
        
        $output = "";
        
        foreach ($arrClassMethods as $methodName) {
            if ($methodName == "__construct") {
                continue;
            }
            $attributes = [
                "className" => $className,
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
     * Generate JSON Code to load Codemonkey Project 
     * 
     * @param string $filename
     * @param array $templates
     * @param array $attributes
     * @return string   JSON
     */
    protected function generateJsonForFile($filename, $templates = [], $attributes = []) {
        $arrJson = [
            "files" => [
                [
                    "name" => $filename,
                    "templates" => $templates,
                    "attributes" => $attributes,
                ]
            ],
        ];
        return json_encode($arrJson, JSON_UNESCAPED_UNICODE);
    }
}
