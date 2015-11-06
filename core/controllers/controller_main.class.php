<?php
class Controller_Main extends Controller{
    protected $_link;

    public function __construct(){
        $this->_link = Db::getLink();
    }

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
            $email = $_POST['email'];
            $password = md5(Config::SECRET.$_POST['password']);
            if(Auth::signIn($email, $password)){
                header('Location: /');
            }else{
                header('Location: /');
            }
        }else{
            $this->show404();
        }
    }
    public function registration(){
        $form_data = array(
            'action'=>'/add/user',
            'method'=>'POST',
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
        $this->content['form'] = new Form($form_data);

        $this->page_data['title'] = 'Регистрация';
        $this->render('general', 'reg');
    }
}