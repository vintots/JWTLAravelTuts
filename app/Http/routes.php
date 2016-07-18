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
$api = app('Dingo\Api\Routing\Router');
// print_r($api);
	// Route::post('/register','TestController@create');
	// Route::post('/register','App\Http\Controllers\Auth\AuthController@registerUser');
    // Route::post('/login','App\Http\Controllers\Auth\AuthController@authenticate');
    // Route::post('/logout','App\Http\Controllers\Auth\AuthController@logout');
	$api->version('v1', function ($api) {
		// $api->get('test','App\Http\Controllers\TestController@create');
		$api->post('register','App\Http\Controllers\Auth\AuthController@registerUser');
		$api->post('login','App\Http\Controllers\Auth\AuthController@authenticate');

		//with jwt authentication
		$api->group(['middleware' => ['jwt.auth']],function($api){
			$api->get('test','App\Http\Controllers\TestController@create');
		});
	});
