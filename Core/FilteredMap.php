<?php
/**
 * This file contains the core FilteredMap class.
 */

namespace Core;

/**
 * This class is used to easily contain arrays.
 * Mostly for global variables such as $_POST, $_GET and $_COOKIES.
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
     * Get length of the $this->map array.
     *
     * @return int
     */
    public function length(): int
    {
        return count($this->map);
    }

    /**
     * Checks if the given map contains an index.
     *
     * Checks if the given map contains a specific index.
     *
     * @param string $name
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
     * @return string
     */
    public function get($name)
    {
        return $this->map[$name] ?? null;
    }
}
