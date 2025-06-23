'use strict';
import { modal_state, page_state } from "../../../global.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import { dtInventoryList } from "../../dt_controller/inventory/inventory_list.js";
import { dtIssuedDevices } from "../../dt_controller/reports/issued_devices.js";
import { fvRepairRequest } from "../../fv_controller/inventory/inventory.js";

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

    });
}
