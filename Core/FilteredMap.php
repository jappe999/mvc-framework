<?php

    namespace Core;

    /**
     * FilteredMap class.
     */
    class FilteredMap
    {
        /**
         * A map that can contain strings, numbers, booleans and arrays.
         *
         * @var array
         */
        private $map;

        /**
         * Construct class
         *
         * Construct class and assign the given parameter to $this->map.
         *
         * @param array $map
         *
         * @return void
         */
        public function __construct($map)
        {
            $this->map = $map;
        }

        /**
         * Checks if the given map contains an index.
         *
         * Checks if the given map contains a specific index.
         *
         * @param string $name
         *
         * @return boolean
         */
        public function has($name): bool
        {
            return isset($this->map[$name]);
        }

        /**
         * Returns the value at the given index in $this->map.
         *
         * Returns the value at the given index in $this->map.
         * If it does not exist, it return null.
         *
         * @param string $name
         *
         * @return string
         */
        public function get($name)
        {
            return $this->map[$name] ?? null;
        }
    }
