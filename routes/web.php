<?php

use App\Http\Controllers\AccessController\EmployeeLogin;
use App\Http\Controllers\RegisterDeviceController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'device','controller' => RegisterDeviceController::class], function () {
    Route::get('/fetch', 'fetch')->name('fetch.device');
    Route::get('/register', 'register')->name('register.device');
    Route::post('/update', 'update')->name('register.update');
});


Route::group(['middleware' => 'prevent.verified.user','controller' => EmployeeLogin::class], function () {
    Route::get('/login', 'form')->name('employee.form.login');
    Route::post('/login', 'login')->name('employee.login');
    Route::post('/logout', 'logout')->name('employee.logout');
});



