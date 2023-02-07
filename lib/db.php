<?php 
class DB{

	private $__conn;

	function connect(){

		$host = 'db';
		$username = 'root';
		$password = '123456';
		$dbname = 'vtnn58';
		
		// $host = 'localhost';
		// $username = 'httragop_vtnn58';
		// $password = 'dRaooqxAbp';
		// $dbname = 'httragop_vtnn58';

		$this->__conn = mysqli_connect($host, $username, $password, $dbname);

		if ($this->__conn->connect_error) {
			die("Connection failed: " . $this->__conn->connect_error);
		}else{
			mysqli_query($this->__conn, "SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
		}	
	}

	function select($table, $condition = '', $order_condition = '', $select = '*'){

        $where = '';
		if($condition){
			foreach ($condition as $k => $v) {
				$where .= " AND ".$k."='".$v."'";
			}
		}

		$order_by = '';
		if($order_condition){
			$order_by = 'ORDER BY';
			foreach ($order_condition as $k => $v) {
				$order_by .= " ".$k." ".$v.",";
			}
		}
		$order_by = trim($order_by,',');

		// Câu truy vấn
        $sql = "SELECT ".$select." FROM `".$table."` WHERE 1 ".$where.' '.$order_by;
        
        // echo '<pre>';
        // print_r($sql);
        // die;
        	
        
        // Kết nối
        $this->connect();

        $result = mysqli_query($this->__conn, $sql);

        if (!$result){
            die ('Câu truy vấn bị sai');
        }

        $data = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$data[] = $row;
		}

		// Xóa kết quả khỏi bộ nhớ
        mysqli_free_result($result);

		return $data;
	}

	function select_like($table, $condition = '', $select = '*'){

        $where = '';
		if($condition){
			foreach ($condition as $k => $v) {
				$where .= " AND ".$k." like '%".$v."%'";
			}
		}

		// Câu truy vấn
        $sql = "SELECT ".$select." FROM `".$table."` WHERE 1 ".$where; 
        
        // Kết nối
        $this->connect();

        $result = mysqli_query($this->__conn, $sql);

        if (!$result){
            die ('Câu truy vấn bị sai');
        }

        $data = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$data[] = $row;
		}

		// Xóa kết quả khỏi bộ nhớ
        mysqli_free_result($result);

		return $data;
	}
	
	function select_search($table, $condition = '', $order_condition = '', $select = '*'){

        $where = '';
		if($condition){
			$where .= ' AND (';
			foreach ($condition as $k => $v) {
				$where .= $k." like '%".$v."%' OR ";					
			}
			$where = trim($where,'OR ');
			$where .= ')';
		}

		$order_by = '';
		if($order_condition){
			$order_by = 'ORDER BY';
			foreach ($order_condition as $k => $v) {
				$order_by .= " ".$k." ".$v.",";
			}
		}
		$order_by = trim($order_by,',');

		// Câu truy vấn
        $sql = "SELECT ".$select." FROM `".$table."` WHERE 1 ".$where.' '.$order_by;
        	
        
        // Kết nối
        $this->connect();

        $result = mysqli_query($this->__conn, $sql);

        if (!$result){
            die ('Câu truy vấn bị sai');
        }

        $data = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$data[] = $row;
		}

		// Xóa kết quả khỏi bộ nhớ
        mysqli_free_result($result);

		return $data;
	}

	function select_in($table, $condition = '', $order_condition = '', $select = '*'){

    	$where = '';
		if($condition){
			foreach ($condition as $k => $v) {
				$where .= " AND ".$k." IN (".$v.")";
			}
		}

		$order_by = '';
		if($order_condition){
			$order_by = 'ORDER BY';
			foreach ($order_condition as $k => $v) {
				$order_by .= " ".$k." ".$v.",";
			}
		}
		$order_by = trim($order_by,',');

		// Câu truy vấn
        $sql = "SELECT ".$select." FROM `".$table."` WHERE 1 ".$where.' '.$order_by;
        
        // echo '<pre>';
        // print_r($sql);
        // die;
        	
        
        // Kết nối
        $this->connect();
        
        $result = mysqli_query($this->__conn, $sql);
        
        if (!$result){
            die ('Câu truy vấn bị sai');
        }

        $data = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$data[] = $row;
		}

		// Xóa kết quả khỏi bộ nhớ
        mysqli_free_result($result);

		return $data;
	}

	function raw_query($query){

        // Kết nối
        $this->connect();
        
        $result = mysqli_query($this->__conn, $query);
        
		if (!$result){
			die('Error: ' . mysqli_error($this->__conn));
		}

        $data = array();
		while ($row = mysqli_fetch_assoc($result)) {
			$data[] = $row;
		}

		// Xóa kết quả khỏi bộ nhớ
        mysqli_free_result($result);

		return $data;
	}

	public function insert($table, $data){

		// Kết nối
        $this->connect();

		// Lưu trữ danh sách field
        $field_list = '';
        // Lưu trữ danh sách giá trị tương ứng với field
        $value_list = '';

        mysqli_query($this->__conn, "SET NAMES 'utf8'"); 

		foreach ($data as $k => $v) {
			$field_list .= $k.','; 
			$value_list .= "'".$v."',";
		}

		$field_list = trim($field_list,',');
		$value_list = trim($value_list,',');
		
		$sql = "INSERT INTO ".$table."(".$field_list.") VALUES(".$value_list.")";

		// echo '<pre>';
		// print_r($sql);
		// die;
		
		if(mysqli_query($this->__conn,$sql)){	
			// $last_id = mysqli_insert_id($this->connect());
			$last_id = $this->__conn->insert_id;
		
			return $last_id;
		}
		return 0;
	}

	// Hàm Update
    function update($table, $data, $condition)
    {
        // Kết nối
        $this->connect();

        $where = '';
		if($condition){
			foreach ($condition as $k => $v) {
				if(!is_numeric($v)){
					$where .= " AND ".$k."='".$v."'";	
				}else{
					$where .= ' AND '.$k.'='.$v;	
				}
			}
		}

        $sql = '';
        // Lặp qua data
        foreach ($data as $key => $value){
            $sql .= "$key = '".mysqli_real_escape_string($this->__conn,$value)."',";
        }
 
        // Vì sau vòng lặp biến $sql sẽ thừa một dấu , nên ta sẽ dùng hàm trim để xóa đi
        $sql = 'UPDATE '.$table. ' SET '.trim($sql, ',').' WHERE 1 '.$where;

		// echo '<pre>';
		// print_r($sql);
		// die;
		
        return mysqli_query($this->__conn, $sql);
    }

	public function delete($table, $condition = ''){

		// Kết nối
        $this->connect();

		$where = '';
		if($condition){
			foreach ($condition as $k => $v) {
				$where .= ' AND '.$k.'='.$v;
			}
		}

		$sql = "DELETE FROM `".$table."` WHERE 1 ".$where;

		
		$result = mysqli_query($this->__conn, $sql);

		if($result){
			$sql = "ALTER TABLE `".$table."` AUTO_INCREMENT = 1";
			mysqli_query($this->__conn, $sql);
		}

		return $result;
	}
}
