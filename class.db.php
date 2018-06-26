<?php

	class db{
		private $url=null;
		private $username=null;
		private $password=null;
		private $dbname=null;
		private $connection=null;

		public $errormsg=null;


		public function __construct(){
			$this->url = "127.0.0.1";
			$this->username = "root";
			$this->password = "45FZG3wwX";
			$this->dbname = "walldisplay";

			$this->connection = new mysqli($this->url, $this->username, $this->password, $this->dbname);
			if($this->connection->connect_error){
				echo "db:__construct() Could not connect to database";
			}
		}

		public function __destruct (){
			$this->connection->close();
		}

		public function select(string $table, array $values, string $condition=null, string $orderBy=null){
			if(!isset($table) or strlen($table)==0){
				$this->errormsg = "argument $table is null or empty";
				logger::logError($this->errormsg, "select", "db");
				return false;
			}
			if(!isset($values) or count($values)==0){
				$this->errormsg = "argument $values is null or empty";
				logger::logError($this->errormsg, "select", "db");
				return false;
			}
			$query = "SELECT " . implode(",", $values) . " FROM " . $table;
			if(isset($condition) and strlen($condition)>0){
				$query .= " WHERE ". $condition;
			}
			if(isset($orderBy) and strlen($orderBy)>0){
				$query .= " ORDER BY ". $orderBy;
			}
			logger::logDB($query, "Select");
			$result = $this->connection->query($query);
			if(!$result){
				$this->errormsg = "select error:". $query;
				logger::logError($this->errormsg, "select", "db");
				return false;
			}
			$this->errormsg = "";
			return $result->fetch_all(MYSQLI_ASSOC);
		}

		public function insert(string $table, array $values){
			if(!isset($table) or strlen($table)==0){
				$this->errormsg = "argument $table is null or empty";
				logger::logError($this->errormsg, "insert", "db");
				return false;
			}
			if(!isset($values) or count($values)==0){
				$this->errormsg = "argument $values is null or empty";
				logger::logError($this->errormsg, "select", "db");
				return false;
			}
			$query = "INSERT INTO " . $table;
			$cols = array();
			$vals = array();

			foreach ($values as $key => $value) {
				array_push($cols, $key);
				array_push($vals, $value);
			}

			$query .= " (" . implode(",", $cols) . ")";
			$query .= " VALUES (" . implode(",", $vals) . ")";

			logger::logDB($query, "insert");
			$result = $this->connection->query($query);
			if(!$result){
				$this->errormsg = "insert error:". $query;
				logger::logError($this->errormsg, "insert", "db");
				return false;
			}
			$this->errormsg = "";
			return true;
		}

		public function updateAll(string $table, array $values, string $condition){
			if(!isset($table) or strlen($table)==0){
				$this->errormsg = "argument $table is null or empty";
				logger::logError($this->errormsg, "updateAll", "db");
				return false;
			}
			if(!isset($values) or count($values)==0){
				$this->errormsg = "argument $values is null or empty";
				logger::logError($this->errormsg, "updateAll", "db");
				return false;
			}
			if(!isset($condition) or strlen($condition)==0){
				$this->errormsg = "argument $condition is null or empty";
				logger::logError($this->errormsg, "updateAll", "db");
				return false;
			}
			$query = "UPDATE " . $table;
			$updtValues = array();
			foreach ($values as $key => $value) {
				array_push($updtValues, $key."=".$value);
			}
			$query .= " SET " . implode(",", $updtValues);
			$query .= " WHERE " . $condition;

			logger::logDB($query, "updateAll");
			$result = $this->connection->query($query);
			if(!$result){
				$this->errormsg = "updateAll error:". $query;
				logger::logError($this->errormsg, "updateAll", "db");
				return $result;
			}
			$this->errormsg = "";
			return $result;
		}

		public function update(string $table, array $values, int $id){
			if(!isset($table) or strlen($table)==0){
				$this->errormsg = "argument $table is null or empty";
				logger::logError($this->errormsg, "update", "db");
				return false;
			}
			if(!isset($values) or count($values)==0){
				$this->errormsg = "argument $values is null or empty";
				logger::logError($this->errormsg, "update", "db");
				return false;
			}
			if(!isset($id) or $id<1){
				$this->errormsg = "argument $id is null or empty";
				logger::logError($this->errormsg, "update", "db");
				return false;
			}
			$query = "UPDATE " . $table;
			$updtValues = array();
			foreach ($values as $key => $value) {
				array_push($updtValues, $key."=".$value);
			}
			$query .= " SET " . implode(",", $updtValues);
			$query .= " WHERE id=" . $id;

			logger::logDB($query, "update");
			$result = $this->connection->query($query);
			if(!$result){
				$this->errormsg = "update error:". $query;
				logger::logError($this->errormsg, "update", "db");
				return $result;
			}
			$this->errormsg = "";
			return $result;
		}

		public function deleteAll(string $table, string $condition){
			if(!isset($table) or strlen($table)==0){
				$this->errormsg = "argument $table is null or empty";
				logger::logError($this->errormsg, "deleteAll", "db");
				return false;
			}
			if(!isset($condition) or strlen($condition)==0){
				$this->errormsg = "argument $condition is null or empty";
				logger::logError($this->errormsg, "deleteAll", "db");
				return false;
			}
			$query = "DELETE FROM " . $table . " WHERE ". $condition;

			logger::logDB($query, "deleteAll");
			$result = $this->connection->query($query);
			if(!$result){
				$this->errormsg = "deleteAll error:". $query;
				logger::logError($this->errormsg, "deleteAll", "db");
				return $result;
			}
			$this->errormsg = "";
			return $result;
		}

		public function delete(string $table, int $id){
			if(!isset($table) or strlen($table)==0){
				$this->errormsg = "argument $table is null or empty";
				logger::logError($this->errormsg, "delete", "db");
				return false;
			}
			if(!isset($id) or $id<1){
				$this->errormsg = "argument $id is null or empty";
				logger::logError($this->errormsg, "delete", "db");
				return false;
			}
			$query = "DELETE FROM " . $table . " WHERE id=". $id;

			logger::logDB($query, "delete");
			$result = $this->connection->query($query);
			if(!$result){
				$this->errormsg = "delete error:". $query;
				logger::logError($this->errormsg, "delete", "db");
				return $result;
			}
			$this->errormsg = "";
			return $result;
		}

		public function getInsertId(): int{
			return $this->connection->insert_id;
		}

		public function protectString(string $value = null){
			if(isset($value)){
				$value = filter_var($value, FILTER_SANITIZE_STRING);
				$value = $this->connection->real_escape_string($value);
			}
			return $value;
		}
	}
?>
