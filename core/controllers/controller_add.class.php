<?php
class Controller_Add extends Controller{
    public function event(){
        $form_data = array(
            'action'=>'/add/event',
            'method'=>'POST',
            'button_text'=>'Создать встречу',
            'enctype'=>'multipart/form-data',
            'fields'=>array(
                array(
                    'field'=>'input',
                    'title'=>'Название встречи',
                    'name'=>'title',
                    'required'=>1,
                    'type'=>'text',
                ),
                array(
                    'field'=>'input',
                    'title'=>'Дата проведения',
                    'name'=>'date_event',
                    'type'=>'date',
                    'required'=>1,
                ),
                array(
                    'field'=>'input',
                    'title'=>'Место проведения',
                    'name'=>'event_place',
                    'required'=>1,
                    'type'=>'text',
                ),
                array(
                    'field'=>'textarea',
                    'title'=>'Анонс',
                    'name'=>'event_description',
                    'required'=>1,
                ),
                array(
                    'field'=>'textarea',
                    'title'=>'Описание',
                    'name'=>'event_data',
                    'required'=>1,
                ),
                array(
                    'field'=>'file',
                    'title'=>'Обложка',
                    'name'=>'event_ava',
                    'required'=>0,
                ),

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

            if($validator->checkFieldsForEmpty($required_fields)->validationResult()){
                if($validator->getDataFromField('password') == $validator->getDataFromField('password_confirm')) {
                    $now = (new DateTime('now'))->format('Y-m-d');
                    if($this->_link->
                    insertRow('events', ['creator'=>$_SESSION['user_id'],
                                        'date_create'=>$now,
                                        'date_event'=>$validator->getDataFromField('date_event'),
                                        'event_title'=>$validator->getDataFromField('title'),
                                        'event_description'=>$validator->getDataFromField('event_description'),
                                        'event_data'=>$validator->getDataFromField('event_data'),
                                        'event_place'=>$validator->getDataFromField('event_data')])->
                    sendInsertQuery()){
                        $event_id = $this->_link->getInsertId();
                        if (isset($_FILES) and $_FILES['event_ava']['errors'] == 0 and !empty($_FILES['event_ava']['tmp_name'])) {
                            $image = new Image($_FILES['event_ava']['tmp_name'], 'storage/events/event'.$event_id.'/', 'big_ava');
                            $image->createImg(['width' => 200, 'height' => 200])->createThumbs(['width' => 64, 'height' => 64]);

                            $this->_link->
                            update('events', ['event_ava'=>'storage/events/event'.$event_id.'/ava_big_ava.jpg', 'event_big_img'=>'storage/events/event'.$event_id.'/big_ava.jpg'], $event_id)->
                            sendInsertQuery();
                        }
                        header("Location: /main/event/".$event_id);
                    }
                }
            }else{
                $this->form->errors = $validator->getErrors();
            }
        }
        $this->page_data['title'] = 'Create event';
        $this->render('general', 'add_event');
    }
}