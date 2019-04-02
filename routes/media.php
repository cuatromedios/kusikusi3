<?php
/*
|--------------------------------------------------------------------------
| Media Routes
|--------------------------------------------------------------------------
|
*/

use Cuatromedios\Kusikusi\Models\Http\ApiResponse;

$router->group(['prefix'     => 'media',
                'middleware' => 'cors',
                'namespace'  => 'Cuatromedios\Kusikusi\Http\Controllers\Media',
], function () use ($router) {
    $router->get('/{id}/{preset}[/{friendly}]', ['uses' => 'MediaController@get']);
    $router->get('/{path:.*}', function () use ($router) {
        return (new ApiResponse(null, false, "Media " . ApiResponse::TEXT_NOTFOUND,
            ApiResponse::STATUS_NOTFOUND))->response();
    });
});
