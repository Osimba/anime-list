<?php

class Dbh {

	private $server;
	private $user;
	private $pass;
	private $db;
	private $charset;

	public function connect() {
		$this->server = 'localhost';
		$this->user = 'root';
		$this->pass = 'root';
		$this->db = 'anime_list_db';
		$this->charset = 'utf8mb4';

		try {
			$dsn = "mysql:host=".$this->server.";dbname=".$this->db.";charset=".$this->charset;
			$pdo = new PDO($dsn, $this->user, $this->pass);
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			return $pdo;
			
		} catch (PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
		}

		
	}
}
	
	
	