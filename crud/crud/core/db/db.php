<?php

namespace Core\DB;

class DB
{
	private $dbhost = '127.0.0.1';
	private $dbname = 'dbmartyshkin';
	private $dbuser = 'usermartyshkin';
	private $dbpass = '@?{m+RV@zoSYOGi';

	private  $pdo;
	private static $instance = null;

	private function __construct()
	{
		$dsn = 'mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname;
		try
		{
			$this->pdo = new \PDO($dsn, $this->dbuser, $this->dbpass);
		}catch (\PDOException $exception)
		{
			die($exception->getMessage());
		}
	}

	/**
	 * @return \PDO
	 */
	public function getConnection(): \PDO
	{
		return static::getInstance()->pdo;
	}

	/**
	 * @return static
	 */
	public static function getInstance(): DB
	{
		if(!self::$instance)
		{
			self::$instance = new DB();
		}
		return self::$instance;
	}
}