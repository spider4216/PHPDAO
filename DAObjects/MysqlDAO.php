<?php

namespace DAObjects;

use Classes\ConnectionsPool;
use DAObjects\GeneralDAO;

/**
 * @author farZa
 * DAO объект для MYSQL
 * Избавиться от дублей чтобы придерживаться принципа DRY
 * todo Отрефакторить
 */
class MysqlDAO implements GeneralDAO
{
	private $query = [];

	public function table(string $tableName):GeneralDAO
	{
		$this->query['table'] = $tableName;

		return $this;
	}

	public function insert(array $data):GeneralDAO
	{
		$this->query['insert'] = $data;

		return $this;
	}

	public function update(array $data):GeneralDAO
	{
		$this->query['update'] = $data;

		return $this;
	}

	public function delete():GeneralDAO
	{
		$this->query['delete'] = true;

		return $this;
	}

	public function select(string $columns):GeneralDAO
	{
		$this->query['select'] = $columns;

		return $this;
	}

	public function where($data, $sep = 'AND'):GeneralDAO
	{
		$this->query['where'] = $data;
		$this->query['sep'] = $sep;

		return $this;
	}

	public function from(string $table):GeneralDAO
	{
		$this->query['table'] = $table;

		return $this;
	}

	public function execute():bool
	{
		/** @var \PDO $pdo */
		$pdo = ConnectionsPool::getConnection('MysqlDAO');

		if (isset($this->query['insert'])) {

			$columns = [];
			$values = [];
			$params = [];

			foreach ($this->query['insert'] as $key => $value) {
				$columns[] = $key;
				$values[] = ':' . $key;
				$params[':' . $key] = $value;
			}

			$columnString = implode(',', $columns);
			$valueString = implode(',', $values);

			$sql = 'INSERT INTO ' . $this->query['table'] . ' (' . $columnString . ') VALUES (' . $valueString . ')';

			/** @var \PDO $pdo */

			$stmt = $pdo->prepare($sql);

			return $stmt->execute($params);
		}

		if (isset($this->query['update'])) {
			$set = '';
			$params = [];

			foreach ($this->query['update'] as $key => $value) {
				$set .= $key . ' = :' . $key . ',';
				$params[':' . $key] = $value;
			}

			$set = rtrim($set, ',');

			$sql = 'UPDATE ' . $this->query['table'] . ' SET ' . $set;

			if (isset($this->query['where'])) {
				$valuesWhere = [];
				$paramsWhere = [];

				foreach ($this->query['where'] as $key => $value) {
					$valuesWhere[] = $key . ' = :' . $key;
					$paramsWhere[':' . $key] = $value;
				}

				$valueWhereString = implode(' ' . $this->query['sep'] . ' ', $valuesWhere);

				$sql .= ' WHERE ' . $valueWhereString;
				$params = array_merge($params, $paramsWhere);
			}

//			var_dump($sql);
//			var_dump($params);

			$stmt = $pdo->prepare($sql);

			return $stmt->execute($params);

		}

		if (isset($this->query['delete'])) {
			$params = [];

			$sql = 'DELETE FROM ' . $this->query['table'];

			if (isset($this->query['where'])) {
				$values = [];

				foreach ($this->query['where'] as $key => $value) {
					$values[] = $key . ' = :' . $key;
					$params[':' . $key] = $value;
				}

				$valueWhereString = implode(' ' . $this->query['sep'] . ' ', $values);

				$sql .= ' WHERE ' . $valueWhereString;
			}

			$stmt = $pdo->prepare($sql);
			return $stmt->execute($params);
		}

		return false;
	}

	public function fetchAll():array
	{
		/** @var \PDO $pdo */
		$pdo = ConnectionsPool::getConnection('MysqlDAO');

		if (!isset($this->query['select'])) {
			return [];
		}

		$sql = 'SELECT ' . $this->query['select'] . ' FROM ' . $this->query['table'];
		$params = [];


		if (isset($this->query['where'])) {
			$values = [];
			foreach ($this->query['where'] as $key => $value) {
				$values[] = $key . ' = :' . $key;
				$params[':' . $key] = $value;
			}

			$valueWhereString = implode(' ' . $this->query['sep'] . ' ', $values);

			$sql .= ' WHERE ' . $valueWhereString;
		}

		$stmt = $pdo->prepare($sql);
		$stmt->execute($params);

		return $stmt->fetchAll();
	}

	public function fetchRow():array
	{
		/** @var \PDO $pdo */
		$pdo = ConnectionsPool::getConnection('MysqlDAO');

		if (!isset($this->query['select'])) {
			return [];
		}

		$sql = 'SELECT ' . $this->query['select'] . ' FROM ' . $this->query['table'];
		$params = [];


		if (isset($this->query['where'])) {
			$values = [];
			foreach ($this->query['where'] as $key => $value) {
				$values[] = $key . ' = :' . $key;
				$params[':' . $key] = $value;
			}

			$valueWhereString = implode(' ' . $this->query['sep'] . ' ', $values);

			$sql .= ' WHERE ' . $valueWhereString;
		}

		$stmt = $pdo->prepare($sql);
		$stmt->execute($params);

		return $stmt->fetch();
	}

	public function resetDao()
	{
		$this->query = [];
	}

}