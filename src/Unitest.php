<?php

namespace Leedch\Unitest;

use Exception;
use Leedch\Unitest\Phpclass;
use Leedch\Codemonkey\Core\Project;

/**
 * The main (controller) class for Unitest, the test generating unicorn
 *
 * @author leed
 */
class Unitest {
    
    protected $argv;
    protected $arrFiles;
    protected $useOldPhpunit = false;

    /**
     * Trigger this if you want to generate code from PHP
     * @param string $pathConfig path to your config file
     */
    public function runFromPhp($pathConfig) {
        try{
            $this->checkIfConfigFileExists($pathConfig);
        } catch (Exception $ex) {
            echo $ex->getMessage();
            die();
        }
        $this->generateTestsFromConfig($pathConfig);
        
        //This Code just zips all the files and clears the temp folder
        $arrConfig = [
            "projectname" => "Testclasses",
        ];
        $jsonProject = json_encode($arrConfig, JSON_UNESCAPED_UNICODE);
        $project = new Project();
        $project->loadConfigJson($jsonProject);
        $project->returnZipFile();
    }
    
    /**
     * This gets triggered if a call is made using the bash bin
     * @param array $argv arguments from call
     */
    public function runFromBash($argv) {
        $this->argv = $argv;
        
        if (!isset($argv[1])) {
            echo $this->bashPrint("You didn't feed me with a config File :(\n");
            echo $this->bashPrintUsage();
            die();
        }
        
        if (isset($argv[2]) && $argv[2] == "oldversion") {
            $this->useOldPhpunit = true;
        }
        
        $pathConfig = $this->argv[1];
        
        if(!defined('codemonkey_constants')){
            define('codemonkey_constants', true);
            define('codemonkey_pathRoot', '');
            define('codemonkey_pathTempDir', codemonkey_pathRoot.'temp/');
            define('codemonkey_pathTemplateDir', codemonkey_pathRoot.'templates/');
        }
        
        try{
            $this->checkIfConfigFileExists($pathConfig);
        } catch (Exception $ex) {
            echo $this->bashPrint($ex->getMessage());
            echo $this->bashPrintUsage();
            die();
        }
        
        try {
            $this->generateTestsFromConfig($pathConfig);
        } catch (Exception $ex) {
            echo $this->bashPrint($ex->getMessage());
            echo $this->bashPrintUsage();
            die();
        }
        
        $text = "Yipii, your tests are generated. Copy them out of the folder "
                . codemonkey_pathTempDir
                . "\n\n";
        echo $this->bashPrint($text);
    }
    
    public function setUseOldPhpunit($bool) {
        $bool = (bool) $bool;
        $this->useOldPhpunit = $bool;
    }
    
    /**
     * Generates all the files
     * @param string $pathConfig
     * @throws Exception
     */
    protected function generateTestsFromConfig($pathConfig) {
        $project = new Project();
        $project->clearTempFolder();
        $jsonClassList = file_get_contents($pathConfig);
        $arrClasses = json_decode($jsonClassList, true);
        
        $this->includeAutoloaders($arrClasses);
        
        if (!isset($arrClasses['classes'])) {
            throw new Exception('No Classes defined');
        }
        foreach ($arrClasses['classes'] as $className) {
            $phpclass = new Phpclass();
            if ($this->useOldPhpunit) {
                $phpclass->setUseOldPhpunit(true);
            }
            $phpclass->generateTestClassFromClassName($className);
        }
        $this->generatePhpUnitXml();
        $this->generateBootstrap();
    }
    
    /**
     * Include all the configs defined in the autoloader
     * 
     * @param array $arrConfig
     */
    protected function includeAutoloaders($arrConfig) {
        if (!is_array($arrConfig) || !isset($arrConfig['autoloaders'])) {
            return;
        }
        
        foreach ($arrConfig['autoloaders'] as $autoloader) {
            require_once codemonkey_pathRoot . $autoloader;
        }
    }
    
    /**
     * Check if config file exists and is valid
     * @param string $file filepath
     * @throws Exception
     */
    protected function checkIfConfigFileExists($file = null) {
        if (!file_exists($file)) {
            throw new Exception("I can't find the config file ".$file." :(\n");
        }
        
        if (!$this->validateConfigFile($file)) {
            throw new Exception("You're config File is written bad, sorry cant read it :(");
        }
    }
    
    /**
     * Check if the config file is valid JSON
     * @return boolean true if valid
     */
    protected function validateConfigFile($file) {
        $json = file_get_contents($file);
        $arrFiles = json_decode($json, true);
        
        if (!is_array($arrFiles)) {
            return false;
        }
        return true;
    }
    
    /**
     * Creates XML for Unitest Suite
     */
    protected function generatePhpUnitXml() {
        $filename = "phpunit.xml";
        $arrTemplates = [
            __DIR__."/../templates/phpunit.xml.txt",
        ];
        //print_r($arrTemplates);die();
        $arrTemplateAttributes = [];
        $json = $this->generateJsonForFile($filename, $arrTemplates, $arrTemplateAttributes);
        
        $project = new Project();
        $project->loadConfigJson($json);
        $project->createFiles();
    }
    
    /**
     * Generate a bootstrap file for Unittests
     */
    protected function generateBootstrap() {
        $filename = "tests/bootstrap.php";
        $arrTemplates = [
            __DIR__."/../templates/bootstrap.php.txt",
        ];
        $arrTemplateAttributes = [];
        $json = $this->generateJsonForFile($filename, $arrTemplates, $arrTemplateAttributes);
        
        $project = new Project();
        $project->loadConfigJson($json);
        $project->createFiles();
    }
    
    /**
     * Generate JSON Code to load Codemonkey Project 
     * 
     * @param string $filename
     * @param array $templates
     * @param array $attributes
     * @return string   JSON
     */
    public function generateJsonForFile($filename, $templates = [], $attributes = []) {
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
    
    /**
     * Output the how to use the bash command
     */
    protected function bashPrintUsage() {
        $this->bashPrint("Usage: vendor/bin/unitest myConfigFile.json");
    }
    
    /**
     * Output text to Bash
     * @param string $text
     */
    protected function bashPrint($text) {
        echo "\n"
        . $text
        . "\n\n";
    }
}
