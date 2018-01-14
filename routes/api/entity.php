<?php

/*
|--------------------------------------------------------------------------
| API Entity Routes
|--------------------------------------------------------------------------
*/

$router->group(['prefix' => 'entity'], function () use ($router) {
    $router->get    ('/',                   ['uses' => 'EntityController@get']);
    $router->post   ('/',                   ['uses' => 'EntityController@post']);
    $router->get    ('/{id}',               ['uses' => 'EntityController@getOne']);
    $router->patch  ('/{id}',               ['uses' => 'EntityController@patch']);
    $router->get    ('/{id}/parent',        ['uses' => 'EntityController@getParent']);
    $router->get    ('/{id}/children',      ['uses' => 'EntityController@getChildren']);
    $router->get    ('/{id}/ancestors',     ['uses' => 'EntityController@getAncestors']);
    $router->get    ('/{id}/descendants',   ['uses' => 'EntityController@getDescendants']);
});
