<?php
class Url{
    protected $_url_segments;
    public function __construct(){
        $this->_url_segments = explode('/', $_GET['url']);
    }
    public function getOffset(){
        $segment_num = $this->getUrlSegment('page')+1;
        if(is_numeric($this->_url_segments[$segment_num])){
            return $this->_url_segments[$segment_num];
        }else{
            return Config::EVENTS_OFFSET;
        }
    }
    public function get($param_name){
        if(isset($_GET[$param_name]) and !empty($_GET[$param_name])){
            switch($param_name){
                case'offset':
                    if(is_numeric($_GET['offset'])){
                        return $_GET['offset'];
                    }
                    break;
                case'date':
                    if(preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['date'])){
                        return $_GET['date'];
                    }
                    break;
                case'limit':
                    if(is_numeric($_GET['limit'])){
                        return $_GET['limit'];
                    }
                    break;
            }
        }else{
            return false;
        }
    }
    public static function getUrl($get_param){
        $url = $_SERVER['REQUEST_URI'];
        $pos = strpos($url, '?');
        if($pos !== false){
            $start = strpos($url, $get_param, $pos);
            $finish = strlen($get_param)+$start+2;
            if($start !== false){
                $url = substr_replace($url, '', $start, $finish);
                return $url;
            }
            return $url.'&';
        }else{
            return $url.'?';
        }
    }
    public function getUrlSegment($param){
        if(is_int($param)){
            return $this->_url_segments[$param];
        }else if(is_string($param)){
            foreach($this->_url_segments as $key=>$val){
                if($val == 'page'){
                    return $key;
                }
            }
        }else{
            return false;
        }
    }
}