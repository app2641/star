#!/usr/bin/env php
<?php

use Star\CLI;

try {
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT_PATH', realpath(dirname(__FILE__)));
    define('CMDS', ROOT_PATH.DS.'library'.DS.'Star'.DS.'Commands');
    define('DATA', ROOT_PATH.DS.'data');
    define('APPLICATION_PATH', ROOT_PATH . '/application');
    defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

    set_include_path(dirname(__FILE__) . '/library' . PATH_SEPARATOR . get_include_path());



    chdir(dirname(__FILE__));
    require_once ('Zend/Loader/Autoloader.php');
    require_once ('Star/CLI.php');


    $autoloader = Zend_Loader_Autoloader::getInstance();
    $autoloader->setFallbackAutoloader(true);

    $cli = CLI::getInstance();

    if (count($argv) > 1) {
        $cli->execute($argv);

    } else {
        $cli->renderCommandList(); 
    }

} catch (\Exception $e) {
    echo PHP_EOL.'  ' .  pack('c',0x1B) . "[1;31m" .
        $e->getMessage() . pack('c',0x1B) . "[0m" .PHP_EOL.PHP_EOL;
}
