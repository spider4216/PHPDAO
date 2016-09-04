<?php

namespace DAOFactories;

use Classes\DAOFactory;
use DAObjects\MysqlDAO;
use Classes\ConnectionsPool;

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
			throw new \Exception('Не указан хост');
		}

		if (!$this->username && !$this->password) {
			throw new \Exception('Не указан логин или пароль');
		}

		if (!$this->dbName) {
			throw new \Exception('Не указано наименование базы данных');
		}

		$this->pdo = new \PDO('mysql:host=' . $this->host . ';dbname=' . $this->dbName, $this->username, $this->password);
		$this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

		ConnectionsPool::pushConnection('MysqlDAO', $this->pdo);
	}

	/**
	 * @author farZa
	 * @return MysqlDAO
	 * General DAO Object
	 * Create other DAO objects if you need
	 */
	public function generalDAO()
	{
		return new MysqlDAO();
	}
}