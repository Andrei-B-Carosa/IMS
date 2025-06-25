'use strict';

import { AccountabilityListController } from './fn_controller/accountability/accountability.js';
import { AccountabilityDetailsController } from './fn_controller/accountability/accountability_details.js';
import { NewAccountabilityController } from './fn_controller/accountability/new_accountability.js';
import { InventoryListController } from './fn_controller/inventory/inventory.js';
import { InventoryDetailsController } from './fn_controller/inventory/inventory_details.js';
import { NewInventoryController } from './fn_controller/inventory/new_inventory.js';
import { MaterialIssuanceListController } from './fn_controller/material_issuance/material_issuance.js';
import { MaterialIssuanceDetailsController } from './fn_controller/material_issuance/material_issuance_details.js';
import { NewMaterialIssuanceController } from './fn_controller/material_issuance/new_material_issuance.js';
import { FileMaintenanceController } from './fn_controller/settings/file_maintenance/file_maintenance.js';
import { ItemDetailsController } from './fn_controller/settings/file_maintenance/item_details.js';
import { NewItemController } from './fn_controller/settings/file_maintenance/new_item.js';
import { UserManagementController } from './fn_controller/settings/user_management/user_management.js';
import { page_content } from './page.js';
import {EmployeeMasterlistController} from './fn_controller/settings/employee_list/employee_masterlist.js'
import { EmployeeDetailsController } from './fn_controller/settings/employee_list/employee_details/employee_details.js';
import { DashboardController } from './fn_controller/dashboard/dashboard.js';
import { DevicePerSite } from './fn_controller/report/devices_per_site.js';
import { DevicePerDepartment } from './fn_controller/report/devices_per_department.js';
import { IssuedDevicesController } from './fn_controller/report/issued_devices.js';
import { DeviceProcurementController } from './fn_controller/report/device_procurement.js';

async function init_page(_default) {
    let pathname = window.location.pathname;
    let page = pathname.split("/")[1] || _default;
    let param = false;
    let url = window.location.pathname;
    if(url.split('/')[2] !== null && typeof url.split('/')[2] !== 'undefined'){
        param =  pathname.split("/")[2];
    }

    load_page(page, param).then((res) => {
        if (res) {
            $(`.navbar[data-page='${page}']`).addClass('here');
        }
    })
}

export async function load_page(page, param=null){
    try {
        const pageContent = await page_content(page, param);
        if (pageContent) {
            // $('.current-directory').text(page);
            await page_handler(page, param);
            return true;
        } else {
            return false;
        }
    } catch (error) {
        console.error('Error in load_page:', error);
        return false;
    }
}

export async function page_handler(page,param=null){
    page = page.replace(/-/g, '_');
    const handler = _handlers[page];
    if (handler) {
        handler(page, param);
    } else {
        console.log("No handler found for this page");
    }
}

const _handlers = {
    dashboard: (page, param) => DashboardController(page, param),
    accountability: (page, param) => AccountabilityListController(page, param),
    accountability_details: (page, param) => AccountabilityDetailsController(page, param),
    new_accountability:(page,param) => NewAccountabilityController(page,param),
    inventory:(page,param) => InventoryListController(page,param),
    inventory_details:(page,param) => InventoryDetailsController(page,param),
    new_inventory:(page,param) => NewInventoryController(page,param),
    material_issuance:(page,param) => MaterialIssuanceListController(page,param),
    new_material_issuance:(page,param) => NewMaterialIssuanceController(page,param),
    material_issuance_details:(page,param) => MaterialIssuanceDetailsController(page,param),
    file_maintenance:(page,param)=>FileMaintenanceController(page,param),
    item_details:(page,param)=>ItemDetailsController(page,param),
    new_item:(page,param)=>NewItemController(page,param),
    user_management:(page,param)=>UserManagementController(page,param),
    employee_list:(page,param)=>EmployeeMasterlistController(page,param),
    employee_details:(page,param)=>EmployeeDetailsController(page,param),
    devices_per_site:(page,param)=>DevicePerSite(page,param),
    devices_per_department:(page,param)=>DevicePerDepartment(page,param),
    issued_devices:(page,param)=>IssuedDevicesController(page,param),
    device_procurement:(page,param)=>DeviceProcurementController(page,param),
};

jQuery(document).ready(function() {
    init_page('dashboard');
    $(".navbar").on("click", function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        let page = $(this).data('page');
        let link = $(this).data('link');
        let title = $(this).find('.menu-title').text();
        let _this = $(this);
        load_page(page).then((res) => {
            if (res) {
                if(_this.hasClass('sub-menu')){
                    $('.navbar ,.menu-sub').removeClass('here');
                    _this.parent().parent().addClass('here');
                }else{
                    $('.navbar').removeClass('here');
                    _this.addClass('here');
                }
            }
        });
    });
})
