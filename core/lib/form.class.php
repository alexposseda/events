<?php
class Form{
    public $method;
    public $action;
    public $enctype;
    public $fields;
    public $errors;
    public $button_text;

    public function __construct($data){
        foreach($data as $key => $val){
            $this->$key = $val;
        }
    }
    public function getInpClass($inp_name){
        if (!empty($this->errors)) {
            if (isset($this->errors['fields'][$inp_name])) {
                return 'has-error';
            } else {
                return 'has-success';
            }
        }
    }
    public function showForm(){
        include 'layouts/templates/form.tpl';
    }
}

