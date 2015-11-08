<?php
class User{
    protected $_role;
    protected $_user_data;
    public function __construct($role){
        $this->_role = $role;
        if($this->_role != 'guest'){
            $link = Db::getLink();
            $this->_user_data = $link->select('users', ['login'])->selectWHERE('id', $_SESSION['user_id'])->sendSelectQuery()->fetch_assoc();
        }
    }
    public function getUserLogin(){
        if(!empty($this->_user_data)){
            return $this->_user_data['login'];
        }
    }
}