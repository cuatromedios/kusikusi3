<?php

/*
|--------------------------------------------------------------------------
| API Entity Routes
|--------------------------------------------------------------------------
*/

$router->group(['prefix' => 'entity'], function () use ($router) {
    $router->get('/',               ['uses' => 'EntityController@all']);
    $router->get('/{id}',           ['uses' => 'EntityController@findOne']);
    $router->get('/{id}/parent',    ['uses' => 'EntityController@parent']);
    $router->get('/{id}/children',  ['uses' => 'EntityController@children']);
});
