<?php

/*
|--------------------------------------------------------------------------
| Web routes
|--------------------------------------------------------------------------
|
| This is the last routes called, after /media and /api.
| There is only one route capturing all web requests. You are free to add
| other routes as needed, for example to redirect previeous version of a site.
|
*/

// Place here site's previous versions urls and where should they go now. (301 = permanent, 302 = temporal)

// Route::get('previous/version', function () {  return redirect('new/version', 301);  });



// Catch any other routes

$router->group(['namespace' => 'Cuatromedios\Kusikusi\Http\Controllers\Web'], function () use ($router) {

    $router->get('/{path:.*}', 'WebController@any');

});