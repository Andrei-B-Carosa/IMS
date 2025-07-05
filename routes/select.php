<?php

use App\Service\Select\CompanyLocationOptions;
use App\Service\Select\ItemOption;
use App\Service\Select\DepartmentOptions;
use App\Service\Select\EmployeeOptions;
use App\Service\Select\FilterYearOptions;
use App\Service\Select\ItemTypeOption;
use App\Service\Select\PositionOptions;
use App\Service\Select\TagNumberOptions;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'select'], function() {

    Route::post('/item', [ItemOption::class, 'list']);
    Route::post('/item-type', [ItemTypeOption::class, 'list']);
    Route::post('/department', [DepartmentOptions::class, 'list']);
    Route::post('/position', [PositionOptions::class, 'list']);
    Route::post('/employee', [EmployeeOptions::class, 'list']);
    Route::post('/company-location', [CompanyLocationOptions::class, 'list']);
    Route::post('/filter-year', [FilterYearOptions::class, 'list']);
    Route::post('/tag-number', [TagNumberOptions::class, 'list']);
;

});
