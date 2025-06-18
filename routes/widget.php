<?php

use App\Service\Select\CompanyLocationOptions;
use App\Service\Select\ItemOption;
use App\Service\Select\DepartmentOptions;
use App\Service\Select\EmployeeOptions;
use App\Service\Select\PositionOptions;
use App\Service\Widget\CellphoneCount;
use App\Service\Widget\LaptopCount;
use App\Service\Widget\PrinterCount;
use App\Service\Widget\SystemUnitCount;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'widget'], function() {

     Route::get('/system-unit-count', [SystemUnitCount::class, 'show']);
     Route::get('/printer-count', [PrinterCount::class, 'show']);
     Route::get('/laptop-count', [LaptopCount::class, 'show']);
     Route::get('/cellphone-count', [CellphoneCount::class, 'show']);
});
