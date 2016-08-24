<?php

/**
 * @author farZa
 * DAO объект для MYSQL
 */
class MysqlDAO implements GeneralDAO
{
	private $table;

	private $insert;

	private $update;

	private $delete;

	private $select;

	public function table(string $tableName):GeneralDAO
	{
		$this->table = $tableName;
	}

	public function insert(array $data):GeneralDAO
	{
		// TODO: Implement insert() method.
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

}