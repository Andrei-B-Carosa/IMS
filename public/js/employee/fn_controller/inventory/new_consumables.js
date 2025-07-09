'use strict';
import { data_bs_components, modal_state, page_state } from "../../../global.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import { get_company_location } from "../../../global/select.js";
import { fvNewConsumableController } from "../../fv_controller/inventory/new_consumables.js";
import { fvNewInventory } from "../../fv_controller/inventory/new_inventory.js";

export var NewConsumablesController = function (page, param) {

    let _page = $('.page-new-consumables');

    get_company_location('select[name="company_location"]','','options',1)
    fvNewConsumableController();
    $(async function () {

        page_block.block();

        setTimeout(() => {
            data_bs_components();
            page_block.release();
        }, 500);

    });
}
