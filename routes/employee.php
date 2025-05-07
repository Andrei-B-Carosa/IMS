<?php

use App\Http\Controllers\EmployeeController\Accountability\Details as AccountabilityDetails;
use App\Http\Controllers\EmployeeController\Accountability\Lists as AccountabilityLists;
use App\Http\Controllers\EmployeeController\Page;
use App\Service\UserRoute;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->controller(Page::class)->group(function () {

    Route::get('/', 'system_file');
    Route::post('/setup-page', 'setup_page');

    Route::get('/accountability-details/{id}', 'system_file');
    Route::get('/new-accountability','system_file')->name('employee.new_accountability');

    $routes = (new UserRoute())->getWebRoutes(2);
    if ($routes) {
        foreach ($routes as $row) {
            if ($row->is_layered) {
                foreach ($row->file_layer as $layer) {
                    Route::get('/'.$layer->href,'system_file');
                }
            }else{
                Route::get('/'.$row->href,'system_file');
            }
        }
    }

    Route::group(['prefix'=>'accountability'], function() {

        Route::controller(AccountabilityLists::class)->group(function() {
            Route::post('/list', 'list');
            Route::post('/create', 'create');
        });

    });

    Route::controller(AccountabilityDetails::class)->group(function() {

            Route::post('/new-accountability/register', 'new_accountability');

        Route::group(['prefix'=>'accountability-details'], function() {

            Route::post('/dt-issued-items', 'dt_issued_items');
            Route::post('/dt-issued-to', 'dt_issued_to');

            Route::post('/update-issued-items', 'update_issued_items');
            Route::post('/update-accountable-to', 'update_accountable_to');

            Route::post('/info-issued-items', 'info_issued_items');
            Route::post('/info-accountable-to', 'info_accountable_to');

            Route::post('/delete-issued-item', 'delete_issued_item');
            Route::post('/delete-issued-to', 'delete_issued_to');
        });
    });

});
