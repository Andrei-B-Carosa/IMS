'use strict';
import { modal_state, page_state } from "../../../global.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import { get_cellphone_count, get_laptop_count, get_monitor_count, get_printer_count, get_router_count, get_system_unit_count } from "../../../global/widget.js";
import { dtInventoryList } from "../../dt_controller/inventory/inventory_list.js";
import { fvRepairRequest } from "../../fv_controller/inventory/inventory.js";

export var DashboardController = function (page, param) {

    let _page = $('.page-dashboard');

    get_system_unit_count();
    get_monitor_count();
    get_laptop_count();
    get_printer_count();
    get_cellphone_count();
    get_router_count();

    $(async function () {

        if (!page_block.isBlocked()) {
            page_block.block();
        }
        setTimeout(() => {
            page_block.release();
        }, 500);

    });
}
