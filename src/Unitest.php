<?php

namespace Leedch\Unitest;

use Exception;

/**
 * The main (controller) class for Unitest, the test generating unicorn
 *
 * @author leed
 */
class Unitest {
    
    protected $argv;
    protected $arrFiles;


    public function runFromBash($argv) {
        $this->argv = $argv;
        
        if (!isset($argv[1])) {
            echo $this->bashPrint("You didn't feed me with a config File :(\n");
            echo $this->bashPrintUsage();
            die();
        }
        
        try{
            $this->checkIfConfigFileExists($argv[1]);
        } catch (Exception $ex) {
            echo $this->bashPrint($ex->getMessage());
            echo $this->bashPrintUsage();
            die();
        }
        
    }
    
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
