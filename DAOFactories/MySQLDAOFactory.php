<?php

/**
 * @author farZa
 */
class MySQLDAOFactory extends DAOFactory
{
	private $host;

	private $dbName;

	private $username;

	private $password;

	private $pdo;

	/**
	 * @param mixed $host
	 */
	public function setHost($host)
	{
		$this->host = $host;
	}

	/**
	 * @param mixed $dbName
	 */
	public function setDbName($dbName)
	{
		$this->dbName = $dbName;
	}

	/**
	 * @param mixed $username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
	}

	/**
	 * @param mixed $password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
	}

	public function createConnection()
	{
		if (!$this->host) {
			throw new Exception('Не указан хост');
		}

		if (!$this->username || !$this->password) {
			throw new Exception('Не указан логин или пароль');
		}

		if (!$this->dbName) {
			throw new Exception('Не указано наименование базы данных');
		}

		$this->pdo = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbName, $this->username, $this->password);

	}

	public function generalDAO()
	{
		return new MysqlDAO();
	}
}