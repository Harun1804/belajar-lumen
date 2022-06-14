<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/login', 'AuthController@login');
$router->group([
    'middleware' => 'auth',
],function() use ($router){
    $router->get('/user', function () use ($router) {
        return $router->app->version();
    });
});

$router->group([
    'prefix' => 'blogs',
    'name'   => 'blogs'
],function() use ($router){
    $router->get('/', 'BlogController@index');
    $router->get('/{id}', 'BlogController@show');
    $router->post('/', 'BlogController@store');
    $router->put('/{id}/update', 'BlogController@update');
    $router->delete('/{id}/delete', 'BlogController@destroy');
});
