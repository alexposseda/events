<?php
	class Db{
		protected static $link = null;
		private $_db;
		protected $_sql;

		private function __construct(){
			$this->_db = new mysqli(Config::DB_HOST, Config::DB_USER, Config::DB_PASSWORD, Config::DB_NAME);
			if($this->_db->connect_errno) die('DataBase connection falied');
			$this->_db->set_charset('utf8');
		}
		public static function getLink(){
			if(is_null(self::$link)){
				self::$link = new Db();
			}
			return self::$link;
		}
        public function start(){
			$this->_db->autocommit(true);
            $sql = 'START TRANSACTION;';
			$this->_db->query($sql);
//            echo $sql.'<br>';
        }
        public function commit(){
            $sql = 'COMMIT;';
			$this->_db->query($sql);
//            echo $sql.'<br>';
        }
        public function rollback(){
            $sql = 'ROLLBACK;';
			$this->_db->query($sql);
//            echo $sql.'<br>';
        }
		public function getError(){
			return $this->_db->error;
		}
		public function sendSelectQuery(){
//			echo $this->_sql.'<br>';
			$result = $this->_db->query($this->_sql);
			$this->clearSql();
			return $result;
		}
		public function sendInsertQuery(){
//            echo $this->_sql;
			$result = $this->_db->query($this->_sql);
			$this->clearSql();
			return $result;
		}
		public function getInsertId(){
			return $this->_db->insert_id;
		}
		public function setCount($table){
			$this->_sql.= "SELECT COUNT(*) FROM `".Config::DB_PREFIX.$table."` ";
			return $this;
		}
		public function getCount(){
			$result = $this->_db->query($this->_sql)->fetch_assoc();
			$this->clearSql();
			return $result['COUNT(*)'];
		}
		protected function getSelfData($data){
			if(is_array($data)){
				foreach($data as $key=>$val){
					$data[$key] = "'".$this->_db->real_escape_string($val)."'";
				}
			}else{
				$data = "'".$this->_db->real_escape_string($data)."'";
			}
			return $data;
		}
		protected function getSelfFields($fields){
			if(is_array($fields)){
				foreach($fields as $key=>$val){
					$fields[$key] = '`'.$this->_db->real_escape_string($val).'`';
				}
			}else{
				$fields = '`'.$this->_db->real_escape_string($fields).'`';
			}
			return $fields;
		}
		protected function clearSql(){
			$this->_sql = '';
		}
		public function select($table, $fields = false){
			if($fields){
				$this->_sql = "SELECT ";
				$fields = $this->getSelfFields($fields);
				$this->_sql .= implode(',', $fields);
				$this->_sql .= ' FROM `'.Config::DB_PREFIX.$table.'` ';
			}else{
				$this->_sql = "SELECT * FROM `".Config::DB_PREFIX.$table."` ";
			}
			return $this;
		}
		public function selectWHERE($field, $param){
			$this->_sql .= 'WHERE `'.$this->_db->real_escape_string($field).'` = '.$this->getSelfData($param).' ';
			return $this;
		}
		public function selectWHERE_AND($params){
            $this->_sql .= 'WHERE ';
            foreach($params as $field=>$value){
				if($field[0] == '-'){
					$field = substr($field, 1);
					$this->_sql .= '`' . $this->_db->real_escape_string($field) . '` != ' . $this->getSelfData($value) . ' AND ';
				}elseif($field[0] == '>'){
					$field = substr($field, 1);
					$this->_sql .= '`' . $this->_db->real_escape_string($field) . '` >= ' . $this->getSelfData($value) . ' AND ';
				}elseif($field[0] == '<'){
					$field = substr($field, 1);
					$this->_sql .= '`' . $this->_db->real_escape_string($field) . '` <= ' . $this->getSelfData($value) . ' AND ';
				}else {
					$this->_sql .= '`' . $this->_db->real_escape_string($field) . '` = ' . $this->getSelfData($value) . ' AND ';
				}
            }
			$this->_sql = substr($this->_sql, 0, -4);
			return $this;
		}

		public function selectWHERE_OR($params, $where = false){
			if($where) {
				$this->_sql .= 'WHERE ';
			}else{
				$this->_sql .= 'OR ';
			}
			foreach($params as $field=>$value){
				if($field[0] == '-'){
					$field = substr($field, 1);
					$this->_sql .= '`' . $this->_db->real_escape_string($field) . '` != ' . $this->getSelfData($value) . ' OR ';
				}else {
					$this->_sql .= '`' . $this->_db->real_escape_string($field) . '` = ' . $this->getSelfData($value) . ' OR ';
				}
			}
			$this->_sql = substr($this->_sql, 0, -3);
			return $this;
		}
		public function selectLIMIT($offset, $length){
			$this->_sql .= 'LIMIT '.(int)$offset.', '.(int)$length.' ';
			return $this;
		}
		public function selectORDER($field, $order=true){
			if($order){
				$order = 'ASC';
			}else{
				$order = 'DESC';
			}
			$this->_sql .= ' ORDER BY `'.$this->_db->real_escape_string($field).'` '.$order.' ';
			return $this;
		}
		public function insertRow($table, $data){
			$fields = $this->getSelfFields(array_keys($data));
			$values = $this->getSelfData($data);
			$this->_sql .= "INSERT INTO `".Config::DB_PREFIX.$table."` (".implode(',', $fields).") VALUES (".implode(',', $values).")";
			return $this;
		}
		public function updateRow($table, $field, $field_data, $id){
			$this->_sql = "UPDATE `".Config::DB_PREFIX.$this->_db->real_escape_string($table)."` SET `".$this->_db->real_escape_string($field)."`= '".$this->_db->real_escape_string($field_data)."' WHERE `id` = '".$this->_db->real_escape_string($id)."'";
			return $this;
		}
		public function update($table, $data, $id){
			$update_data = '';
			foreach($data as $field=>$value){
				$update_data .= "`".$this->_db->real_escape_string($field)."`='".$this->_db->real_escape_string($value)."',";
			}
			$update_data = substr($update_data, 0, -1);
			$this->_sql = "UPDATE `".Config::DB_PREFIX.$this->_db->real_escape_string($table)."` SET ".$update_data." WHERE `id` = '".$this->_db->real_escape_string($id)."'";
			return $this;
		}
		public function delete($table, $id){
			$this->_sql = 'DELETE FROM `'.Config::DB_PREFIX.$this->_db->real_escape_string($table).'` WHERE `id` = '.$this->_db->real_escape_string($id);
			return $this;
		}

		public function deleteRows($table, $field, $data){
			$this->_sql = 'DELETE FROM `'.Config::DB_PREFIX.$this->_db->real_escape_string($table).'` WHERE `'.$this->_db->real_escape_string($field).'` = '.$this->_db->real_escape_string($data);
			return $this;
		}
	}