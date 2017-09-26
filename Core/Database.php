<?php
    /**
     * Database class for calling the database.
     *
     * This handles the default database settings
     * with config/database.json
     */
    class Database extends PDO
    {
        private $db;
        private $configFile = __DIR__ . '../config/database.json';
        private $config;

        /**
         * Construct PDO class with default values
         *
         * Construct a PDO instance with the configurations
         * found in "/config/database.json".
         */
        function __construct($name = NULL)
        {
            $name         = $name ?? 'default';
            $this->config = file_get_contents($configFile);

            $driver   = $this->config[$name]['driver'];
            $host     = $this->config[$name]['host'];
            $user     = $this->config[$name]['user'];
            $password = $this->config[$name]['password'];
            $dbname   = $this->config[$name]['db_name'];
            $dns      = "$driver:host=$host;dbname=$dbname";

            $this->db = parent::__construct($dns, $user, $password);
        }
    }
