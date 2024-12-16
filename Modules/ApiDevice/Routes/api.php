<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\Http\Controllers\ApiDeviceController;

Route::middleware('auth:api')->get('/apidevice', function (Request $request) {
    return $request->user();
});


Route::get('/deviceinfo/{device_id}', 'ApiDeviceController@deviceinfo');
