<?php

use App\Service\Select\ItemOption;
use App\Service\Select\DepartmentOptions;
use App\Service\Select\EmployeeOptions;
use App\Service\Select\PositionOptions;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'select'], function() {

    Route::post('/item', [ItemOption::class, 'list']);
    Route::post('/department', [DepartmentOptions::class, 'list']);
    Route::post('/position', [PositionOptions::class, 'list']);
    Route::post('/employee', [EmployeeOptions::class, 'list']);


});
