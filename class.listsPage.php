<?php

	class todo{

		public $name=null;
		public $id=null;
		public $Tcolor=null;
		public $Bcolor=null;
		public $archived=null;
		public $dueDate=null;
		public $reminder=null;
		public $severity=null;

		public $items=null;

		private $db=null;
		private $table=null;

		public function __construct(
								db $db=null,
								string $Tcolor=null,
								string $Bcolor=null,
								string $name=null,
								int $id=null,
								bool $archived=null){
			if(isset($db)){
				$this->db = $db;
			}else{
				$this->db = new db();
			}
			$this->table = "lists";
			$this->Tcolor = $Tcolor;
			$this->Bcolor = $Bcolor;
			$this->name = $name;
			$this->id = $id;
			$this->archived = $archived;
			/* generate list of items */
			$res = $this->db->select("list_items", array("name", "id", "checked"), "list=" . $this->id);
			$this->items = array();
			foreach ($res as $i => $row) {
				$this->items[] = new listItem($db, $this, $row["name"], (int) $row["id"], (bool) $row["checked"]);
			}
		}
	}

	class listItem{
		public $list=null;
		public $name=null;
		public $id=null;
		public $checked=null;
		public $dueDate=null;
		public $reminder=null;
		public $severity=null;

		private $db=null;
		private $table=null;

		public function __construct(
								db $db=null,
								todo $list=null,
								string $name=null,
								int $id=null,
								bool $checked=null){
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
				$values["Name"] = "'" . $this->name . "'";
			}
			if(isset($this->checked)){
				$values["Checked"] = ($checked ? 1: 0);
			}
			$db->update(
				$this->table,
				$values,
				$this->id
			);

		}
	}

	

	class listsPage extends Page{

		public $lists = null;
		protected $db=null;

		public function __construct(){
	        $this->name = "Lists";
	        $this->pageId = "Lists";
	        $this->svgIcon = "img/svg/list.svg";
	        $this->db = new db();
	    }

	    public function renderLists(): string{
			$rows = array();
			$engine = new templateEngine();
			foreach ($this->lists as $i => $todoList) {
				$iChecked = 0;
				$iTotal = 0;
				foreach ($todoList->items as $item) {
					$iTotal++;
					if($item->checked) $iChecked++;
				}
				$rows[] = $engine->render("list.all.row", Array("name" => $todoList->name, "Bcolor" => $todoList->Bcolor, "Tcolor" => "White", "itemsChecked" => $iChecked, "itemsTotal" => $iTotal));
			}
			return $engine->render("list.all", array("items" => implode("", $rows)));
		}

		public function displayListOfLists(bool $archived): string{
			$res = $this->db->select("list", array("name", "id", "Bcolor", "Tcolor"), $archived ? "Archived=true" : "Archived=false");
			$this->lists = array();
			foreach ($res as $i => $row) {
				$this->lists[] = new todo($this->db, $row["Tcolor"], $row["Bcolor"], $row["name"], (int) $row["id"]);
			}
			return $this->renderLists();
		}

		public function displayList(int $id = null): string{
			if(!isset($id)) return $this->displayListOfLists(false);
			$res = $this->db->select("list", array("name", "Bcolor", "Tcolor"), "id=" . $id);
			$this->lists = array();
			foreach ($res as $i => $row) {
				$this->lists[] = new todo($this->db, $row["Tcolor"], $row["Bcolor"], $row["name"], (int) $row["id"]);
			}
			return "";
		}

		public function process(array $getParams, array $postParams): string{
			return $this->displayListOfLists(false);
		}
	}
	
?>
