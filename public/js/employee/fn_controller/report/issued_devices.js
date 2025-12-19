'use strict';

import { get_company_location, get_filter_inventory_year, get_item_type } from "../../../global/select.js";

// import { modal_state, page_state } from "../../../global.js";
// import {Alert} from "../../../global/alert.js";
// import {RequestHandler} from "../../../global/request.js";
// import { dtInventoryList } from "../../dt_controller/inventory/inventory_list.js";
import { dtIssuedDevices } from "../../dt_controller/reports/issued_devices.js";
// import { fvRepairRequest } from "../../fv_controller/inventory/inventory.js";

export var IssuedDevicesController = function (page, param) {

    let _page = $('.page-report');

    dtIssuedDevices('issued-devices').init();

    $(async function () {

        if (!page_block.isBlocked()) {
            page_block.block();
        }
        setTimeout(() => {
            page_block.release();
        }, 500);

        get_company_location(`select[name="filter_location"]`,'','filter',1);
        get_item_type('select[name="filter_category"]','','filter_item_type',1);
        get_filter_inventory_year('select[name="filter_year"]','','filter_inventory_year',1);

    });
}
