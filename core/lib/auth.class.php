<?php
class Auth{
    protected $_auth_status;
    public static $_access_level;
    public function __construct(){
        //TODO function checkCookies must be here!

        if(isset($_SESSION['user_id']) and !empty($_SESSION['user_id'])){
            $this->_auth_status = true;
            self::$_access_level = $_SESSION['role'];
        }else{
            $this->_auth_status = false;
            self::$_access_level = 'guest';
        }
    }
    public static function getAccessLevel(){
        return self::$_access_level;
    }
    public function getAuthStatus(){
        return $this->_auth_status;
    }
}