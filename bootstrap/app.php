<?php

require_once __DIR__.'/../vendor/autoload.php';

try {
    (new Dotenv\Dotenv(__DIR__.'/../'))->load();
} catch (Dotenv\Exception\InvalidPathException $e) {
    //
}


/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    realpath(__DIR__.'/../')
);

$app->withFacades();

$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel from the KusiKusi
| Kernel. You may add your own bindings here.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    Cuatromedios\Kusikusi\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    Cuatromedios\Kusikusi\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
| By default the KusiKusi Kernel Middleware but you can add your own
| in app/Http/Middleware and registing them here
|
*/


 $app->routeMiddleware([
     'auth' => Cuatromedios\Kusikusi\Http\Middleware\Authenticate::class,
 ]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Default Service providers are
| stored in the KusiKusi PHP Kernel library, but you are free to use your owns
| in app/Providers and register them here.
|
*/

$app->register(Cuatromedios\Kusikusi\Providers\AppServiceProvider::class);
$app->register(Cuatromedios\Kusikusi\Providers\AuthServiceProvider::class);
$app->register(Mnabialek\LaravelSqlLogger\Providers\ServiceProvider::class);
// $app->register(Cuatromedios\Kusikusi\Providers\EventServiceProvider::class);


/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
| By default api and website routes are binded to KusiKusi Kernel
| default controllers, but you are free to create your own routes and controllers
| for example if your App does not need the Entity endpoint exposed,
| and call them here with appropiate paths like app/Http/Controllers:
|
*/

$app->router->group([
    'namespace' => 'Cuatromedios\Kusikusi\Http\Controllers\Api'
], function ($router) {
    require __DIR__.'/../routes/api.php';
});
$app->router->group([
    'namespace' => 'Cuatromedios\Kusikusi\Http\Controllers\Web'
], function ($router) {
    require __DIR__.'/../routes/web.php';
});

// Example App Router
/*
$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__.'/../routes/web.php';
});
*/


/*
|--------------------------------------------------------------------------
| Load The Application Configuration
|--------------------------------------------------------------------------
|
| Located in /config/general.php
|
*/

$app->configure('general');

return $app;