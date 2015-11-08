<?php
class Form{
    public $method;
    public $action;
    public $enctype;
    public $fields;
    public $errors;
    public $button_text;
    public $validator;

    public function __construct($data){
        foreach($data as $key => $val){
            $this->$key = $val;
        }

        $this->validator = new Validator();
    }
    public function getInpClass($inp_name){
        if (!empty($this->errors)) {
            if (isset($this->errors['fields'][$inp_name])) {
                return 'inp-error';
            } else {
                return 'inp-success';
            }
        }
    }
    public function showForm(){
        include 'layouts/templates/form.tpl';
    }
    public function getRequiredFields(){
        $required_fields = array();

        foreach($this->fields as $field){
            if($field['required'] == 1 && $field['field'] != 'file'){
                $required_fields[] = $field['name'];
            }
        }
        return $required_fields;
    }
}

