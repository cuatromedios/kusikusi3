<?php

/*
|--------------------------------------------------------------------------
| Media Routes
|--------------------------------------------------------------------------
|
*/

use Cuatromedios\Kusikusi\Models\Http\ApiResponse;

$router->group(['prefix' => 'media', 'middleware' => 'cors'], function () use ($router) {
    $router->get('/{id}/{preset}[/{friendly}]', ['uses' => 'MediaController@get']);

    $router->get('/{path:.*}', function () use ($router) {
        return (new ApiResponse(NULL, FALSE, "Media " . ApiResponse::TEXT_NOTFOUND, ApiResponse::STATUS_NOTFOUND))->response();
    });
});
