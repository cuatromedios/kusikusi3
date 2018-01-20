<?php

/*
|--------------------------------------------------------------------------
| API Entity Routes
|--------------------------------------------------------------------------
*/

$router->group(['prefix' => 'user'], function () use ($router) {
    $router->post   ('/login',  ['uses' => 'UserController@authenticate']);
});
