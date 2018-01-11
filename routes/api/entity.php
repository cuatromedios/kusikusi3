<?php

/*
|--------------------------------------------------------------------------
| API Entity Routes
|--------------------------------------------------------------------------
*/

$router->group(['prefix' => 'entity'], function () use ($router) {
    $router->get('/',               ['uses' => 'EntityController@get']);
    $router->get('/{id}',           ['uses' => 'EntityController@getOne']);
    $router->get('/{id}/parent',    ['uses' => 'EntityController@getParent']);
    $router->get('/{id}/children',  ['uses' => 'EntityController@getChildren']);
});
