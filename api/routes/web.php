<?php

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
$router->group(array('prefix' => 'api'), function($app){
    $app->post('login', 'AuthController@login');
    $app->post('/logout', 'AuthController@logout');
    $app->post('/refresh', 'AuthController@refresh');
    $app->post('/me', 'AuthController@me');


    $app->get('/stats' , 'GrepoController@stats');
    $app->get('/linechart' , 'GrepoController@linechart');
    $app->get('/barchart' , 'GrepoController@barchart');


    $app->get('/attendance[/{date}]' , 'GrepoController@attendance');
    $app->get('/pairslesshour[/{startDate}/{toDate}]' , 'GrepoController@PairsLessHour');
    //$app->get('/mails[/{date}]','GrepoController@mails');

    $app->get('/test' , 'GrepoController@test');
    $app->get('/functest' , 'GrepoController@functest');
    $app->get('/groupRanks' , 'GrepoController@groupRanks');
});
