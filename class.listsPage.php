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
								bool $archived=null,
								bool $loadItems = true){
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
			if($loadItems){
				$res = $this->db->select(listItem::$table, array("name", "id", "checked, itemOrder"), "list=" . $this->id);
				if(is_bool($res)){
					var_dump($this->db->errormsg);
					$errmsg = "Could not get list of lists";
					logger::logError($errmsg, "__construct", "todo");
					return "Error";
				}
				$this->items = array();
				foreach ($res as $i => $row) {
					$this->items[] = new listItem($db, $this, $row["name"], (int) $row["id"], (bool) $row["checked"], (int) $row["itemOrder"]);
				}
			}
		}

		public function load(): bool{
			$res = $this->db->select(self::$table, array("name", "id", "Bcolor", "Tcolor", "Archived"), "id=" . $this->id);
			if(is_bool($res)){
				var_dump($this->db->errormsg);
				$errmsg = "Could not get list of lists";
				logger::logError($errmsg, "load", "todo");
				return false;
			}
			if(count($res)<1){
				$errmsg = "Could not find list";
				logger::logError($errmsg, "load", "todo");
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

		public function insert(){
			$values = array();
			if(isset($this->Tcolor)) $values["Tcolor"] = "'" . $this->Tcolor . "'";
			if(isset($this->Bcolor)) $values["Bcolor"] = "'" . $this->Bcolor . "'";
			if(isset($this->name)) $values["name"] = "'" . $this->name . "'";
			if(isset($this->archived)) $values["archived"] = $this->archived ? 1: 0;
			if($this->db->insert(
				self::$table,
				$values
			)){
				$this->id = $this->db->getInsertId();
				if(!isset($this->archived))  $this->archived = false;
				return true;
			}else{
				var_dump($this->db->errormsg);
				$errmsg = "Could not get insert list";
				logger::logError($errmsg, "insert", "todo");
				return false;
			}
		}

		public function push(){
			$values = array();
			if(isset($this->Tcolor)){
				$values["Tcolor"] = "'" . $this->Tcolor . "'";
			}
			if(isset($this->Bcolor)){
				$values["Bcolor"] = "'" . $this->Bcolor . "'";
			}
			if(isset($this->name)){
				$values["name"] = "'" . $this->name . "'";
			}
			if(isset($this->archived)){
				$values["archived"] = $this->archived ? 1: 0;
			}
			if($this->db->update(
				self::$table,
				$values,
				$this->id
			)){
				return true;
			}else{
				var_dump($this->db->errormsg);
				$errmsg = "Could not get push list";
				logger::logError($errmsg, "push", "todo");
				return false;
			}
		}
	}

	class listItem{
		public $list=null;
		public $name=null;
		public $id=null;
		public $checked=null;
		public $itemOrder=null;
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
								bool $checked=null,
								int $itemOrder=null){
			if(isset($db)){
				$this->db = $db;
			}else{
				$this->db = new db();
			}
			$this->list = $list;
			$this->name = $name;
			$this->id = $id;
			$this->checked = $checked;
			$this->itemOrder = $itemOrder;
		}

		public function push(){
			$values = array();
			if(isset($this->name)){
				$values["Name"] = "'" . $this->name . "'";
			}
			if(isset($this->checked)){
				$values["Checked"] = $this->checked ? 1: 0;
			}
			if(isset($this->itemOrder)){
				$values["itemOrder"] = $this->itemOrder;
			}
			if(!$this->db->update(
				self::$table,
				$values,
				$this->id
			)){
				var_dump($this->db->errormsg);
				$errmsg = "Could not get insert list item";
				logger::logError($errmsg, "push", "listItem");
			}

		}

		public function insert(){
			$values = array();
			$values["Name"] = "'" . $this->name . "'";
			$values["list"] = $this->list->id;
			$values["itemOrder"] = $this->itemOrder;
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
				logger::logError($errmsg, "insert", "listItem");
				return false;
			}
		}

		public function load(todo $list = null){
			$res = $this->db->select(self::$table, array("name", "id", "checked, list, itemOrder"), "id=" . $this->id);
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
			$this->itemOrder = $item["itemOrder"];
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
			return $engine->render("list.all", array("pagename" => $this->pageId, "items" => implode("", $rows)));
		}

		public function renderSingleLists(): string{
			if(count($this->lists)<1) return "List not found";
			$rowsUnchecked = array();
			$rowsChecked = array();
			$list = $this->lists[0];
			$engine = new templateEngine();
			$listItemTemplate = "list.one.item";
			$listTemplate = "list.one";
			if($list->archived){
				$listItemTemplate = "list.archived.one.item";
				$listTemplate = "list.archived.one";
			}
			foreach ($list->items as $item) {
				if($item->checked){
					$rowsChecked[] = $engine->render($listItemTemplate, Array("id" => $item->id, "name" => $item->name, "checked" => $item->checked ? "checked" : ""));
				}else{
					$rowsUnchecked[] = $engine->render($listItemTemplate, Array("id" => $item->id, "name" => $item->name, "checked" => $item->checked ? "checked" : ""));
				}				
			}
			return $engine->render($listTemplate, array("id" => $list->id, "Bcolor" => $list->Bcolor, "Tcolor" => $list->Tcolor, "name" => $list->name, "items" => implode("", $rowsUnchecked), "checkedItems" => implode("", $rowsChecked)));
		}

		public function displayListOfLists(bool $archived): string{
			$res = $this->db->select(todo::$table, array("name", "id", "Bcolor", "Tcolor"), $archived ? "Archived=true" : "Archived=false");
			if(is_bool($res)){
				var_dump($this->db->errormsg);
				$errmsg = "Could not get list of lists";
				logger::logError($errmsg, "displayListOfLists", "listePage");
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
			$res = $this->db->select(todo::$table, array("name", "Bcolor", "Tcolor", "Archived", "id"), "id=" . $id);
			if(is_bool($res)){
				var_dump($this->db->errormsg);
				return "Error";
			}
			$this->lists = array();
			foreach ($res as $i => $row) {
				$this->lists[] = new todo($this->db, $row["Tcolor"], $row["Bcolor"], $row["name"], (int) $row["id"], (bool) $row["Archived"]);
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
						logger::logError($errmsg, "process", "listePage");
						return $errmsg;
					}
					$this->updateItem($getParams["id"], $getParams["checked"] ?? null, $getParams["name"] ?? null);
					return "OK";
				}
				if(strcmp("addItem", $getParams["aQuery"]) == 0){
					if(!isset($getParams["name"]) || !isset($getParams["id"])){
						$errmsg = "Error: name or id missing";
						logger::logError($errmsg, "process", "listePage");
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
					if(isset($getParams["aSubQuery"])){
						if(strcmp("add", $getParams["aSubQuery"]) == 0){
							$db = new db();
							$colorRegEx = "/#([0-9]|[a-f]|[A-F]){6}/";

							var_dump($getParams);

							$id = (int) $getParams["id"];
							$name = (string) $getParams["name"];
							$TColor = (string) $getParams["TColor"];
							$BColor = (string) $getParams["BColor"];

							$name = $db->protectString($name);
							if(preg_match($colorRegEx, $TColor) == 0){
								$TColor = "#000000";
							}
							if(preg_match($colorRegEx, $BColor) == 0){
								$BColor = "#ffffff";
							}
							if(strlen($name)<1){
								$name = "New List";
							}

							$list = new todo($db, $TColor, $BColor, $name, null, null, false);
							$list->insert();
							return "OK";
							
						}else if(strcmp("update", $getParams["aSubQuery"]) == 0){
							$db = new db();
							$colorRegEx = "/#([0-9]|[a-f]|[A-F]){6}/";

							var_dump($getParams);

							$id = (int) $getParams["id"];
							$name = (string) $getParams["name"];
							$TColor = (string) $getParams["TColor"];
							$BColor = (string) $getParams["BColor"];

							$name = $db->protectString($name);
							if(preg_match($colorRegEx, $TColor) == 0){
								$TColor = "#000000";
							}
							if(preg_match($colorRegEx, $BColor) == 0){
								$BColor = "#ffffff";
							}
							if(strlen($name)<1){
								$name = "New List";
							}

							$list = new todo($db, $TColor, $BColor, $name, $id, null, false);
							$list->push();
							return "OK";
							
						}else if(strcmp("open", $getParams["aSubQuery"]) == 0){
							$engine = new templateEngine();
							$defaultVal = array(
								"listId" => "null",
								"Name" => "New List",
								"TColor" => "#000000",
								"BColor" => "#ffffff"
							);
							if(isset($getParams["id"])){
								$listId = (int) $getParams["id"];
								$list = new todo(null, null, null, null, $listId, null, false);
								$list->load();
								$defaultVal["listId"] = $list->id;
								$defaultVal["Name"] = $list->name;
								$defaultVal["TColor"] = $list->Tcolor;
								$defaultVal["BColor"] = $list->Bcolor;
							}
							return $engine->render("list.settings", $defaultVal);
						}else{
							$errmsg = "addList: unkown action";
							logger::logError($errmsg, "process", "listePage", "addList");
							return "Error";
						}
					}else{
						$errmsg = "addList: no action defined";
						logger::logError($errmsg, "process", "listePage", "addList");
						return "Error";
					}
					
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
							logger::logError($errmsg, "process", "listePage", "purgeList");
							return "Error";
						}
						return "OK";
					}else{
						$errmsg= "Error, the id is unknown, or couldn't be converted: ".$getParams["id"]."=>".$listId;
						logger::logError($errmsg, "process", "listePage", "purgeList");
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
							logger::logError($errmsg, "process", "listePage", "archiveList");
							return "Error";
						}
					}else{
						$errmsg= "Error, the id is unknown, or couldn't be converted: ".$getParams["id"]."=>".$listId;
						logger::logError($errmsg, "process", "listePage", "archiveList");
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
							logger::logError($errmsg, "process", "listePage", "restoreList");
							return "Error";
						}
					}else{
						$errmsg= "Error, the id is unknown, or couldn't be converted: ".$getParams["id"]."=>".$listId;
						logger::logError($errmsg, "process", "listePage", "restoreList");
						return "Error:".$errmsg;
					}
				}
			}
			if(isset($getParams["lid"])) return $this->displayList((int) $getParams["lid"]);
			if(isset($getParams["sub"])){
				if(strcmp("Archived", $getParams["sub"]) == 0){
					return $this->displayListOfLists(true);
				}else{
					return $this->displayListOfLists(false);		
				}
			}
			return $this->displayListOfLists(false);
		}
	}
	
?>
