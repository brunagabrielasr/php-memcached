<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

spl_autoload_register(function($class){
    $file = __DIR__.'/'.$class.'.php';
    if (file_exists($file) && is_file($file)) {
        require_once $file;
    }
});