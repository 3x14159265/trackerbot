<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::group(['middleware' => 'auth'], function() {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/slack/auth', 'SlackController@auth');
    Route::get('/apps/all', 'DashboardController@apps');
    Route::get('/integrations/{appId}', 'DashboardController@integrations');
    Route::post('/app', 'DashboardController@createApp');
    Route::post('/telegram/connect', 'TelegramController@connect');
// });

Route::post('/api', 'ApiController@api');
Route::post('/track', 'ApiController@track');
Route::options('/track', function() {
    return response('')
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Credentials', 'true')
        ->header('Access-Control-Allow-Methods', 'POST')
        ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With, Accept, Authorization')
        ->header('Access-Control-Max-Age', '3600')
        ->header('Content-Type','application/json; charset=utf-8');
});

Route::post('/telegram/webhook/'.getenv('TELEGRAM_WEBHOOK_TOKEN'), 'TelegramController@webhook');

Route::auth();

Route::get('/dashboard', 'DashboardController@index');
