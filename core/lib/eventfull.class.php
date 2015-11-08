<?php
class Eventfull extends Event{
    protected $_template = 'layouts/templates/event_full.tpl';
    protected $_participants;
    public function __construct($data){
        parent::__construct($data);

        $participants = $this->_link->select('participants', ['user'])->selectWHERE('event_id', $this->id)->sendSelectQuery();
        if($participants->num_rows > 0){
            while($row = $participants->fetch_assoc()){
                $this->_participants[] = $row['user'];
            }
        }
    }

    public function getParticipants(){
        return $this->_participants;
    }
}