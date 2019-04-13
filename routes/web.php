<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/threads');
});

Auth::routes(['verify' => true]);

Route::get('/home', function () {
    return redirect('threads');
});

Route::get('/threads/create', 'ThreadsController@create')
    ->middleware('verified');

Route::get('/threads', 'ThreadsController@index')->name('threads');

Route::get('/threads/{channel}/{thread}', 'ThreadsController@show');

Route::get('/threads/{channel}', 'ThreadsController@index');

Route::delete('/threads/{channel}/{thread}', 'ThreadsController@destroy');

Route::post('/threads', 'ThreadsController@store');

Route::get('/threads/{channel}/{thread}/replies', 'RepliesController@index');

Route::post('/threads/{channel}/{thread}/replies', 'RepliesController@store');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@store')
    ->middleware('auth');

Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionsController@destroy')
    ->middleware('auth');

Route::post('/replies/{reply}/favorites', 'FavoritesController@store');

Route::delete('/replies/{reply}/favorites', 'FavoritesController@destroy');

Route::delete('/replies/{reply}', 'RepliesController@destroy');

Route::patch('/replies/{reply}', 'RepliesController@update')->name('replies.destroy');

Route::get('/profiles/{user}', 'ProfilesController@show')->name('profile');

Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationsController@destroy');

Route::get('/profiles/{user}/notifications/', 'UserNotificationsController@index');

Route::middleware('auth')
    ->post('/api/users/{user}/avatar', 'Api\UserAvatarController@store')
    ->name('avatar');

Route::middleware('auth')
    ->post('/api/replies/{reply}/best', 'Api\BestRepliesController@store')->name('best-replies.store');
