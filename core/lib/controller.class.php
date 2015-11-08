<?php
class Controller{
    protected $_link;
    protected $_auth;
    protected $_user;
    protected $_error;
    protected $_url;

    public $page_data;
    public $content;
    public $pagination;

    public function __construct(){
        $this->_link = Db::getLink();
        $this->_error = new Error();
        $this->_auth = new Auth();
        $this->_url = new Url();
        $this->_user = new User($this->_auth->getAccessLevel());
    }

    public function run($a_name){
        if(method_exists($this, $a_name)){
            $this->$a_name();
        }else{
            self::show404();
        }
    }
    protected function actionLogin(){
        if($this->_auth->getAuthStatus()){
            header("Location: /");
        }
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $validator = new Validator();
            $validator->setData($_POST);
            if($validator->checkFieldsForEmpty(['email','password'])->validationResult()){
                $email = $validator->getData('email');
                $password = md5(Config::SECRET.$validator->getData('password'));
                $login_result = $this->_link->select('users', ['id', 'login' ,'role'])->selectWHERE_AND(['email'=>$email, 'password'=>$password])->sendSelectQuery();
                if($login_result->num_rows != 0){
                    $user = $login_result->fetch_assoc();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['role'] = $user['role'];
                    header("Location: ".$_SERVER['HTTP_REFERER']);
                }else{
                    $this->_error->loginError('auth falied');
                }
            }else{
                $this->_error->formError($validator->getErrors());
            }
        }
        $this->render('login');
    }
    protected function actionLogout(){
        session_unset();
        session_destroy();
        header("Location: /");
    }
    protected function render($tpl = false){
        include_once 'layouts/general.tpl';

    }
    public function getAuth(){
        return $this->_auth;
    }
    public function getErrors(){
        return $this->_error;
    }
    public function getUrl(){
        return $this->_url;
    }
    public function getUser(){
        return $this->_user;
    }
    public static function show404(){
        echo 'page not found';
    }
}