<?php
session_start();
function __autoload($class_name){
    $delim_pos = strpos($class_name, '_');
    if($delim_pos){
        $dir = strtolower(substr($class_name, 0, $delim_pos)).'s/';
        $class_name = strtolower($class_name);
    }else{
        $dir = 'lib/';
        $class_name = strtolower($class_name);
    }
    include_once ('core/'.$dir.$class_name.'.class.php');
}
Router::start();