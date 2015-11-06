<?php
class Url{
    public static function getRoutes(){
        //TODO обезопасить данные из $_GET
        return explode('/', $_GET['url']);
    }
    public static function getUrlSegment($num){
        $segments = self::getRoutes();

        if(isset($segments[$num]) and !empty($segments[$num])){
            return $segments[$num];
        }else{
            return false;
        }
    }
}