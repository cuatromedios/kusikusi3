<?php

/*
|--------------------------------------------------------------------------
| API Entity Routes
|--------------------------------------------------------------------------
*/

$router->group(['prefix' => 'user'], function () use ($router) {
    $router->get('/', ['uses' => 'UserController@index']);
});
