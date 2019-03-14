<?php
	class DB extends RnD {
		private $conn;
		private $table;
		private $where;
		private $join;
		private $select;
		public function __construct(){
			//parent::__construct($conn);
			$this->conn = mysqli_connect(HOST,USER,PASS,DATABASE) or die(mysql_error());
		}
		public function table($name){
			$this->table = $this->escape_str($name);
			return $this;
		}
		public function escape_str($str){
			return $this->conn->escape_string($str);
		}
		public function select($data){
			if (is_array($data)){
				foreach ($data as $d)
					$this->select .= $d.',';
				$this->select = substr($this->select,0,strlen($this->select) - 1);
			}
			else
				$this->select = $data;	
			return $this;
		}
		public function join($tbl,$join,$type = NULL){
			$type = (empty($type)) ? $type : strtoupper($type);
			if (is_array($tbl) && is_array($join)){
				if (count($tbl) == count($join)){
					for ($i = 0 ; $i < count($tbl); $i++){
						$tbl[$i] = $this->escape_str($tbl[$i]);
						$this->join .= "$type JOIN $tbl[$i] ON $join[$i] "; 
					}
					return $this;
				}
				else
					die('Error on JOIN !!!!');
			}
			else{
				$tbl = $this->escape_str($tbl);
				$this->join .= "$type JOIN $tbl ON $join";
				return $this;
			}
		}
		public function where($data){
			if (!is_array($data))
				$this->where = "WHERE $data";
			else {
				$this->where = "WHERE ";
				while ($A = current($data)){
					if (is_numeric($A))
						$A = intval($A);
					else
						$A = "'".$this->escape_str($A)."'";
					$this->where .= $this->escape_str(key($data))." = $A AND ";
					next($data);
				}
				$this->where = substr($this->where,0,strlen($this->where) - 4);
			}
			return $this;
		}
		public function get(){
			if (empty($this->select))
				$this->select = '*';			
			if (empty($this->table))
				return false;
			else {
				if ($a = $this->conn->query("SELECT $this->select FROM $this->table $this->join $this->where")){
					$data = [];
					while ($d = $a->fetch_object())
						array_push($data,$d);
					return $data;
				}
				else
					die($this->conn->error);
			}
		}
		public function get_array(){
			if (empty($this->select))
				$this->select = '*';			
			if (empty($this->table))
				return false;
			else {
				if ($a = $this->conn->query("SELECT $this->select FROM $this->table $this->join $this->where")){
					$data = [];
					while ($d = $a->fetch_array(MYSQLI_ASSOC))
						array_push($data,$d);
					return $data;
				}
				else
					die($this->conn->error);
			}
		}
		public function insert($data){
			if (empty($this->table) || !is_array($data))
				return false;
			else {
				$column = [];
				$fill = [];
				while ($A = current($data)){
					array_push($column,$this->escape_str(key($data)));
					array_push($fill,$this->escape_str($A));
					next($data);
				}
				$q = "INSERT INTO $this->table (";
				foreach ($column as $c)
					$q .= $c.',';
				$q = substr($q,0,strlen($q) - 1).') VALUES (';
				foreach ($fill as $F)
					$q .= "'$F',";
				$q = substr($q,0,strlen($q) - 1).')';
				$this->conn->query($q);
				if ($this->conn->affected_rows > 0)
					return True;
				else
					die($this->conn->error);				
			}
		}
		public function force_insert($data){
			if (empty($this->table) || !is_array($data))
				return false;
			else {
				$column = [];
				$fill = [];
				while ($A = current($data)){
					array_push($column,$this->escape_str(key($data)));
					array_push($fill,$this->escape_str($A));
					next($data);
				}
				$getCol = explode(',',$this->conn->query("SELECT GROUP_CONCAT(column_name) FROM information_schema.columns WHERE table_schema=database() AND table_name='$this->table'")->fetch_array(MYSQLI_NUM)[0]);
				$newCol = [];
				$newFill = [];
				for ($i = 0; $i < count($column); $i++){
					if (in_array($column[$i],$getCol)){
						array_push($newCol,$column[$i]);
						array_push($newFill,$fill[$i]);
					}
				}
				//print_r($newCol);
				//print_r($newFill);
				$q = "INSERT INTO $this->table (";
				foreach ($newCol as $c)
					$q .= $c.',';
				$q = substr($q,0,strlen($q) - 1).') VALUES (';
				foreach ($newFill as $F)
					$q .= "'$F',";
				$q = substr($q,0,strlen($q) - 1).')';
				$this->conn->query($q);
				if ($this->conn->affected_rows > 0)
					return True;
				else
					die($this->conn->error);
			}
		}
		public function delete(){
			if(empty($this->table))
				return false;
			else {
				$q = "DELETE FROM $this->table $this->where";
				if ($this->conn->query($q))
					return true;
				else
					die($this->conn->error);
			}
		}
		public function update($data){
			$q = "UPDATE $this->table SET ";
			if(!is_array($data))
				die("Parameter data must array");
			else {
				while ($A = current($data)){
					if (is_numeric($A))
						$A = intval($A);
					else
						$A = "'".$this->escape_str($A)."'";	
					$q .= $this->escape_str(key($data))." = $A ,";
					next($data);
				}
				$q = substr($q,0,strlen($q)-1);
			}
			$q .= $this->where;
			if ($this->conn->query($q))
				return true;
			else
				die($this->conn->error);
		}
		public function insert_id(){
			return $this->conn->insert_id;
		}
	}
?>