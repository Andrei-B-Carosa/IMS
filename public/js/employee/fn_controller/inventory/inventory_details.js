'use strict';
import { data_bs_components, modal_state, page_state } from "../../../global.js";
import { get_company_location } from "../../../global/select.js";
import {RequestHandler} from "../../../global/request.js";
import { fvGeneralDetails, fvSystemUnitDetails } from "../../fv_controller/inventory/inventory_details.js";
import { dtItemLogs } from "../../dt_controller/inventory/inventory_details.js";

export var InventoryDetailsController = function (page, param) {

    let _page = $('.page-inventory-details');

    fvGeneralDetails('',param);
    fvSystemUnitDetails('',param);
    dtItemLogs('item-logs',param).init();

    data_bs_components();

    $(async function () {

        page_block.block();
        // inventory-list_table
        setTimeout(() => {
            page_block.release();
        }, 500);

    });
}
