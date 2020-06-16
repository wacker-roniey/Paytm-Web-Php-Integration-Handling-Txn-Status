<?php 
/**
 * 
 */
class Database
{
	var $host="localhost";
	var $user="root";
	var $pass="";
	var $db="apiphp";

	public function connect()
	{
		$con=mysqli_connect($this->host,$this->user,$this->pass,$this->db); 
		return $con;
	}

	public function Insert($tbName,$custid,$orderid)
	{
		$conn=$this->connect();
		mysqli_query($conn,"INSERT INTO $tbName VALUES(NULL,'".$custid."','".$orderid."','','')") or die(mysqli_error($conn));
	}
	
}
?>