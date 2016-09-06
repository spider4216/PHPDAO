<?php

namespace DAObjects;

use Classes\ConnectionsPool;
use DAObjects\GeneralDAO;

/**
 * @author farZa
 * DAO объект для MYSQL
 * todo comments
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

	private function generateValues(string $type):array
	{
		$columns = [];
		$values = [];
		$params = [];
		$set = '';

		switch ($type) {
			case 'insert' :
				foreach ($this->query['insert'] as $key => $value) {
					$columns[] = $key;
					$values[] = ':' . $key;
					$params[':' . $key] = $value;
				}

				$columnString = implode(',', $columns);
				$valueString = implode(',', $values);

				return [
					'columns' => $columnString,
					'values' => $valueString,
					'params' => $params,
				];
				break;

			case 'update' :
				foreach ($this->query['update'] as $key => $value) {
					$set .= $key . ' = :' . $key . ',';
					$params[':' . $key] = $value;
				}

				$set = rtrim($set, ',');

				return [
					'set' => $set,
					'params' => $params,
				];
				break;

			case 'where' :
				foreach ($this->query['where'] as $key => $value) {
					$values[] = $key . ' = :' . $key;
					$params[':' . $key] = $value;
				}

				$valueString = implode(' ' . $this->query['sep'] . ' ', $values);

				return [
					'condition' => $valueString,
					'params' => $params,
				];
				break;
		}

		return [];
	}

	public function execute():bool
	{
		/** @var \PDO $pdo */
		$pdo = ConnectionsPool::getConnection('MysqlDAO');

		if (isset($this->query['insert'])) {

			$insertResult = $this->generateValues('insert');
			$columns = $insertResult['columns'];
			$values = $insertResult['values'];
			$params = $insertResult['params'];

			$sql = 'INSERT INTO ' . $this->query['table'] . ' (' . $columns . ') VALUES (' . $values . ')';

			$stmt = $pdo->prepare($sql);

			return $stmt->execute($params);
		}

		if (isset($this->query['update'])) {
			$updateResult = $this->generateValues('update');

			$set = $updateResult['set'];
			$params = $updateResult['params'];



			$sql = 'UPDATE ' . $this->query['table'] . ' SET ' . $set;

			if (isset($this->query['where'])) {
				$whereResult = $this->generateValues('where');

				$condition = $whereResult['condition'];
				$paramsWhere = $whereResult['params'];


				$sql .= ' WHERE ' . $condition;
				$params = array_merge($params, $paramsWhere);
			}

			$stmt = $pdo->prepare($sql);

			return $stmt->execute($params);

		}

		if (isset($this->query['delete'])) {
			$params = [];

			$sql = 'DELETE FROM ' . $this->query['table'];

			if (isset($this->query['where'])) {
				$whereResult = $this->generateValues('where');

				$condition = $whereResult['condition'];
				$params = $whereResult['params'];


				$sql .= ' WHERE ' . $condition;
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
			$whereResult = $this->generateValues('where');

			$condition = $whereResult['condition'];
			$params = $whereResult['params'];


			$sql .= ' WHERE ' . $condition;
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
			$whereResult = $this->generateValues('where');

			$condition = $whereResult['condition'];
			$params = $whereResult['params'];


			$sql .= ' WHERE ' . $condition;
		}

		$stmt = $pdo->prepare($sql);
		$stmt->execute($params);

		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

	public function resetDao()
	{
		$this->query = [];
	}

}