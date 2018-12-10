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

/*
|--------------------------------------------------------------------------
| Load The Application Configuration
|--------------------------------------------------------------------------
|
| Located in /config/general.php
|
*/
$app->configure('filesystems');
$app->configure('media');
$app->configure('general');
$app->configure('activity');
$app->configure('cms');


$app->withFacades();
if(!class_exists('Storage')) {
    class_alias('Illuminate\Support\Facades\Storage', 'Storage');
}
if(!class_exists('Image')) {
    class_alias('Intervention\Image\ImageServiceProvider', 'Image');
}


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
| By default the KusiKusi Kernel Middlewares but you can add your own
| in app/Http/Middleware and registing them here
|
*/


 $app->routeMiddleware([
     'auth' => Cuatromedios\Kusikusi\Http\Middleware\Authenticate::class,
     'cors' => Cuatromedios\Kusikusi\Http\Middleware\CorsMiddleware::class,
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
$app->register(Cuatromedios\Kusikusi\Providers\CatchAllOptionsRequestsProvider::class);
$app->register(Mnabialek\LaravelSqlLogger\Providers\ServiceProvider::class);
$app->register(Illuminate\Filesystem\FilesystemServiceProvider::class);
$app->register(Intervention\Image\ImageServiceProvider::class);
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
| for example if your App does not need the EntityBase endpoint exposed,
| and call them here with appropiate paths like app/Http/Controllers:
|
*/

$app->router->group([], function ($router) {
  require __DIR__.'/../routes/api.php';
  require __DIR__.'/../routes/media.php';
  require __DIR__.'/../routes/web.php';
});

return $app;