<?php 

class Model
{

	public $pdo;

	public function __construct()
	{
		try {
			$this->pdo = new PDO("mysql:dbname=".db['dbname'].";host=".db['host'],db['dbuser'],db['dbpass']);
		} catch (PDOException $e) {
			die($e->getMessage());
		}

	}

}
