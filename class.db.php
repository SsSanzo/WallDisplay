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

		public function select(string $table, array $values, string $condition){
			if(!isset($table) or strlen($table)==0){
				$this->errormsg = "db:select() argument $table is null or empty";
				return false;
			}
			if(!isset($values) or count($values)==0){
				$this->errormsg = "db:select() argument $values is null or empty";
				return false;
			}
			$query = "SELECT " . implode(",", $values) . " FROM " . $table;
			if(isset($condition) and strlen($condition)>0){
				$query .= " WHERE ". $condition;
			}
			$result = $this->connection->query($query);
			if(!$result){
				$this->errormsg = "db:select() select error:". $query;
				return false;
			}
			$this->errormsg = "";
			return $result->fetch_all(MYSQLI_ASSOC);
		}

		public function insert(string $table, array $values){
			if(!isset($table) or strlen($table)==0){
				$this->errormsg = "db:select() argument $table is null or empty";
				return false;
			}
			if(!isset($values) or count($values)==0){
				$this->errormsg = "db:select() argument $values is null or empty";
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

			$result = $this->connection->query($query);
			if(!$result){
				$this->errormsg = "db:select() insert error:". $query;
				return false;
			}
			$this->errormsg = "";
			return true;
		}

		public function updateAll(string $table, array $values, string $condition){
			if(!isset($table) or strlen($table)==0){
				$this->errormsg = "db:select() argument $table is null or empty";
				return false;
			}
			if(!isset($values) or count($values)==0){
				$this->errormsg = "db:select() argument $values is null or empty";
				return false;
			}
			if(!isset($condition) or strlen($condition)==0){
				$this->errormsg = "db:select() argument $condition is null or empty";
				return false;
			}
			$query = "UPDATE " . $table;
			$updtValues = array();
			foreach ($values as $key => $value) {
				array_push($updtValues, $key."=".$value);
			}
			$query .= " SET " . implode(",", $updtValues);
			$query .= " WHERE " . $condition;

			$result = $this->connection->query($query);
			if(!$result){
				$this->errormsg = "db:select() updateAll error:". $query;
				return $result;
			}
			$this->errormsg = "";
			return $result;
		}

		public function update(string $table, array $values, int $id){
			if(!isset($table) or strlen($table)==0){
				$this->errormsg = "db:select() argument $table is null or empty";
				return false;
			}
			if(!isset($values) or count($values)==0){
				$this->errormsg = "db:select() argument $values is null or empty";
				return false;
			}
			if(!isset($id) or $id<1){
				$this->errormsg = "db:select() argument $id is null or empty";
				return false;
			}
			$query = "UPDATE " . $table;
			$updtValues = array();
			foreach ($values as $key => $value) {
				array_push($updtValues, $key."=".$value);
			}
			$query .= " SET " . implode(",", $updtValues);
			$query .= " WHERE id=" . $id;

			$result = $this->connection->query($query);
			if(!$result){
				$this->errormsg = "db:select() update error:". $query;
				return $result;
			}
			$this->errormsg = "";
			return $result;
		}

		public function deleteAll(string $table, string $condition){
			if(!isset($table) or strlen($table)==0){
				$this->errormsg = "db:select() argument $table is null or empty";
				return false;
			}
			if(!isset($condition) or strlen($condition)==0){
				$this->errormsg = "db:select() argument $condition is null or empty";
				return false;
			}
			$query = "DELETE FROM " . $table . " WHERE ". $condition;
			$result = $this->connection->query($query);
			if(!$result){
				$this->errormsg = "db:select() deleteAll error:". $query;
				return $result;
			}
			$this->errormsg = "";
			return $result;
		}

		public function delete(string $table, int $id){
			if(!isset($table) or strlen($table)==0){
				$this->errormsg = "db:select() argument $table is null or empty";
				return false;
			}
			if(!isset($id) or $id<1){
				$this->errormsg = "db:select() argument $id is null or empty";
				return false;
			}
			$query = "DELETE FROM " . $table . " WHERE id=". $id;
			$result = $this->connection->query($query);
			if(!$result){
				$this->errormsg = "db:select() delete error:". $query;
				return $result;
			}
			$this->errormsg = "";
			return $result;
		}

		public function getInsertId(): int{
			return $this->connection->insert_id;
		}
	}
?>
