<?php
class Router{
    protected $_routes;

    public function __construct(){
        $this->_routes = explode('/', $_GET['url']);
    }
    public function run(){
        $c_name = (!empty($this->_routes[0])) ? 'Controller_'.ucfirst($this->_routes[0]) : 'Controller_Main' ;
        $a_name = (isset($this->_routes[1]) and !empty($this->_routes[1])) ? 'action'.ucfirst($this->_routes[1]) : 'actionIndex';

        if(file_exists('core/controllers/'.strtolower($c_name).'.class.php')){
            $controller = new $c_name();
            $controller->run($a_name);

        }else{
            Controller::show404();
        }
    }
}