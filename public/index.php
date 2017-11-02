<?php

// Define globals.
require '../config/definitions.php';

// Autoloader.
require '../Core/autoloader.php';

// All other that must be included in every page.
require '../Core/bootstrap.php';

// Handle the request.
Core\Router::load()->handle();
