<?php
class Event{
    protected $_data;

    protected $_template = 'layouts/templates/event_small.tpl';
    protected $_link;

    public function __construct($data){
        $this->_link = Db::getLink();
        $this->_data = $data;

        $creator_name = $this->_link->select('users',['login'])->selectWHERE('id', $this->creator)->sendSelectQuery()->fetch_assoc();
        $this->creator_login = $creator_name['login'];
    }

    public function __get($key){
        //TODO проверка на существование?
        return $this->_data[$key];
    }
    public function __set($key, $val){
        $this->_data[$key] = $val;
    }

    public function showEvent(){
        include $this->_template;
    }
}