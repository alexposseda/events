<?php
class Controller_Main extends Controller{
    public function index(){
        //TODO получить данные из $_GET
        $limit = 10;
        $offset = 0;
        //TODO получить данные из $_GET
        $d = new DateTime('now');
        $today = $d->format('Y-m-d');

        $events_data = $this->_link->
                                    select('events', ['id', 'event_title', 'creator', 'event_ava', 'event_description', 'date_create', 'date_event', 'event_participants','event_place'])->
                                    selectWHERE_AND(['>date_event' => $today])->
                                    selectLIMIT($offset, $limit)->
                                    sendSelectQuery();

        while($row = $events_data->fetch_assoc()){
            $creator = $this->_link->select('users', ['login'])->selectWHERE('id', $row['creator'])->sendSelectQuery()->fetch_assoc();
            $row['creator'] = $creator['login'];

            $this->content['events'][] = $row;
        }

        $this->page_data['title'] = 'Events';
        $this->render('general','main');
    }
    public function event(){
        $event_id = Url::getUrlSegment(2);
        if($event_id !== false){
            $event_data = $this->_link->
                                        select('events', ['event_title', 'creator', 'event_big_img', 'event_data', 'date_create', 'date_event', 'event_participants', 'event_place'])->
                                        selectWHERE('id', $event_id)->sendSelectQuery()->
                                        fetch_assoc();

            if(is_null($event_data)){
                $this->show404();
            }else{
                $creator = $this->_link->select('users', ['login'])->selectWHERE('id', $event_data['creator'])->sendSelectQuery()->fetch_assoc();
                $event_data['creator'] = $creator['login'];
                $this->content['event_data'] = $event_data;
                $this->page_data['title'] = $event_data['event_title'];

                $this->render('general', 'event');
            }
        }else{
            $this->show404();
        }
    }
    public function signin(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $ref = $_SERVER['HTTP_REFERER'];
            $email = $_POST['email'];
            $password = md5(Config::SECRET.$_POST['password']);
            if(Auth::signIn($email, $password)){
                header('Location: '.$ref);
            }else{
                header('Location: '.$ref);
            }
        }else{
            $this->show404();
        }
    }
    public function logout(){
        session_unset();
        session_destroy();
        header("location: /");
    }
    public function registration(){
        $form_data = array(
            'action'=>'/main/registration',
            'method'=>'POST',
            'button_text'=>'Зарегистрироваться и войти',
            'fields'=>array(
                array(
                    'field'=>'input',
                    'title'=>'Login',
                    'name'=>'login',
                    'required'=>1,
                ),
                array(
                    'field'=>'input',
                    'title'=>'Password',
                    'name'=>'password',
                    'type'=>'password',
                    'required'=>1,
                ),
                array(
                    'field'=>'input',
                    'title'=>'Password Confirm',
                    'name'=>'password_confirm',
                    'type'=>'password',
                    'required'=>1,
                ),
                array(
                    'field'=>'input',
                    'title'=>'Email',
                    'name'=>'email',
                    'type'=>'email',
                    'required'=>1,
                )
            ),
        );
        $this->form = new Form($form_data);
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $validator = new Validator($_POST);
            $required_fields = array();

            foreach($form_data['fields'] as $field){
                if($field['required'] == 1 && $field['field'] != 'file'){
                    $required_fields[] = $field['name'];
                }
            }
            if($validator->checkFieldsForEmpty($required_fields)->checkField('email', 'email')->checkField('login', 'uniq', 'users')->checkField('email', 'uniq', 'users')->validationResult()){
                if($validator->getDataFromField('password') == $validator->getDataFromField('password_confirm')){
                    $result = $this->_link->insertRow('users', ['login'=>$validator->getDataFromField('login'), 'password'=>md5(Config::SECRET.$validator->getDataFromField('password')), 'role' => 'user', 'email'=>$validator->getDataFromField('email')])->sendInsertQuery();
                    if($result !== false){
                        $_SESSION['user_id'] = $this->_link->getInsertId();
                        $_SESSION['role'] = 'user';
                        header("Location: /");
                    }
                }else{
                    $this->form->errors['fields']['password'][] = 'Пароли не совпадают!';
                    $this->form->errors['fields']['password_confirm'][] = 'Пароли не совпадают!';
                }
            }else{
                $this->form->errors = $validator->getErrors();
            }
        }

        $this->page_data['title'] = 'Регистрация';
        $this->render('general', 'reg');
    }
}