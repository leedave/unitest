<?php

/******
 * 
 * Copy this file to your Magento /pub/unitest.php and call by Url 
 * http://<yourdomain>/unitest.php
 * 
 *****/

//This nifty code converts PHP Errors from old functions into Exceptions for 
//better Handling
set_error_handler(function($errno, $errstr, $errfile, $errline){ 
    if (!(error_reporting() & $errno)) {
        return;
    }
    
    throw new \ErrorException($errstr, $errno, 0, $errfile, $errline);

});

require_once '../app/bootstrap.php';

if(!defined('codemonkey_constants')){
    define('codemonkey_constants', true);
    define('codemonkey_pathRoot', __DIR__.'/../');
    define('codemonkey_pathTempDir', codemonkey_pathRoot.'temp/');
    define('codemonkey_pathTemplateDir', codemonkey_pathRoot.'templates/');
}

$unitest = new \Leedch\Unitest\Unitest();
//Magento uses the ancient phpunit 4
$unitest->setUseOldPhpunit(true);
$unitest->runFromPhp(__DIR__ . '/../unitest.json');

//It is best practice to reset error handling when finished
restore_error_handler();