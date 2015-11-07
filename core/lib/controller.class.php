<?php
class Controller{
    public $page_data;
    public $content;
    protected $_link;
    public $form;

    public function __construct(){
        $this->_link = Db::getLink();
        if(Auth::checkAuth()){
            $this->content['user_data'] = $this->_link->select('users', ['login'])->selectWHERE('id', $_SESSION['user_id'])->sendSelectQuery()->fetch_assoc();
        }
    }
    public function render($base_tpl, $tpl){
        include_once 'layouts/'.$base_tpl.'.tpl';
    }
    public function show404(){
        echo 404;
    }
}