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
use Illuminate\Support\Facades\Config;

$router->group(['prefix' => 'api', 'middleware' => 'cors'], function () use ($router) {

  /*
  |--------------------------------------------------------------------------
  | Endpoint when getting the root of the API
  |--------------------------------------------------------------------------
  */
  $router->get('/', function () use ($router) {
    return (new ApiResponse(NULL, TRUE, "KusiKusi API"))->response();
  });

  /*
  |--------------------------------------------------------------------------
  | Put your own API endpoints here, you can put your controllers
  | in app/Controllers and reference the namespace here.
  | if the prefix of the group is 'myapp' then the route will be 'api/myapp/'
  |--------------------------------------------------------------------------
  */
  /*
  $router->group(['prefix' => 'myapp', 'namespace' => 'App\Controllers'], function ($router) {
      $router->get('/myendpoint', ['uses' => 'MyApiController@myMethod']);
  });
  */

  /*
  |--------------------------------------------------------------------------
  | Generic API EntityBase, User and Media Endpoints.
  | Feel free to remove the ones you don't need, maybe all of them?
  |--------------------------------------------------------------------------
  */
  $router->group(['namespace' => 'Cuatromedios\Kusikusi\Http\Controllers\Api'], function ($router) {
    $router->group(['prefix' => 'entity'], function () use ($router) {
      $router->get('/', ['uses' => 'EntityController@get']);
      $router->post('/', ['uses' => 'EntityController@post']);
      $router->get('/{id}', ['uses' => 'EntityController@getOne']);
      $router->patch('/{id}', ['uses' => 'EntityController@patch']);
      $router->delete('/{id}', ['uses' => 'EntityController@softDelete']);
      $router->delete('/{id}/hard', ['uses' => 'EntityController@hardDelete']);
      $router->get('/{id}/parent', ['uses' => 'EntityController@getParent']);
      $router->get('/{id}/children', ['uses' => 'EntityController@getChildren']);
      $router->get('/{id}/ancestors', ['uses' => 'EntityController@getAncestors']);
      $router->get('/{id}/descendants', ['uses' => 'EntityController@getDescendants']);
      $router->get('/{id}/relations[/{kind}]', ['uses' => 'EntityController@getRelations']);
      $router->post('/{id}/relations', ['uses' => 'EntityController@postRelation']);
      $router->delete('/{id}/relations/{called}/{kind}', ['uses' => 'EntityController@deleteRelation']);
      $router->get('/{id}/inverse-relations[/{kind}]', ['uses' => 'EntityController@getInverseRelations']);
    });
    $router->group(['prefix' => 'user'], function () use ($router) {
      $router->post('/login', ['uses' => 'UserController@authenticate']);
      $router->get('/permissions/{id}', ['uses' => 'UserController@getPermissions']);
      $router->post('/permissions', ['uses' => 'UserController@postPermissions']);
      $router->patch('/permissions/{id}', ['uses' => 'UserController@patchPermissions']);
    });
    $router->group(['prefix' => 'media'], function () use ($router) {
      $router->post('/', ['uses' => 'MediaController@post']);
      $router->post('/{id}/upload', ['uses' => 'MediaController@upload']);
      $router->delete('/{id}', ['uses' => 'MediaController@delete']);
    });
  });

  /*
  |--------------------------------------------------------------------------
  | Config endpoints, for example the one needed by the CMS
  |--------------------------------------------------------------------------
   */
  $router->group(['prefix' => 'config'], function () use ($router) {
    $router->get('/cms', function () use ($router) {
      $response = (new ApiResponse(Config::get('cms'), TRUE))->response();
      return $response;
    });
  });

  // Success response for all OPTIONS calls
  $router->options('/{path:.*}', function () use ($router) {
    $response = (new ApiResponse(NULL, TRUE))->response();
    return $response;
  });

  // Send an error if no endpoint was found
  $router->get('/{path:.*}', function () use ($router) {
    return (new ApiResponse(NULL, FALSE, "Endpoint " . ApiResponse::TEXT_NOTFOUND, ApiResponse::STATUS_NOTFOUND))->response();
  });
});
