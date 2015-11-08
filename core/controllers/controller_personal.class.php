<?php
class Controller_Personal extends Controller{
    public $offset;
    public $limit;
    public $date;

    protected function actionIndex(){
        if(!$this->_auth->getAuthStatus()){
            die('auth falied...');
        }
        $only_my_event = (isset($_GET['my_event']) and $_GET['my_event'] == 'on') ? true : false ;
        $all_event = (isset($_GET['all_event']) and $_GET['all_event'] == 'on') ? true : false ;
        $this->offset = ($this->_url->get('offset')) ? $this->_url->get('offset') : Config::EVENTS_OFFSET;
        $this->limit = ($this->_url->get('limit')) ? $this->_url->get('limit') : Config::EVENTS_LIMIT;
        if($all_event){
            $this->date = (new DateTime('2000-01-01'))->format('Y-m-d');
        }else {
            $this->date = ($this->_url->get('date')) ? $this->_url->get('date') : (new DateTime('now'))->format('Y-m-d');
        }
        if($only_my_event){
            $my_events = $this->_link->select('events', ['id'])->selectWHERE('creator', $_SESSION['user_id'])->sendSelectQuery();
        }else {
            $my_events = $this->_link->select('participants', ['event_id'])->selectWHERE('user_id', $_SESSION['user_id'])->sendSelectQuery();
        }
        if($my_events->num_rows != 0) {
            while ($row = $my_events->fetch_assoc()) {
                $events = $this->_link
                    ->select('events', ['id', 'event_title', 'creator', 'event_cover', 'event_description', 'date_create', 'date_event', 'event_participants','event_place'])
                    ->selectWHERE_AND(['>date_event' => $this->date, 'id'=>($only_my_event) ? $row['id'] : $row['event_id']])
                    ->selectLIMIT($this->offset, $this->limit)
                    ->sendSelectQuery();
                if($events->num_rows != 0){
                    while($event = $events->fetch_assoc()){
                        $this->content['events'][] = new Event($event);
                    }
                }
            }
        }

        $this->pagination = new Pagination('events', $this->limit, $this->offset);
        $this->page_data['title'] = 'My Events';
        $this->render('main');
    }
}