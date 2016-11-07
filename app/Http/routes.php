<?php
use Illuminate\Http\Request;

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

Route::post('/api/track', 'ApiController@track');
Route::post('/api/domain', 'ApiController@domain');
Route::post('/api/email/', 'ApiController@email');
Route::post('/js/track/{api_key}', 'JsController@track');
Route::post('/js/domain/{api_key}', 'JsController@domain');
Route::post('/js/email/{api_key}', 'JsController@email');
Route::options('/js/track/{api_key}', 'JsController@options');
Route::options('/js/domain/{api_key}', 'JsController@options');
Route::options('/js/email/{api_key}', 'JsController@options');

Route::post('/telegram/webhook/'.getenv('TELEGRAM_WEBHOOK_TOKEN'), 'TelegramController@webhook');

Route::auth();

Route::get('/dashboard', 'DashboardController@index');
