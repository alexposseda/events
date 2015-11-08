<?php
class Controller_Ajax extends Controller{
    protected function actionEvent(){
        if($_SERVER['REQUEST_METHOD'] == 'POST' and is_numeric($this->_url->getUrlSegment(2))){
            $event_id = $this->_url->getUrlSegment(2);
            $res = $this->_link->select('events', ['event_data', 'event_place', 'event_participants'])->selectWHERE('id', $event_id)->sendSelectQuery();
            if($res){
                $event = $res->fetch_assoc();
                $participants = $this->_link->select('participants', ['user'])->selectWHERE('event_id', $event_id)->sendSelectQuery();
                if($participants){
                    while($row = $participants->fetch_assoc()){
                        $event['all_participants'][] = $row['user'];
                    }
                }
                include 'layouts/templates/event_ajax.tpl';
            }
        }else{
            self::show404();
        }
    }
    protected function actionParticipate(){
        if($_SERVER['REQUEST_METHOD'] == "POST" and is_numeric($this->_url->getUrlSegment(2))){
            if($this->_auth->getAuthStatus()) {
                $user_id = $_SESSION['user_id'];
                $user_login = $this->_user->getUserLogin();
                $event_id = $this->_url->getUrlSegment(2);

                $res = $this->_link->select('participants', ['id'])->selectWHERE_AND(['user_id'=>$user_id, 'event_id'=>$event_id])->sendSelectQuery();
                if($res->num_rows != 0){
                    echo '<div id="small_modal"><span id="close">Закрыть</span><p>Вы уже учавствуете в этой встрече!</p></div>';
                }else{
                    $event = $this->_link->select('events', ['event_participants'])->selectWHERE('id', $event_id)->sendSelectQuery();
                    if($event){
                        $event = $event->fetch_assoc();
                        try {
                            $this->_link->start();
                            if(!$this->_link->updateRow('events', 'event_participants', $event['event_participants'] + 1, $event_id)->sendInsertQuery()){
                                throw new Exception($this->_link->getError());
                            }
                            if(!$this->_link->insertRow('participants', ['event_id' => $event_id, 'user_id' => $user_id, 'user' => $user_login])->sendInsertQuery()){
                                throw new Exception($this->_link->getError());
                            }
                            $this->_link->commit();
                            echo '<div id="small_modal"><span id="close">Закрыть</span><p>Ура! Вы участвуете!</p></div>';
                        }catch (Exception $e){
                            $this->_link->rollback();
                            echo '<div id="small_modal"><span id="close">Закрыть</span><p>DataBase error: '.$e->getMessage().'</p></div>';
                        }
                    }
                }


            }else{
                echo '<div id="small_modal"><span id="close">Закрыть</span><p>Нужна авторизация!</p></div>';
            }
        }else{
            self::show404();
        }
    }
}