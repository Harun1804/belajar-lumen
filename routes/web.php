<?php

use Illuminate\Support\Str;

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
$router->get('/key',function() {
    return Str::random(32);
});

$router->post('/login', 'AuthController@login');
$router->get('/logout','AuthController@logout');

$router->group([
    'prefix' => 'blogs',
    'name'   => 'blogs',
    'middleware' => 'auth:api',
],function() use ($router){
    $router->get('/', 'BlogController@index');
    $router->get('/{id}', 'BlogController@show');
    $router->post('/store', 'BlogController@store');
    $router->put('/{id}/update', 'BlogController@update');
    $router->delete('/{id}/delete', 'BlogController@destroy');
});
