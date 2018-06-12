<?php

	class listItem{
		public $list=null;
		public $name=null;
		public $id=null;
		public $checked=null;

		private $db=null;

		public function __construct(db $db=null){
			if(isset($db)){
				$this->db = $db;
			}else{
				$this->db = new db();
			}
		}

		public function push(){
			$db = new db();

		}
	}

	class list{

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