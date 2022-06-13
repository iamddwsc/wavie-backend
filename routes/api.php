<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('signin', 'Api\AuthController@signin');
Route::post('signup', 'Api\AuthController@signup');
Route::get('signout', 'Api\AuthController@signout')->middleware('jwtAuth');
Route::post('save_user_data', 'Api\AuthController@saveUserData')->middleware('jwtAuth');

Route::get('home', 'Api\HomeController@home');
Route::get('get1', 'Api\HomeController@getFirstItem');
Route::get('get10', 'Api\HomeController@getFirst10Items');
Route::get('popular', 'Api\HomeController@getPopular');
Route::get('movie_today', 'Api\HomeController@getMovieToday');

Route::post('movie', 'Api\HomeController@getMovieDetail');
Route::post('search', 'Api\HomeController@getSearchedMovies');

Route::post('/movie/comments', 'Api\HomeController@comments');
Route::post('/movie/comments/create', 'Api\HomeController@create')->middleware('jwtAuth');
Route::post('/movie/comments/update', 'Api\HomeController@update')->middleware('jwtAuth');
Route::post('/movie/comments/delete', 'Api\HomeController@delete')->middleware('jwtAuth');

Route::post('movie_c', 'Api\HomeController@movieWithComments');

Route::get('test', 'Api\scrapDB@modifyCSV');