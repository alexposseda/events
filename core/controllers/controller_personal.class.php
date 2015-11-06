<?php
class Controller_Personal extends Controller{
    protected $_link;
    protected $_user_id;
    public function __construct(){
        $this->_link = Db::getLink();
        $this->_user_id = $_SESSION['user_id'];
    }
    public function index(){
        $limit = 10;
        $offset = 0;

        $events_data = $this->_link->
                                    select('events', ['id', 'event_title', 'creator', 'event_ava', 'event_description', 'date_create', 'date_event', 'event_participants','event_place'])->
                                    selectWHERE('creator', $this->_user_id)->
                                    selectLIMIT($offset, $limit)->
                                    sendSelectQuery();
        while($row = $events_data->fetch_assoc()){
            $creator = $this->_link->select('users', ['login'])->selectWHERE('id', $this->_user_id)->sendSelectQuery()->fetch_assoc();
            $row['creator'] = $creator['login'];

            $this->content['events'][] = $row;
        }

        $this->render('general', 'personal');
    }
}