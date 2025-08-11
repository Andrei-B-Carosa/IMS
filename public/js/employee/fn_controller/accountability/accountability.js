'use strict';
import { modal_state, page_state } from "../../../global.js";
import {Alert} from "../../../global/alert.js";
import {RequestHandler} from "../../../global/request.js";
import { get_employee, get_tag_number } from "../../../global/select.js";
import { dtAccountability } from "../../dt_controller/accountability/accountability_list.js";
import { fvTransferAccountability } from "../../fv_controller/accountability/accountability.js";

export var AccountabilityListController = function (page, param) {

    let _page = $('.page-accountability');

    dtAccountability('accountability-list').init();
    fvTransferAccountability();
    get_employee(`select[name="received_by"]`,'',1);
    $(async function () {

        if (!page_block.isBlocked()) {
            page_block.block();
        }

        setTimeout(() => {
            page_block.release();
        }, 500);

    });
}
