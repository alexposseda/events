<?php
class Controller_Main extends Controller{
    public $offset;
    public $limit;
    public $date;
    public $form;

    protected function actionIndex(){
        $this->offset = ($this->_url->get('offset')) ? $this->_url->get('offset') : Config::EVENTS_OFFSET;
        $this->limit = ($this->_url->get('limit')) ? $this->_url->get('limit') : Config::EVENTS_LIMIT;

        $this->date = ($this->_url->get('date')) ? $this->_url->get('date') : ( new DateTime('now'))->format('Y-m-d') ;

        $events = $this->_link
            ->select('events', ['id', 'event_title', 'creator', 'event_cover', 'event_description', 'date_create', 'date_event', 'event_participants','event_place'])
            ->selectWHERE_AND(['>date_event' => $this->date])
            ->selectLIMIT($this->offset, $this->limit)
            ->sendSelectQuery();
        while($row = $events->fetch_assoc()){
            $this->content['events'][] = new Event($row);
        }

        $this->page_data['title'] = 'EVENTS';
        $this->pagination = new Pagination('events', $this->limit, $this->offset);
        $this->render('main');
    }
    protected function actionEvent(){
        $event_id = $this->_url->getUrlSegment(2);
        if($event_id and is_numeric($event_id)){
            $event = $this->_link
                                ->select('events', ['id', 'event_title', 'creator', 'event_full_cover', 'event_description', 'event_data', 'date_create', 'date_event', 'event_participants','event_place'])
                                ->selectWHERE('id', $event_id)
                                ->sendSelectQuery();
            if($event->num_rows != 0){
                $this->content['event'] = new Eventfull($event->fetch_assoc());
                $this->page_data['title'] = $event->fetch_assoc()['title'];
                $this->render('event');

            }else{
                self::show404();
            }
        }else{
            self::show404();
        }
    }
    protected function actionRegistration(){
        if(!$this->_auth->getAuthStatus()){
            $form_data = array(
                'action'=>'/main/registration',
                'method'=>'POST',
                'button_text'=>'Регистрация',
                'fields'=>array(
                    array(
                        'field'=>'input',
                        'title'=>'Login',
                        'name'=>'login',
                        'required'=>1,
                        'type'=>'text',
                    ),
                    array(
                        'field'=>'input',
                        'title'=>'Email',
                        'name'=>'email',
                        'type'=>'email',
                        'required'=>1,
                    ),
                    array(
                        'field'=>'input',
                        'title'=>'Password',
                        'name'=>'password',
                        'required'=>1,
                        'type'=>'password',
                    ),
                    array(
                        'field'=>'input',
                        'title'=>'Password Confirm',
                        'name'=>'password_confirm',
                        'required'=>1,
                        'type'=>'password',
                    ),

                ),
            );
            $this->form = new Form($form_data);
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $this->form->validator->setData($_POST);
                if($this->form->validator->checkFieldsForEmpty($this->form->getRequiredFields())->checkFields('email', 'email')->checkFields('email', 'uniq', 'users')->checkFields('login', 'uniq', 'users')->validationResult()){
                    if($this->form->validator->getData('password') == $this->form->validator->getData('password_confirm')){
                        $user_data = array(
                            'login' => $this->form->validator->getData('login'),
                            'email' => $this->form->validator->getData('email'),
                            'password' => md5(Config::SECRET.$this->form->validator->getData('password')),
                            'role' => 'user',
                        );

                        if($this->_link->insertRow('users', $user_data)->sendInsertQuery()){
                            $_SESSION['user_id'] = $this->_link->getInsertId();
                            $_SESSION['role'] = 'user';
                            header('Location: /');
                        }else{
                            $this->_error = new Error('Something wrong... DB error:'.$this->_link->getError());
                        }
                    }else{
                        $this->form->errors['fields']['password_confirm'][] = 'Пароли не совпадают!';
                    }
                }else{
                    $this->form->errors = $this->form->validator->getErrors();
                }
            }
            $this->page_data['title'] = 'Регистрация';
            $this->render('registration');
        }else{
            self::show404();
        }

    }
}