<?php

namespace Classes;

use DAOFactories\MySQLDAOFactory;

/**
 * @author farZa
 * Class DAOFactory
 * Абстрактный генератор DAO объектов
 * Класс построен по шаблону проектирования "Фабричный метод" (Factory Method)
 */
abstract class DAOFactory
{
	// Список типов DAO, поддерживаемых генератором
	const MYSQL = 1;

	const POSTGRESQL = 2;

	// Здесь будет метод для каждого DAO, который может быть
	// создан. Реализовывать эти методы
	// должны конкретные генераторы.
	public abstract function generalDAO();

	public static function initial(int $DAOFactory):DAOFactory
	{
		switch ($DAOFactory) {
			case self::MYSQL :
				return new MySQLDAOFactory();
				break;
//			case self::POSTGRESQL :
//				return new PostgreSQLDAOFactory();
//				break;
		}

		throw new \Exception('DAO объект не был найден');
	}
}