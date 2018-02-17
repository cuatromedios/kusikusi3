<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Cuatromedios\Kusikusi\Models\Http\ApiResponse;

$router->group(['prefix' => 'api', 'middleware' => 'cors'], function () use ($router) {
    $router->get('/', function () use ($router) {
        return (new ApiResponse(NULL, TRUE, "KusiKusi API"))->response();
    });

    /*
    |--------------------------------------------------------------------------
    | API Entity Endpoints
    |--------------------------------------------------------------------------
    */

    $router->group(['prefix' => 'entity'], function () use ($router) {
        $router->get    ('/',                   ['uses' => 'EntityController@get']);
        $router->post   ('/',                   ['uses' => 'EntityController@post']);
        $router->get    ('/{id}',               ['uses' => 'EntityController@getOne']);
        $router->patch  ('/{id}',               ['uses' => 'EntityController@patch']);
        $router->delete ('/{id}',               ['uses' => 'EntityController@softDelete']);
        $router->delete ('/{id}/hard',          ['uses' => 'EntityController@hardDelete']);
        $router->get    ('/{id}/parent',        ['uses' => 'EntityController@getParent']);
        $router->get    ('/{id}/children',      ['uses' => 'EntityController@getChildren']);
        $router->get    ('/{id}/ancestors',     ['uses' => 'EntityController@getAncestors']);
        $router->get    ('/{id}/descendants',   ['uses' => 'EntityController@getDescendants']);
        $router->get    ('/{id}/relations[/{kind}]',     ['uses' => 'EntityController@getRelations']);
        $router->get    ('/{id}/inverse-relations[/{kind}]',     ['uses' => 'EntityController@getInverseRelations']);
    });


    /*
    |--------------------------------------------------------------------------
    | API User Endpoints
    |--------------------------------------------------------------------------
    */

    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->post   ('/login',  ['uses' => 'UserController@authenticate']);
    });

    $router->options('/{path:.*}', function() use ($router) {
        $response =  (new ApiResponse(NULL, TRUE))->response();
        return $response;
    });

    $router->get('/{path:.*}', function () use ($router) {
        return (new ApiResponse(NULL, FALSE, "Endpoint " . ApiResponse::TEXT_NOTFOUND, ApiResponse::STATUS_NOTFOUND))->response();
    });
});
