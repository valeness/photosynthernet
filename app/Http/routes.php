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

Route::any('/boars', ['as'=>'boars', 'uses'=>'BoarsController@index']);
Route::get('/boars/add/{auth}', function($auth){
    header('Access-Control-Allow-Headers: Origin, Access-Control-Allow-Origin');
    header("Access-Control-Allow-Origin: *");

    $boars = new \App\Http\Controllers\BoarsController();
    $boars->add($auth);
});

Route::any('/boars/bookmarks', ['as'=>'boars/bookmarks', 'uses'=>'BoarsController@get_bookmarks']);
Route::any('/boars/register', ['as'=>'boars/register', 'uses'=>'BoarsController@register_view']);
Route::post('/boars/register/create', ['as'=>'boars/register/create', 'uses'=>'BoarsController@register']);
Route::any('/boars/login', ['as'=>'boars/login', 'uses'=>'BoarsController@login_view']);
Route::any('/boars/login_api', ['as'=>'boars/login_api', 'uses'=>'BoarsController@login_api']);