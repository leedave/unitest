<?php

/**
 * Use this file in your webroot to generate tests via web
 */

//This nifty code converts PHP Errors from old functions into Exceptions for 
//better Handling
set_error_handler(function($errno, $errstr, $errfile, $errline){ 
    if (!(error_reporting() & $errno)) {
        return;
    }
    
    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);

});

require_once '../vendor/autoload.php';
require_once '../configs/constants.php';
require_once '../src/autoload.php';


$unitest = new \Leedch\Unitest\Unitest();
//$unitest->runFromPhp(__DIR__ . '/../vendor/leedch/unitest/examples/'."example.json");
$unitest->runFromPhp(__DIR__ . '/../unitest.json');

//It is best practice to reset error handling when finished
restore_error_handler();