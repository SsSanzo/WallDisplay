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
		public static $table = "list";

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
			$this->Tcolor = $Tcolor;
			$this->Bcolor = $Bcolor;
			$this->name = $name;
			$this->id = $id;
			$this->archived = $archived;
			/* generate list of items */
			$res = $this->db->select(listItem::$table, array("name", "id", "checked"), "list=" . $this->id);
			if(is_bool($res)){
				var_dump($this->db->errormsg);
				$errmsg = "Could not get list of lists";
				error_log("[".date("c")."] todo:__construct=".$errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
				return "Error";
			}
			$this->items = array();
			foreach ($res as $i => $row) {
				$this->items[] = new listItem($db, $this, $row["name"], (int) $row["id"], (bool) $row["checked"]);
			}
		}

		public function load(): bool{
			$res = $this->db->select(self::$table, array("name", "id", "Bcolor", "Tcolor", "Archived"), "id=" . $this->id);
			if(is_bool($res)){
				var_dump($this->db->errormsg);
				$errmsg = "Could not get list of lists";
				error_log("[".date("c")."] todo:load=".$errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
				return false;
			}
			if(count($res)<1){
				$errmsg = "Could not find list";
				error_log("[".date("c")."] todo:load=".$errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
				return false;
			}
			$list = $res[0];
			$this->Tcolor = $list["Tcolor"];
			$this->Bcolor = $list["Bcolor"];
			$this->name = $list["name"];
			$this->id = $list["id"];
			$this->archived = $list["Archived"];
			return true;
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
		public static $table = "list_items";

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
			if(!$this->db->update(
				self::$table,
				$values,
				$this->id
			)){
				var_dump($this->db->errormsg);
				$errmsg = "Could not get insert list item";
				error_log("[".date("c")."] listItem:push=".$errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
			}

		}

		public function insert(){
			$values = array();
			$values["Name"] = "'" . $this->name . "'";
			$values["list"] = "'" . $this->list->id . "'";
			if($this->db->insert(
				self::$table,
				$values
			)){
				$this->id = $this->db->getInsertId();
				$this->checked = false;
				return true;
			}else{
				var_dump($this->db->errormsg);
				$errmsg = "Could not get insert list item";
				error_log("[".date("c")."] listItem:insert=".$errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
				return false;
			}
		}

		public function load(todo $list = null){
			$res = $this->db->select(self::$table, array("name", "id", "checked, list"), "id=" . $this->id);
			if(count($res)<1) return false;
			$item = $res[0];
			if(isset($list)){
				$this->list = $list;
			}else{
				$list = new todo($this->db, null, null, null, $item["list"], null);
				$list->load();
				$this->list = $list;
			}
			$this->name = $item["name"];
			$this->id = $item["id"];
			$this->checked = $item["checked"];
			return true;
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
			$res = $this->db->select(todo::$table, array("name", "id", "Bcolor", "Tcolor"), $archived ? "Archived=true" : "Archived=false");
			if(is_bool($res)){
				var_dump($this->db->errormsg);
				$errmsg = "Could not get list of lists";
				error_log("[".date("c")."] listePage:displayListOfLists=".$errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
				return "Error";
			}
			$this->lists = array();
			foreach ($res as $i => $row) {
				$this->lists[] = new todo($this->db, $row["Tcolor"], $row["Bcolor"], $row["name"], (int) $row["id"]);
			}
			return $this->renderLists();
		}

		public function displayList(int $id = null): string{
			if(!isset($id)) return $this->displayListOfLists(false);
			$res = $this->db->select(todo::$table, array("name", "Bcolor", "Tcolor", "id"), "id=" . $id);
			if(is_bool($res)){
				var_dump($this->db->errormsg);
				return "Error";
			}
			$this->lists = array();
			foreach ($res as $i => $row) {
				$this->lists[] = new todo($this->db, $row["Tcolor"], $row["Bcolor"], $row["name"], (int) $row["id"]);
			}
			return $this->renderSingleLists();
		}

		public function updateItem(int $id, bool $checked=null, string $name = null){
			if(!isset($id)) return;
			$name = $this->db->protectString($name);
			$item = new listItem(null, null, $name, $id, $checked);
			$item->push();
		}

		public function process(array $getParams, array $postParams): string{
			if(isset($getParams["aQuery"])){
				if(strcmp("updateItem", $getParams["aQuery"]) == 0){
					if(!isset($getParams["id"])){
						$errmsg = "Error: id missing";
						error_log("[".date("c")."] listePage:process=".$errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
						return $errmsg;
					}
					$this->updateItem($getParams["id"], $getParams["checked"] ?? null, $getParams["name"] ?? null);
					return "OK";
				}
				if(strcmp("addItem", $getParams["aQuery"]) == 0){
					if(!isset($getParams["name"]) || !isset($getParams["id"])){
						$errmsg = "Error: name or id missing";
						error_log("[".date("c")."] listePage:process=".$errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
						return $errmsg;
					}
					$engine = new templateEngine();
					$db = new db();
					$list = new todo($db, null, null, null, (int) $getParams["id"], null);
					$item = new listItem($db, $list, $this->db->protectString($getParams["name"]), null, null);
					$item->insert();
					return $engine->render("list.one.item", Array(
						"id" => $item->id,
						"name" => $item->name,
						"checked" => $item->checked ? "checked" : "")
					);
				}
				if(strcmp("addList", $getParams["aQuery"]) == 0){
					// adding a list
					return "OK";
				}
				if(strcmp("purgeList", $getParams["aQuery"]) == 0){
					// purge a list
					$db = new db();
					$listId = (int) $getParams["id"];
					if(isset($listId)){
						$db->deleteAll(listItem::$table, "checked=1 AND list=".$listId);
						if(is_bool($res)){
							var_dump($this->db->errormsg);
							$errmsg = "Could not purge list: " . $this->db->errormsg;
							error_log("[".date("c")."] listePage:process{purgeList}=".$errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
							return "Error";
						}
						return "OK";
					}else{
						$errmsg= "Error, the id is unknown, or couldn't be converted: ".$getParams["id"]."=>".$listId;
						error_log("[".date("c")."] listePage:process=".$errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
						return "Error:".$errmsg;
					}
				}
				if(strcmp("openSettings", $getParams["aQuery"]) == 0){
					// Opening Settings of a list
					return "OK";
				}
				if(strcmp("editList", $getParams["aQuery"]) == 0){
					// editing a list
					return "OK";
				}
				if(strcmp("archiveList", $getParams["aQuery"]) == 0){
					// archiving a list
					$db = new db();
					$listId = (int) $getParams["id"];
					if(isset($listId)){
						if($db->update(todo::$table, array("Archived" => 1), $listId)){
							return "OK";
						}else{
							var_dump($this->db->errormsg);
							$errmsg = "Could not archive list: ". $this->db->errormsg;
							error_log("[".date("c")."] listePage:process{archiveList}=".$errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
							return "Error";
						}
					}else{
						$errmsg= "Error, the id is unknown, or couldn't be converted: ".$getParams["id"]."=>".$listId;
						error_log("[".date("c")."] listePage:process=".$errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
						return "Error:".$errmsg;
					}
				}
				if(strcmp("restoreList", $getParams["aQuery"]) == 0){
					// restore a list
					$db = new db();
					$listId = (int) $getParams["id"];
					if(isset($listId)){
						if($db->update(todo::$table, array("Archived" => 0), $listId)){
							return "OK";
						}else{
							var_dump($this->db->errormsg);
							$errmsg = "Could not restore list". $this->db->errormsg;
							error_log("[".date("c")."] listePage:process{archiveList}=".$errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
							return "Error";
						}
					}else{
						$errmsg= "Error, the id is unknown, or couldn't be converted: ".$getParams["id"]."=>".$listId;
						error_log("[".date("c")."] listePage:process=".$errmsg.PHP_EOL, 3, ERROR_LOG_FILE);
						return "Error:".$errmsg;
					}
				}
			}
			if(isset($getParams["lid"])) return $this->displayList((int) $getParams["lid"]);
			return $this->displayListOfLists(false);
		}
	}
	
?>
