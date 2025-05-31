'use strict';
import { data_bs_components, modal_state, page_state } from "../../../global.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import { fvGeneralDetails, fvSystemUnitDetails } from "../../fv_controller/inventory/inventory_details.js";

export var InventoryDetailsController = function (page, param) {

    let _page = $('.page-inventory-details');

    fvGeneralDetails('',param);
    fvSystemUnitDetails('',param);
    data_bs_components();

    $(async function () {

        page_block.block();
        // inventory-list_table
        setTimeout(() => {
            page_block.release();
        }, 500);

    });
}
