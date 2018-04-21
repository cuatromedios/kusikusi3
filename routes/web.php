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

$router->group(['namespace' => 'Cuatromedios\Kusikusi\Http\Controllers\Web'], function () use ($router) {

    $router->get('/{path:.*}', 'WebController@any');

});