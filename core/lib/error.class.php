<?php
class Error{
    protected $_errors;
    protected $_message;

    public function __construct($message=false){
        if($message !== false){
            $this->_message = $message;
        }
    }

    public function formError($error_data = false){
        if($error_data === false){
            return $this->_errors['form'];
        }
        $this->_errors['form'] = $error_data;
    }
    public function loginError($error_data = false){
        if($error_data === false){
            return $this->_errors['login'];
        }
        $this->_errors['login'] = $error_data;

    }
    public function getErrorMsg(){
        return $this->_message;
    }
}