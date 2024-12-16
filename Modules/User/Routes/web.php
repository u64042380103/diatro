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
// Route::prefix('users')->middleware(User_group::class)->group(function() {
Route::prefix('users')->group(function() {
    Route::get('/', 'UserController@index')->name('users.index');
    Route::get('/{id}/edit', 'UserController@edit')->name('users.edit');
    // Route::post('/', 'UserController@store')->name('users.store');
    Route::get('/create', 'UserController@create')->name('users.create');
    Route::post('/insert','UserController@insert')->name('users.insert');
    Route::get('/delete/{id}','UserController@delete')->name('users.delete');
    Route::post('/update/{id}','UserController@update')->name('users.update');
    Route::post('/change_img_user/{id}','UserController@change_img_user')->name('users.change_img_user');
    Route::delete('/massDelete', 'UserController@massDelete')->name('users.massDelete');
    Route::post('/change_img_pro', 'UserController@change_img_pro')->name('users.change_img_pro');
    Route::post('{id}/comment_insert','UserController@comment_insert')->name('users.comment_insert');


    Route::get('/{id}/users_review', 'User_reviewController@index')->name('users_review.index');
    Route::get('/{id}/create/users_review', 'User_reviewController@create')->name('users_review.create');
    Route::post('{id}/insert/users_review','User_reviewController@insert')->name('users_review.insert');
    Route::post('/change/users_review', 'User_reviewController@change')->name('users_review.change');
    Route::delete('/massDelete/users_review', 'User_reviewController@massDelete')->name('users_review.massDelete');
    Route::get('/{id}/edit/users_review', 'User_reviewController@edit')->name('users_review.edit');
    Route::post('/{id}/update/users_review','User_reviewController@update')->name('users_review.update');
    Route::get('/{id}/delete/users_review','User_reviewController@delete')->name('users_review.delete');

});

