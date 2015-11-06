<?php
class Controller{
    public $page_data;
    public $content;

    public function render($base_tpl, $tpl){
        include_once 'layouts/'.$base_tpl.'.tpl';
    }
    public function show404(){
        echo 404;
    }
}