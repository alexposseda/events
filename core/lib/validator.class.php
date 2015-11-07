<?php
class Validator{
    protected $_data;
    protected $_errors;
    public function __construct($data){
        $this->_data = $this->getSafeData($data);
    }
    public function checkField($field, $rule, $table = false){
        $this->$rule($field, $table);
        return $this;
    }
    public function checkFields($fields, $rule){
        foreach($fields as $field) {
            $this->$rule($field);
        }
        return $this;
    }
    public function checkFieldsForEmpty($fields){
        foreach($fields as $field){
            if(empty($this->_data[$field])){
                $this->_errors['fields'][$field][] = 'empty';
            }
        }
        return $this;
    }
    public function validationResult(){
        if(empty($this->_errors)){
            return true;
        }
        return false;
    }
    public function getErrors(){
        return $this->_errors;
    }
    public function getData($fields = false){
        if($fields !== false){
            $data = array();
            foreach($fields as $field){
                $data[$field] = $this->_data[$field];
            }
            return $data;
        }
        return $this->_data;
    }
    public function getDataFromField($field){
        if(isset($this->_data[$field])){
            return $this->_data[$field];
        }
    }
    protected function uniq($field, $table, $field_data = false){
        $link = Db::getLink();
        $data = ($field_data) ? $field_data : $this->_data[$field];
        $res = $link->select($table, [$field])->selectWHERE($field, $data)->sendSelectQuery();
        if($res->num_rows == 1){
            $this->_errors['fields'][$field][] = 'not_uniq';
        }
    }
    protected function email($field, $table = false){
        if(!empty($this->_data[$field])){
            if(!preg_match('/^([\w\-.])+@+([\w\-]{2}+.+[a-zA-Z]{2})$/', $this->_data[$field])){
                $this->_errors['fields'][$field][] = 'wrong_email_format';
            }
        }else{
            if(!isset($field, $this->_errors['fields'][$field])) {
                $this->_errors['fields'][$field][] = 'empty';
            }
        }
    }
    public function checkTime($time){
        if(!empty($time)) {
            $time = $this->getSafeData($time);
            if (!preg_match('/^([0-2]{1}\d{1}):\d{2}$/', $time)) {
                $this->_errors['fields']['time'][] = 'wrong_time_format';
            }
        }else{
            $this->_errors['fields']['time'][] = 'empty';
        }
        return $this;
    }
    protected function phone($field, $table=false){
        if(!empty($this->_data[$field])){
            if(!preg_match('/^\(\d{3}\)\s\d{3}-\d{2}-\d{2}$/', $this->_data[$field])){
                $this->_errors['fields'][$field][] = 'wrong_phone_format';
            }
        }else {
            if(!isset($field, $this->_errors['fields'][$field])) {
                $this->_errors['fields'][$field][] = 'empty';
            }
        }
    }
    protected function number($field, $table=false){
        if(!empty($this->_data[$field])){
            if(!is_numeric($this->_data[$field])){
                $this->_errors['fields'][$field][] = 'wrong_field_'.$field.'_format';
            }
        }else {
            if(!isset($field, $this->_errors['fields'][$field])) {
                $this->_errors['fields'][$field][] = 'empty';
            }
        }
    }
    protected function getSafeData($data){
        if(is_array($data)) {
            if(!is_array($data)) {
                foreach ($data as $key => $val) {
                    $data[$key] = trim(htmlspecialchars($val));
                }
            }
            return $data;
        }else{
            return trim(htmlspecialchars($data));
        }
    }
}