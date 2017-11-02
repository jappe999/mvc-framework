<?php

// Check root directory
if (strpos($_SERVER['DOCUMENT_ROOT'], 'public') !== false)
    define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/..');
else
    define('ROOT', $_SERVER['DOCUMENT_ROOT']);

define('CONFIG_PATH', ROOT . '/config/');
define('PUBLIC_PATH', ROOT . '/public/');
define('VIEWS_PATH', ROOT . '/resources/views/');
define('ERROR_VIEWS_PATH', ROOT . '/resources/views/errors/');
define('CONTROLLERS_PATH', ROOT . '/Controllers/');
define('MODELS_PATH', ROOT . '/Models/');
