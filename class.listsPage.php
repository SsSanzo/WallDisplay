<?php

	class listItem{
		public $list=null;
		public $name=null;
		public $id=null;
		public $checked=null;

		private $db=null;
		private $table=null;

		public function __construct(
								db $db=null,
								list $list=null,
								string $name=null,
								int $id=null,
								boolean $checked=null){
			if(isset($db)){
				$this->db = $db;
			}else{
				$this->db = new db();
			}
			$this->table = "list_items";
			$this->list = $list;
			$this->name = $name;
			$this->id = $id;
			$this->checked = $checked;
		}

		public function push(){
			$values = array();
			if(isset($this->name)){
				array_push($values, "Name" => "'" . $this->name . "'");
			}
			if(isset($this->checked)){
				array_push($values, "Checked" => ($checked ? 1, 0));
			}
			$db->update(
				$this->table,
				$values,
				$this->id
			);

		}
	}

	class list{

		public $name=null;
		public $id=null;
		public $color=null;
		public $archived=null;

		public $items=null;

		private $db=null;
		private $table=null;

		public function __construct(
								db $db=null,
								string $color=null,
								string $name=null,
								int $id=null,
								boolean $archived=null){
			if(isset($db)){
				$this->db = $db;
			}else{
				$this->db = new db();
			}
			$this->table = "lists";
			$this->color = $color;
			$this->name = $name;
			$this->id = $id;
			$this->archived = $archived;
			/* generate list of items */
		}
	}

	class listsPage extends Page{

		public function __construct(){
	        $this->name = "Lists";
	        $this->pageId = "Lists";
	        $this->svgIcon = "img/svg/list.svg";
	    }

		public function process(array $getParams, array $postParams): string{
			return "";
		}
	}
	
?>