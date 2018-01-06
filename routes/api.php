<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/', function () use ($router) {
        return 'API';
    });

    // Entity endpoints
    require __DIR__.'/api/entity.php';

    // User endpoints
    require __DIR__.'/api/user.php';

    $router->get('/{path:.*}', function () use ($router) {
        return 'API endpoint not found';
    });
});
