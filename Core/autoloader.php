<?php

/**
 * Register the class.
 *
 * Replace all backslashes with slashes (I'm looking at you Windows)
 * and include the requested class.
 */
function registerClass ($class)
{
    $class = str_replace('\\', '/', $class);
    $path  = ROOT . "/$class.php";

    include_once $path;
}

spl_autoload_register("registerClass");
