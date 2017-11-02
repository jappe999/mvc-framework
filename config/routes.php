<?php

use Core\Router as Router;

// --- GET and POST routes --- //
Router::get('', 'TestController@get');
Router::post('', 'TestController@post');

// --- GET route with parameter --- //
Router::get('/random/{random_id}', 'TestController@parameterExample');

// --- GET route with database connection --- //
Router::get('db_connect', 'TestController@makeDBConnection');
