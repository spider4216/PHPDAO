<?php

namespace DAObjects;

/**
 * @author farZa
 * Интерфейс, который должны поддерживать все GeneralDAO
 */
interface GeneralDAO
{
	public function table(string $tableName):GeneralDAO;
	public function insert(array $data):GeneralDAO;
	public function update(array $data, string $condition, array $params = []):GeneralDAO;

	public function delete():GeneralDAO;
	public function select():GeneralDAO;
	public function execute():bool;
}