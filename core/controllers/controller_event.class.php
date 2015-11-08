<?php
class Controller_Event extends Controller{
    public $form;
    protected function actionAdd(){
        if(!$this->_auth->getAuthStatus()){
            die('auth falied...');
        }

        $form_data = array(
            'action'=>'/event/add',
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
                    'MAX_FILE_SIZE'=>100*1024,
                    'name'=>'event_cover',
                    'required'=>0,
                ),

            ),
        );
        $this->form = new Form($form_data);

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $this->form->validator->setData($_POST);
            if($this->form->validator->checkFieldsForEmpty($this->form->getRequiredFields())->validationResult()){
                try{
                    $event = array(
                        'event_title' => $this->form->validator->getData('title'),
                        'date_event' => $this->form->validator->getData('date_event'),
                        'date_create' => (new DateTime('now'))->format('Y-m-d'),
                        'event_place' => $this->form->validator->getData('event_place'),
                        'event_description' => $this->form->validator->getData('event_description'),
                        'event_data' => $this->form->validator->getData('event_data'),
                        'creator' => $_SESSION['user_id'],
                        'event_participants' => 1,
                    );
                    $this->_link->start();
                    if(!$this->_link->insertRow('events', $event)->sendInsertQuery()){
                        throw new Exception($this->_link->getError());
                    }

                    $event_id = $this->_link->getInsertId();
                    if(!$this->_link->insertRow('participants', ['event_id'=>$event_id, 'user_id'=>$_SESSION['user_id'], 'user'=>$this->_user->getUserLogin()])->sendInsertQuery()){
                        throw new Exception($this->_link->getError());
                    }

                    if(isset($_FILES) and $_FILES['event_cover']['error'] == 0){
                        $image = new Image($_FILES['event_cover']['tmp_name'], Config::EVENTS_DIR.'event'.$event_id.'/', 'cover');
                        $image->createImg(['width' => 200, 'height' => 200])->createThumbs(['width' => 100, 'height' => 100]);

                        if(!$this->_link->updateRow('events', 'event_cover', Config::EVENTS_DIR.'event'.$event_id.'/thumbs_cover.jpg', $event_id)->sendInsertQuery()){
                            throw new Exception($this->_link->getError());
                        }
                        if(!$this->_link->updateRow('events', 'event_full_cover', Config::EVENTS_DIR.'event'.$event_id.'/cover.jpg', $event_id)->sendInsertQuery()){
                            throw new Exception($this->_link->getError());
                        }
                    }

                    $this->_link->commit();
                    header("Location: /main/event/".$event_id);
                }catch (Exception $e){
                    $this->_link->rollback();
                    if(is_dir(Config::EVENTS_DIR.'event'.$event_id)) {
                        $dir = opendir(Config::EVENTS_DIR.'event'.$event_id);
                        while($file = readdir($dir)){
                            if($file == '.' or $file == '..'){
                                continue;
                            }
                            unlink(Config::EVENTS_DIR . 'event' . $event_id.'/'.$file);
                        }
                        closedir($dir);
                        rmdir(Config::EVENTS_DIR . 'event' . $event_id);
                    }
                    echo $e->getMessage();
                }
            }else{
                $this->form->errors = $this->form->validator->getErrors();
            }
        }
        $this->page_data['title'] = 'Добавить встречу';
        $this->render('event_form');
    }
    protected function actionEdit(){
        if (!$this->_auth->getAuthStatus()) {
            die('auth falied...');
        }
        $event_id = $this->_url->getUrlSegment(2);
        if($event_id and is_numeric($event_id)) {
            $event = $this->_link->select('events')->selectWHERE('id', $event_id)->sendSelectQuery();
            if($event->num_rows != 0){
                $event = $event->fetch_assoc();
                if($event['creator'] == $_SESSION['user_id'] or Auth::getAccessLevel() == 'admin') {
                    $form_data = array(
                        'action'=>'/event/edit/'.$event_id,
                        'method'=>'POST',
                        'button_text'=>'Применить изменения',
                        'enctype'=>'multipart/form-data',
                        'fields'=>array(
                            array(
                                'field'=>'input',
                                'title'=>'Название встречи',
                                'name'=>'title',
                                'required'=>1,
                                'type'=>'text',
                                'value'=>$event['event_title'],
                            ),
                            array(
                                'field'=>'input',
                                'title'=>'Дата проведения',
                                'name'=>'date_event',
                                'type'=>'date',
                                'required'=>1,
                                'value'=>$event['date_event']
                            ),
                            array(
                                'field'=>'input',
                                'title'=>'Место проведения',
                                'name'=>'event_place',
                                'required'=>1,
                                'type'=>'text',
                                'value'=>$event['event_place']
                            ),
                            array(
                                'field'=>'textarea',
                                'title'=>'Анонс',
                                'name'=>'event_description',
                                'required'=>1,
                                'value'=>$event['event_description']
                            ),
                            array(
                                'field'=>'textarea',
                                'title'=>'Описание',
                                'name'=>'event_data',
                                'required'=>1,
                                'value'=>$event['event_data']
                            ),
                            array(
                                'field'=>'file',
                                'title'=>'Обложка',
                                'MAX_FILE_SIZE'=>100*1024,
                                'name'=>'event_cover',
                                'required'=>0,
                            ),

                        ),
                    );
                    $this->form = new Form($form_data);
                    if($_SERVER['REQUEST_METHOD'] == 'POST'){
                        $this->form->validator->setData($_POST);
                        if($this->form->validator->checkFieldsForEmpty($this->form->getRequiredFields())->validationResult()){
                            try{
                                $event_data = array(
                                    'event_title' => $this->form->validator->getData('title'),
                                    'date_event' => $this->form->validator->getData('date_event'),
                                    'date_create' => $event['date_create'],
                                    'event_place' => $this->form->validator->getData('event_place'),
                                    'event_description' => $this->form->validator->getData('event_description'),
                                    'event_data' => $this->form->validator->getData('event_data'),
                                    'creator' => $event['creator'],
                                    'event_participants' => $event['event_participants'],
                                );
                                $this->_link->start();
                                if(!$this->_link->update('events', $event_data, $event_id)->sendInsertQuery()){
                                    throw new Exception($this->_link->getError());
                                }

                                if(isset($_FILES) and $_FILES['event_cover']['error'] == 0){
                                    $image = new Image($_FILES['event_cover']['tmp_name'], Config::EVENTS_DIR.'event'.$event_id.'/', 'cover');
                                    $image->createImg(['width' => 200, 'height' => 200])->createThumbs(['width' => 100, 'height' => 100]);

                                    if(!$this->_link->updateRow('events', 'event_cover', Config::EVENTS_DIR.'event'.$event_id.'/thumbs_cover.jpg', $event_id)->sendInsertQuery()){
                                        throw new Exception($this->_link->getError());
                                    }
                                    if(!$this->_link->updateRow('events', 'event_full_cover', Config::EVENTS_DIR.'event'.$event_id.'/cover.jpg', $event_id)->sendInsertQuery()){
                                        throw new Exception($this->_link->getError());
                                    }
                                }

                                $this->_link->commit();
                                header("Location: /main/event/".$event_id);
                            }catch (Exception $e){
                                $this->_link->rollback();
                                if(is_dir(Config::EVENTS_DIR.'event'.$event_id)) {
                                    $dir = opendir(Config::EVENTS_DIR.'event'.$event_id);
                                    while($file = readdir($dir)){
                                        if($file == '.' or $file == '..'){
                                            continue;
                                        }
                                        unlink(Config::EVENTS_DIR . 'event' . $event_id.'/'.$file);
                                    }
                                    closedir($dir);
                                    rmdir(Config::EVENTS_DIR . 'event' . $event_id);
                                }
                                echo $e->getMessage();
                            }
                        }else{
                            $this->form->errors = $this->form->validator->getErrors();
                        }
                    }
                    $this->page_data['title'] = 'Редактировать встречу';
                    $this->render('event_form');
                }else{
                    self::show404();
                }
            }else{
                self::show404();
            }
        }else{
            self::show404();
        }
    }
    protected function actionDelete(){
        if(!$this->_auth->getAuthStatus()){
            die('auth falied...');
        }
        $event_id = $this->_url->getUrlSegment(2);
        if($event_id and is_numeric($event_id)){
            $event = $this->_link->select('events')->selectWHERE('id', $event_id)->sendSelectQuery();
            if($event->num_rows != 0){
                $event = $event->fetch_assoc();
                if($event['creator'] == $_SESSION['user_id'] or Auth::getAccessLevel() == 'admin'){
                    $this->_link->delete('events', $event_id)->sendInsertQuery();
                    $this->_link->deleteRows('participants', 'event_id', $event_id)->sendInsertQuery();
                    if(is_dir(Config::EVENTS_DIR.'event'.$event_id)) {
                        $dir = opendir(Config::EVENTS_DIR.'event'.$event_id);
                        while($file = readdir($dir)){
                            if($file == '.' or $file == '..'){
                                continue;
                            }
                            unlink(Config::EVENTS_DIR . 'event' . $event_id.'/'.$file);
                        }
                        closedir($dir);
                        rmdir(Config::EVENTS_DIR . 'event' . $event_id);
                    }
                    header("Location: /");
                }else{
                    self::show404();
                }
            }else{
                self::show404();
            }
        }else{
            self::show404();
        }
    }
}