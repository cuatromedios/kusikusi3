<?php

/*
|--------------------------------------------------------------------------
| Web unique route
|--------------------------------------------------------------------------
|
| There is only one route capturing all web requests. You are free to add
| other routes as needed.
|
*/

$router->get('/{path:.*}', 'WebController@catch');
