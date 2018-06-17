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
				$values["Checked"] = ($this->checked ? 1: 0);
			}
			var_dump($values);
			$this->db->update(
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
				$rows[] = $engine->render("list.all.row", Array("id" => $todoList->id, "name" => $todoList->name, "Bcolor" => $todoList->Bcolor, "Tcolor" => $todoList->Tcolor, "itemsChecked" => $iChecked, "itemsTotal" => $iTotal));
			}
			return $engine->render("list.all", array("items" => implode("", $rows)));
		}

		public function renderSingleLists(): string{
			if(count($this->lists)<1) return "List not found";
			$rowsUnchecked = array();
			$rowsChecked = array();
			$list = $this->lists[0];
			$engine = new templateEngine();
			foreach ($list->items as $item) {
				if($item->checked){
					$rowsChecked[] = $engine->render("list.one.item", Array("id" => $item->id, "name" => $item->name, "checked" => $item->checked ? "checked" : ""));
				}else{
					$rowsUnchecked[] = $engine->render("list.one.item", Array("id" => $item->id, "name" => $item->name, "checked" => $item->checked ? "checked" : ""));
				}				
			}
			return $engine->render("list.one", array("id" => $list->id, "Bcolor" => $list->Bcolor, "Tcolor" => $list->Tcolor, "name" => $list->name, "items" => implode("", $rowsUnchecked), "checkedItems" => implode("", $rowsChecked)));
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
			$res = $this->db->select("list", array("name", "Bcolor", "Tcolor", "id"), "id=" . $id);
			$this->lists = array();
			foreach ($res as $i => $row) {
				$this->lists[] = new todo($this->db, $row["Tcolor"], $row["Bcolor"], $row["name"], (int) $row["id"]);
			}
			return $this->renderSingleLists();
		}

		public function updateItem(int $id, bool $checked=null, string $name = null){
			if(!isset($id)) return;
			$item = new listItem(null, null, $name, $id, $checked);
			$item->push();
		}

		public function process(array $getParams, array $postParams): string{
			if(isset($getParams["aQuery"])){
				if(strcmp("updateItem", $getParams["aQuery"]) == 0){
					$this->updateItem($getParams["id"], $getParams["checked"] ?? null, $getParams["name"] ?? null);
				}
				if(strcmp("addItem", $getParams["aQuery"]) == 0){
					// adding an item
				}
				if(strcmp("addList", $getParams["aQuery"]) == 0){
					// adding a list
				}
				if(strcmp("editList", $getParams["aQuery"]) == 0){
					// editing a list
				}
				if(strcmp("archiveList", $getParams["aQuery"]) == 0){
					// archiving a list
				}
			}
			if(isset($getParams["lid"])) return $this->displayList((int) $getParams["lid"]);
			return $this->displayListOfLists(false);
		}
	}
	
?>
