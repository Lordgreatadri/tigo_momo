<?php
	
	class MCC_CI_Database
	{

		private $db_host = ' ';
        private $db_name = 'airtel_tigo_mobile_money';//mycloud
        private $db_username = ' ';
        private $pass_word = '     (   ';
        private $charset = 'utf8mb4';

		//access to server/database connection 2018-04-09 16:02:44
		public $db_conn;
		
		public function  __construct()
		{
			
			//test connection is null
			if($this->db_conn == null)
			{

				try 
				{
					$server_path = "mysql:host=".$this->getHostName().";dbname=".$this->getDatabaseName().";charset=".$this->getCharacterSet();
					$pdo_features = array(
							PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
							PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
							PDO::ATTR_EMULATE_PREPARES   => false,
						);
					//making an instance of pdo
					$this->db_conn = new PDO($server_path,$this->getUserName(),$this->getPassword());

					//close connection
				    return $this->db_conn;
				} 
				//if error throw exception
				catch (PDOException $e) {
					return $e->getMessage();
				}  
		   }
			
		}

		//creating out getters method
		private function getHostName()
		{
			return $this->db_host;
		}

		private function getDatabaseName()
		{
			return $this->db_name;
		}

		private function getUserName()
		{
			return $this->db_username;
		}
		private function getPassword()
		{
			return $this->pass_word;
		}

		private function getCharacterSet()
		{
			return $this->charset;
		}

		//destroy connection when class is not needed
		public function __destruct(){
			$this->db_conn = null;
		}

	}

