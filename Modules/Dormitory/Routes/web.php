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
use App\Http\Controllers\pdfGenerateController;
use Illuminate\Support\Facades\Route;

Route::prefix('dormitorys')->name('dormitorys.')->group(function() {

    // Dormitory
    Route::get('/', 'DormitoryController@index')->name('index');
    Route::get('/{code}/show', 'DormitoryController@show')->name('show');
    Route::get('/create', 'DormitoryController@create')->name('create');
    Route::post('/insert', 'DormitoryController@insert')->name('insert');
    Route::get('/{code}/edit', 'DormitoryController@edit')->name('edit');
    Route::post('/{id}/update', 'DormitoryController@update')->name('update');
    Route::get('/delete/{id}','DormitoryController@delete')->name('delete');
    Route::delete('/massDelete', 'DormitoryController@massDelete')->name('massDelete');
    Route::get('/{id}/comment','DormitoryController@comment')->name('comment');
    Route::post('/{id}/insert_comment','DormitoryController@insert_comment')->name('insert_comment');

    Route::post('/insert/{id}', 'FacilitateController@insert')->name('insert_Facilitate');
    Route::get('/edit/{id}', 'FacilitateController@edit')->name('edit_Facilitate');
    Route::post('/update/{id}', 'FacilitateController@update')->name('update_Facilitate');
    Route::get('/{id}/delete','FacilitateController@delete')->name('delete_Facilitate');

    
    //User
    Route::get('/{code}/users', 'UserController@index')->name('users.index');
    Route::get('/{code}/users/create', 'UserController@create')->name('users.create');
    Route::post('/{code}/users/insert', 'UserController@insert')->name('users.insert');
    Route::get('/{id}/users/edit', 'UserController@edit')->name('users.edit');
    Route::post('/users/fetchUser', 'UserController@fetchUser')->name('users.fetchUser');
    Route::post('/{id}/users/update', 'UserController@update')->name('users.update');
    Route::get('/{id}/users/delete','UserController@delete')->name('users.delete');
    Route::delete('/users/massDelete', 'UserController@massDelete')->name('users.massDelete');
    Route::get('/{id}/users/show', 'UserController@show')->name('users.show');
    Route::get('/{id}/users/exit', 'UserController@exit')->name('users.exit');

    

    //Room
    Route::get('/{code}/rooms', 'RoomController@index')->name('rooms.index');
    Route::get('/{code}/rooms/create', 'RoomController@create')->name('rooms.create');
    Route::post('/{code}/rooms/insert', 'RoomController@insert')->name('rooms.insert');
    Route::get('/{id}/rooms/edit', 'RoomController@edit')->name('rooms.edit');
    Route::get('/{id}/rooms/show', 'RoomController@show')->name('rooms.show');
    Route::post('/{id}/rooms/update', 'RoomController@update')->name('rooms.update');
    Route::get('/{id}/rooms/delete','RoomController@delete')->name('rooms.delete');
    
    Route::get('/{id}/rooms_details', 'RoomControllerdetails@index')->name('rooms_details.index');
    Route::get('/{id}/rooms_details/create', 'RoomControllerdetails@create')->name('rooms_details.create');
    Route::post('/{id}/rooms_details/insert', 'RoomControllerdetails@insert')->name('rooms_details.insert');
    Route::get('/{id}/rooms_details/edit', 'RoomControllerdetails@edit')->name('rooms_details.edit');
    Route::post('/{id}/rooms_details/update', 'RoomControllerdetails@update')->name('rooms_details.update');
    Route::get('/{id}/rooms_details/delete','RoomControllerdetails@delete')->name('rooms_details.delete');
    Route::delete('{id}/rooms_details/massDelete', 'RoomControllerdetails@massDelete')->name('rooms_details.massDelete');
    
    Route::get('/{id}/rooms_details/create_repair', 'RoomControllerdetails@create_repair')->name('rooms_details.create_repair');
    Route::post('/{id}/rooms_details/insert_repair', 'RoomControllerdetails@insert_repair')->name('rooms_details.insert_repair');
    Route::get('/{id}/rooms_details/edit_repair', 'RoomControllerdetails@edit_repair')->name('rooms_details.edit_repair');
    Route::post('/{id}/rooms_details/update_repair', 'RoomControllerdetails@update_repair')->name('rooms_details.update_repair');
    

    //Meter
    Route::get('/{code}/meters', 'MeterController@index')->name('meters.index');
    Route::get('/{id_room}/show/meters', 'MeterController@show')->name('meters.show');
    Route::post('/{id_room}/insert/meters', 'MeterController@insert')->name('meters.insert');
    Route::get('/{id}/edit/meters', 'MeterController@edit')->name('meters.edit');
    Route::post('/{id}/update/meters', 'MeterController@update')->name('meters.update');
    Route::get('/{id}/edit_payment/meters', 'MeterController@edit_payment')->name('meters.edit_payment');
    Route::post('/{id}/update_payment/meters', 'MeterController@update_payment')->name('meters.update_payment');
    
    //water
    Route::get('/{code}/water', 'WaterController@index')->name('water.index');


    //Billing
    Route::get('/{code}/billings', 'BillingController@index')->name('billings.index');
    Route::get('/{id}/edit/billings', 'BillingController@edit')->name('billings.edit');
    Route::post('/{id}/update/billings', 'BillingController@update')->name('billings.update');
    Route::get('/{id}/show/billings', 'BillingController@show')->name('billings.show');
    Route::get('/{id}/delete/billings','BillingController@delete')->name('billings.delete');
    Route::post('/{id}/settings/billings', 'BillingController@settings')->name('billings.settings');
    Route::post('/{id}/settings_update/billings', 'BillingController@settings_update')->name('billings.settings_update');
    Route::get('/{id}/change_billings/billings', 'BillingController@change_billings')->name('billings.change_billings');
    Route::get('/{id}/insert/billings', 'BillingController@insert')->name('billings.insert');
    Route::delete('/massDelete/billings', 'BillingController@massDelete')->name('billings.massDelete');
    
    
    //Billing_month
    Route::get('/{id}/billings_month', 'BillingController_month@index')->name('billings_month.index');
    Route::get('/{id}/add/billings_month', 'BillingController_month@add')->name('billings_month.add');
    Route::get('/{id}/before/billings_month', 'BillingController_month@before')->name('billings_month.before');
    Route::get('/{id}/create/billings_month', 'BillingController_month@create')->name('billings_month.create');
    Route::post('/{id}/insert_before/billings_month', 'BillingController_month@insert_before')->name('billings_month.insert_before');
    Route::post('/{id}/insert/billings_month', 'BillingController_month@insert')->name('billings_month.insert');
    Route::get('/{id}/delete/billings_month','BillingController_month@delete')->name('billings_month.delete');
    Route::get('/{id}/edit/billings_month', 'BillingController_month@edit')->name('billings_month.edit');
    Route::post('/{id}/update/billings_month', 'BillingController_month@update')->name('billings_month.update');
    Route::delete('/massDelete/billings_month', 'BillingController_month@massDelete')->name('billings_month.massDelete');
    Route::get('/{id}/generate_pdf/billings_month', 'BillingController_month@generatePDF')->name('billings_month.generate_pdf');



    //LeaseAgreement
    Route::get('/{code}/lease_agreements', 'LeaseAgreementController@index')->name('lease_agreements.index');
    Route::get('/{code}/create/lease_agreements', 'LeaseAgreementController@create')->name('lease_agreements.create');
    Route::post('/{code}/insert/lease_agreements', 'LeaseAgreementController@insert')->name('lease_agreements.insert');
    Route::post('/lease_agreements/fetchUser', 'LeaseAgreementController@fetchUser')->name('lease_agreements.fetchUser');
    Route::get('/{id}/edit/lease_agreements', 'LeaseAgreementController@edit')->name('lease_agreements.edit');
    Route::post('/{id}/update/lease_agreements', 'LeaseAgreementController@update')->name('lease_agreements.update');
    Route::get('/{id}/show/lease_agreements', 'LeaseAgreementController@show')->name('lease_agreements.show');


    
    //Monthly_rent
    Route::get('/{id}/monthly_rent', 'Monthly_rentController@index')->name('monthly_rent.index');
    Route::get('/{id}/create/monthly_rent', 'Monthly_rentController@create')->name('monthly_rent.create');
    Route::post('/{id}/insert/monthly_rent', 'Monthly_rentController@insert')->name('monthly_rent.insert');
    Route::get('/{id}/edit/monthly_rent', 'Monthly_rentController@edit')->name('monthly_rent.edit');
    Route::post('/{id}/update/monthly_rent', 'Monthly_rentController@update')->name('monthly_rent.update');
    Route::delete('/monthly_rent/massDelete', 'Monthly_rentController@massDelete')->name('monthly_rent.massDelete');
    Route::get('/{id}/monthly_rent/delete','Monthly_rentController@delete')->name('monthly_rent.delete');

    //check_in
    Route::get('/{code}/check_in', 'Check_InController@index')->name('check_in.index');
    Route::get('/{code}/check_in/Waiting', 'Check_InController@Waiting')->name('check_in.Waiting');
    Route::get('/{code}/check_in/in', 'Check_InController@in')->name('check_in.in');
    Route::get('/{id}/check_in/change', 'Check_InController@change')->name('check_in.change');
    Route::get('/{id}/check_in/out', 'Check_InController@out')->name('check_in.out');

    
    //check_out
    Route::get('/{code}/check_out', 'Check_OutController@index')->name('check_out.index');
    Route::get('/{code}/check_out/Waiting', 'Check_OutController@Waiting')->name('check_out.Waiting');
    Route::get('/{code}/check_out/out', 'Check_OutController@out')->name('check_out.out');
    Route::get('/{id}/check_out/change', 'Check_OutController@change')->name('check_out.change');



    //Report
    Route::get('/{code}/reports', 'ReportController@index')->name('reports.index');




});
