<?php
class Pagination{
	protected $_table;
	protected $_link;
	protected $_pages_count;
	protected $_limit;
	protected $_offset;
	protected $_pagination_length = 5;

	public function __construct($table, $limit, $offset=0){
		$this->_link = Db::getLink();
		$this->_table = $table;
		$this->_limit = $limit;
		$this->_offset = $offset;
		$this->_pages_count = $this->getPagesCount();
		if($this->_pages_count < $this->_pagination_length){
			$this->_pagination_length = $this->_pages_count;
		}
	}
	public function showPagination(){
		$pages = $this->getPages();
		if(count($pages) > 1){
			$active = $this->_offset;
			include_once '/layouts/templates/pagination.tpl';
		}
	}
	protected function getFirstPage(){
		if($this->_offset > 1 && $this->_offset < $this->_pages_count - floor($this->_pagination_length/2)){
			$f_page = $this->_offset - floor($this->_pagination_length/2);
		}else if($this->_offset >= $this->_pages_count - floor($this->_pagination_length/2)){
			$f_page = $this->_pages_count - $this->_pagination_length;
		}else{
			$f_page = 0;
		}
		return $f_page;
	}
	protected function getPages(){
		$pages = array();
		$first_page = $this->getFirstPage();
		for($i = $first_page; $i < $this->_pagination_length+$first_page; $i++){
			$pages[] = $i;
		}
		return $pages;
	}
	protected function getPagesCount(){
		return ceil($this->_link->setCount($this->_table)->getCount()/$this->_limit);
	}
	public function getPrev(){
		if($this->_offset > 0){
			echo '<li class="pagination-prev"><a href="?offset='.($this->_offset-1).'"></a></li>';
		}else{
			echo '<li class="pagination-prev pagination-disabled"></li>';
		}
	}
	public function getNext(){
		if($this->_offset < $this->_pages_count-1){
			echo '<li class="pagination-next"><a href="?offset='.($this->_offset+1).'"></a></li>';
		}else{
			echo '<li class="pagination-next pagination-disabled"></li>';
		}
	}
}