<?php
	namespace Database;
    
	class QueryRunner {

		//this is git ignore file
		// I want to ignore this file

		function connect(){
			$connection=mysqli_connect(getenv('DB_HOST'),getenv('DB_USER'),getenv('DB_PASS'),getenv('DB_NAME'));

			return $connection;
		}

		function read($query){
			$conn=$this->connect();

			$result=mysqli_query($conn,$query);

			if(!$result){
				return false;
			}else{

				$data=false;
				while ($row=mysqli_fetch_assoc($result)) {
						
						$data[]=$row;
				}

				return $data;

			}
		}

		function save($query){
			$conn=$this->connect();
			$result=mysqli_query($conn,$query);

			if(!$result){
				return false;
			}else{
				return true;
			}
		}

	}
