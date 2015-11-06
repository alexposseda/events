<?php
class Router{
    public static function start(){
        $routes = Url::getRoutes();

        if(!empty($routes[0])){
            $controller_name = 'Controller_'.ucfirst($routes[0]);
        }else{
            $controller_name = 'Controller_Main';
        }

        if(file_exists('core/controllers/'.strtolower($controller_name).'.class.php')) {
            $controller = new $controller_name;
        }else{
            (new Controller())->show404();
            die();
        }

        if(isset($routes[1]) and !empty($routes[1])){
            $action_name = strtolower($routes[1]);
        }else{
            $action_name = 'index';
        }

        if(method_exists($controller, $action_name)){
            $controller->$action_name();
        }else{
            $controller->show404();
        }
    }
}