<?php
class Validator{
    protected $_data;
    protected $_errors;
    public function __construct(){

    }
    public function setData($data){
        $this->_data = $this->getSafeData($data);
        return $this;
    }
    public function checkFields($fields, $rule, $table = false){
        if(is_array($fields)){
            foreach($fields as $field) {
                $this->$rule($field, $table);
            }
        }else{
            $this->$rule($fields, $table);
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
            if(is_array($fields)) {
                $data = array();
                foreach ($fields as $field) {
                    $data[$field] = $this->_data[$field];
                }
                return $data;
            }else{
                return $this->_data[$fields];
            }
        }
        return $this->_data;
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
    protected function getSafeData($data)
    {
        if (is_array($data)) {
            if (!is_array($data)) {
                foreach ($data as $key => $val) {
                    $data[$key] = trim(htmlspecialchars($val));
                }
            }
            return $data;
        } else {
            return trim(htmlspecialchars($data));
        }
    }
}