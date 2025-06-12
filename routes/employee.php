<?php

use App\Http\Controllers\EmployeeController\Accountability\Details as AccountabilityDetails;
use App\Http\Controllers\EmployeeController\Accountability\Lists as AccountabilityLists;
use App\Http\Controllers\EmployeeController\Inventory\Details as InventoryDetails;
use App\Http\Controllers\EmployeeController\Inventory\Lists as InventoryLists;
use App\Http\Controllers\EmployeeController\MaterialIssuance\Details as MaterialIssuanceDetails;
use App\Http\Controllers\EmployeeController\MaterialIssuance\Lists as MaterialIssuanceLists;
use App\Http\Controllers\EmployeeController\Page;
use App\Http\Controllers\EmployeeController\Settings\EmployeeList\AccountSecurity\AccountDetails;
use App\Http\Controllers\EmployeeController\Settings\EmployeeList\EmployeeDetails;
use App\Http\Controllers\EmployeeController\Settings\EmployeeList\EmployeeMasterlist;
use App\Http\Controllers\EmployeeController\Settings\EmployeeList\EmploymentDetails\EmploymentDetails;
use App\Http\Controllers\EmployeeController\Settings\EmployeeList\PersonalData\DocumentAttachments;
use App\Http\Controllers\EmployeeController\Settings\EmployeeList\PersonalData\EducationalBackground;
use App\Http\Controllers\EmployeeController\Settings\EmployeeList\PersonalData\FamilyBackground;
use App\Http\Controllers\EmployeeController\Settings\EmployeeList\PersonalData\PersonalInformation;
use App\Http\Controllers\EmployeeController\Settings\EmployeeList\PersonalData\References;
use App\Http\Controllers\EmployeeController\Settings\EmployeeList\PersonalData\WorkExperience;
use App\Http\Controllers\EmployeeController\Settings\FileMaintenance\CompanyLocation;
use App\Http\Controllers\EmployeeController\Settings\FileMaintenance\Item;
use App\Http\Controllers\EmployeeController\Settings\FileMaintenance\ItemBrand;
use App\Http\Controllers\EmployeeController\Settings\FileMaintenance\ItemSuppliers;
use App\Http\Controllers\EmployeeController\Settings\FileMaintenance\ItemType;
use App\Http\Controllers\EmployeeController\Settings\UserManagement\UserManagement;
use App\Http\Controllers\EmployeeController\Settings\EmployeeList\AccountSecurity\Tab as AccountSecurityTab;
use App\Http\Controllers\EmployeeController\Settings\EmployeeList\EmployeeRegistration;
use App\Http\Controllers\EmployeeController\Settings\EmployeeList\PersonalData\Tab as PersonalDataTab;

use App\Service\UserRoute;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth'])->controller(Page::class)->group(function () {

    Route::get('/', 'system_file');
    Route::post('/setup-page', 'setup_page');

    Route::get('/accountability-details/{id}', 'system_file');
    Route::get('/new-accountability','system_file')->name('employee.new_accountability');

    Route::get('/inventory-details/{id}', 'system_file');
    Route::get('/new-inventory','system_file')->name('employee.new_inventory');

    Route::get('/new-material-issuance','system_file')->name('employee.new_material_issuance');
    Route::get('/material-issuance-details/{id}', 'system_file');

    Route::get('/employee-details/{id}', 'system_file');

    Route::get('/register-employee', function(){
        return view('employee.pages.settings.employee_list.employee_registration.index');
    })->name('employee.register_employee');

    Route::get('/item-details/{id}', 'system_file');
    Route::get('/new-item', 'system_file');

    $routes = (new UserRoute())->getWebRoutes(2);
    if ($routes) {
        foreach ($routes as $row) {
            if ($row->is_layered) {
                foreach ($row->file_layer as $layer) {
                    Route::get('/'.$layer->system_layer->href,'system_file');
                }
            }else{
                Route::get('/'.$row->href,'system_file');
            }
        }
    }

    Route::controller(AccountabilityLists::class)->prefix('accountability')->group(function() {
        Route::post('/list', 'list');
        Route::post('/update', 'update');
    });

    Route::controller(AccountabilityDetails::class)->prefix('accountability-details')->group(function() {
        Route::post('/dt-available-items', 'dt_available_items');
        Route::post('/dt-available-personnel', 'dt_available_personnel');

        Route::post('/dt-issued-items', 'dt_issued_items');
        Route::post('/dt-issued-to', 'dt_issued_to');

        Route::post('/update_accountability','update_accountability');
        Route::post('/update-issued-items', 'update_issued_items');
        Route::post('/update-issued-to', 'update_issued_to');

        Route::post('/add-accountability-item','add_accountability_item');
        Route::post('/add-personnel','add_personnel');

        Route::post('/form','info_accountability');
        Route::post('/info-issued-items', 'info_issued_items');
        Route::post('/info-issued-to', 'info_issued_to');

        Route::post('/remove-issued-item', 'remove_issued_item');
        Route::post('/remove-issued-to', 'remove_issued_to');
    });

    Route::controller(InventoryLists::class)->prefix('inventory')->group(function() {
        Route::post('/dt', 'dt');
        Route::post('/update', 'update');
        Route::post('/download-qr', 'download_qr');
        // Route::post('/check-item-tag', 'check_item_tag');

        // Route::post('/request-repair', 'request_repair');
        Route::post('/update-repair', 'update_repair');
        Route::post('/repair-info', 'repair_info');

        Route::post('/delete', 'delete');
    });

    Route::controller(InventoryDetails::class)->prefix('inventory-details')->group(function() {
        Route::post('update-general-details', 'update_general_details');
        Route::post('update-item-details', 'update_item_details');

        Route::post('update-ram', 'update_ram');
        Route::post('update-storage', 'update_storage');
        Route::post('update-gpu', 'update_gpu');

        Route::post('dt-item-logs', 'dt_item_logs');

    });

    Route::controller(MaterialIssuanceLists::class)->prefix('material-issuance')->group(function() {
        Route::post('/list', 'list');
        Route::post('/update', 'update');

        Route::post('/check-item-quantity', 'check_item_quantity');
    });

    Route::controller(MaterialIssuanceDetails::class)->prefix('material-issuance-details')->group(function() {
        Route::post('/form','info_material_issuance');
        Route::post('/update-material-issuance', 'update_material_issuance');

        Route::post('/dt-issued-items', 'dt_issued_items');
        Route::post('/dt-issued-to', 'dt_issued_to');

        Route::post('/dt-available-items', 'dt_available_items');
        Route::post('/update-material-issuance-item','update_material_issuance_item');
        Route::post('/remove-issued-item', 'remove_issued_item');

    });

    Route::group(['prefix'=>'file-maintenance'], function() {
        Route::controller(Item::class)->prefix('item')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update-general-details', 'update_general_details');
            Route::post('/update-item-details', 'update_item_details');

            Route::post('/new-item', 'new_item');
            Route::post('/check-item-type', 'check_item_type');

            Route::post('/delete', 'delete');
            Route::post('/validate', 'validate');
        });

        Route::controller(ItemBrand::class)->prefix('item-brand')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete');

            Route::post('/info', 'info');
            Route::post('/validate', 'validate');
        });

        Route::controller(ItemType::class)->prefix('item-type')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete');

            Route::post('/info', 'info');
            Route::post('/validate', 'validate');
        });

        Route::controller(ItemSuppliers::class)->prefix('item-suppliers')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete');

            Route::post('/info', 'info');
            Route::post('/validate', 'validate');
        });

        Route::controller(CompanyLocation::class)->prefix('company-location')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete');

            Route::post('/info', 'info');
            Route::post('/validate', 'validate');
        });

    });

    Route::group(['prefix'=>'user-management'], function() {
        Route::controller(UserManagement::class)->prefix('role-list')->group(function() {
            Route::get('/list', 'list');
            Route::post('/update', 'update');
            Route::post('/update-system-file', 'update_system_file');
            Route::post('/update-file-layer', 'update_file_layer');

            Route::post('/delete', 'delete');

            Route::post('/employee-list', 'employee_list');
            Route::post('/user-list', 'user_list');
        });
    });

    Route::group(['prefix'=>'employee-list'], function() {
        Route::controller(EmployeeMasterlist::class)->prefix('employee_masterlist')->group(function() {
            Route::post('/dt', 'dt');
            Route::post('/restore', 'restore');
            Route::post('/archive', 'archive');
            Route::post('/update', 'update');
            Route::post('/delete', 'delete');
            Route::post('/emp_details', 'emp_details');
        });

        Route::group(['prefix'=>'employee-details'], function() {

            Route::controller(EmployeeDetails::class)->group(function() {
                Route::post('/tab', 'tab');
                Route::post('/form', 'form');
                Route::post('/update', 'update');
                Route::post('/delete', 'delete');
            });

            Route::group(['prefix'=>'personal_data'], function() {

                Route::post('/tab', [PersonalDataTab::class, 'tab']);

                Route::post('/personal_information/update', [PersonalInformation::class, 'update']);
                Route::post('/family_background/update', [FamilyBackground::class, 'update']);

                Route::controller(EducationalBackground::class)->prefix('educational_background')->group(function() {
                    Route::post('/dt', 'dt');
                    Route::post('/update', 'update');
                    Route::post('/delete', 'delete');
                    Route::post('/check_document', 'check_document');

                    Route::post('/info', 'info');
                });

                Route::controller(WorkExperience::class)->prefix('work_experience')->group(function() {
                    Route::post('/dt', 'dt');
                    Route::post('/update', 'update');
                    Route::post('/delete', 'delete');
                    Route::post('/check_document', 'check_document');

                    Route::post('/info', 'info');
                });

                Route::controller(DocumentAttachments::class)->prefix('document_attachment')->group(function() {
                    Route::post('/dt', 'dt');
                    Route::post('/update', 'update');
                    Route::post('/delete', 'delete');
                    Route::post('/download_document', 'download_document');
                    Route::post('/view_document', 'view_document');
                });

                Route::controller(References::class)->prefix('references')->group(function() {
                    Route::post('/dt', 'dt');
                    Route::post('/update', 'update');
                    Route::post('/delete', 'delete');

                    Route::post('/info', 'info');
                });
            });

            Route::post('/employment_details/update', [EmploymentDetails::class, 'update']);

            Route::group(['prefix'=>'account_security'], function() {
                Route::post('/tab', [AccountSecurityTab::class, 'tab']);
                Route::post('/update', [AccountDetails::class, 'update']);
            });

        });

        Route::controller(EmployeeRegistration::class)->prefix('employee-registration')->group(function() {
            Route::post('/form', 'form');
            Route::post('/update', 'update');
        });
    });

});

