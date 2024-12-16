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

Route::prefix('usergroup')->group(function() {
    Route::get('/', 'UserGroupController@index')->name('usersgroup.index');
    Route::get('/create', 'UserGroupController@create')->name('usersgroup.create');
    Route::get('/{id}/edit', 'UserGroupController@edit')->name('usersgroup.edit');
    Route::get('/{id}/show', 'UserGroupController@show')->name('usersgroup.show');
    Route::post('/insert','UserGroupController@insert')->name('usersgroup.insert');
    Route::get('/delete/{id}','UserGroupController@delete')->name('usersgroup.delete');
    Route::post('/update/{id}','UserGroupController@update')->name('usersgroup.update');
    Route::delete('/massDelete', 'UserGroupController@massDelete')->name('usersgroup.massDelete');
    Route::get('/out/{id}', 'UserGroupController@out')->name('usersgroup.out');
    Route::delete('/massout', 'UserGroupController@massout')->name('usersgroup.massout');
    Route::get('/add', 'UserGroupController@add')->name('usersgroup.add');
    Route::post('/fetchUser', 'UserGroupController@fetchUser')->name('usersgroup.fetchUser');
    Route::post('/make', 'UserGroupController@make')->name('usersgroup.make');
    
});

