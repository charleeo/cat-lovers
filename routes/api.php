<?php

use Illuminate\Support\Facades\Route;



Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::get('users', 'API\UserController@userDetails');
Route::get('user/{id}', 'API\UserController@getUser');

Route::get('movies', 'API\MovieController@listMovies');
// list out movies by user
Route::get('user-movies/{user_id}', 'API\MovieController@userMovieLists');

//get asingle movie
Route::get('movies/{movie_id}', 'API\MovieController@singleMovie');

Route::group(['middleware' => 'auth:api'], function(){
    Route::post('movies', 'API\MovieController@saveMovie')->name('save-movie');
    //update the movie collection
    Route::put('movies/{movie_id}', 'API\MovieController@updateMovie');
    Route::get('user/logout', 'API\UserController@logout');
});
