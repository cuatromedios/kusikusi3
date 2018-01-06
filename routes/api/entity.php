<?php

/*
|--------------------------------------------------------------------------
| API Entity Routes
|--------------------------------------------------------------------------
*/

$router->group(['prefix' => 'entity'], function () use ($router) {
    $router->get('/',       ['uses' => 'EntityController@index']);
    $router->get('/{id}',   ['uses' => 'EntityController@show']);
    $router->get('/{id}/children',   ['uses' => 'EntityController@children']);
});
