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

Route::prefix('controlsystem')->group(function() {
    Route::get('/{id}', 'ControlSystemController@index')->name('device.index');
    Route::get('/settings/{id}', 'ControlSystemController@settings')->name('device.settings');
    Route::get('/create/{id}', 'ControlSystemController@create')->name('device.create');
    Route::post('/insert','ControlSystemController@insert')->name('device.insert');
    Route::get('/delete/{id}','ControlSystemController@delete')->name('device.delete');
    Route::post('/update/{id}','ControlSystemController@update')->name('device.update');
    Route::delete('/massDelete', 'ControlSystemController@massDelete')->name('device.massDelete');


    Route::get('/change_set_time/{id}', 'ControlSystemController@change_set_time')->name('device.change_set_time');
    Route::get('/set_time/{id}', 'ControlSystemController@set_time')->name('device.set_time');
    Route::get('/create_time/{id}', 'ControlSystemController@create_time')->name('device.create_time');
    Route::post('/insert_time/{id}','ControlSystemController@insert_time')->name('device.insert_time');
    Route::get('/delete_time/{id}','ControlSystemController@delete_time')->name('device.delete_time');
    Route::delete('/massDelete_time', 'ControlSystemController@massDelete_time')->name('device.massDelete_time');
    Route::get('/settings_time/{id}', 'ControlSystemController@settings_time')->name('device.settings_time');
    Route::post('/update_time/{id}','ControlSystemController@update_time')->name('device.update_time');



    Route::get('/device_status_update', 'ControlSystemController@device_status_update')->withoutMiddleware(['auth']);


});
