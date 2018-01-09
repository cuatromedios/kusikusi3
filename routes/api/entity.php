<?php

/*
|--------------------------------------------------------------------------
| API Entity Routes
|--------------------------------------------------------------------------
*/

$router->group(['prefix' => 'entity'], function () use ($router) {
    $router->get('/',       ['uses' => 'EntityController@find']);
    $router->get('/{id}',   ['uses' => 'EntityController@findOne']);
    $router->get('/{id}/children',   ['uses' => 'EntityController@children']);
});
