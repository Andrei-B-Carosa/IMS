'use strict';
import { modal_state, page_state } from "../../../global.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import { dtInventoryList } from "../../dt_controller/inventory/inventory_list.js";

export var InventoryListController = function (page, param) {

    let _page = $('.page-inventory-list');

    dtInventoryList('inventory-list').init();

    $(async function () {

        if (!page_block.isBlocked()) {
            page_block.block();
        }
        setTimeout(() => {
            page_block.release();
        }, 500);

    });
}
