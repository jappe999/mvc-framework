<?php
/**
 * This file contains the core Database class.
 */

namespace Core;

use PDO;

/**
 * Database class for calling the database.
 *
 * This handles the default database settings
 * with config/database.php
 */
class Database
{
    /**
     * Default config path for database connections.
     *
     * @var string
     */
    private static $configFile = CONFIG_PATH . 'database.php';

    /**
     * Construct PDO class with default values
     *
     * Construct a PDO instance with the configurations
     * found in "/config/database.php".
     *
     * @param string $name
     * @return PDO
     */
    public static function connect($name = 'default'): PDO
    {
        $config   = require self::$configFile;

        $driver   = $config[$name]['driver'];
        $host     = $config[$name]['host'];
        $user     = $config[$name]['user'];
        $password = $config[$name]['password'];
        $dbname   = $config[$name]['db_name'];
        $options  = $config[$name]['options'];
        $dns      = "$driver:host=$host;dbname=$dbname";

        // Return a new PDO instance.
        return new PDO($dns, $user, $password, $options);
    }

    /**
     * Prepare a query with the default database connection.
     *
     * @param $query
     * @return PDO
     */
    public static function prepare($query)
    {
        $pdoInstance = self::connect();
        return $pdoInstance->prepare($query);
    }
}
