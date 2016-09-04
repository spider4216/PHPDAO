<?php

/**
 * @author farZa
 * Object pool pattern
 * Pool of db connections
 */
class ConnectionsPool
{
    public static $pool;

    public function pushConnection(string $name, object $connection)
    {
        self::$pool[$name] = $connection;
    }

    public static function getConnection(string $name)
    {
        return self::$pool[$name] ?? null;
    }

    public function removeConnection(string $name)
    {
        if (key_exists($name, self::$pool)) {
            unset(self::$pool[$name]);
        }
    }
}