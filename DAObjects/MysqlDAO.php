<?php

namespace DAObjects;

use Classes\ConnectionsPool;
use DAObjects\GeneralDAO;

/**
 * @author farZa
 * DAO объект для MYSQL
 */
class MysqlDAO implements GeneralDAO
{
	private $table;

	private $insert;

	private $insertParams = [];

	private $update;

	private $delete;

	private $select;

	public function table(string $tableName):GeneralDAO
	{
		$this->table = $tableName;

		return $this;
	}

	public function insert(array $data):GeneralDAO
	{
		$columns = [];
		$values = [];
		$this->insertParams = [];

		foreach ($data as $key => $value) {
			$columns[] = $key;
			$values[] = ':' . $key;
			$this->insertParams[':' . $key] = $value;

		}

		$columnString = implode(',', $columns);
		$valueString = implode(',', $values);

		$this->insert = 'INSERT INTO ' . $this->table . ' (' . $columnString . ') VALUES (' . $valueString . ')';

		return $this;
	}

	public function update(array $data, string $condition, array $params = []):GeneralDAO
	{
		// TODO: Implement update() method.
	}

	public function delete():GeneralDAO
	{
		// TODO: Implement delete() method.
	}

	public function select():GeneralDAO
	{
		// TODO: Implement select() method.
	}

	public function execute():bool
	{
		/** @var \PDO $pdo */
		$pdo = ConnectionsPool::getConnection('MysqlDAO');
		$stmt = $pdo->prepare($this->insert);
		$res = $stmt->execute($this->insertParams);

		$this->insert = null;
		$this->insertParams = [];

		return $res;
	}

}