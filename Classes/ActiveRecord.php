<?php

namespace Classes;

/**
 * Interface ActiveRecord
 * @package Classes
 * Active Record Pattern
 */
interface ActiveRecord
{
	public function findAll():array;
	public function findOne():ActiveRecord;
	public function save():bool;
	public function delete():bool;
}